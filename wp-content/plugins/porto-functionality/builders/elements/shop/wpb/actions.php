<?php

extract(
	shortcode_atts(
		array(
			'action'   => '',
			'el_class' => '',
		),
		$atts
	)
);

if ( $action ) {
	if ( ! empty( $shortcode_class ) ) {
		$el_class = $shortcode_class . ' ' . $el_class;
	}

	if ( $el_class ) {
		echo '<div class="' . esc_attr( trim( $el_class ) ) . '">';
	}

	if ( function_exists( 'vc_is_inline' ) && vc_is_inline() && function_exists( 'wc_get_loop_prop' ) ) {
		$paginated = wc_get_loop_prop( 'is_paginated' );
		wc_set_loop_prop( 'is_paginated', true );
		$total = wc_get_loop_prop( 'total', 0 );
		wc_set_loop_prop( 'total', 10 );
		$total_pages = wc_get_loop_prop( 'total_pages', 0 );
		wc_set_loop_prop( 'total_pages', 2 );
	}

	do_action( $action );

	if ( isset( $paginated ) ) {
		wc_set_loop_prop( 'is_paginated', $paginated );
	}
	if ( isset( $total ) ) {
		wc_set_loop_prop( 'total', $total );
	}
	if ( isset( $total_pages ) ) {
		wc_set_loop_prop( 'total_pages', $total_pages );
	}
	if ( $el_class ) {
		echo '</div>';
	}
}
