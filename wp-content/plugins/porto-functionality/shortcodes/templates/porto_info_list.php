<?php

$icon_color = $font_size_icon = $el_class = '';
extract(
	shortcode_atts(
		array(
			'icon_color'     => '',
			'font_size_icon' => '',
			'el_class'       => '',
		),
		$atts
	)
);
$style = '';

$icon_arr = array();
if ( $icon_color ) {
	$style .= 'color: ' . esc_html( $icon_color ) . ';';
	$icon_arr['icon_color'] = $icon_color;
}
if ( $font_size_icon ) {
	$style .= 'font-size: ' . esc_html( $font_size_icon ) . 'px;';
	$icon_arr['font_size_icon'] = $font_size_icon;
}

$uid = 'porto-info-list' . hash( 'md5',  json_encode( $icon_arr ) );

if ( ! empty( $shortcode_class ) ) {
	$el_class .= $shortcode_class;
}
$html = '';
if ( $style && ! ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
	$html .= '<style>#' . esc_html( $uid ) . ' i { ' . $style . ' }</style>';
}
$html .= '<ul id="' . esc_attr( $uid ) . '" class="porto-info-list ' . esc_attr( $el_class ) . '">';
$html .= do_shortcode( $content );
$html .= '</ul>';

if ( $style && function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
	$html .= '<style>#' . esc_html( $uid ) . ' i { ' . $style . ' }</style>';
}

echo porto_filter_output( $html );
