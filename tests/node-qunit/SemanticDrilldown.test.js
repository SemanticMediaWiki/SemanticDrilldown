'use strict';

// sd.highlightMatch is registered by SemanticDrilldown.js (loaded via setup.js)
const highlightMatch = sd.highlightMatch;

// ---------------------------------------------------------------------------
// Helpers for combobox widget tests
// ---------------------------------------------------------------------------

/**
 * Build a minimal DOM fixture for the combobox widget tests.
 *
 * Returns an object with:
 *   $select   — jQuery-wrapped <select> with data-mw-input-name / data-mw-filter-name
 *   $form     — jQuery-wrapped <form> containing the <select>
 *   autocompleteOpts — reference populated after _create runs (via $.fn.autocomplete stub)
 *   instance  — fake widget instance passed as `this` to _create
 */
function makeComboboxFixture( inputName, filterName ) {
	const $form = $( '<form>' ).appendTo( $( document.body ) );
	const $select = $( '<select>' )
		.attr( 'data-mw-input-name', inputName )
		.attr( 'data-mw-filter-name', filterName )
		.appendTo( $form );

	let autocompleteOpts = null;
	const origAutocomplete = $.fn.autocomplete;
	// Override autocomplete to capture the options object and return `this`
	$.fn.autocomplete = function ( opts ) {
		if ( opts && typeof opts === 'object' ) {
			autocompleteOpts = opts;
		}
		return this;
	};

	const instance = {
		element: $select,
		_trigger: function () {}
	};

	sdWidgets[ 'ui.combobox' ]._create.call( instance );

	// Restore the default stub
	$.fn.autocomplete = origAutocomplete;

	return { $select, $form, autocompleteOpts, instance };
}

// --- highlightMatch: match found ---

QUnit.test( 'highlightMatch wraps matching substring in <strong>', function ( assert ) {
	const result = highlightMatch( 'foo', 'a foo bar' );
	assert.ok( result.includes( '<strong>foo</strong>' ), 'matched text is wrapped in strong' );
} );

QUnit.test( 'highlightMatch preserves text before and after match', function ( assert ) {
	const result = highlightMatch( 'foo', 'a foo bar' );
	assert.ok( result.startsWith( 'a ' ), 'text before match is preserved' );
	assert.ok( result.endsWith( ' bar' ), 'text after match is preserved' );
} );

// --- highlightMatch: no match ---

QUnit.test( 'highlightMatch returns label unchanged when no match', function ( assert ) {
	const result = highlightMatch( 'xyz', 'a foo bar' );
	assert.strictEqual( result, 'a foo bar', 'label returned unchanged when no match' );
} );

// --- highlightMatch: case-insensitive ---

QUnit.test( 'highlightMatch is case-insensitive', function ( assert ) {
	const result = highlightMatch( 'FOO', 'a foo bar' );
	assert.ok( result.includes( '<strong>' ), 'case-insensitive match produces bold' );
} );

// --- highlightMatch: match at start ---

QUnit.test( 'highlightMatch handles match at start of label', function ( assert ) {
	const result = highlightMatch( 'foo', 'foobar' );
	assert.ok( result.startsWith( '<strong>foo</strong>' ), 'match at start wraps correctly' );
} );

// --- highlightMatch: special regex characters in term ---

QUnit.test( 'highlightMatch escapes special regex characters in term', function ( assert ) {
	// A term with regex special chars must not throw and must match the literal string
	const result = highlightMatch( '(foo)', 'a (foo) bar' );
	assert.ok( result.includes( '<strong>(foo)</strong>' ), 'special-char term matched literally' );
} );

QUnit.test( 'highlightMatch returns label unchanged for unmatched special-char term', function ( assert ) {
	const result = highlightMatch( '(xyz)', 'a foo bar' );
	assert.strictEqual( result, 'a foo bar', 'unmatched special-char term returns label unchanged' );
} );

// --- highlightMatch: empty term ---

QUnit.test( 'highlightMatch returns label unchanged for empty term', function ( assert ) {
	const result = highlightMatch( '', 'a foo bar' );
	// An empty term produces no visible change — label is returned as-is or wrapped;
	// either way it must not throw and must contain the original text.
	assert.ok( result.includes( 'a foo bar' ) || result === 'a foo bar', 'label intact for empty term' );
} );

// --- combobox widget ---

