<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SD\AppliedFilter;
use SD\Filter;

/**
 * @covers \SD\AppliedFilter
 */
class AppliedFilterTest extends TestCase {

	// ---------------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------------

	private function filterWithType( string $type ): Filter {
		$filter = $this->createMock( Filter::class );
		$filter->method( 'propertyType' )->willReturn( $type );
		return $filter;
	}

	/**
	 * Call a protected method via reflection.
	 * @return mixed
	 */
	private function callProtected( object $obj, string $method, array $args = [] ) {
		$rm = new ReflectionMethod( $obj, $method );
		$rm->setAccessible( true );
		return $rm->invokeArgs( $obj, $args );
	}

	private function newAppliedFilter(): AppliedFilter {
		return new AppliedFilter();
	}

	// ---------------------------------------------------------------------------
	// Tests – parseUpperOrLowerDate()
	// ---------------------------------------------------------------------------

	public function testParseUpperOrLowerDateNullReturnsNull(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ null, false ] );
		$this->assertNull( $result );
	}

	public function testParseUpperOrLowerDateEmptyStringReturnsNull(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '', false ] );
		$this->assertNull( $result );
	}

	public function testParseUpperOrLowerDateFullDateLower(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020-06-15', false ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 6, 'day' => 15 ], $result );
	}

	public function testParseUpperOrLowerDateFullDateUpper(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020-06-15', true ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 6, 'day' => 15 ], $result );
	}

	public function testParseUpperOrLowerDateYearOnlyLowerDefaultsToJanFirst(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020', false ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 1, 'day' => 1 ], $result );
	}

	public function testParseUpperOrLowerDateYearOnlyUpperDefaultsToDecLast(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020', true ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 12, 'day' => 31 ], $result );
	}

	public function testParseUpperOrLowerDateYearMonthLowerDefaultsDayToFirst(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020-06', false ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 6, 'day' => 1 ], $result );
	}

	public function testParseUpperOrLowerDateYearMonthUpperDefaultsDayToLast(): void {
		$result = $this->callProtected( $this->newAppliedFilter(), 'parseUpperOrLowerDate', [ '2020-06', true ] );
		$this->assertSame( [ 'year' => 2020, 'month' => 6, 'day' => 31 ], $result );
	}

	// ---------------------------------------------------------------------------
	// Tests – lowerOrUpperDateToSql()
	// ---------------------------------------------------------------------------

	public function testLowerOrUpperDateToSql(): void {
		$date = [ 'year' => 2020, 'month' => 6, 'day' => 15 ];
		$result = $this->callProtected( $this->newAppliedFilter(), 'lowerOrUpperDateToSql', [ $date ] );
		$this->assertSame( "DATE('2020-6-15')", $result );
	}

	// ---------------------------------------------------------------------------
	// Tests – create(): value construction
	// ---------------------------------------------------------------------------

	public function testCreateWithSingleStringValueCreatesOneFilterValue(): void {
		$filter = $this->filterWithType( 'text' );
		$af = AppliedFilter::create( $filter, 'hello' );
		$this->assertCount( 1, $af->values );
		$this->assertSame( 'hello', $af->values[0]->text );
	}

	public function testCreateWithArrayOfValuesCreatesMultipleFilterValues(): void {
		$filter = $this->filterWithType( 'text' );
		$af = AppliedFilter::create( $filter, [ 'alpha', 'beta', 'gamma' ] );
		$this->assertCount( 3, $af->values );
		$this->assertSame( 'alpha', $af->values[0]->text );
		$this->assertSame( 'beta', $af->values[1]->text );
		$this->assertSame( 'gamma', $af->values[2]->text );
	}

	public function testCreateWithSearchTerms(): void {
		$filter = $this->filterWithType( 'text' );
		$af = AppliedFilter::create( $filter, [], [ 'foo', 'bar' ] );
		$this->assertSame( [ 'foo', 'bar' ], $af->search_terms );
	}

	public function testCreateWithNullDatesLeavesDateFieldsNull(): void {
		$filter = $this->filterWithType( 'text' );
		$af = AppliedFilter::create( $filter, [], null, null, null );
		$this->assertNull( $af->lower_date );
		$this->assertNull( $af->upper_date );
	}

	public function testCreateWithInvalidDateStringLeavesDateFieldsNull(): void {
		$filter = $this->filterWithType( 'text' );
		$af = AppliedFilter::create( $filter, [], null, '', '' );
		$this->assertNull( $af->lower_date );
		$this->assertNull( $af->upper_date );
	}

	public function testCreateStoresFilterReference(): void {
		$filter = $this->filterWithType( 'page' );
		$af = AppliedFilter::create( $filter, 'val' );
		$this->assertSame( $filter, $af->filter );
	}

	public function testCreateWithUnderscoreValueDecodesInFilterValue(): void {
		$filter = $this->filterWithType( 'page' );
		$af = AppliedFilter::create( $filter, 'hello_world' );
		$this->assertSame( 'hello world', $af->values[0]->text );
	}
}
