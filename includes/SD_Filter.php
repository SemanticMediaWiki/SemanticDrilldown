<?php
/**
 * Defines a class, SDFilter, that holds the information in a filter,
 * i.e. a single page in the "Filter:" namespace
 *
 * @author Yaron Koren
 */

class SDFilter {
	var $name;
	var $property;
	var $is_relation;
	var $category;
	var $time_period = NULL;
	var $allowed_values;
	var $possible_applied_filters = array();

	function load($filter_name) {
		global $sdgContLang;
		$sd_props = $sdgContLang->getSpecialPropertiesArray();

		$f = new SDFilter();
		$f->name = $filter_name;
		$relations_used = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, $sd_props[SD_SP_COVERS_PROPERTY], true, SMW_NS_RELATION);
		$smw_version = SMW_VERSION;
		if ($smw_version{0} == '0') {
			$attribute_ns = SMW_NS_ATTRIBUTE;
		} else {
			$attribute_ns = SMW_NS_PROPERTY;
		}
		$attributes_used = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, $sd_props[SD_SP_COVERS_PROPERTY], true, $attribute_ns);
		if (count($relations_used) > 0) {
			$f->property = $relations_used[0];
			$f->is_relation = true;
		} elseif (count($attributes_used) > 0) {
			$f->property = $attributes_used[0];
			$f->is_relation = false;
		}
		// handling of SMW 1.0 is somewhat awkward - the above code
		// retrieves the property correctly for SMW 1.0, but not the
		// "is_relation" value; for that, we need to get this
		// "special value" separately
                if ($smw_version{0} != '0') {
			$f->is_relation = false;
			$proptitle = Title::newFromText($f->property, SMW_NS_PROPERTY);
			if ($proptitle != NULL) {
				$store = smwfGetStore();
				$types = $store->getSpecialValues($proptitle, SMW_SP_HAS_TYPE);
				global $smwgContLang;
				$datatypeLabels =  $smwgContLang->getDatatypeLabels();
				if (count($types) > 0 && $types[0]->getWikiValue() == $datatypeLabels['_wpg']) {
					$f->is_relation = true;
				}
			}
		}
		$categories = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, $sd_props[SD_SP_GETS_VALUES_FROM_CATEGORY], true, NS_CATEGORY);
		$time_periods = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, $sd_props[SD_SP_USES_TIME_PERIOD], false, NS_MAIN);
		if (count($categories) > 0) {
			$f->category = $categories[0];
			$f->allowed_values = sdfGetCategoryChildren($f->category, false, 5);
		} elseif (count($time_periods) > 0) {
			$f->time_period = $time_periods[0];
			$f->allowed_values = array();
		} else {
			$values = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, $sd_props[SD_SP_HAS_VALUE], false, NS_MAIN);
			$f->allowed_values = $values;
		}
		// set list of possible applied filters if allowed values
		// array was set
		foreach($f->allowed_values as $allowed_value) {
			$f->possible_applied_filters[] = SDAppliedFilter::create($f, $allowed_value);
		}
		return $f;
	}

	/**
	 * Creates a temporary database table, semantic_drilldown_filter_values,
	 * that holds every value held by any page in the wiki that matches
	 * the property associated with this filter. This table is useful
	 * both for speeding up later queries (at least, that's the hope)
	 * and for getting the set of 'None' values.
	 */
	function createTempTable() {
		$dbr = wfGetDB( DB_SLAVE );
		if ($this->is_relation) {
			$table_name = $dbr->tableName( 'smw_relations' );
			$property_field = 'relation_title';
			$value_field = 'object_title';
		} else {
			$table_name = $dbr->tableName( 'smw_attributes' );
			$property_field = 'attribute_title';
			$value_field = 'value_xsd';
		}
		$query_property = str_replace(' ', '_', $this->property);
		$sql = "CREATE TEMPORARY TABLE semantic_drilldown_filter_values
			AS SELECT subject_id, $value_field AS value
			FROM $table_name
			WHERE $property_field = '$query_property'";
		$dbr->query($sql);
		$sql = "CREATE INDEX sdfv_subject_id_index ON semantic_drilldown_filter_values (subject_id)";
		$dbr->query($sql);
	}

	/**
	 * Deletes the temporary table.
	 */
	function dropTempTable() {
		$dbr = wfGetDB( DB_SLAVE );
		$sql = "DROP TABLE semantic_drilldown_filter_values";
		$dbr->query($sql);
	}
}
