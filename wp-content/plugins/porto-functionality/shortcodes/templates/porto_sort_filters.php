<?php
$output = $container = $style = $align = $animation_type = $animation_duration = $animation_delay = $el_class = '';
extract(
	shortcode_atts(
		array(
			'container'            => '',
			'style'                => '',
			'align'                => '',
			'animation_type'       => '',
			'animation_duration'   => 1000,
			'animation_delay'      => 0,
			'animation_reveal_clr' => '',
			'el_class'             => '',
		),
		$atts
	)
);

wp_enqueue_script( 'isotope' );
wp_enqueue_script( 'porto-sort-filters' );
$el_class = porto_shortcode_extract_class( $el_class );

$output = '<div class="porto-sort-filters ' . esc_attr( $el_class ) . '"';
if ( $animation_type ) {
	$output .= ' data-appear-animation="' . esc_attr( $animation_type ) . '"';
	if ( $animation_delay ) {
		$output .= ' data-appear-animation-delay="' . esc_attr( $animation_delay ) . '"';
	}
	if ( $animation_duration && 1000 != $animation_duration ) {
		$output .= ' data-appear-animation-duration="' . esc_attr( $animation_duration ) . '"';
	}
	if ( false !== strpos( $animation_type, 'revealDir' ) ) {
		$output .= ' data-animation-reveal-clr="' . ( ! empty( $animation_reveal_clr ) ? esc_attr( $animation_reveal_clr ) : '' ) . '"';
	}
}
$output .= '>';

$output .= '<ul data-sort-id="' . esc_attr( $container ) . '" class="nav nav-pills sort-source ' .
	( $style ? 'sort-source-' . $style . ( $align ? ' text-' . $align : '' ) : ( $align ? ' nav-pills-' . $align : '' ) ) . '">';

$output .= do_shortcode( $content );

$output .= '</ul>';
$output .= '</div>';

echo porto_filter_output( $output );
