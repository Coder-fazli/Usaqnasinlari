/**
 * Alpus Plugin Framework JS
 *
 * @author Alpustheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */

'use strict';
window.alpusPlugin || ( window.alpusPlugin = {} );

( function( $ ) {
    /**
     * jQuery Window Object
     * 
     * @var {jQuery} $window
     * @since 1.0
     */
    alpusPlugin.$window = $( window );
    // Body has been loaded.
    alpusPlugin.$body = $( document.body );

    /**
     * jQuery Body Object
     * 
     * @var {jQuery} $body
     * @since 1.0
     */
    alpusPlugin.$body;

    alpusPlugin.defaults = {
        slider: {
            a11y: false,
            containerModifierClass: 'slider-container-', // NEW
            slideClass: 'slider-slide',
            wrapperClass: 'slider-wrapper',
            slideActiveClass: 'slider-slide-active',
            slideDuplicateClass: 'slider-slide-duplicate',
        },
        popupPresets: {
            video: {
                type: 'iframe',
                mainClass: "mfp-fade",
                preloader: false,
                closeBtnInside: false
            }
        }
    };

    /**
     * Get DOM elements by className
     * 
     * @since 1.0
     * @param {string} className Class name to find
     * @param {HTMLElement} root Root elements
     * @return {HTMLCollection}  Matched elements
     */
    alpusPlugin.byClass = function( className, root ) {
        return root ? root.getElementsByClassName( className ) : document.getElementsByClassName( className );
    }

    /**
     * Get jQuery object
     * 
     * @since 1.0
     * @param {string|jQuery} selector	Selector to find
     * @param {string|jQuery} find		Find from selector root
     * @return {jQuery|Object}			jQuery Object or {each: $.noop}
     */
    alpusPlugin.$ = function( selector, find ) {
        if ( typeof selector == 'string' && typeof find == 'string' ) {
            return $( selector + ' ' + find );
        }
        if ( selector instanceof jQuery ) {
            if ( selector.is( find ) ) {
                return selector;
            }
            if ( typeof find == 'undefined' ) {
                return selector;
            }
            return selector.find( find );
        }
        if ( typeof selector == 'undefined' || !selector ) {
            return $( find );
        }
        if ( typeof find == 'undefined' ) {
            return $( selector );
        }
        return $( selector ).find( find );
    }

    /**
     * Request timeout by using requestAnimationFrame
     * 
     * @since 1.0
     * @param {function} fn
     * @param {number} delay
     * @return {Object} handle
     */
    alpusPlugin.requestTimeout = function( fn, delay ) {
        var handler = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
        if ( !handler ) {
            return setTimeout( fn, delay );
        }
        delay || ( delay = 0 );
        var start, rt = new Object();

        function loop( timestamp ) {
            if ( !start ) {
                start = timestamp;
            }
            var progress = timestamp - start;
            progress >= delay ? fn() : rt.val = handler( loop );
        }

        rt.val = handler( loop );
        return rt;
    }

    /**
     * Parse options string to object
     * 
     * @since 1.0
     * @param {string} options	Options string
     * @return {object}
     */
    alpusPlugin.parseOptions = function( options ) {
        return 'string' == typeof options ? JSON.parse( options.replace( /'/g, '"' ).replace( ';', '' ) ) : {};
    }

    /**
     * Open magnific popup
     *
     * @since 1.0
     * @param {Object} options
     * @param {string} preset
     * @return {void}
     */
    alpusPlugin.popup = function( options, preset ) {
        if ( undefined == typeof $.magnificPopup.instance ) {
            return;
        }
        var mpInstance = $.magnificPopup.instance;
        // if something is already opened, retry after 5seconds
        if ( mpInstance.isOpen ) {
            if ( mpInstance.content ) {
                setTimeout( function() {
                    alpusPlugin.popup( options, preset );
                }, 5000 );
            } else {
                $.magnificPopup.close();
            }
        } else {
            // if nothing is opened, open new
            $.magnificPopup.open(
                $.extend( true, {},
                    alpusPlugin.defaults.popup,
                    preset ? alpusPlugin.defaults.popupPresets[preset] : {},
                    options
                )
            );
        }
    }

    alpusPlugin.initNotifications = function() {
        // $( '#wpbody-content .wrap:not(.alpus-plugin-notices)' ).prependTo( $( '#wpbody-content .templates-builder' ) );
        $( 'div.updated, div.error, div.notice' ).not( '.inline, .below-h2' ).appendTo( $( '.alpus-plugin-notices' ) );
    }

    /**
     * Init Dependency Controls
     * 
     * @since 1.0
     */
    alpusPlugin.initDependency = function() {
        var $form = $( '#pluginform' ),
            $conditions = $form.find( '.display-condition' );

        $conditions.each( function() {
            var $this = $( this ),
                condition_option = $this.data( 'condition-option' ),
                condition_operator = $this.data( 'condition-operator' ),
                condition_value = $this.data( 'condition-value' ),
                condition_id = $this.data( 'condition-id' ),
                $condition_control = $form.find( '#' + condition_option ),
                conditions = [],
                value;

            if ( $condition_control.data( 'conditions' ) ) {
                conditions = $condition_control.data( 'conditions' );
            }

            conditions.push( {
                operator: condition_operator,
                value: condition_value,
                target: $this.find( '#' + condition_id ),
                wrapper: $this,
            } );

            $condition_control.addClass( 'condition-trigger' );
            $condition_control.data( 'conditions', conditions );

            value = $condition_control.val();
            if ( $condition_control.attr( 'type' ) == 'checkbox' ) {
                value = $condition_control.is( ':checked' );
            }
            if ( '==' == condition_operator ) {
                // Array
                if ( Array.isArray( condition_value ) ) {
                    if ( -1 != condition_value.indexOf( value ) ) {
                        $this.addClass( 'show' );
                    } else {
                        $this.removeClass( 'show' );
                    }
                } else {
                    if ( condition_value == value ) {
                        $this.addClass( 'show' );
                    } else {
                        $this.removeClass( 'show' );
                    }
                }
            } else if ( '!=' == condition_operator ) {
                // Array
                if ( Array.isArray( condition_value ) ) {
                    if ( -1 == condition_value.indexOf( value ) ) {
                        $this.addClass( 'show' );
                    } else {
                        $this.removeClass( 'show' );
                    }
                } else {
                    if ( condition_value != value ) {
                        $this.addClass( 'show' );
                    } else {
                        $this.removeClass( 'show' );
                    }
                }
            }
        } );

        $form.find( '.condition-trigger' ).on( 'change', function() {
            var $this = $( this ),
                conditions = $this.data( 'conditions' ),
                value = $this.val();
            if ( $this.attr( 'type' ) == 'checkbox' ) {
                value = $this.is( ':checked' );
            }
            if ( Array.isArray( conditions ) && conditions.length > 0 ) {
                conditions.forEach( function( condition, index ) {
                    if ( '==' == condition.operator ) {
                        // Array
                        if ( Array.isArray( condition.value ) ) {
                            if ( -1 != condition.value.indexOf( value ) ) {
                                condition.wrapper.addClass( 'show' );
                            } else {
                                condition.wrapper.removeClass( 'show' );
                            }
                        } else {
                            if ( condition.value == value ) {
                                condition.wrapper.addClass( 'show' );
                            } else {
                                condition.wrapper.removeClass( 'show' );
                            }
                        }
                    } else if ( '!=' == condition.operator ) {
                        // Array
                        if ( Array.isArray( condition.value ) ) {
                            if ( -1 == condition.value.indexOf( value ) ) {
                                condition.wrapper.addClass( 'show' );
                            } else {
                                condition.wrapper.removeClass( 'show' );
                            }
                        } else {
                            if ( condition.value != value ) {
                                condition.wrapper.addClass( 'show' );
                            } else {
                                condition.wrapper.removeClass( 'show' );
                            }
                        }
                    }
                } );
            }
        } );
    }

    /**
     * Show loading overlay
     * 
     * @since 1.0
     * @param {string|jQuery} selector 
     * @param {string} type
     * @return {void}
     */
    alpusPlugin.doLoading = function( selector, type ) {
        var $selector = alpusPlugin.$( selector );
        if ( typeof type == 'undefined' ) {
            $selector.append( '<div class="d-loading"><i></i></div>' );
        } else if ( type == 'small' ) {
            $selector.append( '<div class="d-loading small"><i></i></div>' );
        } else if ( type == 'simple' ) {
            $selector.append( '<div class="d-loading small"></div>' );
        }

        if ( 'static' == $selector.css( 'position' ) ) {
            alpusPlugin.$( selector ).css( 'position', 'relative' );
        }
    }

    /**
     * Hide loading overlay
     * 
     * @since 1.0
     * @param {string|jQuery} selector
     * @return {void}
     */
    alpusPlugin.endLoading = function( selector ) {
        alpusPlugin.$( selector ).find( '.d-loading' ).remove();
        alpusPlugin.$( selector ).css( 'position', '' );
    }

    /**
     * Prepare Functions
     * 
     * @since 1.0
     */
    alpusPlugin.prepare = function() {

    }

    /**
     * Init Functions
     * 
     * @since 1.0
     */
    alpusPlugin.init = function() {

    }

    // Run prepare functions
    alpusPlugin.prepare();

    // Window Load Event
    $( window ).on( 'load', function() {
        alpusPlugin.init();
    } );
} )( jQuery );
