<?php

namespace SD;

use SD\Sql\PropertyTypeDbInfo;
use SD\Sql\SqlProvider;
use SMWDIProperty;
use SMWDIUri;
use SMWDIWikiPage;

/**
 * Defines a class, Filter, that holds the information in a filter.
 *
 * @author Yaron Koren
 */

class Filter {
	private string $name;
	private string $property;
	private ?string $propertyType;
	private ?string $category;
	private $requiredFilters;
	private ?string $int;
	private ?string $timePeriod;
	private $allowedValues;

	public $possible_applied_filters = [];

	public function __construct(
		DbService $db,
		$name, $property, $category, $requiredFilters, $int,
		$propertyType = null, $timePeriod = null, $allowedValues = null
	) {
		$this->name = $name;
		$this->property = $property;
		$this->category = $category;
		$this->requiredFilters = $requiredFilters ?? [];
		$this->int = $int;
		$this->propertyType = $propertyType;
		$this->timePeriod = $timePeriod;
		$this->allowedValues = $allowedValues;

		if ( $this->category !== null && $this->allowedValues === null ) {
			$this->allowedValues =
				$db->getCategoryChildren( $category, false, 5 );
		}
	}

	public function name() {
		return $this->name;
	}

	public function property() {
		return $this->property;
	}

	public function category() {
		return $this->category;
	}

	public function requiredFilters() {
		return $this->requiredFilters;
	}

	public function int() {
		return $this->int;
	}

	public function propertyType() {
		if ( $this->propertyType === null ) {
			$this->propertyType = $this->getPropertyType();
		}

		return $this->propertyType;
	}

	public function timePeriod() {
		if ( $this->timePeriod === null && $this->propertyType === 'date' ) {
			$this->timePeriod = $this->getTimePeriod();
		}

		return $this->timePeriod;
	}

	public function allowedValues() {
		return $this->allowedValues;
	}

	public function escapedProperty() {
		return SqlProvider::escapedProperty( $this->property() );
	}

	/**
	 * Gets an array of the possible time period values (e.g., years,
	 * months) for this filter, and, for each one,
	 * the number of pages that match that time period.
	 *
	 * @return PossibleFilterValues
	 */
	public function getTimePeriodValues(): PossibleFilterValues {
		$possible_dates = [];
		$property_value = $this->escapedProperty();
		$date_field = PropertyTypeDbInfo::dateField( $this->propertyType() );
		$dbw = wfGetDB( DB_MASTER );
		list( $yearValue, $monthValue, $dayValue ) = SqlProvider::getDateFunctions( $date_field );
		$fields = "$yearValue, $monthValue, $dayValue";
		$datesTable = $dbw->tableName( PropertyTypeDbInfo::tableName( $this->propertyType() ) );
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
		$timePeriod = $this->timePeriod();
		while ( $row = $res->fetchRow() ) {
			if ( $row[0] === null ) {
				continue;
			}

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
			} else { // if ( $this->timePeriod() == 'decade' )
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

		$possibleFilterValues = [];
		foreach ( $possible_dates as $date => $count ) {
			$possibleFilterValues[] = new PossibleFilterValue( $date, $count );
		}

		return new PossibleFilterValues( $possibleFilterValues, $min_date, $max_date );
	}

	/**
	 * Gets an array of all values that the property belonging to this
	 * filter has, and, for each one, the number of pages
	 * that match that value.
	 *
	 * @return PossibleFilterValues
	 */
	public function getAllValues(): PossibleFilterValues {
		$possible_values = [];
		$property_value = $this->escapedProperty();
		$dbw = wfGetDB( DB_MASTER );
		$property_table_name = $dbw->tableName( PropertyTypeDbInfo::tableName( $this->propertyType() ) );
		$revision_table_name = $dbw->tableName( 'revision' );
		$page_props_table_name = $dbw->tableName( 'page_props' );
		$value_field = PropertyTypeDbInfo::valueField( $this->propertyType() );
		$displaytitle = $this->propertyType === 'page' ? 'displaytitle.pp_value' : 'null';
		$smw_ids = $dbw->tableName( Utils::getIDsTableName() );
		$prop_ns = SMW_NS_PROPERTY;
		$sql = <<<END
	SELECT $value_field as value, $displaytitle as displayTitle, count(DISTINCT sdv.id) as count
	FROM semantic_drilldown_values sdv
	JOIN $property_table_name p ON sdv.id = p.s_id
END;
		if ( $this->propertyType === 'page' ) {
			$sql .= <<<END
	JOIN $smw_ids o_ids ON p.o_id = o_ids.smw_id
	LEFT JOIN $revision_table_name ON $revision_table_name.rev_id = o_ids.smw_rev
	LEFT JOIN $page_props_table_name displaytitle ON $revision_table_name.rev_page = displaytitle.pp_page AND displaytitle.pp_propname = 'displaytitle'
END;
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
			$value_string = str_replace( '_', ' ', $row['value'] );
			// We check this here, and not in the SQL, because
			// for MySQL, 0 sometimes equals blank.
			if ( $value_string === '' ) {
				continue;
			}
			$possible_values[] = new PossibleFilterValue( $value_string, $row['count'], htmlspecialchars_decode( $row['displayTitle'] ) );
		}

		return new PossibleFilterValues( $possible_values );
	}

	private function getTimePeriod() {
		$dbw = wfGetDB( DB_MASTER );
		$property_value = $this->escapedProperty();
		$date_field = PropertyTypeDbInfo::dateField( $this->propertyType() );
		$datesTable = $dbw->tableName( PropertyTypeDbInfo::tableName( $this->propertyType() ) );
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
		$minDate = str_replace( '-', '/', $row[0] ); // for sqlite
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
		$maxDate = str_replace( '-', '/', $row[1] ); // for sqlite
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
			$timePeriod = 'decade';
		} elseif ( $yearDifference > 2 ) {
			$timePeriod = 'year';
		} elseif ( $monthDifference > 1 ) {
			$timePeriod = 'month';
		} else {
			$timePeriod = 'day';
		}

		return $timePeriod;
	}

	private function getPropertyType() {
		// Default the property type to "Page" (matching SMW's
		// default), in case there is no type set for this property.
		$propertyType = 'page';

		$store = Utils::getSMWStore();
		$escapedProperty = $this->escapedProperty();
		$propPage = new SMWDIWikiPage( $escapedProperty, SMW_NS_PROPERTY, '' );
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
				$propertyType = 'page';
				// _str stopped existing in SMW 1.9
			} elseif ( array_key_exists( '_str', $datatypeLabels ) && $typeValue == $datatypeLabels['_str'] ) {
				$propertyType = 'string';
			} elseif ( !array_key_exists( '_str', $datatypeLabels ) && $typeValue == $datatypeLabels['_txt'] ) {
				$propertyType = 'string';
			} elseif ( $typeValue == $datatypeLabels['_num'] ) {
				$propertyType = 'number';
			} elseif ( $typeValue == $datatypeLabels['_boo'] ) {
				$propertyType = 'boolean';
			} elseif ( $typeValue == $datatypeLabels['_dat'] ) {
				$propertyType = 'date';
			} elseif ( $typeValue == $datatypeLabels['_eid'] ) {
				$propertyType = 'external_id';
			} else {
				// This should hopefully never get called.
				print "Error! Unsupported property type ($typeValue) for filter {$this->name}.";
			}
		}

		return $propertyType;
	}

}
