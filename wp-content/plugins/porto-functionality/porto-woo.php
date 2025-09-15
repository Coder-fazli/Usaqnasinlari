<?php
use Automattic\WooCommerce\Packages;

defined( 'ABSPATH' ) || exit;
/**
 * Remove woo gutenberg blocks.
 *
 * @since 2.7.0
 */
class Porto_Woo extends Packages {
	/**
	 * The Constructor
	 *
	 * @since 2.7.0
	 */
	public function __construct() {

		remove_action( 'plugins_loaded', array( 'Packages', 'on_init' ) );
        if ( ! is_admin() && get_option( 'porto_disable_woo_blocks', false ) ) {
    		unset( self::$packages['woocommerce-blocks'] ); // woocommerce block
            if ( function_exists( 'yit_maybe_plugin_fw_loader' ) ) {
                function yith_plugin_fw_is_gutenberg_enabled() {
                    return false;
                }
            }
        }
		if ( method_exists( 'Porto_Woo', 'load_packages' ) ) {
			self::load_packages();
		}
	}
}
new Porto_Woo();
