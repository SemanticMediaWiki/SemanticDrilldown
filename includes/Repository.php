<?php

namespace SD;

use SD\Sql\PropertyTypeDbInfo;
use SD\Sql\SqlProvider;
use Wikimedia\Rdbms\DBConnRef;

class Repository {

	private DBConnRef $dbw;

	public function __construct( DBConnRef $dbw ) {
		$this->dbw = $dbw;
	}

	/**
	 * Creates a temporary database table of values that match the current
	 * set of filters selected by the user - used for displaying
	 * all remaining filters
	 */
	public function createTempTable( $category, $subcategory, $subcategories, $applied_filters ) {
		$temporaryTableManager = new TemporaryTableManager( $this->dbw );

		$sql0 = "DROP TABLE IF EXISTS semantic_drilldown_values;";
		$temporaryTableManager->queryWithAutoCommit( $sql0, __METHOD__ );

		$sql1 = "CREATE TEMPORARY TABLE semantic_drilldown_values ( id INT NOT NULL )";
		$temporaryTableManager->queryWithAutoCommit( $sql1, __METHOD__ );

		$sql2 = "CREATE INDEX id_index ON semantic_drilldown_values ( id )";
		$temporaryTableManager->queryWithAutoCommit( $sql2, __METHOD__ );

		$sql3 = "INSERT INTO semantic_drilldown_values SELECT ids.smw_id AS id\n";
		$sql3 .= SqlProvider::getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters );
		$temporaryTableManager->queryWithAutoCommit( $sql3, __METHOD__ );
	}

	/**
	 * Creates a temporary database table, semantic_drilldown_filter_values,
	 * that holds every value held by any page in the wiki that matches
	 * the property associated with this filter. This table is useful
	 * both for speeding up later queries (at least, that's the hope)
	 * and for getting the set of 'None' values.
	 */
	public function createFilterValuesTempTable( $propertyType, $escaped_property ) {
		$smw_ids = $this->dbw->tableName( Utils::getIDsTableName() );

		$valuesTable = $this->dbw->tableName( PropertyTypeDbInfo::tableName( $propertyType ) );
		$value_field = PropertyTypeDbInfo::valueField( $propertyType );

		$query_property = $escaped_property;

		$sql = <<<END
	CREATE TEMPORARY TABLE semantic_drilldown_filter_values
	AS SELECT s_id AS id, $value_field AS value
	FROM $valuesTable
	JOIN $smw_ids p_ids ON $valuesTable.p_id = p_ids.smw_id

END;
		if ( $propertyType === 'page' ) {
			$sql .= "	JOIN $smw_ids o_ids ON $valuesTable.o_id = o_ids.smw_id\n";
		}
		$sql .= "	WHERE p_ids.smw_title = '$query_property'";

		$temporaryTableManager = new TemporaryTableManager( $this->dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}

	/**
	 * Deletes the temporary table.
	 */
	public function dropFilterValuesTempTable() {
		// DROP TEMPORARY TABLE would be marginally safer, but it's
		// not supported on all RDBMS's.
		$sql = "DROP TABLE semantic_drilldown_filter_values";

		$temporaryTableManager = new TemporaryTableManager( $this->dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}

	/**
	 * Gets the number of pages matching both the currently-selected
	 * set of filters and either a new subcategory or a new filter.
	 */
	public function getNumResults( $subcategory, $subcategories, $new_filter = null ) {
		// Escape the given values to prevent SQL injection
		$subcategory = $this->dbw->addQuotes( $subcategory );
		foreach ( $subcategories as $key => $value ) {
			$subcategories[$key] = $this->dbw->addQuotes( $value );
		}

		$sql = "SELECT COUNT(DISTINCT sdv.id) ";
		if ( $new_filter ) {
			$sql .= SqlProvider::getSQLFromClauseForField( $new_filter );
		} else {
			$sql .= SqlProvider::getSQLFromClauseForCategory( $subcategory, $subcategories );
		}
		$res = $this->dbw->query( $sql );
		$row = $res->fetchRow();
		return $row[0];
	}

}
