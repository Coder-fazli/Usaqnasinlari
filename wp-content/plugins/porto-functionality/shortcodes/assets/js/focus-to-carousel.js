/*
* Focus to owl carousel item after clicked
*/

( function( $ ) {
    $( document ).on( 'click', '.porto-focus-owl', function() {
        var $this = $( this ),
            options = $this.data('focus-owl'),
            $selector = $( options['selector'] );
        if ( ! $selector.hasClass( 'owl-carousel' ) ) {
            $selector = $selector.find( 'owl-carousel' ).length > 0 ? $selector.find( 'owl-carousel' ).eq(0) : false;
        }
        if ( $selector.length > 0 ) {
            $( 'html, body' ).animate( { scrollTop: $selector.offset().top - 20 }, 200 );
            setTimeout( () => {
                $selector.trigger( 'to.owl.carousel', [ options['order'] ? options['order'] : 0, 300, true ] );
            }, 200 );
        }
    } );
} )( jQuery );