<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shop Builder Archive Category
 * 
 * @since 3.1.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_Archive_Category_Widget extends Porto_Elementor_Posts_Grid_Widget {
    public function get_name() {
		return 'porto_archive_category';
	}

	public function get_title() {
		return __( 'Shop Page Subcategories', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-elements' );
	}

	public function get_keywords() {
		return array( 'show subcategories & products', 'builder', 'custom', 'shop', 'category', 'archive subcategory' );
	}

	public function get_icon() {
		return 'eicon-product-categories porto-elementor-widget-icon';
	}
    protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'source',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'warning',
				'heading'    => esc_html__( 'For Shop Page', 'porto-functionality' ),
				'content'    => esc_html__( 'The widget can only be used to display product categories on the shop page.', 'porto-functionality' ),
			]
		);
        // $this->remove_control( 'source' );
        $this->remove_control( 'post_type' );
        $this->remove_control( 'product_status' );
        $this->remove_control( 'post_tax' );
        $this->remove_control( 'post_terms' );
        $this->remove_control( 'post_ids' );
        $this->remove_control( 'tax' );
        $this->remove_control( 'terms' );
        $this->remove_control( 'hide_empty' );

    }
    protected function render() {

		$atts    = $this->get_settings_for_display();
        $builder = 'elementor';
		if ( $template = porto_shortcode_woo_template( 'porto_archive_category' ) ) {
        	include $template;
		}
	}
}
