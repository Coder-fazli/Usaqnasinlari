<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Product Attribute
 *
 * @since 3.1.0
 */
class Porto_Elementor_CP_Review_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'porto_cp_review';
	}

	public function get_title() {
		return __( 'Product Review', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'review', 'rating', 'star' );
	}

	public function get_icon() {
		return 'porto-icon-star-empty porto-elementor-widget-icon';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			echo PortoCustomProduct::get_instance()->shortcode_single_product_review( $settings, 'elementor' );
		}
    }
}
