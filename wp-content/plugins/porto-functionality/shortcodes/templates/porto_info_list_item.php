<?php

$icon_type = $list_icon = $list_icon_img = $desc_font_size = $el_class = '';
extract(
	shortcode_atts(
		array(
			'icon_type'            => 'fontawesome',
			'list_icon'            => '',
			'list_icon_simpleline' => '',
			'list_icon_porto'      => '',
			'list_icon_img'        => '',
			'desc_font_size'       => '',
			'el_class'             => '',
		),
		$atts
	)
);

switch ( $icon_type ) {
	case 'simpleline':
		$list_icon = $list_icon_simpleline;
		break;
	case 'porto':
		$list_icon = $list_icon_porto;
		break;
}

if ( ! empty( $shortcode_class ) ) {
	$el_class .= $shortcode_class;
}

$html  = '';
$html .= '<li class="porto-info-list-item' . ( $el_class ? ' ' . esc_attr( $el_class ) : '' ) . '">';
if ( 'image' != $icon_type && $list_icon ) {
	$html .= '<i class="porto-info-icon ' . esc_attr( $list_icon ) . '"></i>';
} elseif ( 'image' == $icon_type && $list_icon_img ) {
	$attachment = wp_get_attachment_image_src( $list_icon_img, 'full' );
	if ( isset( $attachment ) ) {
		$alt_text = get_post_meta( $list_icon_img, '_wp_attachment_image_alt', true );
		$html    .= '<img class="porto-info-icon" src="' . esc_url( $attachment[0] ) . '" width="' . esc_attr( $attachment[1] ) . '" height="' . esc_attr( $attachment[2] ) . '" alt="' . esc_attr( $alt_text ) . '" />';
	}
}
	$html .= '<div class="porto-info-list-item-desc">';
	$html .= do_shortcode( $content );
	$html .= '</div>';
$html     .= '</li>';

echo porto_filter_output( $html );
