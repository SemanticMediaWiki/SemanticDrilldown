<?php

namespace SD\Sql;

use MediaWiki\MediaWikiServices;
use SD\AppliedFilter;
use SD\Utils;
use Wikimedia\Rdbms\Subquery;

class SqlProvider {

	/**
	 * @param string $category
	 * @param string $subcategory
	 * @param string[] $all_subcategories
	 * @param AppliedFilter[] $applied_filters
	 * @param bool $sortByModified order by the time of the last change, newest first, instead of
	 *   leaving the order to the database (the paged list orders by itself, see QueryPage)
	 * @return string
	 */
	public static function getSQL(
		$category, $subcategory, $all_subcategories, $applied_filters, bool $sortByModified = false
	) {
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		// Use cl_sortkey from the categorylinks table so that per-category sort keys
		// (e.g. [[Category:Foo|SortKey]]) are respected, falling back to smw_sortkey (DEFAULTSORT).
		$sql = "SELECT DISTINCT ids.smw_title AS title,
	ids.smw_title AS value,
	ids.smw_title AS t,
	ids.smw_namespace AS namespace,
	ids.smw_namespace AS ns,
	ids.smw_id AS id,
	ids.smw_iw AS iw,
	COALESCE(cl.cl_sortkey, ids.smw_sortkey) AS sortkey";
		if ( $sortByModified ) {
			$sql .= ",\n	rev.rev_timestamp AS sort_modified";
		}
		$sql .= "\n";
		$sql .= self::getSQLFromClause(
			$category, $subcategory, $all_subcategories, $applied_filters, $sortByModified
		);
		if ( $sortByModified ) {
			// smw_id as the last key, so that pages changed at the same second keep a stable order
			$sql .= "\nORDER BY sort_modified DESC, id DESC";
		}
		return $sql;
	}

	/**
	 * Creates a SQL statement, lacking only the initial "SELECT"
	 * clause, to get all the pages that match all the previously-
	 * selected filters, plus the one new filter (with value) that
	 * was passed in to this function.
	 *
	 * @return string
	 */
	public static function getSQLFromClauseForField( $new_filter ) {
		$dbr = MediaWikiServices::getInstance()
			->getDBLoadBalancer()
			->getMaintenanceConnectionRef( DB_REPLICA );
		$tableName = $dbr->tableName( "semantic_drilldown_values" );
		$tableNameFilter = $dbr->tableName( "semantic_drilldown_filter_values" );
		$sql = "FROM $tableName sdv
	LEFT OUTER JOIN $tableNameFilter sdfv
	ON sdv.id = sdfv.id
	WHERE ";
		$sql .= $new_filter->checkSQL( "sdfv.value" );
		return $sql;
	}

