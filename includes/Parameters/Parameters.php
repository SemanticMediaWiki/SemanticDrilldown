<?php

namespace SD\Parameters;

class Parameters {

	private ?string $title;
	private ?string $header;
	private ?string $footer;
	private ?Filters $filters;
	private ?DisplayParametersList $displayParametersList;

	public function __construct(
		?string $title = null, ?string $header = null, ?string $footer = null,
		?Filters $filters = null, ?DisplayParametersList $displayParameters = null
	) {
		$this->title = $title;
		$this->header = $header;
		$this->footer = $footer;
		$this->filters = $filters;
		$this->displayParametersList = $displayParameters;
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
}
