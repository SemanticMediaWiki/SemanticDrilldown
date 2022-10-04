<?php

namespace SD\Specials\BrowseData;

use PageProps;
use SD\Utils;
use Title;

class GetAppliedFilters {

	private PageProps $pageProps;
	private UrlService $urlService;
	private DrilldownQuery $query;

	public function __construct(
		PageProps $pageProps, UrlService $urlService, DrilldownQuery $query
	) {
		$this->pageProps = $pageProps;
		$this->urlService = $urlService;
		$this->query = $query;
	}

	public function __invoke(): array {
		global $wgScriptPath;
		$sdSkinsPath = $wgScriptPath . '/extensions/SemanticDrilldown/skins';

		$remainingHtml = '';
		$subcategory_text = wfMessage( 'sd_browsedata_subcategory' )->text();

		$query = $this->query;

		if ( $query->subcategory() ) {
			$remainingHtml .= " > ";
			$remainingHtml .= "$subcategory_text: ";
			$subcat_string = str_replace( '_', ' ', $query->subcategory() );
			$remove_filter_url = $this->getUrl( $query->category(), $query->appliedFilters() );
			$remainingHtml .= '<span class="drilldown-header-value">' . $subcat_string . '</span>' .
				'<a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removesubcategoryfilter' )->text() . '"><img src="' . $sdSkinsPath . '/filter-x.png" /></a> ';
		}

		foreach ( $query->appliedFilters() as $i => $af ) {
			$remainingHtml .= ( !$query->subcategory() && $i == 0 ) ? " > " : "<span class=\"drilldown-header-value\">&</span>";
			$filter_label = $af->filter->name();
			// add an "x" to remove this filter, if it has more
			// than one value
			if ( count( $query->appliedFilters()[$i]->values ) > 1 ) {
				$temp_filters_array = $query->appliedFilters();
				array_splice( $temp_filters_array, $i, 1 );
				$remove_filter_url = $this->getUrl( $query->category(), $temp_filters_array, $query->subcategory() );
				array_splice( $temp_filters_array, $i, 0 );
				$remainingHtml .= $filter_label . ' <a href="' . $remove_filter_url . '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdSkinsPath . '/filter-x.png" /></a> : ';
			} else {
				$remainingHtml .= "$filter_label: ";
			}
			foreach ( $af->values as $j => $fv ) {
				if ( $j > 0 ) {
					$remainingHtml .= ' <span class="drilldown-or">' . wfMessage( 'sd_browsedata_or' )->text() . '</span> ';
				}
				$filter_text = Utils::escapeString( $this->getNiceAppliedFilterValue( $af->filter->propertyType(), $fv->text ) );
				$temp_filters_array = $query->appliedFilters();
				$removed_values = array_splice( $temp_filters_array[$i]->values, $j, 1 );
				$remove_filter_url = $this->getUrl( $query->category(), $temp_filters_array, $query->subcategory() );
				array_splice( $temp_filters_array[$i]->values, $j, 0, $removed_values );
				$remainingHtml .= '<span class="drilldown-header-value">' . $filter_text . '</span> <a href="' . $remove_filter_url
					. '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdSkinsPath . '/filter-x.png" /></a>';
			}

			if ( $af->search_terms != null ) {
				foreach ( $af->search_terms as $j => $search_term ) {
					if ( $j > 0 ) {
						$remainingHtml .= ' <span class="drilldown-or">' . wfMessage( 'sd_browsedata_or' )->text() . '</span> ';
					}
					$filter_text = Utils::escapeString( $this->getNiceAppliedFilterValue( $af->filter->propertyType(), $search_term ) );
					$temp_filters_array = $query->appliedFilters();
					$removed_values = array_splice( $temp_filters_array[$i]->search_terms, $j, 1 );
					$remove_filter_url = $this->getUrl( $query->category(), $temp_filters_array, $query->subcategory() );
					array_splice( $temp_filters_array[$i]->search_terms, $j, 0, $removed_values );
					$remainingHtml .= '<span class="drilldown-header-value">~ \'' . $filter_text . '\'</span> <a href="' . $remove_filter_url
						. '" title="' . wfMessage( 'sd_browsedata_removefilter' )->text() . '"><img src="' . $sdSkinsPath . '/filter-x.png" /> </a>';
				}
			} elseif ( $af->lower_date != null || $af->upper_date != null ) {
				$remainingHtml .= "<span class=\"drilldown-header-value\">" . $af->lower_date_string . " - " . $af->upper_date_string . "</span>";
			}
		}

		return [
			'category' => str_replace( '_', ' ', $query->category() ),
			'categoryUrl' => $this->getUrl( $query->category() ),
			'remainingHtml' => $remainingHtml,
		];
	}

	private function getNiceAppliedFilterValue( string $propertyType, string $value ): string {
		if ( $propertyType === 'page' ) {
			$title = Title::newFromText( $value );
			$displayTitle = $this->pageProps->getProperties( $title, 'displaytitle' );
			$value = $displayTitle === [] ? $value : htmlspecialchars_decode( array_values( $displayTitle )[0] );
		}

		return Utils::getNiceFilterValue( $propertyType, $value );
	}

	private function getUrl( $category, $applied_filters = [], $subcategory = null ): string {
		return $this->urlService->getUrl( $category, $applied_filters, $subcategory );
	}

}
