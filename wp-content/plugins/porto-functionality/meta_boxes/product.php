<?php

// Meta Fields
if ( ! function_exists( 'porto_product_meta_fields' ) ) {
	function porto_product_meta_fields() {
		global $porto_settings;

		$custom_tabs_count = isset( $porto_settings['product-custom-tabs-count'] ) ? $porto_settings['product-custom-tabs-count'] : '2';
		$meta_fields       = array();
		if ( $custom_tabs_count ) {
			for ( $i = 0; $i < $custom_tabs_count; $i++ ) {
				$tab_priority = 40 + $i;
				$index        = $i + 1;

				// Custom Tab Title
				$meta_fields[ 'custom_tab_title' . $index ] = array(
					'name'  => 'custom_tab_title' . $index,
					'title' => sprintf( __( 'Custom Tab %d Title', 'porto-functionality' ), $index ),
					'type'  => 'text',
				);

				// Content Tab Content
				$meta_fields[ 'custom_tab_content' . $index ] = array(
					'name'  => 'custom_tab_content' . $index,
					'title' => sprintf( __( 'Custom Tab %d Content', 'porto-functionality' ), $index ),
					'type'  => 'editor',
				);

				// Content Tab Priority
				$meta_fields[ 'custom_tab_priority' . $index ] = array(
					'name'    => 'custom_tab_priority' . $index,
					'title'   => sprintf( __( 'Custom Tab %d Priority', 'porto-functionality' ), $index ),
					'desc'    => __( 'Input the custom tab priority. (Description: 10, Additional Information: 20, Reviews: 30, Default Global Tab: 60)', 'porto-functionality' ),
					'type'    => 'text',
					'default' => $tab_priority,
				);
			}
		}

		// Read More Link
		$meta_fields['product_more_link'] = array(
			'name'  => 'product_more_link',
			'title' => __( 'Read More Link in Catalog Mode', 'porto-functionality' ),
			'type'  => 'text',
		);

		return apply_filters( 'porto_product_meta_fields', $meta_fields );
	}
}

function porto_product_view_meta_fields() {
	$meta_fields = porto_ct_default_view_meta_fields( 'product' );
	return $meta_fields;
}

function porto_product_skin_meta_fields() {
	$meta_fields = porto_ct_default_skin_meta_fields();
	return $meta_fields;
}

// Show Meta Boxes
add_action( 'add_meta_boxes', 'porto_add_product_meta_boxes' );
if ( ! function_exists( 'porto_add_product_meta_boxes' ) ) {
	/**
	 * @todo 2.3.0 Legacy Mode
	 */
	function porto_add_product_meta_boxes() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}
		global $porto_settings;
		$screen = get_current_screen();
		if ( function_exists( 'add_meta_box' ) && $screen && 'post' == $screen->base && 'product' == $screen->id ) {
			add_meta_box( 'product-meta-box', __( 'Porto Product Options', 'porto-functionality' ), 'porto_product_meta_box', 'product', 'normal', 'high' );
			add_meta_box( 'view-meta-box', __( 'Porto View Options', 'porto-functionality' ), 'porto_product_view_meta_box', 'product', 'normal', 'low' );
			add_meta_box( 'video-meta-box', __( 'Porto Video Thumbnail', 'porto-functionality' ), 'porto_product_video_meta_box', 'product', 'normal', 'low' );
			// Product 360 degree meta box
			add_meta_box( 'product-360-meta-box', __( 'Porto 360 Degree Gallery', 'porto-functionality' ), 'porto_product_360_degree', 'product', 'side', 'low' );
			if ( $porto_settings['show-content-type-skin'] ) {
				add_meta_box( 'skin-meta-box', __( 'Porto Skin Options', 'porto-functionality' ), 'porto_product_skin_meta_box', 'product', 'normal', 'low' );
			}
		}
	}
}

function porto_product_meta_box() {
	$meta_fields = porto_product_meta_fields();
	porto_show_meta_box( $meta_fields );
}

