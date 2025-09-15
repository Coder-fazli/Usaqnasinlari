<?php

// Porto Woo Archive Category for shop page
add_filter( 'vc_autocomplete_porto_archive_category_builder_id_callback', 'builder_id_callback' );
add_filter( 'vc_autocomplete_porto_archive_category_builder_id_render', 'builder_id_render' );
add_action( 'vc_after_init', 'porto_load_archive_categories_shortcode' );

function porto_load_archive_categories_shortcode() {
    vc_map(
        array(
            'name'        => __( 'Shop Page Subcategories', 'porto-functionality' ),
            'base'        => 'porto_archive_category',
            'icon'        => PORTO_WIDGET_URL . 'shop-subcat.png',
            'class'       => 'porto-wpb-widget',
            'category'    => __( 'Shop Builder', 'porto-functionality' ),
            'description' => __( 'In Shop page, show the product categories.', 'porto-functionality' ),
            'params'      => array_merge(
                array(
                    array(
                        'type'       => 'porto_param_heading',
                        'param_name' => 'posts_layout',
                        'text'       => __( 'Posts Selector', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'autocomplete',
                        'heading'     => __( 'Post Layout', 'porto-functionality' ),
                        'param_name'  => 'builder_id',
                        'settings'    => array(
                            'multiple'      => false,
                            'sortable'      => true,
                            'unique_values' => true,
                        ),
                        /* translators: starting and end A tags which redirects to edit page */
                        'description' => sprintf( __( 'Please select a saved Post Layout template which was built using post type builder. Please create a new Post Layout template in %1$sPorto Templates Builder%2$s. If you don\'t select, default template will be used.', 'porto-functionality' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . PortoBuilders::BUILDER_SLUG . '&' . PortoBuilders::BUILDER_TAXONOMY_SLUG . '=type' ) ) . '" target="_blank">', '</a>' ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'        => 'number',
                        'heading'     => __( 'Count (per page)', 'porto-functionality' ),
                        'description' => __( 'Leave blank if you use default value.', 'porto-functionality' ),
                        'param_name'  => 'count',
                        'admin_label' => true,
                    ),

                    array(
                        'type'        => 'dropdown',
                        'heading'     => __( 'Order way', 'porto-functionality' ),
                        'param_name'  => 'order',
                        'value'       => porto_vc_woo_order_way(),
                        /* translators: %s: Wordpres codex page */
                        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'porto-functionality' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    ),
                    array(
                        'type'       => 'porto_param_heading',
                        'param_name' => 'posts_layout',
                        'text'       => __( 'Posts Layout', 'porto-functionality' ),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => __( 'View mode', 'porto-functionality' ),
                        'param_name'  => 'view',
                        'value'       => array(
                            __( 'Grid', 'porto-functionality' ) => '',
                            __( 'Grid - Creative', 'porto-functionality' ) => 'creative',
                            __( 'Masonry', 'porto-functionality' ) => 'masonry',
                            __( 'Slider', 'porto-functionality' ) => 'slider',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'        => 'porto_image_select',
                        'heading'     => __( 'Grid Layout', 'porto-functionality' ),
                        'description' => sprintf( esc_html__( 'Please %1$schange%2$s the %1$scount(per page) option%2$s as the number of creative grid items of the image above.', 'porto-functionality' ), '<span style="color: red">', '</span>' ),
                        'param_name'  => 'grid_layout',
                        'dependency'  => array(
                            'element' => 'view',
                            'value'   => array( 'creative' ),
                        ),
                        'std'        => '1',
                        'value'      => porto_sh_commons( 'masonry_layouts' ),
                    ),
                    array(
                        'type'       => 'number',
                        'heading'    => __( 'Grid Height (px)', 'porto-functionality' ),
                        'param_name' => 'grid_height',
                        'dependency' => array(
                            'element' => 'view',
                            'value'   => array( 'creative' ),
                        ),
                        'suffix'     => 'px',
                        'std'        => 600,
                    ),
                    array(
                        'type'        => 'number',
                        'heading'     => __( 'Column Spacing (px)', 'porto-functionality' ),
                        'description' => __( 'Leave blank if you use theme default value.', 'porto-functionality' ),
                        'param_name'  => 'spacing',
                        'suffix'      => 'px',
                        'std'         => '',
                        'selectors'   => array(
                            '{{WRAPPER}}' => '--porto-el-spacing: {{VALUE}}px;',
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Columns', 'porto-functionality' ),
                        'param_name' => 'columns',
                        'std'        => '4',
                        'value'      => porto_sh_commons( 'products_columns' ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Add Half Column', 'porto-functionality' ),
                        'param_name' => 'half_column',
                        'value'      => array(
                            __( 'Default', 'porto-functionality' ) => '',
                            __( 'Yes', 'porto-functionality' )     => 'yes',
                            __( 'No', 'porto-functionality' )      => 'no',
                        ),
                        'dependency' => array(
                            'element' => 'view',
                            'value'   => array( 'slider' ),
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Columns on tablet ( <= 991px )', 'porto-functionality' ),
                        'param_name' => 'columns_tablet',
                        'std'        => '',
                        'value'      => array(
                            __( 'Default', 'porto-functionality' ) => '',
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Add Half Column on tablet', 'porto-functionality' ),
                        'param_name' => 'half_column_tablet',
                        'value'      => array(
                            __( 'Default', 'porto-functionality' ) => '',
                            __( 'Yes', 'porto-functionality' )     => 'yes',
                            __( 'No', 'porto-functionality' )      => 'no',
                        ),
                        'dependency' => array(
                            'element' => 'view',
                            'value'   => array( 'slider' ),
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Columns on mobile ( <= 575px )', 'porto-functionality' ),
                        'param_name' => 'columns_mobile',
                        'std'        => '',
                        'value'      => array(
                            __( 'Default', 'porto-functionality' ) => '',
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Add Half Column on Mobile', 'porto-functionality' ),
                        'param_name' => 'half_column_mobile',
                        'value'      => array(
                            __( 'Default', 'porto-functionality' ) => '',
                            __( 'Yes', 'porto-functionality' )     => 'yes',
                            __( 'No', 'porto-functionality' )      => 'no',
                        ),
                        'dependency' => array(
                            'element' => 'view',
                            'value'   => array( 'slider' ),
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => __( 'Image Size', 'porto-functionality' ),
                        'param_name' => 'image_size',
                        'value'      => porto_sh_commons( 'image_sizes' ),
                        'std'        => '',
                        'dependency' => array(
                            'element'            => 'view',
                            'value_not_equal_to' => 'creative',
                        ),
                    ),
                    porto_vc_custom_class(),
                    array(
                        'type'       => 'porto_number',
                        'heading'    => __( 'Stage Padding', 'porto-functionality' ),
                        'param_name' => 'stage_padding',
                        'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_carousel-stage_padding.gif"/>',
                        'dependency' => array(
                            'element'  => 'enable_flick',
                            'is_empty' => true,
                        ),
                        'group'      => __( 'Slider Options', 'porto-functionality' ),
                    ),
                    array(
                        'type'             => 'css_editor',
                        'heading'          => __( 'CSS', 'porto-functionality' ),
                        'param_name'       => 'css_design',
                        'group'            => __( 'Design', 'porto-functionality' ),
                        'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
                    ),
                ),
                porto_vc_product_slider_fields( 'slider' )
            ),
        )
    );	
    if ( ! class_exists( 'WPBakeryShortCode_Porto_Archive_Category' ) ) {
		class WPBakeryShortCode_Porto_Archive_Category extends WPBakeryShortCode {
		}
	}
}
