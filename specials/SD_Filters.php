<?php
/**
 * Shows list of all filters on the site.
 *
 * @author Yaron Koren
 */

class SDFilters extends SpecialPage {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( 'Filters' );
	}

	function execute( $par ) {
		$this->setHeaders();
		list( $limit, $offset ) = wfCheckLimits();
		$rep = new FiltersPage();
		return $rep->execute( $par );
	}
}

class FiltersPage extends QueryPage {
	function __construct( $name = 'Filters' ) {
		parent::__construct( $name );
	}
	
	function getName() {
		return "Filters";
	}

	function isSyndicated() { return false; }

	function getPageHeader() {
		$header = '<p>' . wfMsg( 'sd_filters_docu' ) . "</p><br />\n";
		return $header;
	}

	function getPageFooter() {
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'page' ),
			'fields' => array( 'page_title AS title', 'page_title AS value' ),
			'conds' => array( 'page_namespace' => SD_NS_FILTER )
		);
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( SD_NS_FILTER, $result->value );
		return Linker::link( $title, $title->getText() );
	}
}
