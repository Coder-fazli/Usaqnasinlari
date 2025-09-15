<?php
// Porto Events
add_action( 'vc_after_init', 'porto_load_events_shortcode' );

function porto_load_events_shortcode() {
	vc_map(
		array(
			'name'        => 'Porto ' . __( 'Events', 'porto-functionality' ),
			'base'        => 'porto_events',
			'category'    => __( 'Porto', 'porto-functionality' ),
			'description' => __( 'Show events by beautiful layouts as portfolio', 'porto-functionality' ),
			'icon'        => PORTO_WIDGET_URL . 'events.png',
			'class'       => 'porto-wpb-widget',
			'params'      => array(
				array(
					'type'       => 'porto_param_heading',
					'param_name' => 'notice_wrong_data',
					'text'       => __( 'This element was deprecated in 6.3.0. Please use Posts Grid Widget instead.', 'porto-functionality' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Event Type', 'porto-functionality' ),
					'param_name' => 'event_type',
					'value'      => array(
						__( 'Default', 'porto-functionality' ) => '',
						__( 'Next', 'porto-functionality' ) => 'next',
						__( 'Upcoming', 'porto-functionality' ) => 'upcoming',
						__( 'Past', 'porto-functionality' ) => 'past',
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number of Events', 'porto-functionality' ),
					'param_name' => 'event_numbers',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Skip Number of Events', 'porto-functionality' ),
					'description' => __( 'Controls how many upcoming events is to be skipped.', 'porto-functionality' ),
					'param_name'  => 'event_skip',
					'dependency'  => array(
						'element' => 'event_type',
						'value'   => array( 'upcoming' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Numbers of Columns', 'porto-functionality' ),
					'param_name' => 'event_column',
					'value'      => array(
						__( '1', 'porto-functionality' ) => '1',
						__( '2', 'porto-functionality' ) => '2',
					),
					'dependency' => array(
						'element' => 'event_type',
						'value'   => array( 'upcoming', 'past', 'next' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Display Countdown', 'porto-functionality' ),
					'param_name' => 'event_countdown',
					'value'      => array(
						__( 'Yes', 'porto-functionality' ) => 'show',
						__( 'No', 'porto-functionality' )  => 'hide',
					),
					'dependency' => array(
						'element' => 'event_type',
						'value'   => array( 'next' ),
					),
				),
				array(
					'type'        => 'porto_dimension',
					'heading'     => __( 'Margin', 'porto-functionality' ),
					'description' => __( 'Controls the margin space of the event wrapper.', 'porto-functionality' ),
					'param_name'  => 'event_margin',
					'selectors'   => array(
						'{{WRAPPER}} .custom-post-event' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
					),
					'dependency'  => array(
						'element'            => 'event_type',
						'value_not_equal_to' => 'next',
					),
					'group'       => __( 'Event', 'porto-functionality' ),
				),
				array(
					'type'        => 'porto_dimension',
					'heading'     => __( 'Padding', 'porto-functionality' ),
					'description' => __( 'Controls the padding space of the event caption.', 'porto-functionality' ),
					'param_name'  => 'event_padding',
					'selectors'   => array(
						'{{WRAPPER}} .thumb-info-caption' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
					),
					'dependency'  => array(
						'element' => 'event_type',
						'value'   => 'next',
					),
					'group'       => __( 'Event', 'porto-functionality' ),
				),
				array(
					'type'        => 'porto_dimension',
					'heading'     => __( 'Caption Text Padding', 'porto-functionality' ),
					'description' => __( 'Controls the padding space of the caption text.', 'porto-functionality' ),
					'param_name'  => 'event_caption_padding',
					'selectors'   => array(
						'{{WRAPPER}} .thumb-info-caption-text' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
					),
					'dependency'  => array(
						'element' => 'event_type',
						'value'   => 'next',
					),
					'group'       => __( 'Event', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_typography',
					'heading'    => __( 'Name', 'porto-functionality' ),
					'param_name' => 'name_font',
					'selectors'  => array(
						'{{WRAPPER}} h4 a',
					),
					'group'      => __( 'Event Name', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'name_color',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'selectors'  => array(
						'{{WRAPPER}} h4 a' => 'color: {{VALUE}} !important;',
					),
					'group'      => __( 'Event Name', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_dimension',
					'heading'    => __( 'Margin', 'porto-functionality' ),
					'param_name' => 'name_margin',
					'selectors'  => array(
						'{{WRAPPER}} h4' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
					),
					'group'      => __( 'Event Name', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_typography',
					'heading'    => __( 'Excerpt', 'porto-functionality' ),
					'param_name' => 'excerpt_font',
					'selectors'  => array(
						'{{WRAPPER}} p.post-excerpt',
					),
					'group'      => __( 'Excerpt', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'excerpt_color',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'selectors'  => array(
						'{{WRAPPER}} .post-excerpt' => 'color: {{VALUE}};',
					),
					'group'      => __( 'Excerpt', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_dimension',
					'heading'    => __( 'Margin', 'porto-functionality' ),
					'param_name' => 'excerpt_margin',
					'selectors'  => array(
						'{{WRAPPER}} .post-excerpt' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
					),
					'group'      => __( 'Excerpt', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_typography',
					'heading'    => __( 'Meta', 'porto-functionality' ),
					'param_name' => 'meta_font',
					'selectors'  => array(
						'{{WRAPPER}} .custom-event-infos',
					),
					'group'      => __( 'Meta', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'meta_color',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'selectors'  => array(
						'{{WRAPPER}} .custom-event-infos' => 'color: {{VALUE}};',
					),
					'group'      => __( 'Meta', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'meta_icon_color',
					'heading'    => __( 'Icon Color', 'porto-functionality' ),
					'selectors'  => array(
						'{{WRAPPER}} .custom-event-infos li i' => 'color: {{VALUE}};',
					),
					'group'      => __( 'Meta', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_dimension',
					'heading'    => __( 'Margin', 'porto-functionality' ),
					'param_name' => 'meta_margin',
					'selectors'  => array(
						'{{WRAPPER}} .custom-event-infos' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
					),
					'group'      => __( 'Meta', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_typography',
					'heading'    => __( 'Button Typography', 'porto-functionality' ),
					'param_name' => 'read_more_font',
					'selectors'  => array(
						'{{WRAPPER}} .read-more',
					),
					'group'      => __( 'Read More', 'porto-functionality' ),
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'read_more_color',
					'heading'    => __( 'Color', 'porto-functionality' ),
					'selectors'  => array(
						'{{WRAPPER}} .read-more' => 'color: {{VALUE}};',
					),
					'group'      => __( 'Read More', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_dimension',
					'heading'    => __( 'Margin', 'porto-functionality' ),
					'param_name' => 'read_more_margin',
					'selectors'  => array(
						'{{WRAPPER}} .read-more' => 'margin-top:{{TOP}};margin-right:{{RIGHT}};margin-bottom:{{BOTTOM}};margin-left:{{LEFT}};display: block;',
					),
					'group'      => __( 'Read More', 'porto-functionality' ),
				),
				array(
					'type'       => 'porto_dimension',
					'heading'    => __( 'Margin', 'porto-functionality' ),
					'param_name' => 'countdown_margin',
					'selectors'  => array(
						'{{WRAPPER}} .custom-thumb-info-wrapper-box' => 'margin-top:{{TOP}}; margin-right:{{RIGHT}}; margin-bottom:{{BOTTOM}}; margin-left: {{LEFT}};',
					),
					'group'      => __( 'CountDown', 'porto-functionality' ),
					'dependency' => array(
						'element' => 'event_countdown',
						'value'   => 'show',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Extra class name', 'porto-functionality' ),
					'param_name'  => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'porto-functionality' ),
				),
			),
		)
	);

	if ( ! class_exists( 'WPBakeryShortCode_Porto_Events' ) ) {
		class WPBakeryShortCode_Porto_Events extends WPBakeryShortCode {
		}
	}
}
