<?php

// Porto Masonry Item
add_action( 'vc_after_init', 'porto_load_grid_item_shortcode' );

function porto_load_grid_item_shortcode() {
	$custom_class = porto_vc_custom_class();

	vc_map(
		array(
			'name'        => 'Porto ' . __( 'Masonry Item', 'porto-functionality' ),
			'base'        => 'porto_grid_item',
			'category'    => __( 'Porto', 'porto-functionality' ),
			'description' => __( 'Masonry Grid with any elements', 'porto-functionality' ),
			'icon'        => PORTO_WIDGET_URL . 'grid-container.png',
			'class'       => 'porto-wpb-widget vc_col-sm-12 vc_column',
			'as_parent'   => array( 'except' => 'porto_grid_item' ),
			'as_child'    => array( 'only' => 'porto_grid_container' ),
			'controls'    => 'full',
			//'is_container' => true,
			'js_view'     => 'VcColumnView',
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Width', 'porto-functionality' ),
					'param_name'  => 'width',
					'description' => __( 'This param works for only custom masonry layout and doesn\'t work for predefined grid layouts', 'porto-functionality' ),
				),
				$custom_class,
			),
		)
	);

	if ( ! class_exists( 'WPBakeryShortCode_Porto_Grid_Item' ) ) {
		class WPBakeryShortCode_Porto_Grid_Item extends WPBakeryShortCodesContainer {
		}
	}
}
