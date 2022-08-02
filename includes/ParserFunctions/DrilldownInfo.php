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
use Linker;
use Parser;
use SD\Compat;
use Title;

class DrilldownInfo {

	private Parser $parser;
	private string $title;
	private array $filters;
	private string $displayParameters;
	private string $header;
	private string $footer;

	public function __construct( $parser ) {
		$this->parser = $parser;
	}

	public function __invoke( $params ): string {
		$this->title = '';
		$this->filters = [];
		$this->displayParameters = '';
		$this->header = '';
		$this->footer = '';

		if ( $this->parser->getTitle()->getNamespace() != NS_CATEGORY ) {
			return '<div class="error">Error: #drilldowninfo can only be called in category pages.</div>';
		}

		$this->parse( $params );
		$this->setPageProperties();
		return $this->writeOutput();
	}

	private function parse( $params ) {
		$this->readParameters( $params );
	}

	private function readParameters( $params ) {
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
			if ( $param_name == 'filters' ) {
				$this->filters = $this->parseFilters( $value );
			} elseif ( $param_name == 'title' ) {
				$this->title = $value;
			} elseif ( $param_name == 'display parameters' ) {
				$this->displayParameters = $value;
			} elseif ( $param_name === 'header' ) {
				$this->header = $value;
			} elseif ( $param_name === 'footer' ) {
				$this->footer = $value;
			}
		}
	}

	private function setPageProperties() {
		$parserOutput = $this->parser->getOutput();

		Compat::setPageProperty( $parserOutput, 'SDFilters', serialize( $this->filters ) );
		if ( $this->title != '' ) {
			Compat::setPageProperty( $parserOutput, 'SDTitle', $this->title );
		}
		if ( $this->displayParameters != '' ) {
			Compat::setPageProperty( $parserOutput, 'SDDisplayParams', $this->displayParameters );
		}
		if ( $this->header !== '' ) {
			Compat::setPageProperty( $parserOutput, 'SDHeader', $this->header );
		}
		if ( $this->footer !== '' ) {
			Compat::setPageProperty( $parserOutput, 'SDFooter', $this->footer );
		}
	}

	private static function parseFilters( $filtersStr ) {
		$filters = [];
		preg_match_all( '/([^()]*)\(([^)]*)\)/', $filtersStr, $matches );
		foreach ( $matches[1] as $i => $filterName ) {
			$filterName = trim( $filterName, ", \t\n\r\0\x0B" );
			$filters[$filterName] = [];

			$filterSettingsStr = $matches[2][$i];
			$filterSettings = explode( ',', $filterSettingsStr );
			foreach ( $filterSettings as $filterSettingStr ) {
				$filterSetting = explode( '=', $filterSettingStr, 2 );
				if ( count( $filterSetting ) != 2 ) {
					continue;
				}
				$key = trim( $filterSetting[0] );
				if ( $key != 'property' && $key != 'category' && $key != 'requires' && $key != 'int' ) {
					return "<div class=\"error\">Error: unknown setting, \"$key\".</div>";
				}

				$value = trim( $filterSetting[1] );
				// 'requires' holds a list, the other two
				// hold individual values.
				if ( $key == 'requires' ) {
					$values = explode( ',', $value );
					foreach ( $values as $realValue ) {
						$filters[$filterName][$key] = trim( $realValue );
					}
				} else {
					$filters[$filterName][$key] = $value;
				}
			}
		}

		return $filters;
	}

	private function writeOutput(): string {
		$parserOutput = $this->parser->getOutput();
		$curTitle = $this->parser->getTitle();

		$parserOutput->addModules( [ 'ext.semanticdrilldown.info' ] );

		$text = "<table class=\"drilldownInfo mw-collapsible mw-collapsed\">\n";
		$bd = Title::makeTitleSafe( NS_SPECIAL, 'BrowseData' );
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
					$propertyTitle = Title::makeTitleSafe( SMW_NS_PROPERTY, $value );
					$text .= $parser->getLinkRenderer()->makeLink($propertyTitle, $value);
				} elseif ( $key == 'category' ) {
					$categoryTitle = Title::makeTitleSafe( NS_CATEGORY, $value );
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
		if ( $this->title != '' ) {
			$text .= "<tr class=\"drilldownInfoHeader\"><td colspan=\"2\">Title</td></tr>\n";
			$text .= "<tr><td colspan=\"2\">$this->title</td></tr>\n";
		}
		if ( $this->displayParameters != '' ) {
			$text .= "<tr class=\"drilldownInfoHeader\"><td colspan=\"2\">Display parameters</td></tr>\n";
			$text .= "<tr><td colspan=\"2\">$this->displayParameters</td></tr>\n";
		}
		$text .= "</table>\n";

		return $this->parser->insertStripItem( $text );
	}

}
