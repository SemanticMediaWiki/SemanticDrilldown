<?php

namespace SD;

use ALItem;
use ALRow;
use MagicWord;
use MagicWordFactory;
use MediaWiki\MediaWikiServices;
use SMW\SQLStore\SQLStore;

/**
 * A class for static helper functions for Semantic Drilldown
 *
 * @author Yaron Koren
 */

class Utils {

	public static function setGlobalJSVariables( &$vars ) {
		global $wgScriptPath;
		$sdSkinsPath = "$wgScriptPath/extensions/SemanticDrilldown/skins";

		$vars['sdgDownArrowImage'] = "$sdSkinsPath/down-arrow.png";
		$vars['sdgRightArrowImage'] = "$sdSkinsPath/right-arrow.png";
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
			Compat::setPageProperty( $parserOutput, 'hidefromdrilldown', 'y' );
		}
		if ( $mw_show->matchAndRemove( $text ) ) {
			Compat::setPageProperty( $parserOutput, 'showindrilldown', 'y' );
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

	/**
	 * Return a "nice" version of the value for a filter, if it's some
	 * special case like 'other', 'none', a boolean, etc.
	 */
	public static function getNiceFilterValue( string $propertyType, string $value ): string {
		$value = str_replace( '_', ' ', $value );
		// if it's boolean, display something nicer than "0" or "1"
		if ( $value === ' other' ) {
			return wfMessage( 'sd_browsedata_other' )->text();
		} elseif ( $value === ' none' ) {
			return wfMessage( 'sd_browsedata_none' )->text();
		} elseif ( $propertyType === 'boolean' ) {
			return self::booleanToString( $value );
		} elseif ( $propertyType === 'date' && strpos( $value, '//T' ) ) {
			return str_replace( '//T', '', $value );
		} else {
			return $value;
		}
	}

}
