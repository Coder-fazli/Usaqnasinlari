<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Builder Navigation widget
 *
 * @since 3.2.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_HB_Toggle_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_toggle';
	}

	public function get_title() {
		return __( 'Porto Toggle Dropdown Menu', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'menu', 'click', 'hover', 'submenu', 'popup', 'main menu', 'primary menu' );
	}

	public function get_icon() {
		return 'fas fa-stream porto-elementor-widget-icon';
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_hb_toggle',
			array(
				'label' => __( 'Menu', 'porto-functionality' ),
			)
		);
		
			$this->add_control(
				'menu-toggle-onhome',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Collapse the Menu on homepage', 'porto-functionality' ),
					'description' => __( 'On homepage, a toggle menu is collapsed at first. Then it works as a toggle.', 'porto-functionality' ),
					'default'     => 'yes',
				)
			);

			$this->add_control(
				'show-onhover',
				array(
					'type'    => Controls_Manager::SWITCHER,
					'label'   => __( 'Show menu on Hover', 'porto-functionality' ),
					'default' => 'no',
				)
			);

			$this->add_control(
				'menu_title',
				array(
					'label'        => __( 'Menu Title', 'porto-functionality' ),
					'type'         => Controls_Manager::TEXT,
					'rendery_type' => 'template',
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'top_nav_font',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Typography', 'porto-functionality' ),
					'selector' => '.elementor-element-{{ID}} #main-toggle-menu .menu-title',
				)
			);

			$this->add_control(
				'tg_icon_sz',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Size', 'porto-functionality' ),
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
						'.elementor-element-{{ID}} #main-toggle-menu .toggle' => 'font-size: {{SIZE}}{{UNIT}};vertical-align: middle;',
					),
				)
			);

			$this->add_control(
				'between_spacing',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Between Spacing', 'porto-functionality' ),
					'range'       => array(
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
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} #main-toggle-menu .menu-title .toggle' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
					'qa_selector' => '#main-toggle-menu .menu-title .toggle',
				)
			);

			$this->add_control(
				'padding1',
				array(
					'type'       => Controls_Manager::DIMENSIONS,
					'label'      => __( 'Title Padding', 'porto-functionality' ),
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
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #main-toggle-menu .menu-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'popup_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Popup Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 372,
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
						'.elementor-element-{{ID}} #main-toggle-menu .toggle-menu-wrap' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_hb_menu_color' );

				$this->start_controls_tab(
					'tab_hb_menu_color',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'toggle_menu_top_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} #main-toggle-menu .menu-title' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_menu_top_bgcolor',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} #main-toggle-menu .menu-title' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'tab_hb_menu_hover_color',
					array(
						'label' => esc_html__( 'Hover', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'toggle_menu_top_hover_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} #main-toggle-menu .menu-title:hover' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_menu_top_hover_bgcolor',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} #main-toggle-menu .menu-title:hover' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_menu_toggle_narrow',
			array(
				'label' => __( 'Toggle Narrow', 'porto-functionality' ),
			)
		);
			$this->add_control(
				'show_narrow',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => __( 'Show Narrow', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .menu-title:after' => "content:'\\e81c';position:absolute;font-family:'porto';{$right}: 1.4rem;",
					),
				)
			);
			$this->add_control(
				'narrow_pos',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Position', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
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
						'.elementor-element-{{ID}} .menu-title:after' => "{$right}: {{SIZE}}{{UNIT}};",
					),
					'condition'  => array(
						'show_narrow' => 'yes',
					),
				)
			);
			$this->add_control(
				'narrow_sz',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Size', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
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
						'.elementor-element-{{ID}} .menu-title:after' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'show_narrow' => 'yes',
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_menu_style_top',
			array(
				'label' => __( 'Top Level Menu', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'top_level_font',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Top Level Typography', 'porto-functionality' ),
					'selector' => '.elementor-element-{{ID}} .sidebar-menu > li.menu-item > a',
				)
			);
			$this->add_control(
				'toggle_tl_bd_width',
				array(
					'label'      => esc_html__( 'Border Width of Top Menu', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} #main-toggle-menu .toggle-menu-wrap .sidebar-menu' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
					'separator'  => 'before',
				)
			);

			$this->add_control(
				'toggle_tl_bd_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Border Color of Top Menu', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} #main-toggle-menu .toggle-menu-wrap .sidebar-menu' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->start_controls_tabs( 'tabs_top_level_color' );
				$this->start_controls_tab(
					'tab_top_level_color',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'top_level_link_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Link Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item > .arrow:before' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'top_level_link_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item' => 'background-color: {{VALUE}};',
							),
						)
					);			
					$this->add_control(
						'toggle_sp_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Item Separator Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_top_level_hover_color',
					array(
						'label' => esc_html__( 'Hover', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'top_level_link_hover_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover > .arrow:before, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active > .arrow:before' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'top_level_link_hover_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'toggle_sp_hcolor',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Item Separator Hover Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover + li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'top_level_padding',
				array(
					'label'       => esc_html__( 'Menu Item Padding', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'mainmenu-toplevel-padding1' ) . '" target="_blank">', '</a>' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .sidebar-menu>li.menu-item>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .sidebar-menu .popup:before' => 'top: calc( calc( {{TOP}}{{UNIT}} / 2 + {{BOTTOM}}{{UNIT}} / 2 - 0.5px ) + ( -1 * var(--porto-sd-menu-popup-top, 0px) ) );',
					),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'top_level_icon_sz',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => __( 'Icon Size', 'porto-functionality' ),
					'range'       => array(
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
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} li.menu-item>a>[class*=" fa-"]' => 'width: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} li.menu-item>a>i' => 'font-size: {{SIZE}}{{UNIT}};vertical-align: middle;',
					),
					'qa_selector' => 'li.menu-item>a>i',
				)
			);

			$this->add_control(
				'top_level_icon_spacing',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Spacing', 'porto-functionality' ),
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
						'.elementor-element-{{ID}} li.menu-item>a>.avatar, .elementor-element-{{ID}} li.menu-item>a>i' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_menu_style_submenu',
			array(
				'label' => __( 'Menu Popup', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'submenu_font',
					'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'    => __( 'Popup Typography', 'porto-functionality' ),
					'selector' => '#header .elementor-element-{{ID}} .porto-wide-sub-menu a, #header .elementor-element-{{ID}} .porto-narrow-sub-menu a, .elementor-element-{{ID}} .sidebar-menu .popup, #header .elementor-element-{{ID}} .top-links .narrow li.menu-item>a',
				)
			);

			$this->start_controls_tabs( 'tabs_submenu_color' );
				$this->start_controls_tab(
					'tab_submenu_color',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'submenu_link_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Link Color', 'porto-functionality' ),
							'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'mainmenu-popup-text-color' ) . '" target="_blank">', '</a>' ),
							'selectors'   => array(
								'#header .elementor-element-{{ID}} .top-links .narrow li.menu-item > a, #header .elementor-element-{{ID}} .main-menu .wide li.sub li.menu-item > a, #header .elementor-element-{{ID}} .main-menu .narrow li.menu-item > a,#header .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a, #header .elementor-element-{{ID}} .sidebar-menu .wide li.sub li.menu-item > a,#header .elementor-element-{{ID}} .sidebar-menu .narrow li.menu-item > a' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'submenu_link_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .sidebar-menu .wide .popup > .inner, .elementor-element-{{ID}} .sidebar-menu .narrow ul.sub-menu' => 'background-color: {{VALUE}};',
								'.elementor-element-{{ID}} .mega-menu > li.has-sub:before, .elementor-element-{{ID}} .mega-menu > li.has-sub:after' => 'border-bottom-color: {{VALUE}};',
								'.elementor-element-{{ID}} .sidebar-menu .popup:before' => 'border-right-color: {{VALUE}};'
							),
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_submenu_hover_color',
					array(
						'label' => esc_html__( 'Hover', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'submenu_link_hover_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .sidebar-menu .narrow li.menu-item:hover > a, #header .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'submenu_link_hover_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .sidebar-menu .narrow .menu-item:hover > a, #header .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'background-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'submenu_item_padding',
				array(
					'label'       => esc_html__( 'Item Padding', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .narrow li.menu-item>a, .elementor-element-{{ID}} .wide li.sub li.menu-item>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'   => 'before',
					'qa_selector' => '.narrow li:first-child>a, .wide li.sub li:first-child>a',
				)
			);

			$this->add_control(
				'submenu_padding',
				array(
					'label'       => esc_html__( 'SubMenu Padding', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .narrow ul.sub-menu, .elementor-element-{{ID}} .wide .popup>.inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'#header .elementor-element-{{ID}} .porto-narrow-sub-menu ul.sub-menu' => 'top: -{{TOP}}{{UNIT}};',
					),
					'qa_selector' => '.narrow ul.sub-menu, .wide .popup>.inner',
				)
			);

			$this->add_control(
				'submenu_narrow_bd_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Item Border Width on Narrow Menu', 'porto-functionality' ),
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
						'#header .elementor-element-{{ID}} .narrow li.menu-item>a' => '--porto-submenu-item-bbw: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'submenu_narrow_border_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Item Border Color on Narrow Menu', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .narrow li.menu-item > a' => 'border-bottom-color: {{VALUE}};',
					),
					'qa_selector' => '.narrow li:nth-child(2)>a',
				)
			);

			$this->add_control(
				'heading_wide_subheading',
				array(
					'label'     => esc_html__( 'Sub Heading on Mega Menu', 'porto-functionality' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'mega_title_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Color', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .sidebar-menu .wide li.sub > a' => 'color: {{VALUE}};',
						'#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a' => 'color: {{VALUE}} !important;',
					),
					'qa_selector' => '.wide li.sub > a',
				)
			);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'mega_title_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Typography', 'porto-functionality' ),
					'selector'  => '#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a, #header .elementor-element-{{ID}} .sidebar-menu .wide li.sub > a',
				)
			);

			$this->add_control(
				'mega_title_padding',
				array(
					'label'      => esc_html__( 'Padding', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a, .elementor-element-{{ID}} .sidebar-menu .wide li.sub > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( function_exists( 'porto_header_elements' ) ) {
			global $porto_settings;
			if ( isset( $settings[ 'menu-toggle-onhome' ] ) ) {
				$toggle_home = $porto_settings['menu-toggle-onhome'];
				$porto_settings['menu-toggle-onhome'] = 'yes' == $settings[ 'menu-toggle-onhome' ] ? '1' : '0';
			}
			if ( ! empty( $settings['menu_title'] ) ) {
				$menu_title_backup            = $porto_settings['menu-title'];
				$porto_settings['menu-title'] = $settings['menu_title'];
			}
			porto_header_elements( array( (object) array( 'main-toggle-menu' => '' ) ), ( isset( $settings['show-onhover'] ) && 'yes' == $settings['show-onhover'] ) ? 'show-hover' : '' );
			if ( isset( $toggle_home ) ) {
				$porto_settings['menu-toggle-onhome'] = $toggle_home;
			}
			if ( isset( $menu_title_backup ) ) {
				$porto_settings['menu-title'] = $menu_title_backup;
			}
		}
	}
}
