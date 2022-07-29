<?php

namespace SD;

use ALItem;
use ALRow;
use MagicWord;
use MagicWordFactory;
use MediaWiki\MediaWikiServices;
use SMW\RequestOptions;
use SMW\SQLStore\SQLStore;
use SMWDIProperty;
use SMWDIWikiPage;
use SMWStore;
use Title;

/**
 * A class for static helper functions for Semantic Drilldown
 *
 * @author Yaron Koren
 */

class Utils {

	public static function setGlobalJSVariables( &$vars ) {
		global $sdgScriptPath;

		$vars['sdgDownArrowImage'] = "$sdgScriptPath/skins/down-arrow.png";
		$vars['sdgRightArrowImage'] = "$sdgScriptPath/skins/right-arrow.png";
		return true;
	}

	/**
	 * Helper function to get the SMW data store for different versions
	 * of SMW.
	 */
	public static function getSMWStore() {
		if ( class_exists( '\SMW\StoreFactory' ) ) {
			// SMW 1.9+
			return \SMW\StoreFactory::getStore();
		} else {
			return smwfGetStore();
		}
	}

	/**
	 * Helper function to have backward compatibility with SMW < 3.2
	 *
	 * @return \SMW\Localizer\LocalLanguage\LocalLanguage
	 */
	public static function getSMWContLang() {
		if ( function_exists( 'smwfContLang' ) ) {
			return smwfContLang();
		} else {
			global $smwgContLang;
			return $smwgContLang;
		}
	}

	/**
	 * Helper function to handle getPropertyValues().
	 *
	 * @param SMWStore $store
	 * @param string $pageName
	 * @param int $pageNamespace
	 * @param string $propID
	 * @param null|RequestOptions $requestOptions
	 *
	 * @return array of SMWDataItem
	 */
	public static function getSMWPropertyValues( SMWStore $store, $pageName, $pageNamespace, $propID, $requestOptions = null ) {
		$pageName = str_replace( ' ', '_', $pageName );
		$page = new SMWDIWikiPage( $pageName, $pageNamespace, '' );
		$property = new SMWDIProperty( $propID );
		return $store->getPropertyValues( $page, $property, $requestOptions );
	}

	/**
	 * Gets a list of the names of all categories in the wiki that aren't
	 * children of some other category - this list additionally includes,
	 * and excludes, categories that are manually set with
	 * 'SHOWINDRILLDOWN' and 'HIDEFROMDRILLDOWN', respectively.
	 */
	public static function getTopLevelCategories() {
		$categories = [];
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			[ 'page', 'categorylinks' ],
			'page_title',
			[
				'page_namespace' => NS_CATEGORY,
				'cl_to' => null,
			],
			__METHOD__,
			[],
			[
				'categorylinks' => [
					'LEFT OUTER JOIN',
					[
						'page_id = cl_from'
					]
				],
			]
		);
		foreach ( $res as $row ) {
			$categories[] = str_replace( '_', ' ', $row->page_title );
		}

