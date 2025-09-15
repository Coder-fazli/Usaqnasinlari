<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Linked Products Widget
 *
 * Porto Elementor widget to display Linked products on the single product page when using custom product layout
 *
 * @since 2.3.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Linked_Widget extends Porto_Elementor_Posts_Grid_Widget {

	public function get_name() {
		return 'porto_cp_linked';
	}

	public function get_title() {
		return __( 'Linked Products', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_keywords() {
		return array( 'product', 'linked', 'related', 'upsell', 'cross sell', 'crosssell', 'compare', 'attribute group' );
	}

	protected function register_controls() {
		parent::register_controls();
		$this->start_controls_section(
			'section_heading',
			array(
				'label' => __( 'Heading', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'show_heading',
				array(
					'type'    => Controls_Manager::SWITCHER,
					'label'   => __( 'Show Title', 'porto-functionality' ),
				)
			);

			$this->add_control(
				'heading_text',
				array(
					'type'      => Controls_Manager::TEXT,
					'label'     => __( 'Heading Content', 'porto-functionality' ),
					'default'   => __( 'Related Products', 'porto-functionality' ),
					'condition' => array(
						'show_heading' => 'yes',
					)
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'hd_typography',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Heading Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} .sp-linked-heading',
					'condition' => array(
						'show_heading' => 'yes',
					)
				)
			);

			$this->add_control(
				'heading_align',
				array(
					'label'     => esc_html__( 'Alignment', 'porto-functionality' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'porto-functionality' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'porto-functionality' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'porto-functionality' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'condition' => array(
						'show_heading' => 'yes',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .sp-linked-heading' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'hd_space',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Padding Bottom', 'porto-functionality' ),
					'qa_selector' => '.sp-linked-heading',
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 72,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
						'em' => array(
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
					'selectors'   => array(
						'.elementor-element-{{ID}} .sp-linked-heading' => "padding-bottom: {{SIZE}}{{UNIT}};",
					),
					'condition'   => array(
						'show_heading' => 'yes',
					)
				)
			);
			
			$this->add_control(
				'hide_sp',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => __( 'Hide Border', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .sp-linked-heading' => "border: none;",
					),
					'condition' => array(
						'show_heading' => 'yes',
					)
				)
			);

			$this->add_control(
				'separator_space',
				array(
					'type'  => Controls_Manager::SLIDER,
					'label' => __( 'Margin Bottom', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 72,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
						'em' => array(
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
					'selectors'   => array(
						'.elementor-element-{{ID}} .sp-linked-heading' => "margin-bottom: {{SIZE}}{{UNIT}};",
					),
					'condition' => array(
						'show_heading' => 'yes',
					)
				)
			);

		$this->end_controls_section();

		$this->remove_control( 'source' );
		$this->update_control(
			'post_type',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Linked Type', 'porto-functionality' ),
				'description' => esc_html__( 'Please select a product type of products to display related, upsell, cross-sell or compare products', 'porto-functionality' ),
				'default'     => 'related',
				'options'     => array(
					'related'    => esc_html__( 'Related Products', 'porto-functionality' ),
					'upsell'     => esc_html__( 'Upsells Products', 'porto-functionality' ),
					'cross_sell' => esc_html__( 'Cross Sell Products', 'porto-functionality' ),
					'compare'    => esc_html__( 'Compare Products', 'porto-functionality' ),
				),
				'condition'   => array(),
			)
		);
		$this->remove_control( 'tax' );
		$this->remove_control( 'post_ids' );
		$this->remove_control( 'terms' );
		$this->remove_control( 'post_terms' );
		$this->remove_control( 'post_tax' );
		$this->update_control(
			'orderby',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Order by', 'porto-functionality' ),
				'options'     => array_flip( array_slice( porto_vc_woo_order_by(), 1 ) ),
				'description' => __( 'Price, Popularity and Rating values only work for product post type.', 'porto-functionality' ),
				'condition'   => array(),
			)
		);
		$this->remove_control( 'pagination_style' );
		$this->remove_control( 'category_filter' );

		$this->update_control(
			'view',
			array(
				'label'     => __( 'View', 'porto-functionality' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''         => __( 'Grid', 'porto-functionality' ),
					'creative' => __( 'Grid - Creative', 'porto-functionality' ),
					'masonry'  => __( 'Masonry', 'porto-functionality' ),
					'slider'   => __( 'Slider', 'porto-functionality' ),
				),
				'condition' => array(
					'post_type!' => 'compare'
				)
			)
		);

		$this->start_controls_section(
			'section_attr_source',
			array(
				'label'     => __( 'Attributes', 'porto-functionality' ),
				'condition' => array(
					'post_type' => 'compare',
				)
			)
		);

			$this->add_control(
				'compare_desc',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'Please select the compare products in %1$sLinked Products%2$s tab of "Edit Product" on Dashboard > Products > All Products.', 'porto-functionality' ), '<b>', '</b>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				),
			);

			$this->add_control(
				'attr_desc',
				array(
					'label' => esc_html__( 'Please add attributes to the Compare Products', 'porto-functionality' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_control(
				'show_attributes',
				array(
					'type'  => Controls_Manager::SWITCHER,
					'label' => __( 'Show Attributes', 'porto-functionality' ),
				)
			);

			$repeater = new Elementor\Repeater();

			$repeater->add_control(
				'table_title',
				array(
					'type'  => Controls_Manager::TEXT,
					'label' => __( 'Attribute Group Title', 'porto-functionality' ),
				)
			);

			$repeater->add_control(
				'attr_include',
				array(
					'type'        => Controls_Manager::SELECT2,
					'label'       => __( 'Select Attributes', 'porto-functionality' ),
					'options'     => PortoShortcodesClass::get_woo_attributes(),
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'table_title!' => '',
					),
				)
			);

			$presets = array(
				array(
					'label' => 'Attribute Group',
				),
			);

			$this->add_control(
				'attr_group',
				array(
					'label'     => esc_html__( 'Attributes', 'porto-functionality' ),
					'type'      => Controls_Manager::REPEATER,
					'fields'    => $repeater->get_controls(),
					'default'   => $presets,
					'condition' => array(
						'show_attributes' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		echo PortoCustomProduct::get_instance()->shortcode_single_product_linked( $atts, 'elementor' );
	}
}
