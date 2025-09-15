<?php

// Porto Carousel
add_action( 'vc_after_init', 'porto_load_carousel_shortcode' );

function porto_load_carousel_shortcode() {
	$animation_type     = porto_vc_animation_type();
	$animation_duration = porto_vc_animation_duration();
	$animation_delay    = porto_vc_animation_delay();
	$animation_reveal_clr = porto_vc_animation_reveal_clr();
	$custom_class       = porto_vc_custom_class();
	$left  = is_rtl() ? 'right' : 'left';
	$right = is_rtl() ? 'left' : 'right';

	vc_map(
		array(
			'name'            => 'Porto ' . __( 'Carousel', 'porto-functionality' ),
			'base'            => 'porto_carousel',
			'category'        => __( 'Porto', 'porto-functionality' ),
			'description'     => __( 'A multiple page slider', 'porto-functionality' ),
			'icon'            => PORTO_WIDGET_URL . 'carousel.gif',
			'class'           => 'porto-wpb-widget',
			'as_parent'       => array( 'except' => 'porto_carousel' ),
			'content_element' => true,
			'controls'        => 'full',
			//'is_container' => true,
			'js_view'         => 'VcColumnView',
			'params'          => array(
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_flick',
					'text'       => esc_html__( 'Flick Carousel', 'porto-functionality' ),
					'with_group' => true,
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Enable Flick Type', 'porto-functionality' ),
					'param_name'  => 'enable_flick',
					'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_carousel_flick.gif"/>',
					'separator'   => 'before',
					'description' => sprintf( __( 'This option shows the carousel at the container\'s width. %1$sRead More%2$s', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-porto-flick-carousel" target="_blank">', '</a>' ),
					'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
				),
				array(
					'type'       => 'number',
					'heading'    => __( 'Opacity of Inactive item', 'porto-functionality' ),
					'param_name' => 'flick_opacity',
					'dependency' => array(
						'element'   => 'enable_flick',
						'not_empty' => true,
					),
					'min'        => 0,
					'max'        => 1,
					'std'        => 0.5,
					'selectors'  => array(
						'{{WRAPPER}} .owl-item:not(.active)' => 'opacity: {{VALUE}}',
					),
				),
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_slider',
					'text'       => esc_html__( 'Slider Setting', 'porto-functionality' ),
					'with_group' => true,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Stage Padding (px)', 'porto-functionality' ),
					'param_name' => 'stage_padding',
					'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_carousel-stage_padding.gif"/>',
					'value'      => 40,
					'dependency' => array(
						'element'   => 'enable_flick',
						'is_empty' => true,
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show items in stage padding', 'porto-functionality' ),
					'param_name' => 'show_items_padding',
					'dependency' => array(
						'element'   => 'stage_padding',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Disable Mouse Drag', 'porto-functionality' ),
					'description' => __( 'This option will disapprove Mouse Drag.', 'porto-functionality' ),
					'param_name'  => 'disable_mouse_drag',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Item Margin (px)', 'porto-functionality' ),
					'param_name' => 'margin',
					'value'      => 10,
					'selectors'  => array(
						'{{WRAPPER}}' => '--porto-el-spacing: {{VALUE}}px;',
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Auto Play', 'porto-functionality' ),
					'param_name' => 'autoplay',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Auto Play Timeout', 'porto-functionality' ),
					'param_name' => 'autoplay_timeout',
					'dependency' => array(
						'element'   => 'autoplay',
						'not_empty' => true,
					),
					'value'      => 5000,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Pause on Mouse Hover', 'porto-functionality' ),
					'param_name' => 'autoplay_hover_pause',
					'dependency' => array(
						'element'   => 'autoplay',
						'not_empty' => true,
					),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Items', 'porto-functionality' ),
					'param_name' => 'items_responsive',
					'responsive' => true,
				),
				array(
					'type'       => 'porto_button_group',
					'heading'    => __( 'Alignment', 'porto-functionality' ),
					'param_name' => 'v_align',
					'value'      => array(
						'flex-start'   => array(
							'title' => esc_html__( 'Top', 'porto-functionality' ),
						),
						'center' => array(
							'title' => esc_html__( 'Middle', 'porto-functionality' ),
						),
						'flex-end'  => array(
							'title' => esc_html__( 'Bottom', 'porto-functionality' ),
						),
					),
					'std'        => '',
					'selectors' => array(
						'{{WRAPPER}} .owl-stage' => 'display: flex;align-items: {{VALUE}};flex-wrap: wrap;',
					)
				),
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_nav',
					'text'       => esc_html__( 'Navigation', 'porto-functionality' ),
					'group'      => __( 'Slider Options', 'porto-functionality' ),
					'with_group' => true,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show Nav', 'porto-functionality' ),
					'param_name' => 'show_nav',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Slider Options', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show Nav on Hover', 'porto-functionality' ),
					'param_name' => 'show_nav_hover',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'dependency' => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'group'      => __( 'Slider Options', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Nav Position', 'porto-functionality' ),
					'param_name' => 'nav_pos',
					'value'      => array(
						__( 'Middle', 'porto-functionality' ) => '',
						__( 'Middle Inside', 'porto-functionality' ) => 'nav-pos-inside',
						__( 'Middle Outside', 'porto-functionality' ) => 'nav-pos-outside',
						__( 'Top', 'porto-functionality' ) => 'show-nav-title',
						__( 'Bottom', 'porto-functionality' ) => 'nav-bottom',
						__( 'Custom', 'porto-functionality' ) => 'custom-pos',
					),
					'dependency' => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Nav Type', 'porto-functionality' ),
					'param_name' => 'nav_type',
					'value'      => porto_sh_commons( 'carousel_nav_types' ),
					'dependency' => array(
						'element' => 'nav_pos',
						'value'   => array( '', 'nav-pos-inside', 'nav-pos-outside', 'nav-bottom', 'custom-pos' ),
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_dots',
					'text'       => esc_html__( 'Dots', 'porto-functionality' ),
					'group'      => __( 'Slider Options', 'porto-functionality' ),
					'with_group' => true,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show Dots', 'porto-functionality' ),
					'param_name' => 'show_dots',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Slider Options', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Dots Position', 'porto-functionality' ),
					'param_name' => 'dots_pos',
					'value'      => array(
						__( 'Outside', 'porto-functionality' )          => '',
						__( 'Inside', 'porto-functionality' )           => 'nav-inside',
						__( 'Top beside title', 'porto-functionality' ) => 'show-dots-title',
						__( 'Custom', 'porto-functionality' )           => 'custom-dots',
					),
					'dependency' => array(
						'element'   => 'show_dots',
						'not_empty' => true,
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Dots Style', 'porto-functionality' ),
					'param_name' => 'dots_style',
					'value'      => array(
						__( 'Default', 'porto-functionality' ) => '',
						__( 'Circle inner dot', 'porto-functionality' ) => 'dots-style-1',
					),
					'dependency' => array(
						'element'   => 'show_dots',
						'not_empty' => true,
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'        => 'porto_number',
					'heading'     => __( 'Top Position', 'porto-functionality' ),
					'param_name'  => 'dots_pos_top',
					'units'       => array( 'px', 'rem', '%' ),
					'dependency'  => array(
						'element' => 'dots_pos',
						'value'   => 'custom-dots',
					),
					'responsive'  => true,
					'selectors'   => array(
						'{{WRAPPER}} .owl-dots' => 'top: {{VALUE}}{{UNIT}} !important;',
					),
					'qa_selector' => '.owl-dots > .owl-dot:first-child',
					'group'       => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Bottom Position', 'porto-functionality' ),
					'param_name' => 'dots_pos_bottom',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element' => 'dots_pos',
						'value'   => 'custom-dots',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots' => 'bottom: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Left Position', 'porto-functionality' ),
					'param_name' => 'dots_pos_left',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element' => 'dots_pos',
						'value'   => 'custom-dots',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots' => 'left: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Right Position', 'porto-functionality' ),
					'param_name' => 'dots_pos_right',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element' => 'dots_pos',
						'value'   => 'custom-dots',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots' => 'right: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Dots Size', 'porto-functionality' ),
					'param_name' => 'dots_size',
					'units'      => array( 'px', 'rem', '%' ),
					'responsive' => true,
					'dependency' => array(
						'element'   => 'show_dots',
						'not_empty' => true,
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots .owl-dot span' => 'width: {{VALUE}}{{UNIT}};height: {{VALUE}}{{UNIT}};',
						'{{WRAPPER}}.dots-style-1 .owl-dot span::after' => 'border-width: calc( 2px + ( {{VALUE}}{{UNIT}} - 14px ) / 2 ) !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Dots Spacing', 'porto-functionality' ),
					'param_name' => 'dots_spacing',
					'units'      => array( 'px', 'rem', '%' ),
					'responsive' => true,
					'dependency' => array(
						'element'   => 'show_dots',
						'not_empty' => true,
					),
					'selectors'  => array(
						'{{WRAPPER}}.owl-carousel .owl-dots span' => 'margin: {{VALUE}}{{UNIT}};',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_button_group',
					'heading'    => __( 'Dots Visible', 'porto-functionality' ),
					'param_name' => 'dots_visible',
					'value'      => array(
						'block' => array(
							'title' => esc_html__( 'Show', 'porto-functionality' ),
							'icon'  => 'far fa-eye',
						),
						'none' => array(
							'title' => esc_html__( 'none', 'porto-functionality' ),
							'icon'  => 'far fa-eye-slash',
						),
					),
					'dependency' => array(
						'element'   => 'show_dots',
						'not_empty' => true,
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots:not(.disabled)' => 'display: {{VALUE}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_image_select',
					'heading'    => __( 'Dots Translate X', 'porto-functionality' ),
					'param_name' => 'dots_original',
					'value'      => array(
						'transform/left.jpg'    => '-50%',
						'transform/center.jpg' => '',
						'transform/right.jpg'   => '50%',
					),
					'dependency' => array(
						'element' => 'dots_pos',
						'value'   => 'custom-dots',
					),
					'std'        => '',
					'selectors'  => array(
						'{{WRAPPER}} .owl-dots:not(.disabled)' => 'transform: translateX( {{VALUE}} ) !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'dots_br_color',
					'heading'    => __( 'Dots Color', 'porto-functionality' ),
					'separator'  => 'before',
					'dependency' => array(
						'element' => 'dots_style',
						'value'   => 'dots-style-1',
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-dot span' => 'border-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'dots_abr_color',
					'heading'    => __( 'Dots Active Color', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'dots_style',
						'value'   => 'dots-style-1',
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-dot.active span, {{WRAPPER}} .owl-dot:hover span' => 'color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'dots_bg_color',
					'heading'    => __( 'Dots Color', 'porto-functionality' ),
					'separator'  => 'before',
					'dependency' => array(
						'element'            => 'dots_style',
						'value_not_equal_to' => 'dots-style-1',
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-dot span' => 'background-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'dots_abg_color',
					'heading'    => __( 'Dots Active Color', 'porto-functionality' ),
					'dependency' => array(
						'element'            => 'dots_style',
						'value_not_equal_to' => 'dots-style-1',
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-dot.active span, {{WRAPPER}} .owl-dot:hover span' => 'background-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Dots Align', 'porto-functionality' ),
					'param_name' => 'dots_align',
					'value'      => array(
						__( 'Right', 'porto-functionality' )  => '',
						__( 'Center', 'porto-functionality' ) => 'nav-inside-center',
						__( 'Left', 'porto-functionality' )   => 'nav-inside-left',
					),
					'dependency' => array(
						'element' => 'dots_pos',
						'value'   => array( 'nav-inside' ),
					),
					'group'      => __( 'Dots Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_button_group',
					'heading'    => __( 'Navigation Visible', 'porto-functionality' ),
					'param_name' => 'nav_visible',
					'value'      => array(
						'block' => array(
							'title' => esc_html__( 'Show', 'porto-functionality' ),
							'icon'  => 'far fa-eye',
						),
						'none'   => array(
							'title' => esc_html__( 'none', 'porto-functionality' ),
							'icon'  => 'far fa-eye-slash',
						),
					),
					'dependency' => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'std'        => 'block',
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav:not(.disabled)' => 'display: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'        => 'porto_number',
					'heading'     => __( 'Nav Font Size', 'porto-functionality' ),
					'param_name'  => 'nav_fs',
					'dependency'  => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'responsive' => true,
					'separator'   => 'before',
					'selectors'   => array(
						'{{WRAPPER}} .owl-nav button' => 'font-size: {{VALUE}}px !important;line-height: {{VALUE}}px !important;',
					),
					'qa_selector' => '.owl-nav > .owl-prev',
					'group'       => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Nav Width', 'porto-functionality' ),
					'param_name' => 'nav_width',
					'units'      => array( 'px', 'rem', '%' ),
					'responsive' => true,
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'width: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Nav Height', 'porto-functionality' ),
					'param_name' => 'nav_height',
					'units'      => array( 'px', 'rem', '%' ),
					'responsive' => true,
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'height: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Border Radius', 'porto-functionality' ),
					'param_name' => 'nav_br',
					'units'      => array( 'px', '%' ),
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'border-radius: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Nav Origin X Position', 'porto-functionality' ),
					'param_name' => 'navs_h_origin',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element'            => 'nav_pos',
						'value_not_equal_to' => 'nav-bottom',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav' => "{$left}: {{VALUE}}{{UNIT}} !important; {$right}: unset !important;",
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Nav Origin Y Position', 'porto-functionality' ),
					'param_name' => 'nav_v_pos',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element'            => 'nav_pos',
						'value_not_equal_to' => 'nav-bottom',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav' => 'top: {{VALUE}}{{UNIT}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Horizontal Nav Position', 'porto-functionality' ),
					'param_name' => 'nav_h_pos',
					'units'      => array( 'px', 'rem', '%' ),
					'dependency' => array(
						'element'            => 'nav_pos',
						'value_not_equal_to' => 'nav-bottom',
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button.owl-prev'                                    => "{$left}: {{VALUE}}{{UNIT}} !important;",
						'{{WRAPPER}} .owl-carousel:not(.show-nav-title) .owl-nav button.owl-next' => "{$right}: {{VALUE}}{{UNIT}} !important;",
						'{{WRAPPER}}.owl-carousel:not(.show-nav-title) .owl-nav button.owl-next'  => "{$right}: {{VALUE}}{{UNIT}} !important;",
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_color',
					'heading'    => __( 'Nav Color', 'porto-functionality' ),
					'dependency' => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'separator'  => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_h_color',
					'heading'    => __( 'Hover Nav Color', 'porto-functionality' ),
					'dependency' => array(
						'element'   => 'show_nav',
						'not_empty' => true,
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_bg_color',
					'heading'    => __( 'Background Color', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( '', 'big-nav', 'nav-style-3' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'background-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_h_bg_color',
					'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( '', 'big-nav', 'nav-style-3' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'background-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_br_color',
					'heading'    => __( 'Nav Border Color', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( 'rounded-nav' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button' => 'border-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'nav_h_br_color',
					'heading'    => __( 'Hover Nav Border Color', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'nav_type',
						'value'   => array( 'rounded-nav' ),
					),
					'selectors'  => array(
						'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'border-color: {{VALUE}} !important;',
					),
					'group'      => __( 'Nav Style', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_item_animate',
					'text'       => esc_html__( 'Slide Animation', 'porto-functionality' ),
					'group'      => __( 'Animation', 'porto-functionality' ),
					'with_group' => true,
				),
				array(
					'type'       => 'porto_animation_type',
					'heading'    => __( 'Item Animation In', 'porto-functionality' ),
					'param_name' => 'animate_in',
					'group'      => __( 'Animation', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_animation_type',
					'heading'    => __( 'Item Animation Out', 'porto-functionality' ),
					'param_name' => 'animate_out',
					'group'      => __( 'Animation', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Infinite loop', 'porto-functionality' ),
					'param_name' => 'loop',
					'hint'       => '<img src="' . PORTO_HINT_URL . 'wd_carousel-loop.gif"/>',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Full Screen', 'porto-functionality' ),
					'param_name' => 'fullscreen',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Center Item', 'porto-functionality' ),
					'param_name' => 'center',
					'hint'       => '<img src="' . PORTO_HINT_URL . 'wd_carousel-center.gif"/>',
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Fetch Videos', 'porto-functionality' ),
					'param_name'  => 'video',
					'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'description' => __( 'Please edit video items using porto carousel item element.', 'porto-functionality' ),
					'group'       => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Lazy Load', 'porto-functionality' ),
					'param_name'  => 'lazyload',
					'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'description' => __( 'Please edit lazy load images using porto carousel item element or porto interactive banner element.', 'porto-functionality' ),
					'group'       => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Merge Items', 'porto-functionality' ),
					'param_name'  => 'merge',
					'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'description' => __( 'Please edit merge items using porto carousel item element.', 'porto-functionality' ),
					'group'       => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Merge Fit', 'porto-functionality' ),
					'param_name' => 'mergeFit',
					'std'        => 'yes',
					'dependency' => array(
						'element'   => 'merge',
						'not_empty' => true,
					),
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Merge Fit on Desktop', 'porto-functionality' ),
					'param_name' => 'mergeFit_lg',
					'std'        => 'yes',
					'dependency' => array(
						'element'   => 'merge',
						'not_empty' => true,
					),
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Merge Fit on Tablet', 'porto-functionality' ),
					'param_name' => 'mergeFit_md',
					'std'        => 'yes',
					'dependency' => array(
						'element'   => 'merge',
						'not_empty' => true,
					),
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Merge Fit on Mobile', 'porto-functionality' ),
					'param_name' => 'mergeFit_sm',
					'std'        => 'yes',
					'dependency' => array(
						'element'   => 'merge',
						'not_empty' => true,
					),
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Merge Fit on Mini', 'porto-functionality' ),
					'param_name' => 'mergeFit_xs',
					'std'        => 'yes',
					'dependency' => array(
						'element'   => 'merge',
						'not_empty' => true,
					),
					'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group'      => __( 'Advanced', 'porto-functionality' ),
				),
				$custom_class,
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'dsc_animate',
					'text'       => esc_html__( 'Animation', 'porto-functionality' ),
					'group'      => __( 'Animation', 'porto-functionality' ),
					'with_group' => true,
				),
				$animation_type,
				$animation_duration,
				$animation_delay,
				$animation_reveal_clr,
			),
		)
	);

	if ( ! class_exists( 'WPBakeryShortCode_Porto_Carousel' ) ) {
		class WPBakeryShortCode_Porto_Carousel extends WPBakeryShortCodesContainer {
		}
	}
}
