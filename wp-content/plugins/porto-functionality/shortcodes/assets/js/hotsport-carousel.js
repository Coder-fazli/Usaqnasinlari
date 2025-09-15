/*
* Integrate Hotspot with Carousel
*/

( function( $ ) {
    $( document ).on( 'click', '.porto-hotspot.porto-hs-owl', function() {
        var $this = $(this),
            options = $this.data('hs-owl');
        $( options['selector'] ).trigger( 'to.owl.carousel', [options['order'], 300, true] );
    } );
} )( jQuery );