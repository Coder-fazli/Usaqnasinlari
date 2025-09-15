( function ( $ ) {
	$( document ).ready( function () {
		if ( typeof window.VcRowView != 'undefined' ) {
			window.VcRowView.prototype.convertRowColumns = function ( layout ) {
				var Shortcodes = vc.shortcodes;
				var layout_split = layout.toString().split( /_/ )
					, columns = Shortcodes.where( {
						parent_id: this.model.id
					} )
					, new_columns = []
					, new_layout = []
					, new_width = "";
				return _.each( layout_split, function ( new_column_params, i ) {
					var new_column, new_column_params;
					if ( new_column_params != 'flex1' && new_column_params != 'flexauto' ) {
						new_column_params = _.map( new_column_params.toString().split( "" ), function ( v, i ) {
							return parseInt( v, 10 );
						} );
						new_width = 3 < new_column_params.length ? new_column_params[ 0 ] + "" + new_column_params[ 1 ] + "/" + new_column_params[ 2 ] + new_column_params[ 3 ] : 2 < new_column_params.length ? new_column_params[ 0 ] + "/" + new_column_params[ 1 ] + new_column_params[ 2 ] : new_column_params[ 0 ] + "/" + new_column_params[ 1 ];
					}
					else {
						new_width = new_column_params.slice( 0, 4 ) + '-' + new_column_params.slice( 4 );
						new_column_params = [ new_column_params.slice( 0, 4 ), new_column_params.slice( 4 ) ];
					}
					new_layout.push( new_width ),
						new_column_params = _.extend( _.isUndefined( columns[ i ] ) ? {} : columns[ i ].get( "params" ), {
							width: new_width
						} ),
						vc.storage.lock(),
						new_column = Shortcodes.create( {
							shortcode: this.getChildTag(),
							params: new_column_params,
							parent_id: this.model.id
						} ),
						_.isObject( columns[ i ] ) && _.each( Shortcodes.where( {
							parent_id: columns[ i ].id
						} ), function ( shortcode ) {
							vc.storage.lock(),
								shortcode.save( {
									parent_id: new_column.id
								} ),
								vc.storage.lock(),
								shortcode.trigger( "change_parent_id" )
						} ),
						new_columns.push( new_column )
				}, this ),
					layout_split.length < columns.length && _.each( columns.slice( layout_split.length ), function ( column ) {
						_.each( Shortcodes.where( {
							parent_id: column.id
						} ), function ( shortcode ) {
							vc.storage.lock(),
								shortcode.save( {
									parent_id: _.last( new_columns ).id
								} ),
								vc.storage.lock(),
								shortcode.trigger( "change_parent_id" )
						} )
					} ),
					_.each( columns, function ( shortcode ) {
						vc.storage.lock(),
							shortcode.destroy()
					}, this ),
					this.model.save(),
					this.setActiveLayoutButton( "" + layout ),
					new_layout
			}
		}
		if ( typeof window.InlineShortcodeView_vc_row != 'undefined' ) {
			window.InlineShortcodeView_vc_row.prototype.convertToWidthsArray = function ( string ) {
				return _.map( string.split( /_/ ), function ( c ) {
					if ( c != 'flex1' && c != 'flexauto' ) {
						var w = c.split( "" );
						return w.splice( Math.floor( c.length / 2 ), 0, "/" ),
							w.join( "" )
					}
					else {
						return c.slice( 0, 4 ) + '/' + c.slice( 4 );
					}
				} )
			}
		}
		if ( typeof window.InlineShortcodeView_vc_column != 'undefined' ) {
			window.InlineShortcodeView_vc_column.prototype.setColumnClasses = function () {

				var offset = this.getParam( "offset" ) || ""
					, width = this.getParam( "width" ) || "1/1"
					, $content = this.$el.find( "> .wpb_column" );
				if ( width.indexOf( 'flex' ) == -1 ) {
					this.css_class_width = this.convertSize( width ),
						this.css_class_width !== width && ( this.css_class_width = this.css_class_width.replace( /[^\d]/g, "" ) ),
						$content.removeClass( "vc_col-sm-" + this.css_class_width ),
						offset.match( /vc_col\-sm\-\d+/ ) || this.$el.addClass( "vc_col-sm-" + this.css_class_width ),
						vc.responsive_disabled && ( offset = offset.replace( /vc_col\-(lg|md|xs)[^\s]*/g, "" ) ),
						_.isEmpty( offset ) || ( $content.removeClass( offset ),
							this.$el.addClass( offset ) )
				}
				else {
					if ( width == 'flex/1' || width == 'flex-1' ) this.$el.addClass( 'wpb-flex-1' );
					else return this.$el.addClass( 'wpb-flex-auto' );
				}
			}
		}
		$( 'body' ).on( 'vcPanel.shown', '#vc_ui-panel-edit-element[data-vc-shortcode="porto_products"]', function() {
			orderAutoComplete();
			jQuery('.wpb_el_type_autocomplete[data-vc-shortcode-param-name="orderby"] input.autocomplete_field[name="orderby"]').data('vcParamObject').updateItems = function() {
				this.selected_items.length ? this.$input_param.val(this.getSelectedItems().join(", ")) : this.$input_param.val("")
				orderAutoComplete();
			}
		} );

		/**
		 * Colorpicker
		 * 
		 * @since 3.1.0
		 */
		if ( typeof vc != 'undefined' && vc.atts.colorpicker ) {
			vc.atts.colorpicker.init = function(param, $field) {
				$(".vc_color-control", $field).each(function() {
					var $alpha, $alpha_output, $control = $(this), value = $control.val().replace(/\s+/g, ""), alpha_val = 100;
					value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/) && (alpha_val = 100 * parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1])),
					$control.wpColorPicker({
						palettes: [ 'var(--porto-primary-color)', 'var(--porto-secondary-color)', 'var(--porto-tertiary-color)', 'var(--porto-quaternary-color)', 'var(--porto-dark-color)', 'var(--porto-light-color)', 'var(--porto-primary-light-5)' ],
						clear: function(event, ui) {
							$alpha.val(100),
							$alpha_output.val("100%")
						},
						change: _.debounce(function() {
							$(this).trigger("change")
						}, 500)
					}),
					value = $control.closest(".wp-picker-container"),
					value.find('input.wp-color-picker').removeClass('vc_ui-hidden'),
					$('<div class="vc_alpha-wrapper"><div class="vc_alpha-container"><label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label><input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="vc_alpha-field"></div></div>').appendTo(value.addClass("vc_color-picker").find(".iris-picker")),
					$alpha = value.find(".vc_alpha-field"),
					$alpha_output = value.find(".vc_alpha-container output"),
					$alpha.on("change keyup", function() {
						if ( $control.val().indexOf( 'var(--porto-' ) == -1 ) {
							var alpha_val = parseFloat($alpha.val())
							, iris = $control.data("a8c-iris")
							, color_picker = $control.data("wp-wpColorPicker");
						  $alpha_output.val($alpha.val() + "%"),
						  iris._color._alpha = alpha_val / 100,
						  $control.val(iris._color.toString()),
						  color_picker.toggler.css({
							  backgroundColor: $control.val()
						  })
						}
					}).val(alpha_val).trigger("change")
				})
			}
		}

		/***
		 * Auto complete for Posts Grid Widget
		 * 
		 * @since 6.11.0
		 */
		$( 'body' ).on( 'vcPanel.shown', '#vc_ui-panel-edit-element[data-vc-shortcode="porto_tb_posts"]', function() {
			var $panel = $( this ),
				$tax = $panel.find( '.wpb_vc_param_value.tax' );
				$term = $panel.find( '.vc_autocomplete-field .wpb_vc_param_value.terms' );
			if ( $term.length && $tax.length ) {
				$param = $term.data( 'vc-param-object' );
				$param.source_data = function () {
					return {
						'tax' : $tax.val()
					}
				}
			}

			var $post_ids = $panel.find( '.vc_autocomplete-field .wpb_vc_param_value.post_ids' ),
				$post_type = $panel.find( '.wpb_vc_param_value.post_type' );
			if ( $post_ids.length && $post_type.length ) {
				$param = $post_ids.data( 'vc-param-object' );
				$param.source_data = function () {
					return {
						'post_type' : $post_type.val()
					}
				}
			}
		} );

		var $panelEditElement = $( '#vc_ui-panel-edit-element' );
		// Hint
		$( 'body' ).on( 'vcPanel.shown', function() {
			var $panel = $( this );

			$panel.find( '.vc_shortcode-param' ).each( function() {
				var $this = $( this );
				var settings = $this.data( 'param_settings' );

				if ( typeof settings != 'undefined' && typeof settings.hint != 'undefined' ) {
					$this.find( '.wpb_element_label' ).append( '<div class="porto-widget-hint"><div class="porto-widget-tooltip porto-widget-tooltip-bottom">' + settings.hint + '</div></div>' );
				}
			} );

			$panelEditElement.on( 'hover mouseover', '.porto-widget-hint', function() {
				var $hint = $( this );
				var _scrollTop = $( window ).scrollTop();
				var _offset = $hint.offset();
				var _top = _offset.top - _scrollTop + 25;
				var _left = _offset.left - 5;
				$hint.children().css( { top: _top, left: _left } );
			} );

			$( '.vc_wrapper-param-type-porto_param_heading' ).each( function() {
				var $divider = $(this);
	
				if ( 'undefined' !== typeof $divider.data( 'param_settings' ) && 'undefined' !== typeof $divider.data( 'param_settings' ).with_group && $divider.data( 'param_settings' ).with_group ) {
					var $fields = $divider.nextUntil( '.vc_wrapper-param-type-porto_param_heading' );
					var $wrapper = $( '<div class="porto-wpb-gp"></div>' );
					var $content = $( '<div class="porto-wpb-content"></div>' );
		
					$divider.before( $wrapper );
					$wrapper.append( $divider );
		
					if ( $fields.length ) {
						$content.append( $fields );
						$wrapper.append( $content );
					}
				}
			} );
			$panelEditElement.trigger( 'porto_param_hd_group' );
		} );

		function hideDividerWrapper( $divider ) {
			var $wrapper = $divider.parent( '.porto-wpb-gp' );
			if ( $divider.hasClass( 'vc_dependent-hidden' ) ) {
				$wrapper.addClass( 'vc_dependent-hidden' );
			} else {
				$wrapper.removeClass( 'vc_dependent-hidden' );
			}
		}
	
		$panelEditElement.on( 'change', '.wpb_el_type_porto_param_heading', function() {
			hideDividerWrapper( $( this ) );
		});
	
		$panelEditElement.on( 'porto_param_hd_group', function() {
			$( '.wpb_el_type_porto_param_heading' ).each( function() {
				hideDividerWrapper( $( this ) );
			} );
		} );


		$( '.vc_shortcode-link' ).each( function() {
			var _this = $( this );
    		var img = _this.find( 'i' );
    		_this.find( 'i' ).remove();
    		var content = '<div class="porto-widget-text">' + _this.html() + '</div>';
    		_this.html( '' );
    		var img_div = $( '<div class="porto-widget-img-wrapper"></div>' );
    		img_div.append( img );
    		_this.append( img_div );
    		_this.append( content );
    	} );

		$( '.porto-wpb-widget_nav' ).each( function() {
			var _this = $( this );
			var _icon = _this.find( 'i' );
			if ( _icon.length ) {
				var bi = '';
				_icon.attr('class').split(' ').forEach(function(cls) {
					cls = cls.trim();
					if ( cls ) {
						var ext = cls.substr(-4);
						if ( '.png' == ext || '.gif' == ext || '.svg' == ext || '.jpg' == ext ) {
							bi = cls;
							return false;
						}
					}
				});
				if ( ! bi ) {
					var style = _icon.currentStyle || window.getComputedStyle( _icon[0], false );
					bi = style.backgroundImage.slice( 4, -1 ).replace( /"/g, "" );
				}
				var img = '<img loading="lazy" class="porto-widget-img" src="' + bi + '" >';
				_icon.replaceWith( img );
			}
    		var img = _this.find( 'img' );
    		_this.find( 'img' ).remove();
    		var content = '<div>' + _this.html() + '</div>';
    		_this.html( '' );
    		_this.append( img );
    		_this.append( content );
    	} );
	} );

	var orderAutoComplete = function() {
		if( jQuery( '.orderby.autocomplete_field' ).length ) {
			var orderby = jQuery( '.orderby.autocomplete_field' ).val();
			jQuery('.wpb_el_type_porto_button_group[data-vc-shortcode-param-name*="order_"]').each(function() {
				var $this = jQuery( this );
				var paramName = $this.attr( 'data-vc-shortcode-param-name' ).slice(6);
				if( orderby.indexOf( paramName ) > -1 ) {
					$this.removeClass( 'vc_dependent-hidden' );
				} else {
					$this.addClass( 'vc_dependent-hidden' );
				}
			});			
		}
	}

} )( window.jQuery )