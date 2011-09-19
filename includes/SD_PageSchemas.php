<?php
/**
 * Static functions for Semantic Drilldown, for use by the Page Schemas
 * extension.
 *
 * @author Yaron Koren
 * @author Ankit Garg
 */

class SDPageSchemas {

	/**
	 * Returns an object containing information on a filter, based on XML
	 * from the Page Schemas extension.
	*/
	public static function createPageSchemasObject( $objectName, $xmlForField, &$object ) {
		$sdarray = array();
		if ( $objectName != "semanticdrilldown_Filter" ) {
			return true;
		}

		foreach ( $xmlForField->children() as $tag => $child ) {
			if ( $tag == $objectName ) {
				foreach ( $child->children() as $prop => $value) {
					if( $prop == "Values" ){
						$l_values = array();
						foreach ( $value->children() as $val ) {
							$l_values[] = (string)$val;
						}
						$sdarray['Values'] = $l_values;
					} else {
						$sdarray[$prop] = (string)$value;
					}
				}
				$object['sd'] = $sdarray;
				return true;
			}
		}
		return true;
	}

	/**
	 * Returns the HTML for setting the filter options, for the
	 * Semantic Drilldown section in Page Schemas' "edit schema" page
	 */
	public static function getFieldHTML( $field, &$text_extensions ){
		//$require_filter_label = wfMsg( 'sd_createfilter_requirefilter' );

		$filter_array = array();
		$hasExistingValues = false;
		if ( !is_null( $field ) ) {
			$sd_array = $field->getObject('semanticdrilldown_Filter');
			if ( array_key_exists( 'sd', $sd_array ) ) {
				$filter_array = $sd_array['sd'];
				$hasExistingValues = true;
			}
		}

		if ( array_key_exists( 'Name', $filter_array ) ) {
			$filterName =  $filter_array['Name'];
		} else {
			$filterName = '';
		}
		$fromCategoryAttrs = array();
		if ( array_key_exists( 'ValuesFromCategory', $filter_array ) ) {
			$selectedCategory = $filter_array['ValuesFromCategory'];
			$fromCategoryAttrs['checked'] = true;
		} else {
			$selectedCategory = '';
		}
		$dateRangesAttrs = array();
		$year_value = wfMsgForContent( 'sd_filter_year' );
		$yearOptionAttrs = array( 'value' => $year_value );
		$month_value = wfMsgForContent( 'sd_filter_month' );
		$monthOptionAttrs = array( 'value' => $month_value );
		if ( array_key_exists( 'TimePeriod', $filter_array ) ) {
			$filterTimePeriod = $filter_array['TimePeriod'];
			$dateRangesAttrs['checked'] = true;
			if ( $filterTimePeriod == $year_value ) {
				$yearOptionAttrs['selected'] = true;
			} else {
				$monthOptionAttrs['selected'] = true;
			}
		} else {
			$filterTimePeriod = '';
		}
		$manualSourceAttrs = array();
		$filterValuesAttrs = array( 'size' => 40 );
		if ( array_key_exists( 'Values', $filter_array ) ) {
			$manualSourceAttrs['checked'] = true;
			$values_array = $filter_array['Values'];
			$filterValuesStr = implode( ', ', $values_array );
		} else {
			$filterValuesStr = '';
		}
		// Have the first radiobutton ("Use all values of this
		// property for the filter") checked if none of the other
		// options have been selected - unlike the others, there's
		// no XML to define this option.
		$usePropertyValuesAttr = array();
		if ( empty( $selectedCategory ) && empty( $filterTimePeriod ) && empty( $filterValuesStr ) ) {
			$usePropertyValuesAttr['checked'] = true;
		}

		// The "input type" field.
		$combo_box_value = wfMsgForContent( 'sd_filter_combobox' );
		$date_range_value = wfMsgForContent( 'sd_filter_daterange' );
		$valuesListAttrs = array( 'value' => '' );
		$comboBoxAttrs = array( 'value' => $combo_box_value );
		$dateRangeAttrs = array( 'value' => $date_range_value );
		if ( array_key_exists( 'InputType', $filter_array ) ) {
			$input_type_val = $filter_array['InputType'];
		} else {
			$input_type_val = '';
		}
		if ( $input_type_val == $combo_box_value ) {
			$comboBoxAttrs['selected'] = true;
		} elseif ( $input_type_val == $date_range_value ) {
			$dateRangeAttrs['selected'] = true;
		} else {
			$valuesListAttrs['selected'] = true;
		}

		$html_text = '<p>' . wfMsg( 'ps-optional-name' ) . ' ';
		$html_text .= Html::input( 'sd_filter_name_num', $filterName, 'text', array( 'size' => 25 ) ) . "</p>\n";
		$html_text .= '<fieldset><legend>' . wfMsg( 'sd-pageschemas-values' ) . '</legend>' . "\n";
		$html_text .= '<p>' . Html::input( 'sd_values_source_num', 'property', 'radio', $usePropertyValuesAttr ) . ' ';
		$html_text .= wfMsg( 'sd_createfilter_usepropertyvalues' ) . "</p>\n";
		$html_text .= "\t<p>\n";
		$html_text .= Html::input( 'sd_values_source_num', 'category', 'radio', $fromCategoryAttrs ) . "\n";
		$html_text .= "\t" . wfMsg( 'sd_createfilter_usecategoryvalues' ) . "\n";
		$categories = SDUtils::getTopLevelCategories();
		$categoriesHTML = "";
		foreach ( $categories as $category ) {
			$categoryOptionAttrs = array();
			$category = str_replace( '_', ' ', $category );
			if ( $category == $selectedCategory) {
				$categoryOptionAttrs['selected'] = true;
			}
			$categoriesHTML .= "\t" . Html::element( 'option', $categoryOptionAttrs, $category ) . "\n";
		}
		$html_text .= "\t" . Html::rawElement( 'select', array( 'id' => 'category_dropdown', 'name' => 'sd_category_name_num' ), "\n" . $categoriesHTML ) . "\n";
		$html_text .= "\t</p>\n";

		$html_text .= "\t<p>\n";
		$html_text .= "\t" . Html::input( 'sd_values_source_num', 'dates', 'radio', $dateRangesAttrs ) . "\n";
		$html_text .= "\t" . wfMsg( 'sd_createfilter_usedatevalues' ) . "\n";
		$dateRangeDropdown = Html::element( 'option', $yearOptionAttrs, wfMsg( 'sd_filter_year' ) ) . "\n";
		$dateRangeDropdown .= Html::element( 'option', $monthOptionAttrs, wfMsg( 'sd_filter_month' ) ) . "\n";
		$html_text .= Html::rawElement( 'select', array( 'name' => 'sd_time_period_num', 'id' => 'time_period_dropdown' ), "\n" . $dateRangeDropdown ) . "\n";
		$html_text .= "</p>\n<p>\n";
		$html_text .= "\t" . Html::input( 'sd_values_source_num', 'manual', 'radio', $manualSourceAttrs ) . "\n";
		$html_text .= "\t" . wfMsg( 'sd_createfilter_entervalues' ) . "\n";
		$html_text .= "\t" . Html::input( 'sd_filter_values_num', $filterValuesStr, 'text', $filterValuesAttrs ) . "\n";
		$html_text .= "\t</p>\n";
		$html_text .= "</fieldset>\n";

		$html_text .= '<p>' . wfMsg( 'sd_createfilter_inputtype' ) . "\n";
		$inputTypeOptionsHTML = "\t" . Html::element( 'option', $valuesListAttrs, wfMsg( 'sd_createfilter_listofvalues' ) ) . "\n";
		$inputTypeOptionsHTML .= "\t" . Html::element( 'option', $comboBoxAttrs, wfMsg( 'sd_filter_combobox' ) ) . "\n";
		$inputTypeOptionsHTML .= "\t" . Html::element( 'option', $dateRangeAttrs, wfMsg( 'sd_filter_daterange' ) ) . "\n";
		$html_text .= Html::rawElement( 'select', array( 'name' => 'sd_input_type_num', 'id' => 'input_type_dropdown' ), $inputTypeOptionsHTML ) . "\n";
		$html_text .= "</p>\n";

		$text_extensions['sd'] = array( 'Filter', '#FDD', $html_text, $hasExistingValues );

		return true;
	}

