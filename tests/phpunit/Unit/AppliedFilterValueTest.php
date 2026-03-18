<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\AppliedFilterValue;
use SD\Filter;

/**
 * @covers \SD\AppliedFilterValue
 */
class AppliedFilterValueTest extends TestCase {

	// ---------------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------------

	private function filterWithType( string $type ): Filter {
		$filter = $this->createMock( Filter::class );
		$filter->method( 'propertyType' )->willReturn( $type );
		return $filter;
	}

	// ---------------------------------------------------------------------------
	// Tests – create(): plain text / special values
	// ---------------------------------------------------------------------------

	public function testCreateReplacesUnderscoresWithSpaces(): void {
		$fv = AppliedFilterValue::create( 'hello_world' );
		$this->assertSame( 'hello world', $fv->text );
	}

	public function testCreateNoneValue(): void {
		$fv = AppliedFilterValue::create( '_none' );
		$this->assertTrue( $fv->is_none );
		$this->assertFalse( $fv->is_other );
	}

	public function testCreateOtherValue(): void {
		$fv = AppliedFilterValue::create( '_other' );
		$this->assertTrue( $fv->is_other );
		$this->assertFalse( $fv->is_none );
	}

	public function testCreatePlainTextWithoutFilter(): void {
		$fv = AppliedFilterValue::create( 'some value' );
		$this->assertSame( 'some value', $fv->text );
		$this->assertFalse( $fv->is_none );
		$this->assertFalse( $fv->is_other );
		$this->assertFalse( $fv->is_numeric );
	}

	public function testCreateEmptyStringWithPageFilter(): void {
		$fv = AppliedFilterValue::create( '', $this->filterWithType( 'page' ) );
		$this->assertSame( '', $fv->text );
		$this->assertFalse( $fv->is_numeric );
		$this->assertNull( $fv->lower_limit );
		$this->assertNull( $fv->upper_limit );
	}

	// ---------------------------------------------------------------------------
	// Tests – create(): numeric ranges (non-date filter)
	// ---------------------------------------------------------------------------

	public function testCreateUpperLimitNumeric(): void {
		$fv = AppliedFilterValue::create( '<100', $this->filterWithType( 'page' ) );
		$this->assertTrue( $fv->is_numeric );
		$this->assertNull( $fv->lower_limit );
		$this->assertSame( '100', $fv->upper_limit );
	}

	public function testCreateUpperLimitWithCommasStripped(): void {
		$fv = AppliedFilterValue::create( '<1,000', $this->filterWithType( 'page' ) );
		$this->assertTrue( $fv->is_numeric );
		$this->assertSame( '1000', $fv->upper_limit );
	}

	public function testCreateUpperLimitNonNumericNotSetAsNumeric(): void {
		$fv = AppliedFilterValue::create( '<abc', $this->filterWithType( 'page' ) );
		$this->assertFalse( $fv->is_numeric );
		$this->assertNull( $fv->upper_limit );
	}

	public function testCreateLowerLimitNumeric(): void {
		$fv = AppliedFilterValue::create( '>50', $this->filterWithType( 'page' ) );
		$this->assertTrue( $fv->is_numeric );
		$this->assertSame( '50', $fv->lower_limit );
		$this->assertNull( $fv->upper_limit );
	}

	public function testCreateLowerLimitNonNumericNotSetAsNumeric(): void {
		$fv = AppliedFilterValue::create( '>abc', $this->filterWithType( 'page' ) );
		$this->assertFalse( $fv->is_numeric );
		$this->assertNull( $fv->lower_limit );
	}

	public function testCreateNumericRange(): void {
		$fv = AppliedFilterValue::create( '100-200', $this->filterWithType( 'page' ) );
		$this->assertTrue( $fv->is_numeric );
		$this->assertSame( '100', $fv->lower_limit );
		$this->assertSame( '200', $fv->upper_limit );
	}