function porto_product_view_meta_box() {
	$meta_fields = porto_product_view_meta_fields();
	porto_show_meta_box( $meta_fields );
}

function porto_product_skin_meta_box() {
	$meta_fields = porto_product_skin_meta_fields();
	porto_show_meta_box( $meta_fields );
}

// Save Meta Values
add_action( 'save_post', 'porto_save_product_meta_values' );
function porto_save_product_meta_values( $post_id ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'post' == $screen->base && 'product' == $screen->id ) {
		porto_save_meta_value( $post_id, porto_product_meta_fields() );
		porto_save_meta_value( $post_id, porto_product_view_meta_fields() );
		porto_save_meta_value( $post_id, porto_product_skin_meta_fields() );
		porto_save_meta_value( $post_id, porto_product_video_meta_box( false ) );
		porto_save_meta_value( $post_id, porto_product_360_degree( false ) );
	}
}

// Remove in default custom field meta box
add_filter( 'is_protected_meta', 'porto_product_protected_meta', 10, 3 );
function porto_product_protected_meta( $protected, $meta_key, $meta_type ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return $protected;
	}
	$screen = get_current_screen();
	if ( ! $protected && $screen && 'post' == $screen->base && 'product' == $screen->id ) {
		if ( array_key_exists( $meta_key, porto_product_meta_fields() )
			|| array_key_exists( $meta_key, porto_product_view_meta_fields() )
			|| array_key_exists( $meta_key, porto_product_skin_meta_fields() ) ) {
			$protected = true;
		}
	}
	return $protected;
}

////////////////////////////////////////////////////////////////////////

