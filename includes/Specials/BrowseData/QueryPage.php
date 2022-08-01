<?php

namespace SD\Specials\BrowseData;

use Html;
use IDatabase;
use Linker;
use OutputPage;
use SD\Utils;
use Skin;
use SMW\Query\PrintRequest;
use SMW\Query\QueryResult;
use SMWDIWikiPage;
use SMWOutputs;
use SMWQuery;
use SMWQueryProcessor;
use Title;
use WikiPage;

class QueryPage extends \QueryPage {
	private $category;
	private $sqlProvider;
	private $printer;

	public function __construct( $context, $category, $subcategory, $applied_filters,
								 $remaining_filters, $offset, $limit ) {
		parent::__construct( 'BrowseData' );
		$this->offset = $offset;
		$this->limit = $limit;

		$this->setContext( $context );

		$this->category = $category;
		if ( $subcategory ) {
			$actual_cat = str_replace( ' ', '_', $subcategory );
		} else {
			$actual_cat = str_replace( ' ', '_', $this->category );
		}

		// Get the two arrays for subcategories - one for only the
		// immediate subcategories, for display, and the other for
		// all subcategories, sub-subcategories etc., for querying.
		$next_level_subcategories = Utils::getCategoryChildren( $actual_cat, true, 1 );
		$all_subcategories = Utils::getCategoryChildren( $actual_cat, true, 10 );

		$this->sqlProvider = new SqlProvider( $this->category, $subcategory,
			$all_subcategories, $applied_filters );

		$this->printer = new Printer( $this->category, $subcategory,
			$next_level_subcategories, $all_subcategories, $applied_filters,
			$remaining_filters, $this->getOutput(), $this->getRequest(), $this->sqlProvider );
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

	protected function getPageHeader(): string {
		return $this->printer->getPageHeader();
	}

	protected function getSQL(): string {
		// From the overridden method:
		// "For back-compat, subclasses may return a raw SQL query here, as a string.
		// This is strongly deprecated; getQueryInfo() should be overridden instead."
		return $this->sqlProvider->getSQL();
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
		$this->getOutput()->addHTML( Html::openElement( 'div', [ 'class' => 'drilldown-results-output' ] ) );

		// Add Drilldown Results
		$all_display_params = Utils::getDisplayParamsForCategory( $this->category );
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

		$printer = SMWQueryProcessor::getResultPrinter( $params['format'], SMWQueryProcessor::SPECIAL_PAGE );

		if ( version_compare( SMW_VERSION, '1.6.1', '>' ) ) {
			SMWQueryProcessor::addThisPrintout( $printouts, $params );
			$params = SMWQueryProcessor::getProcessedParams( $params, $printouts );
		}

		$prresult = $printer->getResult(
			$this->addSemanticResultWrapper( $res, $num, $query, $mainlabel, $printouts ),
			$params,
			SMW_OUTPUT_WIKI
		);

		$prtext = is_array( $prresult ) ? $prresult[0] : $prresult;

		$out->addWikiTextAsInterface( $prtext );

		// Add outro template
		$footerPage = Utils::getDrilldownFooter( $this->category );

		if ( $footerPage !== '' ) {
			$title = Title::newFromText( $footerPage );
			$page = WikiPage::factory( $title );

			if ( $page->exists() ) {
				$content = $page->getContent();
				$pageContent = $content->serialize();
				$out->addWikiTextAsInterface( $pageContent );
			}
		}

		// close drilldown-results
		$this->getOutput()->addHTML( Html::closeElement( 'div' ) );

		// close the Bootstrap Panel wrapper opened in getPageHeader();
		$this->getOutput()->addHTML( '</div></div>' );

		SMWOutputs::commitToOutputPage( $out );
	}

	/**
	 * Take non-semantic result set returned by Database->query() method, and wrap it in a
	 * SMWQueryResult container for passing to any of the various semantic result printers.
	 * Code stolen largely from SMWSQLStore2QueryEngine->getInstanceQueryResult() method.
	 * (does this mean it will only work with certain semantic SQL stores?)
	 */
	private function addSemanticResultWrapper( $res, $num, $query, $mainlabel, $printouts ) {
		$qr = [];
		$count = 0;
		$store = Utils::getSMWStore();
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

		$printrequest = new PrintRequest( PrintRequest::PRINT_THIS, $mainlabel );
		$main_printout = [];
		$main_printout[$printrequest->getHash()] = $printrequest;
		$printouts = array_merge( $main_printout, $printouts );

		return new QueryResult( $printouts, $query, $qr, $store, ( $count > $num ) );
	}

	protected function openList( $offset ) {
		return "";
	}

	protected function closeList() {
		return "\n			<br style=\"clear: both\" />\n";
	}

	protected function linkParameters() {
		return $this->printer->linkParameters();
	}

}
