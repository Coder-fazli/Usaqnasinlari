<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Common Widget
 *
 * Porto Elementor widget to give effects to all widgets.
 *
 * @since 2.2.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_Common_Widget extends \Elementor\Widget_Common {
	public function __construct( array $data = [], array $args = null ) {
		parent::__construct( $data, $args );

		add_action( 'elementor/frontend/widget/before_render', array( $this, 'widget_before_render' ) );
	}

	public function get_script_depends() {
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			return array( 'porto-focus-slider' );
		} else {
			return array();
		}
	}

	protected function register_controls() {
		parent::register_controls();
		// Animation Effects
		porto_elementor_animation_controls( $this, array(), '_' );
		// Mouse Parallax
		porto_elementor_mpx_controls( $this );

		// Foucs on Slider Item 
		$this->start_controls_section(
			'foucs_slider_item',
			array(
				'label' => __( 'Foucs on Slider Item', 'porto-functionality' ),
				'tab'   => Porto_Elementor_Editor_Custom_Tabs::TAB_CUSTOM,
			)
		);

			$this->add_control(
				'enable_focus',
				array(
					'label'       => esc_html__( 'Enable', 'porto-functionality' ),
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'If clicked, the selected slider item is focused.', 'porto-functionality' ),
				)
			);

			$this->add_control(
				'slider_selector',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => __( 'Slider Selector', 'porto-functionality' ),
					'description' => __( 'Input Selector of Slider Wrap. ex: .product-slider, #product-slider', 'porto-functionality' ),
					'render_type' => 'template',
					'condition'   => array(
						'enable_focus' => 'yes',
					),
					'render_type' => 'template',
				)
			);

			$this->add_control(
				'item_order',
				array(
					'type'        => Controls_Manager::NUMBER,
					'label'       => __( 'Item Order(> 0)', 'porto-functionality' ),
					'min'         => 1,
					'max'         => 10,
					'condition'   => array(
						'enable_focus'     => 'yes',
						'slider_selector!' => '',
					),
					'render_type' => 'template',
				)
			);

		$this->end_controls_section();
	}

	public function widget_before_render( $widget ) {
		$atts = $widget->get_settings_for_display();

		$widget->add_render_attribute(
			'_wrapper',
			porto_get_mpx_options( $atts )
		);

		if ( isset( $atts['enable_focus'] ) && 'yes' == $atts['enable_focus'] && ! empty( $atts['slider_selector'] ) && ! empty( $atts['item_order'] ) ) {
			wp_enqueue_script( 'porto-focus-slider' );
			$widget->add_render_attribute( '_wrapper', 'class', 'porto-focus-slider' );
			$widget->add_render_attribute( '_wrapper', 'data-focus-slider', esc_attr( json_encode( array( 'selector' => $atts['slider_selector'], 'order' => $atts['item_order'] - 1 ) ) ) );
		}
	}
}
