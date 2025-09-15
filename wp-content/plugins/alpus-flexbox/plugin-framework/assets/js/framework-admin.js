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

    alpusPlugin.initNotifications = function() {
        $( '#wpbody-content .wrap:not(.alpus-plugin-notices)' ).prependTo( $( '#wpbody-content .templates-builder' ) );
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

            if ( condition_value == 'yes' ) {
                condition_value = true;
            } else if ( condition_value == 'no' ) {
                condition_value = false;
            }

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
     * Prepare Functions
     * 
     * @since 1.0
     */
    alpusPlugin.prepare = function() {
        alpusPlugin.initNotifications();
    }

    /**
     * Manage Plugins
     * 
     * @since 1.0
     */
    alpusPlugin.managePlugin = function() {
        
        var $installBtn = '';
        var plugin ='';
        var hashBefore = '';
        // Install Plugin
		function ajaxCallback( response ) {
            if ( typeof response == 'object' && typeof response.message != 'undefined' ) {
                $installBtn.text( response.message );

				if ( typeof response.url != 'undefined' ) {
					// we have an ajax url action to perform.

					if ( response.hash == hashBefore ) {
						$installBtn.text( alpus_plugin_framework_vars.texts.failed );
						afterAjaxCallback({});
					} else {
						hashBefore = response.hash;
						$.post( response.url + '&activate-multi=true', response, function ( response2 ) {
							installPlugin();
							$installBtn.text( response.message );
						} ).fail( ajaxCallback );
					}

				} else if ( typeof response.done != 'undefined' ) {
					afterAjaxCallback(response);
				} else {
					afterAjaxCallback(response);
				}
            } else {
                afterAjaxCallback(response);
            }
        }
		function installPlugin() {
            $.post( alpus_plugin_framework_vars.ajax_url, {
                action: 'alpus_install_plugin',
                nonce: alpus_plugin_framework_vars.nonce,
                plugin: plugin,
            }, ajaxCallback );
		}
		function afterAjaxCallback(response) {
            if ( typeof response.message != 'undefined' ) {
                $installBtn.text( response.message );
            } else if ( typeof response == 'string' ) {
                $installBtn.text( response );
            }

            if ( typeof response.done != 'undefined' && response.done == 1 || response == 'success' ) {
                $installBtn.closest('.plugin-card').find('.alpus_plugin_status').prop('checked', true);
            }

            setTimeout( function () {
                $installBtn.closest('.action-links').removeClass('disabled');
                $installBtn.closest('.plugin-card').find('.alpus_plugin_status').prop('disabled', false);
                $installBtn.closest('.plugin-card').removeClass('installing');
                $('.alpus-plugin-install-button').css( 'pointer-events', 'auto' );
            }, 1000 );
		}
        $( document ).on( 'click', '.alpus-plugin-install-button', function( event ) {
            event.preventDefault();
            $installBtn = $( this );
            plugin = $installBtn.data( 'plugin' );
            $installBtn.closest('.plugin-card').addClass('installing');
            $('.alpus-plugin-install-button').css( 'pointer-events', 'none' );
            installPlugin();
        } );

        // Active / Deactive
        $( document ).on( 'change', '.alpus_plugin_status', function( event ) {
            var $this = $( this ),
                plugin = $this.data( 'url' );
            $this.closest( '#wpcontent' ).css( 'pointer-events', 'none' );
            $.post( alpus_plugin_framework_vars.ajax_url, {
                action: 'alpus_manage_plugin',
                nonce: alpus_plugin_framework_vars.nonce,
                plugin: plugin,
                status: $this.is( ':checked' )
            }, function( response ) {
                if ( 'success' == response ) {
                    if ( true == $this.is( ':checked' ) ) {
                        alpus_plugin_framework_vars.active_plugins++;
                    } else {
                        alpus_plugin_framework_vars.active_plugins--;
                    }

                    if ( !alpus_plugin_framework_vars.active_plugins && !alpus_plugin_framework_vars.core_plugin ) {
                        window.location.href = alpus_plugin_framework_vars.admin_url;
                    } else {
                        window.location.reload();
                    }
                } else {
                }

            } ).fail( function( response ) {
            } );
        } );
    }

    alpusPlugin.initWpColorPicker = function() {
        if ( $.fn.wpColorPicker ) {
            $( 'input.alpus-color-picker:not(.wp-color-picker)' ).wpColorPicker();
        }
    }

    /* Upload */
    alpusPlugin.uploadOption = {
        selectors: {
            imgPreview: '.alpus-upload-img-preview',
            uploadButton: '.alpus-upload-button',
            imgUrl: '.alpus-upload-img-url',
            resetButton: '.alpus-upload-button-reset'
        },
        onImageChange: function() {
            var url = $( this ).val(),
                imageRegex = new RegExp( "(http|ftp|https)://[a-zA-Z0-9@?^=%&amp;:/~+#-_.]*.(gif|jpg|jpeg|png|ico|svg)" ),
                preview = $( this ).parent().find( alpusPlugin.uploadOption.selectors.imgPreview ).first();

            if ( preview.length < 1 ) {
                preview = $( this ).parent().parent().find( alpusPlugin.uploadOption.selectors.imgPreview ).first();
            }

            if ( imageRegex.test( url ) ) {
                preview.html( '<img src="' + url + '" />' );
            } else {
                preview.html( '' );
            }
        },
        onButtonClick: function( e ) {
            e.preventDefault();

            var button = $( this ),
                custom_uploader,
                id = button.attr( 'id' ).replace( /-button$/, '' ).replace( /(\[|\])/g, '\\$1' );

            // If the uploader object has already been created, reopen the dialog
            if ( custom_uploader ) {
                custom_uploader.open();
                return;
            }

            var custom_uploader_states = [
                new wp.media.controller.Library(
                    {
                        library: wp.media.query(),
                        multiple: false,
                        title: 'Choose Image',
                        priority: 20,
                        filterable: 'uploaded'
                    }
                )
            ];

            // Create the media frame.
            custom_uploader = wp.media.frames.downloadable_file = wp.media(
                {
                    // Set the title of the modal.
                    title: 'Choose Image',
                    library: {
                        type: ''
                    },
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false,
                    states: custom_uploader_states
                }
            );

            // When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on( 'select', function() {
                var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
                $( "#" + id ).val( attachment.url );
                alpusPlugin.uploadOption.triggerImageChange();
            } );

            custom_uploader.open();
        },
        onResetClick: function() {
            var button = $( this ),
                id = button.attr( 'id' ).replace( /(\[|\])/g, '\\$1' ),
                input_id = button.attr( 'id' ).replace( /-button-reset$/, '' ).replace( /(\[|\])/g, '\\$1' ),
                default_value = $( '#' + id ).data( 'default' );

            $( "#" + input_id ).val( default_value );
            alpusPlugin.uploadOption.triggerImageChange();
        },
        triggerImageChange: function() {
            $( alpusPlugin.uploadOption.selectors.imgUrl ).trigger( 'change' );
        },
        init: function() {
            if ( typeof wp !== 'undefined' && typeof wp.media !== 'undefined' ) {
                $( document ).on( 'change', alpusPlugin.uploadOption.selectors.imgUrl, alpusPlugin.uploadOption.onImageChange );

                $( document ).on( 'click', alpusPlugin.uploadOption.selectors.uploadButton, alpusPlugin.uploadOption.onButtonClick );

                $( document ).on( 'click', alpusPlugin.uploadOption.selectors.resetButton, alpusPlugin.uploadOption.onResetClick );
            }
        }
    };


    alpusPlugin.initUISlider = function() {
        if ( $( '.wp-slider' ).length ) {
            $( '.wp-slider' ).each( function() {
                var options = $( this ).data( 'range' );
                $( this ).slider( {
                    animate: 'fast',
                    min: options.min,
                    max: options.max,
                    step: options.step,
                    value: options.value
                } );

            } )
            $( '.wp-slider' ).on( 'slide', function( e, ui ) {
                var $slider = $( this ),
                    $input = $slider.siblings( 'input' );

                $input.val( ui.value );
            } )
            $( '.wp-slider-wrapper input' ).on( 'input', function() {
                var $input = $( this ),
                    $slider = $input.siblings( '.wp-slider' );
                $slider.slider( 'option', { 'value': $input.val() } );
            } )
        }
    }

    alpusPlugin.initButtonSet = function() {
        $( '.alpus-set-item' ).on( 'click', function() {
            var $button = $( this ),
                $value = $button.attr( 'data-value' );
            $button.addClass('active').siblings().removeClass('active');
            $button.parent().next('input').val($value);
            $button.parent().next('input').trigger('change');
        } )
    }

    alpusPlugin.initAdminTab = function() {
        $( '.alpus-plugin-options.use-tab nav a' ).on( 'click', function( e ) {
            e.preventDefault();
            let $this = $( this );
            let slug = $this.attr( 'data-tab' );
            $this.siblings().removeClass( 'nav-tab-active' );
            $this.addClass( 'nav-tab-active' );
            $( '.alpus-plugin-options' ).find( '.tab-content.active' ).removeClass( 'active' );
            $( '.alpus-plugin-options' ).find( '.tab-content.' + slug ).addClass( 'active' );
        } );
    }

    /** Repeater **/
    $.fn.saveRepeaterElement = function( spinner ) {
        var repeater = $( this ),
            action = 'alpus_save_repeater_element',
            formdata = repeater.serializeRepeaterElement(),
            wrapper = repeater.find( '.alpus-repeater-wrapper' ),
            id = wrapper.attr( 'id' ),
            current_tab = $.urlParam( 'tab' ),
            url = '';

        formdata.append( 'security', wrapper.data( 'nonce' ) );

        url = alpus_plugin_framework_vars.admin_url + 'admin.php' + '?action=' + action + '&tab=' + current_tab + '&repeater_id=' + id + '&page=' + $.urlParam( 'page' );

        $.ajax( {
            type: "POST",
            url: url,
            data: formdata,
            contentType: false,
            processData: false,
            success: function( result ) {
                if ( spinner ) {
                    spinner.removeClass( 'show' );
                }
            }
        } );
    };

    $.fn.serializeRepeaterElement = function() {
        var obj = $( this );
        var formData = new FormData();
        var params = $( obj ).find( ":input" ).serializeArray();
        $.each( params, function( i, val ) {
            var el_name = val.name;
            formData.append( val.name, val.value );
        } );
        return formData;
    };

    $.fn.formatRepeaterTitle = function() {
        var repeater_el = $( this ),
            fields = repeater_el.find( ':input' ),
            title = repeater_el.find( 'span.title' ).data( 'title_format' ),
            subtitle = repeater_el.find( '.subtitle' ).data( 'subtitle_format' ),
            regExp = new RegExp( "[^%%]+(?=[%%])", 'g' );

        if ( typeof title != 'undefined' ) {
            var res = title.match( regExp );
        }

        if ( typeof subtitle != 'undefined' ) {
            var ressub = subtitle.match( regExp );
        }

        $.each( fields, function( i, field ) {
            if ( typeof $( field ).attr( 'id' ) != 'undefined' ) {
                var $field_id = $( field ).attr( 'id' );
                var $field_array = $field_id.split( '_' );
                $field_array.pop();
                $field_id = $field_array.join( '_' );
                var $field_val = $( field ).val();

                if ( res != null && typeof res != 'undefined' && res.indexOf( $field_id ) !== -1 ) {
                    title = title.replace( '%%' + $field_id + '%%', $field_val );
                }
                if ( ressub != null && typeof ressub != 'undefined' && ressub.indexOf( $field_id ) !== -1 ) {
                    subtitle = subtitle.replace( '%%' + $field_id + '%%', $field_val );
                }
            }
        } );

        if ( '' !== title ) {
            repeater_el.find( 'span.title' ).html( title );
        }

        if ( '' !== subtitle ) {
            repeater_el.find( '.subtitle' ).html( subtitle );
        }

        $( document ).trigger( 'alpus-repeater-element-item-title', [repeater_el] );
    };

    $.urlParam = function( name ) {
        var results = new RegExp( '[\?&]' + name + '=([^&#]*)' )
            .exec( window.location.search );

        return ( results !== null ) ? results[1] || 0 : false;
    };

    $( document ).on( 'click', '.alpus-repeater-title', function( event ) {
        var _repeater = $( event.target ),
            _section = _repeater.closest( '.alpus-repeater-row' ),
            _content = _section.find( '.alpus-repeater-content' );

        if ( _repeater.hasClass( 'alpus-onoff' ) || _repeater.hasClass( 'alpus-icon-drag' ) ) {
            return false;
        }

        if ( _section.is( '.alpus-repeater-row-opened' ) ) {
            _content.slideUp( 400 );
        } else {
            _content.slideDown( 400 );
        }
        _section.toggleClass( 'alpus-repeater-row-opened' );
    } );

    $( document ).on( 'click', '.alpus-add-box-button', function( event ) {
        event.preventDefault();
        var $this = $( this ),
            target_id = $this.data( 'box_id' ),
            closed_label = $this.data( 'closed_label' ),
            label = $this.data( 'opened_label' ),
            id = $this.closest( '.alpus-repeater-wrapper' ).attr( 'id' ),
            template = wp.template( 'alpus-repeater-element-add-box-content-' + id );

        if ( '' !== target_id ) {
            $( '#' + target_id ).html( template( { index: 'box_id' } ) ).slideToggle();
            if ( closed_label !== '' ) {
                if ( $this.html() === closed_label ) {
                    $this.html( label ).removeClass( 'closed' );
                } else {
                    $this.html( closed_label ).addClass( 'closed' );
                }
            }
            $( document ).trigger( 'alpus-add-box-button-repeater', [$this] );
        }
    } );

    $( document ).on( 'click', '.alpus-add-box-buttons .alpus-save-button', function( event ) {

        event.preventDefault();
        var add_box = $( this ).parents( '.alpus-add-box' ),
            id = $( this ).closest( '.alpus-repeater-wrapper' ).attr( 'id' ),
            spinner = add_box.find( '.spinner' ),
            repeater_element = $( this ).parents( '.repeater-element' ),
            fields = add_box.find( ':input' ),
            counter = 0,
            hidden_obj = $( '<input type="hidden">' );

        repeater_element.find( '.alpus-repeater-row' ).each( function() {
            var key = parseInt( $( this ).data( 'item_key' ) );
            if ( counter <= key ) {
                counter = key + 1;
            }
        } );

        hidden_obj.val( counter );


        counter = hidden_obj.val();
        var template = wp.template( 'alpus-repeater-element-item-' + id ),
            repeater_el = $( template( { index: counter } ) );

        spinner.addClass( 'show' );

        $.each( fields, function( i, field ) {
            if ( typeof $( field ).attr( 'id' ) !== 'undefined' ) {

                var _field_id = $( field ).attr( 'id' ),
                    _field_val = $( field ).val();

                if ( 'radio' === $( field ).attr( 'type' ) ) {
                    _field_id = $( field ).closest( '.alpus-radio' ).attr( 'id' );
                    _field_id = _field_id.replace( 'new_', '' ) + '_' + counter;
                    _field_id = _field_id + '-' + _field_val;
                } else {
                    _field_id = _field_id.replace( 'new_', '' ) + '_' + counter;
                }

                if ( $( field ).is( ':checked' ) ) {
                    $( repeater_el ).find( '#' + _field_id ).prop( 'checked', true );
                }

                if ( $( field ).hasClass( 'alpus-post-search' ) || $( field ).hasClass( 'alpus-term-search' ) ) {
                    $( repeater_el ).find( '#' + _field_id ).html( $( '#' + $( field ).attr( 'id' ) ).html() );
                }

                $( repeater_el ).find( '#' + _field_id ).val( _field_val );

            }

        } );

        $( repeater_el ).formatRepeaterTitle();
        var form_is_valid = $( '<input type="hidden">' ).val( 'yes' );
        $( document ).trigger( 'alpus-repeater-element-item-before-add', [add_box, repeater_el, form_is_valid] );

        var delayInMilliseconds = 1000; //1 second
        setTimeout( function() {
            if ( form_is_valid.val() === 'yes' ) {
                $( repeater_element ).find( '.alpus-repeater-elements' ).append( repeater_el );
                // $( add_box ).find( '.alpus-datepicker' ).datepicker( 'destroy' );
                $( add_box ).html( '' );
                $( add_box ).prev( '.alpus-add-box-button' ).trigger( 'click' );
                repeater_element.saveRepeaterElement();

                var delayInMilliseconds = 2000; //1 second
                setTimeout( function() {
                    $( repeater_element ).find( '.highlight' ).removeClass( 'highlight' );
                }, delayInMilliseconds );

            }
        }, delayInMilliseconds );
    } );

    $( document ).on( 'click', '.alpus-repeater-row .alpus-save-button', function( event ) {
        event.preventDefault();
        var repeater = $( this ).closest( '.repeater-element' ),
            repeater_row = $( this ).closest( '.alpus-repeater-row' ),
            spinner = repeater_row.find( '.spinner' );
        repeater_row.formatRepeaterTitle();

        var form_is_valid = $( '<input type="hidden">' ).val( 'yes' );
        $( document ).trigger( 'alpus-repeater-element-item-before-update', [repeater, repeater_row, form_is_valid] );
        if ( form_is_valid.val() === 'yes' ) {
            spinner.addClass( 'show' );
            repeater.saveRepeaterElement( spinner );
        }
    } );

    $( document ).on( 'click', '.alpus-repeater-row .alpus-delete-button', function( event ) {
        event.preventDefault();
        var repeater = $( this ).closest( '.repeater-element' ),
            repeater_row = $( this ).closest( '.alpus-repeater-row' );
        repeater_row.remove();
        repeater.saveRepeaterElement();
    } );

    /**
     * Init Functions
     * 
     * @since 1.0
     */
    alpusPlugin.init = function() {
        alpusPlugin.initDependency();       // Initialize dependency of controls
        alpusPlugin.initWpColorPicker();       // Initialize color picker controls
        alpusPlugin.uploadOption.init(); // Initialize upload option
        alpusPlugin.initUISlider();       // Initialize slider controls
        alpusPlugin.initButtonSet();       // Initialize button set controls
        alpusPlugin.initAdminTab();
        alpusPlugin.managePlugin();
    }

    // Run prepare functions
    // alpusPlugin.prepare();

    // Window Load Event
    $( window ).on( 'load', function() {
        alpusPlugin.init();
    } );
} )( jQuery );
