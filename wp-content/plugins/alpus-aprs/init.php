<?php
/**
 * Plugin Name: Alpus AI Product Review Summary Addon
 * Plugin URI: https://alpustheme.com/product/ai-product-review-summay/
 * Description: Summarize bunch of product reviews in few sentences, so that customers can easily evaluate the products pros and corns.
 * Version: 2.2.0
 * Author: AlpusTheme
 * Author URI: https://alpustheme.com/
 * Text Domain: alpus-aprs
 *
 * @author AlpusTheme
 * @package Alpus APRS
 * @version 2.2.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'ALPUS_APRS_VERSION', '2.2.0' );
define( 'ALPUS_APRS_URI', plugin_dir_url( __FILE__ ) );   // plugin dir uri
define( 'ALPUS_APRS_PATH', plugin_dir_path( __FILE__ ) ); // plugin dir path

// Load Action Scheduler
if ( file_exists( ALPUS_APRS_PATH . 'inc/plugins/action-scheduler/action-scheduler.php' ) ) {
	require_once( ALPUS_APRS_PATH . 'inc/plugins/action-scheduler/action-scheduler.php' );
}

if ( ! function_exists( 'alpus_plugin_framework_loader' ) ) {
	require_once( ALPUS_APRS_PATH . 'plugin-framework/init.php' );
}
alpus_plugin_framework_loader( ALPUS_APRS_PATH );

if ( ! class_exists( 'Alpus_APRS_Manager' ) ) :
	class Alpus_APRS_Manager {
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
			add_action( 'plugins_loaded', array( $this, 'load' ) );

			$this->plugin_config = array(
				'slug'        => 'alpus-aprs',
				'name'        => esc_html__( 'AI Product Review Summary', 'alpus-aprs' ),
				'description' => esc_html__( 'Summarize bunch of product reviews in few sentences, so that customers can easily evaluate the products pros and corns.', 'alpus-aprs' ),
				'admin'       => array(
					'tabs' => array(
						'options'     => array(
							'type'    => 'options',
							'title'   => esc_html__( 'General', 'alpus-aprs' ),
							'options' => include ALPUS_APRS_PATH . 'admin/options/options.php',
						),
						'skin' => array(
							'type'    => 'options',
							'title'   => esc_html__( 'Layout & Skin', 'alpus-aprs' ),
							'options' => include ALPUS_APRS_PATH . 'admin/options/skin.php',
						),
						'tags' => array(
							'type'    => 'options',
							'title'   => esc_html__( 'Tags', 'alpus-aprs' ),
							'options' => include ALPUS_APRS_PATH . 'admin/options/tags.php',
						),
						'cache' => array(
							'type'    => 'options',
							'title'   => esc_html__( 'Cache', 'alpus-aprs' ),
							'options' => include ALPUS_APRS_PATH . 'admin/options/cache.php',
						),
					),
				),
			);
		}
		

		/**
		 * Get API Models from OpenAI
		 * 
		 * @since 1.0.0
		 * @access public
		 */
		public function get_models_from_openai() {
			$models = get_site_transient( 'alpus_aprs_api_models', false );
			
			if ( ! empty( $models ) ) {
				return $models;
			} else {
				return array(
					'gpt-3.5-turbo-16k' => 'gpt-3.5-turbo-16k',
				);
			}
		}

		/**
		 * Load require files
		 *
		 * @since 1.0
		 */
		public function load() {
			if ( ! class_exists( 'WooCommerce', false ) ) {
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
			$default_options  = require_once ALPUS_APRS_PATH . '/inc/default-options.php';

			$alpus_pf_options = array_merge( $alpus_pf_options, $default_options );

			$this->framework = new Alpus_Plugin_Framework( $this->plugin_config );

			require_once ALPUS_APRS_PATH . '/inc/class-alpus-aprs.php';
		}
	}
endif;

new Alpus_APRS_Manager;
