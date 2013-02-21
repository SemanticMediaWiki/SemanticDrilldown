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
		// Handling changed in MW version 1.18.
		if ( method_exists( $rep, 'execute' ) ) {
			return $rep->execute( $par );
		} else {
			return $rep->doQuery( $offset, $limit );
		}
	}
}

class FiltersPage extends QueryPage {
	function __construct( $name = 'Filters' ) {
		// Backwards compatibility for pre-version 1.18.
		if ( $this instanceof SpecialPage ) {
			parent::__construct( $name );
		}
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
		if ( method_exists( $skin, 'makeLinkObj' ) ) {
			// Deprecated in MW 1.21.
			$text = $skin->makeLinkObj( $title, htmlspecialchars( $title->getText() ) );
		} else {
			// Has existed since MW 1.16, but static since MW 1.18.
			$text = Linker::link( $title, $title->getText() );
		}
		return $text;
	}
}
