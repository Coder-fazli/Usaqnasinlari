<?php

if ( isset( $atts['spacing'] ) && ( $atts['spacing'] || '0' == $atts['spacing'] ) ) {
	echo porto_filter_output( $atts['selector'] ) . '{margin-' . ( is_rtl() ? 'right' : 'left' ) . ':' . esc_html( $atts['spacing'] ) . '}';
}
if ( isset( $atts['font_settings'], $atts['font_settings']['color'] ) && $atts['font_settings']['color'] ) {
	echo porto_filter_output( $atts['selector'] ) . '{--add-to-wishlist-icon-color:' . esc_html( $atts['font_settings']['color'] ) . '}';
}
if ( isset( $atts['style_options'], $atts['style_options']['hover'], $atts['style_options']['hover']['color'] ) && $atts['style_options']['hover']['color'] ) {
	echo porto_filter_output( $atts['selector'] ) . ':hover{--add-to-wishlist-icon-color:' . esc_html( $atts['style_options']['hover']['color'] ) . '}';
}
