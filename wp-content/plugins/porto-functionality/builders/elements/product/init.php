<?php
/**
 * Add shortcodes for single product page
 *
 * @author   Porto Themes
 * @category Library
 * @since    2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PortoCustomProduct {

	public static $shortcodes = array(
		'image',
		'title',
		'rating',
		'actions',
		'price',
		'excerpt',
		'description',
		'add_to_cart',
		'meta',
		'tabs',
		'upsell',
		'related',
		'linked',
		'next_prev_nav',
		'addcart_sticky',
		'attribute',
		'review',
		'share',
	);

	protected $display_product_page_elements = false;

	protected $edit_post = null;

	protected $edit_product = null;

	protected $is_product = false;

	protected static $instance = null;

	protected $is_in_product = 0;
	/**
	 * Is legacy mode?
	 *
	 * @access protected
	 * @since 2.3.0
	 */
	protected $legacy_mode = true;

	public function __construct() {
		$this->legacy_mode = apply_filters( 'porto_legacy_mode', true );
		if ( ! $this->legacy_mode ) { // if soft mode
			self::$shortcodes = array_diff( self::$shortcodes, array( 'upsell', 'related' ) );
		}
		if ( class_exists( 'YITH_WCWL' ) ) {
			self::$shortcodes[] = 'wishlist';
		}
		if ( defined( 'YITH_WFBT_VERSION' ) ) {
			self::$shortcodes[] = 'fbt';
		}
		if ( defined( 'YITH_WOOCOMPARE' ) ) {
			self::$shortcodes[] = 'compare';
		}
		$this->init();
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function init() {
		remove_action( 'porto_after_content_bottom', 'porto_woocommerce_output_related_products', 10 );

		if ( defined( 'WPB_VC_VERSION' ) || defined( 'VCV_VERSION' ) ) {
			add_action(
				'template_redirect',
				function () {
					$should_add_shortcodes = false;
					if ( ( is_singular( PortoBuilders::BUILDER_SLUG ) && 'product' == get_post_meta( get_the_ID(), PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) || ! empty( $_GET['vcv-ajax'] ) || ( function_exists( 'porto_is_ajax' ) && porto_is_ajax() && ! empty( $_GET[ PortoBuilders::BUILDER_SLUG ] ) ) ) {
						$should_add_shortcodes = true;
					} else {
						global $porto_settings;
						if ( function_exists( 'porto_check_builder_condition' ) && porto_check_builder_condition( 'product' ) ) {
							$should_add_shortcodes = true;
						} elseif ( is_singular( 'product' ) && isset( $porto_settings['product-single-content-layout'] ) && 'builder' == $porto_settings['product-single-content-layout'] && ! empty( $porto_settings['product-single-content-builder'] ) ) {
							$should_add_shortcodes = true;
						}
					}

					if ( $should_add_shortcodes ) {
						foreach ( $this::$shortcodes as $shortcode ) {
							add_shortcode( 'porto_single_product_' . $shortcode, array( $this, 'shortcode_single_product_' . $shortcode ) );
						}
					}
				}
			);

			add_action(
				'admin_init',
				function () {
					$should_add_shortcodes = false;
					if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'vc_save' == $_REQUEST['action'] ) {
						$should_add_shortcodes = true;
					} elseif ( isset( $_POST['action'] ) && 'editpost' == $_POST['action'] && isset( $_POST['post_type'] ) && PortoBuilders::BUILDER_SLUG == $_POST['post_type'] ) {
						$should_add_shortcodes = true;
					}

					if ( $should_add_shortcodes ) {
						foreach ( $this::$shortcodes as $shortcode ) {
							add_shortcode( 'porto_single_product_' . $shortcode, array( $this, 'shortcode_single_product_' . $shortcode ) );
						}
					}
				}
			);
		}

		add_action( 'save_post', array( $this, 'add_shortcodes_css' ), 100, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		if ( defined( 'WPB_VC_VERSION' ) ) {
			add_filter( 'vc_autocomplete_porto_single_product_linked_builder_id_callback', 'builder_id_callback' );
			add_filter( 'vc_autocomplete_porto_single_product_linked_builder_id_render', 'builder_id_render' );
			if ( is_admin() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
				if ( function_exists( 'vc_is_inline' ) && vc_is_inline() && defined( 'PORTO_VERSION' ) ) {
					wp_enqueue_style( 'porto-sp-layout', PORTO_CSS . '/theme/shop/single-product/builder' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
					wp_enqueue_style( 'porto-sp-scatted-layout', PORTO_CSS . '/theme/shop/single-product/scatted' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
				}
				add_action( 'vc_after_init', array( $this, 'load_custom_product_shortcodes' ) );
			}
		}

		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			add_filter( 'porto_is_product', array( $this, 'filter_is_product' ), 20 );
		} elseif ( defined( 'ELEMENTOR_VERSION' ) ) {
			if ( is_admin() && isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) {
				add_action(
					'elementor/elements/categories_registered',
					function ( $self ) {
						$self->add_category(
							'custom-product',
							array(
								'title'  => __( 'Porto Single Product', 'porto-functionality' ),
								'active' => true,
							)
						);
					}
				);
			}

			add_action( 'elementor/widgets/register', array( $this, 'elementor_custom_product_shortcodes' ), 10, 1 );
		}

		/**
		 * Enqueue styles and scripts for single product gutenberg blocks
		 *
		 * @since 6.1
		 */
		if ( is_admin() ) {
			$load_blocks = false;
			if ( ( PortoBuilders::BUILDER_SLUG ) && isset( $_REQUEST['post'] ) && 'product' == get_post_meta( $_REQUEST['post'], PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
				$load_blocks = true;
			}
			if ( $load_blocks ) {
				add_action(
					'enqueue_block_editor_assets',
					function () {
						wp_enqueue_script( 'porto_single_product_blocks', PORTO_FUNC_URL . 'builders/elements/product/gutenberg/blocks.min.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data'/*, 'wp-editor'*/ ), PORTO_VERSION, true );
					},
					1000
				);
				add_filter(
					'block_categories_all',
					function ( $categories ) {
						return array_merge(
							$categories,
							array(
								array(
									'slug'  => 'porto-single-product',
									'title' => __( 'Porto Single Product Blocks', 'porto-functionality' ),
									'icon'  => '',
								),
							)
						);
					},
					11,
					1
				);
			}
		}

		$gutenberg_attr = array(
			'image'         => array(
				'style'        => array(
					'type' => 'string',
				),
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'title'         => array(
				'font_family'    => array(
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
				'el_class'       => array(
					'type' => 'string',
				),
				'page_builder'   => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'excerpt'       => array(
				'font_size'    => array(
					'type' => 'string',
				),
				'font_weight'  => array(
					'type' => 'integer',
				),
				'line_height'  => array(
					'type' => 'string',
				),
				'ls'           => array(
					'type' => 'string',
				),
				'color'        => array(
					'type' => 'string',
				),
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'price'         => array(
				'font_family'         => array(
					'type' => 'string',
				),
				'font_size'           => array(
					'type' => 'string',
				),
				'font_weight'         => array(
					'type' => 'integer',
				),
				'text_transform'      => array(
					'type' => 'string',
				),
				'line_height'         => array(
					'type' => 'string',
				),
				'letter_spacing'      => array(
					'type' => 'string',
				),
				'color'               => array(
					'type' => 'string',
				),
				'sale_font_family'    => array(
					'type' => 'string',
				),
				'sale_font_size'      => array(
					'type' => 'string',
				),
				'sale_font_weight'    => array(
					'type' => 'integer',
				),
				'sale_text_transform' => array(
					'type' => 'string',
				),
				'sale_line_height'    => array(
					'type' => 'string',
				),
				'sale_letter_spacing' => array(
					'type' => 'string',
				),
				'sale_color'          => array(
					'type' => 'string',
				),
				'page_builder'        => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'rating'        => array(
				'font_size'    => array(
					'type' => 'string',
				),
				'bgcolor'      => array(
					'type' => 'string',
				),
				'color'        => array(
					'type' => 'string',
				),
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'actions'       => array(
				'action'       => array(
					'type' => 'string',
				),
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'add_to_cart'   => array(
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'meta'          => array(
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'next_prev_nav' => array(
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'description'   => array(
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'tabs'          => array(
				'style'        => array(
					'type' => 'string',
				),
				'page_builder' => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'upsell'        => array(
				'title'              => array(
					'type' => 'string',
				),
				'title_border_style' => array(
					'type' => 'string',
				),
				'view'               => array(
					'type'    => 'string',
					'default' => 'grid',
				),
				'status'             => array(
					'type' => 'string',
				),
				'count'              => array(
					'type' => 'integer',
				),
				'orderby'            => array(
					'type'    => 'string',
					'default' => 'title',
				),
				'order'              => array(
					'type'    => 'string',
					'default' => 'asc',
				),
				'columns'            => array(
					'type' => 'integer',
				),
				'columns_mobile'     => array(
					'type' => 'string',
				),
				'column_width'       => array(
					'type' => 'string',
				),
				'grid_layout'        => array(
					'type' => 'integer',
				),
				'grid_height'        => array(
					'type' => 'string',
				),
				'spacing'            => array(
					'type' => 'integer',
				),
				'addlinks_pos'       => array(
					'type' => 'string',
				),
				'navigation'         => array(
					'type' => 'boolean',
				),
				'show_nav_hover'     => array(
					'type' => 'boolean',
				),
				'pagination'         => array(
					'type' => 'boolean',
				),
				'nav_pos'            => array(
					'type' => 'string',
				),
				'nav_pos2'           => array(
					'type' => 'string',
				),
				'nav_type'           => array(
					'type' => 'string',
				),
				'dots_pos'           => array(
					'type' => 'string',
				),
				'category_filter'    => array(
					'type' => 'boolean',
				),
				'pagination_style'   => array(
					'type' => 'string',
				),
				'page_builder'       => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
			'related'       => array(
				'title'              => array(
					'type' => 'string',
				),
				'title_border_style' => array(
					'type' => 'string',
				),
				'view'               => array(
					'type'    => 'string',
					'default' => 'grid',
				),
				'status'             => array(
					'type' => 'string',
				),
				'count'              => array(
					'type' => 'integer',
				),
				'orderby'            => array(
					'type'    => 'string',
					'default' => 'title',
				),
				'order'              => array(
					'type'    => 'string',
					'default' => 'asc',
				),
				'columns'            => array(
					'type' => 'integer',
				),
				'columns_mobile'     => array(
					'type' => 'string',
				),
				'column_width'       => array(
					'type' => 'string',
				),
				'grid_layout'        => array(
					'type' => 'integer',
				),
				'grid_height'        => array(
					'type' => 'string',
				),
				'spacing'            => array(
					'type' => 'integer',
				),
				'addlinks_pos'       => array(
					'type' => 'string',
				),
				'navigation'         => array(
					'type' => 'boolean',
				),
				'show_nav_hover'     => array(
					'type' => 'boolean',
				),
				'pagination'         => array(
					'type' => 'boolean',
				),
				'nav_pos'            => array(
					'type' => 'string',
				),
				'nav_pos2'           => array(
					'type' => 'string',
				),
				'nav_type'           => array(
					'type' => 'string',
				),
				'dots_pos'           => array(
					'type' => 'string',
				),
				'category_filter'    => array(
					'type' => 'boolean',
				),
				'pagination_style'   => array(
					'type' => 'string',
				),
				'page_builder'       => array(
					'type'    => 'string',
					'default' => 'gutenberg',
				),
			),
		);

		foreach ( $gutenberg_attr as $shortcode => $attr ) {
			register_block_type(
				'porto-single-product/porto-sp-' . str_replace( '_', '-', $shortcode ),
				array(
					'editor_script'   => 'porto_single_product_blocks',
					'render_callback' => array( $this, 'shortcode_single_product_' . $shortcode ),
					'attributes'      => $attr,
				)
			);
		}

		/* init google structured data */
		add_action(
			'init',
			function() {
				remove_action( 'woocommerce_single_product_summary', array( WC()->structured_data, 'generate_product_data' ), 60 );
				add_action( 'woocommerce_after_single_product', array( WC()->structured_data, 'generate_product_data' ), 60 );
			}
		);
	}

	public function filter_is_product( $is_product ) {
		if ( $this->is_product ) {
			return true;
		}
		$post_id = (int) vc_get_param( 'vc_post_id' );
		if ( $post_id ) {
			$post = get_post( $post_id );
			if ( $post && PortoBuilders::BUILDER_SLUG == $post->post_type ) {
				$this->is_product = true;
				return true;
			}
		}
		return $is_product;
	}

	public function restore_global_product_variable() {

		if ( ! $this->edit_product && ( is_singular( PortoBuilders::BUILDER_SLUG ) || ( isset( $_REQUEST['context'] ) && 'edit' == $_REQUEST['context'] ) || ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'elementor_ajax' == $_REQUEST['action'] ) || ( isset( $_REQUEST['vc_editable'] ) && $_REQUEST['vc_editable'] ) || ( ! empty( $_REQUEST['wpb_vc_js_status'] ) && ! empty( $_REQUEST['post'] ) ) || ( isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] && isset( $_REQUEST['post'] ) ) ) ) {
			$query = new WP_Query(
				array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'posts_per_page'      => 1,
					'ignore_sticky_posts' => true,
				)
			);
			if ( $query->have_posts() ) {
				$the_post           = $query->next_post();
				$this->edit_post    = $the_post;
				$this->edit_product = wc_get_product( $the_post );
			}
		}
		if ( $this->edit_product ) {
			global $post, $product;
			$post = $this->edit_post;
			setup_postdata( $this->edit_post );
			$product = $this->edit_product;
			$this->is_in_product += 1;
			return true;
		}
		return false;
	}

	public function reset_global_product_variable() {
		if ( $this->edit_product ) {
			$this->is_in_product -= 1;
			if ( $this->is_in_product > 0 ) {
				return;
			}

			wp_reset_postdata();
		}
	}

	/**
	 * Show wishlist in single product page.
	 *
	 * @since 2.4.0
	 */
	public function shortcode_single_product_wishlist( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_wishlist';
			if ( empty( $atts['el_class'] ) ) {
				if ( ! is_array( $atts ) ) {
					$atts = array();
				}
				$atts['el_class'] = 'wpb-sp-wishlist';
			} else {
				$atts['el_class'] .= ' wpb-sp-wishlist';
			}
			// Shortcode class
			$shortcode_class = $atts['el_class'] . ' wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'show_label',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_size',
						'selectors'  => true,
					),
					array(
						'param_name' => 'label_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_added_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'label_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'label_hover_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bg_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bg_height',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bg_hover_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'wl_padding',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}

			if ( ! isset( $atts['show_label'] ) || 'no' == $atts['show_label'] ) {
				$atts['show_label'] = '';
			}
		}
		if ( isset( $atts['show_label'] ) && '' == $atts['show_label'] ) {
			$shortcode_class = ! empty( $shortcode_class ) ? $shortcode_class . ' wishlist-nolabel' : 'wishlist-nolabel';
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}

		// Ajax first load for Yith WooCommerce
		if ( ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ) || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
			if ( 'yes' === get_option( 'yith_wcwl_ajax_enable', 'no' ) ) {
				add_filter( 'yith_wcwl_add_to_wishlist_params', function( $additional_params, $atts ){
					$additional_params['ajax_loading'] = false;
					return $additional_params;
				}, 99, 2 );
			}
		}
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();
		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	/**
	 * Show fbt in single product page.
	 *
	 * @since 2.6.0
	 */
	public function shortcode_single_product_fbt( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_fbt';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'image_w',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_w',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_sz',
						'selectors'  => true,
					),
					array(
						'param_name' => 'hide_title',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing',
						'selectors'  => true,
					),
					array(
						'param_name' => 'item_sz',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
		}
		if ( defined( 'YITH_WFBT_DIR' ) && is_admin() && porto_is_elementor_preview() ) {
			if ( defined( 'YITH_WFBT_PREMIUM' ) ) {
				require_once YITH_WFBT_DIR . 'includes/class.yith-wfbt-discount.php';	
			}
			require_once YITH_WFBT_DIR . 'includes/class.yith-wfbt-frontend.php';
			YITH_WFBT_Frontend();
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		echo do_shortcode( '[ywfbt_form]' );
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();
		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	/**
	 * Show compare in single product page.
	 *
	 * @since 2.6.0
	 */
	public function shortcode_single_product_compare( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_compare';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'compare_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_size',
						'selectors'  => true,
					),
					array(
						'param_name' => 'pd',
						'selectors'  => true,
					),
					array(
						'param_name' => 'margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_bd_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_color_hover',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_bd_color_hover',
						'selectors'  => true,
					),
					array(
						'param_name' => 'bt_bg_color_hover',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
		}
		ob_start();
		$shortcode_class = ! empty( $shortcode_class ) ? $shortcode_class . ' widget-compare' : 'widget-compare';
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		if ( function_exists( 'porto_template_loop_compare' ) ) {
			porto_template_loop_compare();
		}
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();
		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_image( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'style'           => '',
					'icon_cl'         => '',
					'icon_type'       => 'fontawesome',
					'icon_simpleline' => '',
					'icon_porto'      => '',
					'spacing1'        => '',
					'enable_flick'    => false,
					'columns'         => '',
					'columns_tablet'  => '',
					'columns_mobile'  => '',
					'set_loop'        => '',
					'center_mode'     => 'yes',
				),
				$atts
			)
		);

		if ( 'transparent' == $style ) {
			wp_enqueue_script( 'jquery-slick' );
		}
		if ( ! $style ) {
			$style = 'default';
		}
		if ( 'scatted' == $style ) {
			$style = 'sticky_info';
			global $porto_scatted_layout;
			$porto_scatted_layout = true;
			if ( ! ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ) && ! ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
				wp_enqueue_style( 'porto-sp-scatted-layout', PORTO_CSS . '/theme/shop/single-product/scatted' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
			}
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_image';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'spacing',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing1',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing2',
						'selectors'  => true,
					),
					array(
						'param_name' => 'br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'thumbnail_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'thumbnail_img_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'thumbnail_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'thumbnail_hover_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_pos',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_bgc',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_bg_size',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_fs',
						'selectors'  => true,
					),
					array(
						'param_name' => 'popup_br_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'popup_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'popup_space',
						'selectors'  => true,
					),
					array(
						'param_name' => 'flick_opacity',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );

			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_single_product_image', $atts );
			}

			switch ( $icon_type ) {
				case 'simpleline':
					$icon_cl = $icon_simpleline;
					break;
				case 'porto':
					$icon_cl = $icon_porto;
					break;
			}
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		echo '<div class="product-layout-image' . ( $style ? ' product-layout-' . esc_attr( $style ) : '' ) . ( ! empty( $porto_scatted_layout ) ? ' product-scatted-layout' : '' ) . '">';
		echo '<div class="summary-before">';
		woocommerce_show_product_sale_flash();
		echo '</div>';
		global $porto_product_layout;
		$porto_product_layout = $style;

		if ( defined( 'ELEMENTOR_VERSION' ) && ! has_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails' ) ) {
			add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		}
		global $porto_product_info;
		if ( ! empty( $icon_cl ) ) {
			$porto_product_info['icon_cl'] = $icon_cl;
		}
		if ( 'extended' == $style ) {
			if ( $enable_flick ) {
				$porto_product_info['enable_flick'] = $enable_flick;
			}

			if ( ! empty( $columns ) ) {
				$porto_product_info['items'] = (int) $columns;
				
				if ( is_array( $columns_mobile ) && isset( $columns_mobile['size'] ) ) {
					// WPBakery
					$columns_mobile = $columns_mobile['size'];
				}
				if ( empty( $columns_mobile ) ) {
					$columns_mobile = 1;
				} else {
					$columns_mobile = (int) $columns_mobile;
				}
				$porto_product_info['columns_class'] = porto_grid_column_class( $columns, $columns_mobile, $columns_tablet );
				$cols_arr = porto_grid_column_class( $columns, $columns_mobile, $columns_tablet, false );

				$breakpoint_matches = array(
					'xxl' => porto_get_xl_width( false ),
					'xl'  => porto_get_xl_width(),
					'lg'  => 992,
					'md'  => 768,
					'sm'  => 576,
					'xs'  => 0,
				);

				$options = array();
				foreach ( $cols_arr as $breakpoint_name => $cols_val ) {
					if ( isset( $breakpoint_matches[ $breakpoint_name ] ) ) {
						$options[ strval( $breakpoint_matches[ $breakpoint_name ] ) ] = $cols_val;
					}
				}
				$porto_product_info['responsive'] = $options;
				
			}
			if ( isset( $spacing1 ) ) {
				$porto_product_info['margin'] = (int) $spacing1;
			}
			if ( 'no' == $set_loop ) {
				$porto_product_info['loop'] = false;
			} elseif ( 'yes' == $set_loop ) {
				$porto_product_info['loop'] = true;
			}
			if ( '' == $center_mode ) {
				$porto_product_info['center_mode'] = false;
			} else {
				$porto_product_info['center_mode'] = true;
			}
		}
		wc_get_template_part( 'single-product/product-image' );
		if ( isset( $porto_product_info ) ) {
			unset( $GLOBALS['porto_product_info'] );
		}
		if ( ! empty( $porto_scatted_layout ) ) {
			unset( $GLOBALS[ 'porto_scatted_layout' ] );
		}
		echo '</div>';
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$porto_product_layout = 'builder';
		$this->reset_global_product_variable();

		$result = ob_get_clean();

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_title( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		$result = '';
		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			$inline_style = '<style>';
			ob_start();
			include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-title.php';
			$inline_style .= ob_get_clean();
			$inline_style .= '</style>';
			$result       .= porto_filter_inline_css( $inline_style, false );
		}

		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'font_family'     => '',
					'font_size'       => '',
					'font_weight'     => '',
					'text_transform'  => '',
					'line_height'     => '',
					'letter_spacing'  => '',
					'css_porto_title' => '',
					'color'           => '',
					'el_class'        => '',
				),
				$atts
			)
		);

		global $porto_settings;
		$el_class = ! empty( $atts['className'] ) ? $atts['className'] : $el_class;
		
		if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && '' !== $css_porto_title ) {
			$el_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_porto_title, ' ' ), 'css_porto_title', $atts );
		}

		$result  .= '<h2 class="product_title entry-title' . ( apply_filters( 'porto_legacy_mode', true ) && empty( $porto_settings['product-nav'] ) ? '' : ' show-product-nav' ) . ( $el_class ? ' ' . esc_attr( trim( $el_class ) ) : '' ) . '">';
		$result  .= esc_html( get_the_title() );
		$result  .= '</h2>';

		$this->reset_global_product_variable();

		return $result;
	}

	public function shortcode_single_product_rating( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_rating';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'rating_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'review_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'separator',
						'selectors'  => true,
					),
					array(
						'param_name' => 'flex_direction',
						'selectors'  => true,
					),
					array(
						'param_name' => 'between_spacing',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_porto_rating'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_porto_rating'], ' ' ), $shortcode_name, $atts );
			}
		}

		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			ob_start();
			echo '<style>';
			include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-rating.php';
			echo '</style>';
			porto_filter_inline_css( ob_get_clean() );
		}
		woocommerce_template_single_rating();
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();

		$result = ob_get_clean();

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}

		return $result;
	}

	public function shortcode_single_product_actions( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'action'   => 'woocommerce_single_product_summary',
					'el_class' => '',
				),
				$atts
			)
		);
		if ( 'woocommerce_single_product_summary' == $action && defined( 'ELEMENTOR_VERSION' ) ) {
			if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
			}
			if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			}
			if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			}
			if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			}
			if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			}
		}

		if ( ! empty( $atts['extra_plugin'] ) ) {
			if ( 'woocommerce_before_single_product' == $action ) {
				if ( has_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' ) ) {
					$wc_output_all_notices = true;
					remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );
				}
			} elseif ( 'woocommerce_product_thumbnails' == $action ) {
				if ( has_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails' ) ) {
					$wc_show_product_thumbnails = true;
					remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
				}
			} elseif ( 'woocommerce_before_single_product_summary' == $action ) {
				if ( has_action ( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' ) ) {
					$wc_show_product_sale_flash = true;
					remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
				}
				if ( has_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images' ) ) {
					$wc_show_product_images = true;
					remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
				}
			} elseif ( 'woocommerce_single_product_summary' == $action ) {
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title' ) ) {
					$wc_template_single_title = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' ) ) {
					$wc_template_single_rating = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' ) ) {
					$wc_template_single_price = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt' ) ) {
					$wc_template_single_excerpt = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart' ) ) {
					$wc_template_single_add_to_cart = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' ) ) {
					$wc_template_single_meta = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 26 );
				}
				if ( has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing' ) ) {
					$wc_template_single_sharing = true;
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
				}
				
				if ( has_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' ) ) {
					$wc_output_product_data_tabs = true;
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				}
				if ( has_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display' ) ) {
					$wc_upsell_display = true;
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				}
				if ( has_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' ) ) {
					$wc_output_related_products = true;
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
				}
			}
		}

		if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
			$el_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_single_product_actions', $atts );
		}

		ob_start();
		if ( ! empty( $el_class ) ) {
			echo '<div class="' . esc_attr( $el_class ) . '">';
		}
		do_action( $action );
		if ( ! empty( $el_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();

		if ( ! empty( $atts['extra_plugin'] ) ) {
			if ( ! empty( $wc_output_all_notices ) ) {
				add_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );
			}
			if ( ! empty( $wc_show_product_thumbnails ) ) {
				add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			}
			if ( ! empty( $wc_show_product_sale_flash ) ) {
				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
			}
			if ( ! empty( $wc_show_product_images ) ) {
				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
			}
			if ( ! empty( $wc_template_single_title ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
			}
			if ( ! empty( $wc_template_single_rating ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
			}
			if ( ! empty( $wc_template_single_price ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			}
			if ( ! empty( $wc_template_single_excerpt ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			}
			if ( ! empty( $wc_template_single_add_to_cart ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}
			if ( ! empty( $wc_template_single_meta ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 26 );
			}
			if ( ! empty( $wc_template_single_sharing ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			}
			if ( ! empty( $wc_output_product_data_tabs ) ) {
				add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			}
			if ( ! empty( $wc_upsell_display ) ) {
				add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			}
			if ( ! empty( $wc_output_related_products ) ) {
				add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			}
		}

		return ob_get_clean();
	}

	public function shortcode_single_product_price( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( ! has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' ) ) {
			$this->reset_global_product_variable();
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_price';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'old_price_color',
						'selectors'  => true,
					),
				)
			);
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_porto_price'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_porto_price'], ' ' ), $shortcode_name, $atts );
			}
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		if ( ! empty( $atts ) ) {
			if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
				ob_start();
				echo '<style>';
				include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-price.php';
				echo '</style>';
				porto_filter_inline_css( ob_get_clean() );
			}
			echo '<div class="single-product-price">';
		}
		woocommerce_template_single_price();
		if ( ! empty( $atts ) ) {
			echo '</div>';
		}
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();

		$result = ob_get_clean();

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}

		return $result;
	}

	public function shortcode_single_product_excerpt( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		ob_start();
		woocommerce_template_single_excerpt();
		$result = ob_get_clean();
		$this->reset_global_product_variable();
		if ( empty( $result ) && ( ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ) || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) ) {
			return sprintf( esc_html__( '%1$sPlease input the product short description.%2$s', 'porto-functionality' ), '<div class="elementor-panel-alert elementor-panel-alert-info">', '</div>' );
		}
		$el_class = '';
		if ( ! empty( $atts['el_class'] ) ) {
			$el_class = $atts['el_class'];
		}
		if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_porto_excerpt'] ) ) {
			$el_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_porto_excerpt'], ' ' ), 'porto_single_product_excerpt', $atts );
		}

		if ( ! empty( $el_class ) ) {
			$result = '<div class="' . esc_attr( $el_class ) . '">' . $result;
		}
		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			ob_start();
			echo '<style>';
			include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-excerpt.php';
			echo '</style>';
			$result .= porto_filter_inline_css( ob_get_clean(), false );
		}
		if ( ! empty( $el_class ) ) {
			$result = $result . '</div>';
		}

		return $result;
	}

	public function shortcode_single_product_description( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_description';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'description_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'description_color',
						'selectors'  => true,
					),
				)
			);

			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
		}
		ob_start();
		the_content();
		$this->reset_global_product_variable();
		$result = ob_get_clean();
		if ( empty( $result ) && ( ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ) || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) ) {
			return sprintf( esc_html__( '%1$sPlease input the product description.%2$s', 'porto-functionality' ), '<div class="elementor-panel-alert elementor-panel-alert-info">', '</div>' );
		}

		if ( ! empty( $shortcode_class ) ) {
			$result = '<div class="' . esc_attr( $shortcode_class ) . '">' . $result . '</div>';
		}
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_add_to_cart( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		global $porto_settings;
		if ( ! empty( $porto_settings['product-show-price-role'] ) ) {
			$hide_price = false;
			if ( ! is_user_logged_in() ) {
				$hide_price = true;
			} else {
				foreach ( wp_get_current_user()->roles as $role => $val ) {
					if ( ! in_array( $val, $porto_settings['product-show-price-role'] ) ) {
						$hide_price = true;
						break;
					}
				}
			}
			if ( $hide_price ) {
				return null;
			}
		}

		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_add_to_cart';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'quantity_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_height',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'minus_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_height',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'qty_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_height',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'plus_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'price_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'price_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'price_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_bg_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_hover_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_hover_bg_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'view_btn_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'view_btn_bg_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'view_btn_hover_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'view_btn_hover_bg_clr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'cart_btn_sz',
						'selectors'  => true,
					),
					array(
						'param_name' => 'cart_btn_pd',
						'selectors'  => true,
					),
					array(
						'param_name' => 'cart_btn_hd',
						'selectors'  => true,
					),
					array(
						'param_name' => 'form_btn_layout',
						'selectors'  => true,
					),
					array(
						'param_name' => 'form_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'form_padding',
						'selectors'  => true,
					),
					array(
						'param_name' => 'form_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'form_br_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'variation_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'variation_tr',
						'selectors'  => true,
					),
					array(
						'param_name' => 'variation_tr_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'variation_label_block',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
		}

		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		echo '<div class="product-summary-wrap">';
		if ( ( defined( 'ELEMENTOR_VERSION' ) || function_exists( 'register_block_type' ) ) && ! wp_doing_ajax() ) {
			if ( ! has_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart' ) ) {
				add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			}
			if ( ! has_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart' ) ) {
				add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			}
			if ( ! has_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart' ) ) {
				add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			}
			if ( ! has_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart' ) ) {
				add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
			}
			if ( ! has_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button' ) ) {
				add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			}
		}

		$catalog_hide_cart = false;
		if ( ! empty( $porto_settings['catalog-enable'] ) ) {
			if ( $porto_settings['catalog-admin'] || ( ! $porto_settings['catalog-admin'] && ! ( current_user_can( 'administrator' ) && is_user_logged_in() ) ) ) {
				if ( ! $porto_settings['catalog-cart'] ) {
					$catalog_hide_cart = true;
				}
			}
		}
		if ( $catalog_hide_cart ) {
			global $product;
			if ( 'variable' == $product->get_type() ) {
				porto_woocommerce_template_single_add_to_cart();
			} elseif ( ! empty( $porto_settings['catalog-readmore'] ) ) {
				porto_woocommerce_readmore_button();
			}
		} else {
		woocommerce_template_single_add_to_cart();
		}

		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			echo '<script>theme.WooQtyField.initialize();</script>';
		}
		echo '</div>';
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();

		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_meta( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_meta';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'view',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing1',
						'selectors'  => true,
					),
					array(
						'param_name' => 'spacing2',
						'selectors'  => true,
					),
					array(
						'param_name' => 'text_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'text_size',
						'selectors'  => true,
					),
					array(
						'param_name' => 'link_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'link_hover_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'link_size',
						'selectors'  => true,
					),
				)
			);

			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_porto_meta'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_porto_meta'], ' ' ), $shortcode_name, $atts );
			}
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		woocommerce_template_single_meta();
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();
		$result = ob_get_clean();

		if ( ! strpos( $result,'<span' ) && ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) ) {
			global $porto_settings;
			if ( ! empty( $porto_settings['product-metas'] ) && '-' == $porto_settings['product-metas'][0] ) {
				return sprintf( __( '%1$sPlease select the %3$smeta%4$s value in Theme option.%2$s', 'porto-functionality' ), '<div class="elementor-panel-alert elementor-panel-alert-info">', '</div>', '<a class="font-weight-bold porto-setting-link" href="' . porto_get_theme_option_url( 'product-metas' ) . '" target="_blank">', '</a>' );;
			} else {
				return sprintf( esc_html__( '%1$sPlease input the product meta.%2$s', 'porto-functionality' ), '<div class="elementor-panel-alert elementor-panel-alert-info">', '</div>' );
			}
		}

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_tabs( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'style' => '', // tabs or accordion
				),
				$atts
			)
		);
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_tabs';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'is_flex',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_bottom_space',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_border_active',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_padding',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_text_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_border_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'active_tab_text_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'active_tab_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'active_tab_border_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_typography',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_border_radius',
						'selectors'  => true,
					),
					array(
						'param_name' => 'tab_title_space',
						'selectors'  => true,
					),
					array(
						'param_name' => 'text_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'content_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'content_typography',
						'selectors'  => true,
					),
					array(
						'param_name' => 'heading_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'content_heading_typography',
						'selectors'  => true,
					),
					array(
						'param_name' => 'panel_padding',
						'selectors'  => true,
					),
					array(
						'param_name' => 'panel_border_width',
						'selectors'  => true,
					),
					array(
						'param_name' => 'panel_bd_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'panel_border_radius',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );

			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}

			if ( ! empty( $atts['hide_tabs'] ) ) {
				$atts['hide_tabs'] = explode( ',', $atts['hide_tabs'] );
			}
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		if ( 'vertical' == $style ) {
			echo '<style>.woocommerce-tabs .resp-tabs-list { display: none; }
					.woocommerce-tabs h2.resp-accordion { display: block; }
					.woocommerce-tabs h2.resp-accordion:before { font-size: 20px; font-weight: 400; position: relative; top: -4px; }
					.woocommerce-tabs .tab-content { border-top: none; padding-' . ( is_rtl() ? 'right' : 'left' ) . ': 20px; }</style>';
		}

		if ( defined( 'ELEMENTOR_VERSION' ) && ! wp_doing_ajax() ) {
			if ( ! has_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' ) ) {
				add_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );
			}
			if ( ! has_filter( 'woocommerce_product_tabs', 'woocommerce_sort_product_tabs' ) ) {
				add_filter( 'woocommerce_product_tabs', 'woocommerce_sort_product_tabs', 99 );
			}
		}
		wc_get_template( 'single-product/tabs/tabs.php', array( 'closed' => ! empty( $atts['accordion_closed'] ) ? true : false, 'hide_tabs' => ! empty( $atts['hide_tabs'] ) ? $atts['hide_tabs'] : '' ) );
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();
		$result = ob_get_clean();

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	public function shortcode_single_product_upsell( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		global $product, $porto_settings;
		if ( apply_filters( 'porto_legacy_mode', true ) && empty( $porto_settings['product-upsells'] ) ) {
			return;
		}
		$upsells = $product->get_upsell_ids();
		if ( sizeof( $upsells ) === 0 ) {
			return;
		}
		if ( in_array( $product->get_id(), $upsells ) ) {
			$upsells = array_diff( $upsells, array( $product->get_id() ) );
		}

		if ( ! empty( $atts['columns'] ) ) {
			$columns = $atts['columns'];
		} else {
			$columns = isset( $porto_settings['product-upsells-cols'] ) ? $porto_settings['product-upsells-cols'] : ( isset( $porto_settings['product-cols'] ) ? $porto_settings['product-cols'] : 3 );
		}
		if ( ! $columns ) {
			$columns = 4;
		}
		$args = array(
			'posts_per_page' => empty( $atts['count'] ) ? ( isset( $porto_settings['product-upsells-count'] ) ? $porto_settings['product-upsells-count'] : '10' ) : $atts['count'],
			'columns'        => $columns,
	  		'orderby'        => empty( $atts['orderby'] ) ? 'rand' : $atts['orderby'], // @codingStandardsIgnoreLine.
		);

		$args     = apply_filters( 'porto_woocommerce_upsell_display_args', $args );
		$str_atts = 'ids="' . esc_attr( implode( ',', $upsells ) ) . '" count="' . intval( $args['posts_per_page'] ) . '" columns="' . intval( $args['columns'] ) . '" orderby="' . esc_attr( $args['orderby'] ) . '" pagination="1" navigation="" dots_pos="show-dots-title-right"';
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $val ) {
				if ( in_array( $key, array( 'count', 'columns', 'orderby' ) ) || 0 === strpos( $key, '_' ) ) {
					continue;
				}
				$str_atts .= ' ' . esc_html( $key ) . '="' . esc_attr( $val ) . '"';
			}
		}
		if ( empty( $atts['view'] ) ) {
			$str_atts .= ' view="products-slider"';
		}

		ob_start();

		echo '<div class="upsells products">';
		echo '<h2 class="slider-title"><span class="inline-title">' . esc_html__( 'You may also like&hellip;', 'woocommerce' ) . '</span><span class="line"></span></h2>';
		echo do_shortcode( '[porto_products ' . $str_atts . ']' );
		echo '</div>';

		$this->reset_global_product_variable();

		return ob_get_clean();
	}

	public function shortcode_single_product_related( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( ! empty( $atts['columns'] ) ) {
			$columns = $atts['columns'];
		} else {
			$columns = isset( $porto_settings['product-related-cols'] ) ? $porto_settings['product-related-cols'] : ( isset( $porto_settings['product-cols'] ) ? $porto_settings['product-cols'] : 3 );
		}
		if ( ! $columns ) {
			$columns = 4;
		}
		$args = array(
			'posts_per_page' => empty( $atts['count'] ) ? ( isset( $porto_settings['product-related-count'] ) ? $porto_settings['product-related-count'] : '10' ) : $atts['count'],
			'columns'        => $columns,
	  		'orderby'        => empty( $atts['orderby'] ) ? 'rand' : $atts['orderby'], // @codingStandardsIgnoreLine.
		);
		$args = apply_filters( 'woocommerce_related_products_args', $args );
		if ( empty( $args ) ) {
			$this->reset_global_product_variable();
			return;
		}
		global $product, $porto_settings;
		$related = wc_get_related_products( $product->get_id(), $args['posts_per_page'] );
		if ( sizeof( $related ) === 0 || ( apply_filters( 'porto_legacy_mode', true ) && empty( $porto_settings['product-related'] ) ) ) {
			$this->reset_global_product_variable();
			return;
		}
		if ( in_array( $product->get_id(), $related ) ) {
			$related = array_diff( $related, array( $product->get_id() ) );
		}

		$str_atts = 'ids="' . esc_attr( implode( ',', $related ) ) . '" count="' . intval( $args['posts_per_page'] ) . '" columns="' . intval( $args['columns'] ) . '" orderby="' . esc_attr( $args['orderby'] ) . '" pagination="1" navigation="" dots_pos="show-dots-title-right"';
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $val ) {
				if ( in_array( $key, array( 'count', 'columns', 'orderby' ) ) || 0 === strpos( $key, '_' ) ) {
					continue;
				}
				$str_atts .= ' ' . esc_html( $key ) . '="' . esc_attr( $val ) . '"';
			}
		}
		if ( empty( $atts['view'] ) ) {
			$str_atts .= ' view="products-slider"';
		}

		ob_start();

		echo '<div class="related products">';
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
		if ( $heading ) {
			echo '<h2 class="slider-title">' . esc_html( $heading ) . '</h2>';
		}
		echo do_shortcode( '[porto_products ' . $str_atts . ']' );
		echo '</div>';

		$this->reset_global_product_variable();

		return ob_get_clean();
	}

	public function shortcode_single_product_linked( $atts, $builder ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		if ( empty( $atts ) ) {
			$atts = array();
		}
		if ( $template = porto_shortcode_template( 'porto_posts_grid' ) ) {
			ob_start();
			$internal_css = '';

			if ( defined( 'WPB_VC_VERSION' ) && 'elementor' != $builder ) {
				// Shortcode class
				$shortcode_class = ' wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
					$atts,
					'porto_single_product_linked',
					array(
						array(
							'param_name' => 'spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'p_align',
							'selectors'  => true,
						),
						array(
							'param_name' => 'p_margin',
							'selectors'  => true,
						),
						array(
							'param_name' => 'lm_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'lm_typography',
							'selectors'  => true,
						),
						array(
							'param_name' => 'lm_padding',
							'selectors'  => true,
						),
						array(
							'param_name' => 'lm_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_align',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_between_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_spacing',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_typography',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_normal_bgc',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_normal_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_active_bgc',
							'selectors'  => true,
						),
						array(
							'param_name' => 'filter_active_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_pos_top',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_pos_bottom',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_pos_left',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_pos_right',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_br_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_abr_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_abg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_original',
							'selectors'  => true,
						),
						array(
							'param_name' => 'dots_visible',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_visible',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_fs',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_width',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_height',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_br',
							'selectors'  => true,
						),
						array(
							'param_name' => 'navs_h_origin',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_h_pos',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_v_pos',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_h_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_h_bg_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_br_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'nav_h_br_color',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hd_typography',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hd_space',
							'selectors'  => true,
						),
						array(
							'param_name' => 'heading_align',
							'selectors'  => true,
						),
						array(
							'param_name' => 'hide_sp',
							'selectors'  => true,
						),
						array(
							'param_name' => 'separator_space',
							'selectors'  => true,
						),
						array(
							'param_name' => 'flick_opacity',
							'selectors'  => true,
						),
					)
				);
				$internal_css    = PortoShortcodesClass::generate_wpb_css( 'porto_single_product_linked', $atts );
				if ( empty( $atts['linked_product'] ) ) {
					$atts['linked_product'] = 'related';
				}
			} elseif ( defined( 'ELEMENTOR_VERSION' ) ) {
				if ( empty( $atts['spacing'] ) ) {
					$atts['spacing'] = '';
				}
				if ( is_array( $atts['count'] ) ) {
					if ( isset( $atts['count']['size'] ) ) {
						$atts['count'] = $atts['count']['size'];
					} else {
						$atts['count'] = '';
					}
				}
			}
			if ( ! empty( $atts['post_type'] ) ) {
				$atts['linked_product'] = $atts['post_type'];
			}
			if ( ! empty( $atts['show_heading'] ) ) {
				$atts['linked_heading'] = ! empty( $atts['heading_text'] ) ? $atts['heading_text'] : __( 'Related Products','porto-functionality' );
			}
			$atts['post_type'] = 'product';
			include $template;
			$result = ob_get_clean();

			if ( $result && $internal_css ) {
				$first_tag_index = strpos( $result, '>' );
				if ( $first_tag_index ) {
					$result = substr( $result, 0, $first_tag_index + 1 ) . '<style>' . wp_strip_all_tags( $internal_css ) . '</style>' . substr( $result, $first_tag_index + 1 );
				}
			}
			$this->reset_global_product_variable();
			return $result;
		}
	}

	public function shortcode_single_product_next_prev_nav( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_next_prev_nav';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'nav_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'nav_bg_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'nav_border_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'dropdown_padding',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );

			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
		}
		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		add_filter( 'porto_is_product', '__return_true' );
		porto_woocommerce_product_nav();

		$this->reset_global_product_variable();
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	/**
	 * Product Sticky Add To Cart
	 *
	 * @since 2.3.0
	 */
	public function shortcode_single_product_addcart_sticky( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

		$mobile_pos = '';
		if ( ! empty( $atts['enable_mobile'] ) ) {
			$mobile_pos = ' show-mobile';
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_addcart_sticky';
			// Shortcode class
			$shortcode_class = 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'title_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'title_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'price_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'price_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'rating_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'rating_margin',
						'selectors'  => true,
					),
					array(
						'param_name' => 'av_margin',
						'selectors'  => true,
					),
	
					array(
						'param_name' => 'btn_padding',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_ht',
						'selectors'  => true,
					),
					array(
						'param_name' => 'av_font',
						'selectors'  => true,
					),
					array(
						'param_name' => 'av_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'btn_font',
						'selectors'  => true,
					),
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
		}
		
		ob_start();
		global $porto_settings;
		if ( isset( $porto_settings['product-sticky-addcart'] ) ) {
			$setting_backup = $porto_settings['product-sticky-addcart'];
		}
		$porto_settings['product-sticky-addcart'] = empty( $atts['pos'] ) ? 'top' : $atts['pos'];

		add_filter( 'porto_is_product', '__return_true' );

		if ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
			echo sprintf( __( '%1$sTo use the %3$sSticky Add To Cart%4$s, you should use %3$sProduct Add To Cart%4$s widget.%2$s', 'porto-functionality' ), '<div class="elementor-panel-alert elementor-panel-alert-info">', '</div>', '<b>', '</b>' );
		}
		$el_class = empty( $atts['el_class'] ) ? $mobile_pos : trim( $atts['el_class'] ) . $mobile_pos;
		if ( ! empty ( $shortcode_class ) ) {
			$el_class .= ' ' . $shortcode_class;
		}
		porto_woocommerce_product_sticky_addcart( $el_class );
		if ( isset( $setting_backup ) ) {
			$porto_settings['product-sticky-addcart'] = $setting_backup;
		}

		$this->reset_global_product_variable();
		$result = ob_get_clean();
		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}
		return $result;
	}

	function load_custom_product_shortcodes() {
		if ( ! $this->display_product_page_elements ) {
			$this->display_product_page_elements = PortoBuilders::check_load_wpb_elements( 'product' );
		}

		if ( ! $this->display_product_page_elements ) {
			return;
		}


		$fonts = array(
			esc_html__( 'Default', 'porto' ) => '',
			esc_html__( 'Inherit', 'porto')  => 'inherit',
		);
		if ( function_exists( 'porto_include_google_font' ) ) {
			foreach( porto_include_google_font() as $font ) {
				$font_key = str_replace( '%2C', ',', $font );
				$font_key = str_replace( '%27', '\'', $font_key );	
				$fonts[ $font_key ] = $font;
			}
		}
		$custom_fonts = get_option( 'porto_custom_fonts', array() );
		if ( ! empty( $custom_fonts ) ) {
			foreach ( $custom_fonts as $c_fonts ) {
				if ( ! empty( $c_fonts ) ) {
					foreach ( $c_fonts as $c_font_name => $font_fields ) {
						$fonts[] = str_replace( '+', ' ', $c_font_name );
					}
				}
			}
		}

		$left             = is_rtl() ? 'right' : 'left';
		$right            = is_rtl() ? 'left' : 'right';
		$order_by_values  = porto_vc_woo_order_by();
		$order_way_values = porto_vc_woo_order_way();
		$custom_class     = porto_vc_custom_class();
		$products_args    = array(
			array(
				'type'       => 'porto_param_heading',
				'param_name' => 'notice_wrong_data',
				'text'       => __( 'This element was deprecated in 6.3.0. Please use Linked Products Widget instead.', 'porto-functionality' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'View mode', 'porto-functionality' ),
				'param_name'  => 'view',
				'value'       => porto_sh_commons( 'products_view_mode' ),
				'std'         => 'products-slider',
				'admin_label' => true,
			),
			array(
				'type'       => 'porto_image_select',
				'heading'    => __( 'Grid Layout', 'porto-functionality' ),
				'param_name' => 'grid_layout',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'creative' ),
				),
				'std'        => '1',
				'value'      => porto_sh_commons( 'masonry_layouts' ),
			),
			array(
				'type'       => 'number',
				'heading'    => __( 'Grid Height (px)', 'porto-functionality' ),
				'param_name' => 'grid_height',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'creative' ),
				),
				'suffix'     => 'px',
				'std'        => 600,
			),
			array(
				'type'        => 'number',
				'heading'     => __( 'Column Spacing (px)', 'porto-functionality' ),
				'description' => __( 'Leave blank if you use theme default value.', 'porto-functionality' ),
				'param_name'  => 'spacing',
				'dependency'  => array(
					'element' => 'view',
					'value'   => array( 'grid', 'creative', 'products-slider' ),
				),
				'suffix'      => 'px',
				'std'         => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Columns', 'porto-functionality' ),
				'param_name' => 'columns',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'products-slider', 'grid', 'divider' ),
				),
				'std'        => '4',
				'value'      => porto_sh_commons( 'products_columns' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Columns on mobile ( <= 575px )', 'porto-functionality' ),
				'param_name' => 'columns_mobile',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'products-slider', 'grid', 'divider', 'list' ),
				),
				'std'        => '',
				'value'      => array(
					__( 'Default', 'porto-functionality' ) => '',
					'1'                                    => '1',
					'2'                                    => '2',
					'3'                                    => '3',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Pagination Style', 'porto-functionality' ),
				'param_name' => 'pagination_style',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'list', 'grid', 'divider' ),
				),
				'std'        => '',
				'value'      => array(
					__( 'No pagination', 'porto-functionality' ) => '',
					__( 'Default' )   => 'default',
					__( 'Load more' ) => 'load_more',
				),
			),
			array(
				'type'        => 'number',
				'heading'     => __( 'Number of Products per page', 'porto-functionality' ),
				'description' => __( 'Leave blank if you use default value.', 'porto-functionality' ),
				'param_name'  => 'count',
				'admin_label' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Order by', 'js_composer' ),
				'param_name'  => 'orderby',
				'value'       => $order_by_values,
				/* translators: %s: Wordpress codex page */
				'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Order way', 'porto-functionality' ),
				'param_name'  => 'order',
				'value'       => $order_way_values,
				/* translators: %s: Wordpress codex page */
				'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Product Layout', 'porto-functionality' ),
				'description' => __( 'Select position of add to cart, add to wishlist, quickview.', 'porto-functionality' ),
				'param_name'  => 'addlinks_pos',
				'value'       => porto_sh_commons( 'products_addlinks_pos' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Use simple layout?', 'porto-functionality' ),
				'description' => __( 'If you check this option, it will display product title and price only.', 'porto-functionality' ),
				'param_name'  => 'use_simple',
				'std'         => 'no',
			),
			array(
				'type'       => 'number',
				'heading'    => __( 'Overlay Background Opacity (%)', 'porto-functionality' ),
				'param_name' => 'overlay_bg_opacity',
				'dependency' => array(
					'element' => 'addlinks_pos',
					'value'   => array( 'onimage2', 'onimage3' ),
				),
				'suffix'     => '%',
				'std'        => '30',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image Size', 'porto-functionality' ),
				'param_name' => 'image_size',
				'dependency' => array(
					'element' => 'view',
					'value'   => array( 'products-slider', 'grid', 'divider', 'list' ),
				),
				'value'      => porto_sh_commons( 'image_sizes' ),
				'std'        => '',
			),
		);

		vc_map(
			array(
				'name'        => __( 'Product Image', 'porto-functionality' ),
				'description' => __( 'Show product images using by various layouts.', 'porto-functionality' ),
				'base'        => 'porto_single_product_image',
				'icon'        => PORTO_WIDGET_URL . 'sp-image.png',
			    'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_gallery_plugin',
						'text'       => sprintf( __( 'If you use %1$s\'Additional Variation Images Gallery for WooCommerce plugin\'%2$s, you should change the layout %3$sHere%4$s. This plugin overrides the our theme layout.', 'porto-functionality' ), '<b>', '</b>', '<a href="' . admin_url( 'admin.php?page=getwooplugins-settings&tab=woo_variation_gallery' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_skin',
						'text'       => sprintf( __( 'You can change the global value in %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-thumbs' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'        => 'porto_image_select',
						'heading'     => __( 'Style', 'porto-functionality' ),
						'description' => __( 'Controls the layout of product gallery images.', 'porto-functionality' ),
						'param_name'  => 'style',
						'value'       => array(
							'product-images/sp-image-default.jpg'   => '',
							'product-images/sp-image-extended.jpg'  => 'extended',
							'product-images/sp-image-grid.jpg'      => 'grid',
							'product-images/sp-image-thumbs-on.jpg' => 'full_width',
							'product-images/sp-image-list.jpg'      => 'sticky_info',
							'product-images/sp-image-left-1.jpg'    => 'transparent',
							'product-images/sp-image-left-2.jpg'    => 'centered_vertical_zoom',
							'product-images/sp-image-scatted.jpg'   => 'scatted',
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Spacing', 'porto-functionality' ),
						'description' => __( 'Controls the spacing between thumbnails.', 'porto-functionality' ),
						'param_name'  => 'spacing',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-layout-centered_vertical_zoom .product-thumbnails-inner .img-thumbnail' => 'margin-bottom: {{VALUE}}{{UNIT}};',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'centered_vertical_zoom',
						),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Spacing', 'porto-functionality' ),
						'description' => __( 'Controls the spacing between images.', 'porto-functionality' ),
						'param_name'  => 'spacing2',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-images-block .img-thumbnail' => 'margin-bottom: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-layout-grid .product-images-block' => '--bs-gutter-x: {{VALUE}}{{UNIT}};',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'sticky_info', 'grid' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'br_color',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .img-thumbnail .inner' => 'border-color: {{VALUE}};',
						),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Thumbnail Width', 'porto-functionality' ),
						'description' => __( 'Controls the width of thumbnail area.', 'porto-functionality' ),
						'param_name'  => 'thumbnail_width',
						'units'       => array( 'px', 'em' ),
						'responsive'  => true,
						'selectors'   => array(
							'{{WRAPPER}} .product-layout-centered_vertical_zoom .product-thumbnails' => 'width: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-layout-centered_vertical_zoom .product-images' => 'width: calc(100% - {{VALUE}}{{UNIT}});',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'centered_vertical_zoom',
						),
						'group'       => __( 'Thumbnail Image', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Thumbnail Image Width', 'porto-functionality' ),
						'description' => __( 'Controls the width of thumbnail image area.', 'porto-functionality' ),
						'param_name'  => 'thumbnail_img_width',
						'units'       => array( 'px', 'em' ),
						'responsive'  => true,
						'selectors'   => array(
							'{{WRAPPER}} .product-layout-centered_vertical_zoom .product-thumbnails .img-thumbnail' => 'width: {{VALUE}}{{UNIT}};',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'centered_vertical_zoom',
						),
						'group'       => __( 'Thumbnail Image', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_thumb_skin',
						'text'       => sprintf( __( 'You can change the thumbnail info in %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom / Thumbnails Count%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-thumbs-count' ) . '" target="_blank">', '</a>' ),
						'group'      => __( 'Thumbnail Image', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => __( 'Thumbnail Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the border color of thumbnail.', 'porto-functionality' ),
						'param_name'  => 'thumbnail_br_color',
						'selectors'   => array(
							'{{WRAPPER}} .product-thumbs-slider.owl-carousel .img-thumbnail, {{WRAPPER}} .product-layout-full_width .img-thumbnail, {{WRAPPER}} .product-thumbs-vertical-slider img, {{WRAPPER}} .product-layout-centered_vertical_zoom .img-thumbnail' => 'border-color: {{VALUE}};',
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => array( '', 'transparent', 'centered_vertical_zoom', 'full_width' ),
						),
						'group'       => __( 'Thumbnail Image', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => __( 'Thumbnail Hover Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the border \'hover & active\' color of thumbnail.', 'porto-functionality' ),
						'param_name'  => 'thumbnail_hover_br_color',
						'selectors'   => array(
							'{{WRAPPER}} .product-thumbs-slider .owl-item.selected .img-thumbnail, html:not(.touch) {{WRAPPER}} .product-thumbs-slider .owl-item:hover .img-thumbnail, {{WRAPPER}} .product-layout-full_width .img-thumbnail.selected, {{WRAPPER}} .product-thumbs-vertical-slider .slick-slide.selected img, {{WRAPPER}} .product-layout-centered_vertical_zoom .img-thumbnail.selected' => 'border-color: {{VALUE}};',
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => array( '', 'transparent', 'centered_vertical_zoom', 'full_width' ),
						),
						'group'       => __( 'Thumbnail Image', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Spacing (px)', 'porto-functionality' ),
						'description' => __( 'Controls the spacing between images.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-image-slider' => '--porto-el-spacing: {{VALUE}}px;',
						),
						'param_name'  => 'spacing1',
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'group'       => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Enable Loop', 'porto-functionality' ),
						'param_name' => 'set_loop',
						'value'      => array(
							__( 'Theme Options', 'porto-functionality' ) => '',
							__( 'Yes', 'porto-functionality' ) => 'yes',
							__( 'No', 'porto-functionality' )  => 'no',
						),
						'std'        => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Enable Center Mode', 'porto-functionality' ),
						'param_name' => 'center_mode',
						'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'std'        => 'yes',
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Columns', 'porto-functionality' ),
						'param_name' => 'columns',
						'std'        => '',
						'value'      => array_merge(
							array(
								__( 'Default', 'porto-functionality' ) => ''
							),
							porto_sh_commons( 'products_columns' ),
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Columns on tablet ( <= 991px )', 'porto-functionality' ),
						'param_name' => 'columns_tablet',
						'std'        => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'value'      => array(
							__( 'Default', 'porto-functionality' ) => '',
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Columns on mobile ( <= 575px )', 'porto-functionality' ),
						'param_name' => 'columns_mobile',
						'std'        => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'value'      => array(
							__( 'Default', 'porto-functionality' ) => '',
							'1' => '1',
							'2' => '2',
							'3' => '3',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Enable Flick Type', 'porto-functionality' ),
						'param_name'  => 'enable_flick',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_carousel_flick.gif"/>',
						'dependency' => array(
							'element' => 'style',
							'value'   => 'extended',
						),
						'description' => sprintf( __( 'This option shows the carousel at the container\'s width. %1$sRead More%2$s', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-porto-flick-carousel" target="_blank">', '</a>' ),
						'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'group'       => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'number',
						'heading'    => __( 'Opacity of Inactive item', 'porto-functionality' ),
						'param_name' => 'flick_opacity',
						'dependency' => array(
							'element'   => 'enable_flick',
							'not_empty' => true,
						),
						'min'        => 0,
						'max'        => 1,
						'std'        => 1,
						'selectors'  => array(
							'{{WRAPPER}} .owl-item:not(.active)' => 'opacity: {{VALUE}};',
						),
						'group'      => __( 'Slider Option', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_skin',
						'text'       => sprintf( __( 'To show zoom icon, you should enable %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom / Image Popup%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-image-popup' ) . '" target="_blank">', '</a>' ),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Position of Popup Icon (Left/Right)', 'porto-functionality' ),
						'description' => __( 'Aligned-end is the default.', 'porto-functionality' ),
						'param_name'  => 'icon_pos',
						'value'       => array( $left => 'yes' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-images .zoom'                => "{$left}: 4px;",
							'{{WRAPPER}} .product-images .image-galley-viewer' => "{$left}: 4px;",
						),
						'group'       => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Overlay Icon Type', 'porto-functionality' ),
						'param_name' => 'icon_type',
						'value'      => array(
							__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
							__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
							__( 'Porto Icon', 'porto-functionality' ) => 'porto',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Overlay Icon', 'porto-functionality' ),
						'param_name' => 'icon_cl',
						'value'      => '',
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => array( 'fontawesome' ),
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Overlay Icon', 'porto-functionality' ),
						'param_name' => 'icon_simpleline',
						'settings'   => array(
							'type'         => 'simpleline',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'simpleline',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Overlay Icon', 'porto-functionality' ),
						'param_name' => 'icon_porto',
						'settings'   => array(
							'type'         => 'porto',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'porto',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Icon Background', 'porto-functionality' ),
						'param_name' => 'icon_bgc',
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom, {{WRAPPER}} .product-images .img-thumbnail:hover .zoom' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .product-images .image-galley-viewer, {{WRAPPER}} .product-images .img-thumbnail:hover .image-galley-viewer' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Icon Background Size', 'porto-functionality' ),
						'param_name' => 'icon_bg_size',
						'units'      => array( 'px', 'rem', 'em' ),
						'dependency' => array(
							'element'   => 'icon_bgc',
							'not_empty' => true,
						),
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom' => 'width: {{VALUE}}{{UNIT}}; height: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .zoom i' => 'line-height: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .image-galley-viewer' => 'width: {{VALUE}}{{UNIT}}; height: {{VALUE}}{{UNIT}}; --porto-product-action-width: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .image-galley-viewer i' => 'line-height: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Icon Color', 'porto-functionality' ),
						'param_name' => 'icon_clr',
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .product-images .image-galley-viewer i' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Icon Size', 'porto-functionality' ),
						'param_name' => 'icon_fs',
						'units'      => array( 'px', 'rem', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom i' => 'font-size: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .image-galley-viewer i' => 'font-size: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Border Width', 'porto-functionality' ),
						'param_name' => 'popup_br_width',
						'units'      => array( 'px', 'rem' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom' => 'border: {{VALUE}}{{UNIT}} solid; box-sizing: content-box;',
							'{{WRAPPER}} .product-images .image-galley-viewer' => 'border: {{VALUE}}{{UNIT}} solid; box-sizing: content-box; --porto-product-action-border: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'param_name' => 'popup_br_color',
						'selectors'  => array(
							'{{WRAPPER}} .product-images .zoom' => 'border-color: {{VALUE}};',
							'{{WRAPPER}} .product-images .image-galley-viewer' => 'border-color: {{VALUE}};',
						),
						'group'      => __( 'Popup Icon', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Space from Corner', 'porto-functionality' ),
						'description' => __( 'Control the space from the corner of the main image.', 'porto-functionality' ),
						'param_name'  => 'popup_space',
						'units'       => array( 'px', 'rem' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-images .zoom'                             => 'margin: 0 {{VALUE}}{{UNIT}} {{VALUE}}{{UNIT}} {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .image-galley-viewer'              => 'margin: 0 {{VALUE}}{{UNIT}}; --porto-product-action-margin: {{VALUE}}{{UNIT}};',
							'{{WRAPPER}} .product-images .image-galley-viewer.without-zoom' => 'margin-bottom: {{VALUE}}{{UNIT}};',
						),
						'group'       => __( 'Popup Icon', 'porto-functionality' ),
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
				'name'        => __( 'Product Title', 'porto-functionality' ),
				'description' => __( 'Show title in single product page.', 'porto-functionality' ),
				'base'        => 'porto_single_product_title',
				'icon'        => PORTO_WIDGET_URL . 'sp-title.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Font Family', 'porto-functionality' ),
						'param_name' => 'font_family',
						'std'        => '',
						'value'      => $fonts,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Font Size', 'porto-functionality' ),
						'param_name'  => 'font_size',
						'admin_label' => true,
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Font Weight', 'porto-functionality' ),
						'param_name'  => 'font_weight',
						'value'       => array(
							__( 'Default', 'porto-functionality' ) => '',
							'100' => '100',
							'200' => '200',
							'300' => '300',
							'400' => '400',
							'500' => '500',
							'600' => '600',
							'700' => '700',
							'800' => '800',
							'900' => '900',
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Letter Spacing', 'porto-functionality' ),
						'description' => __( 'Please inputs with units together. e.g: -0.1em', 'porto-functionality' ),
						'param_name'  => 'letter_spacing',
						'admin_label' => true,
					),
					array(
						'type'       => 'colorpicker',
						'class'      => '',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'param_name' => 'color',
						'value'      => '',
					),
					$custom_class,
					array(
						'type'             => 'css_editor',
						'heading'          => __( 'Css', 'porto-functionality' ),
						'param_name'       => 'css_porto_title',
						'group'            => __( 'Design', 'porto-functionality' ),
						'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					),
				),
			)
		);
		vc_map(
			array(
				'name'                    => __( 'Product Description', 'porto-functionality' ),
				'description'             => __( 'Show description in single product page.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_description',
				'icon'                    => PORTO_WIDGET_URL . 'sp-desc.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Typography', 'porto-functionality' ),
						'param_name' => 'description_font',
						'selectors'  => array(
							'{{WRAPPER}} p, {{WRAPPER}}',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'description_color',
						'heading'     => __( 'Color', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} p, {{WRAPPER}}' => 'color: {{VALUE}};',
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
				'name'        => __( 'Product Rating', 'porto-functionality' ),
				'description' => __( 'Show rating in single product page.', 'porto-functionality' ),
				'base'        => 'porto_single_product_rating',
				'icon'        => PORTO_WIDGET_URL . 'sp-rating.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Rating Size', 'porto-functionality' ),
						'description' => __( 'Controls the size of rating.', 'porto-functionality' ),
						'param_name'  => 'font_size',
						'admin_label' => true,
					),
					array(
						'type'       => 'colorpicker',
						'class'      => '',
						'heading'    => __( 'Background Star Color', 'porto-functionality' ),
						'param_name' => 'bgcolor',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'class'      => '',
						'heading'    => __( 'Active Color', 'porto-functionality' ),
						'param_name' => 'color',
						'value'      => '',
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Review Typography', 'porto-functionality' ),
						'param_name' => 'rating_font',
						'selectors'  => array(
							'{{WRAPPER}} .review-link',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'review_color',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_rating-review_color.gif"/>',
						'heading'     => __( 'Review Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of review.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .review-link' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Hide Separator', 'porto-functionality' ),
						'description' => __( 'Show/Hide separator.', 'porto-functionality' ),
						'param_name'  => 'separator',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_rating-separator.gif"/>',
						'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'selectors'   => array(
							'{{WRAPPER}} .woocommerce-product-rating::after' => 'content: none;',
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Direction', 'porto-functionality' ),
						'param_name'  => 'flex_direction',
						'description' => __( 'Controls the direction: horizontal, vertical', 'porto-functionality' ),
						'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'selectors'   => array(
							'{{WRAPPER}} .review-link' => 'display: block;',
						),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Between Spacing', 'porto-functionality' ),
						'param_name' => 'between_spacing',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .review-link' => 'margin-top: {{VALUE}}{{UNIT}};',
						),
						'dependency' => array(
							'element' => 'flex_direction',
							'value'   => 'yes',
						),
					),
					$custom_class,
					array(
						'type'             => 'css_editor',
						'heading'          => __( 'Css', 'porto-functionality' ),
						'param_name'       => 'css_porto_rating',
						'group'            => __( 'Design', 'porto-functionality' ),
						'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					),
				),
			)
		);
		vc_map(
			array(
				'name'        => __( 'Product Hooks (Deprecated)', 'porto-functionality' ),
				'description' => __( 'Display the woocommerce default actions.', 'porto-functionality' ),
				'base'        => 'porto_single_product_actions',
				'icon'        => PORTO_WIDGET_URL . 'sp-hooks.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'action', 'porto-functionality' ),
						'param_name'  => 'action',
						'value'       => array(
							'woocommerce_before_single_product_summary'       => 'woocommerce_before_single_product_summary',
							'woocommerce_single_product_summary'              => 'woocommerce_single_product_summary',
							'woocommerce_after_single_product_summary'        => 'woocommerce_after_single_product_summary',
							'porto_woocommerce_before_single_product_summary' => 'porto_woocommerce_before_single_product_summary',
							'porto_woocommerce_single_product_summary2'       => 'porto_woocommerce_single_product_summary2',
							'woocommerce_share'                               => 'woocommerce_share',
							'porto_woocommerce_product_sticky_addcart'        => 'porto_woocommerce_product_sticky_addcart',

							'woocommerce_before_single_product'               => 'woocommerce_before_single_product',
							'woocommerce_product_meta_start'                  => 'woocommerce_product_meta_start',
							'woocommerce_product_meta_end'                    => 'woocommerce_product_meta_end',
							'woocommerce_after_single_product'                => 'woocommerce_after_single_product',
							'woocommerce_product_thumbnails'                  => 'woocommerce_product_thumbnails',							
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'For extra plugin', 'porto-functionality' ),
						'description' => sprintf( esc_html__( 'Apply hooks for extra plugins like Perfect Brands WooCommerce, YITH Brands and so on. Please see %1$sdocumentation%2$s.', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-extra-plugin-like-perfect-brands-yith-brands/" target="_blank">', '</a>' ),
						'param_name'  => 'extra_plugin',
						'dependency'  => array(
							'element'            => 'action',
							'value_not_equal_to' => array( 'porto_woocommerce_before_single_product_summary', 'porto_woocommerce_single_product_summary2', 'porto_woocommerce_product_sticky_addcart' ),
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
				'name'                    => __( 'Product Price', 'porto-functionality' ),
				'description'             => __( 'Show product price.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_price',
				'icon'                    => PORTO_WIDGET_URL . 'sp-price.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Font Family', 'porto-functionality' ),
						'param_name' => 'font_family',
						'std'        => '',
						'value'      => $fonts,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Font Size', 'porto-functionality' ),
						'param_name'  => 'font_size',
						'admin_label' => true,
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Font Weight', 'porto-functionality' ),
						'param_name'  => 'font_weight',
						'value'       => array(
							__( 'Default', 'porto-functionality' ) => '',
							'100' => '100',
							'200' => '200',
							'300' => '300',
							'400' => '400',
							'500' => '500',
							'600' => '600',
							'700' => '700',
							'800' => '800',
							'900' => '900',
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Letter Spacing', 'porto-functionality' ),
						'description' => __( 'Please input with units together. e.g: -0.1em', 'porto-functionality' ),
						'param_name'  => 'letter_spacing',
						'admin_label' => true,
					),
					array(
						'type'        => 'colorpicker',
						'class'       => '',
						'heading'     => __( 'Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of price.', 'porto-functionality' ),
						'param_name'  => 'color',
						'value'       => '',
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'old_price_color',
						'heading'     => __( 'Old Price Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of old price.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .price del' => 'color: {{VALUE}};',
						),
					),
					$custom_class,
					array(
						'type'             => 'css_editor',
						'heading'          => __( 'Css', 'porto-functionality' ),
						'param_name'       => 'css_porto_price',
						'group'            => __( 'Design', 'porto-functionality' ),
						'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					),
				),
			)
		);
		vc_map(
			array(
				'name'        => __( 'Product Excerpt', 'porto-functionality' ),
				'description' => __( 'Show short description.', 'porto-functionality' ),
				'base'        => 'porto_single_product_excerpt',
				'icon'        => PORTO_WIDGET_URL . 'sp-excerpt.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Font Family', 'porto-functionality' ),
						'param_name' => 'font_family',
						'std'        => '',
						'value'      => $fonts,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Font Size', 'porto-functionality' ),
						'param_name'  => 'font_size',
						'admin_label' => true,
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Font Weight', 'porto-functionality' ),
						'param_name'  => 'font_weight',
						'value'       => array(
							__( 'Default', 'porto-functionality' ) => '',
							'100' => '100',
							'200' => '200',
							'300' => '300',
							'400' => '400',
							'500' => '500',
							'600' => '600',
							'700' => '700',
							'800' => '800',
							'900' => '900',
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Line Height', 'porto-functionality' ),
						'param_name'  => 'line_height',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Letter Spacing', 'porto-functionality' ),
						'param_name'  => 'ls',
						'admin_label' => true,
					),
					array(
						'type'       => 'colorpicker',
						'class'      => '',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'param_name' => 'color',
						'value'      => '',
					),
					$custom_class,
					array(
						'type'             => 'css_editor',
						'heading'          => __( 'Css', 'porto-functionality' ),
						'param_name'       => 'css_porto_excerpt',
						'group'            => __( 'Design', 'porto-functionality' ),
						'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					),
				),
			)
		);
		vc_map(
			array(
				'name'                    => __( 'Product Add To Cart', 'porto-functionality' ),
				'description'             => __( 'Display the cart form in product page.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_add_to_cart',
				'icon'                    => PORTO_WIDGET_URL . 'sp-cart.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Margin', 'porto-functionality' ),
						'description' => __( 'Controls the margin of the quantity input.', 'porto-functionality' ),
						'param_name'  => 'quantity_margin',
						'selectors'   => array(
							'{{WRAPPER}} .product-summary-wrap .quantity' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
						),
						'group'       => __( 'Quantity', 'porto-functionality' ),
						'qa_selector' => '.quantity',
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'description_minus',
						'text'       => esc_html__( 'Minus', 'porto-functionality' ),
						'group'      => __( 'Quantity', 'porto-functionality' ),
						'with_group' => true,
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Width', 'porto-functionality' ),
						'param_name' => 'minus_width',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_add_to_cart-description_minus.jpg"/>',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus' => 'width: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Height', 'porto-functionality' ),
						'param_name' => 'minus_height',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus' => 'height: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Width', 'porto-functionality' ),
						'description' => __( 'Controls the border width of the minus.', 'porto-functionality' ),
						'param_name'  => 'minus_border',
						'selectors'   => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}};',
						),
						'group'       => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'minus_br_color',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus' => 'border-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'minus_color',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus:not(:hover)' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'minus_bg_color',
						'heading'    => __( 'Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .minus' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'description_input',
						'text'       => esc_html__( 'Input', 'porto-functionality' ),
						'group'      => __( 'Quantity', 'porto-functionality' ),
						'with_group' => true,
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Typography', 'porto-functionality' ),
						'param_name' => 'qty_font',
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),

					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Width', 'porto-functionality' ),
						'param_name' => 'qty_width',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_add_to_cart-description_input.jpg"/>',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty' => 'width: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Height', 'porto-functionality' ),
						'param_name' => 'qty_height',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty' => 'height: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Width', 'porto-functionality' ),
						'description' => __( 'Controls the border width of the qty.', 'porto-functionality' ),
						'param_name'  => 'qty_border',
						'selectors'   => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}};',
						),
						'group'       => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'qty_br_color',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty' => 'border-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'qty_color',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty:not(:hover)' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'qty_bg_color',
						'heading'    => __( 'Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .qty' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),

					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'description_plus',
						'text'       => esc_html__( 'Plus', 'porto-functionality' ),
						'group'      => __( 'Quantity', 'porto-functionality' ),
						'with_group' => true,
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Width', 'porto-functionality' ),
						'param_name' => 'plus_width',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_add_to_cart-description-plus.jpg"/>',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus' => 'width: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Height', 'porto-functionality' ),
						'param_name' => 'plus_height',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus' => 'height: {{VALUE}}{{UNIT}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Width', 'porto-functionality' ),
						'description' => __( 'Controls the border width of the plus.', 'porto-functionality' ),
						'param_name'  => 'plus_border',
						'selectors'   => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}};',
						),
						'group'       => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'plus_br_color',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus' => 'border-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'plus_color',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus:not(:hover)' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'plus_bg_color',
						'heading'    => __( 'Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .quantity .plus' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Quantity', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Typography', 'porto-functionality' ),
						'param_name' => 'price_font',
						'selectors'  => array(
							'{{WRAPPER}} .woocommerce-variation-price .price',
						),
						'group'      => __( 'Variation Price', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'price_color',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .woocommerce-variation-price .price' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Variation Price', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Margin', 'porto-functionality' ),
						'description' => __( 'Controls the margin of the price.', 'porto-functionality' ),
						'param_name'  => 'price_margin',
						'selectors'   => array(
							'{{WRAPPER}} .woocommerce-variation-price .price' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}}; display: block;',
						),
						'group'       => __( 'Variation Price', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'btn_clr',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'btn_bg_clr',
						'heading'    => __( 'Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'btn_hover_clr',
						'heading'    => __( 'Hover Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'btn_hover_bg_clr',
						'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button:hover' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'view_btn_clr',
						'heading'    => __( 'View Cart Button Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .view-cart-btn' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'view_btn_bg_clr',
						'heading'    => __( 'View Cart Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .view-cart-btn' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'view_btn_hover_clr',
						'heading'    => __( 'View Cart Hover Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .view-cart-btn:hover' => 'color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'view_btn_hover_bg_clr',
						'heading'    => __( 'View Cart Hover Background Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .view-cart-btn:hover' => 'background-color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Typography', 'porto-functionality' ),
						'param_name' => 'cart_btn_sz',
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}} .view-cart-btn',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Padding', 'porto-functionality' ),
						'param_name'  => 'cart_btn_pd',
						'responsive'  => true,
						'selectors'   => array(
							'{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}} .view-cart-btn' => 'padding-top:{{TOP}}; padding-right:{{RIGHT}}; padding-bottom:{{BOTTOM}}; padding-left: {{LEFT}};',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'The Height of Buttons', 'porto-functionality' ),
						'param_name' => 'cart_btn_hd',
						'responsive' => true,
						'units'      => array( 'px', 'rem', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}} .view-cart-btn' => 'height: {{VALUE}}{{UNIT}} !important;line-height: {{VALUE}}{{UNIT}};',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Button Design', 'porto-functionality' ),
						'param_name' => 'form_btn_layout',
						'value'      => array(
							'' => '',
							__( 'Full Width', 'porto-functionality' )   => '1',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} form .button' => 'flex: {{VALUE}};min-width: 150px;',
						),
					),					
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Margin', 'porto-functionality' ),
						'description' => __( 'Controls the margin of the cart form.', 'porto-functionality' ),
						'param_name'  => 'form_margin',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_add_to_cart-form_margin.jpg"/>',
						'selectors'   => array(
							'{{WRAPPER}} .cart:not(.variations_form), {{WRAPPER}} .single_variation_wrap' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
						'qa_selector' => '.cart:not(.variations_form), .single_variation_wrap',
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Padding', 'porto-functionality' ),
						'description' => __( 'Controls the padding of the cart form.', 'porto-functionality' ),
						'param_name'  => 'form_padding',
						'selectors'   => array(
							'{{WRAPPER}} .cart:not(.variations_form), {{WRAPPER}} .single_variation_wrap' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Width', 'porto-functionality' ),
						'description' => __( 'Controls the border width of the cart form.', 'porto-functionality' ),
						'param_name'  => 'form_border',
						'selectors'   => array(
							'{{WRAPPER}} .cart:not(.variations_form), {{WRAPPER}} .single_variation_wrap' => 'border-top-width: {{TOP}};border-right-width: {{RIGHT}};border-bottom-width: {{BOTTOM}};border-left-width: {{LEFT}}; border-style: solid;',
						),
						'group'       => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'form_br_color',
						'heading'    => __( 'Border Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .cart:not(.variations_form), {{WRAPPER}} .single_variation_wrap' => 'border-color: {{VALUE}};',
						),
						'group'      => __( 'Cart Form Button', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_skin',
						'text'       => sprintf( __( 'You can change the global value in %1$sPorto / Theme Options / WooCommerce / Product Swatch Mode%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product_variation_display_mode' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
						'group'       => __( 'Variation', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Margin', 'porto-functionality' ),
						'description' => __( 'Controls the margin of the variations.', 'porto-functionality' ),
						'param_name'  => 'variation_margin',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_add_to_cart-variation_margin.jpg"/>',
						'selectors'   => array(
							'{{WRAPPER}} .product-summary-wrap .variations' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
						),
						'group'       => __( 'Variation', 'porto-functionality' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'View Mode', 'porto-functionality' ),
						'param_name' => 'variation_tr',
						'value'      => array(
							__( 'Stacked', 'porto-functionality' ) => '',
							__( 'Block', 'porto-functionality' )   => 'block',
							__( 'Inline', 'porto-functionality' )  => 'inline-block',
						),
						'group'      => __( 'Variation', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .variations tr' => 'display: {{VALUE}};',
						),
						'qa_selector' => '.variations tr:first-child',
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Label Block', 'porto-functionality' ),
						'param_name'  => 'variation_label_block',
						'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'selectors'   => array(
							'{{WRAPPER}} .variations .label, {{WRAPPER}} .variations td.value' => 'display: block',
						),
						'dependency' => array(
							'element' => 'variation_tr',
							'value'   => array( '', 'block' ),
						),
						'group'      => __( 'Variation', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_dimension',
						'heading'    => __( 'Individual Margin', 'porto-functionality' ),
						'param_name' => 'variation_tr_margin',
						'selectors'  => array(
							'{{WRAPPER}} .product-summary-wrap .variations tr' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
						),
						'dependency' => array(
							'element' => 'variation_tr',
							'value'   => array( 'block', 'inline-block' ),
						),
						'group'      => __( 'Variation', 'porto-functionality' ),
						'qa_selector' => '.variations tr:nth-child(2)',
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
				'name'                    => __( 'Product Meta', 'porto-functionality' ),
				'description'             => __( 'Display the meta including sku, product category, tag, gtin, dokan vendor info and taxonomy information.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_meta',
				'icon'                    => PORTO_WIDGET_URL . 'sp-meta.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_meta',
						'text'       => sprintf( __( 'You can change the global value in %1$sPorto / Theme Options / WooCommerce / Single Product / Show Product Meta%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-metas' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'View Mode', 'porto-functionality' ),
						'param_name' => 'view',
						'value'      => array(
							__( 'Stacked', 'porto-functionality' ) => 'block',
							__( 'Inline', 'porto-functionality' )   => 'flex',
						),
						'selectors'  => array(
							'{{WRAPPER}} .product_meta' => 'display: {{VALUE}}; flex-wrap: wrap;',
						),
						'description' => sprintf( __( 'You can change %1$smeta%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-metas' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Spacing', 'porto-functionality' ),
						'description' => __( 'Controls the spacing between metas.', 'porto-functionality' ),
						'param_name'  => 'spacing1',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .product_meta>*' => 'margin-bottom: {{VALUE}}{{UNIT}};',
						),
						'dependency'  => array(
							'element' => 'view',
							'value'   => 'block',
						),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Spacing', 'porto-functionality' ),
						'description' => __( 'Controls the spacing between metas.', 'porto-functionality' ),
						'param_name'  => 'spacing2',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .product_meta>*+*' => "margin-{$left}: {{VALUE}}{{UNIT}};margin-bottom: 0;",
							'{{WRAPPER}} .product_meta>*' => 'margin-bottom: 0;',
						),
						'dependency'  => array(
							'element'            => 'view',
							'value_not_equal_to' => 'block',
						),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'text_color',
						'heading'    => __( 'Text Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product_meta, .product-summary-wrap {{WRAPPER}} .product_meta span' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Text Typography', 'porto-functionality' ),
						'param_name' => 'text_size',
						'selectors'  => array(
							'{{WRAPPER}} .product_meta, .product-summary-wrap {{WRAPPER}} .product_meta span',
						),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'link_color',
						'heading'    => __( 'Link Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product_meta a, .product-summary-wrap {{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'link_hover_color',
						'heading'    => __( 'Link Hover Color', 'porto-functionality' ),
						'selectors'  => array(
							'{{WRAPPER}} .product_meta a:hover, .product-summary-wrap {{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Link Typography', 'porto-functionality' ),
						'param_name' => 'link_size',
						'selectors'  => array(
							'{{WRAPPER}} .product_meta a, .product-summary-wrap {{WRAPPER}} .product_meta a',
						),
					),
					$custom_class,
					array(
						'type'             => 'css_editor',
						'heading'          => __( 'Css', 'porto-functionality' ),
						'param_name'       => 'css_porto_meta',
						'group'            => __( 'Design', 'porto-functionality' ),
						'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					),
				),
			)
		);
		vc_map(
			array(
				'name'        => __( 'Product Tabs', 'porto-functionality' ),
				'description' => __( 'Show tabs including description, review form and so on.', 'porto-functionality' ),
				'base'        => 'porto_single_product_tabs',
				'icon'        => PORTO_WIDGET_URL . 'sp-tab.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_skin',
						'text'       => sprintf( __( 'You can add the custom tab in %1$sPorto / Theme Options / WooCommerce / Custom Product Tab%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-custom-tabs-count' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Is Flex?', 'porto-functionality' ),
						'param_name' => 'is_flex',
						'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
						'selectors'  => array(
							'{{WRAPPER}} .woocommerce-tabs'    => 'display: flex !important;',
							'{{WRAPPER}} .resp-tabs-list'      => 'flex: 0 0 20%;overflow: hidden;',
							'{{WRAPPER}} .resp-tabs-container' => 'flex: 1;',
							'{{WRAPPER}} .resp-tabs-list li'   => 'position: relative;clear:both;',
							'{{WRAPPER}} .resp-tabs-list li:after' => 'content: "";position: absolute;width: 30vw;left: 0;bottom: -3px;border-bottom: 1px solid #dae2e6;',
						),
						'dependency' => array(
							'element'  => 'style',
							'is_empty' => true,
						),
						'group'      => __( 'Direction', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Tab Bottom Spacing', 'porto-functionality' ),
						'param_name' => 'tab_title_bottom_space',
						'units'      => array( 'px', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .resp-tabs-list li' => 'margin-bottom: {{VALUE}}{{UNIT}} !important;',
						),
						'dependency' => array(
							'element' => 'is_flex',
							'value'   => 'yes',
						),
						'group'      => __( 'Direction', 'porto-functionality' ),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Width', 'porto-functionality' ),
						'param_name' => 'tab_title_width',
						'units'      => array( '%', 'px' ),
						'selectors'  => array(
							'{{WRAPPER}} .woocommerce-tabs .resp-tabs-list' => 'flex-basis: {{VALUE}}{{UNIT}};',
						),
						'responsive'  => true,
						'dependency' => array(
							'element' => 'is_flex',
							'value'   => 'yes',
						),
						'group'      => __( 'Direction', 'porto-functionality' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Collapse Accordion on mobile at first?', 'porto-functionality' ),
						'param_name'  => 'accordion_closed',
						'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					),
					array(
						'type'       => 'porto_multiselect',
						'heading'    => __( 'Hide Product Tabs', 'porto-functionality' ),
						'param_name' => 'hide_tabs',
						'value'      => defined( 'PWB_PLUGIN_VERSION' ) ? array(
							__( 'Description', 'porto-functionality' )            => 'description',
							__( 'Additional Information', 'porto-functionality' ) => 'additional_information',
							__( 'Review', 'porto-functionality' )                 => 'reviews',
							__( 'Perfect Brands', 'porto-functionality' )         => 'pwb_tab',
						)
						: array(
							__( 'Description', 'porto-functionality' )            => 'description',
							__( 'Additional Information', 'porto-functionality' ) => 'additional_information',
							__( 'Review', 'porto-functionality' )                 => 'reviews',
						),
						'std'        => '',
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Style', 'porto-functionality' ),
						'description' => __( 'Controls the layout of tabs.', 'porto-functionality' ),
						'param_name'  => 'style',
						'value'       => array(
							__( 'Default', 'porto-functionality' ) => '',
							__( 'Vetical', 'porto-functionality' ) => 'vertical',
						),
						'admin_label' => true,
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Border Radius', 'porto-functionality' ),
						'description' => __( 'Controls the border radius of tab title.', 'porto-functionality' ),
						'param_name'  => 'tab_border_radius',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} .resp-accordion' => 'border-radius: {{VALUE}}{{UNIT}} {{VALUE}}{{UNIT}} 0 0 !important;',
						),
					),
					array(
						'type'       => 'porto_dimension',
						'heading'    => __( 'Border Width', 'porto-functionality' ),
						'param_name' => 'tab_title_border',
						'selectors'  => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} h2.resp-accordion' => 'border-top: {{TOP}} solid !important; border-right: {{RIGHT}} solid !important; border-bottom: {{BOTTOM}} solid !important; border-left: {{LEFT}} solid !important;',
							'{{WRAPPER}} .resp-tabs-list li:after' => 'bottom: calc(-1 * {{BOTTOM}} - 1px);',
						),
						'qa_selector' => '.resp-tabs-list li:nth-child(2), .resp-accordion:nth-of-type(2)',
					),
					array(
						'type'       => 'porto_dimension',
						'heading'    => __( 'Active Border Width', 'porto-functionality' ),
						'param_name' => 'tab_title_border_active',
						'selectors'  => array(
							'{{WRAPPER}} .resp-tabs-list li.resp-tab-active, {{WRAPPER}} h2.resp-accordion.resp-tab-active' => 'border-top: {{TOP}} solid !important; border-right: {{RIGHT}} solid !important; border-bottom: {{BOTTOM}} solid !important; border-left: {{LEFT}} solid !important;',
							'{{WRAPPER}} .resp-tabs-list li.resp-tab-active:after' => 'bottom: calc(-1 * {{BOTTOM}} - 1px);',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'tab_text_color',
						'heading'     => __( 'Text Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} .resp-accordion' => 'color: {{VALUE}} !important;',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'tab_bg_color',
						'heading'     => __( 'Background Color', 'porto-functionality' ),
						'description' => __( 'Controls the background color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} .woocommerce-tabs .resp-accordion' => 'background-color: {{VALUE}} !important;',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'tab_border_color',
						'heading'     => __( 'Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the border color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} h2.resp-accordion' => 'border-color: {{VALUE}} !important;',
						),
					),
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'active_tab_color',
						'text'       => __( 'Active', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'active_tab_text_color',
						'heading'     => __( 'Text Color', 'porto-functionality' ),
						'description' => __( 'Controls the active color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li.resp-tab-active, {{WRAPPER}} .resp-accordion.resp-tab-active' => 'color: {{VALUE}} !important;',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'active_tab_bg_color',
						'heading'     => __( 'Background Color', 'porto-functionality' ),
						'description' => __( 'Controls the active background color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li.resp-tab-active, {{WRAPPER}} .resp-accordion.resp-tab-active' => 'background-color: {{VALUE}} !important;',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'active_tab_border_color',
						'heading'     => __( 'Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the active border color of tab title.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li.resp-tab-active, {{WRAPPER}} .resp-tabs-list li:hover, {{WRAPPER}} .resp-accordion.resp-tab-active, {{WRAPPER}} h2.resp-accordion:hover' => 'border-color: {{VALUE}} !important;',
						),
					),
					array(
						'type'        => 'porto_typography',
						'heading'     => __( 'Tab Typography', 'porto-functionality' ),
						'description' => __( 'Controls the size of tab title.', 'porto-functionality' ),
						'param_name'  => 'tab_typography',
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} .woocommerce-tabs .resp-accordion',
						),
					),
					array(
						'type'       => 'porto_dimension',
						'heading'    => __( 'Padding', 'porto-functionality' ),
						'param_name' => 'tab_title_padding',
						'selectors'  => array(
							'{{WRAPPER}} .resp-tabs-list li, {{WRAPPER}} .resp-accordion' => 'padding-top: {{TOP}}!important; padding-right: {{RIGHT}}!important; padding-bottom: {{BOTTOM}}!important; padding-left: {{LEFT}}!important; ',
						),
						'qa_selector' => '.resp-tabs-list li:first-child, .resp-accordion:first-child',
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Tab Space', 'porto-functionality' ),
						'description' => __( 'Controls the space between the tabs.', 'porto-functionality' ),
						'param_name'  => 'tab_title_space',
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .resp-tabs-list li' => "margin-{$right}: {{VALUE}}{{UNIT}};",
						),
						'qa_selector' => '.resp-tabs-list li:nth-child(3)',
						'dependency'  => array(
							'element'  => 'style',
							'is_empty' => true,
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'text_color',
						'heading'     => __( 'Text Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of tab content.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .tab-content' => 'color: {{VALUE}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'content_bg_color',
						'heading'     => __( 'Background Color', 'porto-functionality' ),
						'description' => __( 'Controls the background color of tab content.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .tab-content' => 'background-color: {{VALUE}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_typography',
						'heading'     => __( 'Typography', 'porto-functionality' ),
						'description' => __( 'Controls the typography of tab content.', 'porto-functionality' ),
						'param_name'  => 'content_typography',
						'selectors'   => array(
							'{{WRAPPER}} .tab-content, {{WRAPPER}} .tab-content p',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'heading_color',
						'heading'     => __( 'Heading Color', 'porto-functionality' ),
						'description' => __( 'Controls the heading color of tab content.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .tab-content h2' => 'color: {{VALUE}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_typography',
						'heading'     => __( 'Heading Typography', 'porto-functionality' ),
						'description' => __( 'Controls the heading typography of tab content.', 'porto-functionality' ),
						'param_name'  => 'content_heading_typography',
						'selectors'   => array(
							'{{WRAPPER}} .tab-content h2',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Padding', 'porto-functionality' ),
						'description' => __( 'Controls the padding of tab content.', 'porto-functionality' ),
						'param_name'  => 'panel_padding',
						'responsive'  => true,
						'selectors'   => array(
							'{{WRAPPER}} .woocommerce-tabs .tab-content' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Width', 'porto-functionality' ),
						'description' => __( 'Controls the border width of tab content.', 'porto-functionality' ),
						'param_name'  => 'panel_border_width',
						'selectors'   => array(
							'{{WRAPPER}} .woocommerce-tabs .tab-content' => 'border-top: {{TOP}} solid;border-right: {{RIGHT}} solid;border-bottom: {{BOTTOM}} solid;border-left: {{LEFT}} solid;',
						),
						'qa_selector' => '.tab-content',
						'group'       => __( 'Panel', 'porto-functionality' ),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'panel_bd_color',
						'heading'     => __( 'Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the border color of tab content.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .woocommerce-tabs .tab-content' => 'border-color: {{VALUE}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
					),					
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Border Radius', 'porto-functionality' ),
						'description' => __( 'Controls the border radius of tab content.', 'porto-functionality' ),
						'param_name'  => 'panel_border_radius',
						'selectors'   => array(
							'{{WRAPPER}} .tab-content' => 'border-top-left-radius: {{TOP}};border-top-right-radius: {{RIGHT}}; border-bottom-right-radius: {{BOTTOM}}; border-bottom-left-radius: {{LEFT}};',
						),
						'group'       => __( 'Panel', 'porto-functionality' ),
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

		if ( $this->legacy_mode ) {
			vc_map(
				array(
					'name'                    => __( 'Upsells', 'porto-functionality' ),
					'description'             => __( 'Show upsell products.', 'porto-functionality' ),
					'base'                    => 'porto_single_product_upsell',
					'icon'                    => PORTO_WIDGET_URL . 'sp-upsell.png',
					'class'                   => 'porto-wpb-widget',
					'category'                => __( 'Product Page', 'porto-functionality' ),
					'show_settings_on_create' => false,
					'params'                  => array_merge(
						array(
							array(
								'type'       => 'porto_param_heading',
								'param_name' => 'notice_skin',
								'text'       => sprintf(
									__(
										'You can show or hide in %1$sPorto / Theme Options / WooCommerce / 
								Show Up Sells%2$s.',
										'porto-functionality'
									),
									'<a href="' . porto_get_theme_option_url( 'product-upsells' ) . '" target="_blank" class="porto-text-underline">',
									'</a>'
								),
							),
						),
						$products_args
					),
				)
			);
			vc_map(
				array(
					'name'                    => __( 'Related Products', 'porto-functionality' ),
					'description'             => __( 'Show related products.', 'porto-functionality' ),
					'base'                    => 'porto_single_product_related',
					'icon'                    => PORTO_WIDGET_URL . 'sp-related.png',
					'class'                   => 'porto-wpb-widget',
					'category'                => __( 'Product Page', 'porto-functionality' ),
					'show_settings_on_create' => false,
					'params'                  =>  array_merge(
						array(
							array(
								'type'       => 'porto_param_heading',
								'param_name' => 'notice_skin',
								'text'       => sprintf( __( 'You can show or hide in %1$sPorto / Theme Options / WooCommerce / Show Related Products%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-related' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
							),
						),
						$products_args
					),
				)
			);
		}

		vc_map(
			array(
				'name'                    => __( 'Prev and Next Navigation', 'porto-functionality' ),
				'description'             => __( 'Show navigation in product page.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_next_prev_nav',
				'icon'                    => PORTO_WIDGET_URL . 'sp-nav.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'notice_skin',
						'text'       => sprintf( __( 'You can show or hide in %1$sPorto / Theme Options / WooCommerce / Show Prev/Next Product%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-nav' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'nav_color',
						'heading'     => __( 'Nav Color', 'porto-functionality' ),
						'description' => __( 'Controls the color of navigation.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-link' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'nav_bg_color',
						'heading'     => __( 'Background Color', 'porto-functionality' ),
						'description' => __( 'Controls the background color of navigation.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-link' => 'background-color: {{VALUE}};',
						),
					),
					array(
						'type'        => 'colorpicker',
						'param_name'  => 'nav_border_color',
						'heading'     => __( 'Border Color', 'porto-functionality' ),
						'description' => __( 'Controls the border color of navigation.', 'porto-functionality' ),
						'selectors'   => array(
							'{{WRAPPER}} .product-link' => 'border-color: {{VALUE}};',
						),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Dropdown Padding', 'porto-functionality' ),
						'description' => __( 'Controls the padding of navigation dropdown.', 'porto-functionality' ),
						'param_name'  => 'dropdown_padding',
						'selectors'   => array(
							'{{WRAPPER}} .featured-box .box-content' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
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
				'name'                    => __( 'Product Sticky Add To Cart', 'porto-functionality' ),
				'description'             => __( 'Show sticky cart form in product page.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_addcart_sticky',
				'icon'                    => PORTO_WIDGET_URL . 'sp-cart-sticky.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'porto_param_heading',
						'param_name' => 'description_sticky',
						'text'       => __( 'It may seem different between preview and frontend. Please look at product page.', 'porto-functionality' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Position', 'porto-functionality' ),
						'param_name'  => 'pos',
						'value'       => array(
							__( 'Top', 'porto-functionality' ) => '',
							__( 'Bottom', 'porto-functionality' ) => 'bottom',
						),
						'admin_label' => true,
						'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-sticky-addcart' ) . '" target="_blank">', '</a>' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Enable Sticky on Mobile', 'porto-functionality' ),
						'param_name' => 'enable_mobile'
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Product Title Font', 'porto-functionality' ),
						'param_name' => 'title_font',
						'selectors'  => array(
							'{{WRAPPER}} .sticky-detail .product-name',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Product Title Color', 'porto-functionality' ),
						'param_name' => 'title_color',
						'selectors'  => array(
							'{{WRAPPER}} .product-name' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Product Price Font', 'porto-functionality' ),
						'param_name' => 'price_font',
						'selectors'  => array(
							'.sticky-product{{WRAPPER}} .sticky-detail .price',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Product Price Color', 'porto-functionality' ),
						'param_name' => 'price_color',
						'selectors'  => array(
							'{{WRAPPER}} .price' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Rating Size', 'porto-functionality' ),
						'param_name' => 'rating_font',
						'selectors'  => array(
							'{{WRAPPER}} .star-rating',
						),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Rating Margin', 'porto-functionality' ),
						'param_name'  => 'rating_margin',
						'selectors'   => array(
							'{{WRAPPER}} .star-rating' => 'margin-top: {{TOP}}; margin-right: {{RIGHT}}; margin-bottom: {{BOTTOM}}; margin-left: {{LEFT}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Availability Font', 'porto-functionality' ),
						'param_name' => 'av_font',
						'selectors'  => array(
							'{{WRAPPER}} .availability',
						),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Availability Margin', 'porto-functionality' ),
						'param_name'  => 'av_margin',
						'selectors'   => array(
							'{{WRAPPER}} .availability' => 'margin-top: {{TOP}}; margin-right: {{RIGHT}}; margin-bottom: {{BOTTOM}}; margin-left: {{LEFT}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Availability Color', 'porto-functionality' ),
						'param_name' => 'av_color',
						'hint'        => '<img src="' . PORTO_HINT_URL . 'product_addcart_sticky-av_font.gif"/>',
						'selectors'  => array(
							'{{WRAPPER}} .availability' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_typography',
						'heading'    => __( 'Button Font', 'porto-functionality' ),
						'param_name' => 'btn_font',
						'selectors'  => array(
							'{{WRAPPER}} .button',
						),
					),
					array(
						'type'        => 'porto_dimension',
						'heading'     => __( 'Button Padding', 'porto-functionality' ),
						'param_name'  => 'btn_padding',
						'responsive'  => true,
						'selectors'   => array(
							'{{WRAPPER}} .button' => 'padding-top: {{TOP}}; padding-right: {{RIGHT}}; padding-bottom: {{BOTTOM}}; padding-left: {{LEFT}};',
						),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Button Height', 'porto-functionality' ),
						'param_name'  => 'btn_ht',
						'responsive'  => true,
						'units'       => array( 'px', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} .button' => 'height: {{VALUE}}{{UNIT}} !important;',
						),
					),
					$custom_class,
				),
			)
		);

		if ( class_exists( 'YITH_WCWL' ) ) {
			vc_map(
				array(
					'name'                    => __( 'Wishlist', 'porto-functionality' ),
					'description'             => __( 'Show yith wishlist in product page.', 'porto-functionality' ),
					'base'                    => 'porto_single_product_wishlist',
					'icon'                    => PORTO_WIDGET_URL . 'sp-wishlist.png',
					'class'                   => 'porto-wpb-widget',
					'category'                => __( 'Product Page', 'porto-functionality' ),
					'show_settings_on_create' => false,
					'params'                  => array(
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Show Label', 'porto-functionality' ),
							'description' => __( 'Show/Hide the wishlist label.', 'porto-functionality' ),
							'param_name'  => 'show_label',
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}} a, {{WRAPPER}} a span' => 'width: auto;text-indent: 0;',
								'{{WRAPPER}} .yith-wcwl-add-to-wishlist a:before' => "position: static;margin-{$right}: 0.125rem;line-height: 1;",
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Icon Size', 'porto-functionality' ),
							'description' => __( 'Controls the size of icon.', 'porto-functionality' ),
							'param_name'  => 'icon_size',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} a:before, .single-product {{WRAPPER}} .add_to_wishlist:before' => 'font-size: {{VALUE}}{{UNIT}};',
							),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Label Typography', 'porto-functionality' ),
							'param_name' => 'label_font',
							'selectors'  => array(
								'{{WRAPPER}} a, {{WRAPPER}} a span',
							),
							'dependency' => array(
								'element' => 'show_label',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Spacing', 'porto-functionality' ),
							'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
							'param_name'  => 'spacing',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wcwl-add-to-wishlist a:before' => "margin-{$right}: {{VALUE}}{{UNIT}} !important;",
							),
							'dependency'  => array(
								'element' => 'show_label',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'icon_color',
							'heading'     => __( 'Icon Color', 'porto-functionality' ),
							'description' => __( 'Controls the color of wishlist icon.', 'porto-functionality' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wcwl-add-to-wishlist a:before' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'icon_added_color',
							'heading'     => __( 'Added Color', 'porto-functionality' ),
							'description' => __( 'Controls the added color of wishlist icon.', 'porto-functionality' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wcwl-wishlistaddedbrowse a:before, {{WRAPPER}} .yith-wcwl-wishlistexistsbrowse a:before' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'label_color',
							'heading'     => __( 'Label Color', 'porto-functionality' ),
							'description' => __( 'Controls the color of wishlist label.', 'porto-functionality' ),
							'selectors'   => array(
								'.single-product .product-summary-wrap {{WRAPPER}} a, .single-product .product-summary-wrap {{WRAPPER}} a span, {{WRAPPER}} a, {{WRAPPER}} a span:not(.yith-wcwl-tooltip)' => 'color: {{VALUE}};',
							),
							'dependency'  => array(
								'element' => 'show_label',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'label_hover_color',
							'heading'     => __( 'Label Hover Color', 'porto-functionality' ),
							'description' => __( 'Controls the hover color of label.', 'porto-functionality' ),
							'selectors'   => array(
								'.single-product .product-summary-wrap {{WRAPPER}} a:hover, .single-product .product-summary-wrap {{WRAPPER}} a:hover span, {{WRAPPER}} a:hover, {{WRAPPER}} a:hover span' => 'color: {{VALUE}};',
							),
							'dependency'  => array(
								'element' => 'show_label',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Background Width', 'porto-functionality' ),
							'description' => __( 'Controls the width of wishlist.', 'porto-functionality' ),
							'param_name'  => 'bg_width',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .wishlist-nolabel .yith-wcwl-add-to-wishlist a' => 'width: {{VALUE}}{{UNIT}} !important;',
							),
							'dependency'  => array(
								'element' => 'show_label',
								'value'   => '',
							),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Background Height', 'porto-functionality' ),
							'description' => __( 'Controls the height of wishlist.', 'porto-functionality' ),
							'responsive'  => true,
							'param_name'  => 'bg_height',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wcwl-add-to-wishlist a, {{WRAPPER}} .yith-wcwl-add-to-wishlist span:not(.yith-wcwl-tooltip)' => 'height: {{VALUE}}{{UNIT}}; line-height: {{VALUE}}{{UNIT}};',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'bg_color',
							'heading'     => __( 'Background Color', 'porto-functionality' ),
							'description' => __( 'Controls the background color of label.', 'porto-functionality' ),
							'selectors'   => array(
								'.single-product .product-summary-wrap {{WRAPPER}} a, {{WRAPPER}} a' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'        => 'colorpicker',
							'param_name'  => 'bg_hover_color',
							'heading'     => __( 'Background Hover Color', 'porto-functionality' ),
							'description' => __( 'Controls the background hover color of label.', 'porto-functionality' ),
							'selectors'   => array(
								'.single-product .product-summary-wrap {{WRAPPER}} a:hover, {{WRAPPER}} a:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Padding', 'porto-functionality' ),
							'responsive'  => true,
							'param_name'  => 'wl_padding',
							'selectors'   => array(
								'{{WRAPPER}} a' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
							),
							'dependency' => array(
								'element' => 'show_label',
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
		}
		if ( defined( 'YITH_WFBT_VERSION' ) ) {
			vc_map(
				array(
					'name'                    => __( 'Frequently Bought Together', 'porto-functionality' ),
					'description'             => __( 'Show yith frequently bought together(fbt) in product page.', 'porto-functionality' ),
					'base'                    => 'porto_single_product_fbt',
					'icon'                    => PORTO_WIDGET_URL . 'fbt.png',
					'class'                   => 'porto-wpb-widget',
					'category'                => __( 'Product Page', 'porto-functionality' ),
					'show_settings_on_create' => false,
					'params'                  => array(
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Width', 'porto-functionality' ),
							'description' => __( 'Controls the width of the image.', 'porto-functionality' ),
							'param_name'  => 'image_w',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wfbt-images td img' => 'width: {{VALUE}}{{UNIT}};',
							),
							'qa_selector' => '.image-td:first-child',
							'group'       => __( 'Image', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Plus Width', 'porto-functionality' ),
							'description' => __( 'Controls the width of the plus.', 'porto-functionality' ),
							'param_name'  => 'plus_w',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wfbt-images .image_plus' => 'width: {{VALUE}}{{UNIT}};',
							),
							'qa_selector' => '.image_plus_1',
							'group'       => __( 'Image', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Plus Size', 'porto-functionality' ),
							'description' => __( 'Controls the size of the plus.', 'porto-functionality' ),
							'param_name'  => 'plus_sz',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wfbt-images .image_plus' => 'font-size: {{VALUE}}{{UNIT}};',
							),
							'group'       => __( 'Image', 'porto-functionality' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Hide Title', 'porto-functionality' ),
							'param_name'  => 'hide_title',
							'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'selectors'   => array(
								'{{WRAPPER}} .yith-wfbt-section>h3' => 'display: none;',
							),
							'qa_selector' => '.yith-wfbt-section>h3',
							'group'       => __( 'Text', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Between Spacing', 'porto-functionality' ),
							'param_name'  => 'spacing',
							'units'       => array( 'px', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .price_text' => "margin-bottom: {{VALUE}}{{UNIT}};",
							),
							'qa_selector' => '.price_text',
							'group'       => __( 'Text', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Typography', 'porto-functionality' ),
							'param_name' => 'item_sz',
							'qa_selector' => '.yith-wfbt-item:first-child',
							'selectors'  => array(
								'{{WRAPPER}} .yith-wfbt-item',
							),
							'group'       => __( 'Text', 'porto-functionality' ),
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
					'name'                    => __( 'Product Compare', 'porto-functionality' ),
					'description'             => __( 'Show yith compare in product page.', 'porto-functionality' ),
					'base'                    => 'porto_single_product_compare',
					'icon'                    => PORTO_WIDGET_URL . 'sp-compare.png',
					'class'                   => 'porto-wpb-widget',
					'category'                => __( 'Product Page', 'porto-functionality' ),
					'show_settings_on_create' => false,
					'params'                  => array(
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Typography', 'porto-functionality' ),
							'param_name' => 'compare_font',
							'selectors'  => array(
								'{{WRAPPER}} .compare',
							),
						),
						array(
							'type'       => 'porto_number',
							'heading'    => __( 'Icon Size', 'porto-functionality' ),
							'param_name' => 'icon_size',
							'units'      => array( 'px', 'rem', 'em' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare:before' => 'font-size: {{VALUE}}{{UNIT}};',
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Padding', 'porto-functionality' ),
							'description' => __( 'Controls the padding of the button.', 'porto-functionality' ),
							'param_name'  => 'pd',
							'selectors'   => array(
								'{{WRAPPER}} .compare' => 'padding-top: {{TOP}}; padding-right: {{RIGHT}}; padding-bottom: {{BOTTOM}}; padding-left: {{LEFT}};',
							),
						),
						array(
							'type'        => 'porto_dimension',
							'heading'     => __( 'Margin', 'porto-functionality' ),
							'description' => __( 'Controls the margin of the button.', 'porto-functionality' ),
							'param_name'  => 'margin',
							'selectors'   => array(
								'{{WRAPPER}} .compare' => 'margin-top: {{TOP}}; margin-right: {{RIGHT}}; margin-bottom: {{BOTTOM}}; margin-left: {{LEFT}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_color',
							'heading'    => __( 'Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_bd_color',
							'heading'    => __( 'Border Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare' => 'border-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_bg_color',
							'heading'    => __( 'Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare' => 'background-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_color_hover',
							'heading'    => __( 'Hover Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare:hover' => 'color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_bd_color_hover',
							'heading'    => __( 'Hover Border Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare:hover' => 'border-color: {{VALUE}};',
							),
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'bt_bg_color_hover',
							'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors'  => array(
								'{{WRAPPER}} .compare:hover' => 'background-color: {{VALUE}};',
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
		}

        $all_attributes = PortoShortcodesClass::get_woo_attributes( true );

		vc_map(
			array(
				'name'                    => __( 'Product Attribute Table', 'porto-functionality' ),
				'description'             => __( 'Show the product attributes in the product page', 'porto-functionality' ),
				'base'                    => 'porto_single_product_attribute',
				'icon'                    => PORTO_WIDGET_URL . 'sp-attribute.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Attributes', 'porto-functionality' ),
                        'param_name' => 'attr_source',
                        'value'      => array(                          
                            __( 'All', 'porto-functionality' )     => 'all',
                            __( 'Include', 'porto-functionality' ) => 'include',
                            __( 'Exclude', 'porto-functionality' ) => 'exclude',
                        ),
                        'std'        => 'all',
                    ),
                    array(
                        'type'       => 'porto_multiselect',
                        'heading'    => __( 'Include Attributes', 'porto-functionality' ),
                        'param_name' => 'attr_include',
                        'value'      => $all_attributes,
                        'std'        => '',
                        'dependency' => array(
                            'element' => 'attr_source',
                            'value'   => 'include',
                        ),
                    ),
                    array(
                        'type'       => 'porto_multiselect',
                        'heading'    => __( 'Exclude Attributes', 'porto-functionality' ),
                        'param_name' => 'attr_exclude',
                        'value'      => $all_attributes,
                        'std'        => '',
                        'dependency' => array(
                            'element' => 'attr_source',
                            'value'   => 'exclude',
                        ),
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __( 'Title', 'porto-functionality' ),
						'description' => __( 'If you want to hide the title, please leave empty.', 'porto-functionality' ),
                        'param_name'  => 'table_title',
                        'admin_label' => true,
						'std'         => __( 'Product Attributes', 'porto-functionality' ),
						'save_always' => true,
						'group'       => __( 'Table Title', 'porto-functionality' ),
						'qa_selector' => '.porto-attr-title',
                    ),
                    array(
                        'type'       => 'porto_typography',
                        'heading'    => __( 'Typography', 'porto-functionality' ),
                        'param_name' => 'title_typography',
                        'selectors'  => array(
                            '{{WRAPPER}} thead .porto-attr-title',
                        ),
                        'dependency' => array(
                            'element'   => 'table_title',
                            'not_empty' => true,
                        ),
                        'group'      => __( 'Table Title', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'colorpicker',
                        'param_name'  => 'title_color',
                        'heading'     => __( 'Color', 'porto-functionality' ),
                        'selectors'   => array(
                            '{{WRAPPER}} thead .porto-attr-title' => 'color: {{VALUE}};',
                        ),
                        'dependency' => array(
                            'element'   => 'table_title',
                            'not_empty' => true,
                        ),
                        'group'       => __( 'Table Title', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'colorpicker',
                        'param_name'  => 'title_bg_color',
                        'heading'     => __( 'Background Color', 'porto-functionality' ),
                        'selectors'   => array(
                            '{{WRAPPER}} thead .porto-attr-title' => 'background-color: {{VALUE}};',
                        ),
                        'dependency' => array(
                            'element'   => 'table_title',
                            'not_empty' => true,
                        ),
                        'group'       => __( 'Table Title', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'porto_dimension',
                        'heading'     => __( 'Padding', 'porto-functionality' ),
                        'description' => __( 'Controls the margin of the quantity input.', 'porto-functionality' ),
                        'param_name'  => 'title_padding',
						'responsive'  => true,
                        'selectors'   => array(
                            '{{WRAPPER}} thead .porto-attr-title' => 'padding-top: {{TOP}}; padding-right: {{RIGHT}}; padding-bottom: {{BOTTOM}}; padding-left: {{LEFT}};',
                        ),
						'units'       => array( 'px', 'rem', 'em' ),
                        'group'       => __( 'Table Title', 'porto-functionality' ),
                        'dependency' => array(
                            'element'   => 'table_title',
                            'not_empty' => true,
                        ),
                        'group'       => __( 'Table Title', 'porto-functionality' ),
                    ),
					array(
                        'type'       => 'porto_number',
                        'heading'    => __( 'Number of Table columns', 'porto-functionality' ),
                        'param_name' => 'column_count',
						'responsive' => true,
                        'value'         => '1',
                        'selectors'   => array(
                            '{{WRAPPER}} tbody' => '--porto-sp-table-cols: {{VALUE}};',
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
						'qa_selector' => 'tbody',
                    ),
                    array(
                        'type'        => 'porto_number',
                        'heading'     => __( 'Column Gap', 'porto-functionality' ),
                        'param_name'  => 'column_gap',
						'responsive'  => true,
                        'units'       => array( 'px', 'rem' ),
                        'selectors'   => array(
                            '{{WRAPPER}} tbody' => 'column-gap: {{VALUE}}{{UNIT}};',
                        ),
                        'dependency'  => array(
                            'element'            => 'column_count',
							'value_not_equal_to' => '1',
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'porto_number',
                        'heading'     => __( 'Vertical Spacing', 'porto-functionality' ),
						'responsive'  => true,
                        'param_name'  => 'vertical_space',
                        'units'       => array( 'px', 'rem' ),
                        'selectors'   => array(
                            '{{WRAPPER}} tbody' => '--porto-table-vs: {{VALUE}}{{UNIT}};'
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'porto_number',
                        'heading'     => __( 'Field Horizontal Padding', 'porto-functionality' ),
						'responsive'  => true,
                        'param_name'  => 'col_spacing',
                        'units'       => array( 'px', 'em' ,'rem' ),
                        'selectors'   => array(
                            '{{WRAPPER}} tbody .porto-attr-data' => 'padding-left: {{VALUE}}{{UNIT}}; padding-right: {{VALUE}}{{UNIT}};'
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
						'qa_selector' => '.porto-attr-data:first-child',
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Hide Border', 'porto-functionality' ),
                        'param_name'  => 'disable_col_border',
                        'selectors'   => array(
                            '{{WRAPPER}} tbody .porto-attr-data' => 'border-bottom: none',
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'colorpicker',
                        'param_name'  => 'col_border_color',
                        'heading'     => __( 'Border Color', 'porto-functionality' ),
                        'selectors'   => array(
                            '{{WRAPPER}} tbody .porto-attr-data' => 'border-bottom-color: {{VALUE}};',
                        ),
                        'dependency' => array(
                            'element'  => 'disable_col_border',
                            'is_empty' => true,
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'       => 'porto_typography',
                        'heading'    => __( 'Name Typography', 'porto-functionality' ),
                        'param_name' => 'name_typography',
                        'selectors'  => array(
                            '{{WRAPPER}} .porto-attr-name',
                        ),
                        'group'      => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'colorpicker',
                        'param_name'  => 'name_color',
                        'heading'     => __( 'Name Color', 'porto-functionality' ),
                        'selectors'   => array(
                            '{{WRAPPER}} .porto-attr-name' => 'color: {{VALUE}};',
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'       => 'porto_typography',
                        'heading'    => __( 'Term Typography', 'porto-functionality' ),
                        'param_name' => 'term_typography',
                        'selectors'  => array(
                            '{{WRAPPER}} .porto-attr-term',
                        ),
                        'group'      => __( 'Term Fields', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'colorpicker',
                        'param_name'  => 'term_color',
                        'heading'     => __( 'Term Color', 'porto-functionality' ),
                        'selectors'   => array(
                            '{{WRAPPER}} .porto-attr-term' => 'color: {{VALUE}};',
                        ),
                        'group'       => __( 'Term Fields', 'porto-functionality' ),
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
				'name'        => __( 'Product Review', 'porto-functionality' ),
				'description' => __( 'Show the review in the product page', 'porto-functionality' ),
				'base'        => 'porto_single_product_review',
				'icon'        => PORTO_WIDGET_URL . 'sp-review.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array(
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

		$add_params = array(
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Attribute Group Title', 'porto' ),
                'param_name' => 'table_title',
                'group'      => __( 'Attribute (for Compare Products)', 'porto-functionality' ),
            ),
            array(
                'type'       => 'porto_multiselect',
                'heading'    => __( 'Include Attributes', 'porto-functionality' ),
                'param_name' => 'attr_include',
                'value'      => $all_attributes,
                'std'        => '',
                'dependency' => array(
                    'element'   => 'table_title',
                    'not_empty' => true,
                ),
                'group'      => __( 'Attribute (for Compare Products)', 'porto-functionality' ),
            ),
        );

		vc_map(
			array(
				'name'        => __( 'Linked Products', 'porto-functionality' ),
				'description' => __( 'Show related, upsell & compare, cross sell products using the product type which you\'ve made with the type builder.', 'porto-functionality' ),
				'base'        => 'porto_single_product_linked',
				'icon'        => PORTO_WIDGET_URL . 'sp-related.png',
				'class'       => 'porto-wpb-widget',
				'category'    => __( 'Product Page', 'porto-functionality' ),
				'params'      => array_merge(
					array(
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'posts_layout',
							'text'       => __( 'Posts Selector', 'porto-functionality' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => __( 'Post Layout', 'porto-functionality' ),
							'param_name'  => 'builder_id',
							'settings'    => array(
								'multiple'      => false,
								'sortable'      => true,
								'unique_values' => true,
							),
							/* translators: starting and end A tags which redirects to edit page */
							'description' => sprintf( __( 'Please select a saved Post Layout template which was built using post type builder. Please create a new Post Layout template in %1$sPorto Templates Builder%2$s', 'porto-functionality' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . PortoBuilders::BUILDER_SLUG . '&' . PortoBuilders::BUILDER_TAXONOMY_SLUG . '=type' ) ) . '" target="_blank">', '</a>' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Linked Product', 'porto-functionality' ),
							'description' => __( 'Please select a post type of posts to display.', 'porto-functionality' ),
							'param_name'  => 'linked_product',
							'value'       => array(
								__( 'Select linked.', 'porto-functionality' )    => '',
								__( 'Related Products', 'porto-functionality' )    => 'related',
								__( 'Upsells Products', 'porto-functionality' )    => 'upsell',
								__( 'Cross Sell Products', 'porto-functionality' ) => 'cross_sell',
								__( 'Compare Products', 'porto-functionality' )    => 'compare',
							),
							'admin_label' => true,
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'compare_desc',
							'text'       => sprintf(  esc_html__( 'Please select the compare products in %1$sLinked Products%2$s tab of "Edit Product" on Dashboard > Products > All Products.', 'porto-functionality' ), '<b>', '</b>' ),
							'class'      => 'standard-heading',
							'dependency' => array(
								'element' => 'linked_product',
								'value'   => array( 'compare' ),
							),
						),
						array(
							'type'        => 'number',
							'heading'     => __( 'Count', 'porto-functionality' ),
							'description' => __( 'Leave blank if you use default value.', 'porto-functionality' ),
							'param_name'  => 'count',
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Order by', 'porto-functionality' ),
							'param_name'  => 'orderby',
							'value'       => porto_vc_woo_order_by(),
							/* translators: %s: Wordpres codex page */
							'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', 'porto-functionality' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Order way', 'porto-functionality' ),
							'param_name'  => 'order',
							'value'       => porto_vc_woo_order_way(),
							/* translators: %s: Wordpres codex page */
							'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'porto-functionality' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
						),
						array(
							'type'       => 'porto_param_heading',
							'param_name' => 'posts_layout',
							'text'       => __( 'Posts Layout', 'porto-functionality' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'View mode', 'porto-functionality' ),
							'param_name'  => 'view',
							'value'       => array(
								__( 'Grid', 'porto-functionality' ) => '',
								__( 'Grid - Creative', 'porto-functionality' ) => 'creative',
								__( 'Masonry', 'porto-functionality' ) => 'masonry',
								__( 'Slider', 'porto-functionality' ) => 'slider',
							),
							'dependency'  => array(
								'element'            => 'linked_product',
								'value_not_equal_to' => array( 'compare' ),
							),
							'admin_label' => true,
						),
						array(
							'type'       => 'porto_image_select',
							'heading'    => __( 'Grid Layout', 'porto-functionality' ),
							'param_name' => 'grid_layout',
							'dependency' => array(
								'element' => 'view',
								'value'   => array( 'creative' ),
							),
							'std'        => '1',
							'value'      => porto_sh_commons( 'masonry_layouts' ),
						),
						array(
							'type'       => 'number',
							'heading'    => __( 'Grid Height (px)', 'porto-functionality' ),
							'param_name' => 'grid_height',
							'dependency' => array(
								'element' => 'view',
								'value'   => array( 'creative' ),
							),
							'suffix'     => 'px',
							'std'        => 600,
						),
						array(
							'type'        => 'number',
							'heading'     => __( 'Column Spacing (px)', 'porto-functionality' ),
							'description' => __( 'Leave blank if you use theme default value.', 'porto-functionality' ),
							'param_name'  => 'spacing',
							'suffix'      => 'px',
							'std'         => '',
							'selectors'   => array(
								'{{WRAPPER}}' => '--porto-el-spacing: {{VALUE}}px;',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Columns', 'porto-functionality' ),
							'param_name' => 'columns',
							'std'        => '4',
							'value'      => porto_sh_commons( 'products_columns' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Add Half Column', 'porto-functionality' ),
							'param_name' => 'half_column',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								__( 'Yes', 'porto-functionality' )     => 'yes',
								__( 'No', 'porto-functionality' )      => 'no',
							),
							'dependency' => array(
								'element' => 'view',
								'value'   => array( 'slider' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Columns on tablet ( <= 991px )', 'porto-functionality' ),
							'param_name' => 'columns_tablet',
							'std'        => '',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Add Half Column on tablet', 'porto-functionality' ),
							'param_name' => 'half_column_tablet',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								__( 'Yes', 'porto-functionality' )     => 'yes',
								__( 'No', 'porto-functionality' )      => 'no',
							),
							'dependency' => array(
								'element' => 'view',
								'value'   => array( 'slider' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Columns on mobile ( <= 575px )', 'porto-functionality' ),
							'param_name' => 'columns_mobile',
							'std'        => '',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								'1' => '1',
								'2' => '2',
								'3' => '3',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Add Half Column on Mobile', 'porto-functionality' ),
							'param_name' => 'half_column_mobile',
							'value'      => array(
								__( 'Default', 'porto-functionality' ) => '',
								__( 'Yes', 'porto-functionality' )     => 'yes',
								__( 'No', 'porto-functionality' )      => 'no',
							),
							'dependency' => array(
								'element' => 'view',
								'value'   => array( 'slider' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Image Size', 'porto-functionality' ),
							'param_name' => 'image_size',
							'value'      => porto_sh_commons( 'image_sizes' ),
							'std'        => '',
						),
						porto_vc_custom_class(),
						array(
							'type'             => 'css_editor',
							'heading'          => __( 'CSS', 'porto-functionality' ),
							'param_name'       => 'css_design',
							'group'            => __( 'Design', 'porto-functionality' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Show Title', 'porto-functionality' ),
							'param_name'  => 'show_heading',
							'std'         => '',
							'group'       => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Heading Content', 'porto-functionality' ),
							'param_name' => 'heading_text',
							'std'        => __( 'Related Products', 'porto-functionality' ),
							'dependency' => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'group'      => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_typography',
							'heading'    => __( 'Heading Typography', 'porto-functionality' ),
							'param_name' => 'hd_typography',
							'dependency' => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'selectors'  => array(
								'{{WRAPPER}} .sp-linked-heading',
							),
							'group'      => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'       => 'porto_button_group',
							'heading'    => __( 'Alignment', 'porto-functionality' ),
							'param_name' => 'heading_align',
							'value'      => array(
								'left'   => array(
									'title' => esc_html__( 'Left', 'porto-functionality' ),
									'icon'  => 'fas fa-align-left',
								),
								'center' => array(
									'title' => esc_html__( 'Center', 'porto-functionality' ),
									'icon'  => 'fas fa-align-center',
								),
								'right'  => array(
									'title' => esc_html__( 'Right', 'porto-functionality' ),
									'icon'  => 'fas fa-align-right',
								),
							),
							'dependency' => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'selectors' => array(
								'{{WRAPPER}}  .sp-linked-heading' => 'text-align: {{VALUE}};',
							),
							'group'      => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Padding Bottom', 'porto-functionality' ),
							'param_name'  => 'hd_space',
							'units'       => array( 'px', 'rem', 'em' ),
							'qa_selector' => '.sp-linked-heading',
							'selectors'   => array(
								'{{WRAPPER}} .sp-linked-heading' => "padding-bottom: {{VALUE}}{{UNIT}};",
							),
							'dependency'  => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'group'       => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => __( 'Hide Border', 'porto-functionality' ),
							'param_name'  => 'hide_sp',
							'dependency'  => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'selectors'   => array(
								'{{WRAPPER}} .sp-linked-heading' => "border: none;",
							),
							'group'       => __( 'Heading', 'porto-functionality' ),
						),
						array(
							'type'        => 'porto_number',
							'heading'     => __( 'Margin Bottom', 'porto-functionality' ),
							'param_name'  => 'separator_space',
							'units'       => array( 'px', 'rem', 'em' ),
							'selectors'   => array(
								'{{WRAPPER}} .sp-linked-heading' => "margin-bottom: {{VALUE}}{{UNIT}};",
							),
							'dependency'  => array(
								'element'   => 'show_heading',
								'not_empty' => true,
							),
							'group'      => __( 'Heading', 'porto-functionality' ),
						),
					),
					porto_vc_product_slider_fields( 'slider' ),
					array(
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show Attributes under the product', 'porto-functionality' ),
							'param_name' => 'show_attributes',
							'group'      => __( 'Attribute (for Compare Products)', 'porto-functionality' ),
							'dependency' => array(
								'element' => 'linked_product',
								'value'   => array( 'compare' )
							)
						),
						array(
							'type'       => 'param_group',
							'param_name' => 'attr_group',
							'heading'    => __( 'Attributes', 'porto-functionality' ),
							'params'     => $add_params,
							'group'      => __( 'Attribute (for Compare Products)', 'porto-functionality' ),
							'dependency' => array(
								'element'   => 'show_attributes',
								'not_empty' => true,
							),
						)
					)
				),
			)
		);
		vc_map(
			array(
				'name'                    => __( 'Product Share', 'porto-functionality' ),
				'description'             => __( 'Follow social or share buttons in product page.', 'porto-functionality' ),
				'base'                    => 'porto_single_product_share',
				'icon'                    => PORTO_WIDGET_URL . 'sp-share.png',
				'class'                   => 'porto-wpb-widget',
				'category'                => __( 'Product Page', 'porto-functionality' ),
				'show_settings_on_create' => false,
				'params'                  => array(
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Icon Font Size', 'porto-functionality' ),
						'param_name' => 'icon_size',
						'units'      => array( 'px', 'rem', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} a' => 'font-size: {{VALUE}}{{UNIT}};',
						),
					),
					array(
						'type'        => 'porto_number',
						'heading'     => __( 'Icon Width and Height', 'porto-functionality' ),
						'param_name'  => 'icon_border_spacing',
						'units'       => array( 'px', 'rem', 'em' ),
						'selectors'   => array(
							'{{WRAPPER}} a' => 'width: {{VALUE}}{{UNIT}};height: {{VALUE}}{{UNIT}};',
						),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Spacing between icons', 'porto-functionality' ),
						'param_name' => 'icon_spacing',
						'units'      => array( 'px', 'rem', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} a' => 'margin-' . $right . ': {{VALUE}}{{UNIT}};',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Icon Border Radius', 'porto-functionality' ),
						'param_name' => 'icon_border_radius',
						'selectors'  => array(
							'{{WRAPPER}} a' => 'border-radius: {{VALUE}};',
						),
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
						'selectors'  => array(
							'{{WRAPPER}} a' => 'border-style: {{VALUE}};',
						),
					),
					array(
						'type'       => 'porto_number',
						'heading'    => __( 'Icon Border Width', 'porto-functionality' ),
						'param_name' => 'icon_border_size',
						'units'      => array( 'px', 'rem', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} a' => 'border-width: {{VALUE}}{{UNIT}};',
						),
						'dependency' => array(
							'element'   => 'icon_border_style',
							'not_empty' => true,
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color', 'porto-functionality' ),
						'param_name' => 'icon_color',
						'selectors'  => array(
							'{{WRAPPER}} .share-links a:not(:hover)' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Icon Background Color', 'porto-functionality' ),
						'param_name' => 'icon_color_bg',
						'selectors'  => array(
							'{{WRAPPER}} .share-links a:not(:hover)' => 'background-color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Icon Border Color', 'porto-functionality' ),
						'param_name' => 'icon_color_border',
						'selectors'  => array(
							'{{WRAPPER}} a' => 'border-color: {{VALUE}};',
						),
						'dependency' => array(
							'element'   => 'icon_border_style',
							'not_empty' => true,
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Hover Color', 'porto-functionality' ),
						'param_name' => 'icon_hover_color',
						'selectors'  => array(
							'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
						'param_name' => 'icon_hover_color_bg',
						'selectors'  => array(
							'{{WRAPPER}} a:hover' => 'background-color: {{VALUE}};',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Hover Border Color', 'porto-functionality' ),
						'param_name' => 'icon_hover_color_border',
						'selectors'  => array(
							'{{WRAPPER}} a:hover' => 'border-color: {{VALUE}};',
						),
						'dependency' => array(
							'element'   => 'icon_border_style',
							'not_empty' => true,
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
	}

	public function add_shortcodes_css( $post_id, $post ) {
		if ( ! $post || ! isset( $post->post_type ) || PortoBuilders::BUILDER_SLUG != $post->post_type || ! $post->post_content || 'product' != get_post_meta( $post_id, PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( defined( 'WPB_VC_VERSION' ) && false !== strpos( $post->post_content, '[porto_single_product_' ) ) {
			ob_start();
			$css = '';
			preg_match_all( '/' . get_shortcode_regex( array( 'porto_single_product_title', 'porto_single_product_price', 'porto_single_product_excerpt', 'porto_single_product_rating' ) ) . '/', $post->post_content, $shortcodes );
			foreach ( $shortcodes[2] as $index => $tag ) {
				$atts = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
				include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-' . str_replace( array( 'porto_single_product_', '_' ), array( '', '-' ), $tag ) . '.php';
			}
			$css = ob_get_clean();
			if ( $css ) {
				update_post_meta( $post_id, 'porto_builder_css', wp_strip_all_tags( $css ) );
			} else {
				delete_post_meta( $post_id, 'porto_builder_css' );
			}
		} elseif ( false !== strpos( $post->post_content, '<!-- wp:porto-single-product' ) ) { // Gutenberg editor
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
	private function include_style( $blocks ) {
		if ( empty( $blocks ) ) {
			return;
		}
		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) && in_array( $block['blockName'], array( 'porto-single-product/porto-sp-title', 'porto-single-product/porto-sp-price', 'porto-single-product/porto-sp-excerpt', 'porto-single-product/porto-sp-rating' ) ) ) {
				$atts = empty( $block['attrs'] ) ? array() : $block['attrs'];
				include PORTO_BUILDERS_PATH . '/elements/product/wpb/style-' . str_replace( 'porto-single-product/porto-sp-', '', $block['blockName'] ) . '.php';
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$this->include_style( $block['innerBlocks'] );
			}
		}
	}

	public function elementor_custom_product_shortcodes( $self ) {
		$load_widgets = false;
		if ( is_singular( 'product' ) ) {
			$load_widgets = true;
		} elseif ( is_singular( PortoBuilders::BUILDER_SLUG ) && 'product' == get_post_meta( get_the_ID(), PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
			$load_widgets = true;
		} elseif ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'elementor_ajax' == $_REQUEST['action'] && ( ! empty( $_POST['editor_post_id'] ) || ! empty( $_POST['initial_document_id'] ) ) ) {
			$load_widgets = true;
		}
		if ( apply_filters( 'porto_elementor_load_builder_widgets', $load_widgets, 'product' ) ) {
			foreach ( $this::$shortcodes as $shortcode ) {
				include_once PORTO_BUILDERS_PATH . '/elements/product/elementor/' . $shortcode . '.php';
				$class_name = 'Porto_Elementor_CP_' . ucfirst( $shortcode ) . '_Widget';
				if ( class_exists( $class_name ) ) {
					$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
				}
			}
		}
	}

	/**
	 * Product Attribute Table
	 * 
	 * @since 3.1.0
	 */
	public function shortcode_single_product_attribute( $atts, $builder = 'wpbakery' ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}

        if ( $template = porto_shortcode_template( 'porto_sp_attr_table' ) ) {
            ob_start();
            $internal_css = '';

            if ( defined( 'WPB_VC_VERSION' ) && 'elementor' != $builder ) {
				$shortcode_name = 'porto_single_product_attribute';
                //Shortcode class
                $shortcode_class = ( empty( $atts['el_class'] ) ? '' : ' ' . $atts['el_class'] ) . ' wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
                    $atts,
                    $shortcode_name,
                    array(
                        array(
                            'param_name' => 'title_typography',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'title_color',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'title_bg_color',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'title_padding',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'column_count',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'column_gap',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'vertical_space',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'col_spacing',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'disable_col_border',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'col_border_color',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'name_typography',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'name_color',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'term_typography',
                            'selectors'  => true,
                        ),
                        array(
                            'param_name' => 'term_color',
                            'selectors'  => true,
                        ),
                    )
                );

                $internal_css = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
				if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
					$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
				}
            }
            include $template;
            $result = ob_get_clean();

            if ( $result && $internal_css ) {
                $first_tag_index = strpos( $result, '>' );
                if ( $first_tag_index ) {
                    $result = substr( $result, 0, $first_tag_index + 1 ) . '<style>' . wp_strip_all_tags( $internal_css ) . '</style>' . substr( $result, $first_tag_index + 1 );
                }
            }
            $this->reset_global_product_variable();
            return $result;
        }
	}

	/**
	 * Product Attribute Table
	 * 
	 * @since 3.1.0
	 */
	public function shortcode_single_product_review( $atts, $builder = 'wpbakery' ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		ob_start();
		comments_template();
		$result = ob_get_clean();
		$this->reset_global_product_variable();

		$el_class = '';
		if ( ! empty( $atts['el_class'] ) ) {
			$el_class = $atts['el_class'] . ' ';
		}
		if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
			$el_class .= apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'porto_single_product_review', $atts );
		}
		if ( ! empty( $el_class ) ) {
			$result = '<div class="' . esc_attr( $el_class ) . '">' . $result . '</div>';
		}
		return $result;
	}

	/**
	 * Product Share Widget
	 * 
	 * @since 3.3.0
	 */
	public function shortcode_single_product_share( $atts ) {
		if ( ! is_product() && ! $this->restore_global_product_variable() ) {
			return null;
		}
		if ( defined( 'WPB_VC_VERSION' ) && empty( $atts['page_builder'] ) ) {
			$shortcode_name = 'porto_single_product_share';
			// Shortcode class
			$shortcode_class = ( empty( $atts['el_class'] ) ? '' : $atts['el_class'] . ' ' ) . 'wpb_custom_' . PortoShortcodesClass::get_global_hashcode(
				$atts,
				$shortcode_name,
				array(
					array(
						'param_name' => 'icon_size',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_border_spacing',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_spacing',
						'selectors'  => true,
					),	
					array(
						'param_name' => 'icon_border_radius',
						'selectors'  => true,
					),	
					array(
						'param_name' => 'icon_border_style',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_border_size',
						'selectors'  => true,
					),	
					array(
						'param_name' => 'icon_color',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_color_bg',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_color_border',
						'selectors'  => true,
					),
					array(
						'param_name' => 'icon_hover_color',
						'selectors'  => true,
					),	
					array(
						'param_name' => 'icon_hover_color_bg',
						'selectors'  => true,
					),	
					array(
						'param_name' => 'icon_hover_color_border',
						'selectors'  => true,
					),			
				)
			);
			$internal_css    = PortoShortcodesClass::generate_wpb_css( $shortcode_name, $atts );
			
			if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css'] ) ) {
				$shortcode_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), $shortcode_name, $atts );
			}
		}

		ob_start();
		if ( ! empty( $shortcode_class ) ) {
			echo '<div class="' . esc_attr( $shortcode_class ) . '">';
		}
		do_action( 'woocommerce_share' );
		if ( ! empty( $shortcode_class ) ) {
			echo '</div>';
		}
		$this->reset_global_product_variable();

		$result = ob_get_clean();

		if ( ! empty( $internal_css ) ) {
			$result = PortoShortcodesClass::generate_insert_css( $result, $internal_css );
		}

		return $result;
	}

	/**
	 * Enqueue Scripts
	 * 
	 * @since 3.2.0
	 */
	public function enqueue_scripts() {
		if ( function_exists( 'porto_is_product' ) && porto_is_product() ) {
			wp_enqueue_style( 'porto-sp-attr-table', PORTO_SHORTCODES_URL . '/assets/cp-attribute-table/attribute-table.css', false, PORTO_FUNC_VERSION );
			wp_enqueue_style( 'porto-compare-product', PORTO_FUNC_URL . 'lib/compare-product/compare-product.css', false, PORTO_FUNC_VERSION );
		}
	}
}

PortoCustomProduct::get_instance();
