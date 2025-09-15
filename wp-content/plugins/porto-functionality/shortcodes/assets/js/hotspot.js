/**
 * Hotspot popup of mobile
 *
 * @since 3.3.0
 */

( function( $ ) {

	$( document ).ready( function() {
        if ( window.innerWidth <= 768 ) {
            $( '.porto-hotspot .popup-wrap' ).each( function() {
                var content = $( this );
                var offsetLeft = content.offset().left;
                var offsetRight = window.innerWidth - ( offsetLeft + content.outerWidth() );            
                if ( offsetLeft <= 0 ) {
                    content.css( 'marginLeft', Math.abs( offsetLeft - 15 ) + 'px' );
                }
                if ( offsetRight <= 0 ) {
                    content.css( 'marginLeft', offsetRight - 15 + 'px' );
                }
            });
        }
	} );
} )( jQuery );
