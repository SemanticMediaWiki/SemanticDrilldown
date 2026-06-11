<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Specials\BrowseData;

use PHPUnit\Framework\TestCase;
use SD\Specials\BrowseData\NumberUtils;

/**
 * @covers \SD\Specials\BrowseData\NumberUtils
 */
class NumberUtilsTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		// Simulate what extension.json config registration sets via config_prefix "sdg".
		$GLOBALS['sdgNumRangesForNumberFilters'] = 6;
	}

	protected function tearDown(): void {
		parent::tearDown();
		unset( $GLOBALS['sdgNumRangesForNumberFilters'] );
	}

	public function testGenerateFilterValuesWithFewUniqueValuesReturnsIndividualBuckets(): void {
		// 5 unique values ≤ NumRangesForNumberFilters (6) → individual buckets, not ranges
		$numbers = [ 1, 1, 2, 3, 4, 5 ];

		$result = NumberUtils::generateFilterValuesFromNumbers( $numbers );

		$this->assertCount( 5, $result );
		foreach ( $result as $bucket ) {
			$this->assertNull( $bucket['higherNumber'] );
		}
	}

	public function testGenerateFilterValuesWithManyUniqueValuesReturnsBuckets(): void {
		// 20 unique values > NumRangesForNumberFilters (6) → range buckets
		$numbers = range( 1, 20 );

		$result = NumberUtils::generateFilterValuesFromNumbers( $numbers );

		$this->assertNotEmpty( $result );
		$this->assertLessThanOrEqual( 6, count( $result ),
			'Number of buckets must not exceed NumRangesForNumberFilters' );
	}

}
