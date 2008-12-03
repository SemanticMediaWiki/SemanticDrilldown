<?php
/**
 * Defines a class, SDAppliedFilter, that adds a value or a value range
 * onto a an SDFilter instance.
 *
 * @author Yaron Koren
 */

class SDAppliedFilter {
	var $filter;
	var $values = array();
	var $search_term;
	var $lower_date;
	var $upper_date;
	var $lower_date_string;
	var $upper_date_string;

	function create($filter, $values, $search_term = null, $lower_date = null, $upper_date = null) {
		$af = new SDAppliedFilter();
		$af->filter = $filter;
		$af->search_term = str_replace('_', ' ', $search_term);
		if ($lower_date != null) {
			$af->lower_date = $lower_date;
			$af->lower_date_string = sdfMonthToString($lower_date['month']) . " " . $lower_date['day'] . ", " . $lower_date['year'];
		}
		if ($upper_date != null) {
			$af->upper_date = $upper_date;
			$af->upper_date_string = sdfMonthToString($upper_date['month']) . " " . $upper_date['day'] . ", " . $upper_date['year'];
		}
		if (! is_array($values)) {
			$values = array($values);
		}
		foreach ($values as $val) {
			$filter_val = SDFilterValue::create($val, $filter->time_period);
			$af->values[] = $filter_val;
		}
		return $af;
	}

	/**
	 * Returns a string that adds a check for this filter/value
	 * combination to an SQL "WHERE" clause.
	 */
	function checkSQL($value_field) {
		$sql = "(";
		$dbr = wfGetDB( DB_SLAVE );
		if ($this->search_term != null) {
			if ($this->filter->is_relation) {
				$search_term = strtolower(str_replace(' ', '_', $this->search_term));
				$sql .= "LOWER($value_field) LIKE '%{$search_term}%'";
			} else {
				$search_term = strtolower($this->search_term);
				$sql .= "LOWER($value_field) LIKE '%{$search_term}%'";
			}
		}
		if ($this->lower_date != null) {
			$date_string = $this->lower_date['year'] . "-" . $this->lower_date['month'] . "-" . $this->lower_date['day'];
			$sql .= "date($value_field) >= date('$date_string') ";
		}
		if ($this->upper_date != null) {
			if ($this->lower_date != null) {
				$sql .= " AND ";
			}
			$date_string = $this->upper_date['year'] . "-" . $this->upper_date['month'] . "-" . $this->upper_date['day'];
			$sql .= "date($value_field) <= date('$date_string') ";
		}
		foreach ($this->values as $i => $fv) {
			if ($i > 0) {$sql .= " OR ";}
			if ($fv->is_other) {
				$sql .= "(! ($value_field IS NULL OR $value_field = '' ";
				foreach ($this->filter->possible_applied_filters as $paf) {
					$sql .= " OR ";
					$sql .= $paf->checkSQL($value_field);
				}
				$sql .= "))";
			} elseif ($fv->is_none) {
				$sql .= "($value_field = '' OR $value_field IS NULL) ";
			} elseif ($fv->is_numeric) {
				if ($fv->lower_limit && $fv->upper_limit)
					$sql .= "($value_field >= {$fv->lower_limit} AND $value_field <= {$fv->upper_limit}) ";
				elseif ($fv->lower_limit)
					$sql .= "$value_field > {$fv->lower_limit} ";
				elseif ($fv->upper_limit)
					$sql .= "$value_field < {$fv->upper_limit} ";
			} elseif ($this->filter->time_period != NULL) {
				if ($this->filter->time_period == wfMsg('sd_filter_month')) {
					$sql .= "YEAR($value_field) = {$fv->year} AND MONTH($value_field) = {$fv->month} ";
				} else {
					$sql .= "YEAR($value_field) = {$fv->year} ";
				}
			} else {
				$value = $fv->text;
				if ($this->filter->is_relation) {
					$value = str_replace(' ', '_', $value);
				}
				$sql .= "$value_field = '{$dbr->strencode($value)}'";
			}
		}
		$sql .= ")";
		return $sql;
	}
}
