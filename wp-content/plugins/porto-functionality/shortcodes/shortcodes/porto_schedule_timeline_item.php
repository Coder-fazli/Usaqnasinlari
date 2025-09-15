<?php

// Porto Schedule Timeline Item
add_action( 'vc_after_init', 'porto_load_schedule_timeline_item_shortcode' );

function porto_load_schedule_timeline_item_shortcode() {
	$animation_type     = porto_vc_animation_type();
	$animation_duration = porto_vc_animation_duration();
	$animation_delay    = porto_vc_animation_delay();
	$animation_reveal_clr = porto_vc_animation_reveal_clr();
	$custom_class       = porto_vc_custom_class();

	vc_map(
		array(
			'name'        => __( 'Step Item', 'porto-functionality' ),
			'base'        => 'porto_schedule_timeline_item',
			'category'    => __( 'Porto', 'porto-functionality' ),
			'description' => __( 'Show schedule by beautiful timeline, history or step', 'porto-functionality' ),
			'icon'        => PORTO_WIDGET_URL . 'steps.png',
			'class'       => 'porto-wpb-widget',
			'as_child'    => array( 'only' => 'porto_schedule_timeline_container' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Time Text', 'porto-functionality' ),
					'param_name'  => 'subtitle',
					'description' => __( 'Please input the text which describes time or current step. This is not working for "Step" type.', 'porto-functionality' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Icon to display:', 'porto-functionality' ),
					'param_name'  => 'icon_type',
					'value'       => array(
						__( 'Font Awesome', 'porto-functionality' ) => 'fontawesome',
						__( 'Simple Line Icon', 'porto-functionality' ) => 'simpleline',
						__( 'Porto Icon', 'porto-functionality' ) => 'porto',
						__( 'Custom Image Icon', 'porto-functionality' ) => 'custom',
					),
					'std'         => 'custom',
					'description' => __( 'Use an existing font icon or upload a custom image.', 'porto-functionality' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Image URL', 'porto-functionality' ),
					'param_name' => 'image_url',
					'dependency' => array(
						'element' => 'icon_type',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Image', 'porto-functionality' ),
					'param_name' => 'image_id',
					'dependency' => array(
						'element' => 'icon_type',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'porto-functionality' ),
					'param_name' => 'icon',
					'dependency' => array(
						'element' => 'icon_type',
						'value'   => array( 'fontawesome' ),
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'porto-functionality' ),
					'param_name' => 'icon_simpleline',
					'settings'   => array(
						'type'         => 'simpleline',
						'iconsPerPage' => 4000,
					),
					'dependency' => array(
						'element' => 'icon_type',
						'value'   => 'simpleline',
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'porto-functionality' ),
					'param_name' => 'icon_porto',
					'settings'   => array(
						'type'         => 'porto',
						'iconsPerPage' => 4000,
					),
					'dependency' => array(
						'element' => 'icon_type',
						'value'   => 'porto',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Item Title', 'porto-functionality' ),
					'param_name'  => 'heading',
					'admin_label' => true,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Tag', 'porto-functionality' ),
					'param_name' => 'item_title_tag',
					'value'      => array(
						''  => '',
						esc_html__( 'H1', 'porto-functionality' )  => 'h1',
						esc_html__( 'H2', 'porto-functionality' )  => 'h2',
						esc_html__( 'H3', 'porto-functionality' )  => 'h3',
						esc_html__( 'H4', 'porto-functionality' )  => 'h4',
						esc_html__( 'H5', 'porto-functionality' )  => 'h5',
						esc_html__( 'H6', 'porto-functionality' )  => 'h6',
						esc_html__( 'DIV', 'porto-functionality' ) => 'div',
					),
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Description', 'porto-functionality' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Shadow', 'porto-functionality' ),
					'param_name' => 'shadow',
				),
				array(
					'type'       => 'label',
					'heading'    => __( 'Title Settings', 'porto-functionality' ),
					'param_name' => 'label',
					'group'      => 'Typography',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'param_name' => 'heading_color',
					'group'      => 'Typography',
				),
				array(
					'type'       => 'label',
					'heading'    => __( 'Time Text Settings', 'porto-functionality' ),
					'param_name' => 'label',
					'group'      => 'Typography',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'param_name' => 'subtitle_color',
					'group'      => 'Typography',
				),
				$custom_class,
				$animation_type,
				$animation_duration,
				$animation_delay,
				$animation_reveal_clr,

			),
		)
	);

	if ( ! class_exists( 'WPBakeryShortCode_Porto_Schedule_Timeline_Item' ) ) {
		class WPBakeryShortCode_Porto_Schedule_Timeline_Item extends WPBakeryShortCode {
		}
	}
}
