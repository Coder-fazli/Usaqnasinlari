<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header Builder
 */

if ( ! class_exists( 'PortoBuildersHeader' ) ) :
	class PortoBuildersHeader {

		private $display_wpb_elements = false;

		public static $elements = array(
			'logo',
			'menu',
			'toggle',
			'switcher',
			'search-form',
			'social',
			'menu-icon',
			'divider',
		);

		public static $woo_elements = array(
			'mini-cart',
			'myaccount',
		);

		/**
		 * Global Instance Objects
		 *
		 * @var array $instances
		 * @since 2.4.0
		 * @access private
		 */
		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( class_exists( 'YITH_WCWL' ) ) {
				$this::$woo_elements[] = 'wishlist';
			}
			if ( defined( 'YITH_WOOCOMPARE' ) ) {
				$this::$woo_elements[] = 'compare';
			}
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				if ( is_admin() ) {
					add_action(
						'elementor/elements/categories_registered',
						function( $self ) {
							$self->add_category(
								'porto-hb',
								array(
									'title'  => __( 'Porto Header Builder', 'porto-functionality' ),
									'active' => true,
								)
							);
						}
					);
				}

				add_action( 'elementor/widgets/register', array( $this, 'add_elementor_elements' ), 10, 1 );
			}

			if ( defined( 'WPB_VC_VERSION' ) ) {
				add_action( 'vc_after_init', array( $this, 'load_wpb_map_elements' ) );
			}
			if ( is_admin() ) {
				add_action( 'save_post', array( $this, 'add_internal_dynamic_css' ), 100, 2 );
			}

			if ( defined( 'WPB_VC_VERSION' ) || defined( 'VCV_VERSION' ) ) {
				add_action(
					'template_redirect',
					function() {
						$should_add_shortcodes = false;
						if ( is_singular( PortoBuilders::BUILDER_SLUG ) || ! empty( $_GET['vcv-ajax'] ) || ( function_exists( 'porto_is_ajax' ) && porto_is_ajax() && ! empty( $_GET[ PortoBuilders::BUILDER_SLUG ] ) ) ) {
							$should_add_shortcodes = true;
						} elseif ( function_exists( 'porto_check_builder_condition' ) ) {
							global $porto_settings;
							$builder_id = porto_check_builder_condition( 'header' );
							if ( isset( $porto_settings['header-type-select'] ) && 'header_builder_p' == $porto_settings['header-type-select'] && $builder_id ) {
								$should_add_shortcodes = true;
							}
						}

						if ( $should_add_shortcodes ) {
							$this->add_shortcodes();
						} else {
							$this->add_shortcodes( array( 'social' ) );
						}
					}
				);

				add_action(
					'admin_init',
					function() {
						$should_add_shortcodes = false;
						if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'vc_save' == $_REQUEST['action'] ) {
							$should_add_shortcodes = true;
						} elseif ( isset( $_POST['action'] ) && 'editpost' == $_POST['action'] && isset( $_POST['post_type'] ) && PortoBuilders::BUILDER_SLUG == $_POST['post_type'] ) {
							$should_add_shortcodes = true;
						}
						if ( $should_add_shortcodes ) {
							$this->add_shortcodes();
						}
					}
				);
			}

			$this->add_gutenberg_elements();
		}

		public function add_elementor_elements( $self ) {
			$load_widgets = false;
			if ( is_admin() ) {
				$load_widgets = true;
			} else {
				global $porto_settings;
				if ( isset( $porto_settings['header-type-select'] ) && 'header_builder_p' == $porto_settings['header-type-select'] ) {
					$load_widgets = true;
				}
			}
			if ( $load_widgets ) {
				foreach ( $this::$elements as $element ) {
					include_once PORTO_BUILDERS_PATH . '/elements/header/elementor/' . $element . '.php';
					$class_name = 'Porto_Elementor_HB_' . ucfirst( str_replace( '-', '_', $element ) ) . '_Widget';
					if ( class_exists( $class_name ) ) {
						$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
					}
				}
				if ( class_exists( 'Woocommerce' ) ) {
					foreach ( $this::$woo_elements as $element ) {
						include_once PORTO_BUILDERS_PATH . '/elements/header/elementor/' . $element . '.php';
						$class_name = 'Porto_Elementor_HB_' . ucfirst( str_replace( '-', '_', $element ) ) . '_Widget';
						if ( class_exists( $class_name ) ) {
							$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
						}
					}
				}
			}
		}

		private function add_shortcodes( $global_shortcodes = array() ) {
			$shortcodes = $this::$elements;
			if ( class_exists( 'Woocommerce' ) ) {
				$shortcodes = array_merge( $shortcodes, $this::$woo_elements );
			}
			foreach ( $shortcodes as $tag ) {
				if ( /*in_array( $tag, array( 'menu-icon', 'divider', 'myaccount' ) ) || */ ! function_exists( 'porto_header_elements' ) ) {
					continue;
				}
				if ( ! empty( $global_shortcodes ) && ! in_array( $tag, $global_shortcodes ) ) {
					continue;
				}
				$shortcode_name = str_replace( '-', '_', $tag );
				add_shortcode(
					'porto_hb_' . $shortcode_name,
					function( $atts, $content = null ) use ( $tag ) {
						ob_start();
						if ( ! $atts ) {
							$atts = array();
						}
						$el_class = isset( $atts['el_class'] ) ? trim( $atts['el_class'] ) : '';
						$original_atts = $atts;
						if ( in_array( $tag, array( 'menu', 'search-form', 'mini-cart', 'social', 'menu-icon', 'switcher' ) ) ) {
							if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
								ob_start();
								echo '<style>';
								include PORTO_BUILDERS_PATH . '/elements/header/wpb/style-' . $tag . '.php';
								echo '</style>';
								porto_filter_inline_css( ob_get_clean() );
							}
						}

						if ( 'menu' == $tag ) {
							$this->gutenberg_hb_menu( $atts, true );
						} if ( 'toggle' == $tag ) {
							$this->gutenberg_hb_toggle( $atts, true );
						} elseif ( 'switcher' == $tag ) {
							if ( empty( $atts['type'] ) ) {
								return sprintf( esc_html__( 'Select the %1$sSwitcher Type%2$s', 'porto' ), '<b class="ps-1">', '</b>' );
							}

							if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
								$shortcode_name = 'porto_hb_switcher';
								// Shortcode class
								$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
									$atts,
									$shortcode_name,
									array(
										array(
											'param_name' => 'top_bg_color',
											'selectors'  => true,
										),
										array(
											'param_name' => 'top_hover_bg_color',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_menu_bg',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_bg',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_font',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_item_padding',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_padding',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_color',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_hover_color',
											'selectors'  => true,
										),
										array(
											'param_name' => 'dropdown_hover_bg',
											'selectors'  => true,
										),
									)
								);
								$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
								if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
									$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_switcher', $atts );
								}
							}
							if ( ! empty( $shortcode_class ) ) {
								$shortcode_class .= $el_class ? ' ' . $el_class : '';
							}
							if ( ! empty( $internal_css ) ) {
								// only wpbakery frontend editor
								echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
							}
							isset( $atts['type'] ) && porto_header_elements( array( (object) array( $atts['type'] => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );
						} elseif ( 'search-form' == $tag ) {
							$this->gutenberg_hb_search_form( $atts, true );
						} elseif ( 'mini-cart' == $tag ) {
							$this->gutenberg_hb_mini_cart( $original_atts, true );
						} elseif ( 'menu-icon' == $tag ) {
							$this->gutenberg_hb_menu_icon( $atts, true );
						} elseif ( 'divider' == $tag ) {
							$this->gutenberg_hb_divider( $atts, true );
						} elseif ( 'myaccount' == $tag ) {
							$this->gutenberg_hb_myaccount( $atts, true );
						} elseif ( 'wishlist' == $tag ) {
							$this->gutenberg_hb_wishlist( $atts, true );
						} elseif ( 'compare' == $tag ) {
							$this->gutenberg_hb_compare( $atts, true );
						} elseif ( 'social' == $tag ) {
							$this->gutenberg_hb_social( $atts, true );
						} elseif ( 'logo' == $tag ) {
							if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
								$shortcode_name = 'porto_hb_logo';
								// Shortcode class
								$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
									$atts,
									$shortcode_name,
									array(
										array(
											'param_name' => 'is_align_center',
											'selectors'  => true,
										),
									)
								);
								$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
								if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_design'] ) ) {
									$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_design'], ' ' ), 'porto_hb_logo', $atts );
								}
							}
							

							if ( isset( $atts['el_class'] ) ) {
								$el_class = trim( $atts['el_class'] );
							} elseif ( isset( $atts['className'] ) ) {
								$el_class = trim( $atts['className'] );
							} else {
								$el_class = '';
							}
							if ( function_exists( 'vc_is_inline' ) && vc_is_inline() && ! empty( $atts['is_align_center'] ) ) {
								$el_class .= ' mx-auto text-center ';
							}
							if ( ! empty( $shortcode_class ) ) {
								$shortcode_class .= ( $el_class ? ' ' . $el_class : '' );
							}
							if ( ! empty( $internal_css ) ) {
								// only wpbakery frontend editor
								echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
							}
							porto_header_elements( array( (object) array( 'logo' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );						
						} else {
							porto_header_elements( array( (object) array( $tag => '' ) ), $el_class );
						}
						return ob_get_clean();
					}
				);
			}
		}

		/**
		 * Add WPBakery Page Builder header elements
		 */
		public function load_wpb_map_elements( $direct = false ) {
			if ( false === $direct ) {
				if ( ! $this->display_wpb_elements ) {
					$this->display_wpb_elements = PortoBuilders::check_load_wpb_elements( 'header' );
				}
				if ( ! $this->display_wpb_elements ) {
					return;
				}
			}

			$custom_class = porto_vc_custom_class();
			$right        = is_rtl() ? 'left' : 'right';
			vc_map(
				array(
					'name'        => __( 'Logo', 'porto-functionality' ),
					'description' => __( 'Show Site logo.', 'porto-functionality' ),
					'base'        => 'porto_hb_logo',
					'icon'        => PORTO_WIDGET_URL . 'logo.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_logo',
							'text'       => sprintf( esc_html__( 'Please change the settings of logo in %1$s\'Theme Options -> Logo\'%2$s panel.', 'porto-functionality' ), '<a target="_blank" href="' . porto_get_theme_option_url( 'logo' ) . '">', '</a>' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Is Center ?', 'porto-functionality' ),
							'description' => __( 'Turn on to make the logo center alignment for side header.', 'porto-functionality' ),
							'param_name'  => 'is_align_center',
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'#header {{WRAPPER}}.logo' => 'text-align: center; margin-left: auto; margin-right: auto;',
							),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'CSS', 'porto-functionality' ),
							'param_name'       => 'css_design',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'Menu', 'porto-functionality' ),
					'description' => __( 'Show Navigation Menu.', 'porto-functionality' ),
					'base'        => 'porto_hb_menu',
					'icon'        => PORTO_WIDGET_URL . 'menu.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_menu',
							'text'       => esc_html__( 'Please change the Menu Type on \'Theme Options -> Menu\'.', 'porto-functionality' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Location', 'porto-functionality' ),
							'param_name'  => 'location',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-location.gif"/>',
							'value'       => array(
								__( 'Select a Location', 'porto-functionality' ) => '',
								__( 'Main Menu', 'porto-functionality' )         => 'main-menu',
								__( 'Secondary Menu', 'porto-functionality' )    => 'secondary-menu',
								__( 'Main Toggle Menu ( Deprecated )', 'porto-functionality' )  => 'main-toggle-menu',
								__( 'Top Navigation', 'porto-functionality' )    => 'nav-top',
							),
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Collapse the Menu on home page', 'porto-functionality' ),
							'param_name'  => 'menu-toggle-onhome',
							'description' => __( 'In homepage, a toggle menu is collapsed at first. Then it works as a toggle.', 'porto-functionality' ),
							'value'       => array(
								__( 'Select', 'porto-functionality' ) => '',
								__( 'No', 'porto-functionality' )     => '0',
								__( 'Yes', 'porto-functionality' )    => '1',
							),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Show menu on Hover', 'porto-functionality' ),
							'param_name'  => 'show-onhover',
							'value'       => array(
								__( 'No', 'porto-functionality' )  => 'no',
								__( 'Yes', 'porto-functionality' ) => 'yes',
							),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Menu Title', 'porto-functionality' ),
							'param_name'  => 'title',
							'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-title' ) . '" target="_blank">', '</a>' ),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Font Size', 'porto-functionality' ),
							'param_name'  => 'font_size',
							'description' => __( 'Please inputs with units together. e.g: 16px', 'porto-functionality' ),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Font Weight', 'porto-functionality' ),
							'param_name' => 'font_weight',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Text Transform', 'porto-functionality' ),
							'param_name' => 'text_transform',
							'value'      => array(
								__( 'Default', 'porto-functionality' )    => '',
								__( 'None', 'porto-functionality' )       => 'none',
								__( 'Uppercase', 'porto-functionality' )  => 'uppercase',
								__( 'Capitalize', 'porto-functionality' ) => 'capitalize',
								__( 'Lowercase', 'porto-functionality' )  => 'lowercase',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Line Height', 'porto-functionality' ),
							'param_name'  => 'line_height',
							'description' => __( 'Please inputs with units together. e.g: 16px', 'porto-functionality' ),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Letter Spacing', 'porto-functionality' ),
							'param_name'  => 'letter_spacing',
							'description' => __( 'Please inputs with units together. e.g: -0.01em', 'porto-functionality' ),
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Size', 'porto-functionality' ),
							'param_name'  => 'tg_icon_sz',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}}#main-toggle-menu .toggle' => "font-size: {{VALUE}}{{UNIT}};vertical-align: middle;",
							),
							'dependency'  => array(
								'element' => 'location',
								'value'   => 'main-toggle-menu',
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Between Spacing', 'porto-functionality' ),
							'description' => __( 'Controls the spacing.', 'porto-functionality' ),
							'param_name'  => 'between_spacing',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title .toggle' => "margin-{$right}: {{VALUE}}{{UNIT}};",
							),
							'qa_selector' => '#main-toggle-menu .menu-title .toggle',
							'dependency'  => array(
								'element' => 'location',
								'value'   => 'main-toggle-menu',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title Left / Right Padding (e.g: 10px)', 'porto-functionality' ),
							'param_name' => 'padding_x',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Title Top / Bottom Padding', 'porto-functionality' ),
							'param_name' => 'padding3',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title' => 'padding-top: {{VALUE}}{{UNIT}};padding-bottom: {{VALUE}}{{UNIT}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => 'main-toggle-menu',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'param_name' => 'color',
							'value'      => '',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'param_name' => 'bgcolor',
							'value'      => '',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'param_name' => 'hover_color',
							'value'      => '',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu', 'nav-top' ),
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
							'param_name' => 'hover_bgcolor',
							'value'      => '',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Border Width of Top Menu', 'porto-functionality' ),
							'param_name'  => 'toggle_tl_bd_width',
							'dependency'  => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
							'selectors'   => array(
								'#main-toggle-menu{{WRAPPER}} .toggle-menu-wrap > ul.sidebar-menu' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}}; border-style: solid;',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Border Color of Top Menu', 'porto-functionality' ),
							'param_name' => 'toggle_tl_bd_color',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
							'selectors'   => array(
								'#main-toggle-menu{{WRAPPER}} .toggle-menu-wrap > ul.sidebar-menu' => 'border-color: {{VALUE}}; border-style: solid;',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Popup Width e.g: 300px', 'porto-functionality' ),
							'param_name' => 'popup_width',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'desc_hamburger',
							'text'       => sprintf( __( 'Please change the %1$sMenu Type%2$s to \'Popup Menu\', If you want to use the Hamburger Menu.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-type' ) . '" target="_blank">', '</a>' ),
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Width (e.g: 50px)', 'porto-functionality' ),
							'param_name' => 'hamburger_wd',
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
							'selectors'   => array(
								'{{WRAPPER}} .hamburguer-btn' => 'width: {{VALUE}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
							'qa_selector' => '.hamburguer-btn',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Height (e.g: 50px)', 'porto-functionality' ),
							'param_name' => 'hamburger_hg',
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
							'selectors'   => array(
								'{{WRAPPER}} .hamburguer-btn' => 'height: {{VALUE}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Thickness (e.g: 50px)', 'porto-functionality' ),
							'param_name' => 'hamburger_th',
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
							'selectors'   => array(
								'{{WRAPPER}} .hamburguer span' => 'height: {{VALUE}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Margin', 'porto-functionality' ),
							'param_name' => 'hamburger_mr',
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} .hamburguer-btn' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'hamburger_cl',
							'heading'    => __( 'Button Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .hamburguer span' => 'background-color: {{VALUE}};transition: background-color .3s;',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'hamburger_hcl',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .hamburguer-btn:hover .hamburguer span' => 'background-color: {{VALUE}};',
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-menu' ),
							),
							'group'      => __( 'Hamburger Button', 'porto-functionality' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Show Dropdown Arrow', 'porto-functionality' ),
							'param_name'  => 'show_narrow',
							'description' => __( 'Turn on to show the narrow.', 'porto-functionality' ),
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}} .menu-title:after' => 'content:"\\\e81c";' . "position:absolute;font-family:'porto';{$right}: 1.4rem;",
							),
							'dependency' => array(
								'element' => 'location',
								'value'   => 'main-toggle-menu',
							),
							'group'      => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Right Position', 'porto-functionality' ),
							'param_name' => 'narrow_pos',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}} .menu-title:after' => "{$right}: {{VALUE}}{{UNIT}};",
							),
							
							'dependency' => array(
								'element' => 'show_narrow',
								'value'   => 'yes',
							),
							'group'      => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Size', 'porto-functionality' ),
							'param_name' => 'narrow_sz',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}} .menu-title:after' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'dependency' => array(
								'element' => 'show_narrow',
								'value'   => 'yes',
							),
							'group'      => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Top Level Typography', 'porto-functionality' ),
							'param_name' => 'top_level_font',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-top_level_font.gif"/>',
							'selectors'  => array(
								'#header {{WRAPPER}}.main-menu > li.menu-item > a, #header {{WRAPPER}} .menu-custom-block span, #header {{WRAPPER}} .menu-custom-block a, {{WRAPPER}} .sidebar-menu > li.menu-item > a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item > a',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => 'nav-top',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_color',
							'heading'    => __( 'Link Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.main-menu > li.menu-item > a, {{WRAPPER}} .sidebar-menu > li.menu-item > a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item > a, #header {{WRAPPER}}.main-menu > li.menu-item > .arrow:before, {{WRAPPER}} .sidebar-menu > li.menu-item > .arrow:before, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item > .arrow:before' => 'color: {{VALUE}};',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => 'nav-top',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_bg_color',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links > li.menu-item > a, #header {{WRAPPER}}.main-menu > li.menu-item > a, {{WRAPPER}} .sidebar-menu > li.menu-item, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item > a' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_hover_color',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.main-menu > li.menu-item.active > a, #header {{WRAPPER}}.main-menu > li.menu-item:hover > a, {{WRAPPER}} .sidebar-menu > li.menu-item:hover > a, {{WRAPPER}} .sidebar-menu > li.menu-item.active > a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item.active:hover > a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item:hover > a, #header {{WRAPPER}}.main-menu > li.menu-item.active > .arrow:before, #header {{WRAPPER}}.main-menu > li.menu-item:hover > .arrow:before, {{WRAPPER}} .sidebar-menu > li.menu-item:hover > .arrow:before, {{WRAPPER}} .sidebar-menu > li.menu-item.active > .arrow:before, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item.active:hover > .arrow:before, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item:hover > .arrow:before' => 'color: {{VALUE}};',
								'{{WRAPPER}}.menu-hover-line>li.menu-item>a:before' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => 'nav-top',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_hover_bg_color',
							'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links > li.menu-item:hover > a, #header {{WRAPPER}}.top-links > li.menu-item.has-sub:hover > a, #header {{WRAPPER}}.main-menu > li.menu-item.active > a, #header {{WRAPPER}}.main-menu > li.menu-item:hover > a, {{WRAPPER}} .sidebar-menu > li.menu-item:hover, {{WRAPPER}} .sidebar-menu > li.menu-item.active, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item.active:hover > a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item:hover > a' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_sticky_link_color',
							'heading'    => __( 'Link Color On Sticky Header', 'porto-functionality' ),
							'selectors'  => array(
								'#header.sticky-header {{WRAPPER}}.main-menu > li.menu-item > a' => 'color: {{VALUE}};',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top', 'main-toggle-menu' ),
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_sticky_link_color_hover',
							'heading'    => __( 'Link Hover Color On Sticky Header', 'porto-functionality' ),
							'selectors'  => array(
								'#header.sticky-header {{WRAPPER}}.main-menu > li.menu-item:hover > a, #header.sticky-header {{WRAPPER}}.main-menu > li.menu-item.active > a' => 'color: {{VALUE}};',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top', 'main-toggle-menu' ),
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),						
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Size', 'porto-functionality' ),
							'description' => __( 'Controls the size of menu icon.', 'porto-functionality' ),
							'param_name'  => 'top_level_icon_sz',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} li.menu-item>a>[class*=" fa-"]' => 'width: {{VALUE}}{{UNIT}};',
								'{{WRAPPER}} li.menu-item>a>i' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'qa_selector' => 'li.menu-item>a>i',
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Spacing', 'porto-functionality' ),
							'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
							'param_name'  => 'top_level_icon_spacing',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} li.menu-item>a>.avatar, {{WRAPPER}} li.menu-item>a>i' => "margin-{$right}: {{VALUE}}{{UNIT}};",
							),
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_top_item',
							'text'       => __( 'Top Level Menu Item', 'porto-functionality' ),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Menu Item Padding', 'porto-functionality' ),
							'param_name' => 'top_level_padding',
							'responsive' => true,
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links > li.menu-item > a, #header {{WRAPPER}}.main-menu > li.menu-item > a, #header {{WRAPPER}} .menu-custom-block a, #header {{WRAPPER}} .menu-custom-block span, {{WRAPPER}} .sidebar-menu>li.menu-item>a, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item > a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
								'{{WRAPPER}} .sidebar-menu .popup:before' => 'top: calc( calc( {{TOP}} / 2 + {{BOTTOM}} / 2 - 0.5px ) + ( -1 * var(--porto-sd-menu-popup-top, 0px) ) );', 
								'{{WRAPPER}}.menu-hover-underline > li.menu-item > a:before' => 'margin-left: {{LEFT}}; margin-right: {{RIGHT}}',
							),
							'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'mainmenu-toplevel-padding1' ) . '" target="_blank">', '</a>' ),
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
							'qa_selector' => '.top-links > li:nth-child(2) > a, .main-menu > li:nth-child(2) > a',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'mainmenu-toplevel-padding1.gif"/>',
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Menu Item Padding on Sticky Header', 'porto-functionality' ),
							'param_name' => 'top_level_padding_sticky',
							'responsive' => true,
							'selectors'  => array(
								'#header.sticky-header {{WRAPPER}}.main-menu > li.menu-item > a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
								'#header.sticky-header {{WRAPPER}}.menu-hover-underline > li.menu-item > a:before' => 'margin-left: {{LEFT}}; margin-right: {{RIGHT}}',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'main-toggle-menu', 'nav-top' ),
							),
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
							'hint'        => '<img src="' . PORTO_HINT_URL . 'mainmenu-toplevel-padding3.gif"/>',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Item Separator Color', 'porto-functionality' ),
							'param_name' => 'toggle_sp_color',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
							'selectors'   => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Item Separator Hover Color', 'porto-functionality' ),
							'param_name' => 'toggle_sp_hcolor',
							'dependency' => array(
								'element' => 'location',
								'value'   => array( 'main-toggle-menu' ),
							),
							'selectors'   => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item:hover + li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Menu Item Spacing', 'porto-functionality' ),
							'param_name' => 'top_level_margin',
							'responsive' => true,
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links > li.menu-item, #header {{WRAPPER}}.main-menu > li.menu-item, #header {{WRAPPER}} .menu-custom-block, #header {{WRAPPER}}.porto-popup-menu .main-menu > li.menu-item' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => 'main-toggle-menu',
							),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Popup Typography', 'porto-functionality' ),
							'param_name' => 'submenu_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .porto-wide-sub-menu a, #header {{WRAPPER}} .porto-narrow-sub-menu a, {{WRAPPER}} .sidebar-menu .popup, {{WRAPPER}}.porto-popup-menu .sub-menu, #header {{WRAPPER}}.top-links .narrow li.menu-item>a',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_color',
							'heading'    => __( 'Link Color', 'porto-functionality' ),
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-submenu_link_color.jpg"/>',
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links .narrow li.menu-item > a, #header {{WRAPPER}}.main-menu .wide li.sub li.menu-item > a, #header {{WRAPPER}}.main-menu .narrow li.menu-item > a,#header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a,#header {{WRAPPER}} .sidebar-menu .wide li.sub li.menu-item > a,#header {{WRAPPER}} .sidebar-menu .narrow li.menu-item > a,#header {{WRAPPER}}.porto-popup-menu .sub-menu a' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_bg_color',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links .narrow ul.sub-menu, #header {{WRAPPER}}.main-menu .wide .popup > .inner, #header {{WRAPPER}}.main-menu .narrow ul.sub-menu,#header {{WRAPPER}} .sidebar-menu .wide .popup > .inner,#header {{WRAPPER}} .sidebar-menu .narrow ul.sub-menu,#header {{WRAPPER}}.porto-popup-menu .sub-menu a' => 'background-color: {{VALUE}};',
								'#header {{WRAPPER}}.mega-menu > li.has-sub:before, {{WRAPPER}}.mega-menu > li.has-sub:after' => 'border-bottom-color: {{VALUE}};',
								'#header {{WRAPPER}} .sidebar-menu .popup:before' => 'border-right-color: {{VALUE}};'
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_hover_color',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links .narrow li.menu-item:hover > a, #header {{WRAPPER}}.main-menu .wide li.menu-item li.menu-item>a:hover, #header {{WRAPPER}}.main-menu .narrow li.menu-item:hover > a,#header {{WRAPPER}}.porto-popup-menu .sub-menu a:hover,#header {{WRAPPER}} .sidebar-menu .narrow li.menu-item:hover > a,#header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_hover_bg_color',
							'heading'    => __( 'Item Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links .narrow li.menu-item:hover > a, #header {{WRAPPER}} .sidebar-menu .narrow .menu-item:hover > a, #header {{WRAPPER}}.main-menu .narrow li.menu-item:hover > a, #header {{WRAPPER}}.main-menu .wide li.menu-item li.menu-item>a:hover,#header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover,#header {{WRAPPER}}.porto-popup-menu .sub-menu a:hover' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'SubMenu Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of sub menu.', 'porto-functionality' ),
							'param_name'  => 'submenu_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow ul.sub-menu, {{WRAPPER}} .wide .popup>.inner, {{WRAPPER}}.porto-popup-menu .sub-menu' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
								'#header {{WRAPPER}} .porto-narrow-sub-menu ul.sub-menu' => 'top: -{{TOP}};',
							),
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-submenu_padding.gif"/>',
							'qa_selector' => '.narrow ul.sub-menu, .wide .popup>.inner',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_popup_item',
							'text'       => esc_html__( 'Popup Item', 'porto-functionality' ),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Item Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of sub menu items.', 'porto-functionality' ),
							'param_name'  => 'submenu_item_padding',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-submenu_item_padding.gif"/>',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a, {{WRAPPER}} .wide li.sub li.menu-item>a, {{WRAPPER}}.porto-popup-menu .sub-menu li.menu-item>a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'qa_selector' => '.narrow li:first-child>a, .wide li.sub li:first-child>a, .porto-popup-menu .sub-menu li:first-child>a',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Item Border Width on Narrow Menu', 'porto-functionality' ),
							'description' => __( 'Controls the border width of the menu item on narrow menu.', 'porto-functionality' ),
							'param_name'  => 'submenu_narrow_bd_width',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a' => '--porto-submenu-item-bbw: {{VALUE}}{{UNIT}};',
							),
							'dependency'  => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'submenu_narrow_border_color',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-submenu_narrow_border_color.gif"/>',
							'heading'     => __( 'Item Border Color on Narrow Menu', 'porto-functionality' ),
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a' => 'border-bottom-color: {{VALUE}};',
							),
							'dependency'  => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'qa_selector' => '.narrow li:nth-child(2)>a',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),

						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_wide_subheading',
							'text'       => esc_html__( 'Sub Heading on Mega Menu', 'porto-functionality' ),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'with_group' => true,
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'mega_title_color',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_menu-mega_title_color.gif"/>',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.main-menu .wide li.sub > a, {{WRAPPER}} .sidebar-menu .wide li.sub > a' => 'color: {{VALUE}};',
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a' => 'color: {{VALUE}} !important;',
							),
							'qa_selector' => '.wide li.sub > a',
							'dependency'  => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Typography', 'porto-functionality' ),
							'param_name' => 'mega_title_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a, #header {{WRAPPER}}.main-menu .wide li.sub > a, #header {{WRAPPER}} .sidebar-menu .wide li.sub > a',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of subheading.', 'porto-functionality' ),
							'param_name'  => 'mega_title_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a, #header {{WRAPPER}}.main-menu .wide li.sub > a, {{WRAPPER}} .sidebar-menu .wide li.sub > a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'dependency' => array(
								'element'            => 'location',
								'value_not_equal_to' => array( 'nav-top' ),
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'CSS', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'Toggle Dropdown Menu', 'porto-functionality' ),
					'description' => __( 'Show Main Menu as dropdown type', 'porto-functionality' ),
					'base'        => 'porto_hb_toggle',
					'icon'        => PORTO_WIDGET_URL . 'toggle-menu.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Collapse the Menu on home page', 'porto-functionality' ),
							'param_name'  => 'menu-toggle-onhome',
							'description' => __( 'In homepage, a toggle menu is collapsed at first. Then it works as a toggle.', 'porto-functionality' ),
							'value'       => array(
								__( 'Select', 'porto-functionality' ) => '',
								__( 'No', 'porto-functionality' )     => '0',
								__( 'Yes', 'porto-functionality' )    => '1',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Show menu on Hover', 'porto-functionality' ),
							'param_name'  => 'show-onhover',
							'value'       => array(
								__( 'No', 'porto-functionality' )  => 'no',
								__( 'Yes', 'porto-functionality' ) => 'yes',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Menu Title', 'porto-functionality' ),
							'param_name'  => 'title',
							'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-title' ) . '" target="_blank">', '</a>' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Title Typography', 'porto-functionality' ),
							'param_name' => 'font_typography',
							'responsive' => true,
							'selectors'  => array(
								'#header {{WRAPPER}}#main-toggle-menu .menu-title',
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Size', 'porto-functionality' ),
							'param_name'  => 'tg_icon_sz',
							'units'       => array( 'px', 'rem', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}}#main-toggle-menu .toggle' => "font-size: {{VALUE}}{{UNIT}}; vertical-align: middle;",
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Between Spacing', 'porto-functionality' ),
							'description' => __( 'Controls the spacing.', 'porto-functionality' ),
							'param_name'  => 'between_spacing',
							'units'       => array( 'px', 'rem', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title .toggle' => "margin-{$right}: {{VALUE}}{{UNIT}};",
							),
							'qa_selector' => '#main-toggle-menu .menu-title .toggle',
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Title Padding', 'porto-functionality' ),
							'param_name' => 'padding1',
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title' => 'padding-top:{{TOP}}; padding-right:{{RIGHT}}; padding-bottom:{{BOTTOM}}; padding-left: {{LEFT}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'param_name' => 'color',
							'value'      => '',
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'param_name' => 'bgcolor',
							'value'      => '',
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title, {{WRAPPER}}#main-toggle-menu.show-always .menu-title' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'param_name' => 'hover_color',
							'value'      => '',
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title:hover' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
							'param_name' => 'hover_bgcolor',
							'value'      => '',
							'selectors'  => array(
								'{{WRAPPER}}#main-toggle-menu .menu-title:hover' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Show Dropdown Arrow', 'porto-functionality' ),
							'param_name'  => 'show_narrow',
							'description' => __( 'Turn on to show the narrow.', 'porto-functionality' ),
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}} .menu-title:after' => 'content:"\\\e81c";' . "position:absolute;font-family:'porto';{$right}: 1.4rem;",
							),
							'group'       => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Right Position', 'porto-functionality' ),
							'param_name' => 'narrow_pos',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}} .menu-title:after' => "{$right}: {{VALUE}}{{UNIT}};",
							),
							'dependency' => array(
								'element' => 'show_narrow',
								'value'   => 'yes',
							),
							'group'      => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Size', 'porto-functionality' ),
							'param_name' => 'narrow_sz',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}} .menu-title:after' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'dependency' => array(
								'element' => 'show_narrow',
								'value'   => 'yes',
							),
							'group'      => __( 'Toggle Narrow', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Border Width of Top Menu', 'porto-functionality' ),
							'param_name' => 'toggle_tl_bd_width',
							'selectors'  => array(
								'#main-toggle-menu{{WRAPPER}} .toggle-menu-wrap > ul.sidebar-menu' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}}; border-style: solid;',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Border Color of Top Menu', 'porto-functionality' ),
							'param_name' => 'toggle_tl_bd_color',
							'selectors'  => array(
								'#main-toggle-menu{{WRAPPER}} .toggle-menu-wrap > ul.sidebar-menu' => 'border-color: {{VALUE}}; border-style: solid;',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Popup Width', 'porto-functionality' ),
							'param_name' => 'popup_width',
							'units'      => array( 'px', 'rem', 'em' ),
							'selectors'  => array(
								'#main-toggle-menu{{WRAPPER}} .toggle-menu-wrap' => 'width: {{VALUE}}{{UNIT}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Top Level Typography', 'porto-functionality' ),
							'param_name' => 'top_level_font',
							'hint'       => '<img src="' . PORTO_HINT_URL . 'hb_toggle-top_level_font.gif"/>',
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item > a',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_color',
							'heading'    => __( 'Link Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item > a, {{WRAPPER}} .sidebar-menu > li.menu-item > .arrow:before ' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_bg_color',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.top-links > li.menu-item > a, #header {{WRAPPER}}.main-menu > li.menu-item > a, {{WRAPPER}} .sidebar-menu > li.menu-item' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_hover_color',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item:hover > a, {{WRAPPER}} .sidebar-menu > li.menu-item.active > a, {{WRAPPER}} .sidebar-menu > li.menu-item:hover > .arrow:before, {{WRAPPER}} .sidebar-menu > li.menu-item.active > .arrow:before' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_level_link_hover_bg_color',
							'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item:hover, {{WRAPPER}} .sidebar-menu > li.menu-item.active' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),				
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Size', 'porto-functionality' ),
							'description' => __( 'Controls the size of menu icon.', 'porto-functionality' ),
							'param_name'  => 'top_level_icon_sz',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} li.menu-item>a>[class*=" fa-"]' => 'width: {{VALUE}}{{UNIT}};',
								'{{WRAPPER}} li.menu-item>a>i' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'qa_selector' => 'li.menu-item>a>i',
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Spacing', 'porto-functionality' ),
							'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
							'param_name'  => 'top_level_icon_spacing',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} li.menu-item>a>.avatar, {{WRAPPER}} li.menu-item>a>i' => "margin-{$right}: {{VALUE}}{{UNIT}};",
							),
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_top_item',
							'text'       => __( 'Top Level Menu Item', 'porto-functionality' ),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Padding', 'porto-functionality' ),
							'param_name' => 'top_level_padding',
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu>li.menu-item>a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
								'{{WRAPPER}} .sidebar-menu .popup:before' => 'top: calc( calc( {{TOP}} / 2 + {{BOTTOM}} / 2 - 0.5px ) + ( -1 * var(--porto-sd-menu-popup-top, 0px) ) );',
							),
							'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'mainmenu-toplevel-padding1' ) . '" target="_blank">', '</a>' ),
							'group'       => __( 'Top Level Menu', 'porto-functionality' ),
							'qa_selector' => '.top-links > li:nth-child(2) > a, .main-menu > li:nth-child(2) > a',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_toggle-top_level_font.gif"/>',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Separator Color', 'porto-functionality' ),
							'param_name' => 'toggle_sp_color',
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Separator Hover Color', 'porto-functionality' ),
							'param_name' => 'toggle_sp_hcolor',
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu > li.menu-item:hover + li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
							'group'      => __( 'Top Level Menu', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Popup Typography', 'porto-functionality' ),
							'param_name' => 'submenu_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .porto-wide-sub-menu a, #header {{WRAPPER}} .porto-narrow-sub-menu a, {{WRAPPER}} .sidebar-menu .popup, #header {{WRAPPER}}.top-links .narrow li.menu-item>a',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_color',
							'heading'    => __( 'Link Color', 'porto-functionality' ),
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_toggle-submenu_link_color.gif"/>',
							'selectors'  => array(
								'#header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a, #header {{WRAPPER}} .sidebar-menu .wide li.sub li.menu-item > a, #header {{WRAPPER}} .sidebar-menu .narrow li.menu-item > a' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_bg_color',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu .wide .popup > .inner,{{WRAPPER}} .sidebar-menu .narrow ul.sub-menu' => 'background-color: {{VALUE}};',
								'{{WRAPPER}} .sidebar-menu .popup:before' => 'border-right-color: {{VALUE}};'
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_hover_color',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .sidebar-menu .narrow li.menu-item:hover > a, #header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'submenu_link_hover_bg_color',
							'heading'    => __( 'Item Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .sidebar-menu .narrow .menu-item:hover > a, #header {{WRAPPER}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'SubMenu Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of sub menu.', 'porto-functionality' ),
							'param_name'  => 'submenu_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow ul.sub-menu, {{WRAPPER}} .wide .popup>.inner' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
								'#header {{WRAPPER}} .porto-narrow-sub-menu ul.sub-menu' => 'top: -{{TOP}};',
							),
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_toggle-submenu_padding.gif"/>',
							'qa_selector' => '.narrow ul.sub-menu, .wide .popup>.inner',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_popup_item',
							'text'       => esc_html__( 'Popup Item', 'porto-functionality' ),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Item Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of sub menu items.', 'porto-functionality' ),
							'param_name'  => 'submenu_item_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a, {{WRAPPER}} .wide li.sub li.menu-item>a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'qa_selector' => '.narrow li:first-child>a, .wide li.sub li:first-child>a',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Item Border Width on Narrow Menu', 'porto-functionality' ),
							'description' => __( 'Controls the border width of the menu item on narrow menu.', 'porto-functionality' ),
							'param_name'  => 'submenu_narrow_bd_width',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a' => '--porto-submenu-item-bbw: {{VALUE}}{{UNIT}};',
							),
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'submenu_narrow_border_color',
							'heading'     => __( 'Item Border Color on Narrow Menu', 'porto-functionality' ),
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a' => 'border-bottom-color: {{VALUE}};',
							),
							'qa_selector' => '.narrow li:nth-child(2)>a',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),

						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_wide_subheading',
							'text'       => esc_html__( 'Sub Heading on Mega Menu', 'porto-functionality' ),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'mega_title_color',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_toggle-mega_title_color.gif"/>',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .sidebar-menu .wide li.sub > a' => 'color: {{VALUE}};',
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a' => 'color: {{VALUE}} !important;',
							),
							'qa_selector' => '.wide li.sub > a',
							'group'       => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Typography', 'porto-functionality' ),
							'param_name' => 'mega_title_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a, #header {{WRAPPER}} .sidebar-menu .wide li.sub > a',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of subheading.', 'porto-functionality' ),
							'param_name'  => 'mega_title_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .wide li.side-menu-sub-title > a, #header {{WRAPPER}}.main-menu .wide li.sub > a, {{WRAPPER}} .sidebar-menu .wide li.sub > a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'group'      => __( 'Menu Popup', 'porto-functionality' ),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'Css', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'View Switcher', 'porto-functionality' ),
					'description' => __( 'Language, Currency Switcher', 'porto-functionality' ),
					'base'        => 'porto_hb_switcher',
					'icon'        => PORTO_WIDGET_URL . 'switcher.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_switcher',
							'text'       => sprintf( esc_html__( 'Please see %1$s\'Theme Options -> Header -> Language, Currency Switcher\'%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'wpml-switcher' ) . '" target="_blank">', '</a>'),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Type', 'porto-functionality' ),
							'param_name'  => 'type',
							'value'       => array(
								__( 'Select...', 'porto-functionality' ) => '',
								__( 'Language Switcher', 'porto-functionality' ) => 'language-switcher',
								__( 'Currency Switcher', 'porto-functionality' ) => 'currency-switcher',
							),
							'admin_label' => true,
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'dsc_top',
							'text'       => esc_html__( 'Top Level', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Top Level Font Size (e.g: 12.8px)', 'porto-functionality' ),
							'param_name' => 'font_size',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_switcher-font_size.jpg"/>',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Top Level Font Weight (e.g: 700)', 'porto-functionality' ),
							'param_name' => 'font_weight',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Top Level Text Transform', 'porto-functionality' ),
							'param_name' => 'text_transform',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								__( 'None', 'porto-functionality' ) => 'none',
								__( 'Uppercase', 'porto-functionality' ) => 'uppercase',
								__( 'Capitalize', 'porto-functionality' ) => 'capitalize',
								__( 'Lowercase', 'porto-functionality' ) => 'lowercase',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Top Level Line Height (e.g: 1)', 'porto-functionality' ),
							'param_name' => 'line_height',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Top Level Letter Spacing (e.g: -.1em)', 'porto-functionality' ),
							'param_name' => 'letter_spacing',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Top Level Color', 'porto-functionality' ),
							'param_name' => 'color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Top Level Hover Color', 'porto-functionality' ),
							'param_name' => 'hover_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_bg_color',
							'heading'    => __( 'Top Level Bg Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.porto-view-switcher > li.menu-item > a' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'top_hover_bg_color',
							'heading'    => __( 'Top Level Hover Bg Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.porto-view-switcher > li.menu-item:hover > a' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'dsc_dropdown',
							'text'       => esc_html__( 'Dropdown', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Dropdown Label Font', 'porto-functionality' ),
							'param_name' => 'dropdown_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a',
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Dropdown Label Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of dropdown label.', 'porto-functionality' ),
							'param_name'  => 'dropdown_item_padding',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_switcher-dropdown_font.jpg"/>',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow li.menu-item>a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Dropdown Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of dropdown.', 'porto-functionality' ),
							'param_name'  => 'dropdown_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .narrow ul.sub-menu' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'dropdown_color',
							'heading'    => __( 'Dropdown Label Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow li.menu-item > a' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'dropdown_hover_color',
							'heading'    => __( 'Dropdown Label Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow li.menu-item:hover > a' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'dropdown_menu_bg',
							'heading'    => __( 'Dropdown Menu Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow ul.sub-menu' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'dropdown_bg',
							'heading'    => __( 'Dropdown Label Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow li.menu-item > a' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'dropdown_hover_bg',
							'heading'    => __( 'Dropdown Label Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .narrow li.menu-item:hover > a, #header {{WRAPPER}} .narrow li.menu-item > a.active' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'dsc_extra',
							'text'       => esc_html__( 'Extra', 'porto-functionality' ),
							'with_group' => true,
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'Css', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			$border_radius_selectors = array(
				'#header {{WRAPPER}}.searchform-popup .searchform'        => 'border-radius: {{VALUE}};',
				'#header {{WRAPPER}}.search-popup .searchform-fields' => 'border-radius: {{VALUE}};',
				'#header {{WRAPPER}} .searchform:not(.search-layout-reveal) input'  => 'border-radius: {{VALUE}} 0 0 {{VALUE}};',
				'#header {{WRAPPER}} .searchform.search-layout-reveal button'  => 'border-radius: 0;',
				'#header {{WRAPPER}} .searchform button' => 'border-radius: 0 max( 0px, calc({{VALUE}} - 5px)) max( 0px, calc({{VALUE}} - 5px)) 0;',
			);
			if ( is_rtl() ) {
				$border_radius_selectors = array(
					'#header {{WRAPPER}}.searchform-popup .searchform' => 'border-radius: {{VALUE}};',
					'#header {{WRAPPER}}.search-popup .searchform-fields' => 'border-radius: {{VALUE}};',
					'#header {{WRAPPER}} .searchform:not(.search-layout-reveal) input' => 'border-radius: 0 {{VALUE}} {{VALUE}} 0;',
					'#header {{WRAPPER}} .searchform.search-layout-reveal button'  => 'border-radius: 0;',
					'#header {{WRAPPER}} .searchform button' => 'border-radius: max( 0px, calc({{VALUE}} - 5px)) 0 0 max( 0px, calc({{VALUE}} - 5px));',
				);
			}

			global $porto_settings;

			vc_map(
				array(
					'name'        => __( 'Search Form', 'porto-functionality' ),
					'description' => __( 'Show Search Form.', 'porto-functionality' ),
					'base'        => 'porto_hb_search_form',
					'icon'        => PORTO_WIDGET_URL . 'search-form.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_search',
							'text'       => sprintf( esc_html__( 'Please see %1$s\'Theme Options -> Header -> Search Form\'%2$s panel.', 'porto-functionality' ), '<a target="_blank" href="' . porto_get_theme_option_url( 'show-searchform' ) . '">', '</a>' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Is Full Width ?', 'porto-functionality' ),
							'description' => __( 'Turn on to make the search form full-width.', 'porto-functionality' ),
							'param_name'  => 'is_full',
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}}' => 'width: 100%;',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Placeholder Text', 'porto-functionality' ),
							'param_name' => 'placeholder_text',
						),
						array(
							'type'        => 'porto_image_select',
							'heading'     => __( 'Search Layout', 'porto-functionality' ),
							'description' => __( 'Controls the layout of the search forms.', 'porto-functionality' ),
							'param_name'  => 'search_layout',
							'value'       => array(
								'search/search-popup1.svg'   =>  'simple',
								'search/search-popup2.svg'   =>  'large',
								'search/search-reveal.svg'   =>  'reveal',
								'search/search-advanced.svg' =>  'advanced',
								'search/search-overlay.svg'  =>  'overlay',
							),
							'std'         => ! empty( $porto_settings['search-layout'] ) ? $porto_settings['search-layout'] : 'simple',
							'save_always' => true,
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Search Form on Mobile', 'porto-functionality' ),
							'param_name'  => 'show_searchform_mobile',
							'value'       => array(
								__( 'Show', 'porto-functionality' ) => 'show',
								__( 'Hide', 'porto-functionality' ) => 'hide',
							),
							'description' => __( 'Display the full open-text field instead of an icon on mobile.', 'porto-functionality' ),
							'dependency' => array(
								'element' => 'search_layout',
								'value'   => 'advanced',
							),
							'save_always' => true,
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Search Content Type', 'porto-functionality' ),
							'param_name'  => 'search_type',
							'value'       => class_exists( 'WooCommerce' ) ? array(
								__( 'All', 'porto-functionality' )       => 'all',
								__( 'Post', 'porto-functionality' )      => 'post',
								__( 'Product', 'porto-functionality' )   => 'product',
								__( 'Portfolio', 'porto-functionality' ) => 'portfolio',
								__( 'Event', 'porto-functionality' )     => 'event',
							) : array(
								__( 'All', 'porto-functionality' )       => 'all',
								__( 'Post', 'porto-functionality' )      => 'post',
								__( 'Portfolio', 'porto-functionality' ) => 'portfolio',
								__( 'Event', 'porto-functionality' )     => 'event',
							),
							'description' => __( 'Controls the post types that displays in search results.', 'porto-functionality' ),
							'std'         => ! empty( $porto_settings['search-type'] ) ? $porto_settings['search-type'] : 'all',
							'save_always' => true,
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Show Category filter', 'porto-functionality' ),
							'param_name'  => 'category_filter',
							'value'       => array(
								''                                 => '',
								__( 'Yes', 'porto-functionality' ) => 'yes',
								__( 'No', 'porto-functionality' )  => 'no',
							),
							'description' => sprintf( __( 'To show %1$scategory filters%2$s, you must select one of the %3$sSearch Content Type%4$s.', 'porto-functionality' ), '<b>', '</b>', '<a href="' . porto_get_theme_option_url( 'search-type' ) . '" target="_blank">', '</a>' ),
							'std'         => '',
							'dependency' => array(
								'element' => 'search_type',
								'value'   => class_exists( 'WooCommerce' ) ? array( 'post', 'product' ) : 'post',
							),
							'admin_label' => true,
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Show Sub Categories', 'porto-functionality' ),
							'param_name'  => 'sub_cats',
							'dependency'  => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Show Categories on Mobile', 'porto-functionality' ),
							'param_name'  => 'category_filter_mobile',
							'value'       => array(
								''                                 => '',
								__( 'Yes', 'porto-functionality' ) => 'yes',
								__( 'No', 'porto-functionality' )  => 'no',
							),
							'std'         => '',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'category_filter',
								'value'   => array( 'yes' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Popup Position', 'porto-functionality' ),
							'description' => __( 'This works for only "Popup 1" and "Popup 2" and "Form" search layout on mobile.', 'porto-functionality' ),
							'param_name'  => 'popup_pos',
							'value'       => array(
								__( 'Default', 'porto-functionality' ) => '',
								__( 'Left', 'porto-functionality' ) => 'left',
								__( 'Center', 'porto-functionality' ) => 'center',
								__( 'Right', 'porto-functionality' ) => 'right',
							),
							'std'         => '',
							'dependency'  => array(
								'element' => 'search_layout',
								'value'   => array( 'simple', 'large', 'advanced' ),
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Margin', 'porto-functionality' ),
							'description' => __( 'Input units together. e.g: 16px', 'porto-functionality' ),
							'param_name'  => 'wrap_margin',
							'value'       => '',
							'responsive'  => true,
							'selectors'   => array(
								'#header {{WRAPPER}}' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Toggle Icon Size', 'porto-functionality' ),
							'description' => __( 'Input units together. e.g: 16px', 'porto-functionality' ),
							'param_name'  => 'toggle_size',
							'selectors'   => array(
								'#header {{WRAPPER}} .search-toggle' => 'font-size: {{VALUE}};',
							),
							'std'         => '26px',
							'group'       => __( 'Toggle Icon Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Toggle Icon Color', 'porto-functionality' ),
							'param_name' => 'toggle_color',
							'selectors'  => array(
								'#header {{WRAPPER}} .search-toggle' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Toggle Icon Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Toggle Icon Color On Sticky Header', 'porto-functionality' ),
							'param_name' => 'sticky_toggle_color',
							'selectors'  => array(
								'#header.sticky-header {{WRAPPER}} .search-toggle' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Toggle Icon Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'param_name' => 'hover_toggle_color',
							'selectors'  => array(
								'#header {{WRAPPER}} .search-toggle:hover' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Toggle Icon Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Color On Sticky Header', 'porto-functionality' ),
							'param_name' => 'sticky_hover_toggle_color',
							'selectors'  => array(
								'#header.sticky-header {{WRAPPER}} .search-toggle:hover' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Toggle Icon Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Search Form Width (e.g: 100%)', 'porto-functionality' ),
							'param_name' => 'searchform_width',
							'responsive' => true,
							'with_units' => true,
							'selectors'  => array(
								'#header {{WRAPPER}} form.searchform' => 'width: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Search Form Max Width', 'porto-functionality' ),
							'param_name' => 'search_width',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_search_form-search_width.gif"/>',
							'units'      => array( 'px', 'em', '%' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .searchform' => 'max-width: {{VALUE}}{{UNIT}};',
								'#header {{WRAPPER}} .search-layout-advanced' => 'width: 100%;',
								'#header {{WRAPPER}} input' => 'max-width: 100%;',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Height', 'porto-functionality' ),
							'description' => __( 'Controls the height of search form.', 'porto-functionality' ),
							'param_name'  => 'height',
							'selectors'   => array(
								'#header {{WRAPPER}} .searchform input, #header {{WRAPPER}} .searchform select, #header {{WRAPPER}} .searchform .selectric .label, #header {{WRAPPER}} .searchform .selectric, #header {{WRAPPER}} .searchform button' => 'height: {{VALUE}};line-height: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'form_bg_color',
							'heading'     => __( 'Form Background Color', 'porto-functionality' ),
							'selectors'   => array(
								'.fixed-header #header {{WRAPPER}} .searchform, #header {{WRAPPER}} .searchform, .fixed-header #header.sticky-header {{WRAPPER}} .searchform' => 'background-color: {{VALUE}};',
								'#header {{WRAPPER}}.searchform-popup.simple-search-layout .search-toggle:after' => 'border-bottom-color: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_boxshadow',
							'heading'    => __( 'Box Shadow', 'porto-functionality' ),
							'param_name' => 'form_box_shadow',
							'unit'       => 'px',
							'positions'  => array(
								__( 'Horizontal', 'porto-functionality' ) => '',
								__( 'Vertical', 'porto-functionality' ) => '',
								__( 'Blur', 'porto-functionality' )   => '',
								__( 'Spread', 'porto-functionality' ) => '',
							),
							'selectors'  => array(
								'#header {{WRAPPER}}.searchform-popup .searchform',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_search_border',
							'text'       => __( 'Border', 'porto-functionality' ),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Border Width', 'porto-functionality' ),
							'description' => __( 'Controls the border width of the search form. Input units together. e.g: 1px', 'porto-functionality' ),
							'param_name'  => 'border_width',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_search_form-border_width.gif"/>',
							'selectors'   => array(
								'#header {{WRAPPER}} .searchform' => 'border-width: {{VALUE}};',
								'#header {{WRAPPER}}.ssm-advanced-search-layout .searchform' => 'border-width: {{VALUE}};',
								'#header {{WRAPPER}}.search-popup .searchform-fields' => 'border-width: {{VALUE}};',
								'#header {{WRAPPER}} .search-layout-overlay .selectric-cat, #header {{WRAPPER}} .search-layout-overlay .text, #header {{WRAPPER}} .search-layout-overlay .button-wrap' => 'border-width: {{VALUE}};',
								'#header {{WRAPPER}} .search-layout-reveal input' => 'border-bottom-width: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Border Color', 'porto-functionality' ),
							'param_name'  => 'border_color',
							'value'       => '',
							'selectors'   => array(
								'#header {{WRAPPER}} .searchform' => 'border-color: {{VALUE}};',
								'#header {{WRAPPER}} .searchform.search-layout-overlay .selectric-cat' => 'border-color: {{VALUE}};',
								'#header {{WRAPPER}} .searchform.search-layout-overlay .text' => 'border-color: {{VALUE}};',
								'#header {{WRAPPER}} .searchform.search-layout-overlay .button-wrap' => 'border-color: {{VALUE}};',
								'#header {{WRAPPER}}.search-popup .searchform-fields' => 'border-color: {{VALUE}};',
								'#header {{WRAPPER}}.searchform-popup:not(.simple-search-layout) .search-toggle:after' => 'border-bottom-color: {{VALUE}};',
								'#header {{WRAPPER}} .search-layout-reveal input' => 'border-bottom-color: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Border Radius', 'porto-functionality' ),
							'description' => __( 'Controls the border radius of search form.', 'porto-functionality' ),
							'param_name'  => 'border_radius',
							'selectors'   => $border_radius_selectors,
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_search_input',
							'text'       => __( 'Input', 'porto-functionality' ),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Input Typography', 'porto-functionality' ),
							'param_name' => 'input_font',
							'selectors'  => array(
								'#header {{WRAPPER}} .searchform input',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Input Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of Input.', 'porto-functionality' ),
							'param_name'  => 'input_padding',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_search_form-input_padding.gif"/>',
							'selectors'   => array(
								'#header {{WRAPPER}} .searchform input' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'input_color',
							'heading'     => __( 'Input Box Color', 'porto-functionality' ),
							'selectors'   => array(
								'#header {{WRAPPER}} input' => 'color: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'input_placeholder_color',
							'heading'    => __( 'Input Box Placeholder Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} input::placeholder' => 'color: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Input Box Width', 'porto-functionality' ),
							'description' => __( 'Controls the width of the input box in form. Input units together. e.g: 200px', 'porto-functionality' ),
							'param_name'  => 'input_size',
							'selectors'   => array(
								'#header {{WRAPPER}}.searchform-popup .text, #header {{WRAPPER}}.searchform-popup input, #header {{WRAPPER}}.searchform-popup .searchform-cats input' => 'width: {{VALUE}};',
								'#header {{WRAPPER}} input' => 'max-width: {{VALUE}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_search_form_icon',
							'text'       => __( 'Search Icon', 'porto-functionality' ),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Search Icon Size', 'porto-functionality' ),
							'param_name' => 'form_icon_size',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'#header {{WRAPPER}} button' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'form_icon_color',
							'heading'    => __( 'Search Icon Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} button' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'form_icon_bg_color',
							'heading'    => __( 'Search Icon Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} button' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Search Icon Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of search icon.', 'porto-functionality' ),
							'param_name'  => 'form_icon_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .searchform button' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Separator Color', 'porto-functionality' ),
							'param_name' => 'divider_color',
							'value'      => '',
							'selectors'  => array(
								'#header {{WRAPPER}}.searchform-popup input, #header {{WRAPPER}}.searchform-popup select, #header {{WRAPPER}}.searchform-popup .selectric, #header {{WRAPPER}}.searchform-popup .selectric-hover .selectric, #header {{WRAPPER}}.searchform-popup .selectric-open .selectric, #header {{WRAPPER}}.searchform-popup .autocomplete-suggestions, #header {{WRAPPER}}.searchform-popup .selectric-items' => 'border-color: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_close_icon',
							'text'       => __( 'Close Icon', 'porto-functionality' ),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Close Icon Size', 'porto-functionality' ),
							'param_name' => 'close_icon_size',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .btn-close-search-form' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'close_icon_color',
							'heading'    => __( 'Close Icon Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .btn-close-search-form' => 'color: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'close_icon_bg_color',
							'heading'    => __( 'Close Icon Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .btn-close-search-form' => 'background-color: {{VALUE}};',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Close Icon Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of close icon.', 'porto-functionality' ),
							'param_name'  => 'close_icon_padding',
							'selectors'   => array(
								'#header {{WRAPPER}} .btn-close-search-form' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'group'       => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_search_category',
							'text'       => __( 'Category', 'porto-functionality' ),
							'with_group' => true,
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Separator Width', 'porto-functionality' ),
							'param_name' => 'category_inner_width',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_search_form-category_inner_width.gif"/>',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'#header {{WRAPPER}}.searchform-popup .selectric, #header {{WRAPPER}}.simple-popup input, #header {{WRAPPER}}.searchform-popup select' => 'border-right-width: {{VALUE}}{{UNIT}};',
								'#header {{WRAPPER}}.searchform-popup select, #header {{WRAPPER}}.searchform-popup .selectric' => 'border-left-width: {{VALUE}}{{UNIT}};',
								'#header {{WRAPPER}}.simple-popup select, #header {{WRAPPER}}.simple-popup .selectric' => 'border-left-width: 0;',
							),
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Category Width', 'porto-functionality' ),
							'param_name' => 'category_width',
							'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_search_form-category_width.gif"/>',
							'units'      => array( 'px', 'em' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .selectric-cat, #header {{WRAPPER}} select' => 'width: {{VALUE}}{{UNIT}};',
							),
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Category Padding', 'porto-functionality' ),
							'param_name' => 'category_padding',
							'selectors'  => array(
								'#header {{WRAPPER}} .searchform .selectric .label, #header {{WRAPPER}} .searchform select' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Category Typography', 'porto-functionality' ),
							'param_name' => 'category_font',
							'selectors'  => array(
								'{{WRAPPER}} .selectric-cat, #header {{WRAPPER}}.searchform-popup select',
							),
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'category_color',
							'heading'    => __( 'Category Color', 'porto-functionality' ),
							'selectors'  => array(
								'#header {{WRAPPER}} .selectric .label, #header {{WRAPPER}} select' => 'color: {{VALUE}};',
							),
							'dependency' => array(
								'element' => 'category_filter',
								'value'   => 'yes',
							),
							'group'      => __( 'Search Form Style', 'porto-functionality' ),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'CSS', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'Header Social Icons', 'porto-functionality' ),
					'description' => __( 'Displays the social icons for header.', 'porto-functionality' ),
					'base'        => 'porto_hb_social',
					'icon'        => PORTO_WIDGET_URL . 'header-social-icons.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_social_icons',
							'text'       => sprintf( esc_html__( 'Please see %1$s\'Theme Options -> Header -> Social Links\'%2$s panel.', 'porto-functionality' ), '<a target="_blank" href="' . porto_get_theme_option_url( 'show-header-socials' ) . '">', '</a>' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Is Full Width ?', 'porto-functionality' ),
							'description' => __( 'Turn on to make the social icons full-width.', 'porto-functionality' ),
							'param_name'  => 'is_full',
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}}' => 'width: 100%;',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Icon Size (e.g: 12.8px)', 'porto-functionality' ),
							'param_name' => 'icon_size',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Icon Background Size (e.g: 28px)', 'porto-functionality' ),
							'param_name' => 'icon_border_spacing',
						),
						array(
							'type'       => 'porto_dimension',
							'heading'    => __( 'Icon Margin', 'porto-functionality' ),
							'param_name' => 'icon_spacing',
							'selectors'  => array(
								'#header {{WRAPPER}} a' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'param_name' => 'icon_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'param_name' => 'icon_hover_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icon Background Color', 'porto-functionality' ),
							'param_name' => 'icon_color_bg',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icon Hover Background Color', 'porto-functionality' ),
							'param_name' => 'icon_hover_color_bg',
							'value'      => '',
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'dsc_bd',
							'text'       => esc_html__( 'Border Skin', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Icon Border Style', 'porto-functionality' ),
							'param_name' => 'icon_border_style',
							'value'      => array(
								__( 'None', 'porto-functionality' ) => '',
								__( 'Solid', 'porto-functionality' ) => 'solid',
								__( 'Dashed', 'porto-functionality' ) => 'dashed',
								__( 'Dotted', 'porto-functionality' ) => 'dotted',
								__( 'Double', 'porto-functionality' ) => 'double',
								__( 'Inset', 'porto-functionality' ) => 'inset',
								__( 'Outset', 'porto-functionality' ) => 'outset',
							),
							'std'        => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icon Border Color', 'porto-functionality' ),
							'param_name' => 'icon_color_border',
							'dependency' => array(
								'element' => 'icon_border_style',
								'not_empty' => true,
							),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icon Hover Border Color', 'porto-functionality' ),
							'param_name' => 'icon_hover_color_border',
							'dependency' => array(
								'element' => 'icon_border_style',
								'not_empty' => true,
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Icon Border Width (e.g: 1px)', 'porto-functionality' ),
							'param_name' => 'icon_border_size',
							'dependency' => array(
								'element' => 'icon_border_style',
								'not_empty' => true,
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Icon Border Radius (e.g: 50%)', 'porto-functionality' ),
							'param_name' => 'icon_border_radius',
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'dsc_extra',
							'text'       => esc_html__( 'Extra', 'porto-functionality' ),
							'with_group' => true,
						),
						array(
							'type'       => 'porto_boxshadow',
							'heading'    => __( 'Box Shadow', 'porto-functionality' ),
							'param_name' => 'box_shadow',
							'unit'       => 'px',
							'positions'  => array(
								__( 'Horizontal', 'porto-functionality' ) => '',
								__( 'Vertical', 'porto-functionality' ) => '',
								__( 'Blur', 'porto-functionality' )   => '',
								__( 'Spread', 'porto-functionality' ) => '',
							),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'Css', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'Mobile Menu Icon', 'porto-functionality' ),
					'description' => __( 'Show Mobile Toggle Icon.', 'porto-functionality' ),
					'base'        => 'porto_hb_menu_icon',
					'icon'        => PORTO_WIDGET_URL . 'mobile-icon.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'description_mobile_toggle',
							'text'       => sprintf( esc_html__( 'Please see %1$s\'Theme Options -> Menu -> Mobile Menu\'%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'mobile-panel-type' ) . '" target="_blank">', '</a>' ),
						),
						array(
							'type'        => 'dropdown',
							'class'       => '',
							'heading'     => __( 'Icon to display:', 'porto-functionality' ),
							'param_name'  => 'icon_type',
							'value'       => array(
								__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
								__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
								__( 'Porto Icon', 'porto-functionality' ) => 'porto',
							),
							'description' => __( 'Use an existing font icon or upload a custom image.', 'porto-functionality' ),
						),
						array(
							'type'       => 'iconpicker',
							'class'      => '',
							'heading'    => __( 'Icon ', 'porto-functionality' ),
							'param_name' => 'icon_cl',
							'value'      => '',
							'dependency' => array(
								'element' => 'icon_type',
								'value'   => array( 'fontawesome' ),
							),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __( 'Icon', 'porto-functionality' ),
							'param_name' => 'icon_simpleline',
							'settings'   => array(
								'type'         => 'simpleline',
								'iconsPerPage' => 4000,
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value'   => 'simpleline',
							),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __( 'Icon', 'porto-functionality' ),
							'param_name' => 'icon_porto',
							'settings'   => array(
								'type'         => 'porto',
								'iconsPerPage' => 4000,
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value'   => 'porto',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Icon Size(e.g: 13px)', 'porto-functionality' ),
							'param_name' => 'size',
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Background Color', 'porto-functionality' ),
							'param_name'  => 'bg_color',
							'value'       => '',
							'description' => __( 'Controls the background color of the mobile toggle button.', 'porto-functionality' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Icon Color', 'porto-functionality' ),
							'param_name'  => 'color',
							'value'       => '',
							'description' => __( 'Controls the icon color of the mobile toggle button.', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding value of mobile icon.', 'porto-functionality' ),
							'param_name'  => 'icon_padding',
							'selectors'   => array(
								'{{WRAPPER}}.mobile-toggle' => 'padding-top:{{TOP}}!important; padding-right:{{RIGHT}}!important; padding-bottom:{{BOTTOM}}!important; padding-left: {{LEFT}}!important;',
							),
						),
						$custom_class,
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'Css', 'porto-functionality' ),
							'param_name'       => 'css',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
					),
				)
			);

			vc_map(
				array(
					'name'        => __( 'Vertical Divider', 'porto-functionality' ),
					'description' => __( 'Vertical Separator Line.', 'porto-functionality' ),
					'base'        => 'porto_hb_divider',
					'icon'        => PORTO_WIDGET_URL . 'v-divider.png',
					'class'       => 'porto-wpb-widget',
					'category'    => __( 'Header Builder', 'porto-functionality' ),
					'params'      => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Width (e.g: 1px)', 'porto-functionality' ),
							'param_name' => 'width',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Height (e.g: 15px)', 'porto-functionality' ),
							'param_name' => 'height',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'param_name' => 'color',
							'value'      => '',
						),
						$custom_class,
					),
				)
			);

			if ( class_exists( 'Woocommerce' ) ) {
				vc_map(
					array(
						'name'        => __( 'Mini Cart', 'porto-functionality' ),
						'description' => __( 'Displays the mini cart.', 'porto-functionality' ),
						'base'        => 'porto_hb_mini_cart',
						'icon'        => PORTO_WIDGET_URL . 'mini-cart.png',
						'class'       => 'porto-wpb-widget',
						'category'    => __( 'Header Builder', 'porto-functionality' ),
						'params'      => array(
							array(
								'type'        => 'porto_image_select',
								'heading'     => __( 'Type', 'porto-functionality' ),
								'description' => __( 'Controls the cart type.', 'porto-functionality' ),
								'param_name'  => 'type',
								'value'       => array(
									'cart/cart-simple.svg'    => 'simple',
									'cart/cart-arrow-alt.svg' => 'minicart-arrow-alt',
									'cart/cart-text.svg'      => 'minicart-inline',
									'cart/cart-icon-text.svg' => 'minicart-text',
								),
								'std'         => 'simple',
								'save_always' => true,
								'admin_label' => true,
							),

							array(
								'type'        => 'porto_image_select',
								'heading'     => __( 'Content Type (Popup or Off-Canvas)', 'porto-functionality' ),
								'param_name'  => 'content_type',
								'value'       => array(
									'cart/cart-popup.svg'     => '',
									'cart/cart-offcanvas.svg' => 'offcanvas',
								),
								'std'         => '',
								'admin_label' => true,
								'description' => __( 'Controls the content type: dropdown or offcanvas.', 'porto-functionality' ),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Icon to display:', 'porto-functionality' ),
								'param_name'  => 'icon_type',
								'value'       => array(
									__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
									__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
									__( 'Porto Icon', 'porto-functionality' ) => 'porto',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'description' => __( 'Use an existing font icon or upload a custom image.', 'porto-functionality' ),
							),
							array(
								'type'       => 'iconpicker',
								'class'      => '',
								'heading'    => __( 'Icon ', 'porto-functionality' ),
								'param_name' => 'icon_cl',
								'value'      => '',
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => array( 'fontawesome' ),
								),
							),
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'porto-functionality' ),
								'param_name' => 'icon_simpleline',
								'settings'   => array(
									'type'         => 'simpleline',
									'iconsPerPage' => 4000,
								),
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => 'simpleline',
								),
							),
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'porto-functionality' ),
								'param_name' => 'icon_porto',
								'settings'   => array(
									'type'         => 'porto',
									'iconsPerPage' => 4000,
								),
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => 'porto',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => __( 'Icon Size', 'porto-functionality' ),
								'param_name'  => 'icon_size',
								'std'         => '26px',
								'save_always' => true,
								'description' => __( 'Controls the size of the mini cart icon.', 'porto-functionality' ),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'        => 'colorpicker',
								'heading'     => __( 'Icon Color', 'porto-functionality' ),
								'param_name'  => 'icon_color',
								'description' => __( 'Controls the color of the mini cart icon.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}}#mini-cart .minicart-icon, {{WRAPPER}}#mini-cart.minicart-arrow-alt .cart-head:after' => 'color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'        => 'colorpicker',
								'heading'     => __( 'Icon Color On Sticky', 'porto-functionality' ),
								'param_name'  => 'sticky_icon_color',
								'description' => __( 'Controls the color of the mini cart icon on sticky header.', 'porto-functionality' ),
								'selectors'   => array(
									'.sticky-header {{WRAPPER}}#mini-cart .minicart-icon, .sticky-header {{WRAPPER}}#mini-cart.minicart-arrow-alt .cart-head:after' => 'color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Hover Icon Color', 'porto-functionality' ),
								'param_name' => 'hover_icon_color',
								'selectors'   => array(
									'{{WRAPPER}}#mini-cart:hover .minicart-icon, {{WRAPPER}}#mini-cart.minicart-arrow-alt:hover .cart-head:after' => 'color: {{VALUE}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Icon Hover Color On Sticky', 'porto-functionality' ),
								'param_name' => 'sticky_hover_icon_color',
								'selectors'   => array(
									'.sticky-header {{WRAPPER}}#mini-cart:hover .minicart-icon, .sticky-header {{WRAPPER}}#mini-cart.minicart-arrow-alt:hover .cart-head:after' => 'color: {{VALUE}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Content Border Color', 'porto-functionality' ),
								'param_name' => 'icon_border_color',
								'description' => __( 'Controls the border color of cart content.', 'porto-functionality' ),
								'hint'        => '<img src="' . PORTO_HINT_URL . 'minicart-popup-border-color.gif"/>',
								'selectors'   => array(
									'{{WRAPPER}}#mini-cart .cart-popup' => 'border: 1px solid {{VALUE}};',
									'{{WRAPPER}}#mini-cart .cart-icon:after, {{WRAPPER}}#mini-cart.minicart-text .cart-head:after' => 'border-color: {{VALUE}};',
								),
							),
							array(
								'type'        => 'colorpicker',
								'heading'     => __( 'Content Border Color On Sticky', 'porto-functionality' ),
								'param_name'  => 'sticky_icon_border_color',
								'description' => __( 'Controls the border color of cart content.', 'porto-functionality' ),
								'hint'        => '<img src="' . PORTO_HINT_URL . 'minicart-popup-border-color.gif"/>',
								'selectors'   => array(
									'.sticky-header {{WRAPPER}}#mini-cart .cart-popup' => 'border: 1px solid {{VALUE}};',
									'.sticky-header {{WRAPPER}}#mini-cart .cart-icon:after, {{WRAPPER}}#mini-cart.minicart-text .cart-head:after' => 'border-color: {{VALUE}};',
								),
							),

							array(
								'type'        => 'colorpicker',
								'param_name'  => 'icon_item_color',
								'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_mini_cart-badge.gif"/>',
								'heading'     => __( 'Badge Color', 'porto-functionality' ),
								'description' => __( 'Controls the the color of badge.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}}#mini-cart .cart-items, {{WRAPPER}}#mini-cart .cart-items-text' => 'color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'       => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'icon_item_bg_color',
								'heading'     => __( 'Badge Background Color', 'porto-functionality' ),
								'description' => __( 'Controls the the background color of badge.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}}#mini-cart .cart-items' => 'background-color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'       => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'sticky_icon_item_color',
								'heading'     => __( 'Badge Color on Sticky Header', 'porto-functionality' ),
								'selectors'   => array(
									'.sticky-header {{WRAPPER}}#mini-cart .cart-items, .sticky-header {{WRAPPER}}#mini-cart .cart-items-text' => 'color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'       => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'sticky_icon_item_bg_color',
								'heading'     => __( 'Badge Background Color on Sticky Header', 'porto-functionality' ),
								'selectors'   => array(
									'.sticky-header {{WRAPPER}}#mini-cart .cart-items' => 'background-color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'       => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'       => 'porto_number',
								'heading'    => __( 'Size', 'porto-functionality' ),
								'param_name' => 'icon_item_size',
								'units'      => array( 'px', 'em' ),
								'selectors'  => array(
									'{{WRAPPER}}#mini-cart .cart-items' => 'font-size: {{VALUE}}{{UNIT}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'      => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'       => 'porto_number',
								'heading'    => __( 'Background Size', 'porto-functionality' ),
								'param_name' => 'icon_item_bg_size',
								'units'      => array( 'px', 'em' ),
								'selectors'  => array(
									'{{WRAPPER}}#mini-cart .cart-items' => '--porto-badge-size: {{VALUE}}{{UNIT}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'      => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'       => 'porto_number',
								'heading'    => __( 'Badge Right Position', 'porto-functionality' ),
								'param_name' => 'icon_item_right',
								'units'      => array( 'px', 'em' ),
								'selectors'  => array(
									'{{WRAPPER}}#mini-cart .cart-items' => ( is_rtl() ? 'left' : 'right' ) . ':{{VALUE}}{{UNIT}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'      => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'       => 'porto_number',
								'heading'    => __( 'Badge Top Position', 'porto-functionality' ),
								'param_name' => 'icon_item_top',
								'units'      => array( 'px', 'em' ),
								'selectors'  => array(
									'{{WRAPPER}}#mini-cart .cart-items' => 'top:{{VALUE}}{{UNIT}};',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
								'group'      => __( 'Cart Badge', 'porto-functionality' ),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Icon Margin Left (e.g: 15px)', 'porto-functionality' ),
								'param_name' => 'icon_ml',
								'value'      => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Icon Margin Right (e.g: 15px)', 'porto-functionality' ),
								'param_name' => 'icon_mr',
								'value'      => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'simple', 'minicart-arrow-alt', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Text Font Size', 'porto-functionality' ),
								'param_name' => 'text_font_size',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Text Font Weight', 'porto-functionality' ),
								'param_name' => 'text_font_weight',
								'value'      => array(
									__( 'Default', 'porto-functionality' ) => '',
									'200' => '200',
									'300' => '300',
									'400' => '400',
									'500' => '500',
									'600' => '600',
									'700' => '700',
									'800' => '800',
								),
								'std'        => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Text Transform', 'porto-functionality' ),
								'param_name' => 'text_transform',
								'value'      => array(
									__( 'Default', 'porto-functionality' ) => '',
									__( 'None', 'porto-functionality' ) => 'none',
									__( 'Uppercase', 'porto-functionality' ) => 'uppercase',
									__( 'Capitalize', 'porto-functionality' ) => 'capitalize',
									__( 'Lowercase', 'porto-functionality' ) => 'lowercase',
								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Text Line Height', 'porto-functionality' ),
								'param_name' => 'text_line_height',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Text Letter Spacing', 'porto-functionality' ),
								'param_name' => 'text_ls',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Text Color', 'porto-functionality' ),
								'param_name' => 'text_color',
								'value'      => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Price Font Size', 'porto-functionality' ),
								'param_name' => 'price_font_size',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Price Font Weight', 'porto-functionality' ),
								'param_name' => 'price_font_weight',
								'value'      => array(
									__( 'Default', 'porto-functionality' ) => '',
									'200' => '200',
									'300' => '300',
									'400' => '400',
									'500' => '500',
									'600' => '600',
									'700' => '700',
									'800' => '800',
								),
								'std'        => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Price Line Height', 'porto-functionality' ),
								'param_name' => 'price_line_height',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Price Letter Spacing', 'porto-functionality' ),
								'param_name' => 'price_ls',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Price Color', 'porto-functionality' ),
								'param_name' => 'price_color',
								'value'      => '',
								'dependency' => array(
									'element' => 'type',
									'value'   => array( 'minicart-inline', 'minicart-text' ),
								),
							),
							$custom_class,
							array(
								'type'             => 'css_editor',
								'heading'          => __( 'CSS', 'porto-functionality' ),
								'param_name'       => 'css',
								'group'            => __( 'Design', 'porto-functionality' ),
								'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
							),
						),
					)
				);

				vc_map(
					array(
						'name'        => __( 'My Account Icon', 'porto-functionality' ),
						'description' => __( 'Show User Account Icon.', 'porto-functionality' ),
						'base'        => 'porto_hb_myaccount',
						'icon'        => PORTO_WIDGET_URL . 'account.png',
						'class'       => 'porto-wpb-widget',
						'category'    => __( 'Header Builder', 'porto-functionality' ),
						'params'      => array(
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Icon to display:', 'porto-functionality' ),
								'param_name'  => 'icon_type',
								'value'       => array(
									__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
									__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
									__( 'Porto Icon', 'porto-functionality' ) => 'porto',
								),
								'description' => __( 'Use an existing font icon or upload a custom image.', 'porto-functionality' ),
							),
							array(
								'type'       => 'iconpicker',
								'class'      => '',
								'heading'    => __( 'Icon ', 'porto-functionality' ),
								'param_name' => 'icon_cl',
								'value'      => '',
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => array( 'fontawesome' ),
								),
							),
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'porto-functionality' ),
								'param_name' => 'icon_simpleline',
								'settings'   => array(
									'type'         => 'simpleline',
									'iconsPerPage' => 4000,
								),
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => 'simpleline',
								),
							),
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'porto-functionality' ),
								'param_name' => 'icon_porto',
								'settings'   => array(
									'type'         => 'porto',
									'iconsPerPage' => 4000,
								),
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => 'porto',
								),
							),

							array(
								'type'       => 'textfield',
								'heading'    => __( 'Icon Size (e.g: 26px)', 'porto-functionality' ),
								'param_name' => 'size',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Color', 'porto-functionality' ),
								'param_name' => 'color',
								'selectors'   => array(
									'#header {{WRAPPER}}, #header {{WRAPPER}} .my-account' => 'color: {{VALUE}};',
								),
								'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Color On Sticky', 'porto-functionality' ),
								'param_name' => 'sticky_color',
								'selectors'   => array(
									'#header.sticky-header {{WRAPPER}}, #header.sticky-header {{WRAPPER}} .my-account' => 'color: {{VALUE}};',
								),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Hover Color', 'porto-functionality' ),
								'param_name' => 'hover_color',
								'selectors'   => array(
									'#header {{WRAPPER}}:hover, #header {{WRAPPER}} .my-account:hover' => 'color: {{VALUE}};',
								),
								'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Hover Color On Sticky', 'porto-functionality' ),
								'param_name' => 'sticky_hover_color',
								'selectors'   => array(
									'#header.sticky-header {{WRAPPER}}:hover, #header.sticky-header {{WRAPPER}} .my-account:hover' => 'color: {{VALUE}};',
								),
							),
							array(
								'type'        => 'checkbox',
								'heading'     => __( 'Show Account Dropdown', 'porto-functionality' ),
								'description' => sprintf( __( 'When the user is logged in, the Menu that is located in the %1$sAccount Menu%2$s will be shown.', 'porto-functionality' ), '<a href="' . esc_url( admin_url( 'nav-menus.php#locations-account_menu' ) ) . '" target="_blank">', '</a>' ),
								'value'       => array( __( 'Yes, please', 'js_composer' ) => 'yes' ),
								'param_name'  => 'account_dropdown',
								'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_myaccount-dropdown.jpg"/>',
								'selectors'   => array(
									'{{WRAPPER}}.account-dropdown > li.has-sub > a::after' => 'font-size: 12px;vertical-align: middle;',
									'{{WRAPPER}}.account-dropdown > li.menu-item > a'      => 'padding: 0;',
									'{{WRAPPER}}.account-dropdown > li.menu-item > a > i'  => 'width: auto;',
								),
							),
							array(
								'type'        => 'porto_number',
								'heading'     => __( 'Spacing Between Icon and Arrow', 'porto-functionality' ),
								'param_name'  => 'spacing',
								'units'       => array( 'px', 'em' ),
								'selectors'   => array(
									'#header {{WRAPPER}}.account-dropdown > li.menu-item > a > i' => "margin-{$right}: {{VALUE}}{{UNIT}};",
								),
								'dependency'  => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'porto_typography',
								'heading'    => __( 'Account Dropdown Font', 'porto-functionality' ),
								'param_name' => 'account_menu_font',
								'selectors'  => array(
									'#header {{WRAPPER}} .sub-menu li.menu-item > a',
								),
								'dependency' => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'account_dropdown_bgc',
								'heading'     => __( 'Background Color', 'porto-functionality' ),
								'description' => __( 'Controls the background color for account dropdown.', 'porto-functionality' ),
								'value'       => '#fff',
								'selectors'   => array(
									'{{WRAPPER}}.account-dropdown .narrow ul.sub-menu' => 'background-color: {{VALUE}};',
									'{{WRAPPER}}.account-dropdown>li.has-sub:before, {{WRAPPER}}.account-dropdown>li.has-sub:after' => 'border-bottom-color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'account_dropdown_hbgc',
								'heading'     => __( 'Hover Background Color', 'porto-functionality' ),
								'description' => __( 'Controls the background color for account dropdown item on hover.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}}.account-dropdown .sub-menu li.menu-item:hover > a, {{WRAPPER}}.account-dropdown .sub-menu li.menu-item.active > a, {{WRAPPER}}.account-dropdown .sub-menu li.menu-item.is-active > a' => 'background-color: {{VALUE}};',
								),
								'value'       => '#f3f3f3',
								'save_always' => true,
								'dependency'  => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'account_dropdown_lc',
								'heading'     => __( 'Link Color', 'porto-functionality' ),
								'description' => __( 'Controls the link color for account dropdown.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}} .sub-menu li.menu-item:before, {{WRAPPER}} .sub-menu li.menu-item > a' => 'color: {{VALUE}};',
								),
								'value'       => '#777',
								'dependency'  => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							array(
								'type'        => 'colorpicker',
								'param_name'  => 'account_dropdown_hlc',
								'heading'     => __( 'Link Hover Color', 'porto-functionality' ),
								'description' => __( 'Controls the link hover color for account dropdown.', 'porto-functionality' ),
								'selectors'   => array(
									'{{WRAPPER}}.account-dropdown .sub-menu li.menu-item:hover > a, {{WRAPPER}}.account-dropdown .sub-menu li.menu-item.active > a, {{WRAPPER}}.account-dropdown .sub-menu li.menu-item.is-active > a' => 'color: {{VALUE}};',
								),
								'dependency'  => array(
									'element' => 'account_dropdown',
									'value'   => 'yes',
								),
							),
							$custom_class,
							array(
								'type'             => 'css_editor',
								'heading'          => __( 'CSS', 'porto-functionality' ),
								'param_name'       => 'css',
								'group'            => __( 'Design', 'porto-functionality' ),
								'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
							),
						),
					)
				);

				if ( class_exists( 'YITH_WCWL' ) ) {
					vc_map(
						array(
							'name'        => __( 'Wishlist Icon', 'porto-functionality' ),
							'description' => __( 'Show YITH Wishlist Icon.', 'porto-functionality' ),
							'base'        => 'porto_hb_wishlist',
							'icon'        => PORTO_WIDGET_URL . 'wishlist.png',
							'class'       => 'porto-wpb-widget',
							'category'    => __( 'Header Builder', 'porto-functionality' ),
							'params'      => array(
								array(
									'type'       => 'porto_param_heading',
									'param_name' => 'dsc_icon',
									'text'       => esc_html__( 'Icon Skin', 'porto-functionality' ),
									'with_group' => true,
								),
								array(
									'type'        => 'dropdown',
									'class'       => '',
									'heading'     => __( 'Icon to display:', 'porto-functionality' ),
									'param_name'  => 'icon_type',
									'value'       => array(
										__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
										__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
										__( 'Porto Icon', 'porto-functionality' ) => 'porto',
									),
									'description' => __( 'Use an existing font icon or upload a custom image.', 'porto-functionality' ),
								),
								array(
									'type'       => 'iconpicker',
									'class'      => '',
									'heading'    => __( 'Icon ', 'porto-functionality' ),
									'param_name' => 'icon_cl',
									'value'      => '',
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => array( 'fontawesome' ),
									),
								),
								array(
									'type'       => 'iconpicker',
									'heading'    => __( 'Icon', 'porto-functionality' ),
									'param_name' => 'icon_simpleline',
									'settings'   => array(
										'type'         => 'simpleline',
										'iconsPerPage' => 4000,
									),
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => 'simpleline',
									),
								),
								array(
									'type'       => 'iconpicker',
									'heading'    => __( 'Icon', 'porto-functionality' ),
									'param_name' => 'icon_porto',
									'settings'   => array(
										'type'         => 'porto',
										'iconsPerPage' => 4000,
									),
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => 'porto',
									),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Icon Size (e.g: 26px)', 'porto-functionality' ),
									'param_name' => 'size',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Color', 'porto-functionality' ),
									'param_name' => 'color',
									'selectors'   => array(
										'#header {{WRAPPER}}, #header {{WRAPPER}} .my-wishlist' => 'color: {{VALUE}};',
									),
									'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),	
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Color On Sticky Header', 'porto-functionality' ),
									'param_name' => 'sticky_color',
									'selectors'   => array(
										'#header.sticky-header {{WRAPPER}}, #header.sticky-header {{WRAPPER}} .my-wishlist' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Hover Color', 'porto-functionality' ),
									'param_name' => 'hover_color',
									'selectors'   => array(
										'#header {{WRAPPER}}:hover, #header {{WRAPPER}} .my-wishlist:hover' => 'color: {{VALUE}};',
									),
									'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),	
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Hover Color On Sticky Header', 'porto-functionality' ),
									'param_name' => 'sticky_hover_color',
									'selectors'   => array(
										'#header.sticky-header {{WRAPPER}}:hover, #header.sticky-header {{WRAPPER}} .my-wishlist:hover' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'porto_param_heading',
									'param_name' => 'dsc_badge',
									'text'       => esc_html__( 'Badge Skin', 'porto-functionality' ),
									'with_group' => true,
								),
								array(
									'type'        => 'colorpicker',
									'param_name'  => 'badge_color',
									'heading'     => __( 'Badge Color', 'porto-functionality' ),
									'description' => __( 'Controls the the color of badge.', 'porto-functionality' ),
									'selectors'   => array(
										'{{WRAPPER}} .wishlist-count' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'        => 'colorpicker',
									'param_name'  => 'badge_bg_color',
									'heading'     => __( 'Badge Background Color', 'porto-functionality' ),
									'description' => __( 'Controls the the background color of badge.', 'porto-functionality' ),
									'selectors'   => array(
										'{{WRAPPER}} .wishlist-count' => 'background-color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'porto_param_heading',
									'param_name' => 'dsc_extra',
									'text'       => esc_html__( 'Extra', 'porto-functionality' ),
									'with_group' => true,
								),
								array(
									'type'        => 'checkbox',
									'heading'     => __( 'Off-Canvas ?', 'porto-functionality' ),
									'description' => __( 'Controls to show the wishlist dropdown as Off-Canvas.', 'porto-functionality' ),
									'param_name'  => 'offcanvas',
									'hint'        => '<img src="' . PORTO_HINT_URL . 'hb_wishlist-offcanvas.gif"/>',
								),
								$custom_class,
								array(
									'type'             => 'css_editor',
									'heading'          => __( 'CSS', 'porto-functionality' ),
									'param_name'       => 'css',
									'group'            => __( 'Design', 'porto-functionality' ),
									'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
								),
							),
						)
					);
				}

				if ( defined( 'YITH_WOOCOMPARE' ) ) {
					vc_map(
						array(
							'name'        => __( 'Compare Icon', 'porto-functionality' ),
							'description' => __( 'Show YITH Compare Icon.', 'porto-functionality' ),
							'base'        => 'porto_hb_compare',
							'icon'        => PORTO_WIDGET_URL . 'compare.png',
							'class'       => 'porto-wpb-widget',
							'category'    => __( 'Header Builder', 'porto-functionality' ),
							'params'      => array(
								array(
									'type'        => 'dropdown',
									'class'       => '',
									'heading'     => __( 'Icon to display:', 'porto-functionality' ),
									'param_name'  => 'icon_type',
									'value'       => array(
										__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
										__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
										__( 'Porto Icon', 'porto-functionality' ) => 'porto',
									),
									'description' => __( 'Use an existing font icon.', 'porto-functionality' ),
								),
								array(
									'type'       => 'iconpicker',
									'class'      => '',
									'heading'    => __( 'Icon ', 'porto-functionality' ),
									'param_name' => 'icon_cl',
									'value'      => '',
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => array( 'fontawesome' ),
									),
								),
								array(
									'type'       => 'iconpicker',
									'heading'    => __( 'Icon', 'porto-functionality' ),
									'param_name' => 'icon_simpleline',
									'settings'   => array(
										'type'         => 'simpleline',
										'iconsPerPage' => 4000,
									),
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => 'simpleline',
									),
								),
								array(
									'type'       => 'iconpicker',
									'heading'    => __( 'Icon', 'porto-functionality' ),
									'param_name' => 'icon_porto',
									'settings'   => array(
										'type'         => 'porto',
										'iconsPerPage' => 4000,
									),
									'dependency' => array(
										'element' => 'icon_type',
										'value'   => 'porto',
									),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Icon Size (e.g: 26px)', 'porto-functionality' ),
									'param_name' => 'size',
								),
								array(
									'type'        => 'checkbox',
									'heading'     => __( 'Show Label', 'porto-functionality' ),
									'description' => __( 'Show/Hide the compare label.', 'porto-functionality' ),
									'param_name'  => 'show_label',
									'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
								),
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Label', 'porto-functionality' ),
									'param_name'  => 'compare_label',
									'dependency'  => array(
										'element' => 'show_label',
										'value'   => 'yes',
									),
								),
								array(
									'type'       => 'porto_typography',
									'heading'    => __( 'Label Typography', 'porto-functionality' ),
									'param_name' => 'label_typography',
									'responsive' => true,
									'selectors'  => array(
										'{{WRAPPER}} span.hicon-label',
									),
									'dependency'  => array(
										'element' => 'show_label',
										'value'   => 'yes',
									),
								),
								array(
									'type'        => 'porto_number',
									'heading'     => __( 'Between Spacing', 'porto-functionality' ),
									'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
									'param_name'  => 'icon_space',
									'units'       => array( 'px', 'rem', 'em' ),
									'selectors'   => array(
										'{{WRAPPER}} .compare-icon' => "margin-{$right}: {{VALUE}}{{UNIT}};",
									),
									'dependency'  => array(
										'element' => 'show_label',
										'value'   => 'yes',
									),
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Color', 'porto-functionality' ),
									'param_name' => 'color',
									'selectors'   => array(
										'#header {{WRAPPER}}' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Color On Sticky Header', 'porto-functionality' ),
									'param_name' => 'sticky_color',
									'selectors'   => array(
										'#header.sticky-header {{WRAPPER}}' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Hover Color', 'porto-functionality' ),
									'param_name' => 'hover_color',
									'selectors'   => array(
										'#header {{WRAPPER}}:hover' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Hover Color On Sticky Header', 'porto-functionality' ),
									'param_name' => 'sticky_hover_color',
									'selectors'   => array(
										'#header.sticky-header {{WRAPPER}}:hover' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'        => 'colorpicker',
									'param_name'  => 'badge_color',
									'heading'     => __( 'Badge Color', 'porto-functionality' ),
									'description' => __( 'Controls the the color of badge.', 'porto-functionality' ),
									'selectors'   => array(
										'{{WRAPPER}} .compare-count' => 'color: {{VALUE}};',
									),
								),
								array(
									'type'        => 'colorpicker',
									'param_name'  => 'badge_bg_color',
									'heading'     => __( 'Badge Background Color', 'porto-functionality' ),
									'description' => __( 'Controls the the background color of badge.', 'porto-functionality' ),
									'selectors'   => array(
										'{{WRAPPER}} .compare-count' => 'background-color: {{VALUE}};',
									),
								),
								$custom_class,
								array(
									'type'             => 'css_editor',
									'heading'          => __( 'CSS', 'porto-functionality' ),
									'param_name'       => 'css_design',
									'group'            => __( 'Design', 'porto-functionality' ),
									'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
								),
							),
						)
					);
				}
			}
		}

		/**
		 * Save shortcode css to post meta in gutenberg editor
		 *
		 * @since 2.1.0
		 */
		public function add_internal_dynamic_css( $post_id, $post ) {
			
			if ( ! $post || ! isset( $post->post_type ) || PortoBuilders::BUILDER_SLUG != $post->post_type || ! $post->post_content || 'header' != get_post_meta( $post_id, PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			if ( defined( 'WPB_VC_VERSION' ) && false !== strpos( $post->post_content, '[porto_hb_' ) ) {
				ob_start();
				$css = '';
				preg_match_all( '/' . get_shortcode_regex( array( 'porto_hb_menu', 'porto_hb_search_form', 'porto_hb_mini_cart', 'porto_hb_social', 'porto_hb_menu_icon', 'porto_hb_switcher' ) ) . '/', $post->post_content, $shortcodes );
				foreach ( $shortcodes[2] as $index => $tag ) {
					$atts = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
					include PORTO_BUILDERS_PATH . '/elements/header/wpb/style-' . str_replace( array( 'porto_hb_', '_' ), array( '', '-' ), $tag ) . '.php';
				}
				$css = ob_get_clean();
				if ( $css ) {
					$result = update_post_meta( $post_id, 'porto_builder_css', wp_strip_all_tags( $css ) );
				} else {
					delete_post_meta( $post_id, 'porto_builder_css' );
				}
			} elseif ( false !== strpos( $post->post_content, '<!-- wp:porto-hb' ) ) { // Gutenberg editor
				
				$blocks = parse_blocks( $post->post_content );
				if ( ! empty( $blocks ) ) {
					ob_start();
					$css = '';
					$this->include_style( $blocks );
					$css = ob_get_clean();
					if ( $css ) {
						update_post_meta( $post_id, 'porto_builder_css', wp_strip_all_tags( $css ) );
					} else {
						delete_post_meta( $post_id, 'porto_builder_css' );
					}
				}
			}
		}

		public function include_style( $blocks ) {
			if ( empty( $blocks ) ) {
				return;
			}
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) && in_array( $block['blockName'], array( 'porto-hb/porto-menu', 'porto-hb/porto-switcher', 'porto-hb/porto-search-form', 'porto-hb/porto-mini-cart', 'porto-hb/porto-social', 'porto-hb/porto-menu-icon' ) ) ) {
					$atts                 = empty( $block['attrs'] ) ? array() : $block['attrs'];
					$atts['page_builder'] = 'gutenberg';
					include PORTO_BUILDERS_PATH . '/elements/header/wpb/style-' . str_replace( 'porto-hb/porto-', '', $block['blockName'] ) . '.php';
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$this->include_style( $block['innerBlocks'] );
				}
			}
		}

		/**
		 * Load gutenberg header builder blocks
		 *
		 * @since 2.1.0
		 */
		private function add_gutenberg_elements() {

			$load_blocks = false;
			if ( is_admin() ) {
				// Header Builder
				if ( ( PortoBuilders::BUILDER_SLUG ) && isset( $_REQUEST['post'] ) && 'header' == get_post_meta( $_REQUEST['post'], PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
					$load_blocks = true;
				}

				// Gutenberg Full Site Editing
				// global $porto_settings;
				// if ( ! defined( 'ELEMENTOR_VERSION' ) && ! defined( 'WPB_VC_VERSION' ) && ! empty( $porto_settings['enable-gfse'] ) ) {
				// 	$load_blocks = true;
				// }
			}

			if ( $load_blocks ) {
				add_action(
					'enqueue_block_editor_assets',
					function () {
						wp_enqueue_script( 'porto-hb-blocks', PORTO_FUNC_URL . 'builders/elements/header/gutenberg/blocks.min.js', array( 'porto_blocks' ), PORTO_FUNC_VERSION, true );
					},
					999
				);
				add_filter(
					'block_categories_all',
					function ( $categories ) {
						return array_merge(
							$categories,
							array(
								array(
									'slug'  => 'porto-hb',
									'title' => __( 'Porto Header Blocks', 'porto-functionality' ),
									'icon'  => '',
								),
							)
						);
					},
					11,
					1
				);
			}

			register_block_type(
				'porto-hb/porto-logo',
				array(
					'attributes'      => array(
						'className'    => array(
							'type' => 'string',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_logo',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-menu',
				array(
					'attributes'      => array(
						'location'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'title'          => array(
							'type' => 'string',
						),
						'font_size'      => array(
							'type' => 'string',
						),
						'font_weight'    => array(
							'type' => 'integer',
						),
						'text_transform' => array(
							'type' => 'string',
						),
						'line_height'    => array(
							'type' => 'string',
						),
						'letter_spacing' => array(
							'type' => 'string',
						),
						'color'          => array(
							'type' => 'string',
						),
						'padding'        => array(
							'type' => 'string',
						),
						'bgcolor'        => array(
							'type' => 'string',
						),
						'hover_color'    => array(
							'type' => 'string',
						),
						'hover_bgcolor'  => array(
							'type' => 'string',
						),
						'popup_width'    => array(
							'type' => 'string',
						),
						'page_builder'   => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_menu',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-switcher',
				array(
					'attributes'      => array(
						'type'           => array(
							'type'    => 'string',
							'default' => '',
						),
						'font_size'      => array(
							'type' => 'string',
						),
						'font_weight'    => array(
							'type' => 'integer',
						),
						'text_transform' => array(
							'type' => 'string',
						),
						'line_height'    => array(
							'type' => 'string',
						),
						'letter_spacing' => array(
							'type' => 'string',
						),
						'color'          => array(
							'type' => 'string',
						),
						'hover_color'    => array(
							'type' => 'string',
						),
						'page_builder'   => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_switcher',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-search-form',
				array(
					'attributes'      => array(
						'placeholder_text'       => array(
							'type' => 'string',
						),
						'category_filter'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'category_filter_mobile' => array(
							'type'    => 'string',
							'default' => '',
						),
						'popup_pos'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'toggle_size'            => array(
							'type' => 'string',
						),
						'toggle_color'           => array(
							'type' => 'string',
						),
						'input_size'             => array(
							'type' => 'string',
						),
						'height'                 => array(
							'type' => 'string',
						),
						'border_width'           => array(
							'type' => 'integer',
						),
						'border_color'           => array(
							'type' => 'string',
						),
						'border_radius'          => array(
							'type' => 'string',
						),
						'page_builder'           => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_search_form',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-mini-cart',
				array(
					'attributes'      => array(
						'type'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'content_type'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_cl'           => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_size'         => array(
							'type' => 'string',
						),
						'icon_color'        => array(
							'type' => 'string',
						),
						'icon_margin_left'  => array(
							'type' => 'string',
						),
						'icon_margin_right' => array(
							'type' => 'string',
						),
						'text_font_size'    => array(
							'type' => 'string',
						),
						'text_font_weight'  => array(
							'type' => 'integer',
						),
						'text_transform'    => array(
							'type' => 'string',
						),
						'text_line_height'  => array(
							'type' => 'string',
						),
						'text_ls'           => array(
							'type' => 'string',
						),
						'text_color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'price_font_size'   => array(
							'type' => 'string',
						),
						'price_font_weight' => array(
							'type' => 'integer',
						),
						'price_line_height' => array(
							'type' => 'string',
						),
						'price_ls'          => array(
							'type' => 'string',
						),
						'price_color'       => array(
							'type' => 'string',
						),
						'page_builder'      => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_mini_cart',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-social',
				array(
					'attributes'      => array(
						'icon_size'           => array(
							'type' => 'string',
						),
						'icon_color'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_hover_color'    => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_color_bg'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_hover_color_bg' => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_border_style'   => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_color_border'   => array(
							'type'    => 'string',
							'default' => '',
						),
						'icon_border_size'    => array(
							'type' => 'integer',
						),
						'icon_border_radius'  => array(
							'type' => 'string',
						),
						'icon_border_spacing' => array(
							'type' => 'string',
						),
						'spacing'             => array(
							'type' => 'string',
						),
						'className'           => array(
							'type' => 'string',
						),
						'page_builder'        => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_social',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-menu-icon',
				array(
					'attributes'      => array(
						'icon_cl'      => array(
							'type' => 'string',
						),
						'size'         => array(
							'type' => 'string',
						),
						'bg_color'     => array(
							'type'    => 'string',
							'default' => '',
						),
						'color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_menu_icon',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-divider',
				array(
					'attributes'      => array(
						'width'        => array(
							'type' => 'string',
						),
						'height'       => array(
							'type' => 'string',
						),
						'color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_divider',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-myaccount',
				array(
					'attributes'      => array(
						'icon_cl'      => array(
							'type' => 'string',
						),
						'size'         => array(
							'type' => 'string',
						),
						'color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_myaccount',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-wishlist',
				array(
					'attributes'      => array(
						'icon_cl'      => array(
							'type' => 'string',
						),
						'size'         => array(
							'type' => 'string',
						),
						'color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_wishlist',
					),
				)
			);

			register_block_type(
				'porto-hb/porto-compare',
				array(
					'attributes'      => array(
						'icon_cl'      => array(
							'type' => 'string',
						),
						'size'         => array(
							'type' => 'string',
						),
						'color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'page_builder' => array(
							'type'    => 'string',
							'default' => 'gutenberg',
						),
					),
					'editor_script'   => 'porto-hb-blocks',
					'render_callback' => array(
						$this,
						'gutenberg_hb_compare',
					),
				)
			);
		}

		/**
		 * display logo in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_logo( $atts ) {
			$el_class = isset( $atts['className'] ) ? $atts['className'] : '';
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
				$el_class .= apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_design'], ' ' ), 'porto_hb_logo', $atts );
			}
			return porto_logo( false, $el_class );
		}

		/**
		 * display menu in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_menu( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_menu';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'hamburger_wd',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hamburger_hg',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hamburger_th',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hamburger_mr',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hamburger_cl',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hamburger_hcl',
							'selectors'  => true,
						),
						array(
							'param_name' => 'tg_icon_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'between_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'padding3',
							'selectors'  => true,
						),
						array(
							'param_name' => 'show_narrow',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_tl_bd_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_tl_bd_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_sp_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_sp_hcolor',
							'selectors'  => true,
						),
						array(
							'param_name' => 'narrow_pos',
							'selectors'  => true,
						),
						array(
							'param_name' => 'narrow_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_hover_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_sticky_link_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_sticky_link_color_hover',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_padding_sticky',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_margin',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_icon_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_icon_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_hover_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_item_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_narrow_border_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_narrow_bd_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_padding',
							'selectors'  => true,
						)
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_menu', $atts );
				}
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}

			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}

			if ( ! empty( $shortcode_class ) ) {
				$shortcode_class .= $el_class ? ' ' . $el_class : '';
			}

			if ( isset( $atts['show-onhover'] ) && 'yes' == $atts['show-onhover'] ) {
				$shortcode_class .= ' show-hover';
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( stripslashes( $internal_css ) ) . '</style>';
			}

			if ( ! empty( $atts['location'] ) ) {
				global $porto_settings;
				if ( 'main-toggle-menu' == $atts['location'] && ! empty( $porto_settings ) ) {
					if ( ! empty( $atts['title'] ) ) {
						$menu_title_backup            = $porto_settings['menu-title'];
						$porto_settings['menu-title'] = $atts['title'];
					}
					if ( isset( $atts[ 'menu-toggle-onhome' ] ) ) {
						$toggle_backup                        = $porto_settings['menu-toggle-onhome'];
						$porto_settings['menu-toggle-onhome'] = $atts[ 'menu-toggle-onhome' ];
					}
					porto_header_elements( array( (object) array( 'main-toggle-menu' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );

					if ( isset( $menu_title_backup ) ) {
						$porto_settings['menu-title'] = $menu_title_backup;
					}
					if ( isset( $toggle_backup ) ) {
						$porto_settings['menu-toggle-onhome'] = $toggle_backup;
					}
				} else {
					porto_header_elements( array( (object) array( $atts['location'] => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );
				}
			}
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display menu in gutenberg header builder
		 *
		 * @since 3.2.0
		 */
		public function gutenberg_hb_toggle( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_toggle';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'font_typography',
							'selectors'  => true,
						),
						array(
							'param_name' => 'tg_icon_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'between_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'padding1',
							'selectors'  => true,
						),
						array(
							'param_name' => 'color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'bgcolor',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_bgcolor',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_tl_bd_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_tl_bd_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'popup_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'show_narrow',
							'selectors'  => true,
						),
						array(
							'param_name' => 'narrow_pos',
							'selectors'  => true,
						),
						array(
							'param_name' => 'narrow_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_link_hover_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_icon_sz',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_icon_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'top_level_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_sp_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_sp_hcolor',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_link_hover_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_item_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_narrow_bd_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'submenu_narrow_border_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'mega_title_padding',
							'selectors'  => true,
						)
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_toggle', $atts );
				}
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}

			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}

			if ( ! empty( $shortcode_class ) ) {
				$shortcode_class .= $el_class ? ' ' . $el_class : '';
			}

			if ( isset( $atts['show-onhover'] ) && 'yes' == $atts['show-onhover'] ) {
				$shortcode_class .= ' show-hover';
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( stripslashes( $internal_css ) ) . '</style>';
			}

			global $porto_settings;
			if ( ! empty( $atts['title'] ) && ! empty( $porto_settings ) ) {
				$menu_title_backup            = $porto_settings['menu-title'];
				$porto_settings['menu-title'] = $atts['title'];
			}
			if ( isset( $atts[ 'menu-toggle-onhome' ] ) ) {
				$toggle_backup                        = $porto_settings['menu-toggle-onhome'];
				$porto_settings['menu-toggle-onhome'] = $atts[ 'menu-toggle-onhome' ];
			}
			porto_header_elements( array( (object) array( 'main-toggle-menu' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );

			if ( isset( $menu_title_backup ) ) {
				$porto_settings['menu-title'] = $menu_title_backup;
			}
			if ( isset( $toggle_backup ) ) {
				$porto_settings['menu-toggle-onhome'] = $toggle_backup;
			}
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display language/currency swticher in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_switcher( $atts ) {
			
			ob_start();

			$el_class = isset( $atts['className'] ) ? trim( $atts['className'] ) : '';
			isset( $atts['type'] ) && porto_header_elements( array( (object) array( $atts['type'] => '' ) ), $el_class );

			return ob_get_clean();
		}

		/**
		 * display search form in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_search_form( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_search_form';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'is_full',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'toggle_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_toggle_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_toggle_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_hover_toggle_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'searchform_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'search_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'height',
							'selectors'  => true,
						),
						array(
							'param_name' => 'wrap_margin',
							'selectors'  => true,
						),
						array(
							'param_name' => 'border_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'border_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'border_radius',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_box_shadow',
							'selectors'  => true,
						),
						array(
							'param_name' => 'input_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'input_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'input_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'input_placeholder_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'input_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_icon_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_icon_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'form_icon_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'divider_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'close_icon_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'close_icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'close_icon_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'close_icon_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'category_inner_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'category_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'category_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'category_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'category_color',
							'selectors'  => true,
						),
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_search_form', $atts );
				}
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}
			if ( ! $echo ) {
				ob_start();
			}

			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			global $porto_settings;
			if ( isset( $porto_settings['search-cats'] ) ) {
				$backup_cat_filter        = $porto_settings['search-cats'];
				$backup_cat_filter_mobile = $porto_settings['search-cats-mobile'];
				$backup_cat_sub           = $porto_settings['search-sub-cats'];
			}
			if ( ! empty( $porto_settings['search-placeholder'] ) ) {
				$backup_placeholder = $porto_settings['search-placeholder'];
			}
			if ( ! empty( $porto_settings['search-layout'] ) ) {
				$backup_layout = $porto_settings['search-layout'];
			}
			if ( ! empty( $porto_settings['show-searchform-mobile'] ) ) {
				$backup_searchform_mobile = $porto_settings['show-searchform-mobile'];
			}
			if ( ! empty( $porto_settings['search-type'] ) ) {
				$backup_content_type = $porto_settings['search-type'];
			}
			if ( ! empty( $atts['category_filter'] ) ) {
				$porto_settings['search-cats'] = 'yes' == $atts['category_filter'] ? true : false;
			} else {
				$porto_settings['search-cats'] = false;
			}
			if ( ! empty( $atts['category_filter_mobile'] ) ) {
				$porto_settings['search-cats-mobile'] = 'yes' == $atts['category_filter_mobile'] ? true : false;
			} else {
				$porto_settings['search-cats-mobile'] = false;
			}
			if ( ! empty( $atts['sub_cats'] ) ) {
				$porto_settings['search-sub-cats'] = 'yes' == $atts['sub_cats'] ? true : false;
			} else {
				$porto_settings['search-sub-cats'] = false;
			}

			if ( ! empty( $atts['placeholder_text'] ) ) {
				$porto_settings['search-placeholder'] = $atts['placeholder_text'];
			}
			if ( ! empty( $atts['search_layout'] ) ) {
				$porto_settings['search-layout'] = $atts['search_layout'];
			}
			if ( ! empty( $atts['show_searchform_mobile'] ) ) {
				$porto_settings['show-searchform-mobile'] = ( 'show' == $atts['show_searchform_mobile'] ? true : false );
			}
			if ( ! empty( $atts['search_type'] ) ) {
				$porto_settings['search-type'] = $atts['search_type'];
			}
			if ( function_exists( 'vc_is_inline' ) && vc_is_inline() && ! empty( $atts['is_full'] ) ) {
				$el_class .= ' w-100 ';
			}
			if ( ! empty( $atts['popup_pos'] ) ) {
				$el_class .= ' search-popup-' . $atts['popup_pos'];
			}
			if ( 'simple' == $porto_settings['search-layout'] ) {
				$el_class .= ' simple-popup ';
			}
			porto_header_elements( array( (object) array( 'search-form' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class . ' ' . $el_class );
			if ( isset( $backup_cat_filter ) ) {
				$porto_settings['search-cats']        = $backup_cat_filter;
				$porto_settings['search-cats-mobile'] = $backup_cat_filter_mobile;
				$porto_settings['search-sub-cats']    = $backup_cat_sub;
			}
			if ( isset( $backup_placeholder ) ) {
				$porto_settings['search-placeholder'] = $backup_placeholder;
			}
			if ( isset( $backup_layout ) ) {
				$porto_settings['search-layout'] = $backup_layout;
			}
			if ( isset( $backup_searchform_mobile ) ) {
				$porto_settings['show-searchform-mobile'] = $backup_searchform_mobile;
			}
			if ( isset( $backup_content_type ) ) {
				$porto_settings['search-type'] = $backup_content_type;
			}
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display mini cart in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_mini_cart( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_mini_cart';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_hover_icon_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_border_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_icon_border_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_item_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_item_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_icon_item_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_icon_item_bg_color',
							'selectors'  => true,
						),						
						array(
							'param_name' => 'icon_item_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_item_bg_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_item_right',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_item_top',
							'selectors'  => true,
						),
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_mini_cart', $atts );
				}

				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );

				$e_style_class = 'wpb_style_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					'porto_hb_mini_cart',
					array(
						array(
							'param_name' => 'icon_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_mr',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_ml',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_font_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_font_weight',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_transform',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_line_height',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_ls',
							'selectors'  => true,
						),
						array(
							'param_name' => 'text_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'price_font_size',
							'selectors'  => true,
						),
						array(
							'param_name' => 'price_font_weight',
							'selectors'  => true,
						),
						array(
							'param_name' => 'price_line_height',
							'selectors'  => true,
						),
						array(
							'param_name' => 'price_ls',
							'selectors'  => true,
						),
						array(
							'param_name' => 'price_color',
							'selectors'  => true,
						),
					)
				);
			}
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			if ( ! empty( $e_style_class ) ) {
				$el_class .= ' ' . $e_style_class;
			}
			if ( ! empty( $shortcode_class ) ) {
				$shortcode_class .= ( $el_class ? ' ' . $el_class : '' );
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			global $porto_settings;
			if ( ! empty( $atts['type'] ) ) {
				if ( isset( $porto_settings['minicart-type'] ) ) {
					$backup_type = $porto_settings['minicart-type'];
				}
				$porto_settings['minicart-type'] = $atts['type'];
			}
			
			$icon_cl = 'porto-icon-cart-thick';
			if ( ! empty( $atts['icon_cl'] ) ) {
				$icon_cl = $atts['icon_cl'];
			}
			if ( isset( $atts['icon_type'] ) ) {
				switch ( $atts['icon_type'] ) {
					case 'simpleline':
						if ( ! empty( $atts['icon_simpleline'] ) ) {
							$icon_cl = $atts['icon_simpleline'];
						}
						break;
					case 'porto':
						if ( ! empty( $atts['icon_porto'] ) ) {
							$icon_cl = $atts['icon_porto'];
						}
						break;
				}
			}
			if ( '' !== $icon_cl ) {
				if ( isset( $porto_settings['minicart-icon'] ) ) {
					$backup_icon = $porto_settings['minicart-icon'];
				}
				$porto_settings['minicart-icon'] = $icon_cl;
			}
			if ( ! isset( $atts['content_type'] ) ) {
				$atts['content_type'] = '';
			}
			if ( isset( $porto_settings['minicart-content'] ) ) {
				$backup_content_type = $porto_settings['minicart-content'];
			}
			$porto_settings['minicart-content'] = $atts['content_type'];

			porto_header_elements( array( (object) array( 'mini-cart' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );

			if ( isset( $backup_type ) ) {
				$porto_settings['minicart-type'] = $backup_type;
			}
			if ( isset( $backup_icon ) ) {
				$porto_settings['minicart-icon'] = $backup_icon;
			}
			if ( isset( $backup_content_type ) ) {
				$porto_settings['minicart-content'] = $backup_content_type;
			}
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display social icons in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_social( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_social';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'is_full',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_spacing',
							'selectors'  => true,
						),
					)
				);
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_social', $atts );
				}
			}
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			if ( function_exists( 'vc_is_inline' ) && vc_is_inline() && ! empty( $atts['is_full'] ) ) {
				$el_class .= ' w-100 ';
			}
			if ( ! empty( $shortcode_class ) ) {
				$shortcode_class .= ( $el_class ? ' ' . $el_class : '' );
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			porto_header_elements( array( (object) array( 'social' => '' ) ), empty( $shortcode_class ) ? $el_class : $shortcode_class );

			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display mobile menu toggle in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_menu_icon( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_menu_icon';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'icon_padding',
							'selectors'  => true,
						),
					)
				);
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_menu_icon', $atts );
				}
			}
			
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			if ( ! empty( $shortcode_class ) ) {
				$el_class .= ' ' . $shortcode_class;
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			global $porto_settings;
			$custom_icon = 'fas fa-bars';
			if ( ! empty( $atts['icon_cl'] ) ) {
				$custom_icon = $atts['icon_cl'];
			}
			if ( isset( $atts['icon_type'] ) ) {
				switch ( $atts['icon_type'] ) {
					case 'simpleline':
						if ( ! empty( $atts['icon_simpleline'] ) ) {
							$custom_icon = $atts['icon_simpleline'];
						}
						break;
					case 'porto':
						if ( ! empty( $atts['icon_porto'] ) ) {
							$custom_icon = $atts['icon_porto'];
						}
						break;
				}
			}
			echo apply_filters( 'porto_header_builder_mobile_toggle', '<a ' . apply_filters( 'porto_mobile_toggle_data_attrs', '' ) . ' aria-label="Mobile Menu" href="#" class="mobile-toggle' . ( empty( $atts['bg_color'] ) && ( ! isset( $porto_settings['mobile-menu-toggle-bg-color'] ) || 'transparent' == $porto_settings['mobile-menu-toggle-bg-color'] ) ? ' ps-0' : '' ) . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '"><i class="' . esc_attr( $custom_icon ) . '"></i></a>' );

			// if ( ! defined( 'ELEMENTOR_VERSION' ) && ! defined( 'WPB_VC_VERSION' ) && ! empty( $porto_settings['enable-gfse'] ) && isset( $porto_settings['mobile-panel-type'] ) ) {
			// 	if ( 'side' === $porto_settings['mobile-panel-type'] ) {
			// 		add_action( 'wp_footer', function(){
			// 			get_template_part( 'panel' );
			// 		} );
			// 	} elseif ( '' === $porto_settings['mobile-panel-type'] ){
			// 		get_template_part( 'header/mobile_menu' );
			// 	}
			// }
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display vertical divider in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_divider( $atts, $echo = false ) {
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			$inline_style = '';
			if ( ! empty( $atts['width'] ) ) {
				$unit = trim( preg_replace( '/[0-9.]/', '', $atts['width'] ) );
				if ( ! $unit ) {
					$atts['width'] .= 'px';
				}
				$inline_style .= 'border-left-width:' . esc_attr( $atts['width'] ) . ';';
			}
			if ( ! empty( $atts['height'] ) ) {
				$unit = trim( preg_replace( '/[0-9.]/', '', $atts['height'] ) );
				if ( ! $unit ) {
					$atts['height'] .= 'px';
				}
				$inline_style .= 'height:' . esc_attr( $atts['height'] ) . ';';
			}
			if ( ! empty( $atts['color'] ) ) {
				$inline_style .= 'border-left-color:' . esc_attr( $atts['color'] );
			}
			if ( $inline_style ) {
				$inline_style = ' style="' . $inline_style . '"';
			}
			echo '<span class="separator' . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '"' . $inline_style . '></span>';
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display my account icon in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_myaccount( $atts, $echo = false ) {
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_myaccount';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_dropdown',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_menu_font',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_dropdown_bgc',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_dropdown_hbgc',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_dropdown_lc',
							'selectors'  => true,
						),
						array(
							'param_name' => 'account_dropdown_hlc',
							'selectors'  => true,
						),
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_myaccount', $atts );
				}
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			if ( ! empty( $shortcode_class ) ) {
				$el_class .= ' ' . $shortcode_class;
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			$icon_cl = 'porto-icon-user-2';
			if ( ! empty( $atts['icon_cl'] ) ) {
				$icon_cl = trim( $atts['icon_cl'] );
			}
			if ( isset( $atts['icon_type'] ) ) {
				switch ( $atts['icon_type'] ) {
					case 'simpleline':
						if ( ! empty( $atts['icon_simpleline'] ) ) {
							$icon_cl = $atts['icon_simpleline'];
						}
						break;
					case 'porto':
						if ( ! empty( $atts['icon_porto'] ) ) {
							$icon_cl = $atts['icon_porto'];
						}
						break;
				}
			}
			global $porto_settings;
			if ( isset( $porto_settings['show-account-dropdown'] ) ) {
				$backup_account = $porto_settings['show-account-dropdown'];
			}
			$porto_settings['show-account-dropdown'] = ! empty( $atts['account_dropdown'] ) ? true : false;

			$inline_style = '';
			if ( ! empty( $atts['size'] ) ) {
				$unit = trim( preg_replace( '/[0-9.]/', '', $atts['size'] ) );
				if ( ! $unit ) {
					$atts['size'] .= 'px';
				}
				$inline_style .= 'font-size:' . esc_attr( $atts['size'] ) . ';';
			}
			if ( $inline_style ) {
				$inline_style = ' style="' . $inline_style . '"';
			}

			if ( function_exists( 'porto_account_menu' ) ) {
				porto_account_menu( $el_class, $icon_cl, $inline_style );
			} else {
				if ( ! is_user_logged_in() && empty( $porto_settings['woo-account-login-style'] ) ) {
					$el_class .= ' porto-link-login';
				}
				echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '"' . ' title="' . esc_attr__( 'My Account', 'porto' ) . '" class="my-account' . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '"' . $inline_style . '><i class="' . esc_attr( $icon_cl ) . '"></i></a>';
			}
			if ( isset( $backup_account ) ) {
				$porto_settings['show-account-dropdown'] = $backup_account;
			}
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display wishlist icon in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_wishlist( $atts, $echo = false ) {
			if ( ! defined( 'YITH_WCWL' ) ) {
				return;
			}
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_wishlist';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'badge_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'badge_bg_color',
							'selectors'  => true,
						),
					)
				);
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_hb_wishlist', $atts );
				}
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			if ( ! empty( $shortcode_class ) ) {
				$el_class .= ' ' . $shortcode_class;
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			$icon_cl = 'porto-icon-wishlist-2';
			if ( ! empty( $atts['icon_cl'] ) ) {
				$icon_cl = trim( $atts['icon_cl'] );
			}
			if ( isset( $atts['icon_type'] ) ) {
				switch ( $atts['icon_type'] ) {
					case 'simpleline':
						if ( ! empty( $atts['icon_simpleline'] ) ) {
							$icon_cl = $atts['icon_simpleline'];
						}
						break;
					case 'porto':
						if ( ! empty( $atts['icon_porto'] ) ) {
							$icon_cl = $atts['icon_porto'];
						}
						break;
				}
			}
			global $porto_settings;
			if ( isset( $porto_settings['wl-offcanvas'] ) ) {
				$backup_offcanvas = $porto_settings['wl-offcanvas'];
			}
			$porto_settings['wl-offcanvas'] = ! empty( $atts['offcanvas'] ) ? true : false;

			$inline_style = '';
			if ( ! empty( $atts['size'] ) ) {
				$unit = trim( preg_replace( '/[0-9.]/', '', $atts['size'] ) );
				if ( ! $unit ) {
					$atts['size'] .= 'px';
				}
				$inline_style .= 'font-size:' . esc_attr( $atts['size'] ) . ';';
			}

			if ( function_exists( 'porto_wishlist' ) ) {
				echo porto_wishlist( $el_class, $icon_cl, $inline_style );
			} else {
				if ( $inline_style ) {
					$inline_style = ' style="' . $inline_style . '"';
				}
				$wc_count = yith_wcwl_count_products();
				echo '<a href="' . esc_url( YITH_WCWL()->get_wishlist_url() ) . '"' . ' title="' . esc_attr__( 'Wishlist', 'porto' ) . '" class="my-wishlist' . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '"' . $inline_style . '><i class="' . esc_attr( $icon_cl ) . '"></i><span class="wishlist-count">' . intval( $wc_count ) . '</span></a>';
			}
			if ( isset( $backup_offcanvas ) ) {
				$porto_settings['wl-offcanvas'] = $backup_offcanvas;
			}

			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		/**
		 * display product compare icon in gutenberg header builder
		 *
		 * @since 2.1.0
		 */
		public function gutenberg_hb_compare( $atts, $echo = false ) {
			if ( ! defined( 'YITH_WOOCOMPARE' ) || ! class_exists( 'YITH_Woocompare' ) ) {
				return;
			}
			if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
				$shortcode_name = 'porto_hb_compare';
				// Shortcode class
				$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					$shortcode_name,
					array(
						array(
							'param_name' => 'label_typography',
							'selectors'  => true,
						),
						array(
							'param_name' => 'icon_space',
							'selectors'  => true,
						),
						array(
							'param_name' => 'color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'sticky_hover_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'badge_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'badge_bg_color',
							'selectors'  => true,
						),
					)
				);
				$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			}
			if ( ! $echo ) {
				ob_start();
			}
			if ( isset( $atts['el_class'] ) ) {
				$el_class = trim( $atts['el_class'] );
			} elseif ( isset( $atts['className'] ) ) {
				$el_class = trim( $atts['className'] );
			} else {
				$el_class = '';
			}
			
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
				$el_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( isset( $atts['css_design'] ) ? $atts['css_design'] : '', ' ' ), 'porto_hb_compare', $atts );
			}
			if ( ! empty( $shortcode_class ) ) {
				$el_class .= ' ' . $shortcode_class;
			}
			if ( ! empty( $internal_css ) ) {
				// only wpbakery frontend editor
				echo '<style>' . wp_strip_all_tags( $internal_css ) . '</style>';
			}

			global $yith_woocompare;
			$icon_cl = 'porto-icon-compare-link';
			if ( ! empty( $atts['icon_cl'] ) ) {
				$icon_cl = trim( $atts['icon_cl'] );
			}
			if ( isset( $atts['icon_type'] ) ) {
				switch ( $atts['icon_type'] ) {
					case 'simpleline':
						if ( ! empty( $atts['icon_simpleline'] ) ) {
							$icon_cl = $atts['icon_simpleline'];
						}
						break;
					case 'porto':
						if ( ! empty( $atts['icon_porto'] ) ) {
							$icon_cl = $atts['icon_porto'];
						}
						break;
				}
			}

			$inline_style = '';
			if ( ! empty( $atts['size'] ) ) {
				$unit = trim( preg_replace( '/[0-9.]/', '', $atts['size'] ) );
				if ( ! $unit ) {
					$atts['size'] .= 'px';
				}
				$inline_style .= 'font-size:' . esc_attr( $atts['size'] ) . ';';
			}
			if ( $inline_style ) {
				$inline_style = ' style="' . $inline_style . '"';
			}

			$compare_count = isset( $yith_woocompare->obj->products_list ) ? sizeof( $yith_woocompare->obj->products_list ) : 0;

			echo '<a href="#" title="' . esc_attr__( 'Compare', 'porto' ) . '" class="yith-woocompare-open' . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '"' . $inline_style . '><span class="compare-icon"><i class="' . esc_attr( $icon_cl ) . '"></i><span class="compare-count">' . intval( $compare_count ) . '</span></span>' . ( isset( $atts['show_label'] ) && 'yes' == $atts['show_label'] ? '<span class="hicon-label">' . ( ! empty( $atts['compare_label'] ) ? $atts['compare_label'] : esc_html__( 'Compare', 'porto-functionality' ) ) . '</span>' : '' ) . '</a>';

			if ( ! $echo ) {
				return ob_get_clean();
			}
		}
	}
endif;

PortoBuildersHeader::get_instance();
