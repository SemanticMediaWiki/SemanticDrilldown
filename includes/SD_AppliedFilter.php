<?php
/**
 * Defines a class, SDAppliedFilter, that adds a value or a value range
 * onto a an SDFilter instance.
 *
 * @author Yaron Koren
 */

class SDAppliedFilter {
	var $filter;
	var $value;
	var $is_numeric = false;
	var $lower_limit = null;
	var $upper_limit = null;

	function create($filter, $value) {
		$af = new SDAppliedFilter();
		$af->filter = $filter;
		$af->value = $value;
		if (! $af->filter->is_relation) {
			if ($af->value{0} == '<') {
				$possible_number = str_replace(',', '', trim(substr($af->value, 1)));
				if (is_numeric($possible_number)) {
					$af->upper_limit = $possible_number;
					$af->is_numeric = true;
				}
			} elseif ($af->value{0} == '>') {
				$possible_number = str_replace(',', '', trim(substr($af->value, 1)));
				if (is_numeric($possible_number)) {
					$af->lower_limit = $possible_number;
					$af->is_numeric = true;
				}
			} else {
				$elements = explode('-', $af->value);
				if (count($elements) == 2) {
					$first_elem = str_replace(',', '', trim($elements[0]));
					$second_elem = str_replace(',', '', trim($elements[1]));
					if (is_numeric($first_elem) && is_numeric($second_elem)) {
						$af->lower_limit = $first_elem;
						$af->upper_limit = $second_elem;
						$af->is_numeric = true;
					}
				}
			}
		}
		return $af;
	}

	/**
	 * Returns a string that adds a check for this filter/value
	 * combination to an SQL "WHERE" clause.
	 */
	function checkSQL($value_field) {
		$sql = "";
		if ($this->is_numeric) {
			if ($this->lower_limit && $this->upper_limit)
				$sql .= "($value_field >= {$this->lower_limit} AND $value_field <= {$this->upper_limit}) ";
			elseif ($this->lower_limit)
				$sql .= "$value_field > {$this->lower_limit} ";
			elseif ($this->upper_limit)
				$sql .= "$value_field < {$this->upper_limit} ";
		} elseif ($this->filter->time_period != NULL) {
			if ($this->filter->time_period == wfMsg('sd_filter_month')) {
				list($month_str, $year) = explode(' ', $this->value);
				$month = sdfStringToMonth($month_str);
				$sql .= "YEAR($value_field) = $year AND MONTH($value_field) = $month ";
			} else {
				$sql .= "YEAR($value_field) = {$this->value} ";
			}
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			if ($this->filter->is_relation) {
				$query_value = str_replace(' ', '_', $this->value);
			} else {
				$query_value = $this->value;
			}
			$sql .= "$value_field = '{$dbr->strencode($query_value)}' ";
		}
		return $sql;
	}
}
