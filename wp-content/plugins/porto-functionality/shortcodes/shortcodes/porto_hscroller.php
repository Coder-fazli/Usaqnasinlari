<?php
/**
 * Porto Horizontal Scroller
 *
 * @since 2.6.0
 */
add_action( 'vc_after_init', 'porto_load_hscroller_shortcode' );

function porto_load_hscroller_shortcode() {
	$animation_type     = porto_vc_animation_type();
	$animation_duration = porto_vc_animation_duration();
	$animation_delay    = porto_vc_animation_delay();
	$animation_reveal_clr = porto_vc_animation_reveal_clr();
	$custom_class       = porto_vc_custom_class();

	vc_map(
		array(
			'name'            => 'Porto ' . __( 'Horizontal Scroller', 'porto-functionality' ),
			'base'            => 'porto_hscroller',
			'category'        => __( 'Porto', 'porto-functionality' ),
			'description'     => __( 'Multiple items horizontal scroll', 'porto-functionality' ),
			'icon'            => PORTO_WIDGET_URL . 'horizontal-scroll.gif',
			'class'           => 'porto-wpb-widget',
			'content_element' => true,
			'as_parent'       => array( 'except' => 'porto_hscroller' ),
			'is_container'    => true,
			'controls'        => 'full',
			'js_view'         => 'VcColumnView',
			'params'          => array(
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Vertical Alignment', 'porto-functionality' ),
					'description' => __( 'Controls item\'s alignment. Choose from Top, Middle, Bottom.', 'porto-functionality' ),
					'param_name'  => 'h_scroller_align',
					'value'       => array(
						__( 'Top', 'porto-functionality' ) => 'flex-start',
						__( 'Middle', 'porto-functionality' ) => 'center',
						__( 'Bottom', 'porto-functionality' ) => 'flex-end',
					),
					'std'         => 'center',
					'selectors'   => array(
						'{{WRAPPER}} .horizontal-scroller-items' => 'align-items: {{VALUE}}',
					),
				),
				array(
					'type'        => 'porto_dimension',
					'heading'     => __( 'Items Spacing', 'porto-functionality' ),
					'description' => __( 'Controls the item\'s spacing.', 'porto-functionality' ),
					'param_name'  => 'items_spacing',
					'responsive'  => true,
					'selectors'   => array(
						'{{WRAPPER}} .horizontal-scroller-items > *' => 'padding-top:{{TOP}};padding-right:{{RIGHT}};padding-bottom:{{BOTTOM}};padding-left:{{LEFT}};',
					),
				),
				array(
					'type'        => 'number',
					'heading'     => __( 'Item Count', 'porto-functionality' ),
					'description' => __( 'Controls item counts.', 'porto-functionality' ),
					'param_name'  => 'scroller_count_lg',
					'min'         => 1,
					'max'         => 6,
					'step'        => 1,
					'std'         => 3,
				),
				array(
					'type'        => 'number',
					'heading'     => __( 'Item Count( < 992px )', 'porto-functionality' ),
					'description' => __( 'Controls item counts on mobile.', 'porto-functionality' ),
					'param_name'  => 'scroller_count_md',
					'min'         => 1,
					'max'         => 6,
					'step'        => 1,
					'std'         => 1,
				),
				array(
					'type'        => 'porto_dimension',
					'heading'     => __( 'Scroller Padding', 'porto-functionality' ),
					'description' => __( 'Controls padding of scroller wrapper.', 'porto-functionality' ),
					'param_name'  => 'scroller_padding',
					'responsive'  => true,
					'selectors'   => array(
						'{{WRAPPER}} .horizontal-scroller-scroll' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}}',
					),
				),
				$custom_class,
				$animation_type,
				$animation_duration,
				$animation_delay,
				$animation_reveal_clr,
			),
		)
	);

	if ( ! class_exists( 'WPBakeryShortCode_Porto_Hscroller' ) ) {
		class WPBakeryShortCode_Porto_Hscroller extends WPBakeryShortCodesContainer {
		}
	}
}
