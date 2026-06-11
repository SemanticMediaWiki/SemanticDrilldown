<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\DbService;
use Wikimedia\Rdbms\DBConnRef;

/**
 * @covers \SD\DbService
 */
class DbServiceTest extends TestCase {

	private DBConnRef $dbw;
	private DBConnRef $dbr;
	private DbService $service;

	protected function setUp(): void {
		parent::setUp();

		$this->dbw = $this->getMockBuilder( DBConnRef::class )
			->disableOriginalConstructor()
			->getMock();

		$this->dbr = $this->getMockBuilder( DBConnRef::class )
			->disableOriginalConstructor()
			->getMock();

		$this->dbr->method( 'tableName' )
			->willReturnCallback( static fn ( $name ) => "`$name`" );

		$this->dbw->method( 'getFlag' )->willReturn( false );

		$this->service = new DbService( $this->dbw, $this->dbr );
	}

	public function testCreateTempTableUsesDropTemporaryTable(): void {
		$queries = [];
		$this->dbw->method( 'query' )
			->willReturnCallback( static function ( $sql ) use ( &$queries ) {
				$queries[] = $sql;
			} );

		$this->service->createTempTable( 'TestCategory', '', [], [] );

		$this->assertStringContainsString(
			'DROP TEMPORARY TABLE IF EXISTS',
			$queries[0],
			'createTempTable must use DROP TEMPORARY TABLE IF EXISTS, not DROP TABLE IF EXISTS'
		);
	}

	public function testDropFilterValuesTempTableUsesDropTemporaryTable(): void {
		$queries = [];
		$this->dbw->method( 'query' )
			->willReturnCallback( static function ( $sql ) use ( &$queries ) {
				$queries[] = $sql;
			} );

		$this->service->dropFilterValuesTempTable();

		$this->assertStringContainsString(
			'DROP TEMPORARY TABLE',
			$queries[0],
			'dropFilterValuesTempTable must use DROP TEMPORARY TABLE, not DROP TABLE'
		);
	}
}
