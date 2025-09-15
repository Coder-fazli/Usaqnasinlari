<?php
$template = porto_shortcode_template( 'porto_posts_grid' );
$preview = ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ) ||
( function_exists( 'vc_is_inline' ) && vc_is_inline() );


if ( $template && ( $preview || is_shop() || is_product_category() ) ) {
    $cur_pd = is_product_category() ? get_queried_object_id() : 0;
    if ( $preview ) {
        $cur_pd = 0;
    }
    $product_categories = woocommerce_get_product_subcategories( $cur_pd );
    if ( empty ( $atts ) ) {
        $atts = array();
    }
    $atts['source']     = 'terms';
    $atts['terms']      = array();
    $atts['tax']        = 'product_cat';
    if ( isset( $builder ) && 'elementor' == $builder ) {
        if ( is_array( $atts['count'] ) ) {
            if ( isset( $atts['count']['size'] ) ) {
                $atts['count'] = $atts['count']['size'];
            } else {
                $atts['count'] = '';
            }
        }
    }
    if ( ! empty ( $product_categories ) && is_array( $product_categories ) ) {
        foreach ( $product_categories as $category ) {
            $atts['terms'][] = $category->term_id;
        }
    }

    if ( ! empty ( $atts['terms'] ) ) {
        include $template;
    }
}
