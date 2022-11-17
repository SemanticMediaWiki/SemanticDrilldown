<?php

namespace SD\Specials\BrowseData;

use SD\AppliedFilterValue;
use WebRequest;

class UrlService {

	private $browseDataLocalUrl;
	private $request;
	private $query;

	public function __construct(
		string $browseDataLocalUrl, WebRequest $request, ?DrilldownQuery $query
	) {
		$this->browseDataLocalUrl = $browseDataLocalUrl;
		$this->request = $request;
		$this->query = $query;
	}

	public function getUrl(
		$category, $applied_filters = [], $subcategory = null, $filter_to_remove = null
	): string {
		$url = $this->browseDataLocalUrl . '/' . $category;
		if ( $this->showSingleCat() ) {
			$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
			$url .= "_single";
		}
		if ( $subcategory ) {
			$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
			$url .= "_subcat=" . $subcategory;
		}
		foreach ( $applied_filters as $i => $af ) {
			if ( $af->filter->name() == $filter_to_remove ) {
				continue;
			}
			if ( count( $af->values ) == 0 ) {
				// do nothing
			} elseif ( count( $af->values ) == 1 ) {
				$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
				$url .= urlencode( str_replace( ' ', '_', $af->filter->name() ) ) . "=" . urlencode( str_replace( ' ', '_', $af->values[0]->text ) );
			} else {
				usort( $af->values, [ AppliedFilterValue::class, "compare" ] );
				foreach ( $af->values as $j => $fv ) {
					$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
					$url .= urlencode( str_replace( ' ', '_', $af->filter->name() ) ) . "[$j]=" . urlencode( str_replace( ' ', '_', $fv->text ) );
				}
			}
			if ( $af->search_terms != null ) {
				foreach ( $af->search_terms as $j => $search_term ) {
					$url .= ( strpos( $url, '?' ) ) ? '&' : '?';
					$url .= '_search_' . urlencode( str_replace( ' ', '_', $af->filter->name() ) . '[' . $j . ']' ) . "=" . urlencode( str_replace( ' ', '_', $search_term ) );
				}
			}
		}
		return $url;
	}

	/**
	 * Used by QueryOage::linkParameters() to set URL for additional pages of results.
	 */
	public function getLinkParameters(): array {
		$params = [];
		if ( $this->showSingleCat() ) {
			$params['_single'] = null;
		}
		$params['_cat'] = $this->query->category();
		if ( $this->query->subcategory() ) {
			$params['_subcat'] = $this->query->subcategory();
		}

		foreach ( $this->query->appliedFilters() as $i => $af ) {
			if ( count( $af->values ) == 1 ) {
				$key_string = str_replace( ' ', '_', $af->filter->name() );
				$value_string = str_replace( ' ', '_', $af->values[0]->text );
				$params[$key_string] = $value_string;
			} else {
				// HACK - QueryPage's pagination-URL code,
				// which uses wfArrayToCGI(), doesn't support
				// two-dimensional arrays, which is what we
				// need - instead, add the brackets directly
				// to the key string
				foreach ( $af->values as $i => $value ) {
					$key_string = str_replace( ' ', '_', $af->filter->name() . "[$i]" );
					$value_string = str_replace( ' ', '_', $value->text );
					$params[$key_string] = $value_string;
				}
			}

			// Add search terms (if any).
			$search_terms = $af->search_terms ?? [];
			foreach ( $search_terms as $text ) {
				$key_string = '_search_' . str_replace( ' ', '_', $af->filter->name() . "[$i]" );
				$value_string = str_replace( ' ', '_', $text );
				$params[$key_string] = $value_string;
			}
		}
		return $params;
	}

	public function showSingleCat() {
		return $this->request->getCheck( '_single' );
	}

}
