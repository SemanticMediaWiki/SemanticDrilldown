'use strict';

const { JSDOM } = require( 'jsdom' );
const { TextEncoder, TextDecoder } = require( 'util' );

global.TextEncoder = TextEncoder;
global.TextDecoder = TextDecoder;

const dom = new JSDOM( '<!DOCTYPE html><html><body></body></html>' );

// Set window/document globals BEFORE requiring jQuery so it initialises
// with our jsdom environment rather than exporting a factory function.
global.window = dom.window;
global.document = dom.window.document;

const jQuery = require( 'jquery' );
global.jQuery = global.$ = jQuery;

// Stub jQuery UI — not available in Node.js
jQuery.widget = function () {};
jQuery.ui = { autocomplete: { prototype: {} } };

// Pre-stub the combobox plugin that $.widget('ui.combobox') would normally register
jQuery.fn.combobox = function () { return this; };

// Expose the sd namespace so SemanticDrilldown.js can attach helpers to it.
// Must be set on dom.window (= global.window) so the IIFE's `window.sd` reference
// resolves to the same object that tests access via global.sd.
global.sd = dom.window.sd = {};

// Stub mediaWiki used inside toggleValuesDisplay
global.mediaWiki = {
	config: {
		get: function () { return ''; }
	}
};

require( '../../libs/SemanticDrilldown.js' );
