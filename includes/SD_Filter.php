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
	var $is_boolean;
	var $category;
	var $time_period = null;
	var $input_type = null;
	var $allowed_values;
	var $possible_applied_filters = array();

	function load($filter_name) {
		global $sdgContLang;
		$sd_props = $sdgContLang->getSpecialPropertiesArray();

		$f = new SDFilter();
		$f->name = $filter_name;
		$properties_used = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, '_SD_CP', SD_SP_COVERS_PROPERTY, SMW_NS_PROPERTY);
		if (count($properties_used) > 0) {
			$f->property = $properties_used[0];
		}
		$f->is_relation = false;
		$proptitle = Title::newFromText($f->property, SMW_NS_PROPERTY);
		if ($proptitle != NULL) {
			$store = smwfGetStore();
			if (class_exists('SMWPropertyValue')) {
				$types = $store->getPropertyValues($proptitle, SMWPropertyValue::makeUserProperty('Has type'));
			} else {
				$types = $store->getSpecialValues($proptitle, SMW_SP_HAS_TYPE);
			}
			global $smwgContLang;
			$datatypeLabels =  $smwgContLang->getDatatypeLabels();
			if (count($types) > 0) {
				if ($types[0]->getWikiValue() == $datatypeLabels['_wpg']) {
					$f->is_relation = true;
				} elseif ($types[0]->getWikiValue() == $datatypeLabels['_boo']) {
					$f->is_boolean = true;
				}
			}
		}
		$categories = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, '_SD_VC', SD_SP_GETS_VALUES_FROM_CATEGORY, NS_CATEGORY);
		$time_periods = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, '_SD_TP', SD_SP_USES_TIME_PERIOD, null);
		if (count($categories) > 0) {
			$f->category = $categories[0];
			$f->allowed_values = sdfGetCategoryChildren($f->category, false, 5);
		} elseif (count($time_periods) > 0) {
			$f->time_period = $time_periods[0];
			$f->allowed_values = array();
		} elseif ($f->is_boolean) {
			$f->allowed_values = array('0', '1');
		} else {
			$values = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, '_SD_V', SD_SP_HAS_VALUE, null);
			$f->allowed_values = $values;
		}
		$input_types = sdfGetValuesForProperty($filter_name, SD_NS_FILTER, '_SD_IT', SD_SP_HAS_INPUT_TYPE, null);
		if (count($input_types) > 0) {
			$f->input_type = $input_types[0];
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
		global $smwgDefaultStore;
		if ($smwgDefaultStore == 'SMWSQLStore2') {
			$this->createTempTable_2();
		} else {
			$this->createTempTable_orig();
		}
	}

	function createTempTable_orig() {
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
		$sql =<<<END
	CREATE TEMPORARY TABLE semantic_drilldown_filter_values (
		id INT NOT NULL,
		value VARCHAR(200) NOT NULL,
		INDEX sdfv_id_index(id)
	) AS SELECT subject_id AS id, $value_field AS value
	FROM $table_name
	WHERE $property_field = '$query_property'
END;
		$dbr->query($sql);
	}

	function createTempTable_2() {
		$dbr = wfGetDB( DB_SLAVE );
		$smw_ids = $dbr->tableName( 'smw_ids' );
		if ($this->is_relation) {
			$table_name = $dbr->tableName( 'smw_rels2' );
			$property_field = 'p_id';
			$value_field = 'o_ids.smw_title';
		} else {
			$table_name = $dbr->tableName( 'smw_atts2' );
			$property_field = 'p_id';
			$value_field = 'value_xsd';
		}
		$query_property = str_replace(' ', '_', $this->property);
		$sql =<<<END
	CREATE TEMPORARY TABLE semantic_drilldown_filter_values
	AS SELECT s_id AS id, $value_field AS value
	FROM $table_name
	JOIN $smw_ids p_ids ON $table_name.p_id = p_ids.smw_id

END;
		if ($this->is_relation) {
			$sql .= "	JOIN $smw_ids o_ids ON $table_name.o_id = o_ids.smw_id\n";
		}
		$sql .= "	WHERE p_ids.smw_title = '$query_property'";
		$dbr->query($sql);
	}

	/**
	 * Deletes the temporary table.
	 */
	function dropTempTable() {
		$dbr = wfGetDB( DB_SLAVE );
		$sql = "DROP TEMPORARY TABLE semantic_drilldown_filter_values";
		$dbr->query($sql);
	}
}
