<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Upsell Products Widget
 *
 * Porto Elementor widget to display Upsell products on the single product page when using custom product layout
 *
 * @since 1.7.1
 */

use Elementor\Controls_Manager;

require_once 'related.php';

class Porto_Elementor_CP_Upsell_Widget extends Porto_Elementor_CP_Related_Widget {

	public function get_name() {
		return 'porto_cp_upsell';
	}

	public function get_title() {
		return __( 'Upsells', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'product', 'upsell' );
	}

	public function get_icon() {
		return 'eicon-product-upsell porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}
	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'notice_skin',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					__(
						'You can show or hide in %1$sPorto / Theme Options / WooCommerce / 
				Show Up Sells%2$s.',
						'porto-functionality'
					),
					'<a href="' . porto_get_theme_option_url( 'product-upsells' ) . '" target="_blank" class="porto-text-underline">',
					'</a>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			echo PortoCustomProduct::get_instance()->shortcode_single_product_upsell( $settings );
		}
	}
}
