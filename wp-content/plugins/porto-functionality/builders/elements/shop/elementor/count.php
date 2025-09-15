<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Shop Builder - Count Per Page Widget
 *
 * @since 6.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

class Porto_Elementor_SB_Count_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_sb_count';
	}

	public function get_title() {
		return __( 'Pagination (Count Per Page)', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-sb' );
	}

	public function get_keywords() {
		return array( 'page', 'shop products count', 'woocommerce', 'count', 'products', 'pagination', 'loop after' );
	}

	public function get_icon() {
		return 'Simple-Line-Icons-pencil porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/shop-builder-elements/';
	}

	protected function register_controls() {
		$right = is_rtl() ? 'left' : 'right';
		$left = is_rtl() ? 'right' : 'left';

		$this->start_controls_section(
			'section_count',
			array(
				'label' => esc_html( 'Count Per Page', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_control(
				'notice_skin',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: starting and ending bold tags */
					'raw'             => sprintf( esc_html__( 'You can set these values using %3$s%1$sWooCommerce -> Product Archives -> Products per Page%2$s%4$s in Theme Options. This displays pagination together when pagination is disabled in Type Builder Archives element.', 'porto-functionality' ), '<b>', '</b>', '<a target="_blank" href="' . porto_get_theme_option_url( 'category-item' ) . '" class="porto-text-underline">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control (
				'pagination_hide',
				array(
					'label'     => esc_html__( 'Pagination Visibility', 'porto-functionality' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						''             => __( 'Default', 'porto-functionality' ),
						'inline-block' => __( 'Show', 'porto-functionality' ),
						'none'         => __( 'Hide', 'porto-functionality' ),
					),
					'default'   => '',
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers' => 'vertical-align: top; display: {{VALUE}} !important;',
						'.elementor-element-{{ID}} li a,.elementor-element-{{ID}} li span' => 'margin-bottom: 0;',
					),
				)
			);

			$this->add_control(
				'pg_align',
				array(
					'label' => esc_html__( 'Alignment', 'porto-functionality' ),
					'type'  => Controls_Manager::SELECT,
					'options' => array(
						'justify' => esc_html__( 'Justify', 'porto-functionality' ),
						'left'    => esc_html__( 'Left', 'porto-functionality' ),
						'center'  => esc_html__( 'Center', 'porto-functionality' ),
						'right'   => esc_html__( 'Right', 'porto-functionality' ),
					),
					'prefix_class' => 'pg-',
					'description' => esc_html__( 'Controls the alignment between Pagination and count per page select box.', 'porto-functionality' ),
				)
			);

			$this->add_control(
				'pag_space',
				array(
					'label'       => esc_html__( 'Left Space', 'porto-functionality' ),
					'description' => esc_html__( 'Spacing Between Pagination and Select Box.', 'porto-functionality' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => -10,
							'max'  => 30,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => -1,
							'max'  => 3,
						),
					),
					'size_units'  => array(
						'px',
						'rem',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} ul.page-numbers' => "margin-{$left}: {{SIZE}}{{UNIT}};",
					),
					'condition'  => array(
						'pagination_hide!' => 'none',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_label',
			array(
				'label' => esc_html( 'Label', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'label_hide',
				array(
					'label'     => esc_html__( 'Label Visibility', 'porto-functionality' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						''     => __( 'Show', 'porto-functionality' ),
						'none' => __( 'Hide', 'porto-functionality' ),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} label' => 'display: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'label_color',
				array(
					'label'       => esc_html__( 'Label Color', 'porto-functionality' ),
					'description' => esc_html__( 'Controls color of label.', 'porto-functionality' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} label' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'label_hide' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'label_typography',
					'label'     => esc_html__( 'Label Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} label',
					'condition' => array(
						'label_hide' => '',
					),
				)
			);

			$this->add_control(
				'spacing',
				array(
					'label'       => esc_html__( 'Spacing(px)', 'porto-functionality' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} label' => "margin-{$right}: {{SIZE}}px",
					),
					'condition'   => array(
						'label_hide' => '',
					),
					'description' => esc_html__( 'Controls spacing between label and select box.', 'porto-functionality' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_select',
			array(
				'label' => esc_html( 'Select', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control (
				'select_hide',
				array(
					'label'     => esc_html__( 'Hide Select Box', 'porto-functionality' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						''     => __( 'Show', 'porto-functionality' ),
						'none' => __( 'Hide', 'porto-functionality' ),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-viewing select' => 'display: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'select_color',
				array(
					'label'       => esc_html__( 'Select box Color', 'porto-functionality' ),
					'description' => esc_html__( 'Controls color of select box.', 'porto-functionality' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} select' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'select_hide' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'select_typography',
					'label'     => esc_html__( 'Select box Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} select',
					'condition' => array(
						'select_hide' => '',
					),
				)
			);

			$this->add_responsive_control(
				'select_padding',
				array(
					'label'       => esc_html__( 'Select box Padding', 'porto-functionality' ),
					'description' => esc_html__( 'Controls padding of select box.', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'   => array(
						'select_hide' => '',
					),
				)
			);

			$this->add_control(
				'select_height',
				array(
					'label'     => esc_html__( 'Select Height(px)', 'porto-functionality' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} select' => "height: {{SIZE}}px",
					),
					'condition' => array(
						'select_hide' => '',
					),
					'description' => esc_html__( 'Controls height of Select Box.', 'porto-functionality' ),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		include PORTO_BUILDERS_PATH . '/elements/shop/wpb/count.php';
	}
}
