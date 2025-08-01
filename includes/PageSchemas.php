<?php

namespace SD;

use Html;
use MediaWiki\MediaWikiServices;

/**
 * Static functions for Semantic Drilldown, for use by the Page Schemas
 * extension.
 *
 * @author Yaron Koren
 * @author Ankit Garg
 */

class PageSchemas extends \PSExtensionHandler {
	public static function registerClass() {
		global $wgPageSchemasHandlerClasses;
		$wgPageSchemasHandlerClasses[] = self::class;
		return true;
	}

	/**
	 * Returns an object containing information on a filter, based on XML
	 * from the Page Schemas extension.
	 *
	 * @return array
	 */
	public static function createPageSchemasObject( $tagName, $xml ) {
		$sd_array = [];
		if ( $tagName != "semanticdrilldown_Filter" ) {
			return null;
		}

		foreach ( $xml->children() as $tag => $child ) {
			if ( $tag != $tagName ) {
				continue;
			}
			$filterName = $child->attributes()->name;
			if ( $filterName !== null ) {
				$sd_array['name'] = (string)$filterName;
			}
			foreach ( $child->children() as $prop => $value ) {
				if ( $prop == "Values" ) {
					$l_values = [];
					foreach ( $value->children() as $val ) {
						$l_values[] = (string)$val;
					}
					$sd_array['Values'] = $l_values;
				} else {
					$sd_array[$prop] = (string)$value;
				}
			}
			return $sd_array;
		}
		return null;
	}

	public static function getDisplayColor() {
		return '#FDD';
	}

	public static function getFieldDisplayString() {
		return wfMessage( 'sd-pageschemas-filter' )->text();
	}

	/**
	 * Returns the HTML for setting the filter options, for the
	 * Semantic Drilldown section in Page Schemas' "edit schema" page
	 *
	 * @return array
	 */
	public static function getFieldEditingHTML( $psField ) {
		// $require_filter_label = wfMessage( 'sd_createfilter_requirefilter' )->text();

		$filter_array = [];
		$hasExistingValues = false;
		if ( $psField !== null ) {
			$filter_array = $psField->getObject( 'semanticdrilldown_Filter' );
			if ( $filter_array !== null ) {
				$hasExistingValues = true;
			}
		}

		$filterName = \PageSchemas::getValueFromObject( $filter_array, 'name' );
		$selectedCategory = \PageSchemas::getValueFromObject( $filter_array, 'ValuesFromCategory' );
		$fromCategoryAttrs = [];
		if ( $selectedCategory !== null ) {
			$fromCategoryAttrs['checked'] = true;
		}

		// Have the first radiobutton ("Use all values of this
		// property for the filter") checked if none of the other
		// options have been selected - unlike the others, there's
		// no XML to define this option.
		$usePropertyValuesAttr = [];
		if ( empty( $selectedCategory ) ) {
			$usePropertyValuesAttr['checked'] = true;
		}

		$html_text = '<div class="editSchemaMinorFields">' . "\n";
		$html_text .= '<p>' . wfMessage( 'ps-optional-name' )->escaped() . ' ';
		$html_text .= Html::input( 'sd_filter_name_num', $filterName, 'text', [ 'size' => 25 ] ) . "</p>\n";
		$html_text .= wfMessage( 'sd-pageschemas-values' )->escaped() . ":\n";
		$html_text .= Html::input( 'sd_values_source_num', 'property', 'radio', $usePropertyValuesAttr ) . ' ';
		$html_text .= wfMessage( 'sd_createfilter_usepropertyvalues' )->escaped() . "\n";
		$html_text .= Html::input( 'sd_values_source_num', 'category', 'radio', $fromCategoryAttrs ) . "\n";
		$html_text .= "\t" . wfMessage( 'sd_createfilter_usecategoryvalues' )->escaped() . "\n";
		$dbr = MediaWikiServices::getInstance()
			->getDBLoadBalancer()
			->getMaintenanceConnectionRef( DB_REPLICA );
		$categories = ( new DbService( null, $dbr ) )->getTopLevelCategories();
		$categoriesHTML = "";
		foreach ( $categories as $category ) {
			$categoryOptionAttrs = [];
			$category = str_replace( '_', ' ', $category );
			if ( $category == $selectedCategory ) {
				$categoryOptionAttrs['selected'] = true;
			}
			$categoriesHTML .= "\t" . Html::element( 'option', $categoryOptionAttrs, $category ) . "\n";
		}
		$html_text .= "\t" . Html::rawElement( 'select', [ 'id' => 'category_dropdown', 'name' => 'sd_category_name_num' ], "\n" . $categoriesHTML ) . "\n";
		$html_text .= "\t</p>\n";
		$html_text .= "\t</div>\n";

		return [ $html_text, $hasExistingValues ];
	}

	public static function createFieldXMLFromForm() {
		global $wgRequest;

		$fieldNum = -1;
		$xmlPerField = [];
		foreach ( $wgRequest->getValues() as $var => $val ) {
			if ( substr( $var, 0, 15 ) == 'sd_filter_name_' ) {
				$xml = '<semanticdrilldown_Filter';
				$fieldNum = substr( $var, 15 );
				if ( !empty( $val ) ) {
					$xml .= ' name="' . $val . '"';
				}
				$xml .= '>';
			} elseif ( substr( $var, 0, 17 ) == 'sd_values_source_' ) {
				if ( $val == 'category' ) {
					$xml .= '<ValuesFromCategory>' . $wgRequest->getText( 'sd_category_name_' . $fieldNum ) . '</ValuesFromCategory>';
				}
				$xml .= '</semanticdrilldown_Filter>';
				$xmlPerField[$fieldNum] = $xml;
			}
		}

		return $xmlPerField;
	}

	/**
	 * Displays the information about the filter (if any exists)
	 * for one field in the Page Schemas XML.
	 *
	 * @return array
	 */
	public static function getFieldDisplayValues( $field_xml ) {
		foreach ( $field_xml->children() as $tag => $child ) {
			if ( $tag == "semanticdrilldown_Filter" ) {
				$filterName = $child->attributes()->name;
				$values = [];
				foreach ( $child->children() as $prop => $value ) {
					if ( $prop == "Values" ) {
						$filterValues = [];
						foreach ( $value->children() as $valTag ) {
							$filterValues[] = (string)$valTag;
						}
						$valuesStr = implode( ', ', $filterValues );
						$values[wfMessage( 'sd-pageschemas-values' )->text()] = $valuesStr;
					} else {
						$values[$prop] = $value;
					}
				}
				return [ $filterName, $values ];
			}
		}
		return null;
	}
}