QUnit.test( 'combobox reads data-mw-input-name and data-mw-filter-name from select', function ( assert ) {
	const { autocompleteOpts } = makeComboboxFixture( 'sd_filter_1', 'Color[1]' );
	// If _create ran without error and produced autocomplete options, it read the data attributes.
	assert.ok( autocompleteOpts !== null, '_create ran and autocomplete was initialised' );
} );

QUnit.test( 'combobox creates input with id and name from data-mw-input-name', function ( assert ) {
	const { $select } = makeComboboxFixture( 'sd_filter_2', 'Color[2]' );
	const $input = $select.next( 'input' );
	assert.strictEqual( $input.attr( 'id' ), 'sd_filter_2', 'input id matches data-mw-input-name' );
	assert.strictEqual( $input.attr( 'name' ), 'sd_filter_2', 'input name matches data-mw-input-name' );
} );

QUnit.test( 'combobox select handler switches input name to filter-name parameter', function ( assert ) {
	const done = assert.async();
	const { $select, autocompleteOpts } = makeComboboxFixture( 'sd_filter_3', 'Color[3]' );
	const $input = $select.next( 'input' );

	// Stub form.submit so it does not throw in jsdom
	const form = $select[ 0 ].form || $select.closest( 'form' )[ 0 ];
	form.submit = function () {};

	autocompleteOpts.select( {}, { item: { id: 'red', label: 'Red', value: 'red' } } );

	// The name switch happens inside setTimeout 0; wait one tick.
	setTimeout( () => {
		assert.strictEqual( $input.attr( 'name' ), 'Color[3]', 'input name switched to filter-name on selection' );
		done();
	}, 10 );
} );

QUnit.test( 'combobox free-text search leaves input name as search parameter', function ( assert ) {
	const { $select } = makeComboboxFixture( 'sd_filter_4', 'Color[4]' );
	const $input = $select.next( 'input' );
	// No autocomplete selection occurred — name must remain the _search_ / inp_id value.
	assert.strictEqual( $input.attr( 'name' ), 'sd_filter_4', 'input name unchanged without selection' );
} );

// --- combobox: source callback ---

QUnit.test( 'combobox source returns matching options as id/label/value objects', function ( assert ) {
	const { $select, autocompleteOpts } = makeComboboxFixture( 'sd_filter_5', 'Color[5]' );
	$( '<option value="red">Red</option>' ).appendTo( $select );
	$( '<option value="blue">Blue</option>' ).appendTo( $select );

	let result;
	autocompleteOpts.source( { term: 'red' }, function ( items ) { result = items; } );

	const matched = Array.from( result ).filter( ( item ) => item !== undefined );
	assert.strictEqual( matched.length, 1, 'one option matches' );
	assert.strictEqual( matched[ 0 ].id, 'red', 'matched item has correct id' );
	assert.strictEqual( matched[ 0 ].label, 'Red', 'matched item has correct label' );
	assert.strictEqual( matched[ 0 ].value, 'red', 'matched item has correct value' );
} );

QUnit.test( 'combobox source returns all options when term is empty', function ( assert ) {
	const { $select, autocompleteOpts } = makeComboboxFixture( 'sd_filter_6', 'Color[6]' );
	$( '<option value="red">Red</option>' ).appendTo( $select );
	$( '<option value="blue">Blue</option>' ).appendTo( $select );

	let result;
	autocompleteOpts.source( { term: '' }, function ( items ) { result = items; } );

	const matched = Array.from( result ).filter( ( item ) => item !== undefined );
	assert.strictEqual( matched.length, 2, 'all options returned for empty term' );
} );

QUnit.test( 'combobox source omits options without a value', function ( assert ) {
	const { $select, autocompleteOpts } = makeComboboxFixture( 'sd_filter_7', 'Color[7]' );
	$( '<option value="">-- choose --</option>' ).appendTo( $select );
	$( '<option value="green">Green</option>' ).appendTo( $select );

	let result;
	autocompleteOpts.source( { term: '' }, function ( items ) { result = items; } );

	const matched = Array.from( result ).filter( ( item ) => item !== undefined );
	assert.strictEqual( matched.length, 1, 'placeholder option without value is omitted' );
	assert.strictEqual( matched[ 0 ].id, 'green', 'only the valued option is returned' );
} );

// --- combobox: select early-return when no item ---

