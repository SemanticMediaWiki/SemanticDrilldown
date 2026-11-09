<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Sql;

use PHPUnit\Framework\TestCase;
use SD\Sql\SqlProvider;
use Wikimedia\Rdbms\Subquery;

/**
 * @covers \SD\Sql\SqlProvider::getCategorylinksJoin
 */
class SqlProviderCategorylinksJoinTest extends TestCase {

	public function testWithoutLinktargetTheCategoryIsAConditionOfTheJoin(): void {
		[ $table, $joinConds ] = SqlProvider::getCategorylinksJoin( 'categorylinks', null, "'Some_cat'", 14 );

		$this->assertSame( 'categorylinks', $table );
		$this->assertSame( [ 'cl.cl_from = pg.page_id', "cl.cl_to = 'Some_cat'" ], $joinConds );
	}

	public function testWithLinktargetOnlyLinksToTheCategoryAreJoined(): void {
		[ $table, $joinConds ] = SqlProvider::getCategorylinksJoin( 'categorylinks', 'linktarget', "'Some_cat'", 14 );

		$this->assertInstanceOf( Subquery::class, $table );
		$sql = (string)$table;
		$this->assertStringContainsString( 'FROM categorylinks JOIN linktarget ON cl_target_id = lt_id', $sql );
		$this->assertStringContainsString( "WHERE lt_namespace = 14 AND lt_title = 'Some_cat'", $sql );
		$this->assertStringContainsString( 'cl_sortkey', $sql, 'the sort key is needed for ordering' );
		$this->assertSame(
			[ 'cl.cl_from = pg.page_id' ],
			$joinConds,
			'the category must be restricted inside the derived table, not by the join condition'
		);
	}

}
