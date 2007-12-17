<?php
/**
 * Shows list of all filters on the site.
 *
 * @author Yaron Koren
 */

if (!defined('MEDIAWIKI')) die();

global $IP;
require_once( "$IP/includes/SpecialPage.php" );

global $sdgSpecialPagesSpecialInit;
if ($sdgSpecialPagesSpecialInit) {
	global $wgSpecialPages;
	$wgSpecialPages['Filters'] = 'SDFilters';

	class SDFilters extends SpecialPage {

		/**
		 * Constructor
		 */
		public function __construct() {
			smwfInitUserMessages();
			parent::__construct('Filters', '', true);
		}

		function execute() {
			list( $limit, $offset ) = wfCheckLimits();
			$rep = new FiltersPage();
			return $rep->doQuery( $offset, $limit );
		}
	}
} else {
	SpecialPage::addPage( new SpecialPage('Filters','',true,'doSpecialFilters',false) );
}

class FiltersPage extends QueryPage {
	function getName() {
		return "Filters";
	}

	function isExpensive() { return false; }

	function isSyndicated() { return false; }

	function getPageHeader() {
		$header = '<p>' . wfMsg('sd_filters_docu') . "</p><br />\n";
		return $header;
	}

	function getPageFooter() {
	}

	function getSQL() {
		$filter_ns = SD_NS_FILTER;
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		return "SELECT 'Filters' as type,
			page_title as title,
			page_title as value
			FROM $page
			WHERE page_namespace = $filter_ns";
               }

	function sortDescending() {
		return false;
	}

	function formatResult($skin, $result) {
		$title = Title::makeTitle( SD_NS_FILTER, $result->value );
		$text = $skin->makeLinkObj( $title, $title->getText() );
		return $text;
	}
}

function doSpecialFilters() {
	list( $limit, $offset ) = wfCheckLimits();
	$rep = new FiltersPage();
	return $rep->doQuery( $offset, $limit );
}
