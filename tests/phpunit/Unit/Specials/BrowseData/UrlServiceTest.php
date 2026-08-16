<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Specials\BrowseData;

use MediaWiki\Request\WebRequest;
use PHPUnit\Framework\TestCase;
use SD\AppliedFilter;
use SD\DbService;
use SD\Filter;
use SD\Specials\BrowseData\DrilldownQuery;
use SD\Specials\BrowseData\UrlService;

/**
 * @covers \SD\Specials\BrowseData\UrlService
 */
class UrlServiceTest extends TestCase {

	private function makeFilter( string $name ): Filter {
		$db = $this->createMock( DbService::class );
		return new Filter( $db, $name, $name, null, null, null, 'string' );
	}

	private function makeService( DrilldownQuery $query ): UrlService {
		$request = $this->createMock( WebRequest::class );
		$request->method( 'getCheck' )->willReturn( false );
		return new UrlService( '/wiki/Special:BrowseData', $request, $query );
	}

	public function testGetLinkParametersKeysSearchTermsByOwnFilterIndexNotLastFilterProcessed(): void {
		$multiValueFilter = $this->makeFilter( 'Color' );
		$multiValueApplied = AppliedFilter::create( $multiValueFilter, [ 'Red', 'Blue' ] );

		$searchFilter = $this->makeFilter( 'Description' );
		$searchApplied = AppliedFilter::create( $searchFilter, [], [ 'foo' ] );

		$db = $this->createMock( DbService::class );
		$query = new DrilldownQuery(
			$db, 'Category1', null, [ $multiValueFilter, $searchFilter ],
			[ $multiValueApplied, $searchApplied ], []
		);

		$params = $this->makeService( $query )->getLinkParameters();

		// The search-term key must be built from Description's own index (1), not from
		// whatever index the multi-value Color filter's inner loop last reached.
		$this->assertArrayHasKey( '_search_Description[1]', $params );
		$this->assertSame( 'foo', $params['_search_Description[1]'] );
	}

	public function testGetLinkParametersUsesFlatKeyForSingleValueFilter(): void {
		$filter = $this->makeFilter( 'Color' );
		$applied = AppliedFilter::create( $filter, 'Red' );

		$db = $this->createMock( DbService::class );
		$query = new DrilldownQuery( $db, 'Category1', null, [ $filter ], [ $applied ], [] );

		$params = $this->makeService( $query )->getLinkParameters();

		$this->assertSame( 'Red', $params['Color'] );
	}

	public function testGetLinkParametersUsesBracketedKeysForMultiValueFilter(): void {
		$filter = $this->makeFilter( 'Color' );
		$applied = AppliedFilter::create( $filter, [ 'Red', 'Blue' ] );

		$db = $this->createMock( DbService::class );
		$query = new DrilldownQuery( $db, 'Category1', null, [ $filter ], [ $applied ], [] );

		$params = $this->makeService( $query )->getLinkParameters();

		$this->assertSame( 'Red', $params['Color[0]'] );
		$this->assertSame( 'Blue', $params['Color[1]'] );
	}

}
