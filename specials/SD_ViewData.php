<?php
/**
 * Displays an interface to let the user drill down through all data on
 * the wiki, using categories and custom-defined filters that have
 * previously been created.
 *
 * @author Yaron Koren
 */

if (!defined('MEDIAWIKI')) die();

global $IP;
require_once( "$IP/includes/SpecialPage.php" );

SpecialPage::addPage( new SpecialPage('ViewData','',true,'doSpecialViewData',false) );

class ViewDataPage extends QueryPage {
	var $category = "";
	var $subcategory = "";
	var $next_level_subcategories = array();
	var $all_subcategories = array();
	var $applied_filters = array();
	var $remaining_filters = array();
	var $view_data_title;

	/**
	 * Initialize the variables of this page
	 */
	function ViewDataPage($category, $subcategory, $applied_filters, $remaining_filters) {
		$this->category = $category;
		$this->subcategory = $subcategory;
		$this->applied_filters = $applied_filters;
		$this->remaining_filters = $remaining_filters;
		$this->view_data_title = Title::newFromText('ViewData', NS_SPECIAL);

		$dbr = wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$page = $dbr->tableName( 'page' );
		$cat_ns = NS_CATEGORY;
		if ($this->subcategory)
			$actual_cat = str_replace(' ', '_', $this->subcategory);
		else
			$actual_cat = str_replace(' ', '_', $this->category);
		// get the two arrays for subcategories - one for only the
		// immediate subcategories, for display, and the other for
		// all subcategories, sub-subcategories, etc., for querying
		$this->next_level_subcategories = sdfGetCategoryChildren($actual_cat, true, 1);
		$this->all_subcategories = sdfGetCategoryChildren($actual_cat, true, 10);
	}

	function makeURLQuery($category, $applied_filters, $subcategory = null) {
		$query = "_cat=" . str_replace(' ', '_', $category);
		foreach ($applied_filters as $i => $af) {
			$query .= '&' . urlencode(str_replace(' ', '_', $af->filter->name)) . '=' . urlencode(str_replace(' ', '_', $af->value));
		}
		if ($subcategory) {
			$query .= "&_subcat=" . $subcategory;
		}
		return $query;
	}

	function createTempTable($category, $subcategory, $subcategories, $applied_filters) {
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$smw_relations = $dbr->tableName( 'smw_relations' );
		$smw_attributes = $dbr->tableName( 'smw_attributes' );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$sql = "CREATE TEMPORARY TABLE semantic_drilldown_values
			AS SELECT p.page_id ";
		$sql .= $this->getSQLFromClause($category, $subcategory, $subcategories, $applied_filters);
		$dbr->query($sql);
		// create an index to speed up subsequent queries
		// (does this help?)
		$sql2 = "CREATE INDEX page_id_index ON semantic_drilldown_values (page_id)";
		$dbr->query($sql2);
	}

	function getSQLFromClauseForField($new_filter) {
		$dbr = wfGetDB( DB_SLAVE );
		$smw_relations = $dbr->tableName( 'smw_relations' );
		$smw_attributes = $dbr->tableName( 'smw_attributes' );
		$sql = "FROM semantic_drilldown_values sdv ";
		if ($new_filter->value == ' none') {
			$sql .= "LEFT OUTER ";
		}
		$sql .= "JOIN semantic_drilldown_filter_values sdfv
			ON sdv.page_id = sdfv.subject_id ";
		$value_field = "sdfv.value";
		// special values
		if ($new_filter->value == " other") {
			$sql .= "WHERE ! ($value_field IS NULL OR $value_field = '' ";
			foreach ($new_filter->filter->possible_applied_filters as $paf) {
				$sql .= " OR ";
				$sql .= $paf->checkSQL($value_field);
			}
			$sql .= ") ";
		} elseif ($new_filter->value == " none") {
			$sql .= "WHERE ($value_field = '' OR $value_field IS NULL) ";
		} else {
			$sql .= "WHERE ";
			$sql .= $new_filter->checkSQL($value_field);
		}
		return $sql;
	}

	function getSQLFromClauseForCategory($subcategory, $subcategories) {
		$dbr = wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$sql = "FROM semantic_drilldown_values sdv ";
		$sql .= "JOIN $categorylinks c
			ON sdv.page_id = c.cl_from ";
		$sql .= "WHERE (c.cl_to = '{$subcategory}' ";
		foreach ($subcategories as $i => $subcat) {
			$sql .= "OR c.cl_to = '{$subcat}' ";
		}
		$sql .= ") ";
		return $sql;
	}

