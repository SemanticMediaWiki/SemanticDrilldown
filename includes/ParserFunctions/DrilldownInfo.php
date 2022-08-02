<?php

/**
 * Parser function for Semantic Drilldown
 *
 * @file
 * @ingroup SD
 *
 * #drilldowninfo defines the drilldown information for one category - the
 * set of filters and so forth.
 *
 * @author Yaron Koren
 * @author mwjames
 * @author gesinn-it-wam
 */

namespace SD\ParserFunctions;

use Html;
use Parser;
use SD\Parameters\DisplayParametersList;
use SD\Parameters\Filters;
use SD\Parameters\Footer;
use SD\Parameters\Header;
use SD\Parameters\Title;

class DrilldownInfo {

	private Parser $parser;
	private Title $title;
	private Filters $filters;
	private Header $header;
	private Footer $footer;
	private DisplayParametersList $displayParametersList;

	public function __construct( $parser ) {
		$this->parser = $parser;
	}

	public function __invoke( $params ): string {
		$this->title = new Title;
		$this->filters = new Filters;
		$this->header = new Header;
		$this->footer = new Footer;
		$this->displayParametersList = new DisplayParametersList;

		if ( $this->parser->getTitle()->getNamespace() != NS_CATEGORY ) {
			return '<div class="error">Error: #drilldowninfo can only be called in category pages.</div>';
		}

		$this->parse( $params );
		$this->setPageProperties();
		return $this->writeOutput();
	}

	private function parse( $params ) {
		foreach ( $params as $param ) {
			$elements = explode( '=', $param, 2 );
			if ( count( $elements ) > 1 ) {
				$param_name = trim( $elements[0] );
				// parse (and sanitize) parameter values
				$value = trim( $this->parser->recursiveTagParse( $elements[1] ) );
			} else {
				// For now, don't do anything - this function
				// has no unnamed parameters.
				continue;
			}
			if ( $param_name == 'title' ) {
				$this->title = new Title( $value );
			} elseif ( $param_name === 'header' ) {
				$this->header = new Header( $value );
			} elseif ( $param_name === 'footer' ) {
				$this->footer = new Footer( $value );
			} elseif ( $param_name == 'filters' ) {
				$this->filters = new Filters( $value );
			} elseif ( $param_name == 'display parameters' ) {
				$this->displayParametersList->add( $value );
			}
		}
	}

	private function setPageProperties() {
		$parserOutput = $this->parser->getOutput();
		$this->title->setPageProperty( $parserOutput );
		$this->header->setPageProperty( $parserOutput );
		$this->footer->setPageProperty( $parserOutput );
		$this->filters->setPageProperty( $parserOutput );
		$this->displayParametersList->setPageProperty( $parserOutput );
	}

	private function writeOutput(): string {
		$parserOutput = $this->parser->getOutput();
		$curTitle = $this->parser->getTitle();

		$parserOutput->addModules( [ 'ext.semanticdrilldown.info' ] );

		$text = "<table class=\"drilldownInfo mw-collapsible mw-collapsed\">\n";
		$bd = \Title::makeTitleSafe( NS_SPECIAL, 'BrowseData' );
		$bdURL = $bd->getLocalURL() . "/" . $curTitle->getPartialURL();
		$bdLink = Html::rawElement( 'a', [ 'href' => $bdURL ], "Semantic Drilldown" );
		$text .= "<tr><th colspan=\"2\">$bdLink</th></tr>\n";
		$text .= "<tr class=\"drilldownInfoHeader\"><td colspan=\"2\">Filters</td></tr>\n";
		foreach ( $this->filters as $filterName => $filterInfo ) {
			$text .= "<tr><td class=\"drilldownFilterName\">$filterName</td><td>\n";
			$i = 0;
			foreach ( $filterInfo as $key => $value ) {
				if ( $i++ > 0 ) {
					$text .= ", ";
				}
				$text .= $key . ' = ';
				if ( $key == 'property' ) {
					$propertyTitle = \Title::makeTitleSafe( SMW_NS_PROPERTY, $value );
					$text .= $parser->getLinkRenderer()->makeLink($propertyTitle, $value);
				} elseif ( $key == 'category' ) {
					$categoryTitle = \Title::makeTitleSafe( NS_CATEGORY, $value );
					$text .= $parser->getLinkRenderer()->makeLink($categoryTitle, $value);
				} elseif ( $key == 'requires' ) {
					$text .= '<strong>' . $value . '</strong>';
				} else {
					// Do what here?
					$text .= $value;
				}
			}
			$text .= "</td></tr>\n";
		}
		if ( $this->title !== null ) {
			$text .= "<tr class=\"drilldownInfoHeader\"><td colspan=\"2\">Title</td></tr>\n";
			$text .= "<tr><td colspan=\"2\">$this->title</td></tr>\n";
		}
		$text .= "<tr class=\"drilldownInfoHeader\"><td colspan=\"2\">Display parameters</td></tr>\n";
		foreach ( $this->displayParametersList->strings() as $displayParameters ) {
			$text .= "<tr><td colspan=\"2\">$displayParameters</td></tr>\n";
		}
		$text .= "</table>\n";

		return $this->parser->insertStripItem( $text );
	}

}