	public static function getFieldXML( $request, &$xmlArray ) {
		$fieldNum = -1;
		$xmlPerField = array();
		foreach ( $request->getValues() as $var => $val ) {
			if ( substr( $var, 0, 15 ) == 'sd_filter_name_' ) {
				$xml = '<semanticdrilldown_Filter>';
				$fieldNum = substr( $var, 15 );
				$xml .= '<Name>'.$val.'</Name>';
			} elseif ( substr( $var, 0, 17 ) == 'sd_values_source_') {
				if ( $val == 'category' ) {
					$xml .= '<ValuesFromCategory>' . $request->getText('sd_category_name_' . $fieldNum) . '</ValuesFromCategory>';
				} elseif ( $val == 'dates' ) {
					 $xml .= '<TimePeriod>' . $request->getText('sd_time_period_' . $fieldNum) . '</TimePeriod>';
				} elseif ( $val == 'manual' ) {
					$filter_manual_values_str = $request->getText('sd_filter_values_' . $fieldNum);
					// replace the comma substitution character that has no chance of
					// being included in the values list - namely, the ASCII beep
					$listSeparator = ',';
					$filter_manual_values_str = str_replace( "\\$listSeparator", "\a", $filter_manual_values_str );
					$filter_manual_values_array = explode( $listSeparator, $filter_manual_values_str );
					$xml .= '<Values>';
					foreach ( $filter_manual_values_array as $i => $value ) {
						// replace beep back with comma, trim
						$value = str_replace( "\a", $listSeparator, trim( $value ) );
						$xml .= '<Value>'.$value.'</Value>';
					}
					$xml .= '</Values>';
				}
			} elseif ( substr( $var, 0, 14 ) == 'sd_input_type_' ) {
				if ( !empty( $val ) ) {
					$xml .= '<InputType>' . $val . '</InputType>';
				}
				$xml .= '</semanticdrilldown_Filter>';
				$xmlPerField[$fieldNum] = $xml;
			}
		}

		$xmlArray['sd'] = $xmlPerField;
		return true;
	}

	/**
	 * Displays the information about filters contained in the
	 * Page Schemas XML.
	 */
	public static function parseFieldElements( $field_xml, &$text_object ) {
		foreach ( $field_xml->children() as $tag => $child ) {
			if ( $tag == "semanticdrilldown_Filter" ) {
				$text = PageSchemas::tableMessageRowHTML( "paramAttr", wfMsg( 'specialpages-group-sd_group' ), "Filter" );
				foreach ( $child->children() as $prop => $value) {
					if ( $prop == "Values" ) {
						$filterValues = array();
						foreach ( $value->children() as $valTag ) {
							$filterValues[] = (string)$valTag;
						}
						$valuesStr = implode( ', ', $filterValues );
						$text .= PageSchemas::tableMessageRowHTML("paramAttrMsg", wfMsg( 'sd-pageschemas-values' ), $valuesStr );
					} else {
						$text .= PageSchemas::tableMessageRowHTML("paramAttrMsg", $prop, $value );
					}
				}
				$text_object['sd'] = $text;
			}
		}
		return true;
	}
}
