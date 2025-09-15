/**
 * Focus to Owl Carousel/Swiper item after clicked
 *
 * @since 3.1.0
*/

( function( $ ) {
    $( document ).on( 'click', '.porto-focus-slider', function(e) {
        e.preventDefault();
        var $this = $( this ),
            options = JSON.parse( $this.attr('data-focus-slider') ),
            selector = options['selector'];

        if ( '.' != selector.charAt(0) && '#' != selector.charAt(0) ) {
            selector = '.' + selector;
        }
        var $wrap = $(selector ), $selector;

        if ( $wrap.length == 0 ) {
            return;
        }
        // Owl Carousel
        if ( ! $wrap.hasClass( 'owl-carousel' ) ) {
            $selector = $wrap.find( '.owl-carousel' ).length > 0 ? $wrap.find( '.owl-carousel' ).eq(0) : false;
        } else {
            $selector = $wrap;
        }
        if ( $selector.length > 0 ) {
            $selector.trigger( 'to.owl.carousel', [ options['order'] ? options['order'] : 0, 300, true ] );
        }

        // Swiper
        if ( ! $wrap.hasClass( 'swiper' ) ) {
            $selector = $wrap.find( '.swiper' ).length > 0 ? $wrap.find( '.swiper' ).eq(0) : false;
        } else {
            $selector = $wrap;
        }
        if ( $selector.length > 0 ) {
            $selector.data( 'swiper' ).slideTo( options['order'] ? options['order'] : 0, 300 );
        }
    } );
} )( jQuery );