// Taxonomy Meta Fields
if ( ! function_exists( 'porto_product_cat_meta_fields' ) ) {
	/**
	 * @todo 2.3.0 Legacy Mode
	 */
	function porto_product_cat_meta_fields() {
		global $porto_settings;

		$view_mode       = porto_ct_category_view_mode();
		$product_columns = porto_ct_product_columns();
		$addlinks_pos    = porto_ct_category_addlinks_pos();

		$meta_fields = porto_ct_default_view_meta_fields( 'product', 'tax' );

		if ( isset( $meta_fields['loading_overlay'] ) ) {
			// Cateogry Icon
			$meta_fields = array_insert_before(
				'loading_overlay',
				$meta_fields,
				'category_icon',
				array(
					'name'  => 'category_icon',
					'title' => __( 'Category Icon', 'porto-functionality' ),
					'type'  => 'text',
					'desc'  => __( 'Input font icon class such as "fas fa-user". This field is used for porto product categories element when you select "Media Type" option to "Icon" to display icon instead of category thumbnail.', 'porto-functionality' ),
				)
			);

			// Category Image
			$meta_fields = array_insert_after(
				'category_icon',
				$meta_fields,
				'category_image',
				array(
					'name'  => 'category_image',
					'title' => __( 'Category Image', 'porto-functionality' ),
					'type'  => 'upload',
				)
			);

			// View Mode
			$meta_fields = array_insert_after(
				'category_image',
				$meta_fields,
				'view_mode',
				array(
					'name'    => 'view_mode',
					'title'   => __( 'View Mode', 'porto-functionality' ),
					'type'    => 'radio',
					'options' => $view_mode,
				)
			);

			// View Mode
			$meta_fields = array_insert_after(
				'view_mode',
				$meta_fields,
				'filter_layout',
				array(
					'name'    => 'filter_layout',
					'title'   => __( 'Filter Layout', 'porto-functionality' ),
					'type'    => 'imageselect',
					'options' => array(
						''            => array(
							'title' => __( 'Theme Options', 'porto-functionality' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/theme-option.svg',
						),
						'default'     => array(
							'title' => __( 'Default', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/shop-default.svg',
						),
						'horizontal'  => array(
							'title' => __( 'Sidebar With Toggle', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/shop-horizontal1.svg',
						),
						'horizontal2' => array(
							'title' => __( 'Horizontal filters', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/shop-horizontal2.svg',
						),
						'offcanvas'   => array(
							'title' => __( 'Off Canvas', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/shop-offcanvas.svg',
						),
					),
				)
			);

			// Columns
			$meta_fields = array_insert_after(
				'filter_layout',
				$meta_fields,
				'product_cols',
				array(
					'name'    => 'product_cols',
					'title'   => __( 'Product Columns', 'porto-functionality' ),
					'type'    => 'select',
					'options' => $product_columns,
				)
			);

			// Add Links Position
			$meta_fields = array_insert_after(
				'product_cols',
				$meta_fields,
				'addlinks_pos',
				array(
					'name'    => 'addlinks_pos',
					'title'   => __( 'Product Layout', 'porto-functionality' ),
					'desc'    => __( 'Select position of add to cart, add to wishlist, quickview.', 'porto-functionality' ),
					'type'    => 'imageselect',
					'options' => array(
						''                     => array(
							'title' => __( 'Theme Options', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/svg/theme-option.svg',
						),
						'default'              => array(
							'title' => __( 'Default', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_default.jpg',
						),
						'onhover'              => array(
							'title' => __( 'Show Links on Hover', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_default.jpg',
						),
						'outimage_aq_onimage'  => array(
							'title' => __( 'Quick View On Image', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_outimage_aq_onimage.jpg',
						),
						'outimage_aq_onimage2' => array(
							'title' => __( 'Image with Padding', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_outimage_aq_onimage2.jpg',
						),
						'awq_onimage'          => array(
							'title' => __( 'Link On Image', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_awq_onimage.jpg',
						),
						'outimage'             => array(
							'title' => __( 'Out of Image', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_outimage.jpg',
						),
						'onimage'              => array(
							'title' => __( 'On Image', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_onimage.jpg',
						),
						'onimage2'             => array(
							'title' => __( 'On Image with Overlay 1', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_onimage2.jpg',
						),
						'onimage3'             => array(
							'title' => __( 'On Image with Overlay 2', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_onimage3.jpg',
						),
						'quantity'             => array(
							'title' => __( 'Show Quantity Input', 'porto' ),
							'img'   => PORTO_OPTIONS_URI . '/product_layouts/product_layout_quantity_input.jpg',
						),
					),
				)
			);
		}

		if ( isset( $porto_settings['show-category-skin'] ) && $porto_settings['show-category-skin'] ) {
			$meta_fields = array_merge( $meta_fields, porto_ct_default_skin_meta_fields( true ) );
		}

		return $meta_fields;
	}
}


$taxonomy             = 'product_cat';
$table_name           = $wpdb->prefix . $taxonomy . 'meta';
$variable_name        = $taxonomy . 'meta';
$wpdb->$variable_name = $table_name;

// Add Meta Fields when edit taxonomy
add_action( 'product_cat_edit_form_fields', 'porto_edit_product_cat_meta_fields', 100, 2 );
function porto_edit_product_cat_meta_fields( $tag, $taxonomy ) {
	if ( 'product_cat' !== $taxonomy ) {
		return;
	}
	porto_edit_tax_meta_fields( $tag, $taxonomy, porto_product_cat_meta_fields() );
}

// Save Meta Values
add_action( 'edit_term', 'porto_save_product_cat_meta_values', 100, 3 );
function porto_save_product_cat_meta_values( $term_id, $tt_id, $taxonomy ) {
	if ( 'product_cat' !== $taxonomy ) {
		return;
	}
	porto_create_tax_meta_table( $taxonomy );
	return porto_save_tax_meta_values( $term_id, $taxonomy, porto_product_cat_meta_fields() );
}

// Delete Meta Values
add_action( 'delete_term', 'porto_delete_product_cat_meta_values', 10, 5 );
function porto_delete_product_cat_meta_values( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
	if ( 'product_cat' !== $taxonomy ) {
		return;
	}
	return porto_delete_tax_meta_values( $term_id, $taxonomy, porto_product_cat_meta_fields() );
}

// Add Color attribute
if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
	$added_action = false;
	foreach ( $attribute_taxonomies as $tax ) {
		if ( 'color' === $tax->attribute_type || 'label' === $tax->attribute_type || 'image' === $tax->attribute_type ) {
			add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_add_form_fields', 'porto_add_product_attribute_' . $tax->attribute_type . '_fields', 100, 1 );
			add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_edit_form_fields', 'porto_edit_product_attribute_' . $tax->attribute_type . '_fields', 100, 2 );

			if ( ! $added_action ) {
				add_action( 'edit_term', 'porto_save_product_extra_attribute_values', 100, 3 );
				add_action( 'delete_term', 'porto_delete_product_extra_attribute_values', 10, 5 );
				add_action( 'created_term', 'porto_save_product_extra_attribute_values', 100, 3 );
				$added_action = true;
			}
		}
	}
}
function porto_get_product_attribute_color_fields() {
	return array(
		array(
			'name'  => 'color_value',
			'title' => __( 'Color Swatch', 'porto-functionality' ),
			'type'  => 'color',
			'desc'  => __( 'Please choose a swatch color.', 'porto-functionality' ),
		),
	);
}
function porto_get_product_attribute_label_fields() {
	return array(
		array(
			'name'  => 'label_value',
			'title' => __( 'Label Swatch', 'porto-functionality' ),
			'type'  => 'text',
			'desc'  => __( 'Input short label to be displayed instead of title such as "XS".', 'porto-functionality' ),
		),
	);
}
function porto_add_product_attribute_color_fields( $taxonomy ) {
	echo '<table class="form-table">';
	porto_edit_tax_meta_fields( '', $taxonomy, porto_get_product_attribute_color_fields(), true );
	echo '</table>';
}
function porto_edit_product_attribute_color_fields( $tag, $taxonomy ) {
	porto_edit_tax_meta_fields( $tag, $taxonomy, porto_get_product_attribute_color_fields(), true );
}
function porto_add_product_attribute_label_fields( $taxonomy ) {
	echo '<table class="form-table">';
	porto_edit_tax_meta_fields( '', $taxonomy, porto_get_product_attribute_label_fields(), true );
	echo '</table>';
}
function porto_edit_product_attribute_label_fields( $tag, $taxonomy ) {
	porto_edit_tax_meta_fields( $tag, $taxonomy, porto_get_product_attribute_label_fields(), true );
}


function porto_get_product_attribute_image_fields() {
	return array(
		array(
			'name'  => 'image_value',
			'title' => __( 'Image Swatch', 'porto-functionality' ),
			'type'  => 'attach',
			'desc'  => __( 'Uploads a swatch image.', 'porto-functionality' ),
		),
	);
}
function porto_add_product_attribute_image_fields( $taxonomy ) {
	echo '<table class="form-table">';
	porto_edit_tax_meta_fields( '', $taxonomy, porto_get_product_attribute_image_fields(), true );
	echo '</table>';
}
function porto_edit_product_attribute_image_fields( $tag, $taxonomy ) {
	porto_edit_tax_meta_fields( $tag, $taxonomy, porto_get_product_attribute_image_fields(), true );
}

function porto_save_product_extra_attribute_values( $term_id, $tt_id, $taxonomy ) {
	if ( strpos( $taxonomy, 'pa_' ) === false ) {
		return;
	}
	if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( ( 'color' === $tax->attribute_type || 'label' === $tax->attribute_type || 'image' === $tax->attribute_type ) && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				$function_name = 'porto_get_product_attribute_' . $tax->attribute_type . '_fields';
				return porto_save_tax_meta_values( $term_id, $taxonomy, $function_name(), true );
			}
		}
	}
}
function porto_delete_product_extra_attribute_values( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
	if ( strpos( $taxonomy, 'pa_' ) === false ) {
		return;
	}
	if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( 'color' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				delete_term_meta( $term_id, 'color_value' );
			}
			if ( 'label' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				delete_term_meta( $term_id, 'label_value' );
			}
			if ( 'image' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				delete_term_meta( $term_id, 'image_value' );
			}
		}
	}
}

// Video Thumbnail
/**
 * Adds video for product thumbnail
 *
 * @since 6.1
 */
if ( ! function_exists( 'porto_product_video_meta_box' ) ) {
	function porto_product_video_meta_box( $show_box = true ) {
		$meta_fields = array(
			'video_source' => array(
				'name'    => 'porto_video_source',
				'title'   => __( 'Video Source', 'porto-functionality' ),
				'type'    => 'select',
				'options' => array(
					''          => __( 'Select', 'porto-functionality' ),
					'mp4'       => __( 'MP4', 'porto-functionality' ),
					'youtube'   => __( 'YouTube', 'porto-functionality' ),
					'vimeo'     => __( 'Vimeo', 'porto-functionality' ),
					'shortcode' => __( 'Video Shortcode', 'porto-functionality' ),
				),
			),
			'video_sh_type' => array(
				'name'    => 'porto_video_sh_type',
				'title'   => __( 'Video Display Type', 'porto-functionality' ),
				'type'    => 'select',
				'options' => array(
					'popup' => __( 'Popup', 'porto-functionality' ),
					'slide' => __( 'Slide', 'porto-functionality' ),
				),
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'mp4,shortcode,youtube,vimeo',
				),
			),
			'video_thumbnail_img' => array(
				'name'  => 'porto_video_thumbnail_img',
				'title' => __( 'Thumbnail Image', 'porto-functionality' ),
				'type'  => 'upload',
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'mp4,shortcode,youtube,vimeo',
				),
			),
			'video_thumbnail_poster' => array(
				'name'  => 'porto_video_thumbnail_poster',
				'title' => __( 'Poster Image', 'porto-functionality' ),
				'type'  => 'upload',
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'mp4',
				),
			),
			'video_post_image' => array(
				'title'   => esc_html__( 'Video from Library', 'porto-functionality' ),
				'name'    => 'porto_product_video_thumbnails',
				'type'    => 'video',
				'default' => false,
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'mp4',
				),
			),
			'video_youtube'   => array(
				'name'        => 'porto_video_youtube',
				'title'       => __( 'YouTube URL', 'porto-functionality' ),
				'type'        => 'text',
				'placeholder' => 'https://www.youtube.com/watch?v=MDx7RBlSq1A',
				'required'    => array(
					'name'  => 'porto_video_source',
					'value' => 'youtube',
				),
			),
			'video_vimeo'     => array(
				'name'        => 'porto_video_vimeo',
				'title'       => __( 'Vimeo URL', 'porto-functionality' ),
				'type'        => 'text',
				'placeholder' => 'https://vimeo.com/75230326',
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'vimeo',
				),
			),			
			'video_url'    => array(
				'title'    => esc_html__( 'Video Source (Video shortcode, Youtube url or Vimeo url)', 'porto-functionality' ),
				'name'     => 'porto_product_video_thumbnail_shortcode',
				'type'     => 'textarea',
				'rows'     => 5,
				'default'  => '',
				'required' => array(
					'name'  => 'porto_video_source',
					'value' => 'shortcode',
				),
				'desc'     => esc_html__( 'ex. [video src="url.mp4" poster="image.jpg"], https://www.youtube.com/watch?v=MDx7RBlSq1A or https://vimeo.com/75230326', 'porto-functionality' ),
			),
		);
		if ( $show_box ) {
			porto_show_meta_box( $meta_fields );
		}
		return $meta_fields;
	}
}

// Product 360 Degree
/**
 * Adds 360 degree for product
 *
 * @since 6.12.0
 */
if ( ! function_exists( 'porto_product_360_degree' ) ) {
	function porto_product_360_degree( $show_box = true ) {
		$meta_fields = array(
			'product_360_gallery' => array(
				'title'    => esc_html__( 'Select Images for Gallery', 'porto-functionality' ),
				'name'     => 'porto_product_360_gallery',
				'type'     => 'attach',
				'default'  => false,
				'multiple' => true,
			),
		);
		if ( $show_box ) {
			porto_show_meta_box( $meta_fields );
		}
		return $meta_fields;
	}
}
