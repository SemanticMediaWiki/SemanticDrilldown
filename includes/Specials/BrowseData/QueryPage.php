<?php

namespace SD\Specials\BrowseData;

use Closure;
use PageProps;
use RequestContext;
use SD\DbService;
use SD\Parameters\Parameters;
use SD\Sql\SqlProvider;
use SD\Utils;
use SMWOutputs;
use Title;

class QueryPage extends \QueryPage {

	private DbService $db;
	private UrlService $urlService;
	private GetPageContent $getPageContent;
	private GetCategories $getCategories;
	private GetAppliedFilters $getAppliedFilters;
	private GetApplicableFilters $getApplicableFilters;
	private GetSemanticResults $getSemanticResults;

	private ProcessTemplate $processTemplate;

	private DrilldownQuery $query;
	private ?string $headerPage;
	private ?string $footerPage;
	private array $displayParametersWithUnknownFormat = [];
	private array $unpagedDisplayParametersList = [];
	private array $pagedDisplayParametersList = [];
	private array $displayParametersWithUnsupportedFormat = [];

	/** @var string|null cache for getSQL() */
	private ?string $sql = null;

	public function __construct(
		array $resultFormatTypes,
		DbService $db, PageProps $pageProps, Closure $newUrlService,
		Closure $getPageFromTitleText, RequestContext $context, Parameters $parameters,
		DrilldownQuery $query, int $offset, int $limit
	) {
		parent::__construct( 'BrowseData' );
		$this->setContext( $context );

		$request = $context->getRequest();
		$output = $this->getOutput();

		$urlService = $newUrlService( $request, $query );
		$this->getPageContent = new GetPageContent( $getPageFromTitleText, $output );
		$this->getCategories = new GetCategories( $db, $urlService, $query );
		$this->getAppliedFilters = new GetAppliedFilters( $pageProps, $urlService, $query );
		$this->getApplicableFilters = new GetApplicableFilters( $db, $urlService, $output, $request, $query );
		$this->getSemanticResults = new GetSemanticResults();

		$this->db = $db;
		$this->urlService = $urlService;
		$this->query = $query;
		$this->headerPage = $parameters->header();
		$this->footerPage = $parameters->footer();
		$this->offset = $offset;
		$this->limit = $limit;

		if ( $parameters->displayParametersList() ) {
			foreach ( $parameters->displayParametersList() as $dps ) {
				$format = $dps->format();
				if ( !array_key_exists( $format, $resultFormatTypes ) ) {
					$this->displayParametersWithUnknownFormat[] = $dps;
				} elseif ( $resultFormatTypes[ $format ] === 'unpaged' ) {
					$this->unpagedDisplayParametersList[] = $dps;
				} elseif ( $resultFormatTypes[ $format ] === 'paged' ) {
					$this->pagedDisplayParametersList[] = $dps;
				} else {
					$this->displayParametersWithUnsupportedFormat[] = $dps;
				}
			}
		}

		$this->processTemplate = new ProcessTemplate;
	}

	public function getName() {
		return 'BrowseData';
	}

	public function isExpensive() {
		return false;
	}

	public function isSyndicated() {
		return false;
	}

	protected function getPageHeader(): string {
		$categories = Utils::getCategoriesForBrowsing();
		if ( $this->query->category() === null && empty( $categories ) ) {
			return '';
		}

		$res = $this->db->query( $this->getSQL() );
		return ( $this->processTemplate ) ( 'QueryPageHeader', [
			'displayParametersWithUnknownFormat' =>
				array_map( fn( $x ) => "$x", $this->displayParametersWithUnknownFormat ),
			'displayParametersWithUnsupportedFormat' =>
				array_map( fn( $x ) => "$x", $this->displayParametersWithUnsupportedFormat ),
			'header' => ( $this->getPageContent )( $this->headerPage ),
			'categories' => ( $this->getCategories )( $categories ),
			'appliedFilters' => ( $this->getAppliedFilters )(),
			'applicableFilters' => ( $this->getApplicableFilters )(),
			'results' => ( $this->getSemanticResults )( $this->unpagedDisplayParametersList, $this->getOutput(), $res ),
		] );
	}

	protected function getSQL(): string {
		// From the overridden method:
		// "For back-compat, subclasses may return a raw SQL query here, as a string.
		// This is strongly deprecated; getQueryInfo() should be overridden instead."
		if ( $this->sql === null ) {
			$this->sql = SqlProvider::getSQL( $this->query->category(), $this->query->subcategory(),
				$this->query->allSubcategories(), $this->query->appliedFilters() );
		}

		// Note: we have to return the SQL here even if we already know that there are no paged
		// result displays; if there are no results, QueryPage also doesn't show the page header
		return $this->sql;
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

	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		$out->addHTML( ( $this->processTemplate )( 'QueryPageOutput', [
			'results' => ( $this->getSemanticResults )( $this->pagedDisplayParametersList, $out, $res, $num ),
			'footer' => ( $this->getPageContent )( $this->footerPage ),
		] ) );

		SMWOutputs::commitToOutputPage( $out );
	}

	protected function openList( $offset ) {
		return "";
	}

	protected function closeList() {
		return '<br style="clear: both" />';
	}

	protected function linkParameters() {
		return $this->urlService->getLinkParameters( $this->getRequest(), $this->query );
	}

}
