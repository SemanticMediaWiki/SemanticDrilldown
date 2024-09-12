<?php

namespace SD\Specials\BrowseData;

/**
 * Displays an interface to let the user drill down through all data on
 * the wiki, using categories and custom-defined filters that have
 * previously been created.
 *
 * @author Yaron Koren
 * @author Sanyam Goyal
 */

use Closure;
use IncludableSpecialPage;
use MediaWiki\MediaWikiServices;
use SD\AppliedFilter;
use SD\BuildFilters;
use SD\DbService;
use SD\Parameters\LoadParameters;
use SD\Utils;

class SpecialBrowseData extends IncludableSpecialPage {

	private GetCategories $getCategories;
	private LoadParameters $loadParameters;
	private Closure $newDrilldownQuery;
	private Closure $newQueryPage;
	private BuildFilters $buildFilters;

	public function __construct(
		DbService $dbService, Closure $newUrlService, LoadParameters $loadParameters,
		$newDrilldownQuery, $newQueryPage, BuildFilters $buildFilters
	) {
		parent::__construct( 'BrowseData' );
		$this->getCategories = new GetCategories( $dbService, $newUrlService( $this->getRequest() ) );
		$this->loadParameters = $loadParameters;
		$this->newDrilldownQuery = $newDrilldownQuery;
		$this->newQueryPage = $newQueryPage;
		$this->buildFilters = $buildFilters;
	}

	public function execute( $query ): void {
		global $wgScriptPath, $sdgNumResultsPerPage;
		$sdSkinsPath = "$wgScriptPath/extensions/SemanticDrilldown/skins";

		$out = $this->getOutput();
		$request = $this->getRequest();
		$parser = MediaWikiServices::getInstance()->getParser();
		if ( $this->getPageTitle()->getNamespace() != NS_SPECIAL ) {
			$parser->getOutput()->updateCacheExpiry( 0 );
		}
		$this->setHeaders();
		$out->addModules( 'ext.semanticdrilldown.main' );
		$out->addScript( '<!--[if IE]><link rel="stylesheet" href="' . $sdSkinsPath . '/SD_IEfixes.css" media="screen" /><![endif]-->' );

		// set default
		if ( $sdgNumResultsPerPage == null ) {
			$sdgNumResultsPerPage = 250;
		}

		// get information on current category, subcategory and filters
		// that have already been applied from the query string
		$category = str_replace( '_', ' ', $request->getVal( '_cat' ) );
		// if query string did not contain this variables, try the URL
		if ( !$category ) {
			$queryparts = explode( '/', $query, 1 );
			$category = isset( $queryparts[0] ) ? $queryparts[0] : '';
		}

		$out->addHtml( ( new ProcessTemplate )( 'Categories', ( $this->getCategories )( $category ) ) );

		if ( $category ) {
			$parameters = ( $this->loadParameters )( $category );
			$category_title = $parameters->title();
			if ( $category_title === null ) {
				$category_title = wfMessage( 'browsedata' )->text() . html_entity_decode( wfMessage( 'colon-separator' )->text() ) . str_replace( '_', ' ', $category );
			}

			[ $limit, $offset ] = $request->getLimitOffsetForUser(
				$this->getUser(),
				$sdgNumResultsPerPage,
				'sdlimit'
			);

			$out->addHTML( "<div class=\"drilldown-results\">\n" );

			$drilldownQuery = $this->createDrilldownQuery( $category, $parameters );
			$queryPage = ( $this->newQueryPage )( $this->getContext(), $parameters, $drilldownQuery, $offset, $limit );
			$queryPage->execute( $query );

			$out->addHTML( "</div> <!-- drilldown-results -->\n" );
		} else {
			$category_title = wfMessage( 'browsedata' )->text();
		}

		// This has to be set last, because otherwise the QueryPage
		// code will overwrite it.
		$out->setPageTitle( $category_title );
	}

	private function createDrilldownQuery( $category, $parameters ) {
		$request = $this->getRequest();

		$subcategory = Utils::escapeString( $request->getVal( '_subcat' ) );
		$filters = ( $this->buildFilters )( $category, $parameters->filters() );
		$filter_used = array_fill( 0, count( $filters ), false );
		$applied_filters = [];
		$remaining_filters = [];
		foreach ( $filters as $i => $filter ) {
			$filter_name = str_replace( ' ', '_', $filter->name() );
			$search_terms = $request->getArray( '_search_' . $filter_name );
			$lower_date = $request->getVal( '_lower_' . $filter_name );
			$upper_date = $request->getVal( '_upper_' . $filter_name );
			if ( $vals_array = $request->getArray( $filter_name ) ) {
				foreach ( $vals_array as &$val ) {
					$val = str_replace( '_', ' ', $val );
				}
				$applied_filters[] = AppliedFilter::create( $filter, $vals_array );
				$filter_used[$i] = true;
			} elseif ( $search_terms != null ) {
				foreach ( $search_terms as &$search_term ) {
					$search_term = str_replace( '_', ' ', $search_term );
				}
				$applied_filters[] = AppliedFilter::create( $filter, [], $search_terms );
				$filter_used[$i] = true;
			} elseif ( $lower_date != null || $upper_date != null ) {
				$applied_filters[] =
					AppliedFilter::create( $filter, [], null, $lower_date, $upper_date );
				$filter_used[$i] = true;
			}
		}
		// add every unused filter to the $remaining_filters array,
		// unless it requires some other filter that hasn't been applied
		foreach ( $filters as $i => $filter ) {
			$matched_all_required_filters = true;
			foreach ( $filter->requiredFilters() as $required_filter ) {
				$found_match = false;
				foreach ( $applied_filters as $af ) {
					if ( $af->filter->name() == $required_filter ) {
						$found_match = true;
					}
				}
				if ( !$found_match ) {
					$matched_all_required_filters = false;
				}
			}
			if ( $matched_all_required_filters ) {
				if ( !$filter_used[$i] ) {
					$remaining_filters[] = $filter;
				}
			}
		}

		return ( $this->newDrilldownQuery )( $category, $subcategory, $filters, $applied_filters, $remaining_filters );
	}

	/**
	 * @inheritDoc
	 */
	protected function getGroupName() {
		return 'sd_group';
	}
}
