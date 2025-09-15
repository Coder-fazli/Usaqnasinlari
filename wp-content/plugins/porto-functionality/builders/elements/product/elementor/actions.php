<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Hooks Widget
 *
 * Porto Elementor widget to run default hooks on the single product page when using custom product layout
 *
 * @since 1.7.1
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Actions_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_cp_actions';
	}

	public function get_title() {
		return __( 'Product Hooks (Deprecated)', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'product', 'action', 'perfect brands for woocommerce', 'share', 'social', 'yith woocommerce brands', 'Back In Stock and Price Alert', 'extra plugin' );
	}

	public function get_icon() {
		return 'eicon-product-info porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}

	public function get_script_depends() {
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			return array( 'porto-elementor-widgets-js', 'easy-responsive-tabs' );
		} else {
			return array();
		}
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_cp_actions',
			array(
				'label' => __( 'Product Hooks', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'action',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Select an Action', 'porto-functionality' ),
					'label_block' => true,
					'options'     => array(
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
					'default' => 'woocommerce_single_product_summary',
				)
			);

			$this->add_control(
				'extra_plugin',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'For extra plugin', 'porto-functionality' ),
					'description' => sprintf( esc_html__( 'Apply hooks for extra plugins like Perfect Brands WooCommerce, YITH Brands and so on. Please see %1$sdocumentation%2$s.', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-extra-plugin-like-perfect-brands-yith-brands/" target="_blank">', '</a>' ),
					'condition'   => array(
						'action!' => array( 'porto_woocommerce_before_single_product_summary', 'porto_woocommerce_single_product_summary2', 'porto_woocommerce_product_sticky_addcart' ),
					),
				)
			);
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			echo PortoCustomProduct::get_instance()->shortcode_single_product_actions( $settings );
		}
	}
}
