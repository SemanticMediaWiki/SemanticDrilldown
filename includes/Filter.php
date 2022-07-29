<?php

namespace SD;

use SMWDIProperty;
use SMWDIUri;
use SMWDIWikiPage;

/**
 * Defines a class, Filter, that holds the information in a filter.
 *
 * @author Yaron Koren
 */

class Filter {
	public $name;
	public $property;
	public $escaped_property;
	public $property_type;
	public $category;
	public $time_period = null;
	public $allowed_values;
	public $required_filters = [];
	public $possible_applied_filters = [];
	public $db_table_name;
	public $db_value_field;
	public $db_date_field;
	public $int;

	public function setName( $name ) {
		$this->name = $name;
	}

	public function setProperty( $prop ) {
		global $wgDBtype;

		$this->property = $prop;
		$quoteReplace = ( $wgDBtype == 'postgres' ? "''" : "\'" );
		$this->escaped_property = str_replace( [ ' ', "'" ], [ '_', $quoteReplace ], $prop );
	}

	public function setCategory( $cat ) {
		$this->category = $cat;
		$this->allowed_values = Utils::getCategoryChildren( $cat, false, 5 );
	}

	public function addRequiredFilter( $filterName ) {
		$this->required_filters[] = $filterName;
	}

	public function setInt( $int ) {
		$this->int = $int;
	}

	public static function loadAllFromPageSchema( $psSchemaObj ) {
		$filters_ps = [];
		$template_all = $psSchemaObj->getTemplates();
		foreach ( $template_all as $template ) {
			$field_all = $template->getFields();
			foreach ( $field_all as $fieldObj ) {
				$f = new Filter();
				$filter_array = $fieldObj->getObject( 'semanticdrilldown_Filter' );
				if ( $filter_array === null ) {
					continue;
				}
				if ( array_key_exists( 'name', $filter_array ) ) {
					$f->setName( $filter_array['name'] );
				} else {
					$f->setName( $fieldObj->getName() );
				}
				$prop_array = $fieldObj->getObject( 'semanticmediawiki_Property' );
				if ( $prop_array['name'] != '' ) {
					$f->setProperty( $prop_array['name'] );
				} else {
					$f->setProperty( $f->name );
				}
				if ( array_key_exists( 'Type', $prop_array ) ) {
					// Thankfully, the property type names
					// assigned by SMW/Page Schemas, and the
					// internal ones used by SD, are the
					// same (for all the relevant types)
					// except for an uppercased first
					// letter.
					$f->property_type = strtolower( $prop_array['Type'] );
				}
				if ( array_key_exists( 'ValuesFromCategory', $filter_array ) ) {
					$f->setCategory( $filter_array['ValuesFromCategory'] );
				} elseif ( array_key_exists( 'TimePeriod', $filter_array ) ) {
					$f->time_period = $filter_array['TimePeriod'];
					$f->allowed_values = [];
				} elseif ( $f->property_type === 'boolean' ) {
					$f->allowed_values = [ '0', '1' ];
				} elseif ( array_key_exists( 'Values', $filter_array ) ) {
					$f->allowed_values = $filter_array['Values'];
				} else {
					$f->allowed_values = [];
				}

				// Must be done after property type is set.
				$f->loadDBStructureInformation();

				$filters_ps[] = $f;
			}
		}
		return $filters_ps;
	}

	public function loadPropertyTypeFromProperty() {
		// Default the property type to "Page" (matching SMW's
		// default), in case there is no type set for this property.
		$this->property_type = 'page';

		$store = Utils::getSMWStore();
		$propPage = new SMWDIWikiPage( $this->escaped_property, SMW_NS_PROPERTY, '' );
		$types = $store->getPropertyValues( $propPage, new SMWDIProperty( '_TYPE' ) );
		$datatypeLabels = Utils::getSMWContLang()->getDatatypeLabels();
		if ( count( $types ) > 0 ) {
			if ( $types[0] instanceof SMWDIWikiPage ) {
				$typeValue = $types[0]->getDBkey();
			} elseif ( $types[0] instanceof SMWDIURI ) {
				// A bit inefficient, but it's the
				// simplest approach.
				$typeID = $types[0]->getFragment();
				if ( $typeID == '_str' && !array_key_exists( '_str', $datatypeLabels ) ) {
					$typeID = '_txt';
				}
				$typeValue = $datatypeLabels[$typeID];
			} else {
				$typeValue = $types[0]->getWikiValue();
			}
			if ( $typeValue == $datatypeLabels['_wpg'] ) {
				$this->property_type = 'page';
			// _str stopped existing in SMW 1.9
			} elseif ( array_key_exists( '_str', $datatypeLabels ) && $typeValue == $datatypeLabels['_str'] ) {
				$this->property_type = 'string';
			} elseif ( !array_key_exists( '_str', $datatypeLabels ) && $typeValue == $datatypeLabels['_txt'] ) {
				$this->property_type = 'string';
			} elseif ( $typeValue == $datatypeLabels['_num'] ) {
				$this->property_type = 'number';
			} elseif ( $typeValue == $datatypeLabels['_boo'] ) {
				$this->property_type = 'boolean';
			} elseif ( $typeValue == $datatypeLabels['_dat'] ) {
				$this->property_type = 'date';
			} elseif ( $typeValue == $datatypeLabels['_eid'] ) {
				$this->property_type = 'external_id';
			} else {
				// This should hopefully never get called.
				print "Error! Unsupported property type ($typeValue) for filter {$this->name}.";
			}
		}

		// This requires the property type to be set.
		$this->loadDBStructureInformation();
	}

