<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Header Builder Mini cart widget
 *
 * @since 2.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_HB_Mini_Cart_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_mini_cart';
	}

	public function get_title() {
		return __( 'Porto Mini Cart', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'minicart', 'shopping', 'cart', 'woocommerce' );
	}

	public function get_icon() {
		return 'porto-icon-shopping-cart porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/porto-mini-cart-element/';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_hb_mini_cart',
			array(
				'label' => __( 'Mini Cart', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'description_cart',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'Please see %1$s%2$sTheme Options -> Header -> WooCommerce%3$s%4$s panel.', 'porto-functionality' ), '<b>', '<a href="' . porto_get_theme_option_url( 'desc_info_header_woocommerce_notice' ) . '" target="_blank">', '</b>', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'type',
				array(
					'type'        => 'image_choose',
					'label'       => __( 'Mini Cart Type', 'porto-functionality' ),
					'description' => __( 'Controls the cart type.', 'porto-functionality' ),
					'options'     => array(
						'simple'             => 'cart/cart-simple.svg',
						'minicart-arrow-alt' => 'cart/cart-arrow-alt.svg',
						'minicart-inline'    => 'cart/cart-text.svg',
						'minicart-text'      => 'cart/cart-icon-text.svg',
					),
					'default'     => 'minicart-arrow-alt',
				)
			);

			$this->add_control(
				'content_type',
				array(
					'type'    => 'image_choose',
					'label'   => __( 'Content Type', 'porto-functionality' ),
					'options' => array(
						''          => 'cart/cart-popup.svg',
						'offcanvas' => 'cart/cart-offcanvas.svg',
					),
					'default' => '',
				)
			);

			$this->add_control(
				'icon_cl',
				array(
					'type'                   => Controls_Manager::ICONS,
					'label'                  => __( 'Icon', 'porto-functionality' ),
					'fa4compatibility'       => 'icon',
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
					'default'                => array(
						'value'   => 'porto-icon-cart-thick',
						'library' => 'porto-icons',
					),
					'description'            => __( 'Controls the cart icon.', 'porto-functionality' ),
					'condition'              => array(
						'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
					)
				)
			);

			$this->add_responsive_control(
				'icon_size',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Icon Size', 'porto-functionality' ),
					'separator'   => 'before',
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 12,
							'max'  => 72,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 5,
						),
					),
					'size_units'  => array(
						'px',
						'em',
					),
					'default'     => array(
						'size' => '26',
						'unit' => 'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} #mini-cart .cart-head, .elementor-element-{{ID}} #mini-cart.minicart-inline .cart-head' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
					),
					'description' => __( 'Controls the size of the mini cart icon.', 'porto-functionality' ),
				)
			);

			$this->start_controls_tabs( 'tabs_icon_color' );

				$this->start_controls_tab(
					'tab_icon_color',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);

					$this->add_control(
						'icon_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Icon Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} #mini-cart .minicart-icon, .elementor-element-{{ID}} #mini-cart.minicart-arrow-alt .cart-head:after' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
							),
							'description' => __( 'Controls the color of the mini cart icon.', 'porto-functionality' ),
						)
					);

					$this->add_control(
						'hover_icon_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Icon Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} #mini-cart:hover .minicart-icon, .elementor-element-{{ID}} #mini-cart.minicart-arrow-alt:hover .cart-head:after' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
							),
						)
					);

					$this->add_control(
						'icon_border_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Content Border Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} #mini-cart .cart-popup' => 'border: 1px solid {{VALUE}};',
								'.elementor-element-{{ID}} #mini-cart .cart-icon:after, .elementor-element-{{ID}} #mini-cart.minicart-text .cart-head:after' => 'border-color: {{VALUE}};',
							),
							'description' => __( 'Controls the border color of cart content.', 'porto-functionality' ),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sticky_icon_color',
					array(
						'label' => esc_html__( 'On Sticky', 'porto-functionality' ),
					)
				);

					$this->add_control(
						'sticky_icon_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Icon Color', 'porto-functionality' ),
							'selectors'   => array(
								'.sticky-header .elementor-element-{{ID}} #mini-cart .minicart-icon, .sticky-header .elementor-element-{{ID}} #mini-cart.minicart-arrow-alt .cart-head:after' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
							),
							'description' => __( 'Controls the color of the mini cart icon on sticky header.', 'porto-functionality' ),
						)
					);

					$this->add_control(
						'sticky_hover_icon_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Icon Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'.sticky-header .elementor-element-{{ID}} #mini-cart:hover .minicart-icon, .sticky-header .elementor-element-{{ID}} #mini-cart.minicart-arrow-alt:hover .cart-head:after' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
							),
						)
					);

					$this->add_control(
						'sticky_icon_border_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Content Border Color', 'porto-functionality' ),
							'selectors'   => array(
								'.sticky-header .elementor-element-{{ID}} #mini-cart .cart-popup' => 'border: 1px solid {{VALUE}};',
								'.sticky-header .elementor-element-{{ID}} #mini-cart .cart-icon:after,.sticky-header .elementor-element-{{ID}} #mini-cart.minicart-text .cart-head:after' => 'border-color: {{VALUE}};',
							),
							'description' => __( 'Controls the border color of cart popup on sticky header.', 'porto-functionality' ),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'icon_margin_left',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Margin Left', 'porto-functionality' ),
					'separator'  => 'before',
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
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
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-icon' => 'margin-' . ( is_rtl() ? 'right' : 'left' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
					),
				)
			);

			$this->add_control(
				'icon_margin_right',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Margin Right', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
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
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-icon' => 'margin-' . ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
					),
				)
			);

			$this->add_control(
				'text_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Text Color', 'porto-functionality' ),
					'separator' => 'before',
					'selectors' => array(
						'.elementor-element-{{ID}} #mini-cart .cart-subtotal' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'type' => array( 'minicart-inline', 'minicart-text' ),
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'text_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Text Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} #mini-cart .cart-subtotal',
					'condition' => array(
						'type' => array( 'minicart-inline', 'minicart-text' ),
					),
				)
			);

			$this->add_control(
				'price_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Price Color', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} #mini-cart .cart-price' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'type' => array( 'minicart-inline', 'minicart-text' ),
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'price_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Price Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} #mini-cart .cart-price',
					'condition' => array(
						'type' => array( 'minicart-inline', 'minicart-text' ),
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_mini_cart_badge',
			array(
				'label'     => __( 'Cart Badge', 'porto-functionality' ),
				'condition' => array(
					'type' => array( 'simple', 'minicart-arrow-alt', 'minicart-text' )
				),
			)
		);
			$this->add_control(
				'icon_item_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Font Size', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-items' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);


			$this->add_control(
				'icon_item_bg_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Badge Background Size', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-items' => '--porto-badge-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'icon_item_right',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Badge Right Position', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-items' => ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'icon_item_top',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Badge Top Position', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #mini-cart .cart-items' => 'top: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_badge_color' );

				$this->start_controls_tab(
					'tab_badge_color',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);

					$this->add_control(
						'icon_item_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} #mini-cart .cart-items, .elementor-element-{{ID}} #mini-cart .cart-items-text' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'icon_item_bg_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Background Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} #mini-cart .cart-items' => 'background-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_badge_sticky_color',
					array(
						'label' => esc_html__( 'On Sticky', 'porto-functionality' ),
					)
				);

					$this->add_control(
						'sticky_icon_item_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Color', 'porto-functionality' ),
							'selectors'   => array(
								'.sticky-header .elementor-element-{{ID}} #mini-cart .cart-items, .sticky-header .elementor-element-{{ID}} #mini-cart .cart-items-text' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'sticky_icon_item_bg_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Background Color', 'porto-functionality' ),
							'selectors'   => array(
								'.sticky-header .elementor-element-{{ID}} #mini-cart .cart-items' => 'background-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();
		
			$this->end_controls_tabs();

			$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( function_exists( 'porto_header_elements' ) ) {
			$custom_icon = '';
			if ( isset( $settings['icon_cl'] ) && ! empty( $settings['icon_cl']['value'] ) ) {
				if ( isset( $settings['icon_cl']['library'] ) && ! empty( $settings['icon_cl']['value']['id'] ) ) {
					$custom_icon = $settings['icon_cl']['value']['id'];
				} else {
					$custom_icon = $settings['icon_cl']['value'];
				}
			}
			global $porto_settings;
			if ( $settings['type'] ) {
				if ( isset( $porto_settings['minicart-type'] ) ) {
					$backup_type = $porto_settings['minicart-type'];
				}
				$porto_settings['minicart-type'] = $settings['type'];
			}
			if ( $custom_icon ) {
				if ( isset( $porto_settings['minicart-icon'] ) ) {
					$backup_icon = $porto_settings['minicart-icon'];
				}
				$porto_settings['minicart-icon'] = $custom_icon;
			}
			if ( isset( $settings['content_type'] ) ) {
				if ( isset( $porto_settings['minicart-content'] ) ) {
					$backup_content_type = $porto_settings['minicart-content'];
				}
				$porto_settings['minicart-content'] = $settings['content_type'];
			}

			porto_header_elements( array( (object) array( 'mini-cart' => '' ) ) );

			if ( isset( $backup_type ) ) {
				$porto_settings['minicart-type'] = $backup_type;
			}
			if ( isset( $backup_icon ) ) {
				$porto_settings['minicart-icon'] = $backup_icon;
			}
			if ( isset( $backup_content_type ) ) {
				$porto_settings['minicart-content'] = $backup_content_type;
			}
		}
	}

	/**
	 * Compatibility with Elementor Pro plugin
	 * 
	 * @since 7.1.14
	 */
	public function get_widget_css_config( $widget_name ) {
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) && 'yes' === get_option( 'elementor_use_mini_cart_template', '' ) ) {
			$widget_name = 'woocommerce';

			$direction = is_rtl() ? '-rtl' : '';

			$has_custom_breakpoints = $this->is_custom_breakpoints_widget();

			$file_name = 'widget-' . $widget_name . $direction . '.min.css';

			// The URL of the widget's external CSS file that is loaded in case that the CSS content is too large to be printed inline.
			$file_url = ElementorPro\Plugin::instance()->get_frontend_file_url( $file_name, $has_custom_breakpoints );

			// The local path of the widget's CSS file that is being read and saved in the DB when the CSS content should be printed inline.
			$file_path = ElementorPro\Plugin::instance()->get_frontend_file_path( $file_name, $has_custom_breakpoints );

			return [
				'key' => $widget_name,
				'version' => ELEMENTOR_PRO_VERSION,
				'file_path' => $file_path,
				'data' => [
					'file_url' => $file_url,
				],
			];
		}
		return parent::get_widget_css_config( $widget_name );
	}
}