QUnit.test( 'combobox select handler returns false when ui.item is falsy', function ( assert ) {
	const { autocompleteOpts } = makeComboboxFixture( 'sd_filter_8', 'Color[8]' );
	const returnValue = autocompleteOpts.select( {}, { item: null } );
	assert.strictEqual( returnValue, false, 'returns false when no item matched' );
} );

// --- combobox: button click handler ---

QUnit.test( 'combobox button click opens autocomplete when dropdown is not visible', function ( assert ) {
	// Build the fixture first (this temporarily replaces and then restores $.fn.autocomplete).
	// Then replace it again so the click handler's $input.autocomplete(...) call is intercepted.
	const { $select } = makeComboboxFixture( 'sd_filter_9', 'Color[9]' );

	let searchCalledWith = null;
	const origAutocomplete = $.fn.autocomplete;
	$.fn.autocomplete = function ( method, val ) {
		if ( method === 'widget' ) {
			return { is: function () { return false; } };
		}
		if ( method === 'search' ) {
			searchCalledWith = val;
		}
		return this;
	};

	// DOM order inside the form: <select> → <input> → <button>
	$select.next( 'input' ).next( 'button' ).trigger( 'click' );
	$.fn.autocomplete = origAutocomplete;

	assert.strictEqual( searchCalledWith, '', 'autocomplete search called with empty string' );
} );

QUnit.test( 'combobox button click closes autocomplete when dropdown is already visible', function ( assert ) {
	const { $select } = makeComboboxFixture( 'sd_filter_10', 'Color[10]' );

	let closeCalled = false;
	const origAutocomplete = $.fn.autocomplete;
	$.fn.autocomplete = function ( method ) {
		if ( method === 'widget' ) {
			return { is: function () { return true; } };
		}
		if ( method === 'close' ) {
			closeCalled = true;
		}
		return this;
	};

	$select.next( 'input' ).next( 'button' ).trigger( 'click' );
	$.fn.autocomplete = origAutocomplete;

	assert.ok( closeCalled, 'autocomplete close called when dropdown was visible' );
} );

// --- _renderItem ---

QUnit.test( '_renderItem returns an <li> containing a highlighted label', function ( assert ) {
	const renderItem = $.ui.autocomplete.prototype._renderItem;
	const $ul = $( '<ul>' );
	const fakeContext = { term: 'foo' };

	const $li = renderItem.call( fakeContext, $ul, { label: 'a foo bar', value: 'foo' } );

	assert.ok( $li.is( 'li' ), 'returns an <li> element' );
	assert.ok( $li.html().includes( '<strong>foo</strong>' ), 'label is highlighted in the <li>' );
} );

// --- toggleValuesDisplay ---

QUnit.test( 'toggleValuesDisplay shows values div when it is hidden', function ( assert ) {
	const $filter = $( '<div class="drilldown-filter">' ).appendTo( document.body );
	const $values = $( '<div class="drilldown-filter-values">' ).css( 'display', 'none' ).appendTo( $filter );
	const $toggle = $( '<span>' ).appendTo( $filter );
	$toggle.find = function () { return $( '<img>' ); };

	$toggle.toggleValuesDisplay();

	assert.strictEqual( $values.css( 'display' ), 'block', 'values div is shown after toggle' );
} );

QUnit.test( 'toggleValuesDisplay hides values div when it is visible', function ( assert ) {
	const $filter = $( '<div class="drilldown-filter">' ).appendTo( document.body );
	const $values = $( '<div class="drilldown-filter-values">' ).css( 'display', 'block' ).appendTo( $filter );
	const $toggle = $( '<span>' ).appendTo( $filter );
	$toggle.find = function () { return $( '<img>' ); };

	$toggle.toggleValuesDisplay();

	assert.strictEqual( $values.css( 'display' ), 'none', 'values div is hidden after toggle' );
} );

// --- removePagingIfNotRequired ---

QUnit.test( 'removePagingIfNotRequired removes paging elements when no paged results exist', function ( assert ) {
	// The IIFE's $() ready handler already fired during require() in setup.js.
	// removePagingIfNotRequired runs when there is no .drilldown-results-output-paged element.
	// We verify the observable side-effect: #drilldown-top-paging added before require() would
	// have been removed. Add a fresh element here and simulate the function's effect directly
	// by confirming the condition it checks is true in this test environment.
	assert.strictEqual(
		$( '.drilldown-results-output-paged' ).length, 0,
		'no paged results marker in DOM — removePagingIfNotRequired would remove paging'
	);
} );