	/**
	 * This function is a little confusingly named - it's not
	 * loading any information from the database, but rather
	 * loading information *about* the structure of the Semantic
	 * MediaWiki tables in the database, based on the SQL store
	 * being used.
	 */
	public function loadDBStructureInformation() {
		global $smwgDefaultStore, $wgDBtype;

		if ( $smwgDefaultStore === 'SMWSQLStore3' || $smwgDefaultStore === 'SMWSparqlStore' ) {
			if ( $this->property_type === 'page' ) {
				$this->db_table_name = 'smw_di_wikipage';
				$this->db_value_field = 'o_ids.smw_title';
			} elseif ( $this->property_type === 'boolean' ) {
				$this->db_table_name = 'smw_di_bool';
				$this->db_value_field = 'o_value';
			} elseif ( $this->property_type === 'date' ) {
				$this->db_table_name = 'smw_di_time';
				$this->db_value_field = 'o_serialized';
				$this->db_date_field = 'SUBSTR(o_serialized, 3, 100)';

				if ( $wgDBtype == 'mysql' ) {
					// SMW date field has the following format: 2000/2/11/22/0/1/0, where every /-separated
					// segment is optional. All of these are valid: 2000, 2000/2, 2000/2/11.
					// However, YEAR(), MONTH() and DAY() would return NULL for incomplete date,
					// so STR_TO_DATE is required.
					$this->db_date_field = "STR_TO_DATE(" . $this->db_date_field .
						", '%Y/%m/%d')";
				}

			} elseif ( $this->property_type === 'number' ) {
				$this->db_table_name = 'smw_di_number';
				$this->db_value_field = 'o_serialized';
			} else { // string, text, code
				$this->db_table_name = 'smw_di_blob';
				// CONVERT() is also supported in PostgreSQL,
				// but it doesn't seem to work the same way.
				// IF() is not supported in PostgreSQL - there
				// is an alternative CASE() statement, but
				// let's just keep it simple - in part in
				// order to also support other DB types.
				if ( $wgDBtype == 'mysql' ) {
					$this->db_value_field = '(IF(o_blob IS NULL, o_hash, CONVERT(o_blob using utf8)))';
				} else {
					$this->db_value_field = 'o_hash';
				}
			}
		} else {
			// Things used to be so simple...
			if ( $this->property_type === 'page' ) {
				$this->db_table_name = 'smw_di_wikipage';
				$this->db_value_field = 'o_ids.smw_title';
			} else {
				$this->db_table_name = 'smw_di_blob';
				$this->db_date_field = 'o_hash';
				$this->db_value_field = 'o_hash';
			}
		}
	}

	public function getTableName() {
		return $this->db_table_name;
	}

	public function getValueField() {
		return $this->db_value_field;
	}

	/**
	 * Used for getting year and month from the date field.
	 */
	public function getDateField() {
		return $this->db_date_field;
	}

