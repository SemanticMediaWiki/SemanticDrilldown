<?php

namespace SD\Specials\BrowseData;

use SD\DbService;

class GetCategories {

	private DbService $db;
	private UrlService $urlService;
	private DrilldownQuery $query;

	public function __construct(
		DbService $db, UrlService $urlService, DrilldownQuery $query
	) {
		$this->db = $db;
		$this->urlService = $urlService;
		$this->query = $query;
	}

	public function __invoke( $categories ): ?array {
		if ( $this->urlService->showSingleCat() ) {
			return null;
		}

		$toCategoryViewModel = function ( $category ) {
			$category_children = $this->db->getCategoryChildren( $category, false, 5 );
			return [
				'name' => $category . " (" . count( array_unique( $category_children ) ) . ")",
				'isSelected' => str_replace( '_', ' ', $this->query->category() ) == $category,
				'url' => $this->urlService->getUrl( $category )
			];
		};

		global $sdgShowCategoriesAsTabs;
		return [
			'categoriesAsTabs' => $sdgShowCategoriesAsTabs,
			'categories' => array_map( $toCategoryViewModel, $categories )
		];
	}

}
