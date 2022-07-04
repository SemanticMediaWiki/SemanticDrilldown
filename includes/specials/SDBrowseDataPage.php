<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Widget\DateInputWidget;

class SDBrowseDataPage extends QueryPage {
	private $category = "";
	private $subcategory = "";
	private $next_level_subcategories = [];
	private $all_subcategories = [];
	private $applied_filters = [];
	private $remaining_filters = [];
	private $show_single_cat = false;

	/**
	 * Initialize the variables of this page
	 */
	public function __construct( $category, $subcategory, $applied_filters, $remaining_filters, $offset, $limit ) {
		parent::__construct( 'BrowseData' );

		$this->category = $category;
		$this->subcategory = $subcategory;
		$this->applied_filters = $applied_filters;
		$this->remaining_filters = $remaining_filters;
		$this->offset = $offset;
		$this->limit = $limit;

		$dbr = wfGetDB( DB_REPLICA );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$page = $dbr->tableName( 'page' );
		$cat_ns = NS_CATEGORY;
		if ( $this->subcategory ) {
			$actual_cat = str_replace( ' ', '_', $this->subcategory );
		} else {
			$actual_cat = str_replace( ' ', '_', $this->category );
		}
		// Get the two arrays for subcategories - one for only the
		// immediate subcategories, for display, and the other for
		// all subcategories, sub-subcategories etc., for querying.
		$this->next_level_subcategories = SDUtils::getCategoryChildren( $actual_cat, true, 1 );
		$this->all_subcategories = SDUtils::getCategoryChildren( $actual_cat, true, 10 );
	}

	private function makeBrowseURL( $category, $applied_filters = [], $subcategory = null, $filter_to_remove = null ) {
		$bd = SpecialPage::getTitleFor( 'BrowseData' );
		$url = $bd->getLocalURL() . '/' . $category;
		if ( $this->show_single_cat ) {
			$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
			$url .= "_single";
		}
		if ( $subcategory ) {
			$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
			$url .= "_subcat=" . $subcategory;
		}
		foreach ( $applied_filters as $i => $af ) {
			if ( $af->filter->name == $filter_to_remove ) {
				continue;
			}
			if ( count( $af->values ) == 0 ) {
				// do nothing
			} elseif ( count( $af->values ) == 1 ) {
				$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
				$url .= urlencode( str_replace( ' ', '_', $af->filter->name ) ) . "=" . urlencode( str_replace( ' ', '_', $af->values[0]->text ) );
			} else {
				usort( $af->values, [ "SDFilterValue", "compare" ] );
				foreach ( $af->values as $j => $fv ) {
					$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
					$url .= urlencode( str_replace( ' ', '_', $af->filter->name ) ) . "[$j]=" . urlencode( str_replace( ' ', '_', $fv->text ) );
				}
			}
			if ( $af->search_terms != null ) {
				foreach ( $af->search_terms as $j => $search_term ) {
					$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
					$url .= '_search_' . urlencode( str_replace( ' ', '_', $af->filter->name ) . '[' . $j . ']' ) . "=" . urlencode( str_replace( ' ', '_', $search_term ) );
				}
			}
		}
		return $url;
	}

