<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Specials\BrowseData;

use PHPUnit\Framework\TestCase;
use SD\Specials\BrowseData\DrilldownQuery;

/**
 * @covers \SD\Specials\BrowseData\DrilldownQuery
 */
class DrilldownQueryTest extends TestCase {

	private function makeQuery( string $category, ?string $subcategory ): DrilldownQuery {
		$db = $this->createMock( \SD\DbService::class );
		return new DrilldownQuery( $db, $category, $subcategory, [], [], [] );
	}

	public function testSubcategoryPathReturnsEmptyArrayWhenNoSubcategory(): void {
		$q = $this->makeQuery( 'Animals', null );
		$this->assertSame( [], $q->subcategoryPath() );
	}

	public function testSubcategoryPathReturnsSingleElementForFlatSubcategory(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals' );
		$this->assertSame( [ 'Mammals' ], $q->subcategoryPath() );
	}

	public function testSubcategoryPathReturnsAllSegmentsForNestedSubcategory(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals/Primates' );
		$this->assertSame( [ 'Mammals', 'Primates' ], $q->subcategoryPath() );
	}

	public function testSubcategoryPathReturnsThreeSegmentsForThreeLevelNesting(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals/Primates/Apes' );
		$this->assertSame( [ 'Mammals', 'Primates', 'Apes' ], $q->subcategoryPath() );
	}

	public function testParentSubcategoryReturnsNullWhenNoSubcategory(): void {
		$q = $this->makeQuery( 'Animals', null );
		$this->assertNull( $q->parentSubcategory() );
	}

	public function testParentSubcategoryReturnsNullForSingleLevelSubcategory(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals' );
		$this->assertNull( $q->parentSubcategory() );
	}

	public function testParentSubcategoryReturnsFirstSegmentForTwoLevelNesting(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals/Primates' );
		$this->assertSame( 'Mammals', $q->parentSubcategory() );
	}

	public function testParentSubcategoryReturnsPathExcludingLastSegmentForThreeLevel(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals/Primates/Apes' );
		$this->assertSame( 'Mammals/Primates', $q->parentSubcategory() );
	}

	public function testSubcategoryReturnsNullWhenNoSubcategory(): void {
		$q = $this->makeQuery( 'Animals', null );
		$this->assertNull( $q->subcategory() );
	}

	public function testSubcategoryReturnsFullPathForNestedSubcategory(): void {
		$q = $this->makeQuery( 'Animals', 'Mammals/Primates' );
		$this->assertSame( 'Mammals/Primates', $q->subcategory() );
	}

}
