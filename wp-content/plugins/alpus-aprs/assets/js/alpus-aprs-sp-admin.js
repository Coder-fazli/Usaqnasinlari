/**
 * Alpus APRS Single Product Admin JS
 * 
 * @since 1.1.0
 * @since 2.0.0 - Added - AI Consultant Officer
 */
( function($) {
    var initSummaryPopup = function() {
        $('body').on('click', '.alpus-aprs-sp-summary-view', function(e) {
            var postID = $('#post_ID').val(),
                $popup = $('.alpus-aprs-sp-summarize-popup'),
                $this = $(this),
                btnText = $this.html();

            if ( undefined == window.alpus_aprs_sp_summary_loaded ) {
                $this.html($this.data('loading-text'));

                $.ajax({
                    type: 'post',
                    data: {
                        action: 'alpus_aprs_sp_summary',
                        id: postID
                    },
                    url: alpus_aprs_sp_admin_vars.ajax_url,
                    success: function ( res ) {
                        if ( res.success ) {
                            // Update current status.
                            if ( res.data.summary ) {
                                $popup.find('.alpus-aprs-sp-summary p').html(res.data.summary);
                            }
    
                            if ( res.data.pros ) {
                                $popup.find('.alpus-aprs-sp-pros p').html(res.data.pros);
                            }
    
                            if ( res.data.cons ) {
                                $popup.find('.alpus-aprs-sp-cons p').html(res.data.cons);
                            }
                            // Show Popup
                            $popup.addClass('show');
                            $this.html(btnText);

                            window.alpus_aprs_sp_summary_loaded = true;
                        }
                    }
                });
            } else {                
                $popup.addClass('show');
            }


            e.preventDefault();
        });

        $('body').on('click', '.alpus-aprs-sp-ai-advice-view', function(e) {
            var postID = $('#post_ID').val(),
                $popup = $('.alpus-aprs-sp-ai-advice-popup'),
                $this = $(this),
                btnText = $this.html();

            if ( undefined == window.alpus_aprs_sp_ai_advice_loaded ) {
                $this.html($this.data('loading-text'));

                $.ajax({
                    type: 'post',
                    data: {
                        action: 'alpus_aprs_sp_ai_advice',
                        id: postID
                    },
                    url: alpus_aprs_sp_admin_vars.ajax_url,
                    success: function ( res ) {
                        if ( res.success ) {                           
                            console.log( res.data );

                            $popup.find('.alpus-aprs-sp-ai-advice.general').html( res.data );

                            // Show Popup
                            $popup.addClass('show');
                            $this.html(btnText);

                            window.alpus_aprs_sp_ai_advice_loaded = true;
                        }
                    }
                });
            } else {                
                $popup.addClass('show');
            }


            e.preventDefault();
        });

        // Popup Close
        $('body').on('click', '.alpus-aprs-popup-close', function(e) {
            var $popup = $('.alpus-aprs-sp-summarize-popup, .alpus-aprs-sp-ai-advice-popup.show');

            $popup.removeClass('show');

            e.preventDefault();
        });
    }

    var initTab = function() {
        $('body').on('click', '.alpus-aprs-ai-question', function(e) {
            var postID = $('#post_ID').val(),
                $this = $(this),
                target = $this.find('a').attr('href').slice(1),
                $body = $this.closest('.alpus-aprs-popup-wrapper').find('.alpus-aprs-sp-ai-advice.' + target );
                $loading = $body.siblings('.loading');

            $this.addClass('active')
                .siblings()
                .removeClass('active');

            $body.addClass('show')
                .siblings()
                .removeClass('show');

            if ( undefined == window.alpus_aprs_sp_advice ) {
                window.alpus_aprs_sp_advice = {};
            }

            $loading.css('display', '');

            if ( undefined == window.alpus_aprs_sp_advice[ target ] && 'chat' !== target && 'general' !== target ) {
                $loading.css('display', 'block');
                // Get consultant data from AI.
                $.ajax({
                    type: 'post',
                    data: {
                        action: 'alpus_aprs_sp_ai_detail',
                        none: alpus_aprs_sp_admin_vars.nonce,
                        type: target,
                        id: postID
                    },
                    url: alpus_aprs_sp_admin_vars.ajax_url,
                    success: function ( res ) {
    
                        if ( res.success ) {
                            $body.html( res.data );

                            window.alpus_aprs_sp_advice[ target ] = res.data;

                            $loading.css('display', '');
                        }
                    }
                });
            }

            e.preventDefault();
        });
    }

    var initChat = function() {
        $('body').on('click', '.alpus-aprs-ai-chat-send', function(e) {
            var $chatWrapper = $(this).closest('.alpus-aprs-ai-chat-wrapper'),
                $input = $chatWrapper.find('.alpus-aprs-ai-chat-input'),
                $list = $chatWrapper.find('.alpus-aprs-ai-chat-list'),
                query = $input.val();

            $list.append('<div class="alpus-aprs-ai-chat person">' + query + '</div>');

            $input.val('');
            $list.stop().animate( { scrollTop: $list[0].scrollHeight }, 200 );

            var $ai = $('<div class="alpus-aprs-ai-chat ai"><div class="loading" style="display: block;"><span></span><span></span><span></span></div></div>');

            $list.append( $ai );

            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_aprs_chat_query',
                    none: alpus_aprs_sp_admin_vars.nonce,
                    query: query,
                },
                url: alpus_aprs_sp_admin_vars.ajax_url,
                success: function ( res ) {

                    if ( res.success ) {
                        $ai.html(res.data);

                        $list.stop().animate( { scrollTop: $list[0].scrollHeight }, 300 );
                    }
                }
            });

            e.preventDefault();
        });
    }

    var init = function() {
        var postID = $('#post_ID').val(),
            $status = $('.alpus-aprs-sp-summarize-status');

        setTimeout( function() {
            $.ajax({
                type: 'post',
                data: {
                    action: 'alpus_aprs_sp_info',
                    none: alpus_aprs_sp_admin_vars.nonce,
                    id: postID
                },
                url: alpus_aprs_sp_admin_vars.ajax_url,
                success: function ( res ) {

                    if ( res.success ) {
                        // Update current status.
                        if ( res.data.current ) {
                            $status.find('.current').html(res.data.current);
                        }

                        if ( res.data.total ) {
                            $status.find('.total').html(res.data.total);
                        }

                        // Current Tags
                        if ( res.data.tags ) {
                            var tags = res.data.tags,
                                $tags = $('.alpus-aprs-sp-tags');

                            Object.entries(tags).forEach(([key, value]) => {
                                $tags.append('<li class="alpus-aprs-sp-tag">' + value + '</li>');
                            });
                        }
                    }
                }
            });
        },
        200 );
    }
    
    $(document).ready(function() {
        init();
        initSummaryPopup();
        initTab();
        initChat();
    });
} ) (jQuery);
