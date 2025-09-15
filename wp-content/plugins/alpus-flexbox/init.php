<?php
/**
 * Plugin Name: Alpus Elementor FlexBox Addon
 * Plugin URI: https://alpustheme.com/product/elementor-flexbox-addon-nested-slider/
 * Description: Alpus Elementor Flexbox Addon is a powerful plugin with nested carousel, conditional rendering, and flexbox layouts for enhanced website building.
 * Version: 1.1.0
 * Author: AlpusTheme
 * Author URI: https://alpustheme.com/
 * Text Domain: alpus-flexbox
 *
 * @author AlpusTheme
 * @package Alpus Flexbox
 * @version 1.1.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'ALPUS_FLEXBOX_VERSION', '1.1.0' );
define( 'ALPUS_FLEXBOX_URI', plugin_dir_url( __FILE__ ) );   // plugin dir uri
define( 'ALPUS_FLEXBOX_PATH', plugin_dir_path( __FILE__ ) ); // plugin dir path

if ( ! function_exists( 'alpus_plugin_framework_loader' ) ) {
	require_once( ALPUS_FLEXBOX_PATH . 'plugin-framework/init.php' );
}
alpus_plugin_framework_loader( ALPUS_FLEXBOX_PATH );

if ( ! class_exists( 'Alpus_Flexbox_Manager' ) ) :
	class Alpus_Flexbox_Manager {
		/**
		 * Plugin Config
		 *
		 * @since 1.0
		 */
		public $plugin_config = '';

		/**
		 * Framework Class
		 */
		public $framework;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->plugin_config = array(
				'slug'        => 'alpus-flexbox',
				'name'        => esc_html__( 'Elementor FlexBox - Nested Carousel', 'alpus-flexbox' ),
				'description' => esc_html__( 'Alpus Elementor Flexbox Addon is a powerful plugin with nested carousel and flexbox layouts for enhanced website building.', 'alpus-flexbox' ),
			);
			add_action( 'plugins_loaded', array( $this, 'load' ) );
		}

		/**
		 * Load require files
		 *
		 * @since 1.0
		 */
		public function load() {

			if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
				return;
			}
			global $alpus_plugin_fw_data;
			if ( ! empty( $alpus_plugin_fw_data ) ) {
				require_once array_values( $alpus_plugin_fw_data )[0];
			}

			global $alpus_pf_options; // alpus plugin framework options
			if ( ! $alpus_pf_options ) {
				$alpus_pf_options = array();
			}
			// $default_options  = require_once ALPUS_COOKIE_CONSENT_PATH . '/inc/default-options.php';
			// $alpus_pf_options = array_merge( $alpus_pf_options, $default_options );

			$this->framework = new Alpus_Plugin_Framework( $this->plugin_config );

			require_once ALPUS_FLEXBOX_PATH . '/inc/class-alpus-flexbox.php';
		}
	}
endif;

new Alpus_Flexbox_Manager;
