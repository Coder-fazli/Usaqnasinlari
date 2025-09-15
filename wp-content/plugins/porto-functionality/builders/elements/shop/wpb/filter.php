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
	$el_class .= apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_design'], ' ' ), 'porto_sb_filter', $atts );
}

global $porto_settings;
if ( ( function_exists('porto_is_elementor_preview') && porto_is_elementor_preview() ) || ( function_exists('porto_vc_is_inline') && porto_vc_is_inline() ) ) {
	if ( empty( $porto_settings['product-archive-filter-layout'] ) ) {
		echo sprintf( esc_html__( '%1$sFilter toggle isn\'t available for%2$sDefault Filter Layout%3$s%4$s.', 'porto-functionality' ), '<span>', '<a class="ps-1 porto-setting-link" href="' . porto_get_theme_option_url( 'product-archive-layout' ) . '" target="_blank">', '</a>', '</span>' );
		return;
	} elseif ( 'horizontal2' != $porto_settings['product-archive-filter-layout'] && ! in_array( $porto_settings['product-archive-layout'], porto_options_sidebars() ) ) {
		echo sprintf( esc_html__( '%1$sFilter toggle is available for%2$sLayout with Sidebar%3$s%4$s.', 'porto-functionality' ), '<span>', '<a class="ps-1 porto-setting-link" href="' . porto_get_theme_option_url( 'product-archive-filter-layout' ) . '" target="_blank">', '</a>', '</span>' );
		return;
	}
}
porto_woocommerce_output_horizontal_filter( trim( $el_class ) );
