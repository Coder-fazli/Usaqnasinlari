<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Header Builder Compare Icon widget
 *
 * @since 6.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_HB_Compare_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_compare';
	}

	public function get_title() {
		return __( 'Porto Compare', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'compare', 'icon', 'yith' );
	}

	public function get_icon() {
		return 'porto-icon-compare-link porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/porto-compare-icon-element/';
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_hb_compare',
			array(
				'label' => __( 'Compare Icon', 'porto-functionality' ),
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
					'value'   => '',
					'library' => '',
				),
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Icon Size', 'porto-functionality' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 72,
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
					'#header .elementor-element-{{ID}} .yith-woocompare-open' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_label',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Show Label', 'porto-functionality' ),
				'description' => __( 'Show/Hide the compare label.', 'porto-functionality' ),
			)
		);

		$this->add_control(
			'compare_label',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Label', 'porto-functionality' ),
				'placeholder' => __( 'Compare', 'porto-functionality' ),
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'label_font',
				'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'label'     => __( 'Label Typography', 'porto-functionality' ),
				'selector'  => '.elementor-element-{{ID}} span.hicon-label',
				'condition' => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_space',
			array(
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 5,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 5,
					),
				),
				'size_units'  => array(
					'px',
					'rem',
					'em',
				),
				'label'       => __( 'Between Spacing', 'porto-functionality' ),
				'description' => __( 'Controls the spacing between icon and label.', 'porto-functionality' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .compare-icon' => "margin-{$right}: {{SIZE}}{{UNIT}}",
				),
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'porto-functionality' ),
				'separator' => 'before',
				'selectors' => array(
					'#header .elementor-element-{{ID}} .yith-woocompare-open' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sticky_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color On Sticky Header', 'porto-functionality' ),
				'selectors' => array(
					'#header.sticky-header .elementor-element-{{ID}} .yith-woocompare-open' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Hover Color', 'porto-functionality' ),
				'separator' => 'before',
				'selectors' => array(
					'#header .elementor-element-{{ID}} .yith-woocompare-open:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sticky_hover_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Hover Color On Sticky Header', 'porto-functionality' ),
				'selectors' => array(
					'#header.sticky-header .elementor-element-{{ID}} .yith-woocompare-open:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Badge Color', 'porto-functionality' ),
				'separator' => 'before',
				'selectors' => array(
					'.elementor-element-{{ID}} .compare-count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Badge Background Color', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .compare-count' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( class_exists( 'Woocommerce' ) && defined( 'YITH_WOOCOMPARE' ) && class_exists( 'YITH_Woocompare' ) ) {
			global $yith_woocompare;
			$icon_cl = 'porto-icon-compare-link';
			if ( isset( $settings['icon_cl'] ) && ! empty( $settings['icon_cl']['value'] ) ) {
				if ( isset( $settings['icon_cl']['library'] ) && ! empty( $settings['icon_cl']['value']['id'] ) ) {
					$icon_cl = $settings['icon_cl']['value']['id'];
				} else {
					$icon_cl = $settings['icon_cl']['value'];
				}
			}
			$compare_count = isset( $yith_woocompare->obj->products_list ) ? sizeof( $yith_woocompare->obj->products_list ) : 0;
			echo '<a href="#" title="' . esc_attr__( 'Compare', 'porto-functionality' ) . '" class="yith-woocompare-open"><span class="compare-icon"><i class="' . esc_attr( $icon_cl ) . '"></i><span class="compare-count">' . intval( $compare_count ) . '</span></span>' . ( 'yes' == $settings['show_label'] ? '<span class="hicon-label">' . ( $settings['compare_label'] ? $settings['compare_label'] : esc_html__( 'Compare', 'porto-functionality' ) ) . '</span>' : '' ) . '</a>';
		}
	}
}
