<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\DbService;
use SD\Filter;

/**
 * @covers \SD\Filter
 */
class FilterTest extends TestCase {

	private function makeFilter( $requiredFilters ): Filter {
		$db = $this->createMock( DbService::class );
		return new Filter( $db, 'TestFilter', 'TestProperty', null, $requiredFilters, null );
	}

	public function testRequiredFiltersReturnsEmptyArrayWhenNull(): void {
		$filter = $this->makeFilter( null );

		$this->assertSame( [], $filter->requiredFilters() );
	}

	public function testRequiredFiltersReturnsArrayWhenPassedArray(): void {
		$filter = $this->makeFilter( [ 'Country' ] );

		$this->assertSame( [ 'Country' ], $filter->requiredFilters() );
	}

	public function testRequiredFiltersReturnsArrayWhenPassedString(): void {
		$filter = $this->makeFilter( 'Country' );

		$this->assertSame( [ 'Country' ], $filter->requiredFilters() );
	}

	public function testRequiredFiltersStringIsIterableAndMatchesFilterName(): void {
		$filter = $this->makeFilter( 'Country' );

		$found = false;
		foreach ( $filter->requiredFilters() as $required ) {
			if ( $required === 'Country' ) {
				$found = true;
			}
		}

		$this->assertTrue( $found, 'Expected to find "Country" in requiredFilters(), not single characters' );
	}

}
