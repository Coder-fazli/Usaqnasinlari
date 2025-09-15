<?php
$output = $prefix = $text = $suffix = $display = $type = $link = $btn_size = $btn_skin = $btn_context = $popover_title = $popover_text = $popover_position = $popover_trigger = $popover_skin = $animation_type = $animation_duration = $animation_delay = $el_class = '';
extract(
	shortcode_atts(
		array(
			'prefix'               => '',
			'text'                 => '',
			'suffix'               => '',
			'display'              => '',
			'type'                 => '',
			'link'                 => '',
			'btn_size'             => '',
			'btn_skin'             => 'custom',
			'btn_context'          => '',
			'popover_title'        => '',
			'popover_text'         => '',
			'popover_position'     => 'top',
			'popover_trigger'      => 'click',
			'popover_skin'         => 'custom',
			'animation_type'       => '',
			'animation_duration'   => 1000,
			'animation_delay'      => 0,
			'animation_reveal_clr' => '',
			'el_class'             => '',
		),
		$atts
	)
);

global $porto_settings_optimize;
wp_enqueue_script( 'bootstrap-popover', PORTO_SHORTCODES_URL . 'assets/js/bootstrap-popover.min.js', array( 'jquery-core' ), PORTO_FUNC_VERSION, true );

$el_class = porto_shortcode_extract_class( $el_class );

if ( 'block' == $display ) {
	$el_class .= ' wpb_content_element';
} else {
	$el_class .= ' inline';
}

if ( 'custom' != $popover_skin ) {
	$el_class .= ' popover-' . $popover_skin;
}

$output = '<div class="porto-popover ' . esc_attr( $el_class ) . '"';
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

$output .= $prefix;

if ( 'btn' == $type || 'btn-link' == $type ) {
	$btn_class = 'btn';
	if ( $btn_size ) {
		$btn_class .= ' btn-' . $btn_size;
	}
	if ( 'custom' != $btn_skin ) {
		$btn_class .= ' btn-' . $btn_skin;
	}
	if ( $btn_context ) {
		$btn_class .= ' btn-' . $btn_context;
	}
	if ( 'custom' == $btn_skin && ! $btn_context ) {
		$btn_class .= ' btn-default';
	}
	if ( 'btn' == $type ) {
		$output .= ' <button type="button" data-toggle="popover" title="' . esc_attr( $popover_title ) . '" data-bs-content="' . esc_attr( $popover_text ) . '" data-bs-placement="' . esc_attr( $popover_position ) . '" class="' . esc_attr( $btn_class ) . '" data-trigger="' . esc_attr( $popover_trigger ) . '">';
		$output .= wp_kses_post( $text );
		$output .= '</button> ';
	} else {
		$output .= ' <a href="' . ( ! $link ? 'javascript:;' : esc_url( $link ) ) . '" data-toggle="popover" title="' . esc_attr( $popover_title ) . '" data-bs-content="' . esc_attr( $popover_text ) . '" data-bs-placement="' . esc_attr( $popover_position ) . '" class="' . esc_attr( $btn_class ) . '" data-trigger="' . esc_attr( $popover_trigger ) . '">';
		$output .= wp_kses_post( $text );
		$output .= '</a> ';
	}
} else {
	$output .= ' <a href="' . ( ! $link ? 'javascript:;' : esc_url( $link ) ) . '" data-toggle="popover" title="' . esc_attr( $popover_title ) . '" data-bs-content="' . esc_attr( $popover_text ) . '" data-bs-placement="' . esc_attr( $popover_position ) . '" data-trigger="' . esc_attr( $popover_trigger ) . '">';
	$output .= wp_kses_post( $text );
	$output .= '</a> ';
}

$output .= $suffix;

$output .= '</div>';

echo porto_filter_output( $output );
