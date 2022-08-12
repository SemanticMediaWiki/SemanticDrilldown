<?php

namespace SD;

class PossibleFilterValue {

	private string $value;
	private ?string $displayValue;
	private ?int $count;

	public function __construct( string $value, ?int $count = null, string $displayValue = null ) {
		$this->value = $value;
		$this->displayValue = $displayValue;
		$this->count = $count;
	}

	public function value(): string {
		return $this->value;
	}

	public function displayValue(): string {
		return $this->displayValue ?? $this->value;
	}

	public function count(): ?int {
		return $this->count;
	}

}
