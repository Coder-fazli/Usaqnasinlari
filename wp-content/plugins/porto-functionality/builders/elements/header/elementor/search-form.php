<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Header Builder Search form widget
 *
 * @since 6.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

class Porto_Elementor_HB_Search_Form_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_search_form';
	}

	public function get_title() {
		return __( 'Porto Search Form', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'search', 'form', 'query' );
	}

	public function get_icon() {
		return 'Simple-Line-Icons-magnifier porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/porto-search-form-element/';
	}

	public function get_script_depends() {
		$depends = array();
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			$depends[] = 'jquery-selectric';
		}
		return $depends;
	}

	protected function register_controls() {

		global $porto_settings;

		$this->start_controls_section(
			'section_hb_search_form',
			array(
				'label' => __( 'Search Form Layout', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'description_search',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'Please see global search options in %1$sTheme Options -> Header -> Search Form%2$s.', 'porto-functionality' ), '<a target="_blank" href="' . porto_get_theme_option_url( 'show-searchform' ) . '"><b>', '</b></a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'placeholder_text',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => __( 'Placeholder Text', 'porto-functionality' ),
				)
			);

			$this->add_control(
				'search_layout',
				array(
					'type'        => 'image_choose',
					'label'       => __( 'Search Layout', 'porto-functionality' ),
					'description' => __( 'Controls the layout of the search forms.', 'porto-functionality' ),
					'options'     => array(
						'simple'   => 'search/search-popup1.svg',
						'large'    => 'search/search-popup2.svg',
						'reveal'   => 'search/search-reveal.svg',
						'advanced' => 'search/search-advanced.svg',
						'overlay'  => 'search/search-overlay.svg',
					),
					'default'     => ! empty( $porto_settings['search-layout'] ) ? $porto_settings['search-layout'] : 'simple',
				)
			);

			$this->add_control(
				'show_searchform_mobile',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Search Form on Mobile', 'porto-functionality' ),
					'description' => __( 'Display the full open-text field instead of an icon on mobile.', 'porto-functionality' ),
					'options'     => array(
						'show' => __( 'Show', 'porto-functionality' ),
						'hide' => __( 'Hide', 'porto-functionality' ),
					),
					'condition'   => array(
						'search_layout' => 'advanced',
					),
				)
			);

			$this->add_control(
				'search_type',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Search Content Type', 'porto-functionality' ),
					'description' => __( 'Controls the post types that displays in search results.', 'porto-functionality' ),
					'options'     => class_exists( 'WooCommerce' ) ? array(
						'all'       => __( 'All', 'porto-functionality' ),
						'post'      => __( 'Post', 'porto-functionality' ),
						'product'   => __( 'Product', 'porto-functionality' ),
						'portfolio' => __( 'Portfolio', 'porto-functionality' ),
						'event'     => __( 'Event', 'porto-functionality' ),
					) : array(
						'all'       => __( 'All', 'porto-functionality' ),
						'post'      => __( 'Post', 'porto-functionality' ),
						'portfolio' => __( 'Portfolio', 'porto-functionality' ),
						'event'     => __( 'Event', 'porto-functionality' ),
					),
					'default'     => ! empty( $porto_settings['search-type'] ) ? $porto_settings['search-type'] : 'all',
				)
			);

			$this->add_control(
				'category_filter',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show Category filter', 'porto-functionality' ),
					'condition'   => array(
						'search_type' => class_exists( 'WooCommerce' ) ? array( 'post', 'product' ) : 'post',
					),
				)
			);

			$this->add_control(
				'sub_cats',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show Sub Categories', 'porto-functionality' ),
					'condition'   => array(
						'category_filter' => 'yes',
						'search_type!'    => 'all',
					),
				)
			);

			$this->add_control(
				'category_filter_mobile',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show Categories on Mobile', 'porto-functionality' ),
					'condition'   => array(
						'category_filter' => 'yes',
						'search_type!'    => 'all',
					),
				)
			);

			$this->add_control(
				'popup_pos',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Popup Position', 'porto-functionality' ),
					'description' => __( 'This works for only "Popup 1" and "Popup 2" and "Form" search layout on mobile. You can change search layout using Porto -> Theme Options -> Header -> Search Form -> Search Layout.', 'porto-functionality' ),
					'options'     => array(
						''       => __( 'Default', 'porto-functionality' ),
						'left'   => __( 'Left', 'porto-functionality' ),
						'center' => __( 'Center', 'porto-functionality' ),
						'right'  => __( 'Right', 'porto-functionality' ),
					),
					'default'     => '',
					'condition'   => array(
						'search_layout!' => array( 'reveal', 'overlay' ),
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_search_form_toggle_style',
			array(
				'label' => __( 'Toggle Icon Style', 'porto-functionality' ),
			)
		);
			$this->add_responsive_control(
				'toggle_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Toggle Icon Size', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 40,
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
					'default'     => array(
						'unit' => 'px',
						'size' => '26',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .search-toggle' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'toggle_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Toggle Icon Color', 'porto-functionality' ),
					'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .search-toggle' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'sticky_toggle_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Toggle Icon Color In Sticky Header', 'porto-functionality' ),
					'selectors'   => array(
						'#header.sticky-header .elementor-element-{{ID}} .search-toggle' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'hover_toggle_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Hover Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .search-toggle:hover' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'sticky_hover_toggle_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Hover Color In Sticky Header', 'porto-functionality' ),
					'selectors'   => array(
						'#header.sticky-header .elementor-element-{{ID}} .search-toggle:hover' => 'color: {{VALUE}};',
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_search_form_style',
			array(
				'label' => __( 'Search Form Style', 'porto-functionality' ),
			)
		);
			$this->add_responsive_control(
				'searchform_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Search Form Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 800,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 80,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units' => array(
						'px',
						'em',
						'%',
						'custom',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} form.searchform' => 'width: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .searchform-popup' => 'width: 100%;',
					),
				)
			);

			$this->add_control(
				'search_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Search Form Max Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 800,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 80,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .searchform' => 'max-width: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .searchform-popup, #header .elementor-element-{{ID}} .search-layout-advanced' => 'width: 100%;',
						'#header .elementor-element-{{ID}} input' => 'max-width: 100%',
					),
				)
			);

			$this->add_responsive_control(
				'height',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Height', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 80,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 8,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .searchform-popup input, #header .elementor-element-{{ID}} .searchform-popup select, #header .elementor-element-{{ID}} .searchform-popup .selectric .label, #header .elementor-element-{{ID}} .searchform-popup .selectric, #header .elementor-element-{{ID}} .searchform-popup button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'border_width',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => __( 'Border Width (px)', 'porto-functionality' ),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .searchform' => 'border-width: {{SIZE}}{{UNIT}};',
						'#header .elementor-element-{{ID}} .ssm-advanced-search-layout .searchform' => 'border-width: {{SIZE}}{{UNIT}};',
						'#header .elementor-element-{{ID}} .search-popup .searchform-fields' => 'border-width: {{SIZE}}{{UNIT}};',
						'#header .elementor-element-{{ID}} .search-layout-overlay .selectric-cat, #header .elementor-element-{{ID}} .search-layout-overlay .text, #header .elementor-element-{{ID}} .search-layout-overlay .button-wrap' => 'border-width: {{SIZE}}{{UNIT}};',
						'#header .elementor-element-{{ID}} .search-layout-reveal input' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'border_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Border Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .searchform, #header .elementor-element-{{ID}} .searchform.search-layout-overlay .selectric-cat, #header .elementor-element-{{ID}} .searchform.search-layout-overlay .text, #header .elementor-element-{{ID}} .searchform.search-layout-overlay .button-wrap, #header .elementor-element-{{ID}} .search-popup .searchform-fields' => 'border-color: {{VALUE}};',
						'#header .elementor-element-{{ID}} .searchform-popup:not(.simple-search-layout) .search-toggle:after' => 'border-bottom-color: {{VALUE}};',
						'#header .elementor-element-{{ID}} .search-layout-reveal input' => 'border-bottom-color: {{VALUE}};',
					),
				)
			);

			$border_radius_selectors = array(
				'#header .elementor-element-{{ID}} .searchform-popup .searchform' => 'border-radius: {{SIZE}}{{UNIT}};',
				'#header .elementor-element-{{ID}} .search-popup .searchform-fields' => 'border-radius: {{SIZE}}{{UNIT}};',
				'#header .elementor-element-{{ID}} .searchform:not(.search-layout-reveal) input'  => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}};',
				'#header .elementor-element-{{ID}} .searchform.search-layout-reveal button'  => 'border-radius: 0;',
				'#header .elementor-element-{{ID}} .searchform button' => 'border-radius: 0 max( 0px, calc({{SIZE}}{{UNIT}} - 5px)) max( 0px, calc({{SIZE}}{{UNIT}} - 5px)) 0;',
			);
		if ( is_rtl() ) {
			$border_radius_selectors = array(
				'#header .elementor-element-{{ID}} .searchform-popup .searchform' => 'border-radius: {{SIZE}}{{UNIT}};',
				'#header .elementor-element-{{ID}} .search-popup .searchform-fields' => 'border-radius: {{SIZE}}{{UNIT}};',
				'#header .elementor-element-{{ID}} .searchform:not(.search-layout-reveal) input'  => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;',
				'#header .elementor-element-{{ID}} .searchform.search-layout-reveal button'  => 'border-radius: 0;',
				'#header .elementor-element-{{ID}} .searchform button' => 'border-radius: max( 0px, calc({{SIZE}}{{UNIT}} - 5px)) 0 0 max( 0px, calc({{SIZE}}{{UNIT}} - 5px));',
			);
		}
			$this->add_control(
				'border_radius',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Border Radius', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 40,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 4,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => $border_radius_selectors,
				)
			);

			$this->add_control(
				'form_bg_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Form Background Color', 'porto-functionality' ),
					'selectors'   => array(
						'.fixed-header #header .elementor-element-{{ID}} .searchform, #header .elementor-element-{{ID}} .searchform, .fixed-header #header.sticky-header .elementor-element-{{ID}} .searchform' => 'background-color: {{VALUE}};',
						'#header .elementor-element-{{ID}} .searchform-popup.simple-search-layout .search-toggle:after' => 'border-bottom-color: {{VALUE}};',
					),
					'separator'   => 'after',
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'input_text',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Input Typography', 'porto-functionality' ),
					'selector' => '#header .elementor-element-{{ID}} input',
				)
			);

			$this->add_control(
				'input_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Input Box Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} input' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'input_padding',
				array(
					'label'       => esc_html__( 'Input Padding', 'porto-functionality' ),
					'description' => esc_html__( 'Controls the padding of Input field.', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'rem',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .searchform input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'qa_selector' => '.searchform input[type="text"]',
				)
			);

			$this->add_responsive_control(
				'input_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Input Box Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 800,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 80,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .searchform-popup .text, #header .elementor-element-{{ID}} .searchform-popup input, #header .elementor-element-{{ID}} .searchform-popup .searchform-cats input' => 'width: {{SIZE}}{{UNIT}};',
						'#header .elementor-element-{{ID}} input' => 'max-width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'input_placeholder_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Input Box Placeholder Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} input::placeholder' => 'color: {{VALUE}};',
					),
					'separator' => 'after',
				)
			);

			$this->add_control(
				'close_icon_size',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Close Icon Size', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 40,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 4,
						),
					),
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .btn-close-search-form' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'qa_selector' => '.btn-close-search-form',
				)
			);
			$this->add_control(
				'close_icon_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Close Icon Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .btn-close-search-form' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'close_icon_bg_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Close Icon Background Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .btn-close-search-form' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'close_icon_padding',
				array(
					'label'       => esc_html__( 'Close Icon Padding', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .btn-close-search-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'   => 'after',
				)
			);
			$this->add_control(
				'form_icon_size',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Search Icon Size', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 40,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 4,
						),
					),
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} button' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'qa_selector' => 'button.btn-special',
				)
			);

			$this->add_control(
				'form_icon_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Search Icon Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} button' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_icon_bg_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Search Icon Background Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} button' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_icon_padding',
				array(
					'label'       => esc_html__( 'Search Icon Padding', 'porto-functionality' ),
					'description' => esc_html__( 'Controls the padding of search icon.', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .searchform-popup button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'divider_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Separator Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .searchform-popup input, #header .elementor-element-{{ID}} .searchform-popup select, #header .elementor-element-{{ID}} .searchform-popup .selectric, #header .elementor-element-{{ID}} .searchform-popup .selectric-hover .selectric, #header .elementor-element-{{ID}} .searchform-popup .selectric-open .selectric, #header .elementor-element-{{ID}} .searchform-popup .autocomplete-suggestions, #header .elementor-element-{{ID}} .searchform-popup .selectric-items' => 'border-color: {{VALUE}};',
					),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'category_inner_width',
				array(
					'type'      => Controls_Manager::NUMBER,
					'label'     => __( 'Separator Width (px)', 'porto-functionality' ),
					'min'       => 0,
					'max'       => 10,
					'selectors' => array(
						'#header .elementor-element-{{ID}} .searchform-popup .selectric, #header .elementor-element-{{ID}} .simple-popup input, #header .elementor-element-{{ID}} .searchform-popup select' => 'border-right-width: {{VALUE}}px;',
						'#header .elementor-element-{{ID}} .searchform-popup select, #header .elementor-element-{{ID}} .searchform-popup .selectric' => 'border-left-width: {{VALUE}}px;',
						'#header .elementor-element-{{ID}} .simple-popup select, #header .elementor-element-{{ID}} .simple-popup .selectric' => 'border-left-width: 0;',
					),
					'condition' => array(
						'category_filter' => 'yes',
					),
				)
			);

			$this->add_control(
				'category_width',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Category Width', 'porto-functionality' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 800,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units'  => array(
						'px',
						'%',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .selectric-cat, #header .elementor-element-{{ID}} select' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'category_filter' => 'yes',
					),
					'qa_selector' => '.selectric-cat',
				)
			);

			$this->add_control(
				'category_padding',
				array(
					'label'      => esc_html__( 'Category Padding', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .searchform-popup .selectric .label, #header .elementor-element-{{ID}} .searchform-popup select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'category_filter' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'category_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Category Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} .selectric-cat, #header .elementor-element-{{ID}} .searchform-popup select',
					'condition' => array(
						'category_filter' => 'yes',
					),
				)
			);

			$this->add_control(
				'category_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Category Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .selectric .label, #header .elementor-element-{{ID}} select' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'category_filter' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'form_box_shadow',
					'selector' => '#header .elementor-element-{{ID}} .searchform',
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( function_exists( 'porto_header_elements' ) ) {
			global $porto_settings;
			if ( isset( $porto_settings['search-cats'] ) ) {
				$backup_cat_filter        = $porto_settings['search-cats'];
				$backup_cat_filter_mobile = $porto_settings['search-cats-mobile'];
				$backup_cat_sub           = $porto_settings['search-sub-cats'];
			}
			$porto_settings['search-cats']        = ! empty( $settings['category_filter'] ) ? true : false;
			$porto_settings['search-cats-mobile'] = ! empty( $settings['category_filter_mobile'] ) ? true : false;
			$porto_settings['search-sub-cats']    = ! empty( $settings['sub_cats'] ) ? true : false;
			if ( ! empty( $settings['placeholder_text'] ) ) {
				if ( isset( $porto_settings['search-placeholder'] ) ) {
					$backup_placeholder = $porto_settings['search-placeholder'];
				}
				$porto_settings['search-placeholder'] = $settings['placeholder_text'];
			}

			if ( ! empty( $settings['search_layout'] ) ) {
				if ( isset( $porto_settings['search-layout'] ) ) {
					$backup_search_layout = $porto_settings['search-layout'];
				}
				$porto_settings['search-layout'] = $settings['search_layout'];
			}

			if ( ! empty( $settings['show_searchform_mobile'] ) ) {
				if ( isset( $porto_settings['show-searchform-mobile'] ) ) {
					$backup_show_mobile = $porto_settings['show-searchform-mobile'];
				}
				$porto_settings['show-searchform-mobile'] = ( 'show' == $settings['show_searchform_mobile'] ? true : false );
			}

			if ( ! empty( $settings['search_type'] ) ) {
				if ( isset( $porto_settings['search-type'] ) ) {
					$backup_search_content = $porto_settings['search-type'];
				}
				$porto_settings['search-type'] = $settings['search_type'];
			}

			$el_cls = '';
			if ( 'simple' == $porto_settings['search-layout'] ) {
				$el_cls .= 'simple-popup ';
			}
			if ( 'advanced' == $porto_settings['search-layout'] ) {
				$el_cls .= 'advanced-popup ';
			}
			if ( ! empty( $settings['popup_pos'] ) ) {
				if ( 'simple' == $porto_settings['search-layout'] || 'large' == $porto_settings['search-layout'] || 'advanced' == $porto_settings['search-layout'] ) {
					$el_cls .= 'search-popup-' . $settings['popup_pos'];
				}
			}

			porto_header_elements( array( (object) array( 'search-form' => '' ) ), $el_cls );
			if ( isset( $backup_cat_filter ) ) {
				$porto_settings['search-cats']        = $backup_cat_filter;
				$porto_settings['search-cats-mobile'] = $backup_cat_filter_mobile;
				$porto_settings['search-sub-cats']    = $backup_cat_sub;
			}
			if ( isset( $backup_placeholder ) ) {
				$porto_settings['search-placeholder'] = $backup_placeholder;
			}
			if ( isset( $backup_search_layout ) ) {
				$porto_settings['search-layout'] = $backup_search_layout;
			}
			if ( isset( $backup_show_mobile ) ) {
				$porto_settings['show-searchform-mobile'] = $backup_show_mobile;
			}
			if ( isset( $backup_search_content ) ) {
				$porto_settings['search-type'] = $backup_search_content;
			}
		}
	}
}
