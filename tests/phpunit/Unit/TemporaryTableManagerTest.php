<?php

namespace SD\Tests\Unit;

use SD\TemporaryTableManager;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase ;

/**
 * @covers \SD\TemporaryTableManager
 */
class TemporaryTableManagerTest extends \PHPUnit\Framework\TestCase {
	/** @var Database|IDatabase */
	private $databaseConnectionMock;

	/** @var TemporaryTableManager */
	private $temporaryTableManager;

	protected function setUp(): void {
		parent::setUp();

		// Database::commit is final, cannot be mocked - we must use interface :/
		$this->databaseConnectionMock = $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()
			->getMock();

		$this->temporaryTableManager = new TemporaryTableManager( $this->databaseConnectionMock );
	}

	/**
	 * @dataProvider sqlProvider
	 * @param string $sqlQuery
	 */
	public function testTransactionStateAndFlagsAreNotManipulatedWhenDboTrxIsNotSet( $sqlQuery ) {
		$this->databaseConnectionMock->expects( $this->any() )
			->method( 'getFlag' )
			->with( IDatabase::DBO_TRX )
			->willReturn( false );

		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'query' )
			->with( $sqlQuery, $this->anything() );

		$this->databaseConnectionMock->expects( $this->never() )
			->method( 'startAtomic' );
		$this->databaseConnectionMock->expects( $this->never() )
			->method( 'setFlag' );

		$this->temporaryTableManager->queryWithAutoCommit( $sqlQuery );
	}

	/**
	 * @dataProvider sqlProvider
	 * @param string $sqlQuery
	 */
	public function testDboTrxFlagIsPreservedButCommitIsNotCalledIfDboTrxIsSetWithNoOpenTransaction(
		$sqlQuery
	) {
		$this->databaseConnectionMock->expects( $this->any() )
			->method( 'getFlag' )
			->with( IDatabase::DBO_TRX )
			->willReturn( true );
		$this->databaseConnectionMock->expects( $this->any() )
			->method( 'trxLevel' )
			->willReturn( false );

		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'query' )
			->with( $sqlQuery, $this->anything() );

		$this->databaseConnectionMock->expects( $this->never() )
			->method( 'startAtomic' );
		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'setFlag' )
			->with( IDatabase::DBO_TRX );

		$this->temporaryTableManager->queryWithAutoCommit( $sqlQuery );
	}

	/**
	 * @dataProvider sqlProvider
	 * @param string $sqlQuery
	 */
	public function testDboTrxFlagIsPreservedAndCommitIsCalledIfDboTrxIsSetWithOpenTransaction(
		$sqlQuery
	) {
		$this->databaseConnectionMock->expects( $this->any() )
			->method( 'getFlag' )
			->with( IDatabase::DBO_TRX )
			->willReturn( true );
		$this->databaseConnectionMock->expects( $this->any() )
			->method( 'trxLevel' )
			->willReturn( true );

		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'query' )
			->with( $sqlQuery, $this->anything() );

		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'startAtomic' );
		$this->databaseConnectionMock->expects( $this->once() )
			->method( 'setFlag' )
			->with( IDatabase::DBO_TRX );

		$this->temporaryTableManager->queryWithAutoCommit( $sqlQuery );
	}

	public function sqlProvider() {
		return [
			[ 'CREATE TEMPORARY TABLE semantic_drilldown_values ( id INT NOT NULL )' ],
			[ 'CREATE INDEX id_index ON semantic_drilldown_values ( id )' ],
			[ 'INSERT INTO semantic_drilldown_values SELECT ids.smw_id AS id\n' ],
		];
	}
}