	public function testCreateNumericRangeWithCommas(): void {
		$fv = AppliedFilterValue::create( '1,000-2,000', $this->filterWithType( 'page' ) );
		$this->assertTrue( $fv->is_numeric );
		$this->assertSame( '1000', $fv->lower_limit );
		$this->assertSame( '2000', $fv->upper_limit );
	}

	public function testCreateNonNumericRangeNotSetAsNumeric(): void {
		$fv = AppliedFilterValue::create( 'abc-def', $this->filterWithType( 'page' ) );
		$this->assertFalse( $fv->is_numeric );
		$this->assertNull( $fv->lower_limit );
		$this->assertNull( $fv->upper_limit );
	}

	public function testCreateTextWithoutAnySpecialPattern(): void {
		$fv = AppliedFilterValue::create( 'Berlin', $this->filterWithType( 'page' ) );
		$this->assertFalse( $fv->is_numeric );
		$this->assertSame( 'Berlin', $fv->text );
	}

	// ---------------------------------------------------------------------------
	// Tests – compare()
	// ---------------------------------------------------------------------------

	private function fv( array $props ): AppliedFilterValue {
		$fv = new AppliedFilterValue();
		foreach ( $props as $k => $v ) {
			$fv->$k = $v;
		}
		return $fv;
	}

	public function testCompareFirstIsNoneReturnPositive(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'is_none' => true ] ),
			$this->fv( [ 'text' => 'A' ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareSecondIsNoneReturnNegative(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'text' => 'A' ] ),
			$this->fv( [ 'is_none' => true ] )
		);
		$this->assertLessThan( 0, $result );
	}

	public function testCompareFirstIsOtherReturnPositive(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'is_other' => true ] ),
			$this->fv( [ 'text' => 'A' ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareSecondIsOtherReturnNegative(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'text' => 'A' ] ),
			$this->fv( [ 'is_other' => true ] )
		);
		$this->assertLessThan( 0, $result );
	}

	public function testCompareByYearLater(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'year' => 2020, 'month' => 1 ] ),
			$this->fv( [ 'year' => 2010, 'month' => 1 ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareByYearEarlier(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'year' => 2010, 'month' => 1 ] ),
			$this->fv( [ 'year' => 2020, 'month' => 1 ] )
		);
		$this->assertLessThan( 0, $result );
	}

	public function testCompareSameYearLaterMonth(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'year' => 2020, 'month' => 6 ] ),
			$this->fv( [ 'year' => 2020, 'month' => 3 ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareSameYearEarlierMonth(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'year' => 2020, 'month' => 3 ] ),
			$this->fv( [ 'year' => 2020, 'month' => 6 ] )
		);
		$this->assertLessThan( 0, $result );
	}

	public function testCompareSameYearSameMonthReturnsZero(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'year' => 2020, 'month' => 6 ] ),
			$this->fv( [ 'year' => 2020, 'month' => 6 ] )
		);
		$this->assertSame( 0, $result );
	}

	public function testCompareNumericByLowerLimit(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'is_numeric' => true, 'lower_limit' => '200' ] ),
			$this->fv( [ 'is_numeric' => true, 'lower_limit' => '100' ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareNumericNullLowerLimitReturnsNegative(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'is_numeric' => true, 'lower_limit' => null ] ),
			$this->fv( [ 'is_numeric' => true, 'lower_limit' => '100' ] )
		);
		$this->assertLessThan( 0, $result );
	}

	public function testCompareTextAlphabetically(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'text' => 'Beta' ] ),
			$this->fv( [ 'text' => 'Alpha' ] )
		);
		$this->assertGreaterThan( 0, $result );
	}

	public function testCompareEqualTextReturnsZero(): void {
		$result = AppliedFilterValue::compare(
			$this->fv( [ 'text' => 'Same' ] ),
			$this->fv( [ 'text' => 'Same' ] )
		);
		$this->assertSame( 0, $result );
	}
}
