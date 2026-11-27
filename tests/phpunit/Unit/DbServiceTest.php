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

	public function testCreateTempTableOnMySqlDeclaresIndexInTableDefinition(): void {
		$this->dbw->method( 'getType' )->willReturn( 'mysql' );
		$queries = [];
		$this->dbw->method( 'query' )
			->willReturnCallback( static function ( $sql ) use ( &$queries ) {
				$queries[] = $sql;
			} );

		$this->service->createTempTable( 'TestCategory', '', [], [] );

		foreach ( $queries as $sql ) {
			$this->assertStringNotContainsString(
				'CREATE INDEX',
				$sql,
				'CREATE INDEX commits the open transaction on MySQL, even on a temporary table'
			);
		}
		$this->assertMatchesRegularExpression(
			'/^CREATE TEMPORARY TABLE .*INDEX id_index \( id \)/s',
			$queries[1],
			'the index has to be part of CREATE TEMPORARY TABLE'
		);
	}

	public function testCreateTempTableOnOtherBackendsKeepsSeparateCreateIndex(): void {
		$this->dbw->method( 'getType' )->willReturn( 'sqlite' );
		$queries = [];
		$this->dbw->method( 'query' )
			->willReturnCallback( static function ( $sql ) use ( &$queries ) {
				$queries[] = $sql;
			} );

		$this->service->createTempTable( 'TestCategory', '', [], [] );

		$this->assertStringNotContainsString( 'INDEX', $queries[1] );
		$this->assertStringContainsString( 'CREATE INDEX id_index ON', $queries[2] );
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

	public function testCreateTempTableThrowsWithoutPrimaryConnection(): void {
		$service = new DbService( null, $this->dbr );

		$this->expectException( \LogicException::class );

		$service->createTempTable( 'TestCategory', '', [], [] );
	}
}
