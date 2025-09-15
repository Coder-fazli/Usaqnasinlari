<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Add to Cart Widget
 *
 * Porto Elementor widget to display "add to cart" button on the single product page when using custom product layout
 *
 * @since 1.7.1
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Add_to_cart_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_cp_add_to_cart';
	}

	public function get_title() {
		return __( 'Product Add To Cart', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'product', 'cart', 'add_to_cart' );
	}

	public function get_icon() {
		return 'eicon-product-add-to-cart porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_cp_quantity',
			array(
				'label'       => esc_html__( 'Quantity', 'porto-functionality' ),
				'tab'         => Controls_Manager::TAB_STYLE,
				'qa_selector' => '.quantity',
			)
		);
			$this->add_control(
				'quantity_margin',
				array(
					'label'      => esc_html__( 'Margin', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-summary-wrap .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->start_controls_tabs( 'tabs_quantity' );
				$this->start_controls_tab(
					'tab_minus',
					array(
						'label' => esc_html__( 'Minus', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'minus_width',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Width', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus' => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'minus_height',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Height', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus' => 'height: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'minus_border',
						array(
							'label'      => esc_html__( 'Border Width', 'porto-functionality' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'minus_br_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Border Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus' => 'border-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'minus_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus:not(:hover)' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'minus_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .minus' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_input',
					array(
						'label' => esc_html__( 'Input', 'porto-functionality' ),
					)
				);
					$this->add_group_control(
						Elementor\Group_Control_Typography::get_type(),
						array(
							'name'     => 'qty_font',
							'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
							'label'    => __( 'Typography', 'porto-functionality' ),
							'selector' => '.elementor-element-{{ID}} .product-summary-wrap .quantity .qty',
						)
					);
					$this->add_control(
						'qty_width',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Width', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'qty_height',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Height', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'qty_border',
						array(
							'label'      => esc_html__( 'Border Width', 'porto-functionality' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'qty_br_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Border Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty' => 'border-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'qty_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty:not(:hover)' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'qty_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .qty' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_plus',
					array(
						'label' => esc_html__( 'Plus', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'plus_width',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Width', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus' => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'plus_height',
						array(
							'type'       => Controls_Manager::SLIDER,
							'label'      => __( 'Height', 'porto-functionality' ),
							'range'      => array(
								'px' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 72,
								),
								'em' => array(
									'step' => 0.1,
									'min'  => 0,
									'max'  => 5,
								),
							),
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus' => 'height: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'plus_border',
						array(
							'label'      => esc_html__( 'Border Width', 'porto-functionality' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'plus_br_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Border Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus' => 'border-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'plus_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus:not(:hover)' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'plus_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-summary-wrap .quantity .plus' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();

			$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_cp_add_to_cart_price',
			array(
				'label' => esc_html__( 'Variation Price', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'price_font',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Typography', 'porto-functionality' ),
					'selector' => '.elementor-element-{{ID}} .woocommerce-variation-price .price',
				)
			);

			$this->add_control(
				'price_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Color', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-variation-price .price' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'price_margin',
				array(
					'label'      => esc_html__( 'Margin', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .woocommerce-variation-price .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display: block;',
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_cp_add_to_cart_form',
			array(
				'label'       => esc_html__( 'Cart Form', 'porto-functionality' ),
				'tab'         => Controls_Manager::TAB_STYLE,
				'qa_selector' => '.cart:not(.variations_form), .single_variation_wrap',
			)
		);

			$this->start_controls_tabs( 'cart_btn_color' );
				$this->start_controls_tab(
					'normal_clr',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'btn_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .single_add_to_cart_button' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'btn_bg_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
							),
						)
					);
				
				$this->end_controls_tab();
				$this->start_controls_tab(
					'hover_clr',
					array(
						'label' => esc_html__( 'Hover', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'btn_hover_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'btn_hover_bg_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .single_add_to_cart_button:hover' => 'background-color: {{VALUE}};',
							),
						)
					);
				
				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->start_controls_tabs( 'view_cart_btn_color' );
				$this->start_controls_tab(
					'view_normal_clr',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'view_btn_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'View Cart Button Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .view-cart-btn' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'view_btn_bg_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .view-cart-btn' => 'background-color: {{VALUE}};',
							),
						)
					);
				
				$this->end_controls_tab();
				$this->start_controls_tab(
					'view_hover_clr',
					array(
						'label' => esc_html__( 'Hover', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'view_btn_hover_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'View Cart Button Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .view-cart-btn:hover' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'view_btn_hover_bg_clr',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .view-cart-btn:hover' => 'background-color: {{VALUE}};',
							),
						)
					);
				
				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'cart_btn_sz',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Typography', 'porto-functionality' ),
					'selector' => '.elementor-element-{{ID}} .single_add_to_cart_button, .elementor-element-{{ID}} .view-cart-btn',
				)
			);
			$this->add_responsive_control(
				'cart_btn_pd',
				array(
					'label'      => esc_html__( 'Padding', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .single_add_to_cart_button, .elementor-element-{{ID}} .view-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_responsive_control(
				'cart_btn_hd',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'The Height of Buttons', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'em'  => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .single_add_to_cart_button, .elementor-element-{{ID}} .view-cart-btn' => 'height: {{SIZE}}{{UNIT}} !important;line-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'form_btn_layout',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Button Design', 'porto-functionality' ),
					'options'     => array(
						''        => '',
						'1'       => __( 'Full Width', 'porto-functionality' ),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} form .button' => 'flex: {{VALUE}};min-width: 150px;',
					),
					'separator'   => 'before',
				)
			);
			$this->add_control(
				'form_margin',
				array(
					'label'      => esc_html__( 'Margin', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .cart:not(.variations_form), .elementor-element-{{ID}} .single_variation_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'form_padding',
				array(
					'label'      => esc_html__( 'Padding', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .cart:not(.variations_form), .elementor-element-{{ID}} .single_variation_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'form_border',
				array(
					'label'      => esc_html__( 'Border Width', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .cart:not(.variations_form), .elementor-element-{{ID}} .single_variation_wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};border-style: solid;',
					),
				)
			);
			$this->add_control(
				'form_br_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Border Color', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .cart:not(.variations_form), .elementor-element-{{ID}} .single_variation_wrap' => 'border-color: {{VALUE}};',
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_cp_add_to_cart_variation',
			array(
				'label' => esc_html__( 'Variation', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
			$this->add_control(
				'notice_skin',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'You can change the global value in %1$sPorto / Theme Options / WooCommerce / Product Swatch Mode%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product_variation_display_mode' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'variation_margin',
				array(
					'label'      => esc_html__( 'Margin', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-summary-wrap .variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'variation_tr',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'View Mode', 'porto-functionality' ),
					'options'     => array(
						''             => __( 'Stacked', 'porto-functionality' ),
						'block'        => __( 'Block', 'porto-functionality' ),
						'inline-block' => __( 'Inline', 'porto-functionality' ),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .product-summary-wrap .variations tr' => 'display: {{VALUE}};',
					),
					'qa_selector' => '.variations tr:first-child',
				)
			);
			$this->add_control(
				'variation_label_block',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => __( 'Label Block', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .variations .label, .elementor-element-{{ID}} .variations td.value' => 'display: block;',
					),
					'condition'   => array(
						'variation_tr' => array( '', 'block' ),
					),
				)
			);
			$this->add_control(
				'variation_tr_margin',
				array(
					'label'       => esc_html__( 'Individual Margin', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .product-summary-wrap .variations tr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'   => array(
						'variation_tr!' => ''
					),
					'qa_selector' => '.variations tr:nth-child(2)',
				)
			);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			$settings['page_builder'] = 'elementor';
			echo PortoCustomProduct::get_instance()->shortcode_single_product_add_to_cart( $settings );
		}
	}
}
