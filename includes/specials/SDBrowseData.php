<?php
/**
 * Displays an interface to let the user drill down through all data on
 * the wiki, using categories and custom-defined filters that have
 * previously been created.
 *
 * @author Yaron Koren
 * @author Sanyam Goyal
 */

use MediaWiki\MediaWikiServices;

class SDBrowseData extends IncludableSpecialPage {

	public function __construct() {
		parent::__construct( 'BrowseData' );
	}

	public function execute( $query ) {
		global $wgScriptPath, $sdgNumResultsPerPage;

		// If this was called from the command line, exit.
		if ( PHP_SAPI === 'cli' ) {
			return '';
		}

		$out = $this->getOutput();
		$request = $this->getRequest();
		$parser = MediaWikiServices::getInstance()->getParser();
		if ( $this->getPageTitle()->getNamespace() != NS_SPECIAL ) {
			$parser->getOutput()->updateCacheExpiry( 0 );
		}
		$this->setHeaders();
		$out->addModules( 'ext.semanticdrilldown.main' );
		$out->addScript( '<!--[if IE]><link rel="stylesheet" href="' . $wgScriptPath . '/extensions/SemanticDrilldown/skins/SD_IEfixes.css" media="screen" /><![endif]-->' );

		// set default
		if ( $sdgNumResultsPerPage == null ) {
			$sdgNumResultsPerPage = 250;
		}

		if ( method_exists( $request, 'getLimitOffsetForUser' ) ) {
			// MW 1.35+
			list( $limit, $offset ) = $request->getLimitOffsetForUser(
				$this->getUser(),
				$sdgNumResultsPerPage,
				'sdlimit'
			);
		} else {
			list( $limit, $offset ) = $request->getLimitOffset(
				$sdgNumResultsPerPage,
				'sdlimit'
			);
		}
		$filters = [];

		// get information on current category, subcategory and filters
		// that have already been applied from the query string
		$category = str_replace( '_', ' ', $request->getVal( '_cat' ) );
		// if query string did not contain this variables, try the URL
		if ( !$category ) {
			$queryparts = explode( '/', $query, 1 );
			$category = isset( $queryparts[0] ) ? $queryparts[0] : '';
		}
		if ( !$category ) {
			$category_title = wfMessage( 'browsedata' )->text();
		} else {
			$category_title = SDUtils::getDrilldownTitleForCategory( $category );
			if ( $category_title == '' ) {
				$category_title = wfMessage( 'browsedata' )->text() . html_entity_decode( wfMessage( 'colon-separator' )->text() ) . str_replace( '_', ' ', $category );
			}
		}
		// if no category was specified, go with the first
		// category on the site, alphabetically
		if ( !$category ) {
			$categories = SDUtils::getCategoriesForBrowsing();
			if ( count( $categories ) == 0 ) {
				// There are apparently no top-level
				// categories in this wiki - just exit now.
				return 0;
			}
			$category = $categories[0];
		}

		$subcategory = SDUtils::escapeString( $request->getVal( '_subcat' ) );

		$filters = SDUtils::loadFiltersForCategory( $category );

		$filters_used = [];
		foreach ( $filters as $i => $filter ) {
			$filter_used[] = false;
		}
		$applied_filters = [];
		$remaining_filters = [];
		foreach ( $filters as $i => $filter ) {
			$filter_name = str_replace( [ ' ', "'" ], [ '_', "\'" ], $filter->name );
			$search_terms = $request->getArray( '_search_' . $filter_name );
			$lower_date = $request->getVal( '_lower_' . $filter_name );
			$upper_date = $request->getVal( '_upper_' . $filter_name );
			if ( $vals_array = $request->getArray( $filter_name ) ) {
				foreach ( $vals_array as $j => $val ) {

					// Escape the filter value to prevent malicious strings in the URLs
					$val = SDUtils::escapeString( $val );

					$vals_array[$j] = str_replace( '_', ' ', $val );
				}
				$applied_filters[] = SDAppliedFilter::create( $filter, $vals_array );
				$filter_used[$i] = true;
			} elseif ( $search_terms != null ) {
				$applied_filters[] = SDAppliedFilter::create( $filter, [], $search_terms );
				$filter_used[$i] = true;
			} elseif ( $lower_date != null || $upper_date != null ) {
				$applied_filters[] = SDAppliedFilter::create( $filter, [], null, $lower_date, $upper_date );
				$filter_used[$i] = true;
			}
		}
		// add every unused filter to the $remaining_filters array,
		// unless it requires some other filter that hasn't been applied
		foreach ( $filters as $i => $filter ) {
			$matched_all_required_filters = true;
			foreach ( $filter->required_filters as $required_filter ) {
				$found_match = false;
				foreach ( $applied_filters as $af ) {
					if ( $af->filter->name == $required_filter ) {
						$found_match = true;
					}
				}
				if ( !$found_match ) {
					$matched_all_required_filters = false;
					continue;
				}
			}
			if ( $matched_all_required_filters ) {
				if ( !$filter_used[$i] ) {
					$remaining_filters[] = $filter;
				}
			}
		}

		$out->addHTML( "\n			<div class=\"drilldown-results\">\n" );
		$rep = new SDBrowseDataPage( $category, $subcategory, $applied_filters, $remaining_filters, $offset, $limit );
		$num = $rep->execute( $query );
		$out->addHTML( "\n			</div> <!-- drilldown-results -->\n" );

		// This has to be set last, because otherwise the QueryPage
		// code will overwrite it.
		$out->setPageTitle( $category_title );

		return $num;
	}

	/**
	 * @inheritDoc
	 */
	protected function getGroupName() {
		return 'sd_group';
	}
}
