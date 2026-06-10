<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Sql;

use PHPUnit\Framework\TestCase;
use SD\AppliedFilter;
use SD\AppliedFilterValue;
use SD\Filter;
use SD\Sql\SqlProvider;

/**
 * @covers \SD\Sql\SqlProvider::buildSQLFromClause
 */
class SqlProviderGetSQLFromClauseTest extends TestCase {

	private const SMW_IDS = 'smw_object_ids';
	private const SMW_CAT = 'smw_di_wikipage_cat';
	private const PAGE = 'page';
	private const CATLINKS = 'categorylinks';

	private function makeFilter( string $propertyType, string $propertyName ): Filter {
		$filter = $this->createMock( Filter::class );
		$filter->method( 'propertyType' )->willReturn( $propertyType );
		$filter->method( 'property' )->willReturn( $propertyName );
		$filter->method( 'escapedProperty' )->willReturn( $propertyName );
		return $filter;
	}

	private function makeNoneAppliedFilter( string $propertyType, string $propertyName ): AppliedFilter {
		$filter = $this->makeFilter( $propertyType, $propertyName );
		$af = new AppliedFilter();
		$af->filter = $filter;
		$af->values = [ AppliedFilterValue::create( '_none' ) ];
		return $af;
	}

	private function makeValueAppliedFilter(
		string $propertyType, string $propertyName, string $value
	): AppliedFilter {
		$filter = $this->makeFilter( $propertyType, $propertyName );
		$af = new AppliedFilter();
		$af->filter = $filter;
		$af->values = [ AppliedFilterValue::create( $value ) ];
		return $af;
	}

	private function buildSQL( array $appliedFilters, array $propertyTableNames = [] ): string {
		if ( $propertyTableNames === [] ) {
			foreach ( $appliedFilters as $i => $af ) {
				$propertyTableNames[$i] = 'prop_table_' . $i;
			}
		}
		return SqlProvider::buildSQLFromClause(
			'TestCat', '', [], $appliedFilters,
			self::SMW_IDS, self::SMW_CAT, self::PAGE, self::CATLINKS,
			$propertyTableNames
		);
	}

	public function testNoneFilterForNonPageTypeUsesLeftOuterJoin(): void {
		$af = $this->makeNoneAppliedFilter( 'text', 'Status' );

		$sql = $this->buildSQL( [ $af ] );

		$this->assertStringContainsString(
			'LEFT OUTER JOIN prop_table_0',
			$sql,
			'A "none" filter on a non-page property must use LEFT OUTER JOIN to include pages with no value'
		);
	}

	public function testNoneFilterForNonPageTypeWhereClauseAllowsNullPid(): void {
		$af = $this->makeNoneAppliedFilter( 'text', 'Status' );

		$sql = $this->buildSQL( [ $af ] );

		$this->assertStringContainsString(
			'IS NULL',
			$sql,
			'WHERE clause for a non-page "none" filter must allow p_id IS NULL'
		);
	}

	public function testNoneFilterForPageTypeUsesLeftOuterJoin(): void {
		$af = $this->makeNoneAppliedFilter( 'page', 'Author' );

		$sql = $this->buildSQL( [ $af ] );

		$this->assertStringContainsString(
			'LEFT OUTER JOIN',
			$sql,
			'A "none" filter on a page property must use LEFT OUTER JOIN'
		);
	}

	public function testNonNoneFilterForNonPageTypeUsesInnerJoin(): void {
		$af = $this->makeValueAppliedFilter( 'text', 'Status', 'Active' );

		$sql = $this->buildSQL( [ $af ] );

		$this->assertStringNotContainsString(
			'LEFT OUTER JOIN prop_table_0',
			$sql,
			'A regular (non-none) filter on a non-page property must use plain INNER JOIN'
		);
	}

	/**
	 * Two filters: first has "none", second is a regular value.
	 * The scoping bug caused $includes_none from the first filter to bleed into the second.
	 */
	public function testNoneFilterDoesNotBleedIntoSubsequentRegularFilter(): void {
		$noneAf = $this->makeNoneAppliedFilter( 'text', 'Status' );
		$regularAf = $this->makeValueAppliedFilter( 'text', 'Category', 'Active' );

		$sql = $this->buildSQL( [ $noneAf, $regularAf ] );

		// The second filter's main JOIN must be a plain INNER JOIN (no LEFT OUTER).
		// prop_table_0 = noneAf's table, prop_table_1 = regularAf's table.
		$this->assertStringNotContainsString(
			'LEFT OUTER JOIN prop_table_1',
			$sql,
			'$includes_none from a none filter must not bleed into a subsequent non-none filter'
		);
	}

	public function testNoneFilterForNonPageTypeWhereClauseDoesNotAllowNullPidForOtherFilter(): void {
		$noneAf = $this->makeNoneAppliedFilter( 'text', 'Status' );
		$regularAf = $this->makeValueAppliedFilter( 'text', 'Category', 'Active' );

		$sql = $this->buildSQL( [ $noneAf, $regularAf ] );

		// The WHERE clause for the second (regular) filter must NOT include OR a1.p_id IS NULL.
		$this->assertStringNotContainsString(
			'a1.p_id IS NULL',
			$sql,
			'WHERE clause for the second (regular) filter must not allow p_id IS NULL; scoping must not bleed'
		);
	}
}
