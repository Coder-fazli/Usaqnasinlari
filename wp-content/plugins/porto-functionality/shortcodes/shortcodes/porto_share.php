<?php
// Porto Info List

add_action( 'vc_after_init', 'porto_load_share_shortcode' );

function porto_load_share_shortcode() {

	$custom_class       = porto_vc_custom_class();

	vc_map(
		array(
			'name'                    => __( 'Porto Share', 'porto-functionality' ),
			'base'                    => 'porto_share',
			'icon'                    => PORTO_WIDGET_URL . 'share.png',
			'class'                   => 'porto-wpb-widget porto_share',
			'category'                => __( 'Porto', 'porto-functionality' ),
			'description'             => __( 'Display social share links', 'porto-functionality' ),
			'show_settings_on_create' => false,
		)
	);

	class WPBakeryShortCode_porto_share extends WPBakeryShortCode {
	}
}
