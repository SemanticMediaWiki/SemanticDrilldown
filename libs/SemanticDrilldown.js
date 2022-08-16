/**
 * SemanticDrilldown.js
 *
 * Javascript code for use by the Semantic Drilldown extension.
 *
 * @param $
 * @author Sanyam Goyal
 */
( function ( $ ) {
	$.ui.autocomplete.prototype._renderItem = function ( ul, item ) {
		var re = new RegExp( '(?![^&;]+;)(?!<[^<>]*)(' + this.term.replace( /([^$()[]{}*.+?|\\])/gi, '\\$1' ) + ')(?![^<>]*>)(?![^&;]+;)', 'gi' );
		var loc = item.label.search( re );
		var t;
		if ( loc >= 0 ) {
			t = item.label.slice( 0, Math.max( 0, loc ) ) + '<strong>' + item.label.substr( loc, this.term.length ) + '</strong>' + item.label.slice( loc + this.term.length );
		} else {
			t = item.label;
		}
		return $( '<li></li>' )
			.data( 'item.autocomplete', item )
			.append( ' <a>' + t + '</a>' )
			.appendTo( ul );
	};

	$.widget( 'ui.combobox', {
		_create: function () {
			var self = this;
			var select = this.element.hide();
			var inp_id = select[ 0 ].options[ 0 ].value;
			var curval = select[ 0 ].name;
			var input = $( '<input id = "' + inp_id + '" type="text" name="' + inp_id + '" value="' + curval + '">' )
				.insertAfter( select )
				.autocomplete( {
					source: function ( request, response ) {
						var matcher = new RegExp( request.term, 'i' );
						response( select.children( 'option' ).map( function () {
							var text = this.innerHTML;
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
						setTimeout( function () {
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
				.click( function () {
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

}( jQuery ) );

jQuery.fn.toggleValuesDisplay = function () {
	jQuery.valuesDiv = jQuery( this ).closest( '.drilldown-filter' )
		.find( '.drilldown-filter-values' );
	if ( jQuery.valuesDiv.css( 'display' ) === 'none' ) {
		jQuery.valuesDiv.css( 'display', 'block' );
		var downArrowImage = mediaWiki.config.get( 'sdgDownArrowImage' );
		this.find( 'img' ).attr( 'src', downArrowImage );
	} else {
		jQuery.valuesDiv.css( 'display', 'none' );
		var rightArrowImage = mediaWiki.config.get( 'sdgRightArrowImage' );
		this.find( 'img' ).attr( 'src', rightArrowImage );
	}
};

jQuery( document ).ready( function () {
	jQuery( '.semanticDrilldownCombobox' ).combobox();
	jQuery( '.drilldown-values-toggle' ).click( function () {
		jQuery( this ).toggleValuesDisplay();
	} );
} );
