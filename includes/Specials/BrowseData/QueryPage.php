<?php

namespace SD\Specials\BrowseData;

use Closure;
use PageProps;
use RequestContext;
use SD\DbService;
use SD\Parameters\DisplayParameters;
use SD\Parameters\Parameters;
use SD\Sql\PropertyTypeDbInfo;
use SD\Sql\SqlProvider;
use SD\Utils;
use SMWOutputs;
use Title;
use Wikimedia\Rdbms\Subquery;

class QueryPage extends \QueryPage {

	private DbService $db;
	private UrlService $urlService;
	private GetPageContent $getPageContent;
	private GetAppliedFilters $getAppliedFilters;
	private GetApplicableFilters $getApplicableFilters;
	private GetSemanticResults $getSemanticResults;

	private ProcessTemplate $processTemplate;

	private DrilldownQuery $query;
	private ?string $headerPage = null;
	private ?string $footerPage = null;
	private array $displayParametersWithUnknownFormat = [];
	private array $unpagedDisplayParametersList = [];
	private array $pagedDisplayParametersList = [];
	private array $displayParametersWithUnsupportedFormat = [];

	/** @var string|null cache for getSQL() */
	private ?string $sql = null;

	public function __construct(
		array $resultFormatTypes,
		DbService $db, PageProps $pageProps, Closure $newUrlService,
		Closure $getPageFromTitleText, RequestContext $context,
		Parameters $parameters, DrilldownQuery $query, int $offset, int $limit
	) {
		parent::__construct( 'BrowseData' );
		$this->setContext( $context );

		$request = $context->getRequest();
		$output = $this->getOutput();

		$this->getPageContent = new GetPageContent( $getPageFromTitleText, $context );
		$this->getSemanticResults = new GetSemanticResults();

		$urlService = $newUrlService( $request, $query );

		$this->getAppliedFilters = new GetAppliedFilters( $pageProps, $urlService, $query );
		$this->getApplicableFilters =
			new GetApplicableFilters( $db, $urlService, $output, $request, $query );

		$this->db = $db;
		$this->urlService = $urlService;
		$this->query = $query;
		$this->offset = $offset;
		$this->limit = $limit;

		$this->headerPage = $parameters->header();
		$this->footerPage = $parameters->footer();
		$displayParametersList = $parameters->displayParametersList();
		if ( $displayParametersList && $displayParametersList->count() ) {
			foreach ( $displayParametersList as $dps ) {
				$format = $dps->format();
				if ( !array_key_exists( $format, $resultFormatTypes ) ) {
					$this->displayParametersWithUnknownFormat[] = $dps;
				} elseif ( $resultFormatTypes[$format] === 'unpaged' ) {
					$this->unpagedDisplayParametersList[] = $dps;
				} elseif ( $resultFormatTypes[$format] === 'paged' ) {
					$this->pagedDisplayParametersList[] = $dps;
				} else {
					$this->displayParametersWithUnsupportedFormat[] = $dps;
				}
			}
		} else {
			$this->pagedDisplayParametersList[] = new DisplayParameters();
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
		$vm = [
			'displayParametersWithUnknownFormat' =>
				array_map( fn ( $x ) => "$x", $this->displayParametersWithUnknownFormat ),
			'displayParametersWithUnsupportedFormat' =>
				array_map( fn ( $x ) => "$x", $this->displayParametersWithUnsupportedFormat ),
			'header' => $this->getPageContent( $this->getOutput(), $this->headerPage ),
		];

		if ( $this->query ) {
			$res = $this->db->query( $this->getSQL() );
			$vm += [
				'appliedFilters' => ( $this->getAppliedFilters )(),
				'applicableFilters' => ( $this->getApplicableFilters )(),
				'results' => ( $this->getSemanticResults )( $this->unpagedDisplayParametersList, $this->getOutput(), $res ),
			];
		}

		return ( $this->processTemplate ) ( 'QueryPageHeader', $vm );
	}

	public function getQueryInfo() {
		if ( !$this->query ) {
			return 'select null as sortkey where 0 = 1';
		}
		$dbr = \MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$smwIDs = $dbr->tableName( Utils::getIDsTableName() );
		$smwCategoryInstances = $dbr->tableName( Utils::getCategoryInstancesTableName() );
		$cat_ns = NS_CATEGORY;
		$prop_ns = SMW_NS_PROPERTY;

		$query = [
			'fields' => [
				'title' => 'ids.smw_title',
				'value' => 'ids.smw_title',
				't' => 'ids.smw_title',
				'namespace' => 'ids.smw_namespace',
				'ns' => 'ids.smw_namespace',
				'id' => 'ids.smw_id',
				'iw' => 'ids.smw_iw',
				'sortkey' => 'ids.smw_sortkey',
			],
			'tables' => [
				'ids' => $smwIDs,
				'insts' => $smwCategoryInstances
			],
			'join_conds' => [
				'insts' => [
					'JOIN',
					[
						'ids.smw_id = insts.s_id',
						'ids.smw_namespace != ' . $cat_ns
					]
				]
			],
			'conds' => []
		];
		$applied_filters = $this->query->appliedFilters();
		foreach ( $applied_filters as $i => $af ) {
			// if any of this filter's values is 'none',
			// include another table to get this information
			$includes_none = false;
			foreach ( $af->values as $fv ) {
				if ( $fv->text === '_none' || $fv->text === ' none' ) {
					$includes_none = true;
					break;
				}
			}
			if ( $includes_none ) {
				$property_table_name = $dbr->tableName(
					PropertyTypeDbInfo::tableName( $af->filter->propertyType() ) );
				if ( $af->filter->propertyType() === 'page' ) {
					$property_table_nickname = "nr$i";
					$property_field = 'p_id';
				} else {
					$property_table_nickname = "na$i";
					$property_field = 'p_id';
				}
				$property_value = str_replace( ' ', '_', $af->filter->property() );
				$property_value = str_replace( "'", "\'", $property_value );
				// The sub-query that returns an SMW ID contains
				// a "SELECT MIN", even though by definition it
				// doesn't need to, because of occasional bugs
				// in SMW where the same page gets two
				// different SMW IDs.
				$query[ 'tables' ][ $property_table_nickname ] = new Subquery( "SELECT s_id FROM $property_table_name WHERE $property_field = (SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns)" );
				$query[ 'join_conds' ][ $property_table_nickname ] = [
					"LEFT OUTER JOIN",
					[
						"ids.smw_id = $property_table_nickname.s_id"
					]
				];
			}
		}
		foreach ( $applied_filters as $i => $af ) {
			$property_table_name = $dbr->tableName(
				PropertyTypeDbInfo::tableName( $af->filter->propertyType() ) );
			if ( $af->filter->propertyType() === 'page' ) {
				$sql = "";
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN";
				$query[ 'tables' ][ 'r' . $i ] = $property_table_name;
				$query[ 'join_conds' ][ 'r' . $i ] = [
					$sql,
					[
						"ids.smw_id = r$i.s_id"
					]
				];
				$sql = "";
				if ( $includes_none ) {
					$sql .= "LEFT OUTER ";
				}
				$sql .= "JOIN";
				$query[ 'tables' ][ 'o_ids' . $i ] = $smwIDs;
				$query[ 'join_conds' ][ 'o_ids' . $i ] = [
					$sql,
					[
						"r$i.o_id = o_ids$i.smw_id"
					]
				];
			} else {
				$query[ 'tables' ][ 'a' . $i ] = $property_table_name;
				$query[ 'join_conds' ][ 'a' . $i ] = [
					'JOIN',
					[
						"ids.smw_id = a$i.s_id"
					]
				];
			}
		}
		$category = $this->query->category();
		$subcategory = $this->query->subcategory();
		$actual_cat = str_replace( ' ', '_', ( $subcategory ) ? $subcategory : $category );
		$actual_cat = str_replace( "'", "\'", $actual_cat );

		$sql = "(SELECT smw_id FROM $smwIDs cat_ids WHERE smw_namespace = $cat_ns AND (smw_title = '$actual_cat'";
		foreach ( $this->query->allSubcategories() as $subcat ) {
			$subcat = str_replace( "'", "\'", $subcat );
			$sql .= " OR smw_title = '{$subcat}'";
		}
		$sql .= ")) ";
		$query['conds'][] = "insts.o_id IN " . new Subquery( $sql );
		foreach ( $applied_filters as $i => $af ) {
			$property_value = $af->filter->escapedProperty();
			$value_field = PropertyTypeDbInfo::valueField( $af->filter->propertyType() );
			if ( $af->filter->propertyType() === 'page' ) {
				$property_field = "r$i.p_id";
				$sql = "SELECT MIN(smw_id) FROM $smwIDs WHERE (smw_title = '$property_value' AND smw_namespace = $prop_ns)";
				if ( $includes_none ) {
					$sql .= " OR $property_field IS NULL";
				}
				$sql .= " AND ";
				$value_field = "o_ids$i.smw_title";
			} else {
				$property_field = "a$i.p_id";
				$sql = "SELECT MIN(smw_id) FROM $smwIDs WHERE smw_title = '$property_value' AND smw_namespace = $prop_ns AND ";
				if ( strncmp( $value_field, '(IF(o_blob IS NULL', 18 ) === 0 ) {
					$value_field = str_replace( 'o_', "a$i.o_", $value_field );
				} else {
					$value_field = "a$i.$value_field";
				}
			}
			$sql .= $af->checkSQL( $value_field );
			$query[ 'conds' ][] = "$property_field = " . new Subquery( $sql );
		}
		return $query;
	}

	protected function getSQL(): ?string {
		if ( !$this->query ) {
			return 'select null as sortkey where 0 = 1';
		}

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
			'footer' => $this->getPageContent( $out, $this->footerPage ),
		] ) );

		SMWOutputs::commitToOutputPage( $out );
	}

	/**
	 * Returns the HTML of $title and additionally adds the required modules to $out.
	 */
	private function getPageContent( $out, ?string $title ): string {
		[ $html, $modules ] = ( $this->getPageContent )( $title );
		$out->addModules( $modules );
		return $html;
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
