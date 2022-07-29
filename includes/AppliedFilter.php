<?php

namespace SD;

use RequestContext;

/**
 * Defines a class, AppliedFilter, that adds a value or a value range
 * onto a an Filter instance.
 *
 * @author Yaron Koren
 */

class AppliedFilter {
	public $filter;
	public $values = [];
	public $search_terms;
	public $lower_date;
	public $upper_date;
	public $lower_date_string;
	public $upper_date_string;

	public static function create( $filter, $values, $search_terms = null, $lower_date = null, $upper_date = null ) {
		$af = new AppliedFilter();
		$af->filter = $filter;
		if ( $search_terms != null ) {
			$af->search_terms = [];
			foreach ( $search_terms as $search_term ) {
				$search_term = htmlspecialchars( str_replace( '_', ' ', $search_term ) );
				// Ampersands need to be restored - hopefully
				// this is safe.
				$af->search_terms[] = str_replace( '&amp;', '&', $search_term );
			}
		}

		$dateParsed = $af->parseUpperOrLowerDate( $lower_date, false );
		if ( $dateParsed ) {
			$af->lower_date = $dateParsed;
			$af->lower_date_string = $af->lowerOrUpperDateToString( $dateParsed );
		}

		$dateParsed = $af->parseUpperOrLowerDate( $upper_date, true );
		if ( $dateParsed ) {
			$af->upper_date = $dateParsed;
			$af->upper_date_string = $af->lowerOrUpperDateToString( $dateParsed );
		}

		if ( !is_array( $values ) ) {
			$values = [ $values ];
		}
		foreach ( $values as $val ) {
			$filter_val = FilterValue::create( $val, $filter );
			$af->values[] = $filter_val;
		}
		return $af;
	}

	/**
	 * Convert the value in _lower and _upper parameter into SQL-safe value,
	 * and replace incomplete dates with complete dates (e.g. "1760" -> "1760-01-01").
	 * Aside from "/" delimiter, it should only contain digits.
	 * @param string|null $raw_date Query string of _upper_ or _lower from WebRequest.
	 * @param bool $is_upper True if this is upper filter, false if this is lower filter.
	 * @return array|null If a valid date is found, returned array has 'year', 'month' and 'day' keys.
	 *
	 * @phan-return array{year:int,month:int,day:int}|null
	 */
	protected function parseUpperOrLowerDate( $raw_date, $is_upper ) {
		$parts = array_filter( array_map( 'intval', explode( '-', $raw_date ) ) );
		if ( !$parts ) {
			return null;
		}

		$year = array_shift( $parts );

		// If day and/or month is missing in lower filter, we treat this as 1 January.
		// If day and/or month is missing in upper filter, we treat this as 31 December.
		// This way specifying "2012-2018" will include all dates within these years.
		$month = $parts ? array_shift( $parts ) : ( $is_upper ? 12 : 1 );
		$day = $parts ? array_shift( $parts ) : ( $is_upper ? 31 : 1 );

		return [
			'year' => $year,
			'month' => $month,
			'day' => $day
		];
	}

	/**
	 * Convert value of datepicker field (e.g. "1760-11-23") into a human-readable representation
	 * (e.g. "June 15, 2000").
	 */
	protected function lowerOrUpperDateToString( $date ) {
		$ts = sprintf( '%04d%02d%02d000000', $date['year'], $date['month'], $date['day'] );
		return RequestContext::getMain()->getLanguage()->date( $ts );
	}

	/**
	 * Convert value of datepicker field (e.g. "1760-11-23") into value usable in SQL queries.
	 * (e.g. DATE(...)).
	 */
	protected function lowerOrUpperDateToSql( $date ) {
		return "DATE('" . $date['year'] . "-" . $date['month'] . "-" . $date['day'] . "')";
	}