	/**
	 * Creates a temporary database table of values that match the current
	 * set of filters selected by the user - used for displaying
	 * all remaining filters
	 */
	private function createTempTable( $category, $subcategory, $subcategories, $applied_filters ) {
		$dbw = wfGetDB( DB_MASTER );

		$temporaryTableManager = new TemporaryTableManager( $dbw );

		$sql0 = "DROP TABLE IF EXISTS semantic_drilldown_values;";
		$temporaryTableManager->queryWithAutoCommit( $sql0, __METHOD__ );

		$sql1 = "CREATE TEMPORARY TABLE semantic_drilldown_values ( id INT NOT NULL )";
		$temporaryTableManager->queryWithAutoCommit( $sql1, __METHOD__ );

		$sql2 = "CREATE INDEX id_index ON semantic_drilldown_values ( id )";
		$temporaryTableManager->queryWithAutoCommit( $sql2, __METHOD__ );

		$sql3 = "INSERT INTO semantic_drilldown_values SELECT ids.smw_id AS id\n";
		$sql3 .= $this->getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters );
		$temporaryTableManager->queryWithAutoCommit( $sql3, __METHOD__ );
	}

	/**
	 * Creates a SQL statement, lacking only the initial "SELECT"
	 * clause, to get all the pages that match all the previously-
	 * selected filters, plus the one new filter (with value) that
	 * was passed in to this function.
	 */
	private function getSQLFromClauseForField( $new_filter ) {
		$sql = "FROM semantic_drilldown_values sdv
	LEFT OUTER JOIN semantic_drilldown_filter_values sdfv
	ON sdv.id = sdfv.id
	WHERE ";
		$sql .= $new_filter->checkSQL( "sdfv.value" );
		return $sql;
	}

	/**
	 * Very similar to getSQLFromClauseForField(), except that instead
	 * of a new filter passed in, it's a subcategory, plus all that
	 * subcategory's child subcategories, to ensure completeness.
	 */
	private function getSQLFromClauseForCategory( $subcategory, $child_subcategories ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( SDUtils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( SDUtils::getCategoryInstancesTableName() );
		$ns_cat = NS_CATEGORY;
		$subcategory_escaped = $dbr->addQuotes( $subcategory );
		$sql = "FROM semantic_drilldown_values sdv
	JOIN $smwCategoryInstances inst
	ON sdv.id = inst.s_id
	WHERE inst.o_id IN
		(SELECT MIN(smw_id) FROM $smwIDs
		WHERE smw_namespace = $ns_cat AND (smw_title = $subcategory_escaped ";
		foreach ( $child_subcategories as $i => $subcat ) {
			$subcat_escaped = $dbr->addQuotes( $subcat );
			$sql .= "OR smw_title = $subcat_escaped ";
		}
		$sql .= ")) ";
		return $sql;
	}

	/**
	 * Returns everything from the FROM clause onward for a SQL statement
	 * to get all pages that match a certain set of criteria for
	 * category, subcategory and filters.
	 */
	private function getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters ) {
		$dbr = wfGetDB( DB_REPLICA );
		$smwIDs = $dbr->tableName( SDUtils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( SDUtils::getCategoryInstancesTableName() );
		$cat_ns = NS_CATEGORY;
		$prop_ns = SMW_NS_PROPERTY;

		$sql = "FROM $smwIDs ids
	JOIN $smwCategoryInstances insts
	ON ids.smw_id = insts.s_id
	AND ids.smw_namespace != $cat_ns ";
		foreach ( $applied_filters as $i => $af ) {
			// if any of this filter's values is 'none',
			// include another table to get this information
			$includes_none = false;
			foreach ( $af->values as $fv ) {
				if ( $fv->text === '_none' || $fv->text === ' none' ) {
					$includes_none = true;
					break;
				}
			}
			if ( $includes_none ) {
				$property_table_name = $dbr->tableName( $af->filter->getTableName() );
				if ( $af->filter->property_type === 'page' ) {
					$property_table_nickname = "nr$i";
					$property_field = 'p_id';
				} else {
					$property_table_nickname = "na$i";
					$property_field = 'p_id';
				}
				$property_value = str_replace( ' ', '_', $af->filter->property );
				$property_value = str_replace( "'", "\'", $property_value );
				// The sub-query that returns an SMW ID contains
				// a "SELECT MIN", even though by definition it
				// doesn't need to, because of occasional bugs
				// in SMW where the same page gets two
				// different SMW IDs.

				$sql .= "LEFT OUTER JOIN
	(SELECT s_id
	FROM $property_table_name
	WHERE $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)) $property_table_nickname
	ON ids.smw_id = $property_table_nickname.s_id ";
			}
		}
		foreach ( $applied_filters as $i => $af ) {
			$sql .= "\n	";
			$property_table_name = $dbr->tableName( $af->filter->getTableName() );
			if ( $af->filter->property_type === 'page' ) {
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $property_table_name r$i ON ids.smw_id = r$i.s_id\n	";
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN $smwIDs o_ids$i ON r$i.o_id = o_ids$i.smw_id ";
			} else {
				$sql .= "JOIN $property_table_name a$i ON ids.smw_id = a$i.s_id ";
			}
		}
		if ( $subcategory ) {
			$actual_cat = str_replace( ' ', '_', $subcategory );
		} else {
			$actual_cat = str_replace( ' ', '_', $category );
		}
		$actual_cat = str_replace( "'", "\'", $actual_cat );
		$sql .= "WHERE insts.o_id IN
	(SELECT smw_id FROM $smwIDs cat_ids
	WHERE smw_namespace = $cat_ns AND (smw_title = '$actual_cat'";
		foreach ( $subcategories as $i => $subcat ) {
			$subcat = str_replace( "'", "\'", $subcat );
			$sql .= " OR smw_title = '{$subcat}'";
		}
		$sql .= ")) ";
		foreach ( $applied_filters as $i => $af ) {
			$property_value = $af->filter->escaped_property;
			$value_field = $af->filter->getValueField();
			if ( $af->filter->property_type === 'page' ) {
				$property_field = "r$i.p_id";
				$sql .= "\n	AND ($property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)";
				if ( $includes_none ) {
					$sql .= " OR $property_field IS NULL";
				}
				$sql .= ")\n	AND ";
				$value_field = "o_ids$i.smw_title";
			} else {
				$property_field = "a$i.p_id";
				$sql .= "\n	AND $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns) AND ";
				if ( strncmp( $value_field, '(IF(o_blob IS NULL', 18 ) === 0 ) {
					$value_field = str_replace( 'o_', "a$i.o_", $value_field );
				} else {
					$value_field = "a$i.$value_field";
				}
			}
			$sql .= $af->checkSQL( $value_field );
		}
		return $sql;
	}

	/**
	 * Gets the number of pages matching both the currently-selected
	 * set of filters and either a new subcategory or a new filter.
	 */
	private function getNumResults( $subcategory, $subcategories, $new_filter = null ) {
		$dbw = wfGetDB( DB_MASTER );

		// Escape the given values to prevent SQL injection
		$subcategory = $dbw->addQuotes( $subcategory );
		foreach ( $subcategories as $key => $value ) {
			$subcategories[$key] = $dbw->addQuotes( $value );
		}

		$sql = "SELECT COUNT(DISTINCT sdv.id) ";
		if ( $new_filter ) {
			$sql .= $this->getSQLFromClauseForField( $new_filter );
		} else {
			$sql .= $this->getSQLFromClauseForCategory( $subcategory, $subcategories );
		}
		$res = $dbw->query( $sql );
		$row = $res->fetchRow();
		return $row[0];
	}

	public function getName() {
		return "BrowseData";
	}

	public function isExpensive() {
		return false;
	}

	public function isSyndicated() {
		return false;
	}

	private function printCategoriesList( $categories ) {
		global $sdgShowCategoriesAsTabs;

		$choose_category_text = wfMessage( 'sd_browsedata_choosecategory' )->text() . wfMessage( 'colon-separator' )->text();
		if ( $sdgShowCategoriesAsTabs ) {
			$cats_wrapper_class = "drilldown-categories-tabs-wrapper";
			$cats_list_class = "drilldown-categories-tabs";
		} else {
			$cats_wrapper_class = "drilldown-categories-wrapper";
			$cats_list_class = "drilldown-categories";
		}
		$text = <<<END

				<div id="$cats_wrapper_class">

END;
		if ( $sdgShowCategoriesAsTabs ) {
		$text .= <<<END
					<p id="categories-header">$choose_category_text</p>
					<ul id="$cats_list_class">

END;
		} else {
			$text .= <<<END
					<ul id="$cats_list_class">
					<li id="categories-header">$choose_category_text</li>

END;
		}
		foreach ( $categories as $i => $category ) {
			$category_children = SDUtils::getCategoryChildren( $category, false, 5 );
			$category_str = $category . " (" . count( array_unique( $category_children ) ) . ")";
			if ( str_replace( '_', ' ', $this->category ) == $category ) {
				$text .= '						<li class="category selected">';
				$text .= $category_str;
			} else {
				$text .= '						<li class="category">';
				$category_url = $this->makeBrowseURL( $category );
				$text .= "<a href=\"$category_url\" title=\"$choose_category_text\">$category_str</a>";
			}
			$text .= "</li>\n";
		}
		$text .= <<<END
					</li>
				</ul>
			</div>

END;
		return $text;
	}

	/**
	 * Create the full display of the filter line, once the text for
	 * the "results" (values) for this filter has been created.
	 */
	private function printFilterLine( $filterName, $isApplied, $isNormalFilter, $resultsLine, $filter ) {
		global $sdgScriptPath;
		global $sdgDisableFilterCollapsible;

		if ( isset( $filter->int ) ) {
			$filterName = wfMessage( $filter->int )->text();
		}

		$additionalClasses = '';
		if ( $isApplied ) {
			$additionalClasses .= ' is-applied';
		}
		if ( $isNormalFilter ) {
			$additionalClasses .= ' is-normal-filter';
		}

		if ( $sdgDisableFilterCollapsible ) {
			$text  = '<div class="drilldown-filter' . $additionalClasses . '">';
			$text .= "	<div class='drilldown-filter-label'>  \t\t\t\t\t$filterName</div>";
			$text .= '	<div class="drilldown-filter-values">' . $resultsLine . '</div>';
			$text .= '</div>';

			return $text;
		}

		$text = <<<END
				<div class="drilldown-filter' . $additionalClasses . '">
					<div class="drilldown-filter-label">

END;
		// No point showing arrow if it's just a
		// single text or date input.
		if ( $isNormalFilter ) {
			if ( $isApplied ) {
				$arrowImage = "$sdgScriptPath/skins/right-arrow.png";
			} else {
				$arrowImage = "$sdgScriptPath/skins/down-arrow.png";
			}
			$text .= <<<END
					<a class="drilldown-values-toggle" style="cursor: default;"><img src="$arrowImage" /></a>

END;
		}
		$text .= "\t\t\t\t\t$filterName:";
		if ( $isApplied ) {
			$add_another_str = wfMessage( 'sd_browsedata_addanothervalue' )->text();
			$text .= " <span class=\"drilldown-filter-notes\">($add_another_str)</span>";
		}
		$displayText = ( $isApplied ) ? 'style="display: none;"' : '';
		$text .= <<<END

					</div>
					<div class="drilldown-filter-values" $displayText>$resultsLine
					</div>
				</div>

END;
		return $text;
	}

	/**
	 * Print a "nice" version of the value for a filter, if it's some
	 * special case like 'other', 'none', a boolean, etc.
	 */
	private function printFilterValue( $filter, $value ) {
		$value = str_replace( '_', ' ', $value );
		// if it's boolean, display something nicer than "0" or "1"
		if ( $value === ' other' ) {
			return wfMessage( 'sd_browsedata_other' )->text();
		} elseif ( $value === ' none' ) {
			return wfMessage( 'sd_browsedata_none' )->text();
		} elseif ( $filter->property_type === 'boolean' ) {
			return SDUtils::booleanToString( $value );
		} elseif ( $filter->property_type === 'date' && strpos( $value, '//T' ) ) {
			return str_replace( '//T', '', $value );
		} else {
			return $value;
		}
	}

	/**
	 * Print the line showing 'OR' values for a filter that already has
	 * at least one value set
	 */
	private function printAppliedFilterLine( $af ) {
		$results_line = "";
		foreach ( $this->applied_filters as $af2 ) {
			if ( $af->filter->name == $af2->filter->name ) {
				$current_filter_values = $af2->values;
			}
		}
		if ( $af->filter->allowed_values != null ) {
			$or_values = $af->filter->allowed_values;
		} else {
			$or_values = $af->getAllOrValues( $this->category );
		}
		if ( $af->search_terms != null ) {
			// HACK - printComboBoxInput() needs values as the
			// *keys* of the array
			$filter_values = [];
			foreach ( $or_values as $or_value ) {
				$filter_values[$or_value] = '';
			}
			$curSearchTermNum = count( $af->search_terms );
			$results_line = $this->printComboBoxInput( $af->filter->name, $curSearchTermNum, $filter_values );
			return $this->printFilterLine( $af->filter->name, true, true, $results_line, $af->filter );
		/*
		} elseif ( $af->lower_date != null || $af->upper_date != null ) {
			// With the current interface, this code will never get
			// called; but at some point in the future, there may
			// be a date-range input again.
			$results_line = $this->printDateRangeInput( $af->filter->name, $af->lower_date, $af->upper_date );
			return $this->printFilterLine( $af->filter->name, true, false, $results_line );
		*/
		}
		// add 'Other' and 'None', regardless of whether either has
		// any results - add 'Other' only if it's not a date field
		if ( $af->filter->property_type != 'date' ) {
			$or_values[] = '_other';
		}
		$or_values[] = '_none';
		foreach ( $or_values as $i => $value ) {
			if ( $i > 0 ) {
				$results_line .= " · ";
			}
			$filter_text = SDUtils::escapeString( $this->printFilterValue( $af->filter, $value ) );
			$applied_filters = $this->applied_filters;
			foreach ( $applied_filters as $af2 ) {
				if ( $af->filter->name == $af2->filter->name ) {
					$or_fv = SDFilterValue::create( $value, $af->filter );
					$af2->values = array_merge( $current_filter_values, [ $or_fv ] );
				}
			}
			// show the list of OR values, only linking
			// the ones that haven't been used yet
			$found_match = false;
			foreach ( $current_filter_values as $fv ) {
				if ( $value == $fv->text ) {
					$found_match = true;
					break;
				}
			}
			if ( $found_match ) {
				$results_line .= "\n				$filter_text";
			} else {
				$filter_url = $this->makeBrowseURL( $this->category, $applied_filters, $this->subcategory );
				$results_line .= "\n						" . '<a href="' . $filter_url . '" title="' . wfMessage( 'sd_browsedata_filterbyvalue' )->text() . '">' . $filter_text . '</a>';
			}
			foreach ( $applied_filters as $af2 ) {
				if ( $af->filter->name == $af2->filter->name ) {
					$af2->values = $current_filter_values;
				}
			}
		}
		return $this->printFilterLine( $af->filter->name, true, true, $results_line, $af->filter );
	}

	private function printUnappliedFilterValues( $cur_url, $f, $filter_values ) {
		global $sdgFiltersSmallestFontSize, $sdgFiltersLargestFontSize;

		$results_line = "";
		// set font-size values for filter "tag cloud", if the
		// appropriate global variables are set
		if ( $sdgFiltersSmallestFontSize > 0 && $sdgFiltersLargestFontSize > 0 ) {
			$lowest_num_results = min( $filter_values );
			$highest_num_results = max( $filter_values );
			if ( $lowest_num_results != $highest_num_results ) {
				$scale_factor = ( $sdgFiltersLargestFontSize - $sdgFiltersSmallestFontSize ) / ( log( $highest_num_results ) - log( $lowest_num_results ) );
			}
		}
		// now print the values
		$num_printed_values = 0;
		foreach ( $filter_values as $value_str => $num_results ) {
			if ( $num_printed_values++ > 0 ) {
				$results_line .= " · ";
			}
			$filter_text = SDUtils::escapeString( $this->printFilterValue( $f, $value_str ) );
			$filter_text .= "&nbsp;($num_results)";
			$filter_url = $cur_url . urlencode( str_replace( ' ', '_', $f->name ) ) . '=' . urlencode( str_replace( ' ', '_', $value_str ) );
			if ( $sdgFiltersSmallestFontSize > 0 && $sdgFiltersLargestFontSize > 0 ) {
				if ( $lowest_num_results != $highest_num_results ) {
					$font_size = round( ( ( log( $num_results ) - log( $lowest_num_results ) ) * $scale_factor ) + $sdgFiltersSmallestFontSize );
				} else {
					$font_size = ( $sdgFiltersSmallestFontSize + $sdgFiltersLargestFontSize ) / 2;
				}
				$results_line .= "\n						" . '<a href="' . $filter_url . '" title="' . wfMessage( 'sd_browsedata_filterbyvalue' )->text() . '" style="font-size: ' . $font_size . 'px">' . $filter_text . '</a>';
			} else {
				$results_line .= "\n						" . '<a href="' . $filter_url . '" title="' . wfMessage( 'sd_browsedata_filterbyvalue' )->text() . '">' . $filter_text . '</a>';
			}
		}
		return $results_line;
	}

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	private function getNearestNiceNumber( $num, $previousNum, $nextNum ) {
		if ( $previousNum == null ) {
			$smallestDifference = $nextNum - $num;
		} elseif ( $nextNum == null ) {
			$smallestDifference = $num - $previousNum;
		} else {
			$smallestDifference = min( $num - $previousNum, $nextNum - $num );
		}

		$base10LogOfDifference = log10( $smallestDifference );
		$significantFigureOfDifference = floor( $base10LogOfDifference );

		$powerOf10InCorrectPlace = pow( 10, $significantFigureOfDifference );
		$significantDigitsOnly = round( $num / $powerOf10InCorrectPlace );
		$niceNumber = $significantDigitsOnly * $powerOf10InCorrectPlace;

		// Special handling if it's the first or last number in the
		// series - we have to make sure that the "nice" equivalent is
		// on the right "side" of the number.

		// That's especially true for the last number -
		// it has to be greater, not just equal to, because of the way
		// number filtering works.
		// ...or does it??
		if ( $previousNum == null && $niceNumber > $num ) {
			$niceNumber -= $powerOf10InCorrectPlace;
		}
		if ( $nextNum == null && $niceNumber < $num ) {
			$niceNumber += $powerOf10InCorrectPlace;
		}

		return $niceNumber;
	}

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	private function generateIndividualFilterValuesFromNumbers( $uniqueValues ) {
		$propertyValues = [];
		foreach ( $uniqueValues as $uniqueValue => $numInstances ) {
			$curBucket = [
				'lowerNumber' => $uniqueValue,
				'higherNumber' => null,
				'numValues' => $numInstances
			];
			$propertyValues[] = $curBucket;
		}
		return $propertyValues;
	}

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	private function generateFilterValuesFromNumbers( $numberArray ) {
		global $sdgNumRangesForNumberFilters;

		$numNumbers = count( $numberArray );

		// First, find the number of unique values - if it's the value
		// of $sdgNumRangesForNumberFilters, or fewer, just display
		// each one as its own bucket.
		$numUniqueValues = 0;
		$uniqueValues = [];
		foreach ( $numberArray as $curNumber ) {
			if ( !array_key_exists( $curNumber, $uniqueValues ) ) {
				$uniqueValues[$curNumber] = 1;
				$numUniqueValues++;
				if ( $numUniqueValues > $sdgNumRangesForNumberFilters ) {
					continue;
				}
			} else {
				// We do this now to save time on the next step,
				// if we're creating individual filter values.
				$uniqueValues[$curNumber]++;
			}
		}

		if ( $numUniqueValues <= $sdgNumRangesForNumberFilters ) {
			return $this->generateIndividualFilterValuesFromNumbers( $uniqueValues );
		}

		$propertyValues = [];
		$separatorValue = $numberArray[0];
		$startIndexOfBucket = 0;
		$endIndexOfBucket;

		// Make sure there are at least, on average, five numbers per
		// bucket.
		// @HACK - add 3 to the number so that we don't end up with
		// just one bucket ( 7 + 3 / 5 = 2).
		$numBuckets = min( $sdgNumRangesForNumberFilters, floor( ( $numNumbers + 3 ) / 5 ) );
		$bucketSeparators = [];
		$bucketSeparators[] = $numberArray[0];
		for ( $i = 1; $i < $numBuckets; $i++ ) {
			$separatorIndex = floor( $numNumbers * $i / $numBuckets ) - 1;
			$previousSeparatorValue = $separatorValue;
			$separatorValue = $numberArray[$separatorIndex];
			if ( $separatorValue == $previousSeparatorValue ) {
				continue;
			}
			$bucketSeparators[] = $separatorValue;
		}
		$lastValue = ceil( $numberArray[count( $numberArray ) - 1] );
		if ( $lastValue != $separatorValue ) {
			$bucketSeparators[] = $lastValue;
		}

		// Get the closest "nice" (few significant digits) number for
		// each of the bucket separators, with the number of significant digits
		// required based on their proximity to their neighbors.
		// The first and last separators need special handling.
		$bucketSeparators[0] = $this->getNearestNiceNumber( $bucketSeparators[0], null, $bucketSeparators[1] );
		for ( $i = 1; $i < count( $bucketSeparators ) - 1; $i++ ) {
			$bucketSeparators[$i] = $this->getNearestNiceNumber( $bucketSeparators[$i], $bucketSeparators[$i - 1], $bucketSeparators[$i + 1] );
		}
		$bucketSeparators[count( $bucketSeparators ) - 1] = $this->getNearestNiceNumber( $bucketSeparators[count( $bucketSeparators ) - 1], $bucketSeparators[count( $bucketSeparators ) - 2], null );

		$oldSeparatorValue = $bucketSeparators[0];
		for ( $i = 1; $i < count( $bucketSeparators ); $i++ ) {
			$separatorValue = $bucketSeparators[$i];
			$propertyValues[] = [
				'lowerNumber' => $oldSeparatorValue,
				'higherNumber' => $separatorValue,
				'numValues' => 0,
			];
			$oldSeparatorValue = $separatorValue;
		}

		$curSeparator = 0;
		for ( $i = 0; $i < count( $numberArray ); $i++ ) {
			if ( $curSeparator < count( $propertyValues ) - 1 ) {
				$curNumber = $numberArray[$i];
				while ( ( $curSeparator < count( $bucketSeparators ) - 2 ) && ( $curNumber >= $bucketSeparators[$curSeparator + 1] ) ) {
					$curSeparator++;
				}
			}
			$propertyValues[$curSeparator]['numValues']++;
		}

		return $propertyValues;
	}

	private function printNumberRanges( $filter_name, $filter_values ) {
		// We generate $cur_url here, instead of passing it in, because
		// if there's a previous value for this filter it may be
		// removed.
		$cur_url = $this->makeBrowseURL( $this->category, $this->applied_filters, $this->subcategory, $filter_name );
		$cur_url .= ( strpos( $cur_url, '?' ) ) ? '&' : '?';

		$numberArray = [];
		foreach ( $filter_values as $value => $num_instances ) {
			for ( $i = 0; $i < $num_instances; $i++ ) {
				$numberArray[] = $value;
			}
		}
		// Put into numerical order.
		sort( $numberArray );

		$text = '';
		$filterValues = $this->generateFilterValuesFromNumbers( $numberArray );
		foreach ( $filterValues as $i => $curBucket ) {
			if ( $i > 0 ) {
				$text .= " &middot; ";
			}
			// number_format() adds in commas for each thousands place.
			$curText = number_format( $curBucket['lowerNumber'] );
			if ( $curBucket['higherNumber'] != null ) {
				$curText .= ' - ' . number_format( $curBucket['higherNumber'] );
			}
			$curText .= ' (' . $curBucket['numValues'] . ') ';
			$filterURL = $cur_url . "$filter_name=" . $curBucket['lowerNumber'];
			if ( $curBucket['higherNumber'] != null ) {
				$filterURL .= '-' . $curBucket['higherNumber'];
			}
			$text .= '<a href="' . $filterURL . '">' . $curText . '</a>';
		}
		return $text;
	}

	private function printComboBoxInput( $filter_name, $instance_num, $filter_values, $cur_value = null ) {
		$filter_name = str_replace( ' ', '_', $filter_name );
		// URL-decode the filter name - necessary if it contains
		// any non-Latin characters.
		$filter_name = urldecode( $filter_name );

		// Add on the instance number, since it can be one of a string
		// of values.
		$filter_name .= '[' . $instance_num . ']';

		$inputName = "_search_$filter_name";

		$filter_url = $this->makeBrowseURL( $this->category, $this->applied_filters, $this->subcategory );

		$text = <<< END
<form method="get" action="$filter_url">

END;

		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			if ( $key != $inputName ) {
				if ( is_array( $val ) ) {
					foreach ( $val as $i => $realVal ) {
						$keyString = $key . '[' . $i . ']';
						$text .= Html::hidden( $keyString, $realVal ) . "\n";
					}
				} else {
					$text .= Html::hidden( $key, $val ) . "\n";
				}
			}
		}

		$text .= <<< END
	<div class="ui-widget">
		<select class="semanticDrilldownCombobox" name="$cur_value">
			<option value="$inputName"></option>;

END;
		foreach ( $filter_values as $value => $num_instances ) {
			if ( $value != '_other' && $value != '_none' ) {
				$display_value = str_replace( '_', ' ', $value );
				$text .= "\t\t" . Html::element( 'option', [ 'value' => $display_value ], $display_value ) . "\n";
			}
		}

		$text .= <<<END
		</select>
	</div>

END;

		$text .= Html::input(
			null,
			wfMessage( 'sd_browsedata_search' )->text(),
			'submit',
			[ 'style' => 'margin: 4px 0 8px 0;' ]
		) . "\n";
		$text .= "</form>\n";
		return $text;
	}

	private function printDateInput( $input_name, $cur_value = null ) {
		$this->getOutput()->enableOOUI();
		$this->getOutput()->addModuleStyles( 'mediawiki.widgets.DateInputWidget.styles' );

		$widget = new DateInputWidget( [
			'name' => $input_name,
			'value' => $cur_value
		] );
		return (string)$widget;
	}

	private function printDateRangeInput( $filter_name, $lower_date = null, $upper_date = null ) {
		$start_label = wfMessage( 'sd_browsedata_daterangestart' )->text();
		$end_label = wfMessage( 'sd_browsedata_daterangeend' )->text();
		$start_month_input = $this->printDateInput( "_lower_$filter_name", $lower_date );
		$end_month_input = $this->printDateInput( "_upper_$filter_name", $upper_date );
		$text = <<<END
<form method="get">
<p>$start_label $start_month_input
$end_label $end_month_input</p>

END;
		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			if ( $key == "_lower_$filter_name" || $key == "_upper_$filter_name" ) {
				// Prevent older value from querystring from overriding the value from inputs.
				continue;
			}

			if ( is_array( $val ) ) {
				foreach ( $val as $realKey => $realVal ) {
					$text .= Html::hidden( $key . '[' . $realKey . ']', $realVal ) . "\n";
				}
			} else {
				$text .= Html::hidden( $key, $val ) . "\n";
			}
		}
		$submitButton = Html::input( null, wfMessage( 'searchresultshead' )->text(), 'submit' );
		$text .= Html::rawElement( 'p', null, $submitButton ) . "\n";
		$text .= "</form>\n";
		return $text;
	}

	/**
	 * Print the line showing 'AND' values for a filter that has not
	 * been applied to the drilldown
	 */
	private function printUnappliedFilterLine( $f, $cur_url = null ) {
		global $sdgMinValuesForComboBox;
		global $sdgHideFiltersWithoutValues;

		$f->createTempTable();
		$found_results_for_filter = false;
		if ( empty( $f->allowed_values ) ) {
			if ( $f->property_type == 'date' ) {
				list( $filter_values, $lower_date, $upper_date ) = $f->getTimePeriodValues();
			} else {
				$filter_values = $f->getAllValues();
			}
			if ( !is_array( $filter_values ) ) {
				$f->dropTempTable();
				return $this->printFilterLine( $f->name, false, false, $filter_values, $f );
			}
			if ( count( $filter_values ) > 0 ) {
				$found_results_for_filter = true;
			}
		} else {
			$filter_values = [];
			foreach ( $f->allowed_values as $value ) {
				$new_filter = SDAppliedFilter::create( $f, $value );
				$num_results = $this->getNumResults( $this->subcategory, $this->all_subcategories, $new_filter );
				if ( $num_results > 0 ) {
					$filter_values[$value] = $num_results;
				}
			}
		}
		// Now get values for 'Other' and 'None', as well
		// - don't show 'Other' if filter values were
		// obtained dynamically.
		if ( !empty( $f->allowed_values ) ) {
			$other_filter = SDAppliedFilter::create( $f, ' other' );
			$num_results = $this->getNumResults( $this->subcategory, $this->all_subcategories, $other_filter );
			if ( $num_results > 0 ) {
				$filter_values['_other'] = $num_results;
			}
		}
		// Show 'None' only if any other results have been found, and
		// if it's not a numeric filter.
		if ( !empty( $f->allowed_values ) ) {
			$fv = SDFilterValue::create( $f->allowed_values[0] );
			if ( !$fv->is_numeric ) {
				$none_filter = SDAppliedFilter::create( $f, ' none' );
				$num_results = $this->getNumResults( $this->subcategory, $this->all_subcategories, $none_filter );
				if ( $num_results > 0 ) {
					$filter_values['_none'] = $num_results;
				}
			}
		}

		$filter_name = urlencode( str_replace( ' ', '_', $f->name ) );
		$normal_filter = true;
		if ( count( $filter_values ) == 0 ) {
			$results_line = '(' . wfMessage( 'sd_browsedata_novalues' )->text() . ')';
		} elseif ( $f->property_type == 'number' ) {
			$results_line = $this->printNumberRanges( $filter_name, $filter_values );
		} elseif ( count( $filter_values ) >= $sdgMinValuesForComboBox ) {
			$results_line = $this->printComboBoxInput( $filter_name, 0, $filter_values );
			$normal_filter = false;
		} else {
			// If $cur_url wasn't passed in, we have to create it.
			$cur_url = $this->makeBrowseURL( $this->category, $this->applied_filters, $this->subcategory, $f->name );
			$cur_url .= ( strpos( $cur_url, '?' ) ) ? '&' : '?';
			$results_line = $this->printUnappliedFilterValues( $cur_url, $f, $filter_values );
		}

		// For dates additionally add two datepicker inputs (Start/End) to select a custom interval.
		if ( $f->property_type == 'date' && count( $filter_values ) != 0 ) {
			$results_line .= '<br>' . $this->printDateRangeInput( $filter_name, $lower_date, $upper_date );
		}

		$text = $this->printFilterLine( $f->name, false, $normal_filter, $results_line, $f );
		$f->dropTempTable();

		if ( $sdgHideFiltersWithoutValues && count( $filter_values ) == 0 ) {
			$text = '';
		}

		return $text;
	}

	protected function getPageHeader() {
		global $sdgScriptPath;
		global $sdgFiltersSmallestFontSize, $sdgFiltersLargestFontSize;

		// If this was called from the command line, exit.
		if ( PHP_SAPI === 'cli' ) {
			return '';
		}

		$categories = SDUtils::getCategoriesForBrowsing();
		// if there are no categories, escape quickly
		if ( count( $categories ) == 0 ) {
			return "";
		}
		$subcategory_text = wfMessage( 'sd_browsedata_subcategory' )->text();

		$header = "";

		// Add intro template
		$headerPage = SDUtils::getDrilldownHeader( $this->category );
		if ( $headerPage !== '' ) {
			$title = Title::newFromText( $headerPage );
			$page = WikiPage::factory( $title );
			if ( $page->exists() ) {
				$content = $page->getContent();
				$pageContent = $content->serialize();
				$out = $this->getOutput();
				$header .= $out->parseInline( $pageContent );
			}
		}

		// wrap output in Bootstrap panel
		$header .= '<div class="panel panel-default"><div class="panel-heading">&nbsp;</div><div class="panel-body">';

		$this->show_single_cat = $this->getRequest()->getCheck( '_single' );
		if ( !$this->show_single_cat ) {
			$header .= $this->printCategoriesList( $categories );
		}
		// if there are no subcategories or filters for this
		// category, escape now that we've (possibly) printed the
		// categories list
		if ( ( count( $this->next_level_subcategories ) == 0 ) &&
			( count( $this->applied_filters ) == 0 ) &&
			( count( $this->remaining_filters ) == 0 )
		) {
			return $header;
		}
		$header .= '				<div id="drilldown-header">' . "\n";
		if ( count( $this->applied_filters ) > 0 || $this->subcategory ) {
			$category_url = $this->makeBrowseURL( $this->category );
			$header .= '<a href="' . $category_url . '" title="' . wfMessage( 'sd_browsedata_resetfilters' )->text() . '">' . str_replace( '_', ' ', $this->category ) . '</a>';
		}

		if ( $this->subcategory ) {
			$header .= " > ";
			$header .= "$subcategory_text: ";
			$subcat_string = str_replace( '_', ' ', $this->subcategory );
			$remove_filter_url = $this->makeBrowseURL( $this->category, $this->applied_filters );
			$header .= "\n" . '				<span class="drilldown-header-value">' . $subcat_string . '</span> <a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removesubcategoryfilter' )->text() . '"><img src="' . $sdgScriptPath . '/skins/filter-x.png" /></a> ';
		}
		foreach ( $this->applied_filters as $i => $af ) {
			$header .= ( !$this->subcategory && $i == 0 ) ? " > " : "\n					<span class=\"drilldown-header-value\">&</span> ";
			$filter_label = $af->filter->name;
			// add an "x" to remove this filter, if it has more
			// than one value
			if ( count( $this->applied_filters[$i]->values ) > 1 ) {
				$temp_filters_array = $this->applied_filters;
				array_splice( $temp_filters_array, $i, 1 );
				$remove_filter_url = $this->makeBrowseURL( $this->category, $temp_filters_array, $this->subcategory );
				array_splice( $temp_filters_array, $i, 0 );
				$header .= $filter_label . ' <a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdgScriptPath . '/skins/filter-x.png" /></a> : ';
			} else {
				$header .= "$filter_label: ";
			}
			foreach ( $af->values as $j => $fv ) {
				if ( $j > 0 ) {
					$header .= ' <span class="drilldown-or">' . wfMessage( 'sd_browsedata_or' )->text() . '</span> ';
				}
				$filter_text = SDUtils::escapeString( $this->printFilterValue( $af->filter, $fv->text ) );
				$temp_filters_array = $this->applied_filters;
				$removed_values = array_splice( $temp_filters_array[$i]->values, $j, 1 );
				$remove_filter_url = $this->makeBrowseURL( $this->category, $temp_filters_array, $this->subcategory );
				array_splice( $temp_filters_array[$i]->values, $j, 0, $removed_values );
				$header .= '				<span class="drilldown-header-value">' . $filter_text . '</span> <a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdgScriptPath . '/skins/filter-x.png" /></a>';
			}

			if ( $af->search_terms != null ) {
				foreach ( $af->search_terms as $j => $search_term ) {
					if ( $j > 0 ) {
						$header .= ' <span class="drilldown-or">' . wfMessage( 'sd_browsedata_or' )->text() . '</span> ';
					}
					$temp_filters_array = $this->applied_filters;
					$removed_values = array_splice( $temp_filters_array[$i]->search_terms, $j, 1 );
					$remove_filter_url = $this->makeBrowseURL( $this->category, $temp_filters_array, $this->subcategory );
					array_splice( $temp_filters_array[$i]->search_terms, $j, 0, $removed_values );
					$header .= "\n\t" . '<span class="drilldown-header-value">~ \'' . $search_term . '\'</span> <a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdgScriptPath . '/skins/filter-x.png" /> </a>';
				}
			} elseif ( $af->lower_date != null || $af->upper_date != null ) {
				$header .= "\n\t<span class=\"drilldown-header-value\">" . $af->lower_date_string . " - " . $af->upper_date_string . "</span>";
			}
		}
		$header .= "</div>\n";
		$drilldown_description = wfMessage( 'sd_browsedata_docu' )->text();
		$header .= "				<p>$drilldown_description</p>\n";
		// display the list of subcategories on one line, and below
		// it every filter, each on its own line; each line will
		// contain the possible values, and, in parentheses, the
		// number of pages that match that value
		$header .= "				<div class=\"drilldown-filters\">\n";
		$cur_url = $this->makeBrowseURL( $this->category, $this->applied_filters, $this->subcategory );
		$cur_url .= ( strpos( $cur_url, '?' ) ) ? '&' : '?';
		$this->createTempTable( $this->category, $this->subcategory, $this->all_subcategories, $this->applied_filters );
		$num_printed_values = 0;
		if ( count( $this->next_level_subcategories ) > 0 ) {
			$results_line = "";
			// loop through to create an array of subcategory
			// names and their number of values, then loop through
			// the array to print them - we loop through twice,
			// instead of once, to be able to print a tag-cloud
			// display if necessary
			$subcat_values = [];
			foreach ( $this->next_level_subcategories as $i => $subcat ) {
				$further_subcats = SDUtils::getCategoryChildren( $subcat, true, 10 );
				$num_results = $this->getNumResults( $subcat, $further_subcats );
				$subcat_values[$subcat] = $num_results;
			}
			// get necessary values for creating the tag cloud,
			// if appropriate
			if ( $sdgFiltersSmallestFontSize > 0 && $sdgFiltersLargestFontSize > 0 ) {
				$lowest_num_results = min( $subcat_values );
				$highest_num_results = max( $subcat_values );
				if ( $lowest_num_results != $highest_num_results ) {
					$scale_factor = ( $sdgFiltersLargestFontSize - $sdgFiltersSmallestFontSize ) / ( log( $highest_num_results ) - log( $lowest_num_results ) );
				}
			}

			foreach ( $subcat_values as $subcat => $num_results ) {
				if ( $num_results > 0 ) {
					if ( $num_printed_values++ > 0 ) {
						$results_line .= " · ";
					}
					$filter_text = str_replace( '_', ' ', $subcat ) . " ($num_results)";
					$filter_url = $cur_url . '_subcat=' . urlencode( $subcat );
					if ( $sdgFiltersSmallestFontSize > 0 && $sdgFiltersLargestFontSize > 0 ) {
						if ( $lowest_num_results != $highest_num_results ) {
							$font_size = round( ( ( log( $num_results ) - log( $lowest_num_results ) ) * $scale_factor ) + $sdgFiltersSmallestFontSize );
						} else {
							$font_size = ( $sdgFiltersSmallestFontSize + $sdgFiltersLargestFontSize ) / 2;
						}
						$results_line .= "\n					" . '<a href="' . $filter_url . '" title="' . wfMessage( 'sd_browsedata_filterbysubcategory' )->text() . '" style="font-size: ' . $font_size . 'px">' . $filter_text . '</a>';
					} else {
						$results_line .= "\n					" . '<a href="' . $filter_url . '" title="' . wfMessage( 'sd_browsedata_filterbysubcategory' )->text() . '">' . $filter_text . '</a>';
					}
				}
			}
			if ( $results_line != "" ) {
				$header .= "					<p><strong>$subcategory_text:</strong> $results_line</p>\n";
			}
		}
		$filters = SDUtils::loadFiltersForCategory( $this->category );
		foreach ( $filters as $f ) {
			foreach ( $this->applied_filters as $af ) {
				if ( $af->filter->name == $f->name ) {
					if ( $f->property_type == 'date' || $f->property_type == 'number' ) {
						$header .= $this->printUnappliedFilterLine( $f );
					} else {
						$header .= $this->printAppliedFilterLine( $af );
					}
				}
			}
			foreach ( $this->remaining_filters as $rf ) {
				if ( $rf->name == $f->name ) {
					$header .= $this->printUnappliedFilterLine( $rf, $cur_url );
				}
			}
		}
		$header .= "				</div> <!-- drilldown-filters -->\n";
		return $header;
	}

	/**
	 * Used to set URL for additional pages of results.
	 */
	protected function linkParameters() {
		$params = [];
		if ( $this->show_single_cat ) {
			$params['_single'] = null;
		}
		$params['_cat'] = $this->category;
		if ( $this->subcategory ) {
			$params['_subcat'] = $this->subcategory;
		}

		foreach ( $this->applied_filters as $i => $af ) {
			if ( count( $af->values ) == 1 ) {
				$key_string = str_replace( ' ', '_', $af->filter->name );
				$value_string = str_replace( ' ', '_', $af->values[0]->text );
				$params[$key_string] = $value_string;
			} else {
				// HACK - QueryPage's pagination-URL code,
				// which uses wfArrayToCGI(), doesn't support
				// two-dimensional arrays, which is what we
				// need - instead, add the brackets directly
				// to the key string
				foreach ( $af->values as $i => $value ) {
					$key_string = str_replace( ' ', '_', $af->filter->name . "[$i]" );
					$value_string = str_replace( ' ', '_', $value->text );
					$params[$key_string] = $value_string;
				}
			}

			// Add search terms (if any).
			$search_terms = $af->search_terms ?? [];
			foreach ( $search_terms as $i => $text ) {
				$key_string = '_search_' . str_replace( ' ', '_', $af->filter->name . "[$i]" );
				$value_string = str_replace( ' ', '_', $text );
				$params[$key_string] = $value_string;
			}
		}

		return $params;
	}

	protected function getSQL() {
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		$sql = "SELECT DISTINCT ids.smw_title AS title,
	ids.smw_title AS value,
	ids.smw_title AS t,
	ids.smw_namespace AS namespace,
	ids.smw_namespace AS ns,
	ids.smw_id AS id,
	ids.smw_iw AS iw,
	ids.smw_sortkey AS sortkey\n";
		$sql .= $this->getSQLFromClause( $this->category, $this->subcategory, $this->all_subcategories, $this->applied_filters );
		return $sql;
	}

	protected function getOrder() {
		return ' ORDER BY sortkey ';
	}

	protected function getOrderFields() {
		return [ 'sortkey' ];
	}

	protected function sortDescending() {
		return false;
	}

	protected function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->value );
		return Linker::link( $title, htmlspecialchars( $title->getText() ) );
	}

	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @param OutputPage $out OutputPage to print to
	 * @param Skin $skin User skin to use
	 * @param IDatabase $dbr Database (read) connection to use
	 * @param int $res Result pointer
	 * @param int $num Number of available result rows
	 * @param int $offset Paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();

		// If this was called from the command line, exit.
		if ( PHP_SAPI === 'cli' ) {
			return;
		}

		// Add Drilldown Results
		$all_display_params = SDUtils::getDisplayParamsForCategory( $this->category );
		$querystring = null;
		$printouts = $params = [];
		// only one set of params is handled for now
		if ( count( $all_display_params ) > 0 ) {
			$display_params = array_map( 'trim', $all_display_params[0] );
			list( $querystring, $params, $printouts ) = SMWQueryProcessor::getComponentsFromFunctionParams( $display_params, false );
		}
		if ( !empty( $querystring ) ) {
			$query = SMWQueryProcessor::createQuery( $querystring, $params );
		} else {
			$query = new SMWQuery();
		}
		if ( !array_key_exists( 'format', $params ) ) {
			$params['format'] = 'category';
		}

		if ( array_key_exists( 'mainlabel', $params ) ) {
			$mainlabel = $params['mainlabel'];
		} else {
			$mainlabel = '';
		}

		$r = $this->addSemanticResultWrapper( $dbr, $res, $num, $query, $mainlabel, $printouts );
		$printer = SMWQueryProcessor::getResultPrinter( $params['format'], SMWQueryProcessor::SPECIAL_PAGE, $r );

		if ( version_compare( SMW_VERSION, '1.6.1', '>' ) ) {
			SMWQueryProcessor::addThisPrintout( $printouts, $params );
			$params = SMWQueryProcessor::getProcessedParams( $params, $printouts );
		}

		$prresult = $printer->getResult(
			$r,
			$params,
			SMW_OUTPUT_WIKI
		);

		$prtext = is_array( $prresult ) ? $prresult[0] : $prresult;

		$out->addWikiText( $prtext );

		// Add outro template
		$footerPage = SDUtils::getDrilldownFooter( $this->category );

		if ( $footerPage !== '' ) {
			$title = Title::newFromText($footerPage);
			$page = WikiPage::factory( $title );

			if ( $page->exists() ) {
				$content = $page->getContent();
				$pageContent = $content->serialize();
				$out->addWikiText( $pageContent );
			}
		}

		// close the Bootstrap Panel wrapper opened in getPageHeader();
		$this->getOutput()->addHTML('</div></div>');
	}

	/**
	 * Take non-semantic result set returned by Database->query() method, and wrap it in a
	 * SMWQueryResult container for passing to any of the various semantic result printers.
	 * Code stolen largely from SMWSQLStore2QueryEngine->getInstanceQueryResult() method.
	 * (does this mean it will only work with certain semantic SQL stores?)
	 */
	private function addSemanticResultWrapper( $dbr, $res, $num, $query, $mainlabel, $printouts ) {
		$qr = [];
		$count = 0;
		$store = SDUtils::getSMWStore();
		while ( ( $count < $num ) && ( $row = $res->fetchObject() ) ) {
			$count++;
			$qr[] = new SMWDIWikiPage( $row->t, $row->ns, '' );
			if ( method_exists( $store, 'cacheSMWPageID' ) ) {
				$store->cacheSMWPageID( $row->id, $row->t, $row->ns, $row->iw, '' );
			}
		}
		if ( $res->fetchObject() ) {
			$count++;
		}

		$printrequest = new SMWPrintRequest( SMWPrintRequest::PRINT_THIS, $mainlabel );
		$main_printout = [];
		$main_printout[$printrequest->getHash()] = $printrequest;
		$printouts = array_merge( $main_printout, $printouts );

		return new SMWQueryResult( $printouts, $query, $qr, $store, ( $count > $num ) );
	}

	protected function openList( $offset ) {
	}

	protected function closeList() {
		return "\n			<br style=\"clear: both\" />\n";
	}

}
