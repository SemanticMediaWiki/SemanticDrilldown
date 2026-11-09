<?php

namespace SD\Parameters;

class Parameters {

	private ?string $title;
	private ?string $header;
	private ?string $footer;
	private ?Filters $filters;
	private ?DisplayParametersList $displayParametersList;
	private ?string $sort;
	private ?string $resultsPerPage;

	public function __construct(
		?string $title = null, ?string $header = null, ?string $footer = null,
		?Filters $filters = null, ?DisplayParametersList $displayParameters = null,
		?string $sort = null, ?string $resultsPerPage = null
	) {
		$this->title = $title;
		$this->header = $header;
		$this->footer = $footer;
		$this->filters = $filters;
		$this->displayParametersList = $displayParameters;
		$this->sort = $sort;
		$this->resultsPerPage = $resultsPerPage;
	}

	public function title(): ?string {
		return $this->title;
	}

	public function header(): ?string {
		return $this->header;
	}

	public function footer(): ?string {
		return $this->footer;
	}

	public function filters(): ?Filters {
		return $this->filters;
	}

	public function displayParametersList(): ?DisplayParametersList {
		return $this->displayParametersList;
	}

	public function sort(): ?string {
		return $this->sort;
	}

	/**
	 * @return int|null the number of results per page of the category, null if none (or no valid
	 *   number) is set
	 */
	public function resultsPerPage(): ?int {
		return ResultsPerPage::toNumber( $this->resultsPerPage );
	}
}
