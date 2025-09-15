/**
 * Alpus APRS Backend JS
 * 
 * @since 1.0.0
 */
( function($) {
    $(document).ready(function() {
        $('body').on('click', '.alpus-aprs-clear-all .alpus-plugin-action-button', function(e) {
            // Check Background Generating Running.
            if ( $('.alpus-aprs-generate-all .alpus-plugin-action-button.disabled').length > 0 ) {
                alpus_swal( wp.i18n.__('Oops!'),  wp.i18n.__('"Generate all summaries" functionality is still running!') ,  "error" );
            } else {
                var $this = $(this);
    
                $this.append('<span class="alpus-aprs-loading small"><i></i></span>')
                    .addClass('loading');
    
                $.ajax({
                    type: 'post',
                    data: {
                        action: 'alpus_aprs_clear_cache'
                    },
                    url: alpus_aprs_backend_vars.ajax_url,
                    success: function ( res ) {
                        if ( res.success ) {
                            $this.removeClass('loading')
                                .find('span').remove();
                        }
                    }
                });
            }

            e.preventDefault();
        });

        $('body').on('click', '.alpus-aprs-generate-all .alpus-plugin-action-button:not(.disabled)', function (e) {
            var $this = $(this);

            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_aprs_generate_all'
                },
                url: alpus_aprs_backend_vars.ajax_url,
                success: function ( res ) {
                    location.reload();
                }
            });

            e.preventDefault();
        });

        // Stop Background Running
        $('body').on('click', '.alpus-aprs-generate-all .alpus-aprs-bg-generating-stop', function (e) {
            var $this = $(this);

            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_aprs_generate_stop'
                },
                url: alpus_aprs_backend_vars.ajax_url,
                success: function ( res ) {
                    if ( 'stopped' == res.data ) {
                        location.reload();
                    }
                }
            });

            e.preventDefault();
        });

        // Check if background generate is running.
        if ( $('.alpus-aprs-generate-all .alpus-plugin-action-button').length > 0 ) {
            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_aprs_is_generating'
                },
                url: alpus_aprs_backend_vars.ajax_url,
                success: function ( res ) {
                    if ('false' !== res.data) {
                        let $button = $('.alpus-aprs-generate-all .alpus-plugin-action-button').html( wp.i18n.__( 'Running - ' ) + res.data + wp.i18n.__( ' Products Generated.' ) );
    
                        $button.addClass('disabled');

                        $( '<button class="button-primary alpus-aprs-bg-generating-stop">' + wp.i18n.__( 'Stop Generating' ) + '</button>').insertAfter( $button );
                    }
                }
            });
        }
    });
} ) (jQuery);
