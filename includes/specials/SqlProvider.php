<?php

class SqlProvider {

	private $category;
	private $subcategory;
	private $all_subcategories;
	private $applied_filters;

	public function __construct( $category, $subcategory, $all_subcategories, $applied_filters ) {
		$this->category = $category;
		$this->subcategory = $subcategory;
		$this->all_subcategories = $all_subcategories;
		$this->applied_filters = $applied_filters;
	}

	public function getSQL() {
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		$sql = "SELECT DISTINCT ids.smw_title AS title,
	ids.smw_title AS value,
	ids.smw_title AS t,
	ids.smw_namespace AS namespace,
	ids.smw_namespace AS ns,
	ids.smw_id AS id,
	ids.smw_iw AS iw,
	ids.smw_sortkey AS sortkey\n";
		$sql .= $this->getSQLFromClause( $this->category, $this->subcategory, $this->all_subcategories, $this->applied_filters );
		return $sql;
	}

	/**
	 * Creates a temporary database table of values that match the current
	 * set of filters selected by the user - used for displaying
	 * all remaining filters
	 */
	public function createTempTable( $category, $subcategory, $subcategories, $applied_filters ) {
		$dbw = wfGetDB( DB_MASTER );

		$temporaryTableManager = new TemporaryTableManager( $dbw );

		$sql0 = "DROP TABLE IF EXISTS semantic_drilldown_values;";
		$temporaryTableManager->queryWithAutoCommit( $sql0, __METHOD__ );

		$sql1 = "CREATE TEMPORARY TABLE semantic_drilldown_values ( id INT NOT NULL )";
		$temporaryTableManager->queryWithAutoCommit( $sql1, __METHOD__ );

		$sql2 = "CREATE INDEX id_index ON semantic_drilldown_values ( id )";
		$temporaryTableManager->queryWithAutoCommit( $sql2, __METHOD__ );

		$sql3 = "INSERT INTO semantic_drilldown_values SELECT ids.smw_id AS id\n";
		$sql3 .= $this->getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters );
		$temporaryTableManager->queryWithAutoCommit( $sql3, __METHOD__ );
	}

	/**
	 * Creates a SQL statement, lacking only the initial "SELECT"
	 * clause, to get all the pages that match all the previously-
	 * selected filters, plus the one new filter (with value) that
	 * was passed in to this function.
	 */
	private function getSQLFromClauseForField( $new_filter ) {
		$sql = "FROM semantic_drilldown_values sdv
	LEFT OUTER JOIN semantic_drilldown_filter_values sdfv
	ON sdv.id = sdfv.id
	WHERE ";
		$sql .= $new_filter->checkSQL( "sdfv.value" );
		return $sql;
	}

	/**
	 * Very similar to getSQLFromClauseForField(), except that instead
	 * of a new filter passed in, it's a subcategory, plus all that
	 * subcategory's child subcategories, to ensure completeness.
	 */
	private function getSQLFromClauseForCategory( $subcategory, $child_subcategories ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( SDUtils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( SDUtils::getCategoryInstancesTableName() );
		$ns_cat = NS_CATEGORY;
		$subcategory_escaped = $dbr->addQuotes( $subcategory );
		$sql = "FROM semantic_drilldown_values sdv
	JOIN $smwCategoryInstances inst
	ON sdv.id = inst.s_id
	WHERE inst.o_id IN
		(SELECT MIN(smw_id) FROM $smwIDs
		WHERE smw_namespace = $ns_cat AND (smw_title = $subcategory_escaped ";
		foreach ( $child_subcategories as $i => $subcat ) {
			$subcat_escaped = $dbr->addQuotes( $subcat );
			$sql .= "OR smw_title = $subcat_escaped ";
		}
		$sql .= ")) ";
		return $sql;
	}

	/**
	 * Returns everything from the FROM clause onward for a SQL statement
	 * to get all pages that match a certain set of criteria for
	 * category, subcategory and filters.
	 */
	private function getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( SDUtils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( SDUtils::getCategoryInstancesTableName() );
		$cat_ns = NS_CATEGORY;
		$prop_ns = SMW_NS_PROPERTY;

		$sql = "FROM $smwIDs ids
	JOIN $smwCategoryInstances insts
	ON ids.smw_id = insts.s_id
	AND ids.smw_namespace != $cat_ns ";
		foreach ( $applied_filters as $i => $af ) {
			// if any of this filter's values is 'none',
			// include another table to get this information
			$includes_none = false;
			foreach ( $af->values as $fv ) {
				if ( $fv->text === '_none' || $fv->text === ' none' ) {
					$includes_none = true;
					break;
				}
			}
			if ( $includes_none ) {
				$property_table_name = $dbr->tableName( $af->filter->getTableName() );
				if ( $af->filter->property_type === 'page' ) {
					$property_table_nickname = "nr$i";
					$property_field = 'p_id';
				} else {
					$property_table_nickname = "na$i";
					$property_field = 'p_id';
				}
				$property_value = str_replace( ' ', '_', $af->filter->property );
				$property_value = str_replace( "'", "\'", $property_value );
				// The sub-query that returns an SMW ID contains
				// a "SELECT MIN", even though by definition it
				// doesn't need to, because of occasional bugs
				// in SMW where the same page gets two
				// different SMW IDs.

				$sql .= "LEFT OUTER JOIN
	(SELECT s_id
	FROM $property_table_name
	WHERE $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)) $property_table_nickname
	ON ids.smw_id = $property_table_nickname.s_id ";
			}
		}
		foreach ( $applied_filters as $i => $af ) {
			$sql .= "\n	";
			$property_table_name = $dbr->tableName( $af->filter->getTableName() );
			if ( $af->filter->property_type === 'page' ) {
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $property_table_name r$i ON ids.smw_id = r$i.s_id\n	";
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $smwIDs o_ids$i ON r$i.o_id = o_ids$i.smw_id ";
			} else {
				$sql .= "JOIN $property_table_name a$i ON ids.smw_id = a$i.s_id ";
			}
		}
		if ( $subcategory ) {
			$actual_cat = str_replace( ' ', '_', $subcategory );
		} else {
			$actual_cat = str_replace( ' ', '_', $category );
		}
		$actual_cat = str_replace( "'", "\'", $actual_cat );
		$sql .= "WHERE insts.o_id IN
	(SELECT smw_id FROM $smwIDs cat_ids
	WHERE smw_namespace = $cat_ns AND (smw_title = '$actual_cat'";
		foreach ( $subcategories as $i => $subcat ) {
			$subcat = str_replace( "'", "\'", $subcat );
			$sql .= " OR smw_title = '{$subcat}'";
		}
		$sql .= ")) ";
		foreach ( $applied_filters as $i => $af ) {
			$property_value = $af->filter->escaped_property;
			$value_field = $af->filter->getValueField();
			if ( $af->filter->property_type === 'page' ) {
				$property_field = "r$i.p_id";
				$sql .= "\n	AND ($property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)";
				if ( $includes_none ) {
					$sql .= " OR $property_field IS NULL";
				}
				$sql .= ")\n	AND ";
				$value_field = "o_ids$i.smw_title";
			} else {
				$property_field = "a$i.p_id";
				$sql .= "\n	AND $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns) AND ";
				if ( strncmp( $value_field, '(IF(o_blob IS NULL', 18 ) === 0 ) {
					$value_field = str_replace( 'o_', "a$i.o_", $value_field );
				} else {
					$value_field = "a$i.$value_field";
				}
			}
			$sql .= $af->checkSQL( $value_field );
		}
		return $sql;
	}

	/**
	 * Gets the number of pages matching both the currently-selected
	 * set of filters and either a new subcategory or a new filter.
	 */
	private function getNumResults( $subcategory, $subcategories, $new_filter = null ) {
		$dbw = wfGetDB( DB_MASTER );

		// Escape the given values to prevent SQL injection
		$subcategory = $dbw->addQuotes( $subcategory );
		foreach ( $subcategories as $key => $value ) {
			$subcategories[$key] = $dbw->addQuotes( $value );
		}

		$sql = "SELECT COUNT(DISTINCT sdv.id) ";
		if ( $new_filter ) {
			$sql .= $this->getSQLFromClauseForField( $new_filter );
		} else {
			$sql .= $this->getSQLFromClauseForCategory( $subcategory, $subcategories );
		}
		$res = $dbw->query( $sql );
		$row = $res->fetchRow();
		return $row[0];
	}

}
