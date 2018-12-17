<?php
/**
 * Provides helper method to execute SQL queries in auto-commit mode
 */

class TemporaryTableManager {
	/** @var \Wikimedia\Rdbms\IDatabase|DatabaseBase $databaseConnection */
	private $databaseConnection;

	/**
	 * TemporaryTableManager constructor.
	 * @param \Wikimedia\Rdbms\IDatabase|DatabaseBase $databaseConnection the DB connection to execute queries against
	 */
	public function __construct( $databaseConnection ) {
		$this->databaseConnection = $databaseConnection;
	}

	/**
	 * Execute the given SQL query against the database in auto-commit mode.
	 * If a transaction was already open (via DBO_TRX flag), it is commited.
	 *
	 * @param string $sqlQuery SQL query to execute
	 * @param string $method method name to log for query, defaults to this method
	 */
	public function queryWithAutoCommit( $sqlQuery, $method = __METHOD__ ) {
		$wasAutoTrx = $this->databaseConnection->getFlag( DBO_TRX );
		$this->databaseConnection->clearFlag( DBO_TRX );

		// If a transaction was automatically started on first query, make sure we commit it
		if ( $wasAutoTrx && $this->databaseConnection->trxLevel() ) {
			$this->databaseConnection->startAtomic( __METHOD__ );
		}

		$this->databaseConnection->query( $sqlQuery, $method );

		if ( $wasAutoTrx && $this->databaseConnection->trxLevel() ) {
			$this->databaseConnection->endAtomic( __METHOD__ );
		}

		if ( $wasAutoTrx ) {
			$this->databaseConnection->setFlag( DBO_TRX );
		}
	}
}
