<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Product Attribute
 *
 * @since 3.1.0
 */
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
class Porto_Elementor_CP_Attribute_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'porto_cp_attribute';
	}

	public function get_title() {
		return __( 'Product Attribute Table', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'color', 'size', 'width', 'height', 'length', 'weight' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents porto-elementor-widget-icon';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			echo PortoCustomProduct::get_instance()->shortcode_single_product_attribute( $settings, 'elementor' );
		}
	}

	protected function register_controls() {
		$all_attributes = PortoShortcodesClass::get_woo_attributes();

		$this->start_controls_section(
			'section_attr_source',
			array(
				'label' => __( 'Attributes', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'attr_source',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Attributes', 'porto-functionality' ),
					'options' => array(
						'all'     => __( 'All', 'porto-functionality' ),
						'include' => __( 'Include', 'porto-functionality' ),
						'exclude' => __( 'Exclude', 'porto-functionality' ),
					),
					'default' => 'all',
				)
			);

			$this->add_control(
				'attr_include',
				array(
					'type'        => Controls_Manager::SELECT2,
					'label'       => __( 'Include Attributes', 'porto-functionality' ),
					'options'     => $all_attributes,
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'attr_source' => 'include',
					),
				)
			);

			$this->add_control(
				'attr_exclude',
				array(
					'type'        => Controls_Manager::SELECT2,
					'label'       => __( 'Exclude Attributes', 'porto-functionality' ),
					'options'     => $all_attributes,
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'attr_source' => 'exclude',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_title',
			array(
				'label' => __( 'Table Title', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'table_title',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => __( 'Table Text', 'porto-functionality' ),
					'description' => __( 'If you want to hide the title, please leave empty.', 'porto-functionality' ),
					'default'     => __( 'Product Attributes', 'porto-functionality' ),
					'qa_selector' => '.porto-attr-title',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'label'    => esc_html__( 'Typography', 'porto-functionality' ),
					'selector' => '.elementor-element-{{ID}} thead .porto-attr-title',
					'condition'   => array(
						'table_title!' => '',
					),
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'table_title!' => '',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} thead .porto-attr-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'title_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'table_title!' => '',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} thead .porto-attr-title' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'title_padding',
				array(
					'label'      => esc_html__( 'Padding', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} thead .porto-attr-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'table_title!' => '',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_attr',
			array(
				'label' => __( 'Term Fields', 'porto-functionality' ),
			)
		);

			$this->add_responsive_control(
				'column_count',
				array(
					'type'       => Controls_Manager::SELECT,
					'label'      => __( 'Number of Table columns', 'porto-functionality' ),
					'options'    => array(
						'1' => __( '1', 'porto-functionality' ),
						'2' => __( '2', 'porto-functionality' ),
						'3' => __( '3', 'porto-functionality' ),
						'4' => __( '4', 'porto-functionality' ),
						'5' => __( '5', 'porto-functionality' ),
						'6' => __( '6', 'porto-functionality' ),
					),
					'default'    => '1',
					'selectors'  => array(
						'.elementor-element-{{ID}} tbody' => '--porto-sp-table-cols: {{SIZE}};',
					),
					'qa_selector' => 'tbody',
				)
			);
			
			$this->add_responsive_control(
				'column_gap',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Column Gap', 'porto-functionality' ),
					'size_units' => array(
						'px',
						'rem',
					),
					'condition'  => array(
						'column_count!' => '1',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} tbody' => 'column-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'vertical_space',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Vertical Spacing', 'porto-functionality' ),
					'size_units' => array(
						'px',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} tbody' => '--porto-table-vs: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'col_spacing',
				array(
					'label'       => esc_html__( 'Field Padding', 'porto-functionality' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} tbody .porto-attr-data' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					),
					'separator'   => 'before',
					'qa_selector' => '.porto-attr-data:first-child',
				)
			);

			$this->add_control(
				'disable_col_border',
				array(
					'type'     => Controls_Manager::SWITCHER,
					'label'     => __( 'Hide Border', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} tbody .porto-attr-data' => 'border-bottom: none',
					),
				)
			);

			$this->add_control(
				'col_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'disable_col_border' => '',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} tbody .porto-attr-data' => 'border-bottom-color: {{VALUE}};',
					),
				)
			);
			
		$this->start_controls_tabs( 'tabs_attr_style' );
		
			$this->start_controls_tab(
				'tab_attr_name',
				array(
					'label' => esc_html__( 'Name', 'porto-functionality' ),
				)
			);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'name_typography',
						'label'    => esc_html__( 'Typography', 'porto-functionality' ),
						'selector' => '.elementor-element-{{ID}} .porto-attr-name',
					)
				);

				$this->add_control(
					'name_color',
					array(
						'label'     => esc_html__( 'Color', 'porto-functionality' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .porto-attr-name' => 'color: {{VALUE}};',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_attr_term',
				array(
					'label' => esc_html__( 'Term', 'porto-functionality' ),
				)
			);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'term_typography',
						'label'    => esc_html__( 'Typography', 'porto-functionality' ),
						'selector' => '.elementor-element-{{ID}} .porto-attr-term',
					)
				);

				$this->add_control(
					'term_color',
					array(
						'label'     => esc_html__( 'Color', 'porto-functionality' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .porto-attr-term' => 'color: {{VALUE}};',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();
    }
}