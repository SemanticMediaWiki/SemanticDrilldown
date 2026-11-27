<?php

namespace SD;

use Wikimedia\Rdbms\Database;

/**
 * Provides helper method to execute SQL queries for temporary tables outside of the
 * implicit transaction of a web request.
 *
 * The statements have to stay on the connection that later reads the temporary tables
 * (a temporary table is only visible on its own connection), so this class cannot move
 * them to a separate connection. It only wraps a transaction that MediaWiki started
 * implicitly (DBO_TRX). In an explicit transaction round, e.g. a job, nothing is wrapped
 * and the statement runs inside the open transaction.
 *
 * Callers must therefore only pass statements that do not commit implicitly there. On
 * MySQL 8 these are CREATE/DROP TEMPORARY TABLE and INSERT; a CREATE INDEX or a plain
 * DROP TABLE commits the transaction behind MediaWiki's back, and its next SAVEPOINT then
 * fails with error 1305 (see DbService::createTempTable(), issue #163).
 */
class TemporaryTableManager {
	/** @var \Wikimedia\Rdbms\IDatabase|Database */
	private $databaseConnection;

	/**
	 * TemporaryTableManager constructor.
	 * @param \Wikimedia\Rdbms\IDatabase|Database $databaseConnection the DB connection to execute queries against
	 */
	public function __construct( $databaseConnection ) {
		$this->databaseConnection = $databaseConnection;
	}

	/**
	 * Execute the given SQL query against the database.
	 * If the transaction was started implicitly (DBO_TRX flag), it is committed. An explicit
	 * transaction is left open and the query runs inside it.
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
