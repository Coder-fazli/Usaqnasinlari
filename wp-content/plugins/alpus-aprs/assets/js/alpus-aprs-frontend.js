/**
 * Alpus APRS Frontend JS
 * 
 * @since 1.0.0
 */
( function($) {
    var loadSummary = function () {
        var $wrapper = $('.alpus-aprs-wrapper'),
            $content = $wrapper.find('.alpus-aprs-content'),
            $error   = $wrapper.find('.alpus-aprs-error-msg'),
            post_id  = $wrapper.data('post-id');

        $wrapper.removeClass('hide');

        if ( $wrapper.length > 0 ) {
            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_apr_get_summary',
                    nonce: alpus_aprs_frontend_vars.nonce,
                    post_id: post_id
                },
                url: alpus_aprs_frontend_vars.ajax_url,
                success: function ( res ) {
                    $wrapper.find('.loading-overlay').remove();
                    $wrapper.removeClass('loading');
                    if ( res.success ) {
    
                        $content.html(res.data)
                            .css('display', 'block');
                    } else {
                        if ( res.data ) {
                            $error.html(wp.i18n.__('Failure, Please try again after refresh page.', 'alpus-aprs') + '<br/>' + res.data);
                        } else {
                            $error.html(wp.i18n.__('Failure, Please try again after refresh page.', 'alpus-aprs'));
                        }
                        
                        $error.addClass('show');
                        $content.css('display', 'none');
                    }
                }
            });
        }
    }

    var initFilter = function() {
        $('body').on('click', '.alpus-aprs-shop-filter', function(e) {
            let $this        = $(this),
                key          = $this.find('a').attr('href').slice(1),
                search       = window.location.search,
                params       = new URLSearchParams( search ),
                searchParams = Array(),
                index        = -1;

            if ( undefined != params.get('alpus_aprs') ) {
                searchParams = decodeURIComponent( params.get('alpus_aprs') ).split(',');
                index = searchParams.indexOf( key );
            }

            if ( -1 == index ) {
                searchParams.push( key );
            } else {
                searchParams.splice(index,1);
            }

            if ( searchParams.length > 0 ) {
                params.set('alpus_aprs', encodeURIComponent( searchParams.join(",") ) );
            } else {
                params.delete('alpus_aprs');
            }
            
            window.location.search = params.toString(); 

            e.preventDefault();
        });

        $('body').on('click', '.alpus-aprs-shop-filters-clear', function(e) {
            let search = window.location.search,
                params = new URLSearchParams( search );

            params.delete('alpus_aprs');
            
            window.location.search = params.toString(); 
            e.preventDefault();
        });
    }

    $(document).ready(function() {
        loadSummary();
        initFilter();
    });
} ) (jQuery);
