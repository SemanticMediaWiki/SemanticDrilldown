/**
 * SemanticDrilldown.js
 *
 * Javascript code for use by the Semantic Drilldown extension.
 *
 * @param $
 * @author Sanyam Goyal
 */
( function ( $ ) {
	const sd = window.sd = window.sd || {};

	/**
	 * Wraps the first occurrence of `term` in `label` with `<strong>` tags.
	 * Returns `label` unchanged when no match is found.
	 *
	 * @param {string} term   Search term (regex-special characters are escaped)
	 * @param {string} label  Display label to highlight
	 * @return {string}
	 */
	sd.highlightMatch = function ( term, label ) {
		const escapedTerm = term.replace( /[.*+?^${}()|[\]\\]/g, '\\$&' );
		const re = new RegExp( '(?![^&;]+;)(?!<[^<>]*)(' + escapedTerm + ')(?![^<>]*>)(?![^&;]+;)', 'gi' );
		const loc = label.search( re );
		if ( loc >= 0 ) {
			return label.slice( 0, Math.max( 0, loc ) ) + '<strong>' + label.slice( loc, loc + term.length ) + '</strong>' + label.slice( loc + term.length );
		}
		return label;
	};

	$.ui.autocomplete.prototype._renderItem = function ( ul, item ) {
		const t = sd.highlightMatch( this.term, item.label );
		return $( '<li>' )
			.data( 'item.autocomplete', item )
			.append( ' <a>' + t + '</a>' )
			.appendTo( ul );
	};

	$.widget( 'ui.combobox', {
		_create: function () {
			const self = this;
			const select = this.element.hide();
			const inp_id = select[ 0 ].options[ 0 ].value;
			const curval = select[ 0 ].name;
			const input = $( '<input id = "' + inp_id + '" type="text" name="' + inp_id + '" value="' + curval + '">' )
				.insertAfter( select )
				.autocomplete( {
					source: function ( request, response ) {
						const matcher = new RegExp( request.term, 'i' );
						response( select.children( 'option' ).map( function () {
							const text = this.innerHTML;
							if ( this.value && ( !request.term || matcher.test( text ) ) ) {
								return {
									id: this.value,
									label: text,
									value: this.value
								};
							}
						} ) );
					},
					delay: 0,
					select: function ( event, ui ) {
						if ( !ui.item ) {
							// if it didn't match anything,
							// just leave it as it is
							return false;
						}
						select.val( ui.item.id );
						self._trigger( 'selected', event, {
							item: select.find( '[value="' + ui.item.id + '"]' )
						} );
						setTimeout( () => {
							select[ 0 ].form.submit();
						}, 0 );

					},
					minLength: 0
				} )
				.addClass( 'ui-widget ui-widget-content ui-corner-left' );
			$( '<button type="button">&nbsp;</button>' )
				.attr( 'tabIndex', -1 )
				.attr( 'title', 'Show All Items' )
				.insertAfter( input )
				.button( {
					icons: {
						primary: 'ui-icon-triangle-1-s'
					},
					text: false
				} )
				.removeClass( 'ui-corner-all' )
				.addClass( 'ui-corner-right ui-button-icon' )
				// Need to do some hardcoded CSS here, to override
				// pesky jQuery UI settings!
				// Unfortunately, calling .css() won't work, because
				// it ignores '!important'.
				.attr( 'style', 'width: 2.4em; margin: 0 !important; border-radius: 0' )
				.click( () => {
					// close if already visible
					if ( input.autocomplete( 'widget' ).is( ':visible' ) ) {
						input.autocomplete( 'close' );
						return;
					}
					// pass empty string as value to search for, displaying all results
					input.autocomplete( 'search', '' );
					input.focus();
				} );
		}
	} );

	$.fn.toggleValuesDisplay = function () {
		const $valuesDiv = $( this ).closest( '.drilldown-filter' )
			.find( '.drilldown-filter-values' );
		if ( $valuesDiv.css( 'display' ) === 'none' ) {
			$valuesDiv.css( 'display', 'block' );
			const downArrowImage = mediaWiki.config.get( 'sdgDownArrowImage' );
			this.find( 'img' ).attr( 'src', downArrowImage );
		} else {
			$valuesDiv.css( 'display', 'none' );
			const rightArrowImage = mediaWiki.config.get( 'sdgRightArrowImage' );
			this.find( 'img' ).attr( 'src', rightArrowImage );
		}
	};

	function removePagingIfNotRequired() {
		if ( $( '.drilldown-results-output-paged' ).length === 0 ) {
			const $pagingSectionSelectors = $( '#drilldown-top-paging, .mw-spcontent > p:last-of-type' );
			$pagingSectionSelectors.remove();
		}
	}

	$( () => {
		removePagingIfNotRequired();
		$( '.semanticDrilldownCombobox' ).combobox();
		$( '.drilldown-values-toggle' ).on( 'click', function () {
			$( this ).toggleValuesDisplay();
		} );
	} );

}( jQuery ) );
