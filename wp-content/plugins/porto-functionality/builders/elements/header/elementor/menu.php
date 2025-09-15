<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Builder Navigation widget
 *
 * @since 6.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_HB_Menu_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_menu';
	}

	public function get_title() {
		return __( 'Porto Navigation Menu', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'menu', 'navigation', 'main menu', 'primary menu' );
	}

	public function get_icon() {
		return 'Simple-Line-Icons-link porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/porto-menu-element/';
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_hb_menu',
			array(
				'label' => __( 'Menu', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'description_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'Please change the %1$sMenu Type%2$s.%3$s Please %4$ssave and refresh%5$s the page after %4$schanging%5$s the menu type on %4$stheme options%5$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-type' ) . '" target="_blank">', '</a>', '<br/>', '<b>', '</b>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'location',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Location', 'porto-functionality' ),
					'options' => array(
						'main-menu'        => __( 'Main Menu', 'porto-functionality' ),
						'secondary-menu'   => __( 'Secondary Menu', 'porto-functionality' ),
						'main-toggle-menu' => __( 'Main Toggle Menu( Deprecated )', 'porto-functionality' ),
						'nav-top'          => __( 'Top Navigation', 'porto-functionality' ),
					),
				)
			);

			$this->add_control(
				'menu-toggle-onhome',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Collapse the Toggle Menu on home page', 'porto-functionality' ),
					'description' => __( 'In homepage, the toggle menu is collapsed at first. Then it works as a toggle.', 'porto-functionality' ),
					'default'     => 'no',
					'condition'   => array(
						'location' => 'main-toggle-menu',
					),
				)
			);

			$this->add_control(
				'show-onhover',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show menu on Hover', 'porto-functionality' ),
					'default'     => 'no',
					'render_type' => 'template',
					'condition'   => array(
						'location' => 'main-toggle-menu',
					),
				)
			);

			$this->add_control(
				'description_toggle_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( '%1$sPlease change the Title%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-title' ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => array(
						'location' => 'main-toggle-menu',
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'top_nav_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Top Level Typography', 'porto-functionality' ),
					'selector'  => '#header .elementor-element-{{ID}} .top-links > li.menu-item > a',
					'condition' => array(
						'location' => 'nav-top',
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'top_nav_font2',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Typography', 'porto-functionality' ),
					'selector'  => '.elementor-element-{{ID}} #main-toggle-menu .menu-title',
					'condition' => array(
						'location' => 'main-toggle-menu',
					),
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
					'condition'  => array(
						'location' => 'main-toggle-menu',
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
					'condition'   => array(
						'location' => 'main-toggle-menu',
					),
					'qa_selector' => '#main-toggle-menu .menu-title .toggle',
				)
			);

			$this->add_control(
				'padding',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Top Level Left/Right Padding', 'porto-functionality' ),
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
						'#header .elementor-element-{{ID}} .top-links > li.menu-item > a' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'location' => 'nav-top',
					),
				)
			);

			$this->add_control(
				'padding2',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Title Left / Right Padding', 'porto-functionality' ),
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
						'.elementor-element-{{ID}} #main-toggle-menu .menu-title' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'location' => 'main-toggle-menu',
					),
				)
			);

			$this->add_control(
				'padding3',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Title Top / Bottom Padding', 'porto-functionality' ),
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
						'.elementor-element-{{ID}} #main-toggle-menu .menu-title' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'location' => 'main-toggle-menu',
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
					'condition'  => array(
						'location' => 'main-toggle-menu',
					),
				)
			);
			$this->start_controls_tabs(
				'tabs_hb_menu_color',
				array(
					'condition' => array(
						'location' => array( 'main-toggle-menu', 'nav-top' ),
					),
				)
			);
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
							'condition' => array(
								'location' => 'main-toggle-menu',
							),
						)
					);

					$this->add_control(
						'toggle_menu_top_color1',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Top Level Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links > li.menu-item > a' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'location' => 'nav-top',
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
							'condition' => array(
								'location' => 'main-toggle-menu',
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
							'condition' => array(
								'location' => 'main-toggle-menu',
							),
						)
					);

					$this->add_control(
						'toggle_menu_top_hover_color1',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Top Level Hover Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links > li.menu-item:hover > a' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'location' => 'nav-top',
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
							'condition' => array(
								'location' => 'main-toggle-menu',
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();



		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_menu_toggle_narrow',
			array(
				'label'     => __( 'Toggle Narrow', 'porto-functionality' ),
				'condition' => array(
					'location' => 'main-toggle-menu',
				),
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
			'section_hb_menu_hamburger',
			array(
				'label'     => __( 'Hamburger Button', 'porto-functionality' ),
				'condition' => array(
					'location' => 'main-menu',
				),
			)
		);
			$this->add_control(
				'desc_hamburger',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'Please change the %1$sMenu Type%2$s to \'Popup Menu\', If you want to use the hamburger menu.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'menu-type' ) . '" target="_blank">', '</a>', ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);		
			$this->add_responsive_control(
				'hamburger_wd',
				array(
					'type'  => Controls_Manager::SLIDER,
					'label' => __( 'Width (px)', 'porto-functionality' ),
					'range' => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 72,
						),
					),
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer-btn' => 'width: {{SIZE}}{{UNIT}};',
					),
					'qa_selector' => '.hamburguer-btn',
				)
			);
			$this->add_responsive_control(
				'hamburger_hg',
				array(
					'type'  => Controls_Manager::SLIDER,
					'label' => __( 'Height', 'porto-functionality' ),
					'range' => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 72,
						),
					),
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer-btn' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_responsive_control(
				'hamburger_th',
				array(
					'type'  => Controls_Manager::SLIDER,
					'label' => __( 'Thickness', 'porto-functionality' ),
					'range' => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 72,
						),
					),
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer span' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);	
			$this->add_responsive_control(
				'hamburger_mr',
				array(
					'label'       => esc_html__( 'Margin', 'porto-functionality' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);			
			$this->add_control(
				'hamburger_cl',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Button Color', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer span' => 'background-color: {{VALUE}};transition: background-color .3s;',
					),
				)
			);
			$this->add_control(
				'hamburger_hcl',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Hover Color', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .hamburguer-btn:hover .hamburguer span' => 'background-color: {{VALUE}};',
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
					'name'      => 'top_level_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Top Level Typography', 'porto-functionality' ),
					'selector'  => '#header .elementor-element-{{ID}} .main-menu > li.menu-item > a, #header .elementor-element-{{ID}} .menu-custom-block span, #header .elementor-element-{{ID}} .menu-custom-block a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item > a',
					'condition' => array(
						'location!' => 'nav-top',
					),
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
					'condition'  => array(
						'location' => 'main-toggle-menu',
					),
				)
			);

			$this->add_control(
				'toggle_tl_bd_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Border Color of Top Menu', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} #main-toggle-menu .toggle-menu-wrap .sidebar-menu' => 'border-color: {{VALUE}}; border-style: solid;',
					),
					'condition' => array(
						'location' => 'main-toggle-menu',
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
								'#header .elementor-element-{{ID}} .main-menu > li.menu-item > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item > .arrow:before, .elementor-element-{{ID}} .sidebar-menu > li.menu-item > .arrow:before' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'location!' => 'nav-top',
							),
						)
					);
					$this->add_control(
						'top_level_link_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Background Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links > li.menu-item > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'top_level_sticky_link_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Color On Sticky Header', 'porto-functionality' ),
							'selectors'   => array(
								'#header.sticky-header .elementor-element-{{ID}} .main-menu > li.menu-item > a' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'location!' => array( 'nav-top', 'main-toggle-menu' ),
							),
						)
					);					
					$this->add_control(
						'toggle_sp_color',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Item Separator Color', 'porto-functionality' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .sidebar-menu > li.menu-item > a' => 'border-top-color: {{VALUE}};',
							),
							'condition'  => array(
								'location' => 'main-toggle-menu',
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
								'#header .elementor-element-{{ID}} .main-menu > li.menu-item.active > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item:hover > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item.active > .arrow:before, #header .elementor-element-{{ID}} .main-menu > li.menu-item:hover > .arrow:before, .elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover > .arrow:before, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active > .arrow:before' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .menu-hover-line>li.menu-item>a:before' => 'background-color: {{VALUE}};',
							),
							'condition' => array(
								'location!' => 'nav-top',
							),
						)
					);
					$this->add_control(
						'top_level_link_hover_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Background Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links > li.menu-item:hover > a, #header .elementor-element-{{ID}} .top-links > li.menu-item.has-sub:hover > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item.active > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item:hover > a, .elementor-element-{{ID}} .sidebar-menu > li.menu-item:hover, .elementor-element-{{ID}} .sidebar-menu > li.menu-item.active' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'top_level_sticky_link_color_hover',
						array(
							'type'        => Controls_Manager::COLOR,
							'label'       => __( 'Hover Color On Sticky Header', 'porto-functionality' ),
							'selectors'   => array(
								'#header.sticky-header .elementor-element-{{ID}} .main-menu > li.menu-item.active > a, #header.sticky-header .elementor-element-{{ID}} .main-menu > li.menu-item:hover > a' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'location!' => array( 'nav-top', 'main-toggle-menu' ),
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
							'condition'  => array(
								'location' => 'main-toggle-menu',
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
						'#header .elementor-element-{{ID}} .top-links > li.menu-item > a, #header .elementor-element-{{ID}} .main-menu > li.menu-item > a, #header .elementor-element-{{ID}} .menu-custom-block a, #header .elementor-element-{{ID}} .menu-custom-block span, .elementor-element-{{ID}} .sidebar-menu>li.menu-item>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .sidebar-menu .popup:before' => 'top: calc( calc( {{TOP}}{{UNIT}} / 2 + {{BOTTOM}}{{UNIT}} / 2 - 0.5px ) + ( -1 * var(--porto-sd-menu-popup-top, 0px) ) );',
						'.elementor-element-{{ID}} .menu-hover-underline > li.menu-item > a:before' => 'margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}',
					),
					'separator'   => 'before',
					'qa_selector' => '.top-links > li:nth-child(2) > a, .main-menu > li:nth-child(2) > a',
				)
			);
			$this->add_responsive_control(
				'top_level_padding_sticky',
				array(
					'label'      => esc_html__( 'Menu Item Padding on Sticky Header', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header.sticky-header .elementor-element-{{ID}} .main-menu > li.menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'#header.sticky-header .elementor-element-{{ID}} .menu-hover-underline > li.menu-item > a:before' => 'margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}',
					),
					'condition'  => array(
						'location!' => array( 'main-toggle-menu', 'nav-top' ),
					),
				)
			);

			$this->add_responsive_control(
				'top_level_margin',
				array(
					'label'      => esc_html__( 'Menu Item Spacing', 'porto-functionality' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .top-links > li.menu-item, #header .elementor-element-{{ID}} .main-menu > li.menu-item, #header .elementor-element-{{ID}} .menu-custom-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'location!' => 'main-toggle-menu',
					),
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
					'selector' => '#header .elementor-element-{{ID}} .porto-wide-sub-menu a, #header .elementor-element-{{ID}} .porto-narrow-sub-menu a, .elementor-element-{{ID}} .sidebar-menu .popup, .elementor-element-{{ID}} .porto-popup-menu .sub-menu, #header .elementor-element-{{ID}} .top-links .narrow li.menu-item>a',
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
							'selectors'   => array(
								'#header .elementor-element-{{ID}} .top-links .narrow li.menu-item > a, #header .elementor-element-{{ID}} .main-menu .wide li.sub li.menu-item > a, #header .elementor-element-{{ID}} .main-menu .narrow li.menu-item > a,#header .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a,#header .elementor-element-{{ID}} .sidebar-menu .wide li.sub li.menu-item > a,#header .elementor-element-{{ID}} .sidebar-menu .narrow li.menu-item > a,#header .elementor-element-{{ID}} .porto-popup-menu .sub-menu a' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'submenu_link_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Popup Background Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links .narrow ul.sub-menu, #header .elementor-element-{{ID}} .main-menu .wide .popup > .inner, #header .elementor-element-{{ID}} .main-menu .narrow ul.sub-menu, .elementor-element-{{ID}} .sidebar-menu .wide .popup > .inner, .elementor-element-{{ID}} .sidebar-menu .narrow ul.sub-menu, .elementor-element-{{ID}} .porto-popup-menu .sub-menu a' => 'background-color: {{VALUE}};',
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
								'#header .elementor-element-{{ID}} .top-links .narrow li.menu-item:hover > a, #header .elementor-element-{{ID}} .main-menu .wide li.menu-item li.menu-item>a:hover, #header .elementor-element-{{ID}} .main-menu .narrow li.menu-item:hover > a, .elementor-element-{{ID}} .porto-popup-menu .sub-menu a:hover,#header .elementor-element-{{ID}} .sidebar-menu .narrow li.menu-item:hover > a,#header .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'submenu_link_hover_bg_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Item Hover Background Color', 'porto-functionality' ),
							'selectors' => array(
								'#header .elementor-element-{{ID}} .top-links .narrow li.menu-item:hover > a, #header .elementor-element-{{ID}} .sidebar-menu .narrow .menu-item:hover > a, #header .elementor-element-{{ID}} .main-menu .narrow li.menu-item:hover > a, #header .elementor-element-{{ID}} .main-menu .wide li.menu-item li.menu-item>a:hover, .elementor-element-{{ID}} .sidebar-menu .wide li.menu-item li.menu-item > a:hover, .elementor-element-{{ID}} .porto-popup-menu .sub-menu a:hover' => 'background-color: {{VALUE}};',
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
						'#header .elementor-element-{{ID}} .narrow li.menu-item>a, .elementor-element-{{ID}} .wide li.sub li.menu-item>a, .elementor-element-{{ID}} .porto-popup-menu .sub-menu li.menu-item>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'   => 'before',
					'qa_selector' => '.narrow li:first-child>a, .wide li.sub li:first-child>a, .porto-popup-menu .sub-menu li:first-child>a',
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
						'#header .elementor-element-{{ID}} .narrow ul.sub-menu, .elementor-element-{{ID}} .wide .popup>.inner, .elementor-element-{{ID}} .porto-popup-menu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'condition'  => array(
						'location!' => 'nav-top',
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
					'condition'   => array(
						'location!' => 'nav-top',
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
					'condition' => array(
						'location!' => 'nav-top',
					),
				)
			);

			$this->add_control(
				'mega_title_color',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Color', 'porto-functionality' ),
					'selectors'   => array(
						'#header .elementor-element-{{ID}} .main-menu .wide li.sub > a, .elementor-element-{{ID}} .sidebar-menu .wide li.sub > a' => 'color: {{VALUE}};',
						'#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a' => 'color: {{VALUE}} !important;',
					),
					'qa_selector' => '.wide li.sub > a',
					'condition'   => array(
						'location!' => 'nav-top',
					),
				)
			);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'mega_title_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Typography', 'porto-functionality' ),
					'selector'  => '#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a, #header .elementor-element-{{ID}} .main-menu .wide li.sub > a, #header .elementor-element-{{ID}} .sidebar-menu .wide li.sub > a',
					'condition' => array(
						'location!' => 'nav-top',
					),
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
						'#header .elementor-element-{{ID}} .wide li.side-menu-sub-title > a, #header .elementor-element-{{ID}} .main-menu .wide li.sub > a, .elementor-element-{{ID}} .sidebar-menu .wide li.sub > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'location!' => 'nav-top',
					),
				)
			);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( function_exists( 'porto_header_elements' ) ) {
			global $porto_settings;
			$el_class = '';
			if ( 'main-toggle-menu' == $settings['location'] && isset( $settings[ 'menu-toggle-onhome' ] ) ) {
				if ( 'no' !== $settings[ 'menu-toggle-onhome' ] ) {
					$toggle_home = $porto_settings['menu-toggle-onhome'];
					$porto_settings['menu-toggle-onhome'] = 'yes' == $settings[ 'menu-toggle-onhome' ] ? '1' : '0';
				}
				$el_class = isset( $settings['show-onhover'] ) && 'yes' == $settings['show-onhover'] ? 'show-hover' : '';
				if ( ! empty( $settings['menu_title'] ) ) {
					$menu_title_backup            = $porto_settings['menu-title'];
					$porto_settings['menu-title'] = $settings['menu_title'];
				}
			}
			porto_header_elements( array( (object) array( $settings['location'] => '' ) ), $el_class );
			if ( isset( $toggle_home ) ) {
				$porto_settings['menu-toggle-onhome'] = $toggle_home;
			}
			if ( isset( $menu_title_backup ) ) {
				$porto_settings['menu-title'] = $menu_title_backup;
			}
		}
	}
}
