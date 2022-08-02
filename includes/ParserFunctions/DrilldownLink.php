<?php

/**
 * Parser function for Semantic Drilldown
 *
 * @file
 * @ingroup SD
 *
 * #drilldownlink links to the page Special:BrowseData, with a query string
 * dictated by the parameters.
 *
 * {{#drilldownlink:category=|subcategory=|single|link text=|tooltip=|filter=}}
 *
 * @author Yaron Koren
 * @author mwjames
 * @author gesinn-it-wam
 */

namespace SD\ParserFunctions;

use Html;
use MediaWiki\MediaWikiServices;
use Parser;
use Sanitizer;

class DrilldownLink {

	private Parser $parser;
	private array $inQueryArr;
	private string $inLinkStr;
	private string $category;
	private string $inTooltip;

	public function __construct( $parser ) {
		$this->parser = $parser;
	}

	public function __invoke( $params ): string {
		$this->inQueryArr = [];
		$this->inLinkStr = $this->category = $this->inTooltip = '';

		$this->parse( $params );
		return $this->writeLink();
	}

	private function parse( $params ) {
		foreach ( $params as $param ) {
			$elements = explode( '=', $param, 2 );

			// set param_name and value
			if ( count( $elements ) > 1 ) {
				$param_name = trim( $elements[0] );
				// parse (and sanitize) parameter values
				$value = trim( $this->parser->recursiveTagParse( $elements[1] ) );
			} else {
				$param_name = null;
				// parse (and sanitize) parameter values
				$value = trim( $this->parser->recursiveTagParse( $param ) );
			}

			if ( $param_name == 'category' || $param_name == 'cat' ) {
				$this->category = $value;
			} elseif ( $param_name == 'subcategory' || $param_name == 'subcat' ) {
				parse_str( '_subcat=' . $value, $arr );
				$this->inQueryArr = array_merge( $this->inQueryArr, $arr );
			} elseif ( $param_name == 'link text' ) {
				$this->inLinkStr = $value;
			} elseif ( $param_name == 'tooltip' ) {
				$this->inTooltip = Sanitizer::decodeCharReferences( $value );
			} elseif ( $param_name == null && $value == 'single' ) {
				parse_str( '_single', $arr );
				$this->inQueryArr = array_merge( $this->inQueryArr, $arr );
			} elseif ( $param_name == 'filters' ) {
				$inQueryStr = str_replace( '&amp;', '%26', $value );
				parse_str( $inQueryStr, $arr );
				$this->inQueryArr = array_merge( $this->inQueryArr, $arr );
			}
		}
	}

	private function writeLink(): string {
		$specialPage = MediaWikiServices::getInstance()
			->getSpecialPageFactory()
			->getPage( 'BrowseData' );

		$title = $specialPage->getPageTitle();
		$link_url = $title->getLocalURL() . "/$this->category";
		$link_url = str_replace( ' ', '_', $link_url );
		if ( !empty( $this->inQueryArr ) ) {
			$link_url .= ( strstr( $link_url, '?' ) ) ? '&' : '?';
			$link_url .= str_replace( '+', '%20', http_build_query( $this->inQueryArr, '', '&' ) );
		}

		$this->inLinkStr = ( empty( $this->inLinkStr ) ? $this->category : $this->inLinkStr );
		$link = Html::rawElement( 'a', [ 'href' => $link_url, 'title' => $this->inTooltip ],
			$this->inLinkStr );

		return $this->parser->insertStripItem( $link );
	}

}
