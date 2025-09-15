<?php
/**
 * Function for Content Type
 */
if ( ! function_exists( 'array_insert_before' ) ) :
	function array_insert_before( $key, &$array, $new_key, $new_value ) {
		if ( array_key_exists( $key, $array ) ) {
			$new = array();
			foreach ( $array as $k => $value ) {
				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
				$new[ $k ] = $value;
			}
			return $new;
		}
		return $array;
	}
endif;


if ( ! function_exists( 'array_insert_after' ) ) :
	function array_insert_after( $key, &$array, $new_key, $new_value ) {
		if ( array_key_exists( $key, $array ) ) {
			$new = array();
			foreach ( $array as $k => $value ) {
				$new[ $k ] = $value;
				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
			}
			return $new;
		}
		return $array;
	}
endif;

if ( ! function_exists( 'porto_stringify_attributes' ) ) :
	function porto_stringify_attributes( $attributes ) {

		$atts = array();
		foreach ( $attributes as $name => $value ) {
			$atts[] = $name . '="' . esc_attr( $value ) . '"';
		}

		return implode( ' ', $atts );
	}
endif;

if ( ! function_exists( 'porto_filter_output' ) ) :
	function porto_filter_output( $output_escaped ) {
		return $output_escaped;
	}
endif;

if ( ! function_exists( 'porto_ct_layouts' ) ) :
	function porto_ct_layouts() {

		return array(
			'widewidth'          => esc_html__( 'Wide Width', 'porto-functionality' ),
			'wide-left-sidebar'  => esc_html__( 'Wide Left Sidebar', 'porto-functionality' ),
			'wide-right-sidebar' => esc_html__( 'Wide Right Sidebar', 'porto-functionality' ),
			'wide-both-sidebar'  => esc_html__( 'Wide Left & Right Sidebars', 'porto-functionality' ),
			'fullwidth'          => esc_html__( 'Without Sidebar', 'porto-functionality' ),
			'left-sidebar'       => esc_html__( 'Left Sidebar', 'porto-functionality' ),
			'right-sidebar'      => esc_html__( 'Right Sidebar', 'porto-functionality' ),
			'both-sidebar'       => esc_html__( 'Left & Right Sidebars', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_post_archive_layouts' ) ) :
	function porto_ct_post_archive_layouts() {

		return array(
			'full'        => array(
				'title' => __( 'Full', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_full.svg',
			),
			'large'       => array(
				'title' => __( 'Large', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_full.svg',
			),
			'large-alt'   => array(
				'title' => __( 'Large Alt', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_large_alt.svg',
			),
			'medium'      => array(
				'title' => __( 'Medium', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_medium.svg',
			),
			'grid'        => array(
				'title' => __( 'Grid', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_grid.svg',
			),
			'masonry'     => array(
				'title' => __( 'Masonry', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_masonry.svg',
			),
			'timeline'    => array(
				'title' => __( 'Timeline', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_timeline.svg',
			),
			'woocommerce' => array(
				'title' => __( 'Woocommerce', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_woocommerce.svg',
			),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_post_single_layouts' ) ) :
	function porto_ct_post_single_layouts() {

		return array(
			'full'        => array(
				'title' => __( 'Full', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_full.svg',
			),
			'large'       => array(
				'title' => __( 'Large', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_full.svg',
			),
			'large-alt'   => array(
				'title' => __( 'Large Alt', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_large_alt.svg',
			),
			'medium'      => array(
				'title' => __( 'Medium', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/post_medium.svg',
			),
			'woocommerce' => array(
				'title' => __( 'Woocommerce', 'porto-functionality' ),
				'img'   => PORTO_OPTIONS_URI . '/images/blog_woocommerce.svg',
			),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_portfolio_archive_layouts' ) ) :
	function porto_ct_portfolio_archive_layouts() {

		return array(
			'grid'     => array(
				'alt' => __( 'Grid', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_1.jpg',
			),
			'masonry'  => array(
				'alt' => __( 'Masonry', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_2.jpg',
			),
			'timeline' => array(
				'alt' => __( 'Timeline', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_3.jpg',
			),
			'full'     => array(
				'alt' => __( 'Full', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_6.jpg',
			),
			'large'    => array(
				'alt' => __( 'Large', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_5.jpg',
			),
			'medium'   => array(
				'alt' => __( 'Medium', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_archive_style_4.jpg',
			),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_portfolio_single_layouts' ) ) :
	function porto_ct_portfolio_single_layouts() {

		return array(
			'medium'      => array(
				'alt' => __( 'Medium Slider', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_1.jpg',
			),
			'large'       => array(
				'alt' => __( 'Large Slider', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_2.jpg',
			),
			'full'        => array(
				'alt' => __( 'Full Slider', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_3.jpg',
			),
			'gallery'     => array(
				'alt' => __( 'Gallery', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_4.jpg',
			),
			'carousel'    => array(
				'alt' => __( 'Carousel', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_5.jpg',
			),
			'medias'      => array(
				'alt' => __( 'Medias', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_6.jpg',
			),
			'full-video'  => array(
				'alt' => __( 'Full Width Video', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_7.jpg',
			),
			'masonry'     => array(
				'alt' => __( 'Masonry Images', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_8.jpg',
			),
			'full-images' => array(
				'alt' => __( 'Full Images', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_9.jpg',
			),
			'extended'    => array(
				'alt' => __( 'Extended', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/portfolio_single_style_10.jpg',
			),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_event_single_layouts' ) ) :
	function porto_ct_event_single_layouts() {

		return array(
			'medium'      => __( 'Medium Slider', 'porto-functionality' ),
			'large'       => __( 'Large Slider', 'porto-functionality' ),
			'full'        => __( 'Full Slider', 'porto-functionality' ),
			'gallery'     => __( 'Gallery', 'porto-functionality' ),
			'gallery'     => __( 'Gallery', 'porto-functionality' ),
			'carousel'    => __( 'Carousel', 'porto-functionality' ),
			'medias'      => __( 'Medias', 'porto-functionality' ),
			'full-video'  => __( 'Full Width Video', 'porto-functionality' ),
			'masonry'     => __( 'Masonry Images', 'porto-functionality' ),
			'full-images' => __( 'Full Images', 'porto-functionality' ),
			'extended'    => __( 'Extended', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_sidebars' ) ) :
	function porto_ct_sidebars() {

		global $wp_registered_sidebars;
		$sidebar_options = array();

		if ( ! empty( $wp_registered_sidebars ) ) {

			foreach ( $wp_registered_sidebars as $sidebar ) {
				if ( ! in_array( $sidebar['id'], array( 'content-bottom-1', 'content-bottom-2', 'content-bottom-3', 'content-bottom-4', 'footer-top', 'footer-column-1', 'footer-column-2', 'footer-column-3', 'footer-column-4', 'footer-bottom' ) ) ) {
					$sidebar_options[ esc_html( $sidebar['id'] ) ] = esc_html( $sidebar['name'] );
				}
			}
		};
		return $sidebar_options;
	}
endif;

if ( ! function_exists( 'porto_ct_banner_pos' ) ) :
	function porto_ct_banner_pos() {

		return array(
			''              => __( 'Default', 'porto-functionality' ),
			'before_header' => __( 'Before Header', 'porto-functionality' ),
			'below_header'  => __( 'Behind Header', 'porto-functionality' ),
			'fixed'         => __( 'Fixed', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_banner_type' ) ) :
	function porto_ct_banner_type() {

		return array(
			'rev_slider'    => __( 'Revolution Slider', 'porto-functionality' ),
			'master_slider' => __( 'Master Slider', 'porto-functionality' ),
			'banner_block'  => __( 'Banner Block', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_header_view' ) ) :
	function porto_ct_header_view( $theme_option = false ) {
		if ( $theme_option ) {
			return array(
				'default' => array(
                    'label' => __( 'Default', 'porto-functionality' ),
                    'hint'  => array(
                        'content' => esc_html( '<img src="' . PORTO_HINT_URL . 'header-view.jpg"/>' ),
                    ),
                ),
				'fixed'   => array(
                    'label' => __( 'Fixed', 'porto-functionality' ),
                    'hint'  => array(
                        'content' => esc_html( '<img src="' . PORTO_HINT_URL . 'header-view-fixed.jpg"/>' ),
                    ),
                ),
			);
		} else {
			return array(
				'default' => __( 'Default', 'porto-functionality' ),
				'fixed'   => __( 'Fixed', 'porto-functionality' ),
			);
		}
	}
endif;

if ( ! function_exists( 'porto_ct_footer_view' ) ) :
	function porto_ct_footer_view() {
		return array(
			''       => __( 'Default', 'porto-functionality' ),
			'simple' => __( 'Simple', 'porto-functionality' ),
			'fixed'  => __( 'Simple and Fixed', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_master_sliders' ) ) :
	global $porto_master_sliders, $porto_check_master_sliders;
	$porto_master_sliders = null;

	$porto_check_master_sliders = false;

	function porto_ct_master_sliders() {

		global $wpdb, $porto_master_sliders, $porto_check_master_sliders;
		if ( $porto_master_sliders ) {
			return $porto_master_sliders;
		}
		if ( ! class_exists( 'Master_Slider' ) ) {
			return array();
		}
		if ( ! $porto_check_master_sliders ) {

			$table_name = $wpdb->prefix . 'masterslider_sliders';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) == $table_name ) {
				$sliders        = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . esc_sql( $table_name ) . ' WHERE status=%s ORDER BY ID DESC', 'published' ) );
				$master_sliders = array();

				if ( ! empty( $sliders ) ) {
					foreach ( $sliders as $slider ) {
						$master_sliders[ $slider->ID ] = '#' . $slider->ID . ': ' . $slider->title;
					}
				}
				$porto_master_sliders = $master_sliders;
			}
			$porto_check_master_sliders = true;
		}
		return $porto_master_sliders;
	}
endif;

if ( ! function_exists( 'porto_ct_rev_sliders' ) ) :
	global $porto_rev_sliders, $porto_check_rev_sliders;
	$porto_rev_sliders = null;

	$porto_check_rev_sliders = false;
	function porto_ct_rev_sliders() {

		global $wpdb, $porto_rev_sliders, $porto_check_rev_sliders;
		if ( $porto_rev_sliders ) {
			return $porto_rev_sliders;
		}
		if ( ! class_exists( 'RevSliderFront' ) ) {
			return array();
		}
		if ( ! $porto_check_rev_sliders ) {
			$table_name = $wpdb->prefix . 'revslider_sliders';

			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) == $table_name ) {
				$sliders     = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( $table_name ) );
				$rev_sliders = array();
				if ( ! empty( $sliders ) ) {
					foreach ( $sliders as $slider ) {
						$rev_sliders[ $slider->alias ] = '#' . $slider->id . ': ' . $slider->title;
					}
				}
				$porto_rev_sliders = $rev_sliders;
			}
			$porto_check_rev_sliders = true;
		}
		return $porto_rev_sliders;
	}
endif;

if ( ! function_exists( 'porto_ct_related_product_columns' ) ) :
	function porto_ct_related_product_columns() {

		return array(
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		);
	}
endif;

if ( ! function_exists( 'porto_ct_product_columns' ) ) :
	function porto_ct_product_columns() {

		return array(
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => __( '7 (widthout sidebar)', 'porto-functionality' ),
			'8' => __( '8 (widthout sidebar)', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_category_addlinks_pos' ) ) :
	function porto_ct_category_addlinks_pos() {

		$layouts = porto_sh_commons( 'products_addlinks_pos' );
		$result  = array();
		foreach ( $layouts as $key => $value ) {
			$result[ $value ] = $key;
		}
		return $result;
	}
endif;

if ( ! function_exists( 'porto_ct_bg_repeat' ) ) :
	function porto_ct_bg_repeat() {

		return array(
			''          => __( 'Default', 'porto-functionality' ),
			'no-repeat' => __( 'No Repeat', 'porto-functionality' ),
			'repeat'    => __( 'Repeat All', 'porto-functionality' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'porto-functionality' ),
			'repeat-y'  => __( 'Repeat Vertically', 'porto-functionality' ),
			'inherit'   => __( 'Inherit', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_bg_size' ) ) :
	function porto_ct_bg_size() {

		return array(
			''        => __( 'Default', 'porto-functionality' ),
			'inherit' => __( 'Inherit', 'porto-functionality' ),
			'cover'   => __( 'Cover', 'porto-functionality' ),
			'contain' => __( 'Contain', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_bg_attachment' ) ) :
	function porto_ct_bg_attachment() {

		return array(
			''        => __( 'Default', 'porto-functionality' ),
			'fixed'   => __( 'Fixed', 'porto-functionality' ),
			'scroll'  => __( 'Scroll', 'porto-functionality' ),
			'inherit' => __( 'Inherit', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_bg_position' ) ) :
	function porto_ct_bg_position() {

		return array(
			''              => __( 'Default', 'porto-functionality' ),
			'left top'      => __( 'Left Top', 'porto-functionality' ),
			'left center'   => __( 'Left Center', 'porto-functionality' ),
			'left bottom'   => __( 'Left Bottom', 'porto-functionality' ),
			'center top'    => __( 'Center Top', 'porto-functionality' ),
			'center center' => __( 'Center Center', 'porto-functionality' ),
			'center bottom' => __( 'Center Bottom', 'porto-functionality' ),
			'right top'     => __( 'Right Top', 'porto-functionality' ),
			'right center'  => __( 'Right Center', 'porto-functionality' ),
			'right bottom'  => __( 'Right Bottom', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_category_view_mode' ) ) :
	function porto_ct_category_view_mode( $theme_option = false ) {
		if ( $theme_option ) {
			return array(
				''     => __( 'Default', 'porto' ),
				'grid' => array(
					'label' => __( 'Grid', 'porto' ),
					'hint'  => array(
						'content' => esc_html( '<img src="' . PORTO_HINT_URL . 'category-view-mode-grid.jpg"/>' ),
					),
				),
				'list' => array(
					'label' => __( 'List', 'porto' ),
					'hint'  => array(
						'content' => esc_html( '<img src="' . PORTO_HINT_URL . 'category-view-mode-list.jpg"/>' ),
					),
				),
			);
		} else {
			return array(
				''     => __( 'Default', 'porto-functionality' ),
				'grid' => __( 'Grid', 'porto-functionality' ),
				'list' => __( 'List', 'porto-functionality' ),
			);
			
		}
	}
endif;

if ( ! function_exists( 'porto_ct_categories_orderby' ) ) :
	function porto_ct_categories_orderby() {

		return array(

			'id'    => __( 'ID', 'porto-functionality' ),
			'name'  => __( 'Name', 'porto-functionality' ),
			'slug'  => __( 'Slug', 'porto-functionality' ),
			'count' => __( 'Count', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_categories_order' ) ) :
	function porto_ct_categories_order() {

		return array(
			'asc'  => __( 'Asc', 'porto-functionality' ),
			'desc' => __( 'Desc', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_categories_sort_pos' ) ) :
	function porto_ct_categories_sort_pos() {

		return array(
			'content'     => array(
				'title' => __( 'In Content', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/filter-content.svg',
			),
			'breadcrumbs' => array(
				'title' => __( 'In Breadcrumbs', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/filter-breadcrumb.svg',
			),
			'sidebar'     => array(
				'title' => __( 'In Sidebar', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/filter-sidebar.svg',
			),
			'hide'        => array(
				'title' => __( 'Hide', 'porto-functionality' ),
				'img' => PORTO_OPTIONS_URI . '/images/filter-hide.svg',
			),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_share_options' ) ) :
	function porto_ct_share_options() {

		return array(
			''    => __( 'Default', 'porto-functionality' ),
			'yes' => __( 'Yes', 'porto-functionality' ),
			'no'  => __( 'No', 'porto-functionality' ),
		);

	}
endif;

if ( ! function_exists( 'porto_ct_show_options' ) ) :
	function porto_ct_show_options() {

		return array(
			''    => __( 'Default', 'porto-functionality' ),
			'yes' => __( 'Show', 'porto-functionality' ),
			'no'  => __( 'Hide', 'porto-functionality' ),
		);

	}
endif;

if ( ! function_exists( 'porto_ct_enable_options ' ) ) :
	function porto_ct_enable_options() {

		return array(
			''    => __( 'Default', 'porto-functionality' ),
			'yes' => __( 'Enable', 'porto-functionality' ),
			'no'  => __( 'Disable', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_slideshow_types' ) ) :
	function porto_ct_slideshow_types() {

		return array(
			'images' => __( 'Featured Images', 'porto-functionality' ),
			'video'  => __( 'Video & Audio or Content', 'porto-functionality' ),
			'none'   => __( 'None', 'porto-functionality' ),
		);
	}
endif;

if ( ! function_exists( 'porto_ct_post_media_types' ) ) :
	function porto_ct_post_media_types() {

		return array(
			'images' => __( 'Slideshow', 'porto-functionality' ),
			'grid'   => __( 'Grid Images', 'porto-functionality' ),
			'video'  => __( 'Video & Audio or Content', 'porto-functionality' ),
			'none'   => __( 'None', 'porto-functionality' ),
		);
	}
endif;