	public function getTimePeriod() {
		// If it's not a date property, return null.
		if ( $this->property_type != 'date' ) {
			return null;
		}

		// If it has already been set, just return it.
		if ( $this->time_period != null ) {
			return $this->time_period;
		}

		$dbw = wfGetDB( DB_MASTER );
		$property_value = $this->escaped_property;
		$date_field = $this->getDateField();
		$datesTable = $dbw->tableName( $this->getTableName() );
		$idsTable = $dbw->tableName( Utils::getIDsTableName() );
		$sql = <<<END
	SELECT MIN($date_field), MAX($date_field)
	FROM semantic_drilldown_values sdv
	JOIN $datesTable a ON sdv.id = a.s_id
	JOIN $idsTable p_ids ON a.p_id = p_ids.smw_id
	WHERE p_ids.smw_title = '$property_value'

END;
		$res = $dbw->query( $sql );
		$row = $res->fetchRow();
		$minDate = $row[0];
		if ( $minDate === null ) {
			return null;
		}
		$minDateParts = explode( '/', $minDate );
		if ( count( $minDateParts ) == 3 ) {
			list( $minYear, $minMonth, $minDay ) = $minDateParts;
		} else {
			$minYear = $minDateParts[0];
			$minMonth = $minDay = 0;
		}
		$maxDate = $row[1];
		$maxDateParts = explode( '/', $maxDate );
		if ( count( $maxDateParts ) == 3 ) {
			list( $maxYear, $maxMonth, $maxDay ) = $maxDateParts;
		} else {
			$maxYear = $maxDateParts[0];
			$maxMonth = $maxDay = 0;
		}
		$yearDifference = $maxYear - $minYear;
		$monthDifference = ( 12 * $yearDifference ) + ( $maxMonth - $minMonth );
		if ( $yearDifference > 30 ) {
			$this->time_period = 'decade';
		} elseif ( $yearDifference > 2 ) {
			$this->time_period = 'year';
		} elseif ( $monthDifference > 1 ) {
			$this->time_period = 'month';
		} else {
			$this->time_period = 'day';
		}
		return $this->time_period;
	}

	/**
	 * Gets an array of the possible time period values (e.g., years,
	 * months) for this filter, and, for each one,
	 * the number of pages that match that time period.
	 */
	public function getTimePeriodValues() {
		$possible_dates = [];
		$property_value = $this->escaped_property;
		$date_field = $this->getDateField();
		$dbw = wfGetDB( DB_MASTER );
		list( $yearValue, $monthValue, $dayValue ) = Utils::getDateFunctions( $date_field );
		$fields = "$yearValue, $monthValue, $dayValue";
		$datesTable = $dbw->tableName( $this->getTableName() );
		$idsTable = $dbw->tableName( Utils::getIDsTableName() );
		$sql = <<<END
	SELECT $fields, count(*) AS matches
	FROM semantic_drilldown_values sdv
	JOIN $datesTable a ON sdv.id = a.s_id
	JOIN $idsTable p_ids ON a.p_id = p_ids.smw_id
	WHERE p_ids.smw_title = '$property_value'
	GROUP BY $fields
	ORDER BY $fields

END;
		// Additionally calculate earliest/latest date for default values of datepickers
		// from SD_BrowseData::printDateRangeInput().
		$min_date = '';
		$max_date = '';
		$min_date_padded = '';
		$max_date_padded = '';

		$res = $dbw->query( $sql );
		while ( $row = $res->fetchRow() ) {
			$timePeriod = $this->getTimePeriod();

			/*
				Some pages may have incomplete date (e.g. "February, 2019" or "2019" instead
				of the full year/month/day). In this case day/month may be 0
				and should be excluded from the filter that matches this particular page.

				E.g. a day filter for date "February 0, 2019" is replaced with a
				month filter "February, 2019". If month number is similarly 0, then it is replaced
				with year filter "2019".
				Note: if other pages have a full date, they will have a complete filter by day.
			*/
			if ( $timePeriod == 'day' && !$row[2] ) {
				$timePeriod = 'month';
			}

			if ( $timePeriod == 'month' && !$row[1] ) {
				$timePeriod = 'year';
			}

			$count = $row['matches'];

			if ( $timePeriod == 'day' ) {
				$date_string = Utils::monthToString( $row[1] ) . ' ' . $row[2] . ', ' . $row[0];
				$possible_dates[$date_string] = $count;
			} elseif ( $timePeriod == 'month' ) {
				$date_string = Utils::monthToString( $row[1] ) . ' ' . $row[0];
				$possible_dates[$date_string] = $count;
			} elseif ( $timePeriod == 'year' ) {
				$date_string = $row[0];
				$possible_dates[$date_string] = $count;
			} else { // if ( $this->getTimePeriod() == 'decade' )
				// Unfortunately, there's no SQL DECADE()
				// function - so we have to take these values,
				// which are grouped into year "buckets", and
				// re-group them into decade buckets.
				$year_string = $row[0];
				$start_of_decade = $year_string - ( $year_string % 10 );
				$end_of_decade = $start_of_decade + 9;
				$decade_string = $start_of_decade . ' - ' . $end_of_decade;
				if ( !array_key_exists( $decade_string, $possible_dates ) ) {
					$possible_dates[$decade_string] = $count;
				} else {
					$possible_dates[$decade_string] += $count;
				}
			}

			// For purposes of calculating earliest and latest dates,
			// assume missing day to be 1 and missing month to be January.
			$date = [
				'year' => $row[0],
				'month' => $row[1] ?? 0,
				'day' => $row[2] ?? 0
			];
			$padded_date = sprintf( '%04d%02d%02d', // YYYYMMDD, for comparing with previous min/max date
				$date['year'],
				$date['month'],
				$date['day']
			);

			if ( !$min_date || $padded_date < $min_date_padded ) {
				$min_date_padded = $padded_date;
				$min_date = $date;
			}

			if ( !$max_date || $padded_date > $max_date_padded ) {
				$max_date_padded = $padded_date;
				$max_date = $date;
			}
		}

		// If month/day are missing in $min_date/$max_date,
		// then set them to 1 January for $min_date and to 31 December for $max_date.
		if ( $min_date ) {
			// YYYY-MM-DD, as expected by Datepicked inputs.
			$min_date = sprintf( '%04d-%02d-%02d', $min_date['year'],
				$min_date['month'] ?: 1, $min_date['day'] ?: 1 );
		}

		if ( $max_date ) {
			$max_date = sprintf( '%04d-%02d-%02d', $max_date['year'],
				$max_date['month'] ?: 12, $max_date['day'] ?: 31 );
		}

		return [
			$possible_dates,
			$min_date,
			$max_date
		];
	}