	/**
	 * Very similar to getSQLFromClauseForField(), except that instead
	 * of a new filter passed in, it's a subcategory, plus all that
	 * subcategory's child subcategories, to ensure completeness.
	 *
	 * @return string
	 */
	public static function getSQLFromClauseForCategory( $subcategory, $child_subcategories ) {
		$dbr = MediaWikiServices::getInstance()
			->getDBLoadBalancer()
			->getMaintenanceConnectionRef( DB_REPLICA );
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
		$ns_cat = NS_CATEGORY;
		$subcategory_escaped = $dbr->addQuotes( $subcategory );
		$tableName = $dbr->tableName( "semantic_drilldown_values" );
		$sql = "FROM $tableName sdv
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
	 *
	 * @param string $category
	 * @param string $subcategory
	 * @param string[] $subcategories
	 * @param AppliedFilter[] $applied_filters
	 * @return string
	 */
	public static function getSQLFromClause(
		string $category, string $subcategory, array $subcategories, array $applied_filters,
		bool $withRevision = false
	) {
		$services = MediaWikiServices::getInstance();
		$dbr = $services
			->getDBLoadBalancer()
			->getMaintenanceConnectionRef( DB_REPLICA );
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
		$pageTable = $dbr->tableName( 'page' );
		$categorylinksTable = $dbr->tableName( 'categorylinks' );

		// LinksMigration::getQueryInfo( 'categorylinks' ) is only available from MW 1.44+.
		// In MW 1.43 the categorylinks table is not yet in the migration mapping.
		$linktargetTable = null;
		if ( version_compare( MW_VERSION, '1.44', '>=' ) ) {
			$queryInfo = $services->getLinksMigration()->getQueryInfo( 'categorylinks' );
			if ( in_array( 'linktarget', $queryInfo['tables'], true ) ) {
				$linktargetTable = $dbr->tableName( 'linktarget' );
			}
		}

		$propertyTableNames = [];
		foreach ( $applied_filters as $i => $af ) {
			$propertyTableNames[$i] = $dbr->tableName(
				PropertyTypeDbInfo::tableName( $af->filter->propertyType() ) );
		}

		return self::buildSQLFromClause(
			$category, $subcategory, $subcategories, $applied_filters,
			$smwIDs, $smwCategoryInstances, $pageTable, $categorylinksTable,
			$propertyTableNames, $linktargetTable,
			$withRevision ? $dbr->tableName( 'revision' ) : null
		);
	}

	/**
	 * Pure SQL-string builder — no DB calls, fully unit-testable.
	 * Receives all table names pre-resolved by getSQLFromClause().
	 *
	 * @param string $category
	 * @param string $subcategory
	 * @param string[] $subcategories
	 * @param AppliedFilter[] $applied_filters
	 * @param string $smwIDs
	 * @param string $smwCategoryInstances
	 * @param string $pageTable
	 * @param string $categorylinksTable
	 * @param string[] $propertyTableNames map of filter index to quoted table name
	 * @param string|null $linktargetTable pre-resolved linktarget table name, or null to
	 *   join against categorylinks.cl_to directly (MW < 1.44, or 1.44+ before migration)
	 * @param string|null $revisionTable pre-resolved revision table name, to join the latest revision of
	 *   each page as "rev" (for sorting by the time of the last change), or null to not join it
	 * @return string
	 */
	public static function buildSQLFromClause(
		string $category, string $subcategory, array $subcategories, array $applied_filters,
		string $smwIDs, string $smwCategoryInstances, string $pageTable, string $categorylinksTable,
		array $propertyTableNames, ?string $linktargetTable = null, ?string $revisionTable = null
	) {
		$cat_ns = NS_CATEGORY;
		$prop_ns = SMW_NS_PROPERTY;

		$actual_cat = str_replace( ' ', '_', $subcategory ?: $category );
		$actual_cat = str_replace( "'", "\'", $actual_cat );

		$sql = "FROM $smwIDs ids
	JOIN $smwCategoryInstances insts
	ON ids.smw_id = insts.s_id
	AND ids.smw_namespace != $cat_ns
	LEFT JOIN $pageTable pg
	ON pg.page_title = ids.smw_title AND pg.page_namespace = ids.smw_namespace ";
		if ( $revisionTable !== null ) {
			$sql .= "LEFT JOIN $revisionTable rev ON rev.rev_id = pg.page_latest ";
		}
		if ( $linktargetTable !== null ) {
			// The category condition has to be part of the categorylinks join itself. If it were
			// only part of a following join on linktarget, every category link of a page would
			// produce a row, i.e. a page in n categories would be listed n times.
			$sql .= "LEFT JOIN ( $categorylinksTable cl
	JOIN $linktargetTable lt
	ON cl.cl_target_id = lt.lt_id AND lt.lt_namespace = $cat_ns AND lt.lt_title = '$actual_cat' )
	ON cl.cl_from = pg.page_id ";
		} else {
			$sql .= "LEFT JOIN $categorylinksTable cl
	ON cl.cl_from = pg.page_id AND cl.cl_to = '$actual_cat' ";
		}
		foreach ( $applied_filters as $i => $af ) {
			// if any of this filter's values is 'none',
			// include another table to get this information
			$includes_none = self::filterIncludesNone( $af );
			if ( $includes_none ) {
				$property_table_name = $propertyTableNames[$i];
				if ( $af->filter->propertyType() === 'page' ) {
					$property_table_nickname = "nr$i";
				} else {
					$property_table_nickname = "na$i";
				}
				$property_value = str_replace( ' ', '_', $af->filter->property() );
				$property_value = str_replace( "'", "\'", $property_value );
				// The sub-query that returns an SMW ID contains
				// a "SELECT MIN", even though by definition it
				// doesn't need to, because of occasional bugs
				// in SMW where the same page gets two
				// different SMW IDs.

				$sql .= "LEFT OUTER JOIN
	(SELECT s_id
	FROM $property_table_name
	WHERE p_id = (SELECT MIN(smw_id) FROM $smwIDs
		WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)) $property_table_nickname
	ON ids.smw_id = $property_table_nickname.s_id ";
			}
		}
		foreach ( $applied_filters as $i => $af ) {
			$includes_none = self::filterIncludesNone( $af );
			$sql .= "\n	";
			$property_table_name = $propertyTableNames[$i];
			if ( $af->filter->propertyType() === 'page' ) {
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $property_table_name r$i ON ids.smw_id = r$i.s_id\n	";
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $smwIDs o_ids$i ON r$i.o_id = o_ids$i.smw_id ";
			} else {
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $property_table_name a$i ON ids.smw_id = a$i.s_id ";
			}
		}
		$sql .= "WHERE insts.o_id IN
	(SELECT smw_id FROM $smwIDs cat_ids
	WHERE smw_namespace = $cat_ns AND (smw_title = '$actual_cat'";
		foreach ( $subcategories as $i => $subcat ) {
			$subcat = str_replace( "'", "\'", $subcat );
			$sql .= " OR smw_title = '{$subcat}'";
		}
		$sql .= ")) ";
		foreach ( $applied_filters as $i => $af ) {
			$includes_none = self::filterIncludesNone( $af );
			$property_value = $af->filter->escapedProperty();
			$value_field = PropertyTypeDbInfo::valueField( $af->filter->propertyType() );
			if ( $af->filter->propertyType() === 'page' ) {
				$property_field = "r$i.p_id";
				$sql .= "\n	AND ($property_field = (SELECT MIN(smw_id) FROM $smwIDs"
					. " WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)";
				if ( $includes_none ) {
					$sql .= " OR $property_field IS NULL";
				}
				$sql .= ")\n	AND ";
				$value_field = "o_ids$i.smw_title";
			} else {
				$property_field = "a$i.p_id";
				$sql .= "\n	AND (";
				$sql .= "$property_field = (SELECT MIN(smw_id) FROM $smwIDs"
					. " WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)";
				if ( $includes_none ) {
					$sql .= " OR $property_field IS NULL";
				}
				$sql .= ") AND ";
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
	 * Returns the categorylinks table (to be aliased "cl") and the condition of its LEFT JOIN with the
	 * page table (aliased "pg") for QueryPage::getQueryInfo().
	 *
	 * The category has to restrict the categorylinks rows themselves. If it were only a condition of
	 * a following join on linktarget, every category link of a page would produce a result row, i.e.
	 * a page in n categories would be listed n times. Hence, with a linktarget table (MW 1.44+ with
	 * migrated categorylinks), only the links to the category are joined, as a derived table.
	 *
	 * @param string $categorylinksTable
	 * @param string|null $linktargetTable pre-resolved linktarget table name, or null to
	 *   use categorylinks.cl_to directly
	 * @param string $quotedCategory the category name (with underscores) as an SQL string literal
	 * @param int $categoryNamespace
	 * @return array{0: string|Subquery, 1: string[]} table and join conditions
	 */
	public static function getCategorylinksJoin(
		string $categorylinksTable, ?string $linktargetTable, string $quotedCategory, int $categoryNamespace
	): array {
		if ( $linktargetTable === null ) {
			return [ $categorylinksTable, [ 'cl.cl_from = pg.page_id', "cl.cl_to = $quotedCategory" ] ];
		}

		$links = new Subquery(
			"SELECT cl_from, cl_sortkey FROM $categorylinksTable"
			. " JOIN $linktargetTable ON cl_target_id = lt_id"
			. " WHERE lt_namespace = $categoryNamespace AND lt_title = $quotedCategory"
		);
		return [ $links, [ 'cl.cl_from = pg.page_id' ] ];
	}

	public static function filterIncludesNone( AppliedFilter $af ): bool {
		foreach ( $af->values as $fv ) {
			if ( $fv->text === '_none' || $fv->text === ' none' ) {
				return true;
			}
		}
		return false;
	}

	public static function getDateFunctions( $dateDBField ) {
		global $wgDBtype;

		// Unfortunately, date handling in general - and date extraction
		// specifically - is done differently in almost every DB
		// system.
		if ( $wgDBtype == 'postgres' ) {
			$yearValue = "EXTRACT(YEAR FROM TIMESTAMP $dateDBField)";
			$monthValue = "EXTRACT(MONTH FROM TIMESTAMP $dateDBField)";
			$dayValue = "EXTRACT(DAY FROM TIMESTAMP $dateDBField)";
		} elseif ( $wgDBtype == 'sqlite' ) {
			$yearValue = "cast(strftime('%Y', $dateDBField) as integer)";
			$monthValue = "cast(strftime('%m', $dateDBField) as integer)";
			$dayValue = "cast(strftime('%d', $dateDBField) as integer)";
		} else {
			// MySQL
			$yearValue = "YEAR($dateDBField)";
			$monthValue = "MONTH($dateDBField)";
			$dayValue = "DAY($dateDBField)";
		}
		return [ $yearValue, $monthValue, $dayValue ];
	}

	public static function escapedProperty( $property ) {
		global $wgDBtype;
		$quoteReplace = ( $wgDBtype == 'postgres' ? "''" : "\'" );
		return str_replace( [ ' ', "'" ], [ '_', $quoteReplace ], $property );
	}

}
