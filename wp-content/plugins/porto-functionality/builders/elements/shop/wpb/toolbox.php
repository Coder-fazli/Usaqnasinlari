<?php
extract(
	shortcode_atts(
		array(
			'el_class' => '',
		),
		$atts
	)
);
if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && isset( $atts['css_design'] ) ) {
	$el_class .= apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_design'], ' ' ), 'porto_sb_toolbox', $atts );
}

echo '<div class="shop-loop-' . ( apply_filters( 'porto_sb_products_rendered', false ) ? 'after' : 'before' ) . ' shop-builder' . ( $el_class ? ' ' . esc_attr( trim( $el_class ) ) : '' ) . '">';
echo do_shortcode( $content );
echo '</div>';