	/**
	 * Returns a string that adds a check for this filter/value
	 * combination to an SQL "WHERE" clause.
	 */
	public function checkSQL( $value_field ) {
		global $wgDBtype;

		if ( $this->filter->property_type == 'date' ) {
			$value_field = $this->filter->getDateField();
		}

		$sql = "(";
		$dbr = wfGetDB( DB_REPLICA );
		if ( $this->search_terms != null ) {
			$quoteReplace = ( $wgDBtype == 'postgres' ? "''" : "\'" );
			foreach ( $this->search_terms as $i => $search_term ) {
				$search_term = str_replace( "'", $quoteReplace, $search_term );
				if ( $i > 0 ) {
					$sql .= ' OR ';
				}
				if ( $this->filter->property_type === 'page' ) {
					// FIXME: 'LIKE' is supposed to be
					// case-insensitive, but it's not acting
					// that way here.
					// $search_term = strtolower( $search_term );
					$search_term = str_replace( ' ', '\_', $search_term );
					$sql .= "$value_field LIKE '%{$search_term}%'";
				} else {
					// $search_term = strtolower( $search_term );
					$sql .= "$value_field LIKE '%{$search_term}%'";
				}
			}
		}
		if ( $this->lower_date != null ) {
			$sql .= "date($value_field) >= " . $this->lowerOrUpperDateToSql( $this->lower_date ) . " ";
		}
		if ( $this->upper_date != null ) {
			if ( $this->lower_date != null ) {
				$sql .= " AND ";
			}
			$sql .= "date($value_field) <= " . $this->lowerOrUpperDateToSql( $this->upper_date ) . " ";
		}
		foreach ( $this->values as $i => $fv ) {
			if ( $i > 0 ) {
				$sql .= " OR ";
			}
			if ( $fv->is_other ) {
				$checkNullOrEmptySql = "$value_field IS NULL " . ( $wgDBtype == 'postgres' ? '' : "OR $value_field = '' " );
				$notOperatorSql = ( $wgDBtype == 'postgres' ? "not" : "!" );
				$sql .= "($notOperatorSql ($checkNullOrEmptySql ";
				foreach ( $this->filter->possible_applied_filters as $paf ) {
					$sql .= " OR ";
					$sql .= $paf->checkSQL( $value_field );
				}
				$sql .= "))";
			} elseif ( $fv->is_none ) {
				$checkNullOrEmptySql = ( $wgDBtype == 'postgres' ? '' : "$value_field = '' OR " ) . "$value_field IS NULL";
				$sql .= "($checkNullOrEmptySql) ";
			} elseif ( $fv->is_numeric ) {
				if ( $fv->lower_limit && $fv->upper_limit ) {
					$sql .= "($value_field >= {$fv->lower_limit} AND $value_field <= {$fv->upper_limit}) ";
				} elseif ( $fv->lower_limit ) {
					$sql .= "$value_field > {$fv->lower_limit} ";
				} elseif ( $fv->upper_limit ) {
					$sql .= "$value_field < {$fv->upper_limit} ";
				}
			} elseif ( $this->filter->property_type == 'date' ) {
				list( $yearValue, $monthValue, $dayValue ) = Utils::getDateFunctions( $value_field );
				if ( $fv->time_period == 'day' ) {
					$sql .= "$yearValue = {$fv->year} AND $monthValue = {$fv->month} AND $dayValue = {$fv->day} ";
				} elseif ( $fv->time_period == 'month' ) {
					$sql .= "$yearValue = {$fv->year} AND $monthValue = {$fv->month} ";
				} elseif ( $fv->time_period == 'year' ) {
					$sql .= "$yearValue = {$fv->year} ";
				} else { // if ( $fv->time_period == 'year range' ) {
					$sql .= "$yearValue >= {$fv->year} AND $yearValue <= {$fv->end_year} ";
				}
			} else {
				$value = $fv->text;
				if ( $this->filter->property_type === 'page' ) {
					$value = str_replace( ' ', '_', $value );
				}
				$sql .= "$value_field = '{$dbr->strencode($value)}'";
			}
		}
		$sql .= ")";

		return $sql;
	}

	/**
	 * Gets an array of all values that the property belonging to this
	 * filter has, for pages in the passed-in category.
	 */
	public function getAllOrValues( $category ) {
		$possible_values = [];
		$dbr = wfGetDB( DB_REPLICA );
		$property_value = $dbr->addQuotes( $this->filter->escaped_property );
		$property_table_name = $dbr->tableName( $this->filter->getTableName() );
		$category = $dbr->addQuotes( $category );
		if ( $this->filter->property_type != 'date' ) {
			$value_field = $this->filter->getValueField();
		} else {
			// Is this necessary?
			$date_field = $this->filter->getDateField();
			list( $yearValue, $monthValue, $dayValue ) = Utils::getDateFunctions( $date_field );
			if ( $this->filter->getTimePeriod() == 'day' ) {
				$value_field = "$yearValue, $monthValue, $dayValue";
			} elseif ( $this->filter->getTimePeriod() == 'month' ) {
				$value_field = "$yearValue, $monthValue";
			} elseif ( $this->filter->getTimePeriod() == 'year' ) {
				$value_field = $yearValue;
			} else { // if ( $this->filter->getTimePeriod() == 'year range' ) {
				$value_field = $yearValue;
			}
		}
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
		$cat_ns = NS_CATEGORY;
		$sql = "SELECT $value_field
	FROM $property_table_name p
	JOIN $smwIDs p_ids ON p.p_id = p_ids.smw_id\n";
		if ( $this->filter->property_type === 'page' ) {
			$sql .= "       JOIN $smwIDs o_ids ON p.o_id = o_ids.smw_id\n";
		}
		$sql .= "	JOIN $smwCategoryInstances insts ON p.s_id = insts.s_id
	JOIN $smwIDs cat_ids ON insts.o_id = cat_ids.smw_id
	WHERE p_ids.smw_title = $property_value
	AND cat_ids.smw_namespace = $cat_ns
	AND cat_ids.smw_title = $category
	GROUP BY $value_field
	ORDER BY $value_field";
		$res = $dbr->query( $sql );
		while ( $row = $res->fetchRow() ) {
			if ( $this->filter->property_type == 'date' && $this->filter->getTimePeriod() == 'month' ) {
				$value_string = Utils::monthToString( $row[1] ) . " " . $row[0];
			} else {
				// why is trim() necessary here???
				$value_string = str_replace( '_', ' ', trim( $row[0] ) );
			}
			$possible_values[] = $value_string;
		}
		return $possible_values;
	}

}