		// get 'hide' and 'show' categories
		$hidden_cats = $shown_cats = [];
		$res2 = $dbr->select(
			[ 'page', 'page_props' ],
			[ 'page_title', 'pp_propname' ],
			[
				'page_namespace' => NS_CATEGORY,
				'pp_propname' => [ 'hidefromdrilldown', 'showindrilldown' ],
				'pp_value' => 'y',
			],
			__METHOD__,
			[],
			[
				'page_props' => [
					'JOIN',
					[
						'page_id = pp_page'
					]
				],
			]
		);
		foreach ( $res2 as $row ) {
			if ( $row->pp_propname == 'hidefromdrilldown' ) {
				$hidden_cats[] = str_replace( '_', ' ', $row->page_title );
			} else {
				$shown_cats[] = str_replace( '_', ' ', $row->page_title );
			}
		}
		$categories = array_merge( $categories, $shown_cats );
		foreach ( $hidden_cats as $hidden_cat ) {
			foreach ( $categories as $i => $cat ) {
				if ( $cat == $hidden_cat ) {
					unset( $categories[$i] );
				}
			}
		}
		sort( $categories );
		// This shouldn't be necessary, but sometimes it is, due
		// to faulty storage in either MW or SMW.
		$categories = array_unique( $categories );
		return $categories;
	}

	/**
	 * Gets the list of names of only those categories in the wiki
	 * that have a __SHOWINDRILLDOWN__ declaration on their page.
	 */
	public static function getOnlyExplicitlyShownCategories() {
		$shown_cats = [];

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			[ 'p' => 'page', 'pp' => 'page_props' ],
			'p.page_title',
			[
				'p.page_namespace' => NS_CATEGORY,
				'pp.pp_propname' => 'showindrilldown',
				'pp.pp_value' => 'y'
			],
			self::class . '::getOnlyExplicitlyShownCategories',
			[ 'ORDER BY' => 'p.page_title' ],
			[ 'pp' => [ 'JOIN', 'p.page_id = pp.pp_page' ] ]
		);

		foreach ( $res as $row ) {
			$shown_cats[] = str_replace( '_', ' ', $row->page_title );
		}

		return $shown_cats;
	}

	/**
	 * Returns the list of categories that will show up in the
	 * header/sidebar of the 'BrowseData' special page.
	 */
	public static function getCategoriesForBrowsing() {
		global $sdgHideCategoriesByDefault;

		if ( $sdgHideCategoriesByDefault ) {
			return self::getOnlyExplicitlyShownCategories();
		} else {
			return self::getTopLevelCategories();
		}
	}

	/**
	 * Gets all the filters specified for a category.
	 */
	public static function loadFiltersForCategory( $category ) {
		$filters = [];

		$title = Title::newFromText( $category, NS_CATEGORY );

		// Return an empty array if the title object couldn't be created.
		// This mainly happens if people change the $_cat parameter in the url.
		if ( $title === null ) {
			return $filters;
		}

		$pageId = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'page_props',
			[
				'pp_value'
			],
			[
				'pp_page' => $pageId,
				'pp_propname' => 'SDFilters'
			]
		);

		foreach ( $res as $row ) {
			// There should only be one row.
			$filtersStr = $row->pp_value;
			$filtersInfo = unserialize( $filtersStr );
			foreach ( $filtersInfo as $filterName => $filterValues ) {
				$curFilter = new Filter();
				$curFilter->setName( $filterName );
				foreach ( $filterValues as $key => $value ) {
					if ( $key == 'property' ) {
						$curFilter->setProperty( $value );
						$curFilter->loadPropertyTypeFromProperty();
					} elseif ( $key == 'category' ) {
						$curFilter->setCategory( $value );
					} elseif ( $key == 'requires' ) {
						$curFilter->addRequiredFilter( $value );
					} elseif ( $key == 'int' ) {
						$curFilter->setInt( $value );
					}
				}
				$filters[] = $curFilter;
			}
		}

		// Read from the Page Schemas schema for this category, if
		// it exists, and add any filters defined there.
		if ( class_exists( 'PSSchema' ) ) {
			$pageSchemaObj = new \PSSchema( $category );
			if ( $pageSchemaObj->isPSDefined() ) {
				$filters_ps = Filter::loadAllFromPageSchema( $pageSchemaObj );
				$result_filters = array_merge( $filters, $filters_ps );
				return $result_filters;
			}
		}
		return $filters;
	}

	/**
	 * Gets the custom drilldown title for a category, if there is one.
	 */
	public static function getDrilldownTitleForCategory( $category ) {
		$title = Title::newFromText( $category, NS_CATEGORY );

		// Return false if the title object couldn't be created.
		// This mainly happens if people change the $_cat in the url.
		if ( $title === null ) {
			return false;
		}

		$pageID = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectField( 'page_props',
			[
				'pp_value'
			],
			[
				'pp_page' => $pageID,
				'pp_propname' => 'SDTitle'
			]
		);
	}

	/**
	 * Gets all the display parameters defined for a category
	 */
	public static function getDisplayParamsForCategory( $category ) {
		$return_display_params = [];

		$title = Title::newFromText( $category, NS_CATEGORY );
		$pageID = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'page_props',
			[
				'pp_value'
			],
			[
				'pp_page' => $pageID,
				'pp_propname' => 'SDDisplayParams'
			]
		);

		foreach ( $res as $row ) {
			// There should only be one row.
			$displayParamsStr = $row->pp_value;
			$return_display_params[] = explode( ';', $displayParamsStr );
		}

		return $return_display_params;
	}

	public static function getCategoryChildren( $category_name, $get_categories, $levels ) {
		if ( $levels == 0 ) {
			return [];
		}
		$pages = [];
		$subcategories = [];
		$dbr = wfGetDB( DB_REPLICA );
		$conds = [ 'cl_to' => str_replace( ' ', '_', $category_name ), ];
		if ( $get_categories ) {
			$conds['page_namespace'] = NS_CATEGORY;
		}

		$res = $dbr->select(
			[ 'categorylinks', 'page' ],
			[ 'page_title', 'page_namespace' ],
			$conds,
			__METHOD__,
			[
				'ORDER BY' => 'cl_sortkey',
			],
			[
				'page' => [
					'JOIN',
					[
						'cl_from = page_id'
					]
				]
			]
		);

		foreach ( $res as $row ) {
			if ( $get_categories ) {
				$subcategories[] = $row->page_title;
				$pages[] = $row->page_namespace;
			} else {
				if ( $row->page_title == NS_CATEGORY ) {
					$subcategories[] = $row->page_title;
				} else {
					$pages[] = $row->page_title;
				}
			}
		}
		foreach ( $subcategories as $subcategory ) {
			$pages = array_merge( $pages, self::getCategoryChildren( $subcategory, $get_categories, $levels - 1 ) );
		}
		return $pages;
	}

	public static function getDateFunctions( $dateDBField ) {
		global $wgDBtype;

		// Unfortunately, date handling in general - and date extraction
		// specifically - is done differently in almost every DB
		// system. If support were ever added for SQLite,
		// that would require special handling as well.
		if ( $wgDBtype == 'postgres' ) {
			$yearValue = "EXTRACT(YEAR FROM TIMESTAMP $dateDBField)";
			$monthValue = "EXTRACT(MONTH FROM TIMESTAMP $dateDBField)";
			$dayValue = "EXTRACT(DAY FROM TIMESTAMP $dateDBField)";
		} else { // MySQL
			$yearValue = "YEAR($dateDBField)";
			$monthValue = "MONTH($dateDBField)";
			$dayValue = "DAY($dateDBField)";
		}
		return [ $yearValue, $monthValue, $dayValue ];
	}

	/**
	 * Gets the custom drilldown title for a category, if there is one.
	 */
	public static function getDrilldownHeader( $category ) {
		$title = Title::newFromText( $category, NS_CATEGORY );
		$pageID = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'page_props',
			[
				'pp_value'
			],
			[
				'pp_page' => $pageID,
				'pp_propname' => 'SDHeader'
			]
		);

		if ( $row = $dbr->fetchRow( $res ) ) {
			return $row['pp_value'];
		} else {
			return '';
		}
	}

	/**
	 * Gets the custom drilldown title for a category, if there is one.
	 */
	public static function getDrilldownFooter( $category ) {
		$title = Title::newFromText( $category, NS_CATEGORY );
		$pageID = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'page_props',
			[
				'pp_value'
			],
			[
				'pp_page' => $pageID,
				'pp_propname' => 'SDFooter'
			]
		);

		if ( $row = $dbr->fetchRow( $res ) ) {
			return $row['pp_value'];
		} else {
			return '';
		}
	}

	public static function monthToString( $month ) {
		if ( $month == 1 ) {
			return wfMessage( 'january' )->text();
		} elseif ( $month == 2 ) {
			return wfMessage( 'february' )->text();
		} elseif ( $month == 3 ) {
			return wfMessage( 'march' )->text();
		} elseif ( $month == 4 ) {
			return wfMessage( 'april' )->text();
		} elseif ( $month == 5 ) {
			// Needed to avoid using 3-letter abbreviation
			return wfMessage( 'may_long' )->text();
		} elseif ( $month == 6 ) {
			return wfMessage( 'june' )->text();
		} elseif ( $month == 7 ) {
			return wfMessage( 'july' )->text();
		} elseif ( $month == 8 ) {
			return wfMessage( 'august' )->text();
		} elseif ( $month == 9 ) {
			return wfMessage( 'september' )->text();
		} elseif ( $month == 10 ) {
			return wfMessage( 'october' )->text();
		} elseif ( $month == 11 ) {
			return wfMessage( 'november' )->text();
		} else { // if ($month == 12) {
			return wfMessage( 'december' )->text();
		}
	}

	public static function stringToMonth( $str ) {
		if ( $str == wfMessage( 'january' )->text() ) {
			return 1;
		} elseif ( $str == wfMessage( 'february' )->text() ) {
			return 2;
		} elseif ( $str == wfMessage( 'march' )->text() ) {
			return 3;
		} elseif ( $str == wfMessage( 'april' )->text() ) {
			return 4;
		} elseif ( $str == wfMessage( 'may_long' )->text() ) {
			return 5;
		} elseif ( $str == wfMessage( 'june' )->text() ) {
			return 6;
		} elseif ( $str == wfMessage( 'july' )->text() ) {
			return 7;
		} elseif ( $str == wfMessage( 'august' )->text() ) {
			return 8;
		} elseif ( $str == wfMessage( 'september' )->text() ) {
			return 9;
		} elseif ( $str == wfMessage( 'october' )->text() ) {
			return 10;
		} elseif ( $str == wfMessage( 'november' )->text() ) {
			return 11;
		} else { // if ($strmonth == wfMessage('december')->text()) {
			return 12;
		}
	}

	public static function booleanToString( $bool_value ) {
		$words_field_name = ( $bool_value == true ) ? 'smw_true_words' : 'smw_false_words';
		$words_array = explode( ',', wfMessage( $words_field_name )->inContentLanguage()->text() );
		// go with the value in the array that tends to be "yes" or
		// "no", which is the 3rd
		$index_of_word = 2;
		// capitalize first letter of word
		if ( count( $words_array ) > $index_of_word ) {
			$string_value = ucwords( $words_array[$index_of_word] );
		} elseif ( count( $words_array ) == 0 ) {
			$string_value = $bool_value; // a safe value if no words are found
		} else {
			$string_value = ucwords( $words_array[0] );
		}
		return $string_value;
	}

	/**
	 * Register magic-word variable IDs
	 */
	public static function addMagicWordVariableIDs( &$magicWordVariableIDs ) {
		$magicWordVariableIDs[] = 'MAG_HIDEFROMDRILLDOWN';
		$magicWordVariableIDs[] = 'MAG_SHOWINDRILLDOWN';
		return true;
	}

	/**
	 * Set the actual value of the magic words
	 */
	public static function addMagicWordLanguage( &$magicWords, $langCode ) {
		switch ( $langCode ) {
		default:
			$magicWords['MAG_HIDEFROMDRILLDOWN'] = [ 0, '__HIDEFROMDRILLDOWN__' ];
			$magicWords['MAG_SHOWINDRILLDOWN'] = [ 0, '__SHOWINDRILLDOWN__' ];
		}
		return true;
	}

	/**
	 * Set values in the page_props table based on the presence of the
	 * 'HIDEFROMDRILLDOWN' and 'SHOWINDRILLDOWN' magic words in a page
	 */
	public static function handleShowAndHide( &$parser, &$text ) {
		if ( class_exists( MagicWordFactory::class ) ) {
			// MW 1.32+
			$factory = MediaWikiServices::getInstance()->getMagicWordFactory();
			$mw_hide = $factory->get( 'MAG_HIDEFROMDRILLDOWN' );
			$mw_show = $factory->get( 'MAG_SHOWINDRILLDOWN' );
		} else {
			$mw_hide = MagicWord::get( 'MAG_HIDEFROMDRILLDOWN' );
			$mw_show = MagicWord::get( 'MAG_SHOWINDRILLDOWN' );
		}
		$parserOutput = $parser->getOutput();
		if ( $mw_hide->matchAndRemove( $text ) ) {
			if ( method_exists( $parserOutput, 'setPageProperty' ) ) {
				// MW 1.38
				$parserOutput->setPageProperty( 'hidefromdrilldown', 'y' );
			} else {
				$parserOutput->setProperty( 'hidefromdrilldown', 'y' );
			}
		}
		if ( $mw_show->matchAndRemove( $text ) ) {
			if ( method_exists( $parserOutput, 'setPageProperty' ) ) {
				// MW 1.38
				$parserOutput->setPageProperty( 'showindrilldown', 'y' );
			} else {
				$parserOutput->setProperty( 'showindrilldown', 'y' );
			}
		}
		return true;
	}

	public static function getIDsTableName() {
		return SQLStore::ID_TABLE;
	}

	public static function getCategoryInstancesTableName() {
		return 'smw_fpt_inst';
	}

	public static function addToAdminLinks( &$admin_links_tree ) {
		$browse_search_section = $admin_links_tree->getSection( wfMessage( 'adminlinks_browsesearch' )->text() );
		$sd_row = new ALRow( 'sd' );
		$sd_row->addItem( ALItem::newFromSpecialPage( 'BrowseData' ) );
		$sd_name = wfMessage( 'specialpages-group-sd_group' )->text();
		$sd_docu_label = wfMessage( 'adminlinks_documentation', $sd_name )->text();
		$sd_row->addItem( ALItem::newFromExternalLink( "https://www.mediawiki.org/wiki/Extension:Semantic_Drilldown", $sd_docu_label ) );

		$browse_search_section->addRow( $sd_row );

		return true;
	}

	/**
	 * Register extension unit tests with old versions of MediaWiki
	 *
	 * @param string[] &$paths
	 * @return bool
	 */
	public static function onUnitTestsList( &$paths ) {
		$paths[] = __DIR__ . '/../tests/phpunit';
		return true;
	}

	/**
	 * Escapes the given string
	 *
	 * @param string $val
	 * @return string
	 */
	public static function escapeString( $val ) {
		return htmlspecialchars( $val, ENT_QUOTES, 'UTF-8' );
	}
}
