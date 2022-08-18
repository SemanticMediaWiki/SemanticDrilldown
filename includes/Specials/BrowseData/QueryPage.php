<?php

namespace SD\Specials\BrowseData;

use Html;
use IDatabase;
use OutputPage;
use SD\Parameters\Parameters;
use SD\Sql\SqlProvider;
use Skin;
use SMWOutputs;
use Title;
use WikiPage;

class QueryPage extends \QueryPage {
	private Printer $printer;
	private Parameters $parameters;
	private DrilldownQuery $query;

	public function __construct( $newPrinter, $context, $parameters, $query, $offset, $limit ) {
		parent::__construct( 'BrowseData' );

		$this->setContext( $context );
		$this->printer = $newPrinter( $this->getOutput(), $this->getRequest(), $parameters, $query );

		$this->parameters = $parameters;
		$this->query = $query;
		$this->offset = $offset;
		$this->limit = $limit;
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
		return SqlProvider::getSQL(
			$this->query->category(), $this->query->subcategory(),
			$this->query->allSubcategories(), $this->query->appliedFilters() );
	}

	protected function getOrderFields() {
		return [ 'sortkey' ];
	}

	protected function sortDescending() {
		return false;
	}

	protected function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->value );
		return $this->getLinkRenderer()->makeLink( $title, $title->getText() );
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

		$semanticResultPrinter = new SemanticResultPrinter( $res, $num );
		$displayParametersList = $this->parameters->displayParametersList();
		foreach ( $displayParametersList as $displayParameters ) {
			$caption = $displayParameters->caption !== null
				? \Html::element( 'h2', [], $displayParameters->caption )
				: '';

			$text = $semanticResultPrinter->getText( iterator_to_array( $displayParameters ) );
			$out->addWikiTextAsInterface( "$caption\n$text" );
		}

		// Add outro template
		$footerPage = $this->parameters->footer();
		if ( $footerPage !== null ) {
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