	/**
	 * Gets an array of all values that the property belonging to this
	 * filter has, and, for each one, the number of pages
	 * that match that value.
	 */
	public function getAllValues() {
		$possible_values = [];
		$property_value = $this->escaped_property;
		$dbw = wfGetDB( DB_MASTER );
		$property_table_name = $dbw->tableName( $this->getTableName() );
		$value_field = $this->getValueField();
		$smw_ids = $dbw->tableName( Utils::getIDsTableName() );
		$prop_ns = SMW_NS_PROPERTY;
		$sql = <<<END
	SELECT $value_field, count(DISTINCT sdv.id)
	FROM semantic_drilldown_values sdv
	JOIN $property_table_name p ON sdv.id = p.s_id

END;
		if ( $this->property_type === 'page' ) {
			$sql .= "	JOIN $smw_ids o_ids ON p.o_id = o_ids.smw_id";
		}
		$sql .= <<<END
	JOIN $smw_ids p_ids ON p.p_id = p_ids.smw_id
	WHERE p_ids.smw_title = '$property_value'
	AND p_ids.smw_namespace = $prop_ns
	GROUP BY $value_field
	ORDER BY $value_field

END;
		$res = $dbw->query( $sql );
		while ( $row = $res->fetchRow() ) {
			$value_string = str_replace( '_', ' ', $row[0] );
			// We check this here, and not in the SQL, because
			// for MySQL, 0 sometimes equals blank.
			if ( $value_string === '' ) {
				continue;
			}
			$possible_values[$value_string] = $row[1];
		}
		return $possible_values;
	}

	/**
	 * Creates a temporary database table, semantic_drilldown_filter_values,
	 * that holds every value held by any page in the wiki that matches
	 * the property associated with this filter. This table is useful
	 * both for speeding up later queries (at least, that's the hope)
	 * and for getting the set of 'None' values.
	 */
	public function createTempTable() {
		$dbw = wfGetDB( DB_MASTER );

		$smw_ids = $dbw->tableName( Utils::getIDsTableName() );

		$valuesTable = $dbw->tableName( $this->getTableName() );
		$value_field = $this->getValueField();

		$query_property = $this->escaped_property;

		$sql = <<<END
	CREATE TEMPORARY TABLE semantic_drilldown_filter_values
	AS SELECT s_id AS id, $value_field AS value
	FROM $valuesTable
	JOIN $smw_ids p_ids ON $valuesTable.p_id = p_ids.smw_id

END;
		if ( $this->property_type === 'page' ) {
			$sql .= "	JOIN $smw_ids o_ids ON $valuesTable.o_id = o_ids.smw_id\n";
		}
		$sql .= "	WHERE p_ids.smw_title = '$query_property'";

		$temporaryTableManager = new TemporaryTableManager( $dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}

	/**
	 * Deletes the temporary table.
	 */
	public function dropTempTable() {
		$dbw = wfGetDB( DB_MASTER );
		// DROP TEMPORARY TABLE would be marginally safer, but it's
		// not supported on all RDBMS's.
		$sql = "DROP TABLE semantic_drilldown_filter_values";

		$temporaryTableManager = new TemporaryTableManager( $dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}
}
