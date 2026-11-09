<?php

namespace SD\Parameters;

/**
 * The order of the results of a category: "modified" lists the most recently changed pages first,
 * any other value (including none) keeps the alphabetical order.
 */
class Sort extends SimpleParameter {
	public const PAGE_PROPERTY_NAME = 'SDSort';

	public const MODIFIED = 'modified';
}
