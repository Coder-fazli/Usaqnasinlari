<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Wishlist Widget
 *
 * @since 2.4.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Wishlist_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_cp_wishlist';
	}

	public function get_title() {
		return __( 'Product Wishlist', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'yith', 'heart', 'single' );
	}

	public function get_icon() {
		return 'eicon-heart-o porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';
		$this->start_controls_section(
			'section_cp_wishlist',
			array(
				'label' => __( 'Product Wishlist', 'porto-functionality' ),
			)
		);
			$this->add_control(
				'show_label',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show Label', 'porto-functionality' ),
					'description' => __( 'Show/Hide the wishlist label.', 'porto-functionality' ),
					'render_type' => 'template',
					'default'     => 'yes',
					'selectors'   => array(
						'.elementor-element-{{ID}} a, .elementor-element-{{ID}} a span' => 'width: auto;text-indent: 0;',
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a:before' => "position: static;margin-{$right}: 0.125rem;line-height: 1;",
					),
				)
			);
			$this->add_control(
				'icon_size',
				array(
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units'  => array(
						'px',
						'em',
					),
					'label'       => __( 'Icon Size', 'porto-functionality' ),
					'description' => __( 'Controls the size of icon.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} a:before, .single-product .elementor-element-{{ID}} .add_to_wishlist:before' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'label_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Label Typography', 'porto-functionality' ),
					'selector'  => '{{WRAPPER}} a, {{WRAPPER}} a span',
					'condition' => array(
						'show_label' => 'yes',
					),
				)
			);
			$this->add_control(
				'spacing',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Spacing', 'porto-functionality' ),
					'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a:before' => "margin-{$right}: {{SIZE}}{{UNIT}} !important;",
					),
					'condition'   => array(
						'show_label' => 'yes',
					),
				)
			);
			$this->add_control(
				'icon_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Icon Color', 'porto-functionality' ),
					'description' => __( 'Controls the color of wishlist icon.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a:before' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'icon_added_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Added Color', 'porto-functionality' ),
					'description' => __( 'Controls the added color of wishlist icon.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .yith-wcwl-wishlistaddedbrowse a:before, .elementor-element-{{ID}} .yith-wcwl-wishlistexistsbrowse a:before' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'label_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Label Color', 'porto-functionality' ),
					'description' => __( 'Controls the color of wishlist label.', 'porto-functionality' ),
					'selectors'   => array(
						'.single-product .product-summary-wrap .elementor-element-{{ID}} a, .single-product .product-summary-wrap .elementor-element-{{ID}} a span, .elementor-element-{{ID}} a, .elementor-element-{{ID}} a span:not(.yith-wcwl-tooltip)' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'show_label' => 'yes',
					),
				)
			);
			$this->add_control(
				'label_hover_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Label Hover Color', 'porto-functionality' ),
					'description' => __( 'Controls the hover color of label.', 'porto-functionality' ),
					'selectors'   => array(
						'.single-product .product-summary-wrap .elementor-element-{{ID}} a:hover, .single-product .product-summary-wrap .elementor-element-{{ID}} a:hover span, .elementor-element-{{ID}} a:hover, .elementor-element-{{ID}} a:hover span' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'show_label' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'bg_height',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Background Height', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units'  => array(
						'px',
						'em',
					),
					'description' => __( 'Controls the height of wishlist.', 'porto-functionality' ),
					'separator'   => 'before',
					'selectors'   => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a, .elementor-element-{{ID}} .yith-wcwl-add-to-wishlist span:not(.yith-wcwl-tooltip)' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'bg_width',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Background Width', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'description' => __( 'Controls the width of wishlist.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .wishlist-nolabel .yith-wcwl-add-to-wishlist a' => 'width: {{SIZE}}{{UNIT}} !important;',
					),
					'condition'   => array(
						'show_label' => '',
					),
				)
			);

			$this->add_control(
				'bg_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Background Color', 'porto-functionality' ),
					'description' => __( 'Controls the background color of label.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} a, .single-product .product-summary-wrap .elementor-element-{{ID}} a' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'bg_hover_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Background Hover Color', 'porto-functionality' ),
					'description' => __( 'Controls the background hover color of label.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} a:hover, .single-product .product-summary-wrap .elementor-element-{{ID}} a:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'wl_padding',
				array(
					'label'              => esc_html__( 'Padding', 'porto-functionality' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => array( 'px', 'em', '%', 'rem' ),
					'allowed_dimensions' => 'horizontal',
					'placeholder'        => array(
						'top'    => 'auto',
						'right'  => '',
						'bottom' => 'auto',
						'left'   => '',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} a' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					),
					'condition'   => array(
						'show_label' => 'yes',
					),
				)
			);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			$settings['page_builder'] = 'elementor';
			echo PortoCustomProduct::get_instance()->shortcode_single_product_wishlist( $settings );
		}
	}
}
