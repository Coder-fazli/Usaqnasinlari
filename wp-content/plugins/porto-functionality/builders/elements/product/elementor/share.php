<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Hooks Widget
 *
 * Porto Elementor widget to run default hooks on the single product page when using custom product layout
 *
 * @since 3.3.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Share_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_cp_share';
	}

	public function get_title() {
		return __( 'Product Share', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'product', 'action', 'share', 'social' );
	}

	public function get_icon() {
		return 'eicon-share porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}


	protected function register_controls() {

		$this->start_controls_section(
			'section_cp_social_icons',
			array(
				'label' => __( 'Social Icons', 'porto-functionality' ),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Icon Font Size', 'porto-functionality' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 6,
						'max'  => 50,
					),
					'em' => array(
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 5,
					),
				),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .share-links a' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Icon Width and Height', 'porto-functionality' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'em' => array(
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 10,
					),
				),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );
			$this->start_controls_tab(
				'tab_icon_normal',
				array(
					'label' => __( 'Normal', 'porto-functionality' ),
				)
			);
				$this->add_control(
					'icon_color',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Color', 'porto-functionality' ),
						'selectors' => array(
							'.elementor-element-{{ID}} .share-links a:not(:hover)' => 'color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'icon_color_bg',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Background Color', 'porto-functionality' ),
						'selectors' => array(
							'.elementor-element-{{ID}} .share-links a:not(:hover)' => 'background: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'icon_color_border',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Border Color', 'porto-functionality' ),
						'selectors' => array(
							'.elementor-element-{{ID}} a' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'icon_border_style!' => '',
						),
					)
				);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_icon_hover',
				array(
					'label' => __( 'Hover', 'porto-functionality' ),
				)
			);
				$this->add_control(
					'icon_hover_color',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Hover Color', 'porto-functionality' ),
						'selectors' => array(
							'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
						),
					)
				);
				$this->add_control(
					'icon_hover_color_bg',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Hover Background Color', 'porto-functionality' ),
						'selectors' => array(
							'.elementor-element-{{ID}} a:hover' => 'background: {{VALUE}};',
						),
					)
				);
				$this->add_control(
					'icon_hover_color_border',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => __( 'Hover Border Color', 'porto-functionality' ),
						'selectors' => array(
							'.elementor-element-{{ID}} a:hover' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'icon_border_style!' => '',
						),
					)
				);
			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'icon_border_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Icon Border Style', 'porto-functionality' ),
				'options'   => array(
					''       => __( 'None', 'porto-functionality' ),
					'solid'  => __( 'Solid', 'porto-functionality' ),
					'dashed' => __( 'Dashed', 'porto-functionality' ),
					'dotted' => __( 'Dotted', 'porto-functionality' ),
					'double' => __( 'Double', 'porto-functionality' ),
					'inset'  => __( 'Inset', 'porto-functionality' ),
					'outset' => __( 'Outset', 'porto-functionality' ),
				),
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} a' => 'border-style: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_border_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Icon Border Width', 'porto-functionality' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 10,
					),
					'em' => array(
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 4,
					),
				),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} a' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'icon_border_style!' => '',
				),
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Border Radius', 'porto-functionality' ),
				'range'     => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'selectors' => array(
					'.elementor-element-{{ID}} a' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after',
			)
		);

		$right = is_rtl() ? 'left' : 'right';

		$this->add_control(
			'icon_spacing',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Spacing between icons', 'porto-functionality' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 10,
					),
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} a' => 'margin-' . $right . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			$settings['page_builder'] = 'elementor';
			echo PortoCustomProduct::get_instance()->shortcode_single_product_share( $settings, 'elementor' );
		}
	}
}
