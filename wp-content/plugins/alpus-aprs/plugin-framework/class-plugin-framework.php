<?php
/**
 * Alpus Plugin Framework
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.2.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( 'ALPUS_PLUGIN_FRAMEWORK_VERSION' ) ) {
	define( 'ALPUS_PLUGIN_FRAMEWORK_VERSION', '1.2.0' );
}

define( 'ALPUS_PLUGIN_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) );
define( 'ALPUS_PLUGIN_FRAMEWORK_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALPUS_PLUGIN_FRAMEWORK_ASSETS', ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/' );
if ( ! defined( 'ALPUS_PLUGIN_JS_SUFFIX' ) ) {
	defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? define( 'ALPUS_PLUGIN_JS_SUFFIX', '.js' ) : define( 'ALPUS_PLUGIN_JS_SUFFIX', '.min.js' );
}

require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'admin/options/class-plugin-options.php';
require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'common-functions.php';

if ( ! class_exists( 'Alpus_Base' ) ) {
	require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'base/class-alpus-base.php';
}

if ( ! class_exists( 'Alpus_Plugin_Framework' ) ) {
	class Alpus_Plugin_Framework extends Alpus_Base {
		/**
		 * Alpus Plugins
		 *
		 * @since 1.0
		 */
		public $alpus_plugins;

		/**
		 * Plugin Config
		 *
		 * @since 1.0
		 */
		public $plugin_config;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct( $config = false ) {
			$this->alpus_plugins = array(
				'alpus-aprs'             => array(
					'url'           => 'alpus-aprs/init.php',
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/aprs.png',
					'name'          => esc_html__( 'AI Product Review Summary', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'Summarize bunch of product reviews in few sentences, so that customers can easily evaluate the products pros and corns.', 'alpus-plugin-framework' ),
					'link'          => 'https://alpustheme.com/product/ai-product-review-summary/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2023/08/29/ai-product-review-summary-plugin/',
					'free'          => true,
				),
				'alpus-flexbox'             => array(
					'url'           => 'alpus-flexbox/init.php',
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/flexbox.jpg',
					'name'          => esc_html__( 'Elementor FlexBox - Nested Carousel', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'Alpus Elementor Flexbox Addon is a powerful plugin with nested carousel and flexbox layouts for enhanced website building.', 'alpus-plugin-framework' ),
					'link'          => 'https://alpustheme.com/product/elementor-flexbox-addon-nested-slider/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2023/05/10/nested-slider-on-elementor-flexbox/',
					'free'          => true,
				),
				'alpus-variation-swatch'    => array(
					'name'          => esc_html__( 'Product Variations Swatch', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product Variations Swatch plugin allows showing your product variations in a better way as customers can sort out colors, labels and images instead of dropdown.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/swatch.jpg',
					'url'           => 'alpus-variation-swatch/init.php',
					'link'          => 'https://alpustheme.com/product/product-variation-swatch/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-variation-swatch/',
					'free'          => true,
				),
				'alpus-product-attr-guide'  => array(
					'name'          => esc_html__( 'Product Attribute Guide', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product Attribute Guide allows you to describe your product attributes in detail. Product attribute guide block appears in the product description tab of product single page.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/guide.jpg',
					'url'           => 'alpus-product-attr-guide/init.php',
					'link'          => 'https://alpustheme.com/product/product-attribute-guide/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-attribute-guide/',
					'free'          => true,
				),
				'alpus-product-brand'       => array(
					'name'          => esc_html__( 'Product Brand', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product Brand plugin allows you to add taxonomy "Brands" on your products easily. It will enhances product details and boost product sales with famous brands.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/brand.jpg',
					'url'           => 'alpus-product-brand/init.php',
					'link'          => 'https://alpustheme.com/product/product-brand/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-brand/',
					'free'          => true,
				),
				'alpus-cookie-consent'      => array(
					'name'          => esc_html__( 'Cookie Consent', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Cookie Consent plugin allows you to use cookies to track visitors in some capacity for analytics or remembering users with comments and popup box for consent.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/gdpr.jpg',
					'url'           => 'alpus-cookie-consent/init.php',
					'link'          => 'https://alpustheme.com/product/cookie-constent/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2023/02/04/cookie-consent/',
					'free'          => true,
				),
				'alpus-product-compare'     => array(
					'name'          => esc_html__( 'Product Compare', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product Compare plugin allow you to compare products in a simple and efficient way with detailed  comparison tables and analyze their main features.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/compare.jpg',
					'url'           => 'alpus-product-compare/init.php',
					'link'          => 'https://alpustheme.com/product/product-compare/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-compare/',
					'free'          => true,
				),
				'alpus-product-360-degree'  => array(
					'name'          => esc_html__( 'Product 360 Degree', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product 360 Degree plugin allows you to see every corner of your products with a full 360° view. It provides customers with as much real shopping experience as possible.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/360.jpg',
					'url'           => 'alpus-product-360-degree/init.php',
					'link'          => 'https://alpustheme.com/product/product-360-degree/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-360-degree/',
					'free'          => true,
				),
				'alpus-product-video-popup' => array(
					'name'          => esc_html__( 'Product Video Popup', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Product Video Popup plugin allows you to provide a complete overview of the product by adding video showing figures from different angles or different ways of usage.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/video.jpg',
					'url'           => 'alpus-product-video-popup/init.php',
					'link'          => 'https://alpustheme.com/product/product-video-thumbnail/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/product-video-popup/',
					'free'          => true,
				),
				'alpus-post-like'           => array(
					'name'          => esc_html__( 'Post Like', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Post Like plugin allows you add or remove posts to the favourite list. You can survey the top rated posts from customers and use that info for your marketing.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/post-like.jpg',
					'url'           => 'alpus-post-like/init.php',
					'link'          => 'https://alpustheme.com/product/post-likes/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2022/12/16/post-like/',
					'free'          => true,
				),
				'alpus-custom-fonts'        => array(
					'name'          => esc_html__( 'Custom Fonts', 'alpus-plugin-framework' ),
					'description'   => esc_html__( 'The Alpus Custom Fonts plugin enables you to use custom fonts for your website. If you cannot find your favourite fonts from google, you can upload your font and just use it.', 'alpus-plugin-framework' ),
					'image'         => ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/font.jpg',
					'url'           => 'alpus-custom-fonts/init.php',
					'link'          => 'https://alpustheme.com/product/custom-font/',
					'documentation' => 'https://alpustheme.com/alpus/documentation/2023/02/03/custom-fonts/',
					'free'          => true,
				),
			);
			
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			foreach ( $this->alpus_plugins as $plugin => $options ) {
				$this->alpus_plugins[ $plugin ]['link'] = ! is_wp_error( validate_plugin( $options['url'] ) ) && is_plugin_active( $options['url'] ) ? esc_url( admin_url( 'admin.php?page=wp-' . $plugin ) ) : $options['link'];
			}

			$this->plugin_config = array(
				'admin' => array(
					'links' => array(
						'docs'    => array(
							'icon'        => 'alpus-plugin-docs',
							'label'       => esc_html__( 'Documentation', 'alpus-plugin-framework' ),
							'description' => esc_html__( 'Get yourself used to with the easy to read documentation we have till now. We\'re always working to make it easier for you.', 'alpus-plugin-framework' ),
							'url'         => ( empty( $_GET['page'] ) || 'alpus-addons' != $_GET['page'] ) && ! empty( $config['slug'] ) && ! empty( $this->alpus_plugins[ $config['slug'] ] ) ? $this->alpus_plugins[ $config['slug'] ]['documentation'] : 'https://alpustheme.com/alpus/documentation/category/alpus-plugins/',
							'button'      => esc_html__( 'Documentation', 'alpus-plugin-framework' ),
						),
						'feature' => array(
							'icon'        => 'alpus-plugin-feature',
							'label'       => esc_html__( 'Request a Feature', 'alpus-plugin-framework' ),
							'description' => esc_html__( 'Missing something? Share your idea with us, drop us an email about your details. We will be in touch with you and develop it.', 'alpus-plugin-framework' ),
							'url'         => 'https://alpustheme.com/contact-us/',
							'button'      => esc_html__( 'Request a Feature', 'alpus-plugin-framework' ),
						),
						'support' => array(
							'icon'        => 'alpus-plugin-support',
							'label'       => esc_html__( 'Need Help?', 'alpus-plugin-framework' ),
							'description' => esc_html__( 'We are always here to listen your beautiful voice. A dedicated support team is on your way to the rescue, the moment you need us.', 'alpus-plugin-framework' ),
							'url'         => 'https://alpustheme.com/forums/',
							'button'      => esc_html__( 'Need Help?', 'alpus-plugin-framework' ),
						),
					),
				),
			);

			if ( ! empty( $config ) ) {
				$this->plugin_config = array_merge_recursive( $this->plugin_config, $config );
			}

			// Plugin config
			add_action( 'init', array( $this, 'config_plugin_info' ) );

			// Load styles & scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Print plugin options styles
			add_action( 'wp_footer', array( $this, 'enqueue_dynamic_styles' ), 99 );

			// Add admin menu
			add_action( 'admin_menu', array( $this, 'add_admin_menus' ), 5 );

			// Handle saving settings
			add_action( 'wp_loaded', array( $this, 'manage_options' ) );

			if ( isset( $_REQUEST['action'] ) && 'alpus_save_repeater_element' == $_REQUEST['action'] ) {
				$this->save_repeater_element_options();
			}
			// Load text domain
			$this->load();

			// Add plugins to tgmpa list
			add_filter( 'alpha_plugin', array( $this, 'register_plugins' ) );

			// Plugin Installer
			require_once ALPUS_PLUGIN_FRAMEWORK_PATH . '/install/class-plugin-install.php';
		}

		/**
		 * Set plugin review url
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function config_plugin_info() {
			$this->plugin_config['admin']['links']['review'] = array(
				'icon'        => 'alpus-plugin-review',
				'label'       => esc_html__( 'Show Your Love', 'alpus-plugin-framework' ),
				'description' => esc_html__( 'Support us by giving a 5 Star rating on our WordPress plugin Repository. It will inspire us to do better work for you.', 'alpus-plugin-framework' ),
				'url'         =>  ( empty( $_GET['page'] ) || 'alpus-addons' != $_GET['page'] ) && ! empty( $config['slug'] ) && ! empty( $this->alpus_plugins[ $config['slug'] ] ) ? 'https://alpustheme.com/product/' . str_replace( 'alpus-', '', $config['slug'] ) . '/#reviews' : ( defined( 'ALPHA_ENVATO_CODE' ) && ALPHA_ENVATO_CODE ? 'http://themeforest.net/downloads' : 'https://wordpress.org/support/theme/alpus/reviews/#new-post' ),
				'button'      => esc_html__( 'Leave a Review', 'alpus-plugin-framework' ),
			);
		}

		/**
		 * Register addon plugins
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function register_plugins( $plugins ) {
			if ( ! function_exists( 'alpha_framework_path' ) ) {
				return $plugins;
			}

			foreach ( $this->alpus_plugins as $slug => $plugin ) {
				$plugins[ $slug ] = array(
					'name'       => $plugin['name'],
					'slug'       => $slug,
					'required'   => false,
					'url'        => $plugin['url'],
					'visibility' => '',
				);
			}

			return $plugins;
		}

		/**
		 * Load required files
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function load() {
			if ( ! empty( $this->plugin_config['slug'] ) ) {
				load_plugin_textdomain( $this->plugin_config['slug'], false, dirname( plugin_basename( __FILE__ ), 2 ) . '/languages' );
			}

			if ( ! is_textdomain_loaded( 'alpus-plugin-framework' ) ) {
				load_plugin_textdomain( 'alpus-plugin-framework', false, dirname( __FILE__ ) . '/languages' );
			}

		}

		/**
		 * Load style & scripts
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			// Styles
			if ( ! defined( 'ALPHA_VERSION' ) ) {
				wp_enqueue_style( 'alpus-plugin-framework-base', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'css/base' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_PLUGIN_FRAMEWORK_VERSION );
			}
			wp_enqueue_style( 'alpus-plugin-framework', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'css/framework' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_PLUGIN_FRAMEWORK_VERSION );

			// Scripts
			wp_enqueue_script( 'alpus-plugin-framework', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'js/framework' . ALPUS_PLUGIN_JS_SUFFIX, array( 'jquery-core' ), ALPUS_PLUGIN_FRAMEWORK_VERSION, true );


		}

		/**
		 * Print plugin options styles
		 *
		 * @since 1.0
		 */
		public function enqueue_dynamic_styles() {
			if ( isset( $this->plugin_config['admin']['tabs'] ) ) {
				$tabs   = $this->plugin_config['admin']['tabs'];
				$styles = '';
				foreach ( $tabs as $tab ) {
					if ( 'options' == $tab['type'] && count( $tab['options'] ) ) {
						$options     = $tab['options'];
						$ignore_list = array( 'section_start', 'section_end' );
						foreach ( $options as $key => $option ) {
							if ( ! in_array( $option['type'], $ignore_list ) && isset( $option['selectors'] ) ) {
								if ( ! empty( $option['dependency'] ) ) {
									if ( '==' == $option['dependency']['operator'] ) {
										if ( $option['dependency']['value'] != Alpus_Plugin_Options::get_option( $option['dependency']['option'], isset( $options[ $option['dependency']['option'] ]['default'] ) ? $options[ $option['dependency']['option'] ]['default'] : '' ) ) {
											continue;
										}
									} else {
										if ( $option['dependency']['value'] == Alpus_Plugin_Options::get_option( $option['dependency']['option'], isset( $options[ $option['dependency']['option'] ]['default'] ) ? $options[ $option['dependency']['option'] ]['default'] : '' ) ) {
											continue;
										}
									}
								}
								foreach ( $option['selectors'] as $selector => $css ) {
									if ( '' === Alpus_Plugin_Options::get_option( $key, isset( $option['default'] ) ? $option['default'] : null ) ) {
										continue;
									}

									$value = Alpus_Plugin_Options::get_option( $key, isset( $option['default'] ) ? $option['default'] : '' );

									if ( '' !== $value ) {
										$styles .= $selector . '{' . str_replace( '{{VALUE}}', $value, $css ) . '}';
									}
								}
							}
						}
					}
				}
				if ( $styles ) {
					echo '<style>' . $styles . '</style>';
				}
			}
		}

		/**
		 * Load styles & scripts for Admin pages
		 *
		 * @since 1.0
		 */
		public function admin_enqueue_scripts() {

			// Styles
			wp_enqueue_style( 'alpus-plugin-framework-admin', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'css/framework-admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_PLUGIN_FRAMEWORK_VERSION );

			// Load google font
			wp_enqueue_style( 'alpus-admin-fonts', apply_filters( 'alpus_admin_fonts', '//fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600%2C700' ) );

			// Scripts
			wp_enqueue_script( 'alpus-plugin-framework-admin', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'js/framework-admin' . ALPUS_PLUGIN_JS_SUFFIX, array( 'jquery-core', 'wp-util' ), ALPUS_PLUGIN_FRAMEWORK_VERSION, true );

			wp_enqueue_media();

			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$active_plugins = 0;
			foreach ( $this->alpus_plugins as $plugin => $data ) {
				if ( is_plugin_active( $data['url'] ) ) {
					$active_plugins ++;
				}
			}

			wp_localize_script(
				'alpus-plugin-framework-admin',
				'alpus_plugin_framework_vars',
				array(
					'ajax_url'        => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'           => wp_create_nonce( 'alpus-plugin-framework-admin' ),
					'admin_url'       => esc_url( admin_url() ),
					'active_plugins'  => $active_plugins,
					'core_plugin'     => class_exists( 'Alpha_Core' ),
					'texts'           => array(
						'failed'           => esc_html__( 'Failed', 'alpus-plugin-framework-admin' ),
					)
				)
			);

			// SweetAlert
			wp_enqueue_script( 'alpus-plugin-sweetalert', ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'vendor/sweetalert/sweetalert.min.js', array( 'jquery-core' ), ALPUS_PLUGIN_FRAMEWORK_VERSION, true );
		}

		/**
		 * Add admin nav menus
		 *
		 * @since 1.0
		 */
		public function add_admin_menus() {
			if ( current_user_can( 'edit_theme_options' ) ) {
				global $alpus_plugin_admin;

				if ( ! isset( $alpus_plugin_admin['admin_panel'] ) ) {
					// Menu - alpus-addons
					add_menu_page( esc_html__( 'Alpus Addons', 'alpus-plugin-framework' ), esc_html__( 'Alpus Addons', 'alpus-plugin-framework' ), 'administrator', 'alpus-addons', array( $this, 'dashboard' ), 'dashicons-alpus-addon-logo', '3' );

					$alpus_plugin_admin['admin_panel'] = 'alpus-addons';
				}

				if ( ! empty( $this->plugin_config['name'] ) && ! empty( $this->plugin_config['slug'] ) && ! empty( $this->plugin_config['admin']['tabs'] ) ) {
					add_submenu_page( 'alpus-addons', $this->plugin_config['name'], $this->plugin_config['name'], 'administrator', 'wp-' . $this->plugin_config['slug'], array( $this, ! empty( $this->plugin_config['no_use_tab'] ) ? 'plugin_panel' : 'plugin_panel_tab' ) );
				}
			}
		}

		/**
		 * Load header template for admin panel.
		 *
		 * @since 1.0
		 */
		public function view_header() {
			require_once ALPUS_PLUGIN_FRAMEWORK_PATH . '/admin/views/header.php';
		}

		/**
		 * Load footer template for admin panel.
		 *
		 * @since 1.0
		 */
		public function view_footer() {
			require_once ALPUS_PLUGIN_FRAMEWORK_PATH . '/admin/views/footer.php';
		}

		/**
		 * Load dashboard template for admin panel
		 *
		 * @since 1.0
		 */
		public function dashboard() {
			$this->view_header();
			require_once ALPUS_PLUGIN_FRAMEWORK_PATH . '/admin/views/dashboard.php';
			$this->view_footer();
		}

		/**
		 * Get Current Tab Slug
		 *
		 * @since 1.0
		 */
		public function get_current_tab_slug() {
			$curslug = '';
			if ( isset( $_GET ) && isset( $_GET['page'] ) && 'wp-' . $this->plugin_config['slug'] == $_GET['page'] && isset( $_GET['tab'] ) ) {
				$curslug = $_GET['tab'];
			}

			return $curslug;
		}

		/**
		 * Get Current Tab Options
		 *
		 * @return array
		 *
		 * @since 1.0
		 */
		public function get_current_options() {
			$curslug = $this->get_current_tab_slug();

			if ( empty( $this->plugin_config['admin']['tabs'] ) ) {
				return false;
			}
			$tabs = $this->plugin_config['admin']['tabs'];

			if ( empty( $this->plugin_config['no_use_tab'] ) ) {
				$all_options = array();
				foreach ( $tabs as $each_tab ) {
					if ( isset( $each_tab['options'] ) ) {
						$all_options = array_merge( $all_options, $each_tab['options'] );
					}
				}
				return  $all_options;
			}

			if ( ! empty( $curslug ) ) {
				if ( empty( $tabs[ $curslug ]['options'] ) ) {
					return false;
				}
				return $tabs[ $curslug ]['options'];
			} else { // First Item
				foreach ( $tabs as $first_tab ) {
					if ( empty( $first_tab['options'] ) ) {
						return false;
					}
					return $first_tab['options'];
				}
			}
		}

		/**
		 * Print settings tab
		 */
		public function print_tabs( $curslug = '' ) {
			if ( empty( $this->plugin_config['admin']['tabs'] ) ) {
				return;
			}

			$tabs = $this->plugin_config['admin']['tabs'];

			if ( '' == $curslug ) {
				$curslug = array_key_first( $tabs );
			}

			if ( ! empty( $tabs[ $curslug ] ) ) {
				$current_panel = $tabs[ $curslug ];
				$type          = isset( $current_panel['type'] ) ? $current_panel['type'] : '';

				if ( 'options' == $type && ! empty( $current_panel['options'] ) ) {
					// Options Panel
					Alpus_Plugin_Options::print_fields( $current_panel['options'] );

					echo '<p class="submit">';
					echo '<button name="save" class="button-primary alpus-plugin-save-button" type="submit" value="' . esc_attr__( 'Save Options', 'alpus-plugin-framework' ) . '">' . esc_html__( 'Save Options', 'alpus-plugin-framework' ) . '</button>';
					echo '<button name="reset" class="button-secondary alpus-plugin-reset-button" type="submit" value="' . esc_attr__( 'Reset Defaults', 'alpus-plugin-framework' ) . '" onclick="return confirm(\'' . esc_html__( 'If you continue with this action, you will reset all options in this page.\nAre you sure?', 'alpus-plugin-framework' ) . '\');">' . esc_html__( 'Reset Defaults', 'alpus-plugin-framework' ) . '</button>';
					echo wp_nonce_field( 'alpus-plugin-settings' );

					echo '</p>';
				} elseif ( 'page' == $type && ! empty( $current_panel['path'] ) ) {
					// Page Panel
					require_once $current_panel['path'];
				}
			}
		}

		/**
		 * Load plugin panel
		 *
		 * @since 1.0
		 */
		public function plugin_panel() {
			$curslug     = $this->get_current_tab_slug();
			$plugin_slug = $this->plugin_config['slug'];
			$tabs        = $this->plugin_config['admin']['tabs'];
			if ( 'critical_css' == $curslug && class_exists( 'Alpus_Critical' ) ) {
				Alpus_Critical::get_instance()->view_critical( $curslug, $plugin_slug, $tabs );
				return;
			}
			$this->view_header();
			?>
			<div class="alpus-plugin-options">
				<?php
				echo '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';
				$index = 0;
				foreach ( $tabs as $slug => $tab ) {
					echo '<a href="' . esc_url( admin_url( 'admin.php?page=wp-' . $plugin_slug . '&tab=' . $slug ) ) . '" class="nav-tab ' . ( ( $slug == $curslug || ( '' == $curslug && 0 == $index ) ) ? ' nav-tab-active' : '' ) . '">' . ( isset( $tab['title'] ) ? $tab['title'] : '' ) . '</a>';
					$index ++;
				}
				echo '</nav>';
				?>
				<form method="post" id="pluginform">
					<?php
						$this->print_tabs( $curslug );
					?>
				</form>
			</div>
			<?php
			$this->view_footer();
		}

		/**
		 * Load plugin panel tab.
		 *
		 * @since 1.0
		 */
		public function plugin_panel_tab() {
			$curslug     = $this->get_current_tab_slug();
			$plugin_slug = $this->plugin_config['slug'];
			$tabs        = $this->plugin_config['admin']['tabs'];
			$this->view_header();
			?>
			<div class="alpus-plugin-options use-tab">
				<?php
				echo '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';
				$index = 0;
				foreach ( $tabs as $slug => $tab ) {
					echo '<a href="#" data-tab="' . $slug . '" class="nav-tab ' . ( ( $slug == $curslug || ( '' == $curslug && 0 == $index ) ) ? ' nav-tab-active' : '' ) . '">' . ( isset( $tab['title'] ) ? $tab['title'] : '' ) . '</a>';
					$index ++;
				}
				echo '</nav>';
				?>
				<form method="post" id="pluginform">
					<?php
					$index = 0;
					foreach ( $tabs as $slug => $current_panel ) {
						$type = isset( $current_panel['type'] ) ? $current_panel['type'] : '';

						echo "<div class=\"tab-content {$slug}" . ( $index == 0 ? ' active' : '' ) . '">';
						if ( 'options' == $type && ! empty( $current_panel['options'] ) ) {
							// Options Panel
							Alpus_Plugin_Options::print_fields( $current_panel['options'] );

						} elseif ( 'page' == $type && ! empty( $current_panel['path'] ) ) {
							// Page Panel
							require_once $current_panel['path'];
						}
						echo '</div>';
						$index++;
					}
						echo '<p class="submit">';
						echo '<button name="save" class="button-primary alpus-plugin-save-button" type="submit" value="' . esc_attr__( 'Save Options', 'alpus-plugin-framework' ) . '">' . esc_html__( 'Save Options', 'alpus-plugin-framework' ) . '</button>';
						echo '<button name="reset" class="button-secondary alpus-plugin-reset-button" type="submit" value="' . esc_attr__( 'Reset Defaults', 'alpus-plugin-framework' ) . '" onclick="return confirm(\'' . esc_html__( 'If you continue with this action, you will reset all options in this page.\nAre you sure?', 'alpus-plugin-framework' ) . '\');">' . esc_html__( 'Reset Defaults', 'alpus-plugin-framework' ) . '</button>';
						echo wp_nonce_field( 'alpus-plugin-settings' );

						echo '</p>';
					?>
				</form>
			</div>
			<?php
			$this->view_footer();
		}

		/**
		 * Save settings
		 *
		 * @since 1.0
		 */
		public function manage_options() {
			if ( ! is_admin() || ! isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( $_GET['page'] != ( 'wp-' . $this->plugin_config['slug'] ) ) {
				return;
			}

			$current_options = $this->get_current_options();
			if ( ! empty( $_POST['save'] ) ) {
				Alpus_Plugin_Options::save( $current_options );
			} elseif ( ! empty( $_POST['reset'] ) ) {
				Alpus_Plugin_Options::reset( $current_options );
			}
		}


		public function save_repeater_element_options() {
			check_ajax_referer( 'alpus-repeater', 'security' );

			$posted           = $_POST;
			$repeater_options = $this->get_current_options();
			$option_id        = isset( $_REQUEST['repeater_id'] ) ? sanitize_key( wp_unslash( $_REQUEST['repeater_id'] ) ) : '';
			$updated          = false;

			$option_array = $repeater_options[ $option_id ]['elements'];

			if ( ! empty( $option_array ) && ! empty( $option_id ) ) {
				$value = isset( $posted[ $option_id ] ) ? $posted[ $option_id ] : '';

				$value = wp_unslash( $value ); // The value must be un-slashed before using it in self::sanitize_option.

				$value = Alpus_Plugin_Options::sanitize_option( $option_array, $value );

				$updated = update_option( $option_id, $value );
			}

			return $updated;
		}
	}
}
