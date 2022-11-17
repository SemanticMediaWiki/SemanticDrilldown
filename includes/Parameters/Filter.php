<?php

namespace SD\Parameters;

class Filter {

	private string $name;
	private ?string $property;
	private ?string $category;
	private ?string $requires;
	private ?string $int;

	public function __construct(
		string $name, ?string $property, ?string $category, ?string $requires, ?string $int
	) {
		$this->name = $name;
		$this->property = $property;
		$this->category = $category;
		$this->requires = $requires;
		$this->int = $int;
	}

	public function name(): string {
		return $this->name;
	}

	public function property(): ?string {
		return $this->property;
	}

	public function category(): ?string {
		return $this->category;
	}

	public function requires(): ?string {
		return $this->requires;
	}

	public function int(): ?string {
		return $this->int;
	}

}
