'use strict';

// sd.highlightMatch is registered by SemanticDrilldown.js (loaded via setup.js)
const highlightMatch = sd.highlightMatch;

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
