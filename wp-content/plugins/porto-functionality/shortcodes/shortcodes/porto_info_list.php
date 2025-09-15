<?php
// Porto Info List

add_action( 'vc_after_init', 'porto_load_info_list_shortcode' );

function porto_load_info_list_shortcode() {

	$animation_type     = porto_vc_animation_type();
	$animation_duration = porto_vc_animation_duration();
	$animation_delay    = porto_vc_animation_delay();
	$custom_class       = porto_vc_custom_class();

	$left  = is_rtl() ? 'right' : 'left';
	$right = is_rtl() ? 'left' : 'right';
	
	vc_map(
		array(
			'name'                    => __( 'Porto Info List', 'porto-functionality' ),
			'base'                    => 'porto_info_list',
			'icon'                    => PORTO_WIDGET_URL . 'info-list.png',
			'class'                   => 'porto-wpb-widget porto_info_list',
			'category'                => __( 'Porto', 'porto-functionality' ),
			'as_parent'               => array( 'only' => 'porto_info_list_item' ),
			'description'             => __( 'Text blocks connected together in one list.', 'porto-functionality' ),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'params'                  => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Layout', 'porto-functionality' ),
					'param_name' => 'layout',
					'value'      => array(
						__( 'Default', 'porto-functionality' ) => '',
						__( 'Inline', 'porto-functionality' )  => 'flex',
					),
					'selectors'  => array(
						'{{WRAPPER}}' => 'display: {{VALUE}};',
					),
				),
				array(
					'type'        => 'colorpicker',
					'class'       => '',
					'heading'     => __( 'Icon Color:', 'porto-functionality' ),
					'param_name'  => 'icon_color',
					'value'       => '#333333',
					'description' => __( 'Select the color for icon.', 'porto-functionality' ),
					'selectors'   => array(
						'{{WRAPPER}} .porto-info-list-item i' => 'color: {{VALUE}};',
					),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Top/Bottom Item Padding ', 'porto-functionality' ),
					'param_name' => 'item_space',
					'units'      => array( 'px', 'em' , 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} li.porto-info-list-item' => 'padding-top: {{VALUE}}{{UNIT}}; padding-bottom: {{VALUE}}{{UNIT}};',
					),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Horizontal Spacing', 'porto-functionality' ),
					'param_name' => 'item_space_inline',
					'units'      => array( 'px', 'em' , 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} li.porto-info-list-item:not(:last-child)' => 'padding-' . $right . ': {{VALUE}}{{UNIT}};',
					),
					'dependency'  => array(
						'element' => 'layout',
						'value'   => 'flex',
					),
				),
				array(
					'type'       => 'porto_number',
					'heading'    => __( 'Spacing between Icon & Description', 'porto-functionality' ),
					'param_name' => 'icon_space',
					'units'      => array( 'px', 'em' , 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .porto-info-icon' => 'margin-' . ( is_rtl() ? 'left' : 'right' ) . ': {{VALUE}}{{UNIT}};',
					),
				),
				array(
					'type'       => 'number',
					'class'      => '',
					'heading'    => __( 'Icon Font Size (px)', 'porto-functionality' ),
					'param_name' => 'font_size_icon',
					'value'      => '',
					'min'        => 10,
					'max'        => 50,
					'suffix'     => 'px',
					'selectors'  => array(
						'{{WRAPPER}} .porto-info-list-item i' => 'font-size: {{VALUE}}px;',
					),
				),
				array(
					'type'       => 'number',
					'class'      => '',
					'heading'    => __( 'Image Width (px)', 'porto-functionality' ),
					'param_name' => 'image_size',
					'value'      => '',
					'min'        => 10,
					'max'        => 400,
					'selectors'  => array(
						'{{WRAPPER}} .porto-info-list-item img.porto-info-icon' => 'width: {{VALUE}}px;',
					),
				),
				$custom_class,
			),
			'js_view'                 => 'VcColumnView',
		)
	);

	class WPBakeryShortCode_porto_info_list extends WPBakeryShortCodesContainer {
	}
}
