<?php

namespace SD\Specials\BrowseData;

use SD\DbService;

class GetCategories {

	private DbService $db;
	private UrlService $urlService;

	public function __construct(
		DbService $db, UrlService $urlService
	) {
		$this->db = $db;
		$this->urlService = $urlService;
	}

	public function __invoke( $selectedCategory ): ?array {
		if ( $this->urlService->showSingleCat() ) {
			return null;
		}

		$toCategoryViewModel = function ( $category ) use ( $selectedCategory ) {
			$category_children = $this->db->getCategoryChildren( $category, false, 5 );

			return [
				'name' => $category . " (" . count( array_unique( $category_children ) ) . ")",
				'isSelected' => str_replace( '_', ' ', $selectedCategory ) == $category,
				'url' => $this->urlService->getUrl( $category )
			];
		};

		global $sdgShowCategoriesAsTabs;
		$categories = $this->db->getCategoriesForBrowsing();

		return [
			'categoriesAsTabs' => $sdgShowCategoriesAsTabs,
			'categories' => array_map( $toCategoryViewModel, $categories )
		];
	}

}
