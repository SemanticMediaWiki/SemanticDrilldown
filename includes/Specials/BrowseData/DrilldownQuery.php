<?php

namespace SD\Specials\BrowseData;

use SD\Repository;

class DrilldownQuery {

	private Repository $repository;

	private $category;
	private $subcategory;
	private $filters;
	private $applied_filters;
	private $remaining_filters;
	/** @var null|string[] */
	private ?array $next_level_subcategories = null;
	/** @var null|string[] */
	private ?array $all_subcategories = null;

	public function __construct(
		Repository $repository,
		$category, $subcategory, $filters, $applied_filters, $remaining_filters
	) {
		$this->repository = $repository;
		$this->category = $category;
		$this->subcategory = $subcategory;
		$this->filters = $filters;
		$this->applied_filters = $applied_filters;
		$this->remaining_filters = $remaining_filters;
	}

	public function category() {
		return $this->category;
	}

	public function subcategory() {
		return $this->subcategory;
	}

	public function filters() {
		return $this->filters;
	}

	public function appliedFilters() {
		return $this->applied_filters;
	}

	public function remainingFilters() {
		return $this->remaining_filters;
	}

	public function nextLevelSubcategories() {
		if ( $this->next_level_subcategories === null ) {
			$this->next_level_subcategories =
				$this->repository->getCategoryChildren( $this->actualCategory(), true, 1 );
		}

		return $this->next_level_subcategories;
	}

	public function allSubcategories() {
		if ( $this->all_subcategories === null ) {
			$this->all_subcategories =
				$this->repository->getCategoryChildren( $this->actualCategory(), true, 10 );
		}

		return $this->all_subcategories;
	}

	private function actualCategory() {
		return str_replace( ' ', '_', $this->subcategory ?: $this->category );
	}

}
