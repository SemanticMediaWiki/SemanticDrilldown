<?php

namespace SD\Sql;

use SD\AppliedFilter;
use SD\Utils;

class SqlProvider {

	public static function getSQL( $category, $subcategory, $all_subcategories, $applied_filters ) {
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
		$sql .= self::getSQLFromClause( $category, $subcategory, $all_subcategories, $applied_filters );
		return $sql;
	}

	/**
	 * Creates a SQL statement, lacking only the initial "SELECT"
	 * clause, to get all the pages that match all the previously-
	 * selected filters, plus the one new filter (with value) that
	 * was passed in to this function.
	 */
	public static function getSQLFromClauseForField( $new_filter ) {
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
	public static function getSQLFromClauseForCategory( $subcategory, $child_subcategories ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
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
	 *
	 * @param string $category
	 * @param string $subcategory
	 * @param string[] $subcategories
	 * @param AppliedFilter[] $applied_filters
	 * @return string
	 */
	public static function getSQLFromClause( string $category, string $subcategory, array $subcategories, array $applied_filters ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
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
				$property_table_name = $dbr->tableName(
					PropertyTypeDbInfo::tableName( $af->filter->propertyType() ) );
				if ( $af->filter->propertyType() === 'page' ) {
					$property_table_nickname = "nr$i";
					$property_field = 'p_id';
				} else {
					$property_table_nickname = "na$i";
					$property_field = 'p_id';
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
	WHERE $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)) $property_table_nickname
	ON ids.smw_id = $property_table_nickname.s_id ";
			}
		}
		foreach ( $applied_filters as $i => $af ) {
			$sql .= "\n	";
			$property_table_name = $dbr->tableName(
				PropertyTypeDbInfo::tableName( $af->filter->propertyType() ) );
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
			$property_value = $af->filter->escapedProperty();
			$value_field = PropertyTypeDbInfo::valueField( $af->filter->propertyType() );
			if ( $af->filter->propertyType() === 'page' ) {
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
		} else { // MySQL
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