	/**
	 * Returns everything from the FROM clause onward for a SQL statement
	 * to get all pages that match a certain set of criteria for
	 * category, subcategory and filters
	 */
	function getSQLFromClause($category, $subcategory, $subcategories, $applied_filters) {
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$smw_relations = $dbr->tableName( 'smw_relations' );
		$smw_attributes = $dbr->tableName( 'smw_attributes' );
		$categorylinks = $dbr->tableName( 'categorylinks' );

		$sql = "FROM $page p
			JOIN $categorylinks c
			ON p.page_id = c.cl_from ";
		$cat_ns = NS_CATEGORY;
		$sql .= "AND p.page_namespace != $cat_ns ";
		foreach ($applied_filters as $i => $af) {
			if ($af->value == '_none' || $af->value == ' none') {
				if ($af->filter->is_relation) {
					$property_table_name = $smw_relations;
					$property_table_nickname = "nr$i";
					$property_field = 'relation_title';
					$value_field = 'object_title';
				} else {
					$property_table_name = $smw_attributes;
					$property_table_nickname = "na$i";
					$property_field = 'attribute_title';
					$value_field = 'value_xsd';
				}
				$property_value = str_replace(' ', '_', $af->filter->property);
				$sql .= "LEFT OUTER JOIN
			(SELECT subject_id, $value_field
			FROM $property_table_name
			WHERE $property_field = '$property_value') $property_table_nickname
			ON p.page_id = $property_table_nickname.subject_id ";
			}
		}
		foreach ($applied_filters as $i => $af) {
			if ($af->value != '_none' && $af->value != ' none') {
				if ($af->filter->is_relation) {
					$sql .= "JOIN $smw_relations r$i
				ON p.page_id = r$i.subject_id ";
				} else {
					$sql .= "JOIN $smw_attributes a$i
				ON p.page_id = a$i.subject_id ";
				}
			}
		}
		if ($subcategory)
			$actual_cat = str_replace(' ', '_', $subcategory);
		else
			$actual_cat = str_replace(' ', '_', $category);
		$sql .= "WHERE (c.cl_to = '$actual_cat' ";
		foreach ($subcategories as $i => $subcat) {
			$sql .= "OR c.cl_to = '{$subcat}' ";
		}
		$sql .= ") ";
		foreach ($applied_filters as $i => $af) {
			if ($af->filter->is_relation) {
				$property_field = "r$i.relation_title";
				$value_field = "r$i.object_title";
			} else {
				$property_field = "a$i.attribute_title";
				$value_field = "a$i.value_xsd";
			}
			$property_value = str_replace(' ', '_', $af->filter->property);
			if ($af->value == ' other') {
				$sql .= "AND $property_field = '{$property_value}'
					AND ! ($value_field IS NULL OR $value_field = '' ";
				foreach ($af->filter->possible_applied_filters as $paf) {
					$sql .= " OR ";
					$sql .= $paf->checkSQL($value_field);
				}
				$sql .= ")";
			} elseif ($af->value == ' none' || $af->value == '_none') {
				$sql .= "\nAND (n$value_field = '' OR n$value_field IS NULL) ";
			} else {
				$sql .= "AND $property_field = '$property_value' AND ";
				$sql .= $af->checkSQL($value_field);
			}
		}
		return $sql;
	}

	function getNumResults($subcategory, $subcategories, $applied_filters, $new_filter = null) {
		$dbr = wfGetDB( DB_SLAVE );
		$sql = "SELECT COUNT(*) ";
		if ($new_filter)
			$sql .= $this->getSQLFromClauseForField($new_filter);
		else
			$sql .= $this->getSQLFromClauseForCategory($subcategory, $subcategories);
		$res = $dbr->query($sql);
		$row = $dbr->fetchRow($res);
		$dbr->freeResult($res);
		return $row[0];
	}

	function getName() {
		return "ViewData";
	}

	function isExpensive() { return false; }

	function isSyndicated() { return false; }

	function getPageHeader() {
		global $wgUser;
		global $sdgContLang;

		$skin = $wgUser->getSkin();
		$view_data_title = Title::newFromText('ViewData', NS_SPECIAL);
		$categories = sdfGetTopLevelCategories();
		$sd_props = $sdgContLang->getSpecialPropertiesArray();
		$subcategory_text = wfMsg('sd_viewdata_subcategory');
		$choose_category_text = wfMsg('sd_viewdata_choosecategory');
		$other_str = wfMsg('sd_viewdata_other');
		$none_str = wfMsg('sd_viewdata_none');

		$header = "<div class=\"drilldown_categories\">\n";
		$header .= "<p><strong>$choose_category_text:</strong>\n";
		foreach ($categories as $i => $category) {
			if ($i > 0) { $header .= " &middot; "; }
			$category_children = sdfGetCategoryChildren($category, false, 5);
			$category_str = $category . " (" . count($category_children) . ")";
			if ($this->category == $category)
				$header .= $category_str;
			else
				$header .= $skin->makeLinkObj($this->view_data_title, $category_str, $this->makeURLQuery($category, array()));
		}
		$header .= "</p>\n";
		$header .= "</div>\n";
		$header .= '<h3>';
		if (count ($this->applied_filters) > 0 || $this->subcategory)
			$header .= $skin->makeLinkObj($this->view_data_title, $this->category, $this->makeURLQuery($this->category, array()));
		else
			$header .= $this->category;
		if ($this->subcategory) {
			$header .= " > ";
			$filter_string = "$subcategory_text: " . str_replace('_', ' ', $this->subcategory);
			$header .= $filter_string;
			$header .= ' (' . $skin->makeLinkObj($this->view_data_title, 'x', $this->makeURLQuery($this->category, $this->applied_filters)) . ') ';
		}
		foreach ($this->applied_filters as $i => $af) {
			$header .= " > ";
			$labels_for_filter = sdfGetValuesForProperty($af->filter->name, SD_NS_FILTER, $sd_props[SD_SP_HAS_LABEL], false, NS_MAIN);
			if (count($labels_for_filter) > 0) {
				$filter_label = $labels_for_filter[0];
			} else {
				$filter_label = str_replace('_', ' ', $af->filter->name);
			}
			if ($af->value == ' other') {
				$filter_value_str = $other_str;
			} elseif ($af->value == ' none') {
				$filter_value_str = $none_str;
			} else {
				$filter_value_str = $af->value;
			}
			$filter_string = str_replace('_', ' ', $filter_label . ": " . $filter_value_str);
			$header .= $filter_string;
			$temp_filters_array = $this->applied_filters;
			array_splice($temp_filters_array, $i, 1);
			$header .= ' (' . $skin->makeLinkObj($this->view_data_title, 'x', $this->makeURLQuery($this->category, $temp_filters_array, $this->subcategory)) . ') ';
		}
		$header .= "</h3>\n";
		// display the list of subcategories on one line, and below
		// it every filter, each on its own line; each line will
		// contain the possible values, and, in parentheses, the
		// number of pages that match that value
		$header .= "<div class=\"drilldown_filters\">\n";
		$cur_url = $this->makeURLQuery($this->category, $this->applied_filters, $this->subcategory);
		$cur_url .= "&";
		$this->createTempTable($this->category, $this->subcategory, $this->all_subcategories, $this->applied_filters);
		$num_printed_values = 0;
		if (count($this->next_level_subcategories) > 0) {
			$results_line = "";
			foreach ($this->next_level_subcategories as $i => $subcat) {
				$further_subcats = sdfGetCategoryChildren($subcat, true, 10);
				$num_results = $this->getNumResults($subcat, $further_subcats, $this->applied_filters);
				if ($num_results > 0) {
					if ($num_printed_values++ > 0) { $results_line .= " &middot; "; }
					$results_line .= $skin->makeLinkObj($this->view_data_title, str_replace('_', ' ', $subcat) . " ($num_results)", $cur_url . '_subcat=' . urlencode($subcat));
				}
			}
			if ($results_line != "") {
				$header .= "<p><strong>$subcategory_text:</strong> $results_line</p>\n";
			}
		}
		foreach ($this->remaining_filters as $rf) {
			$num_printed_values = 0;
			$results_line = "";
			$rf->createTempTable();
			$found_results_for_filter = false;
			foreach ($rf->allowed_values as $value) {
				$new_filter = SDAppliedFilter::create($rf, $value);
				$num_results = $this->getNumResults($this->subcategory, $this->all_subcategories, $this->applied_filters, $new_filter);
				if ($num_results > 0) {
					$found_results_for_filter = true;
					if ($num_printed_values++ > 0) { $results_line .= " &middot; "; }
					$results_line .= $skin->makeLinkObj($this->view_data_title, str_replace('_', ' ', $value) . " ($num_results)", $cur_url . urlencode(str_replace(' ', '_', $rf->name)) . '=' . urlencode(str_replace(' ', '_', $value)));
				}
			}
			// now get values for 'Other' and 'None', as well
			$other_filter = SDAppliedFilter::create($rf, ' other');
			$num_results = $this->getNumResults($this->subcategory, $this->all_subcategories, $this->applied_filters, $other_filter);
			if ($num_results > 0) {
				$found_results_for_filter = true;
				if ($num_printed_values++ > 0) { $results_line .= " &middot; "; }
				$results_line .= $skin->makeLinkObj($this->view_data_title, "$other_str ($num_results)", $cur_url . urlencode($rf->name) . '=_other');
			}
			if ($found_results_for_filter) {
				$none_filter = SDAppliedFilter::create($rf, ' none');
				$num_results = $this->getNumResults($this->subcategory, $this->all_subcategories, $this->applied_filters, $none_filter);
				if ($num_results > 0) {
					if ($num_printed_values++ > 0) { $results_line .= " &middot; "; }
					$results_line .= $skin->makeLinkObj($this->view_data_title, "$none_str ($num_results)", $cur_url . urlencode($rf->name) . '=_none');
				}
			} else {
			// if there weren't any results for any of the possible
			// values, or 'Other', don't even bother with 'None' -
			// don't display the line at all
				$results_line = "";
			}
			if ($results_line != "") {
				$labels_for_filter = sdfGetValuesForProperty($rf->name, SD_NS_FILTER, $sd_props[SD_SP_HAS_LABEL], false, NS_MAIN);
				if (count($labels_for_filter) > 0) {
					$filter_label = $labels_for_filter[0];
				} else {
					$filter_label = str_replace('_', ' ', $rf->name);
				}
				$header .= "<p><strong>$filter_label:</strong> $results_line</p>";
			}
			$rf->dropTempTable();
		}
		$header .= "</div>\n";
		return $header;
	}

	function getPageFooter() {
	}

	function linkParameters() {
		$params = array();
		$params['_cat'] = $this->category;
		if ($this->subcategory)
			$params['_subcat'] = $this->subcategory;
		$params['_cat'] = $this->category;
		foreach ($this->applied_filters as $i => $af) {
			$params[$af->filter->name] = $af->value;
		}
		return $params;
	}

	function getSQL() {
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		$sql = "SELECT DISTINCT p.page_title AS title,
			p.page_title AS value,
			p.page_namespace AS namespace ";
		$sql .= $this->getSQLFromClause($this->category, $this->subcategory, $this->all_subcategories, $this->applied_filters);
		return $sql;
	}

	function sortDescending() {
		return false;
	}

	function formatResult($skin, $result) {
		$title = Title::makeTitle( $result->namespace, $result->value );
		return $skin->makeLinkObj( $title, $title->getText() );
	}
}

function doSpecialViewData() {
	global $wgRequest, $wgOut, $sdgScriptPath;

	$mainCssUrl = $sdgScriptPath . '/skins/SD_main.css';
	$wgOut->addLink( array(
		'rel' => 'stylesheet',
		'type' => 'text/css',
		'media' => "screen, projection",
		'href' => $mainCssUrl
	));

	list( $limit, $offset ) = wfCheckLimits();
	$filters = array();

	// get information on current category, subcategory and filters that
	// have already been applied from the query string
	$category = str_replace('_', ' ', $wgRequest->getVal('_cat'));
	if (! $category) {
		// if no category was specified, go with the first
		// category on the site, alphabetically
		$categories = sdfGetTopLevelCategories();
		if (count($categories) > 0) {
			$category = $categories[0];
		}
	}
	$subcategory = $wgRequest->getVal('_subcat');

	$filters = sdfLoadFiltersForCategory($category);

	$filters_used = array();
	foreach ($filters as $i => $filter)
		$filter_used[] = false;
	$applied_filters = array();
	$remaining_filters = array();
	foreach ($wgRequest->getValues() as $var => $val) {
		$actual_var = str_replace('_', ' ', $var);
		$actual_val = str_replace('_', ' ', $val);
		foreach ($filters as $i => $filter) {
			if ($actual_var == $filter->name) {
				$applied_filters[] = SDAppliedFilter::create($filter, $actual_val);
				$filter_used[$i] = true;
				break;
			}
		}
	}
	foreach ($filters as $i => $filter) {
		if (! $filter_used[$i]) {
			$remaining_filters[] = $filter;
		}
	}
	
	$rep = new ViewDataPage($category, $subcategory, $applied_filters, $remaining_filters);
	return $rep->doQuery( $offset, $limit );
}
