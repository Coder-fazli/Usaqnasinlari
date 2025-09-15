<?php
/**
 * Alpus Plugin Framework
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Alpus_Plugin_Install' ) ) {
	class Alpus_Plugin_Install extends Alpus_Base {

		public function __construct() {
			// Plugin Install Actions
			add_action( 'wp_ajax_alpus_install_plugin', array( $this, 'install_plugin' ) );
			add_action( 'wp_ajax_nopriv_alpus_install_plugin', array( $this, 'install_plugin' ) );

			// Plugin Active / Deactive Actions
			add_action( 'wp_ajax_alpus_manage_plugin', array( $this, 'manage_plugin' ) );
			add_action( 'wp_ajax_nopriv_alpus_manage_plugin', array( $this, 'manage_plugin' ) );
		}

		/**
		 * Get plugins for demo import.
		 *
		 * @return array Get plugins.
		 * @since 1.0
		 */
		public function _get_plugins() {
			if ( ! defined( 'ALPHA_VERSION' ) ) {
				return;
			}

			$instance         = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
			$plugin_func_name = 'is_plugin_active';

			$plugins          = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);
			foreach ( $instance->plugins as $slug => $plugin ) {
				$new_version = $instance->does_plugin_have_update( $slug );
				if ( ( ! empty( $plugin['visibility'] ) && 'setup_wizard' != $plugin['visibility'] ) || ( $instance->$plugin_func_name( $slug ) && false === $new_version ) ) {
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $new_version ) {
							$plugins['update'][ $slug ] = $plugin;
							$plugins['update'][ $slug ]['version'] = $new_version;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}
			return $plugins;
		}

		/**
		 * Active / Deactive Plugin
		 *
		 * @since 1.0
		 */
		public function manage_plugin() {
			if ( check_ajax_referer( 'alpus-plugin-framework-admin', 'nonce' ) && current_user_can( 'install_plugins' ) && ! empty( $_POST['plugin'] ) ) {
				if ( 'true' == $_POST['status'] ) {
					$res = activate_plugin( ABSPATH . PLUGINDIR . '/' . $_POST['plugin'], '', false, true );
				} else {
					$res = deactivate_plugins( array( $_POST['plugin'] ) );
				}

				echo null === $res ? 'success' : 'failure';
			}

			die;
		}

		/**
		 * Install Plugin
		 *
		 * @since 1.0
		 */
		public function install_plugin() {
			if ( check_ajax_referer( 'alpus-plugin-framework-admin', 'nonce' ) && current_user_can( 'install_plugins' ) && ! empty( $_POST['plugin'] ) ) {
				$json = array();
				$plugins = $this->_get_plugins();

				// what are we doing with this plugin?
				foreach ( $plugins['activate'] as $slug => $plugin ) {
					if ( $_POST['plugin'] == $slug ) {
						$json = array(
							'url'           => esc_url( admin_url( Alpha_Setup_Wizard::get_instance()->tgmpa_url ) ),
							'plugin'        => array( $slug ),
							'tgmpa-page'    => Alpha_Setup_Wizard::get_instance()->tgmpa_menu_slug,
							'plugin_status' => 'all',
							'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
							'action'        => 'tgmpa-bulk-activate',
							'action2'       => -1,
							'message'       => esc_html__( 'Activating', 'alpus-plugin-framework' ),
						);
						break;
					}
				}
				foreach ( $plugins['update'] as $slug => $plugin ) {
					if ( $_POST['plugin'] == $slug ) {
						$json = array(
							'url'           => esc_url( admin_url( Alpha_Setup_Wizard::get_instance()->tgmpa_url ) ),
							'plugin'        => array( $slug ),
							'tgmpa-page'    => Alpha_Setup_Wizard::get_instance()->tgmpa_menu_slug,
							'plugin_status' => 'all',
							'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
							'action'        => 'tgmpa-bulk-update',
							'action2'       => -1,
							'message'       => esc_html__( 'Updating', 'alpus-plugin-framework' ),
						);
						break;
					}
				}
				foreach ( $plugins['install'] as $slug => $plugin ) {
					if ( $_POST['plugin'] == $slug ) {
						$json = array(
							'url'           => esc_url( admin_url( Alpha_Setup_Wizard::get_instance()->tgmpa_url ) ),
							'plugin'        => array( $slug ),
							'tgmpa-page'    => Alpha_Setup_Wizard::get_instance()->tgmpa_menu_slug,
							'plugin_status' => 'all',
							'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
							'action'        => 'tgmpa-bulk-install',
							'action2'       => -1,
							'message'       => esc_html__( 'Installing', 'alpus-plugin-framework' ),
						);
						break;
					}
				}

				if ( $json ) {
					$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
					wp_send_json( $json );
				} else {
					wp_send_json(
						array(
							'done'    => 1,
							'message' => esc_html__(
								'Success',
								'alpus-plugin-framework'
							),
						)
					);
				}
			}
			exit;
		}
	}
}

/**
 * Create instance
 *
 * @since 1.0
 */
Alpus_Plugin_Install::get_instance();
