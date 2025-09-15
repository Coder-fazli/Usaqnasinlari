<?php

function porto_shortcode_template( $name = false ) {
	if ( ! $name ) {
		return false;
	}

	if ( $overridden_template = locate_template( 'vc_templates/' . $name . '.php' ) ) {
		return $overridden_template;
	} else {
		// If neither the child nor parent theme have overridden the template,
		// we load the template from the 'templates' sub-directory of the directory this file is in
		return PORTO_SHORTCODES_TEMPLATES . $name . '.php';
	}
}

function porto_shortcode_woo_template( $name = false ) {
	if ( ! $name ) {
		return false;
	}

	if ( $overridden_template = locate_template( 'vc_templates/' . $name . '.php' ) ) {
		return $overridden_template;
	} else {
		// If neither the child nor parent theme have overridden the template,
		// we load the template from the 'templates' sub-directory of the directory this file is in
		return PORTO_SHORTCODES_WOO_TEMPLATES . $name . '.php';
	}
}

function porto_shortcode_extract_class( $el_class ) {
	$output = '';
	if ( $el_class ) {
		$output = ' ' . str_replace( '.', '', $el_class );
	}

	return $output;
}

function porto_shortcode_end_block_comment( $string ) {
	return WP_DEBUG ? '<!-- END ' . $string . ' -->' : '';
}

function porto_shortcode_format_content( $content ) {

	return wpautop( wptexturize( $content ) );
}

function porto_shortcode_image_resize( $attach_id, $img_url, $width, $height, $crop = false ) {
	// this is an attachment, so we have the ID
	$image_src = array();
	if ( $attach_id ) {
		$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
		$actual_file_path = get_attached_file( $attach_id );
		// this is not an attachment, let's use the image url
	} elseif ( $img_url ) {
		$file_path        = parse_url( $img_url );
		$actual_file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
		$actual_file_path = ltrim( $file_path['path'], '/' );
		$actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
		$orig_size        = getimagesize( $actual_file_path );
		$image_src[0]     = $img_url;
		$image_src[1]     = $orig_size[0];
		$image_src[2]     = $orig_size[1];
	}
	if ( ! empty( $actual_file_path ) ) {
		$file_info = pathinfo( $actual_file_path );
		$extension = '.' . $file_info['extension'];

		// the image path without the extension
		$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];

		$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;

		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {

			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
				$vt_image        = array(
					'url'    => $cropped_img_url,
					'width'  => $width,
					'height' => $height,
				);

				return $vt_image;
			}

			// $crop = false
			if ( ! $crop ) {
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;

				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

					$vt_image = array(
						'url'    => $resized_img_url,
						'width'  => $proportional_size[0],
						'height' => $proportional_size[1],
					);

					return $vt_image;
				}
			}

			// no cache files - let's finally resize it
			$img_editor = wp_get_image_editor( $actual_file_path );

			if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
				return array(
					'url'    => '',
					'width'  => '',
					'height' => '',
				);
			}

			$new_img_path = $img_editor->generate_filename();

			if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
				return array(
					'url'    => '',
					'width'  => '',
					'height' => '',
				);
			}
			if ( ! is_string( $new_img_path ) ) {
				return array(
					'url'    => '',
					'width'  => '',
					'height' => '',
				);
			}

			$new_img_size = getimagesize( $new_img_path );
			$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

			// resized output
			$vt_image = array(
				'url'    => $new_img,
				'width'  => $new_img_size[0],
				'height' => $new_img_size[1],
			);

			return $vt_image;
		}

		// default output - without resizing
		$vt_image = array(
			'url'    => $image_src[0],
			'width'  => $image_src[1],
			'height' => $image_src[2],
		);

		return $vt_image;
	}
	return false;
}

function porto_shortcode_get_image_by_size(
	$params = array(
		'post_id'    => null,
		'attach_id'  => null,
		'thumb_size' => 'thumbnail',
		'class'      => '',
	)
) {
	//array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_size )
	if ( ( ! isset( $params['attach_id'] ) || null == $params['attach_id'] ) && ( ! isset( $params['post_id'] ) || null == $params['post_id'] ) ) {
		return false;
	}
	$post_id = isset( $params['post_id'] ) ? $params['post_id'] : 0;

	if ( $post_id ) {
		$attach_id = get_post_thumbnail_id( $post_id );
	} else {
		$attach_id = $params['attach_id'];
	}

	$thumb_size  = $params['thumb_size'];
	$thumb_class = ( isset( $params['class'] ) && $params['class'] ) ? $params['class'] . ' ' : '';

	global $_wp_additional_image_sizes;
	$thumbnail = '';

	if ( is_string( $thumb_size ) && ( ( ! empty( $_wp_additional_image_sizes[ $thumb_size ] ) && is_array( $_wp_additional_image_sizes[ $thumb_size ] ) ) || in_array(
		$thumb_size,
		array(
			'thumbnail',
			'thumb',
			'medium',
			'large',
			'full',
		)
	) )
	) {
		$thumbnail = wp_get_attachment_image( $attach_id, $thumb_size, false, array( 'class' => $thumb_class . 'attachment-' . $thumb_size ) );
	} elseif ( $attach_id ) {
		if ( is_string( $thumb_size ) ) {
			preg_match_all( '/\d+/', $thumb_size, $thumb_matches );
			if ( isset( $thumb_matches[0] ) ) {
				$thumb_size = array();
				if ( count( $thumb_matches[0] ) > 1 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][1]; // height
				} elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][0]; // height
				} else {
					$thumb_size = false;
				}
			}
		}
		if ( is_array( $thumb_size ) ) {
			// Resize image to custom size
			$p_img      = porto_shortcode_image_resize( $attach_id, null, $thumb_size[0], $thumb_size[1], true );
			$alt        = trim( strip_tags( get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) ) );
			$attachment = get_post( $attach_id );
			if ( ! empty( $attachment ) ) {
				$title = trim( strip_tags( $attachment->post_title ) );

				if ( empty( $alt ) ) {
					$alt = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
				}
				if ( empty( $alt ) ) {
					$alt = $title;
				} // Finally, use the title
				if ( $p_img ) {
					$img_class = '';
					//if ( $grid_layout == 'thumbnail' ) $img_class = ' no_bottom_margin'; class="'.$img_class.'"
					$thumbnail = '<img class="' . esc_attr( $thumb_class ) . '" src="' . esc_url( $p_img['url'] ) . '" width="' . esc_attr( $p_img['width'] ) . '" height="' . esc_attr( $p_img['height'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
				}
			}
		}
	}

	$p_img_large = wp_get_attachment_image_src( $attach_id, 'large' );

	return apply_filters(
		'vc_wpb_getimagesize',
		array(
			'thumbnail'   => $thumbnail,
			'p_img_large' => $p_img_large,
		),
		$attach_id,
		$params
	);
}

function porto_vc_animation_type() {
	return array(
		'type'       => 'porto_animation_type',
		'heading'    => __( 'Animation Type', 'porto-functionality' ),
		'param_name' => 'animation_type',
		'group'      => __( 'Animation', 'porto-functionality' ),
	);
}

function porto_vc_animation_duration() {
	return array(
		'type'        => 'textfield',
		'heading'     => __( 'Animation Duration', 'porto-functionality' ),
		'param_name'  => 'animation_duration',
		'description' => __( 'numerical value (unit: milliseconds)', 'porto-functionality' ),
		'value'       => '1000',
		'group'       => __( 'Animation', 'porto-functionality' ),
	);
}

function porto_vc_animation_delay() {
	return array(
		'type'        => 'textfield',
		'heading'     => __( 'Animation Delay', 'porto-functionality' ),
		'param_name'  => 'animation_delay',
		'description' => __( 'numerical value (unit: milliseconds)', 'porto-functionality' ),
		'value'       => '0',
		'group'       => __( 'Animation', 'porto-functionality' ),
	);
}

function porto_vc_animation_reveal_clr() {
	return array(
		'type'       => 'colorpicker',
		'heading'    => __( 'Animation Color', 'porto-functionality' ),
		'param_name' => 'animation_reveal_clr',
		'dependency' => array(
			'element' => 'animation_type',
			'value'   => array( 'revealDirTop', 'revealDirDown', 'revealDirLeft', 'revealDirRight' ),
		),
		'group'      => __( 'Animation', 'porto-functionality' ),
	);
}

function porto_vc_custom_class() {
	return array(
		'type'        => 'textfield',
		'heading'     => __( 'Extra class name', 'porto-functionality' ),
		'param_name'  => 'el_class',
		'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'porto-functionality' ),
	);
}

function porto_vc_product_slider_fields( $condition_val = 'products-slider', $dots_style_default = '' ) {
	$left  = is_rtl() ? 'right' : 'left';
	$right = is_rtl() ? 'left' : 'right';
	return array(
		array(
			'type'       => 'checkbox',
			'heading'    => __( 'Show Nav', 'porto-functionality' ),
			'param_name' => 'navigation',
			'std'        => 'yes',
			'dependency' => array(
				'element' => 'view',
				'value'   => array( $condition_val ),
			),
			'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'group'      => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Nav Position', 'porto-functionality' ),
			'param_name' => 'nav_pos',
			'value'      => array(
				__( 'Middle', 'porto-functionality' ) => '',
				__( 'Middle of Images', 'porto-functionality' ) => 'nav-center-images-only',
				__( 'Top', 'porto-functionality' )    => 'show-nav-title',
				__( 'Bottom', 'porto-functionality' ) => 'nav-bottom',
			),
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Nav Inside/Outside?', 'porto-functionality' ),
			'param_name' => 'nav_pos2',
			'value'      => array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Inside', 'porto-functionality' )  => 'nav-pos-inside',
				__( 'Outside', 'porto-functionality' ) => 'nav-pos-outside',
			),
			'dependency' => array(
				'element' => 'nav_pos',
				'value'   => array( '', 'nav-center-images-only' ),
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Nav Type', 'porto-functionality' ),
			'param_name' => 'nav_type',
			'value'      => porto_sh_commons( 'carousel_nav_types' ),
			'dependency' => array(
				'element' => 'nav_pos',
				'value'   => array( '', 'nav-bottom', 'nav-center-images-only' ),
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'checkbox',
			'heading'    => __( 'Show Nav on Hover', 'porto-functionality' ),
			'param_name' => 'show_nav_hover',
			'value'      => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_button_group',
			'heading'    => __( 'Navigation Visible', 'porto-functionality' ),
			'param_name' => 'nav_visible',
			'value'      => array(
				'block' => array(
					'title' => esc_html__( 'Show', 'porto-functionality' ),
					'icon'  => 'far fa-eye',
				),
				'none'   => array(
					'title' => esc_html__( 'none', 'porto-functionality' ),
					'icon'  => 'far fa-eye-slash',
				),
			),
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'std'        => 'block',
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav:not(.disabled)' => 'display: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Nav Font Size(px)', 'porto-functionality' ),
			'param_name' => 'nav_fs',
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'responsive' => true,
			'separator'  => 'before',
			'qa_selector' => '.owl-nav > .owl-prev',
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'font-size: {{VALUE}}px !important;line-height: {{VALUE}}px !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Nav Width', 'porto-functionality' ),
			'param_name' => 'nav_width',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'width: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Nav Height', 'porto-functionality' ),
			'param_name' => 'nav_height',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'height: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Border Radius', 'porto-functionality' ),
			'param_name' => 'nav_br',
			'units'      => array( 'px', '%' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( '', 'rounded-nav', 'big-nav', 'nav-style-3' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'border-radius: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Nav Origin X Position', 'porto-functionality' ),
			'param_name' => 'navs_h_origin',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'nav_pos',
				'value'   => array( '', 'show-nav-title', 'nav-center-images-only' ),
			),
			'separator'  => 'before',
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav' => "{$left}: {{VALUE}}{{UNIT}} !important; {$right}: unset !important;",
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Nav Origin Y Position', 'porto-functionality' ),
			'param_name' => 'nav_v_pos',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'nav_pos',
				'value'   => array( '', 'show-nav-title' ),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav' => 'top: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Horizontal Nav Position', 'porto-functionality' ),
			'param_name' => 'nav_h_pos',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'nav_pos',
				'value'   => array( '', 'show-nav-title', 'nav-center-images-only' ),
			),
			'range'      => array(
				'px' => array(
					'min' => -100,
					'max' => 100,
				),
				'rem' => array(
					'min' => -10,
					'max' => 10,
				),
				'%' => array(
					'min' => -100,
					'max' => 100,
				)
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-carousel:not(.show-nav-title) .owl-nav button.owl-prev, {{WRAPPER}}.owl-carousel:not(.show-nav-title) .owl-nav button.owl-prev' => "{$left}: {{VALUE}}{{UNIT}} !important;",
				'{{WRAPPER}}.show-nav-title .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel.show-nav-title .owl-nav button.owl-prev'                          => "{$left}: {{VALUE}}{{UNIT}} !important;",
				'{{WRAPPER}} .owl-carousel:not(.show-nav-title) .owl-nav button.owl-next'                                                                         => "{$right}: {{VALUE}}{{UNIT}} !important;",
				'{{WRAPPER}}.owl-carousel:not(.show-nav-title) .owl-nav button.owl-next'                                                                          => "{$right}: {{VALUE}}{{UNIT}} !important;",
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_color',
			'heading'    => __( 'Nav Color', 'porto-functionality' ),
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'separator'  => 'before',
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_h_color',
			'heading'    => __( 'Hover Nav Color', 'porto-functionality' ),
			'dependency' => array(
				'element'   => 'navigation',
				'not_empty' => true,
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_bg_color',
			'heading'    => __( 'Background Color', 'porto-functionality' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( '', 'big-nav', 'nav-style-3' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'background-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_h_bg_color',
			'heading'    => __( 'Hover Background Color', 'porto-functionality' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( '', 'big-nav', 'nav-style-3' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'background-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_br_color',
			'heading'    => __( 'Nav Border Color', 'porto-functionality' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'rounded-nav' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button' => 'border-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'nav_h_br_color',
			'heading'    => __( 'Hover Nav Border Color', 'porto-functionality' ),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'rounded-nav' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-nav button:not(.disabled):hover' => 'border-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Navigation', 'porto-functionality' ),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Show Dots', 'porto-functionality' ),
			'param_name'  => 'pagination',
			'std'         => '',
			'dependency'  => array(
				'element' => 'view',
				'value'   => array( $condition_val ),
			),
			'separator'   => 'before',
			'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'qa_selector' => '.owl-dots > .owl-dot:first-child',
			'group'       => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Dots Position', 'porto-functionality' ),
			'param_name' => 'dots_pos',
			'std'        => '',
			'value'      => array(
				__( 'Bottom', 'porto-functionality' )      => '',
				__( 'Top Right', 'porto-functionality' )   => 'show-dots-title-right',
				__( 'Inside Right', 'porto-functionality' ) => 'nav-inside',
				__( 'Inside Center', 'porto-functionality' ) => 'nav-inside nav-inside-center',
				__( 'Inside Left', 'porto-functionality' ) => 'nav-inside nav-inside-left',
				__( 'Custom', 'porto-functionality' )      => 'custom-dots',
			),
			'dependency' => array(
				'element'   => 'pagination',
				'not_empty' => true,
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Dots Style', 'porto-functionality' ),
			'param_name' => 'dots_style',
			'std'        => $dots_style_default,
			'value'      => array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Circle inner dot', 'porto-functionality' ) => 'dots-style-1',
			),
			'dependency' => array(
				'element'   => 'pagination',
				'not_empty' => true,
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Dots Top Position', 'porto-functionality' ),
			'param_name' => 'dots_pos_top',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'dots_pos',
				'value'   => 'custom-dots',
			),
			'responsive' => true,
			'separator'  => 'before',
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots' => 'top: {{VALUE}}{{UNIT}} !important;',
			),
			'group'       => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Dots Bottom Position', 'porto-functionality' ),
			'param_name' => 'dots_pos_bottom',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'dots_pos',
				'value'   => 'custom-dots',
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots' => 'bottom: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Dots Left Position', 'porto-functionality' ),
			'param_name' => 'dots_pos_left',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'dots_pos',
				'value'   => 'custom-dots',
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots' => 'left: {{VALUE}}{{UNIT}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_number',
			'heading'    => __( 'Dots Right Position', 'porto-functionality' ),
			'param_name' => 'dots_pos_right',
			'units'      => array( 'px', 'rem', '%' ),
			'dependency' => array(
				'element' => 'dots_pos',
				'value'   => 'custom-dots',
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots' => 'right: {{VALUE}}{{UNIT}} !important;',
			),
			'group'       => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_button_group',
			'heading'    => __( 'Dots Visible', 'porto-functionality' ),
			'param_name' => 'dots_visible',
			'value'      => array(
				'block' => array(
					'title' => esc_html__( 'Show', 'porto-functionality' ),
					'icon'  => 'far fa-eye',
				),
				'none' => array(
					'title' => esc_html__( 'none', 'porto-functionality' ),
					'icon'  => 'far fa-eye-slash',
				),
			),
			'dependency' => array(
				'element'   => 'pagination',
				'not_empty' => true,
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots:not(.disabled)' => 'display: {{VALUE}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'porto_image_select',
			'heading'    => __( 'Dots Translate X', 'porto-functionality' ),
			'param_name' => 'dots_original',
			'value'      => array(
				'transform/left.jpg'    => '-50%',
				'transform/center.jpg' => '',
				'transform/right.jpg'   => '50%',
			),
			'dependency'  => array(
				'element' => 'dots_pos',
				'value'   => 'custom-dots',
			),
			'std'        => '',
			'selectors'  => array(
				'{{WRAPPER}} .owl-dots:not(.disabled)' => 'transform: translateX( {{VALUE}} ) !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'dots_br_color',
			'heading'    => __( 'Dots Color', 'porto-functionality' ),
			'separator'  => 'before',
			'dependency' => array(
				'element' => 'dots_style',
				'value'   => 'dots-style-1',
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-dot span' => 'border-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'dots_abr_color',
			'heading'    => __( 'Dots Active Color', 'porto-functionality' ),
			'dependency' => array(
				'element' => 'dots_style',
				'value'   => 'dots-style-1',
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-dot.active span, {{WRAPPER}} .owl-dot:hover span' => 'color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'dots_bg_color',
			'heading'    => __( 'Dots Color', 'porto-functionality' ),
			'separator'  => 'before',
			'dependency' => array(
				'element'            => 'dots_style',
				'value_not_equal_to' => 'dots-style-1',
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-dot span' => 'background-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'dots_abg_color',
			'heading'    => __( 'Dots Active Color', 'porto-functionality' ),
			'dependency' => array(
				'element'            => 'dots_style',
				'value_not_equal_to' => 'dots-style-1',
			),
			'selectors'  => array(
				'{{WRAPPER}} .owl-dot.active span, {{WRAPPER}} .owl-dot:hover span' => 'background-color: {{VALUE}} !important;',
			),
			'group'      => __( 'Dots Style', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Auto Play', 'porto-functionality' ),
			'param_name' => 'autoplay',
			'value'      => array(
				__( 'Theme Options', 'porto-functionality' ) => '',
				__( 'Yes', 'porto-functionality' ) => 'yes',
				__( 'No', 'porto-functionality' )  => 'no',
			),
			'std'        => '',
			'dependency' => array(
				'element' => 'view',
				'value'   => array( $condition_val ),
			),
			'separator'  => 'before',
			'group'      => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Auto Play Timeout', 'porto-functionality' ),
			'param_name' => 'autoplay_timeout',
			'dependency' => array(
				'element' => 'autoplay',
				'value'   => array( 'yes' ),
			),
			'value'      => 5000,
			'group'      => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Enable Loop', 'porto-functionality' ),
			'param_name' => 'set_loop',
			'value'      => array(
				__( 'Theme Options', 'porto-functionality' ) => '',
				__( 'Yes', 'porto-functionality' ) => 'yes',
				__( 'No', 'porto-functionality' )  => 'no',
			),
			'dependency' => array(
				'element' => 'view',
				'value'   => array( $condition_val ),
			),
			'std'        => '',
			'group'      => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Enable Flick Type', 'porto-functionality' ),
			'param_name'  => 'enable_flick',
			'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_carousel_flick.gif"/>',
			'dependency'  => array(
				'element' => 'view',
				'value'   => array( $condition_val ),
			),
			'separator'   => 'before',
			'description' => sprintf( __( 'This option shows the carousel at the container\'s width. %1$sRead More%2$s', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-porto-flick-carousel" target="_blank">', '</a>' ),
			'value'       => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'group'       => __( 'Slider Options', 'porto-functionality' ),
		),
		array(
			'type'       => 'number',
			'heading'    => __( 'Opacity of Inactive item', 'porto-functionality' ),
			'param_name' => 'flick_opacity',
			'dependency' => array(
				'element'   => 'enable_flick',
				'not_empty' => true,
			),
			'min'        => 0,
			'max'        => 1,
			'std'        => 0.5,
			'selectors'  => array(
				'{{WRAPPER}} .owl-item:not(.active)' => 'opacity: {{VALUE}}',
			),
			'group'      => __( 'Slider Options', 'porto-functionality' ),
		),
	);
}

if ( ! function_exists( 'porto_sh_commons' ) ) {
	function porto_sh_commons( $asset = '' ) {
		switch ( $asset ) {
			case 'toggle_type':
				return Porto_ShSharedLibrary::getToggleType();
			case 'toggle_size':
				return Porto_ShSharedLibrary::getToggleSize();
			case 'align':
				return Porto_ShSharedLibrary::getTextAlign();
			case 'blog_layout':
				return Porto_ShSharedLibrary::getBlogLayout();
			case 'blog_grid_columns':
				return Porto_ShSharedLibrary::getBlogGridColumns();
			case 'portfolio_layout':
				return Porto_ShSharedLibrary::getPortfolioLayout();
			case 'portfolio_grid_columns':
				return Porto_ShSharedLibrary::getPortfolioGridColumns();
			case 'portfolio_grid_view':
				return Porto_ShSharedLibrary::getPortfolioGridView();
			case 'member_columns':
				return Porto_ShSharedLibrary::getMemberColumns();
			case 'member_view':
				return Porto_ShSharedLibrary::getMemberView();
			case 'custom_zoom':
				return Porto_ShSharedLibrary::getCustomZoom();
			case 'products_view_mode':
				return Porto_ShSharedLibrary::getProductsViewMode();
			case 'products_columns':
				return Porto_ShSharedLibrary::getProductsColumns();
			case 'products_column_width':
				return Porto_ShSharedLibrary::getProductsColumnWidth();
			case 'products_addlinks_pos':
				return Porto_ShSharedLibrary::getProductsAddlinksPos();
			case 'product_view_mode':
				return Porto_ShSharedLibrary::getProductViewMode();
			case 'content_boxes_bg_type':
				return Porto_ShSharedLibrary::getContentBoxesBgType();
			case 'content_boxes_style':
				return Porto_ShSharedLibrary::getContentBoxesStyle();
			case 'content_box_effect':
				return Porto_ShSharedLibrary::getContentBoxEffect();
			case 'colors':
				return Porto_ShSharedLibrary::getColors();
			case 'testimonial_styles':
				return Porto_ShSharedLibrary::getTestimonialStyles();
			case 'contextual':
				return Porto_ShSharedLibrary::getContextual();
			case 'position':
				return Porto_ShSharedLibrary::getPosition();
			case 'size':
				return Porto_ShSharedLibrary::getSize();
			case 'trigger':
				return Porto_ShSharedLibrary::getTrigger();
			case 'bootstrap_columns':
				return Porto_ShSharedLibrary::getBootstrapColumns();
			case 'price_boxes_style':
				return Porto_ShSharedLibrary::getPriceBoxesStyle();
			case 'price_boxes_size':
				return Porto_ShSharedLibrary::getPriceBoxesSize();
			case 'sort_style':
				return Porto_ShSharedLibrary::getSortStyle();
			case 'sort_by':
				return Porto_ShSharedLibrary::getSortBy();
			case 'grid_columns':
				return Porto_ShSharedLibrary::getGridColumns();
			case 'preview_time':
				return Porto_ShSharedLibrary::getPreviewTime();
			case 'preview_position':
				return Porto_ShSharedLibrary::getPreviewPosition();
			case 'popup_action':
				return Porto_ShSharedLibrary::getPopupAction();
			case 'feature_box_style':
				return Porto_ShSharedLibrary::getFeatureBoxStyle();
			case 'feature_box_dir':
				return Porto_ShSharedLibrary::getFeatureBoxDir();
			case 'section_skin':
				return Porto_ShSharedLibrary::getSectionSkin();
			case 'section_color_scale':
				return Porto_ShSharedLibrary::getSectionColorScale();
			case 'section_text_color':
				return Porto_ShSharedLibrary::getSectionTextColor();
			case 'separator_icon_style':
				return Porto_ShSharedLibrary::getSeparatorIconStyle();
			case 'separator_icon_size':
				return Porto_ShSharedLibrary::getSeparatorIconSize();
			case 'separator_icon_pos':
				return Porto_ShSharedLibrary::getSeparatorIconPosition();
			case 'carousel_nav_types':
				return Porto_ShSharedLibrary::getCarouselNavTypes();
			case 'image_sizes':
				return Porto_ShSharedLibrary::getImageSizes();
			case 'masonry_layouts':
				return Porto_ShSharedLibrary::getMasonryLayouts();
			case 'easing_methods':
				return Porto_ShSharedLibrary::getEasingMethods();
			case 'divider_type':
				return Porto_ShSharedLibrary::getDividerType();
			case 'shape_divider':
				return Porto_ShSharedLibrary::getShapeDivider();
			default:
				return array();
		}
	}
}

function porto_vc_woo_order_by() {
	$result = array(
		'',
		esc_html__( 'Date', 'porto-functionality' )       => 'date',
		esc_html__( 'ID', 'porto-functionality' )         => 'id',
		esc_html__( 'Menu order', 'porto-functionality' ) => 'menu_order',
		esc_html__( 'Title', 'porto-functionality' )      => 'title',
		esc_html__( 'Random', 'porto-functionality' )     => 'rand',
		esc_html__( 'Price', 'porto-functionality' )      => 'price',
		esc_html__( 'Popularity', 'porto-functionality' ) => 'popularity',
	);
	if ( class_exists( 'Woocommerce' ) && wc_review_ratings_enabled() ) {
		$result[ esc_html__( 'Rating', 'porto-functionality' ) ] = 'rating';
	}
	return $result;
}

function porto_woo_sort_by() {
	$result = array(
		__( 'All', 'porto-functionality' )     => 'all',
		__( 'Popular', 'porto-functionality' ) => 'popular',
		__( 'Date', 'porto-functionality' )    => 'date',
		__( 'On Sale', 'porto-functionality' ) => 'onsale',
	);
	if ( class_exists( 'Woocommerce' ) && wc_review_ratings_enabled() ) {
		$result[ __( 'Rating', 'porto-functionality' ) ] = 'rating';
	}
	return $result;
}

function porto_vc_order_by() {
	return array(
		'',
		esc_html__( 'Date', 'porto-functionality' )       => 'date',
		esc_html__( 'ID', 'porto-functionality' )         => 'ID',
		esc_html__( 'Author', 'porto-functionality' )     => 'author',
		esc_html__( 'Title', 'porto-functionality' )      => 'title',
		esc_html__( 'Modified', 'porto-functionality' )   => 'modified',
		esc_html__( 'Random', 'porto-functionality' )     => 'rand',
		esc_html__( 'Comment count', 'porto-functionality' ) => 'comment_count',
		esc_html__( 'Menu order', 'porto-functionality' ) => 'menu_order',
	);
}

function porto_vc_woo_order_way() {
	return array(
		'',
		__( 'Descending', 'porto-functionality' ) => 'DESC',
		__( 'Ascending', 'porto-functionality' )  => 'ASC',
	);
}

if ( ! class_exists( 'Porto_ShSharedLibrary' ) ) {
	class Porto_ShSharedLibrary {

		public static function getTextAlign() {
			return array(
				__( 'None', 'porto-functionality' )    => '',
				__( 'Left', 'porto-functionality' )    => 'left',
				__( 'Right', 'porto-functionality' )   => 'right',
				__( 'Center', 'porto-functionality' )  => 'center',
				__( 'Justify', 'porto-functionality' ) => 'justify',
			);
		}

		public static function getToggleType() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Simple', 'porto-functionality' )  => 'toggle-simple',
			);
		}

		public static function getToggleSize() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Small', 'porto-functionality' )   => 'toggle-sm',
				__( 'Large', 'porto-functionality' )   => 'toggle-lg',
			);
		}

		public static function getBlogLayout() {
			return array(
				__( 'Full', 'porto-functionality' )       => 'full',
				__( 'Large', 'porto-functionality' )      => 'large',
				__( 'Large Alt', 'porto-functionality' )  => 'large-alt',
				__( 'Medium', 'porto-functionality' )     => 'medium',
				__( 'Medium Alt', 'porto-functionality' ) => 'medium-alt',
				__( 'Grid', 'porto-functionality' )       => 'grid',
				__( 'Grid - Creative', 'porto-functionality' ) => 'creative',
				__( 'Masonry', 'porto-functionality' )    => 'masonry',
				__( 'Masonry - Creative', 'porto-functionality' ) => 'masonry-creative',
				__( 'Timeline', 'porto-functionality' )   => 'timeline',
				__( 'Slider', 'porto-functionality' )     => 'slider',
			);
		}

		public static function getBlogGridColumns() {
			return array(
				__( '1', 'porto-functionality' ) => '1',
				__( '2', 'porto-functionality' ) => '2',
				__( '3', 'porto-functionality' ) => '3',
				__( '4', 'porto-functionality' ) => '4',
				__( '5', 'porto-functionality' ) => '5',
				__( '6', 'porto-functionality' ) => '6',
			);
		}

		public static function getPortfolioLayout() {
			return array(
				__( 'Grid', 'porto-functionality' )        => 'grid',
				__( 'Grid - Creative', 'porto-functionality' ) => 'creative',
				__( 'Masonry', 'porto-functionality' )     => 'masonry',
				__( 'Masonry - Creative', 'porto-functionality' ) => 'masonry-creative',
				__( 'Timeline', 'porto-functionality' )    => 'timeline',
				__( 'Medium', 'porto-functionality' )      => 'medium',
				__( 'Large', 'porto-functionality' )       => 'large',
				__( 'Full', 'porto-functionality' )        => 'full',
				__( 'Full Screen', 'porto-functionality' ) => 'fullscreen',
			);
		}

		public static function getPortfolioGridColumns() {
			return array(
				__( '1', 'porto-functionality' ) => '1',
				__( '2', 'porto-functionality' ) => '2',
				__( '3', 'porto-functionality' ) => '3',
				__( '4', 'porto-functionality' ) => '4',
				__( '5', 'porto-functionality' ) => '5',
				__( '6', 'porto-functionality' ) => '6',
			);
		}

		public static function getPortfolioGridView() {
			return array(
				__( 'Standard', 'porto-functionality' )  => 'classic',
				__( 'Default', 'porto-functionality' )   => 'default',
				__( 'No Margin', 'porto-functionality' ) => 'full',
				__( 'Out of Image', 'porto-functionality' ) => 'outimage',
			);
		}

		public static function getMemberView() {
			return array(
				__( 'Standard', 'porto-functionality' ) => 'classic',
				__( 'Text On Image', 'porto-functionality' ) => 'onimage',
				__( 'Text Out Image', 'porto-functionality' ) => 'outimage',
				__( 'Text & Cat Out Image', 'porto-functionality' ) => 'outimage_cat',
				__( 'Simple & Out Image', 'porto-functionality' ) => 'simple',
			);
		}

		public static function getCustomZoom() {
			return array(
				__( 'Zoom', 'porto-functionality' )    => 'zoom',
				__( 'No_Zoom', 'porto-functionality' ) => 'no_zoom',
			);
		}

		public static function getMemberColumns() {
			return array(
				__( '2', 'porto-functionality' ) => '2',
				__( '3', 'porto-functionality' ) => '3',
				__( '4', 'porto-functionality' ) => '4',
				__( '5', 'porto-functionality' ) => '5',
				__( '6', 'porto-functionality' ) => '6',
			);
		}

		public static function getProductsViewMode() {
			return array(
				__( 'Grid', 'porto-functionality' )   => 'grid',
				__( 'Grid - Divider Line', 'porto-functionality' ) => 'divider',
				__( 'Grid - Creative', 'porto-functionality' ) => 'creative',
				__( 'List', 'porto-functionality' )   => 'list',
				__( 'Slider', 'porto-functionality' ) => 'products-slider',
			);
		}

		public static function getProductsColumns() {
			return array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7 ' . __( '(without sidebar)', 'porto-functionality' ) => 7,
				'8 ' . __( '(without sidebar)', 'porto-functionality' ) => 8,
			);
		}

		public static function getProductsColumnWidth() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				'1/1' . __( ' of content width', 'porto-functionality' ) => 1,
				'1/2' . __( ' of content width', 'porto-functionality' ) => 2,
				'1/3' . __( ' of content width', 'porto-functionality' ) => 3,
				'1/4' . __( ' of content width', 'porto-functionality' ) => 4,
				'1/5' . __( ' of content width', 'porto-functionality' ) => 5,
				'1/6' . __( ' of content width', 'porto-functionality' ) => 6,
				'1/7' . __( ' of content width (without sidebar)', 'porto-functionality' ) => 7,
				'1/8' . __( ' of content width (without sidebar)', 'porto-functionality' ) => 8,
			);
		}

		public static function getProductsAddlinksPos() {
			return array(
				__( 'Theme Options', 'porto-functionality' ) => '',
				__( 'Default', 'porto-functionality' )  => 'default',
				__( 'Default - Show Links on Hover', 'porto-functionality' ) => 'onhover',
				__( 'Add to Cart, Quick View on Image', 'porto-functionality' ) => 'outimage_aq_onimage',
				__( 'Add to Cart, Quick View on Image with Padding', 'porto-functionality' ) => 'outimage_aq_onimage2',
				__( 'Links On Image', 'porto-functionality' ) => 'awq_onimage',
				__( 'Out of Image', 'porto-functionality' ) => 'outimage',
				__( 'On Image', 'porto-functionality' ) => 'onimage',
				__( 'On Image with Overlay 1', 'porto-functionality' ) => 'onimage2',
				__( 'On Image with Overlay 2', 'porto-functionality' ) => 'onimage3',
				__( 'Show Quantity Input', 'porto-functionality' ) => 'quantity',
			);
		}

		public static function getProductViewMode() {
			return array(
				__( 'Grid', 'porto-functionality' ) => 'grid',
				__( 'List', 'porto-functionality' ) => 'list',
			);
		}

		public static function getColors() {
			return array(
				''                                        => 'custom',
				__( 'Primary', 'porto-functionality' )    => 'primary',
				__( 'Secondary', 'porto-functionality' )  => 'secondary',
				__( 'Tertiary', 'porto-functionality' )   => 'tertiary',
				__( 'Quaternary', 'porto-functionality' ) => 'quaternary',
				__( 'Dark', 'porto-functionality' )       => 'dark',
				__( 'Light', 'porto-functionality' )      => 'light',
			);
		}

		public static function getContentBoxesBgType() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Flat', 'porto-functionality' )    => 'featured-boxes-flat',
				__( 'Custom', 'porto-functionality' )  => 'featured-boxes-custom',
			);
		}

		public static function getContentBoxesStyle() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Style 1', 'porto-functionality' ) => 'featured-boxes-style-1',
				__( 'Style 2', 'porto-functionality' ) => 'featured-boxes-style-2',
				__( 'Style 3', 'porto-functionality' ) => 'featured-boxes-style-3',
				__( 'Style 4', 'porto-functionality' ) => 'featured-boxes-style-4',
				__( 'Style 5', 'porto-functionality' ) => 'featured-boxes-style-5',
				__( 'Style 6', 'porto-functionality' ) => 'featured-boxes-style-6',
				__( 'Style 7', 'porto-functionality' ) => 'featured-boxes-style-7',
				__( 'Style 8', 'porto-functionality' ) => 'featured-boxes-style-8',
			);
		}

		public static function getContentBoxEffect() {
			return array(
				__( 'Default', 'porto-functionality' )  => '',
				__( 'Effect 1', 'porto-functionality' ) => 'featured-box-effect-1',
				__( 'Effect 2', 'porto-functionality' ) => 'featured-box-effect-2',
				__( 'Effect 3', 'porto-functionality' ) => 'featured-box-effect-3',
				__( 'Effect 4', 'porto-functionality' ) => 'featured-box-effect-4',
				__( 'Effect 5', 'porto-functionality' ) => 'featured-box-effect-5',
				__( 'Effect 6', 'porto-functionality' ) => 'featured-box-effect-6',
				__( 'Effect 7', 'porto-functionality' ) => 'featured-box-effect-7',
			);
		}

		public static function getTestimonialStyles() {
			return array(
				__( 'Style 1', 'porto-functionality' ) => '',
				__( 'Style 2', 'porto-functionality' ) => 'testimonial-style-2',
				__( 'Style 3', 'porto-functionality' ) => 'testimonial-style-3',
				__( 'Style 4', 'porto-functionality' ) => 'testimonial-style-4',
				__( 'Style 5', 'porto-functionality' ) => 'testimonial-style-5',
				__( 'Style 6', 'porto-functionality' ) => 'testimonial-style-6',
			);
		}

		public static function getContextual() {
			return array(
				__( 'None', 'porto-functionality' )    => '',
				__( 'Success', 'porto-functionality' ) => 'success',
				__( 'Info', 'porto-functionality' )    => 'info',
				__( 'Warning', 'porto-functionality' ) => 'warning',
				__( 'Danger', 'porto-functionality' )  => 'danger',
			);
		}

		public static function getPosition() {
			return array(
				__( 'Top', 'porto-functionality' )    => 'top',
				__( 'Right', 'porto-functionality' )  => 'right',
				__( 'Bottom', 'porto-functionality' ) => 'bottom',
				__( 'Left', 'porto-functionality' )   => 'left',
			);
		}

		public static function getSize() {
			return array(
				__( 'Normal', 'porto-functionality' )      => '',
				__( 'Large', 'porto-functionality' )       => 'lg',
				__( 'Small', 'porto-functionality' )       => 'sm',
				__( 'Extra Small', 'porto-functionality' ) => 'xs',
			);
		}

		public static function getTrigger() {
			return array(
				__( 'Click', 'porto-functionality' ) => 'click',
				__( 'Hover', 'porto-functionality' ) => 'hover',
				__( 'Focus', 'porto-functionality' ) => 'focus',
			);
		}

		public static function getBootstrapColumns() {
			return array( 6, 4, 3, 2, 1 );
		}

		public static function getPriceBoxesStyle() {
			return array(
				__( 'Default', 'porto-functionality' )     => '',
				__( 'Alternative', 'porto-functionality' ) => 'flat',
				__( 'Classic', 'porto-functionality' )     => 'classic',
			);
		}

		public static function getPriceBoxesSize() {
			return array(
				__( 'Normal', 'porto-functionality' ) => '',
				__( 'Small', 'porto-functionality' )  => 'sm',
			);
		}

		public static function getSortStyle() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Style 2', 'porto-functionality' ) => 'style-2',
			);
		}

		public static function getSortBy() {
			return array(
				__( 'Original Order', 'porto-functionality' ) => 'original-order',
				__( 'Popular Value', 'porto-functionality' )  => 'popular',
			);
		}

		public static function getGridColumns() {
			return array(
				__( '12 columns - 1/1', 'porto-functionality' ) => '12',
				__( '11 columns - 11/12', 'porto-functionality' ) => '11',
				__( '10 columns - 5/6', 'porto-functionality' ) => '10',
				__( '9 columns - 3/4', 'porto-functionality' )  => '9',
				__( '8 columns - 2/3', 'porto-functionality' )  => '8',
				__( '7 columns - 7/12', 'porto-functionality' ) => '7',
				__( '6 columns - 1/2', 'porto-functionality' )  => '6',
				__( '5 columns - 5/12', 'porto-functionality' ) => '5',
				__( '4 columns - 1/3', 'porto-functionality' )  => '4',
				__( '3 columns - 1/4', 'porto-functionality' )  => '3',
				__( '2 columns - 1/6', 'porto-functionality' )  => '2',
				__( '1 columns - 1/12', 'porto-functionality' ) => '1',
			);
		}

		public static function getMasonryLayouts() {
			return apply_filters(
				'porto_creative_grid_layout_images',
				array(
					'cg/1.jpg'  => '1',
					'cg/2.jpg'  => '2',
					'cg/3.jpg'  => '3',
					'cg/4.jpg'  => '4',
					'cg/5.jpg'  => '5',
					'cg/6.jpg'  => '6',
					'cg/7.jpg'  => '7',
					'cg/8.jpg'  => '8',
					'cg/9.jpg'  => '9',
					'cg/10.jpg' => '10',
					'cg/11.jpg' => '11',
					'cg/12.jpg' => '12',
					'cg/13.jpg' => '13',
					'cg/14.jpg' => '14',
				)
			);
		}

		public static function getPreviewTime() {
			return array(
				__( 'Normal', 'porto-functionality' ) => '',
				__( 'Short', 'porto-functionality' )  => 'short',
				__( 'Long', 'porto-functionality' )   => 'long',
			);
		}

		public static function getPreviewPosition() {
			return array(
				__( 'Center', 'porto-functionality' ) => '',
				__( 'Top', 'porto-functionality' )    => 'top',
				__( 'Bottom', 'porto-functionality' ) => 'bottom',
			);
		}

		public static function getPopupAction() {
			return array(
				__( 'Open URL (Link)', 'porto-functionality' ) => 'open_link',
				__( 'Popup Video or Map', 'porto-functionality' ) => 'popup_iframe',
				__( 'Popup Block', 'porto-functionality' ) => 'popup_block',
			);
		}

		public static function getFeatureBoxStyle() {
			return array(
				__( 'Style 1', 'porto-functionality' ) => '',
				__( 'Style 2', 'porto-functionality' ) => 'feature-box-style-2',
				__( 'Style 3', 'porto-functionality' ) => 'feature-box-style-3',
				__( 'Style 4', 'porto-functionality' ) => 'feature-box-style-4',
				__( 'Style 5', 'porto-functionality' ) => 'feature-box-style-5',
				__( 'Style 6', 'porto-functionality' ) => 'feature-box-style-6',
			);
		}

		public static function getFeatureBoxDir() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Reverse', 'porto-functionality' ) => 'reverse',
			);
		}

		public static function getSectionSkin() {
			return array(
				__( 'Default', 'porto-functionality' )     => 'default',
				__( 'Transparent', 'porto-functionality' ) => 'parallax',
				__( 'Primary', 'porto-functionality' )     => 'primary',
				__( 'Secondary', 'porto-functionality' )   => 'secondary',
				__( 'Tertiary', 'porto-functionality' )    => 'tertiary',
				__( 'Quaternary', 'porto-functionality' )  => 'quaternary',
				__( 'Dark', 'porto-functionality' )        => 'dark',
				__( 'Light', 'porto-functionality' )       => 'light',
			);
		}

		public static function getSectionColorScale() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Scale 1', 'porto-functionality' ) => 'scale-1',
				__( 'Scale 2', 'porto-functionality' ) => 'scale-2',
				__( 'Scale 3', 'porto-functionality' ) => 'scale-3',
				__( 'Scale 4', 'porto-functionality' ) => 'scale-4',
				__( 'Scale 5', 'porto-functionality' ) => 'scale-5',
				__( 'Scale 6', 'porto-functionality' ) => 'scale-6',
				__( 'Scale 7', 'porto-functionality' ) => 'scale-7',
				__( 'Scale 8', 'porto-functionality' ) => 'scale-8',
				__( 'Scale 9', 'porto-functionality' ) => 'scale-9',
			);
		}

		public static function getSectionTextColor() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Dark', 'porto-functionality' )    => 'dark',
				__( 'Light', 'porto-functionality' )   => 'light',
			);
		}

		public static function getSeparatorIconStyle() {
			return array(
				__( 'Style 1', 'porto-functionality' ) => '',
				__( 'Style 2', 'porto-functionality' ) => 'style-2',
				__( 'Style 3', 'porto-functionality' ) => 'style-3',
				__( 'Style 4', 'porto-functionality' ) => 'style-4',
			);
		}

		public static function getSeparatorIconSize() {
			return array(
				__( 'Normal', 'porto-functionality' ) => '',
				__( 'Small', 'porto-functionality' )  => 'sm',
				__( 'Large', 'porto-functionality' )  => 'lg',
			);
		}

		public static function getSeparatorIconPosition() {
			return array(
				__( 'Center', 'porto-functionality' ) => '',
				__( 'Left', 'porto-functionality' )   => 'left',
				__( 'Right', 'porto-functionality' )  => 'right',
			);
		}

		public static function getCarouselNavTypes() {
			return array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Rounded', 'porto-functionality' ) => 'rounded-nav',
				__( 'Big & Full Width', 'porto-functionality' ) => 'big-nav',
				__( 'Simple Arrow 1', 'porto-functionality' ) => 'nav-style-1',
				__( 'Simple Arrow 2', 'porto-functionality' ) => 'nav-style-2',
				__( 'Simple Arrow 3', 'porto-functionality' ) => 'nav-style-4',
				__( 'Square Grey Arrow', 'porto-functionality' ) => 'nav-style-3',
			);
		}

		public static function getEasingMethods() {
			return array(
				__( 'easingSinusoidalIn', 'porto-functionality' )     => 'easingSinusoidalIn',
				__( 'easingSinusoidalOut', 'porto-functionality' )    => 'easingSinusoidalOut',
				__( 'easingSinusoidalInOut', 'porto-functionality' )  => 'easingSinusoidalInOut',
				__( 'easingQuadraticIn', 'porto-functionality' )      => 'easingQuadraticIn',
				__( 'easingQuadraticOut', 'porto-functionality' )     => 'easingQuadraticOut',
				__( 'easingQuadraticInOut', 'porto-functionality' )   => 'easingQuadraticInOut',
				__( 'easingCubicIn', 'porto-functionality' )          => 'easingCubicIn',
				__( 'easingCubicOut', 'porto-functionality' )         => 'easingCubicOut',
				__( 'easingCubicInOut', 'porto-functionality' )       => 'easingCubicInOut',
				__( 'easingQuarticIn', 'porto-functionality' )        => 'easingQuarticIn',
				__( 'easingQuarticOut', 'porto-functionality' )       => 'easingQuarticOut',
				__( 'easingQuarticInOut', 'porto-functionality' )     => 'easingQuarticInOut',
				__( 'easingQuinticIn', 'porto-functionality' )        => 'easingQuinticIn',
				__( 'easingQuinticOut', 'porto-functionality' )       => 'easingQuinticOut',
				__( 'easingQuinticInOut', 'porto-functionality' )     => 'easingQuinticInOut',
				__( 'easingExponentialIn', 'porto-functionality' )    => 'easingExponentialIn',
				__( 'easingExponentialOut', 'porto-functionality' )   => 'easingExponentialOut',
				__( 'easingExponentialInOut', 'porto-functionality' ) => 'easingExponentialInOut',
				__( 'easingCircularIn', 'porto-functionality' )       => 'easingCircularIn',
				__( 'easingCircularOut', 'porto-functionality' )      => 'easingCircularOut',
				__( 'easingCircularInOut', 'porto-functionality' )    => 'easingCircularInOut',
				__( 'easingBackIn', 'porto-functionality' )           => 'easingBackIn',
				__( 'easingBackOut', 'porto-functionality' )          => 'easingBackOut',
				__( 'easingBackInOut', 'porto-functionality' )        => 'easingBackInOut',
			);
		}

		public static function getShapeDivider() {
			return array(
				'triangle'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M500,98.9L0,6.1V0h1000v6.1L500,98.9z"></path></svg>',
				'slant'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 L0 0 L100 0 Z"></path></svg>',
				'bigtriangle'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M738,99l262-93V0H0v5.6L738,99z"></path></svg>',
				'split'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 20" preserveAspectRatio="none"><path d="M0,0v3c0,0,393.8,0,483.4,0c9.2,0,16.6,7.4,16.6,16.6c0-9.1,7.4-16.6,16.6-16.6C606.2,3,1000,3,1000,3V0H0z"></path></svg>',
				'curved'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 C 60 0 75 0 100 100 Z"></path></svg>',
				'big-half-circle' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" ><path d="M0 100 C40 0 60 0 100 100 Z"></path></svg>',
				'clouds'          => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M-5 100 Q 0 20 5 100 Z"></path><path d="M0 100 Q 5 0 10 100"></path><path d="M5 100 Q 10 30 15 100"></path><path d="M10 100 Q 15 10 20 100"></path> <path d="M15 100 Q 20 30 25 100"></path><path d="M20 100 Q 25 -10 30 100"></path><path d="M25 100 Q 30 10 35 100"></path><path d="M30 100 Q 35 30 40 100"></path><path d="M35 100 Q 40 10 45 100"></path><path d="M40 100 Q 45 50 50 100"></path><path d="M45 100 Q 50 20 55 100"></path><path d="M50 100 Q 55 40 60 100"></path><path d="M55 100 Q 60 60 65 100"></path><path d="M60 100 Q 65 50 70 100"></path><path d="M65 100 Q 70 20 75 100"></path><path d="M70 100 Q 75 45 80 100"></path><path d="M75 100 Q 80 30 85 100"></path><path d="M80 100 Q 85 20 90 100"></path><path d="M85 100 Q 90 50 95 100"></path><path d="M90 100 Q 95 25 100 100"></path><path d="M95 100 Q 100 15 105 100 Z"></path></svg>',
				'horizon'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -0.5 1024 178" preserveAspectRatio="none"><path d="M1024 177.371H0V.219l507.699 133.939L1024 .219v177.152z" opacity="0.12"></path><path d="M1024 177.781H0V39.438l507.699 94.925L1024 39.438v138.343z" opacity="0.18"></path><path d="M1024 177.781H0v-67.892l507.699 24.474L1024 109.889v67.892z" opacity="0.24"></path><path d="M1024 177.781H0v-3.891l507.699-39.526L1024 173.889v3.892z"></path></svg>',
				'waves'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 54 1024 162" preserveAspectRatio="none"><path class="st3" d="M1024.1 54.368c-4 .2-8 .4-11.9.7-206.5 15.1-227.9 124.4-434.5 141.6-184.9 15.5-226.3-41.1-404.9-21.3-64 7.2-121.9 20.8-172.7 37.9v3.044h1024V54.368z"></path></svg>',
				'waves_opacity'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 216" preserveAspectRatio="none"><path d="M1024.1 1.068c-19.4-.5-38.7-1.6-57.7-.3-206.6 15-248.5 126.6-455 143.8-184.8 15.5-285.7-60.9-464.3-41.3-16.9 1.8-32.5 4.4-47.1 7.6l.1 105.2h1024v-215z" opacity="0.12"></path><path d="M1024.1 20.068c-30.2-1.6-59.6-1.6-86.8.4-206.6 15.1-197.3 122.6-403.9 139.8-184.9 15.5-278.5-58.2-457.1-38.4-28.3 3.2-53.5 8.2-76.2 14.6v79.744h1024V20.068z" opacity="0.18"></path><path d="M1024.1 46.668c-22.2-.3-43.8.2-64.2 1.7-206.6 15-197.8 112.5-404.4 129.7-184.8 15.5-226.8-51.1-405.4-31.3-54.8 6-104.9 18.3-150 33.7v35.744h1024V46.668z" style="opacity="0.24"></path><path d="M1024.1 54.368c-4 .2-8 .4-11.9.7-206.5 15.1-227.9 124.4-434.5 141.6-184.9 15.5-226.3-41.1-404.9-21.3-64 7.2-121.9 20.8-172.7 37.9v3.044h1024V54.368z"></path></svg>',
				'waves_brush'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 283.5 27.8" preserveAspectRatio="none"><path d="M283.5,9.7c0,0-7.3,4.3-14,4.6c-6.8,0.3-12.6,0-20.9-1.5c-11.3-2-33.1-10.1-44.7-5.7 s-12.1,4.6-18,7.4c-6.6,3.2-20,9.6-36.6,9.3C131.6,23.5,99.5,7.2,86.3,8c-1.4,0.1-6.6,0.8-10.5,2c-3.8,1.2-9.4,3.8-17,4.7 c-3.2,0.4-8.3,1.1-14.2,0.9c-1.5-0.1-6.3-0.4-12-1.6c-5.7-1.2-11-3.1-15.8-3.7C6.5,9.2,0,10.8,0,10.8V0h283.5V9.7z M260.8,11.3 c-0.7-1-2-0.4-4.3-0.4c-2.3,0-6.1-1.2-5.8-1.1c0.3,0.1,3.1,1.5,6,1.9C259.7,12.2,261.4,12.3,260.8,11.3z M242.4,8.6 c0,0-2.4-0.2-5.6-0.9c-3.2-0.8-10.3-2.8-15.1-3.5c-8.2-1.1-15.8,0-15.1,0.1c0.8,0.1,9.6-0.6,17.6,1.1c3.3,0.7,9.3,2.2,12.4,2.7 C239.9,8.7,242.4,8.6,242.4,8.6z M185.2,8.5c1.7-0.7-13.3,4.7-18.5,6.1c-2.1,0.6-6.2,1.6-10,2c-3.9,0.4-8.9,0.4-8.8,0.5 c0,0.2,5.8,0.8,11.2,0c5.4-0.8,5.2-1.1,7.6-1.6C170.5,14.7,183.5,9.2,185.2,8.5z M199.1,6.9c0.2,0-0.8-0.4-4.8,1.1 c-4,1.5-6.7,3.5-6.9,3.7c-0.2,0.1,3.5-1.8,6.6-3C197,7.5,199,6.9,199.1,6.9z M283,6c-0.1,0.1-1.9,1.1-4.8,2.5s-6.9,2.8-6.7,2.7 c0.2,0,3.5-0.6,7.4-2.5C282.8,6.8,283.1,5.9,283,6z M31.3,11.6c0.1-0.2-1.9-0.2-4.5-1.2s-5.4-1.6-7.8-2C15,7.6,7.3,8.5,7.7,8.6 C8,8.7,15.9,8.3,20.2,9.3c2.2,0.5,2.4,0.5,5.7,1.6S31.2,11.9,31.3,11.6z M73,9.2c0.4-0.1,3.5-1.6,8.4-2.6c4.9-1.1,8.9-0.5,8.9-0.8 c0-0.3-1-0.9-6.2-0.3S72.6,9.3,73,9.2z M71.6,6.7C71.8,6.8,75,5.4,77.3,5c2.3-0.3,1.9-0.5,1.9-0.6c0-0.1-1.1-0.2-2.7,0.2 C74.8,5.1,71.4,6.6,71.6,6.7z M93.6,4.4c0.1,0.2,3.5,0.8,5.6,1.8c2.1,1,1.8,0.6,1.9,0.5c0.1-0.1-0.8-0.8-2.4-1.3 C97.1,4.8,93.5,4.2,93.6,4.4z M65.4,11.1c-0.1,0.3,0.3,0.5,1.9-0.2s2.6-1.3,2.2-1.2s-0.9,0.4-2.5,0.8C65.3,10.9,65.5,10.8,65.4,11.1 z M34.5,12.4c-0.2,0,2.1,0.8,3.3,0.9c1.2,0.1,2,0.1,2-0.2c0-0.3-0.1-0.5-1.6-0.4C36.6,12.8,34.7,12.4,34.5,12.4z M152.2,21.1 c-0.1,0.1-2.4-0.3-7.5-0.3c-5,0-13.6-2.4-17.2-3.5c-3.6-1.1,10,3.9,16.5,4.1C150.5,21.6,152.3,21,152.2,21.1z"></path><path d="M269.6,18c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3 C267.7,18.8,269.7,18,269.6,18z"></path><path d="M227.4,9.8c-0.2-0.1-4.5-1-9.5-1.2c-5-0.2-12.7,0.6-12.3,0.5c0.3-0.1,5.9-1.8,13.3-1.2 S227.6,9.9,227.4,9.8z"></path><path d="M204.5,13.4c-0.1-0.1,2-1,3.2-1.1c1.2-0.1,2,0,2,0.3c0,0.3-0.1,0.5-1.6,0.4 C206.4,12.9,204.6,13.5,204.5,13.4z"></path><path d="M201,10.6c0-0.1-4.4,1.2-6.3,2.2c-1.9,0.9-6.2,3.1-6.1,3.1c0.1,0.1,4.2-1.6,6.3-2.6 S201,10.7,201,10.6z"></path><path d="M154.5,26.7c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3 C152.6,27.5,154.6,26.8,154.5,26.7z"></path><path d="M41.9,19.3c0,0,1.2-0.3,2.9-0.1c1.7,0.2,5.8,0.9,8.2,0.7c4.2-0.4,7.4-2.7,7-2.6 c-0.4,0-4.3,2.2-8.6,1.9c-1.8-0.1-5.1-0.5-6.7-0.4S41.9,19.3,41.9,19.3z"></path><path d="M75.5,12.6c0.2,0.1,2-0.8,4.3-1.1c2.3-0.2,2.1-0.3,2.1-0.5c0-0.1-1.8-0.4-3.4,0 C76.9,11.5,75.3,12.5,75.5,12.6z"></path><path d="M15.6,13.2c0-0.1,4.3,0,6.7,0.5c2.4,0.5,5,1.9,5,2c0,0.1-2.7-0.8-5.1-1.4 C19.9,13.7,15.7,13.3,15.6,13.2z"></path></svg>',
				'hills'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 74 1024 107" preserveAspectRatio="none"><path d="M0 182.086h1024v-77.312c-49.05 20.07-120.525 42.394-193.229 42.086-128.922-.512-159.846-72.294-255.795-72.294-89.088 0-134.656 80.179-245.043 82.022S169.063 99.346 49.971 97.401C32.768 97.094 16.077 99.244 0 103.135v78.951z"></path></svg>',
				'hills_opacity'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -0.5 1024 182" preserveAspectRatio="none"><path d="M0 182.086h1024V41.593c-28.058-21.504-60.109-37.581-97.075-37.581-112.845 0-198.144 93.798-289.792 93.798S437.658 6.777 351.846 6.777s-142.234 82.125-238.49 82.125c-63.078 0-75.776-31.744-113.357-53.658L0 182.086z" opacity="0.12"></path><path d="M1024 181.062v-75.878c-39.731 15.872-80.794 27.341-117.658 25.805-110.387-4.506-191.795-109.773-325.53-116.224-109.158-5.12-344.166 120.115-429.466 166.298H1024v-.001z" opacity="0.18"></path><path d="M0 182.086h1024V90.028C966.451 59.103 907.059 16.3 824.115 15.071 690.278 13.023 665.19 102.93 482.099 102.93S202.138-1.62 74.24.019C46.49.326 21.811 4.217 0 9.849v172.237z" opacity="0.24"></path><path d="M0 182.086h1024V80.505c-37.171 19.558-80.691 35.328-139.571 36.25-151.142 2.355-141.619-28.57-298.496-29.184s-138.854 47.002-305.459 43.725C132.813 128.428 91.238 44.563 0 28.179v153.907z" opacity="0.3"></path><path d="M0 182.086h1024v-77.312c-49.05 20.07-120.525 42.394-193.229 42.086-128.922-.512-159.846-72.294-255.795-72.294-89.088 0-134.656 80.179-245.043 82.022S169.063 99.346 49.971 97.401C32.768 97.094 16.077 99.244 0 103.135v78.951z"></path></svg>',
				'zigzag'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 5.8" preserveAspectRatio="none"><path d="M5.4.4l5.4 5.3L16.5.4l5.4 5.3L27.5.4 33 5.7 38.6.4l5.5 5.4h.1L49.9.4l5.4 5.3L60.9.4l5.5 5.3L72 .4l5.5 5.3L83.1.4l5.4 5.3L94.1.4l5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3L161 .4l5.4 5.3L172 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3L261 .4l5.4 5.3L272 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3L361 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3L461 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1L550 .4l5.4 5.3L561 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2L650 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2L750 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2L850 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4V0H-.2v5.8z"></path></svg>',
				'book'            => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M194,99c186.7,0.7,305-78.3,306-97.2c1,18.9,119.3,97.9,306,97.2c114.3-0.3,194,0.3,194,0.3s0-91.7,0-100c0,0,0,0,0-0 L0,0v99.3C0,99.3,79.7,98.7,194,99z"></path></svg>',
				'arrow'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 10" preserveAspectRatio="none"><path d="M350,10L340,0h20L350,10z"></path></svg>',
			);
		}

		public static function getDividerType() {
			return array(
				__( 'None', 'porto-functionality' )        => 'none',
				__( 'Triangle', 'porto-functionality' )    => 'triangle',
				__( 'Slant', 'porto-functionality' )       => 'slant',
				__( 'Triangle Asymmetrical', 'porto-functionality' ) => 'bigtriangle',
				__( 'Split', 'porto-functionality' )       => 'split',
				__( 'Curved', 'porto-functionality' )      => 'curved',
				__( 'Big Half Circle', 'porto-functionality' ) => 'big-half-circle',
				__( 'Clouds', 'porto-functionality' )      => 'clouds',
				__( 'Horizon', 'porto-functionality' )     => 'horizon',
				__( 'Waves', 'porto-functionality' )       => 'waves',
				__( 'Waves Opacity', 'porto-functionality' ) => 'waves_opacity',
				__( 'Waves Brush', 'porto-functionality' ) => 'waves_brush',
				__( 'Hills', 'porto-functionality' )       => 'hills',
				__( 'Hills Opacity', 'porto-functionality' ) => 'hills_opacity',
				__( 'Zigzag', 'porto-functionality' )      => 'zigzag',
				__( 'Book', 'porto-functionality' )        => 'book',
				__( 'Arrow', 'porto-functionality' )       => 'arrow',
				__( 'Custom', 'porto-functionality' )      => 'custom',
			);
		}

		public static function getImageSizes() {
			global $_wp_additional_image_sizes;

			$sizes = array(
				__( 'Default', 'porto-functionality' ) => '',
				__( 'Full', 'porto-functionality' )    => 'full',
			);

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$sizes[ $_size . ' ( ' . get_option( "{$_size}_size_w" ) . 'x' . get_option( "{$_size}_size_h" ) . ( get_option( "{$_size}_crop" ) ? '' : ', false' ) . ' )' ] = $_size;
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size . ' ( ' . $_wp_additional_image_sizes[ $_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size ]['height'] . ( $_wp_additional_image_sizes[ $_size ]['crop'] ? '' : ', false' ) . ' )' ] = $_size;
				}
			}
			return $sizes;
		}
	}
}

function porto_shortcode_widget_title( $params = array( 'title' => '' ) ) {
	if ( '' == $params['title'] ) {
		return '';
	}

	$extraclass = ( isset( $params['extraclass'] ) ) ? ' ' . $params['extraclass'] : '';
	$output     = '<h4 class="wpb_heading' . $extraclass . '">' . $params['title'] . '</h4>';

	return apply_filters( 'wpb_widget_title', $output, $params );
}

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'porto_animation_type', 'porto_theme_vc_animation_type_field' );
	vc_add_shortcode_param( 'porto_theme_animation_type', 'porto_theme_vc_animation_type_field' );
}

function porto_theme_vc_animation_type_field( $settings, $value ) {
	$param_line = '<select name="' . $settings['param_name'] . '" class="wpb_vc_param_value dropdown wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '">';

	$param_line .= '<option value="">none</option>';

	$param_line .= '<optgroup label="' . __( 'Attention Seekers', 'porto-functionality' ) . '">';
	$options     = array( 'bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble', 'zoomIn' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Bouncing Entrances', 'porto-functionality' ) . '">';
	$options     = array( 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Bouncing Exits', 'porto-functionality' ) . '">';
	$options     = array( 'bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Fading Entrances', 'porto-functionality' ) . '">';
	$options     = array( 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Fading Exits', 'porto-functionality' ) . '">';
	$options     = array( 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Flippers', 'porto-functionality' ) . '">';
	$options     = array( 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Lightspeed', 'porto-functionality' ) . '">';
	$options     = array( 'lightSpeedIn', 'lightSpeedOut' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Rotating Entrances', 'porto-functionality' ) . '">';
	$options     = array( 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Rotating Exits', 'porto-functionality' ) . '">';
	$options     = array( 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Sliding Entrances', 'porto-functionality' ) . '">';
	$options     = array( 'slideInUp', 'slideInDown', 'slideInLeft', 'slideInRight', 'maskUp' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Sliding Exit', 'porto-functionality' ) . '">';
	$options     = array( 'slideOutUp', 'slideOutDown', 'slideOutLeft', 'slideOutRight' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Specials', 'porto-functionality' ) . '">';
	$options     = array( 'hinge', 'rollIn', 'rollOut' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '<optgroup label="' . __( 'Reveal with Color', 'porto-functionality' ) . '">';
	$options     = array( 'revealDirTop', 'revealDirDown', 'revealDirLeft', 'revealDirRight' );
	foreach ( $options as $option ) {
		$selected = '';
		if ( $option == $value ) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
	}
	$param_line .= '</optgroup>';

	$param_line .= '</select>';

	return $param_line;
}

function porto_getCategoryChildsFull( $parent_id, $pos, $array, $level, &$dropdown ) {

	for ( $i = $pos; $i < count( $array ); $i ++ ) {
		if ( $array[ $i ]->category_parent == $parent_id ) {
			$name       = str_repeat( '- ', $level ) . $array[ $i ]->name;
			$value      = $array[ $i ]->slug;
			$dropdown[] = array(
				'label' => $name,
				'value' => $value,
			);
			porto_getCategoryChildsFull( $array[ $i ]->term_id, 0, $array, $level + 1, $dropdown );
		}
	}
}

function porto_sc_parse_google_font( $fonts_string ) {
	if ( ! class_exists( 'Vc_Google_Fonts' ) ) {
		return false;
	}
	$google_fonts_param = new Vc_Google_Fonts();
	$field_settings     = array();
	$fonts_data         = $fonts_string ? $google_fonts_param->_vc_google_fonts_parse_attributes( $field_settings, $fonts_string ) : '';
	return $fonts_data;
}
function porto_sc_google_font_styles( $fonts_data ) {

	$inline_style = '';
	if ( $fonts_data ) {
		$styles      = array();
		$font_family = explode( ':', $fonts_data['values']['font_family'] );
		$styles[]    = 'font-family:' . $font_family[0];
		$font_styles = explode( ':', $fonts_data['values']['font_style'] );

		if ( ! empty( $font_styles[1] ) ) {
			$styles[]    = 'font-weight:' . $font_styles[1];
		}
		if ( ! empty( $font_styles[2] ) ) {
			$styles[]    = 'font-style:' . $font_styles[2];
		}

		foreach ( $styles as $attribute ) {
			$inline_style .= $attribute . '; ';
		}
	}

	return $inline_style;
}
function porto_sc_enqueue_google_fonts( $fonts_data ) {

	global $porto_settings, $porto_google_fonts;

	if ( ! isset( $porto_google_fonts ) && function_exists( 'porto_settings_google_fonts' ) ) {
		$fonts              = porto_settings_google_fonts();
		$porto_google_fonts = array();
		foreach ( $fonts as $option => $weights ) {
			if ( isset( $porto_settings[ $option . '-font' ]['google'] ) && 'false' !== $porto_settings[ $option . '-font' ]['google'] ) {
				if ( isset( $porto_settings[ $option . '-font' ]['font-family'] ) && $porto_settings[ $option . '-font' ]['font-family'] && ! in_array( $porto_settings[ $option . '-font' ]['font-family'], $porto_google_fonts ) ) {
					$porto_google_fonts[] = $porto_settings[ $option . '-font' ]['font-family'];
				}
			}
		}
	}

	$fonts_str  = '';
	$fonts_name = '';
	foreach ( $fonts_data as $font_data ) {

		if ( ! isset( $font_data['values']['font_family'] ) ) {
			continue;
		}
		$font_family = explode( ':', $font_data['values']['font_family'] );
		if ( in_array( $font_family[0], $porto_google_fonts ) ) {
			continue;
		}
		$porto_google_fonts[] = $font_family[0];
		if ( $fonts_str ) {
			$fonts_str .= '%7C';
		}
		$fonts_str  .= $font_data['values']['font_family'];
		$fonts_name .= $font_family[0];
	}
	if ( ! $fonts_str ) {
		return;
	}

	// Get extra subsets for settings (latin/cyrillic/etc)
	$charsets = array();
	$subsets  = '';
	if ( isset( $porto_settings['select-google-charset'] ) && $porto_settings['select-google-charset'] && isset( $porto_settings['google-charsets'] ) && $porto_settings['google-charsets'] ) {
		foreach ( $porto_settings['google-charsets'] as $charset ) {
			if ( $charset && ! in_array( $charset, $charsets ) ) {
				$charsets[] = $charset;
			}
		}
	}
	if ( ! empty( $charsets ) ) {
		$subsets = '&subset=' . implode( ',', $charsets );
	}

	// We also need to enqueue font from googleapis
	wp_enqueue_style(
		'porto_sc_google_fonts_' . urlencode( $fonts_name ),
		'//fonts.googleapis.com/css?family=' . $fonts_str . $subsets
	);
}

if ( ! function_exists( 'porto_strip_script_tags' ) ) :
	function porto_strip_script_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '/<script.*?\/script>/s', '', $content ) ? : $content;
		$content = preg_replace( '/<style.*?\/style>/s', '', $content ) ? : $content;
		return $content;
	}
endif;

if ( ! function_exists( 'porto_shortcode_is_ajax' ) ) :
	function porto_shortcode_is_ajax() {
		if ( function_exists( 'mb_strtolower' ) ) {
			return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && mb_strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
		} else {
			return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
		}
	}
endif;

if ( ! function_exists( 'porto_creative_grid_layout' ) ) :
	function porto_creative_grid_layout( $layout ) {
		if ( '1' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
			);
		}
		if ( '2' == $layout ) {
			return array(
				array(
					'height'   => '2-3',
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-3',
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
			);
		}
		if ( '3' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'large',
				),
				array(
					'height'   => '1',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
			);
		}
		if ( '4' == $layout ) {
			return array(
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '5-12',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '5-12',
					'width_md' => '1',
					'size'     => 'blog-masonry-small',
				),
			);
		}
		if ( '5' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '2-5',
					'width_md' => '1',
					'width_lg' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1',
					'width'    => '1-5',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-5',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-5',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-5',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-5',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'medium',
				),
			);
		}
		if ( '6' == $layout ) {
			return array(
				array(
					'height'   => '2-3',
					'width'    => '1-2',
					'width_md' => '1',
					'width_lg' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '2-3',
					'width'    => '1-4',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1',
					'width'    => '1-4',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-3',
					'width'    => '1-4',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'blog-grid-small',
				),
				array(
					'height'   => '1-3',
					'width'    => '1-4',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'blog-grid-small',
				),
				array(
					'height'   => '1-3',
					'width'    => '1-4',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'blog-grid-small',
				),
			);
		}
		if ( '7' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-2',
					'width_md' => '1-2',
					'size'     => 'large',
				),
			);
		}
		if ( '8' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-6',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-6',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
			);
		}
		if ( '9' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '2-5',
					'width_md' => '1',
					'width_lg' => '2-3',
					'size'     => 'blog-masonry',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-5',
					'width_md'  => '1-2',
					'width_lg'  => '1-3',
					'size'      => 'medium',
				),
			);
		}
		if ( '10' == $layout ) {
			return array(
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '2-3',
					'width_md'  => '1',
					'size'      => 'blog-grid',
				),
				array(
					'height'    => '1',
					'height_md' => '1',
					'width'     => '1-3',
					'width_md'  => '1-2',
					'size'      => 'blog-masonry',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-3',
					'width_md'  => '1-2',
					'size'      => 'medium',
				),
				array(
					'height'    => '1-2',
					'height_md' => '1-2',
					'width'     => '1-3',
					'width_md'  => '1-2',
					'size'      => 'medium',
				),
			);
		}
		if ( '11' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'blog-masonry-small',
				),
			);
		}
		if ( '12' == $layout ) {
			return array(
				array(
					'height'   => '1',
					'width'    => '5-12',
					'width_md' => '1',
					'width_lg' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1',
					'width'    => '3-12',
					'width_md' => '1-2',
					'width_lg' => '1-2',
					'size'     => 'large',
				),
				array(
					'height'   => '1-2',
					'width'    => '2-12',
					'width_md' => '1-2',
					'width_lg' => '1-4',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '2-12',
					'width_md' => '1-2',
					'width_lg' => '1-4',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '2-12',
					'width_md' => '1-2',
					'width_lg' => '1-4',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '2-12',
					'width_md' => '1-2',
					'width_lg' => '1-4',
					'size'     => 'medium',
				),
			);
		}
		if ( '13' == $layout ) {
			return array(
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-3',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-2',
					'width_md' => '2-3',
					'size'     => 'blog-masonry',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '5-12',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-3',
					'width_md' => '7-12',
					'size'     => 'blog-masonry-small',
				),
				array(
					'height'   => '1-2',
					'width'    => '1-4',
					'width_md' => '1-3',
					'size'     => 'medium',
				),
				array(
					'height'   => '1-2',
					'width'    => '5-12',
					'width_md' => '2-3',
					'size'     => 'blog-masonry',
				),
			);
		}
		if ( '14' == $layout ) {
			return array(
				array(
					'height'    => '3-5',
					'height_md' => '3-5',
					'width'     => '1-4',
					'width_md'  => '1-2',
					'size'      => 'blog-masonry-small',
				),
				array(
					'height'    => '1',
					'height_md' => '4-5',
					'width'     => '1-2',
					'width_md'  => '1-2',
					'size'      => 'blog-masonry',
				),
				array(
					'height'    => '2-5',
					'height_md' => '2-5',
					'width'     => '1-4',
					'width_md'  => '1-2',
					'size'      => 'medium',
				),
				array(
					'height'    => '3-5',
					'height_md' => '3-5',
					'width'     => '1-4',
					'width_md'  => '1-2',
					'size'      => 'blog-masonry-small',
				),
				array(
					'height'    => '2-5',
					'height_md' => '2-5',
					'width'     => '1-4',
					'width_md'  => '1-2',
					'size'      => 'medium',
				),
			);
		}
		return apply_filters( 'porto_creative_grid_layouts', false, $layout );
	}
endif;

if ( ! function_exists( 'porto_creative_masonry_layout' ) ) :
	function porto_creative_masonry_layout( $layout ) {
		if ( '1' == $layout ) {
			return array(
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-2',
					'width_md' => '1',
					'size'     => 'large',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
				array(
					'width'    => '1-4',
					'width_md' => '1-2',
					'size'     => 'medium',
				),
			);
		}
		return false;
	}
endif;

if ( ! function_exists( 'porto_creative_grid_style' ) ) :
	function porto_creative_grid_style( $layout, $grid_height, $selector, $spacing = false, $include_style = true, $unit = 'px', $item_selector = '.product-col', $grid_layout = 1 ) {
		if ( ! $layout ) {
			return false;
		}
		if ( 0 !== strpos( $selector, '#' ) && 0 !== strpos( $selector, '.' ) ) {
			$selector = '#' . $selector;
		}

		global $porto_settings;
		$widths     = array();
		$heights    = array();
		$heights_md = array();

		if ( empty( $unit ) ) {
			$unit = 'px';
		}

		$has_lg_grid = false;
		foreach ( $layout as $index => $grid ) {
			if ( ! in_array( $grid['width'] . ',' . $grid['width_md'], $widths ) ) {
				$widths[] = $grid['width'] . ',' . $grid['width_md'];
			}
			if ( isset( $grid['height'] ) && ! in_array( $grid['height'], $heights ) ) {
				$heights[ $index ] = $grid['height'];
			}
			if ( isset( $grid['height_md'] ) && ! in_array( $grid['height_md'], $heights_md ) ) {
				$heights_md[ $index ] = $grid['height_md'];
			}
			if ( isset( $grid['width_lg'] ) ) {
				$has_lg_grid = true;
			}
		}
		if ( $include_style ) {
			echo '<style scope="scope">';
		}
		$max_col = 1;
		foreach ( $widths as $width ) {
			$width     = explode( ',', $width )[0];
			$width_arr = explode( '-', $width );
			if ( count( $width_arr ) > 1 ) {
				$width_number = (int) $width_arr[0] / (int) $width_arr[1];
				if ( $max_col < $width_arr[1] ) {
					$max_col = (int) $width_arr[1];
				}
			} else {
				$width_number = (int) $width_arr[0];
			}
			$max_width = floor( $width_number * 1000000 ) / 10000;
			echo esc_html( $selector ) . ' .grid-col-' . esc_html( $width ) . '{ flex: 0 0 auto; width: ' . $max_width . '%; }';
		}
		echo esc_html( $selector ) . ' .grid-col-sizer { flex: 0 0 auto; width: ' . ( floor( 1000000 / $max_col ) / 10000 ) . '% }';
		foreach ( $heights as $height ) {
			$height_arr = explode( '-', $height );
			if ( count( $height_arr ) > 1 ) {
				$height_number = (int) $grid_height * (int) $height_arr[0] / (int) $height_arr[1];
			} else {
				$height_number = (int) $grid_height;
			}
			echo esc_html( $selector ) . ' .grid-height-' . $height . '{ height: ' . round( $height_number ) . esc_html( $unit ) . ' }';
		}
		if ( $has_lg_grid ) {
			$widths_lg = array();
			echo '@media (max-width: ' . ( $porto_settings['container-width'] + $porto_settings['grid-gutter-width'] - 1 ) . 'px) {';
			$max_col = 1;
			foreach ( $layout as $grid ) {
				if ( ! in_array( $grid['width_lg'], $widths_lg ) ) {
					$width_arr = explode( '-', $grid['width_lg'] );
					if ( count( $width_arr ) > 1 ) {
						$width_number = (int) $width_arr[0] / (int) $width_arr[1];
						if ( $max_col < $width_arr[1] ) {
							$max_col = (int) $width_arr[1];
						}
					} else {
						$width_number = (int) $width_arr[0];
					}
					$max_width = floor( $width_number * 1000000 ) / 10000;
					echo esc_html( $selector ) . ' .grid-col-lg-' . esc_html( $grid['width_lg'] ) . '{ flex: 0 0 auto; width: ' . $max_width . '%; }';
					$widths_lg[] = $grid['width_lg'];
				}
			}
			echo esc_html( $selector ) . ' .grid-col-sizer { flex: 0 0 ' . ( floor( 1000000 / $max_col ) / 10000 ) . '%; width: ' . ( floor( 1000000 / $max_col ) / 10000 ) . '% }';
			echo '}';
		}
		echo '@media (max-width: 767px) {';
		$max_col = 1;
		foreach ( $widths as $width ) {
			$width     = explode( ',', $width );
			$width_arr = explode( '-', $width[1] );
			if ( count( $width_arr ) > 1 ) {
				$width_number = (int) $width_arr[0] / (int) $width_arr[1];
				if ( $max_col < $width_arr[1] ) {
					$max_col = (int) $width_arr[1];
				}
			} else {
				$width_number = (int) $width_arr[0];
			}
			$max_width = floor( $width_number * 1000000 ) / 10000;
			echo esc_html( $selector ) . ' .grid-col-md-' . esc_html( $width[1] ) . '{ flex: 0 0 auto; width: ' . $max_width . '%; }';
		}
		echo esc_html( $selector ) . ' .grid-col-sizer { flex: 0 0 ' . ( floor( 1000000 / $max_col ) / 10000 ) . '%; width: ' . ( floor( 1000000 / $max_col ) / 10000 ) . '% }';
		foreach ( $heights as $index => $height ) {
			if ( isset( $heights_md[ $index ] ) ) {
				$height_arr = explode( '-', $heights_md[ $index ] );
				if ( count( $height_arr ) > 1 ) {
					$height_number = (int) $height_arr[0] / (int) $height_arr[1] * (int) $grid_height;
				} else {
					$height_number = (int) $height_arr[0] * (int) $grid_height;
				}
				echo esc_html( $selector ) . ' .grid-height-' . $height . '{ height: ' . $height_number . esc_html( $unit ) . '; }';
			} else {
				$height_arr = explode( '-', $height );
				if ( count( $height_arr ) > 1 ) {
					$height_number = (int) $grid_height * (int) $height_arr[0] / (int) $height_arr[1];
				} else {
					$height_number = (int) $grid_height;
				}
				echo esc_html( $selector ) . ' .grid-height-' . $height . '{ height: ' . round( $height_number / 1.5 ) . esc_html( $unit ) . '; }';
			}
		}
		echo '}';
		if ( 9 === (int) $grid_layout ) {
			echo '@media (min-width: 768px) and (max-width: 991px) {';
				echo esc_html( $selector ) . ' .product-col:last-child { display: none; }';
			echo '}';
		}
		echo '@media (max-width: 480px) {';
			echo esc_html( $selector ) . ' ' . $item_selector . ' { flex: 0 0 auto; width: 100%; }';
		echo '}';
		if ( false !== $spacing && '' !== $spacing ) {
			echo esc_html( $selector ) . ' .grid-creative { margin-left: -' . ( (int) $spacing / 2 ) . 'px; margin-right: -' . ( (int) $spacing / 2 ) . 'px; width: calc(100% + ' . intval( $spacing ) . esc_html( $unit ) . ') }';
			echo esc_html( $selector ) . ' ' . $item_selector . ' { padding: 0 ' . ( (int) $spacing / 2 ) . 'px ' . ( (int) $spacing ) . 'px; }';
		}
		if ( $include_style ) {
			echo '</style>';
		}
	}
endif;

if ( ! function_exists( 'porto_update_vc_options_to_elementor' ) ) :
	function porto_update_vc_options_to_elementor( $arr, $dependency = true ) {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}

		$arr_key          = '';
		$replace_selector = false;
		$option_type      = '';
		foreach ( $arr as $key => $option ) {
			if ( is_array( $option ) && is_numeric( $key ) ) {
				$result = porto_update_vc_options_to_elementor( $option, $dependency );
				if ( $result ) {
					unset( $arr[ $key ] );
					$arr[ array_keys( $result )[0] ] = array_values( $result )[0];
				}
				continue;
			}
			if ( 'type' == $key ) {
				if ( 'dropdown' == $option ) {
					$arr['type'] = \Elementor\Controls_Manager::SELECT;
				} elseif ( 'textfield' == $option || 'porto_animation_type' == $option ) {
					$arr['type'] = \Elementor\Controls_Manager::TEXT;
					if ( isset( $arr['value'] ) ) {
						$arr['default'] = $arr['value'];
					}
				} elseif ( 'checkbox' == $option ) {
					$arr['type'] = \Elementor\Controls_Manager::SWITCHER;
				} elseif ( 'porto_number' == $option ) {
					$arr['type']      = \Elementor\Controls_Manager::SLIDER;
					$replace_selector = true;
				} elseif ( 'colorpicker' == $option ) {
					$arr['type'] = \Elementor\Controls_Manager::COLOR;
				} elseif ( 'porto_button_group' == $option ) {
					$arr['type'] = \Elementor\Controls_Manager::CHOOSE;
					$option_type = 'porto_button_group';
				} elseif ( 'porto_image_select' == $option ) {
					$arr['type'] = 'image_choose';
				}
			} elseif ( 'param_name' == $key ) {
				unset( $arr[ $key ] );
				$arr_key = $option;
			} elseif ( 'heading' == $key ) {
				unset( $arr[ $key ] );
				$arr['label'] = $option;
			} elseif ( 'value' == $key ) {
				if ( is_array( $option ) ) {
					unset( $arr[ $key ] );
					if( 'porto_button_group' !== $option_type ) {
						$arr['options'] = array_combine( array_values( $option ), array_keys( $option ) );
					} else {
						$arr['options'] = $option;
					}
				}
			} elseif ( 'std' == $key ) {
				unset( $arr[ $key ] );
				$arr['default'] = $option;
			} elseif ( 'dependency' == $key && is_array( $option ) ) {
				unset( $arr[ $key ] );
				if ( isset( $option['element'] ) && isset( $option['value'] ) ) {
					$arr['condition'] = array( $option['element'] => $option['value'] );
				} elseif ( isset( $option['element'] ) && isset( $option['not_empty'] ) ) {
					$arr['condition'] = array( $option['element'] . '!' => '' );
				} elseif ( isset( $option['element'] ) && isset( $option['value_not_equal_to'] ) ) {
					$arr['condition'] = array( $option['element'] . '!' => $option['value_not_equal_to'] );
				} elseif ( isset( $option['element'] ) && isset( $option['is_empty'] ) ) {
					$arr['condition'] = array( $option['element'] => '' );
				}

				if ( isset( $arr_key ) && in_array( $arr_key, array( 'dots_pos_top', 'dots_pos_bottom', 'dots_pos_left', 'dots_pos_right', 'dots_br_color', 'dots_abr_color', 'dots_bg_color', 'dots_abg_color', 'dots_visible', 'dots_original', 'dots_pos', 'dots_style' ) ) ) {
					$element = $dependency ? 'pagination' : 'show_dots';
					$arr['condition'] = array_merge(
						$arr['condition'],
						array(
							$element => 'yes',
						)
					);
					if ( ! $dependency ) {
						unset( $arr['condition']['pagination'] );
						unset( $arr['condition']['pagination!'] );
					}
				} else if ( isset( $arr_key ) && in_array( $arr_key, array( 'nav_fs', 'nav_width', 'nav_height', 'nav_br', 'nav_h_pos', 'navs_h_origin', 'nav_v_pos', 'nav_color', 'nav_h_color', 'nav_bg_color', 'nav_h_bg_color', 'nav_br_color', 'nav_h_br_color', 'nav_visible', 'nav_type', 'nav_pos2', 'nav_pos', 'show_nav_hover' ) ) ) {
					$element = $dependency ? 'navigation' : 'show_nav';
					$arr['condition'] = array_merge(
						$arr['condition'],
						array(
							$element => 'yes',
						)
					);
					if ( ! $dependency ) {
						unset( $arr['condition']['navigation'] );
						unset( $arr['condition']['navigation!'] );
					}
				}
			} elseif ( 'units' == $key ) {
				unset( $arr[ $key ] );
				$arr['size_units'] = $option;
			} elseif ( 'selectors' == $key && $replace_selector ) {
				foreach ( $option as $key => $value ) {
					$option[ $key ] = str_replace( '{{VALUE}}', '{{SIZE}}', $value );
				}
				$arr['selectors'] = $option;
			} elseif ( 'range' == $key && is_array( $option ) ) {
				$arr['range'] = $option;
			}
		}
		unset( $arr['group'] );
		if ( $arr_key ) {
			return array( $arr_key => $arr );
		}
		return $arr;
	}
endif;

if ( ! function_exists( 'porto_gcd' ) ) :
	function porto_gcd( $a, $b = false ) {
		if ( is_array( $a ) ) {
			$len = count( $a );
			if ( 1 === $len ) {
				return $a[0];
			}
			if ( 2 === $len ) {
				return porto_gcd( $a[0], $a[1] );
			} elseif ( $len > 2 ) {
				$tmp = $a;
				unset( $tmp[ $len - 1 ] );
				return porto_gcd( $a[ $len - 1 ], porto_gcd( $tmp ) );
			}
		} else {
			$max = max( $a, $b );
			$min = min( $a, $b );
			$rem = $max % $min;
			$max = $min;
			$min = $rem;
			if ( 0 === $rem ) {
				return $max;
			} else {
				return porto_gcd( $max, $min );
			}
		}
	}
endif;

if ( ! function_exists( 'porto_lcm' ) ) :
	function porto_lcm( $a, $b = false ) {
		if ( is_array( $a ) ) {
			$len = count( $a );
			if ( 1 === $len ) {
				return $a[0];
			}
			if ( 2 === $len ) {
				return porto_lcm( $a[0], $a[1] );
			} else {
				$tmp = $a;
				unset( $tmp[ $len - 1 ] );
				return porto_lcm( $a[ $len - 1 ], porto_lcm( $tmp ) );
			}
		} else {
			return ( $a * $b ) / porto_gcd( $a, $b );
		}
	}
endif;

if ( ! function_exists( 'porto_shortcode_floating_fields' ) ) :
	function porto_shortcode_floating_fields() {
		$animation_group = __( 'Animation', 'porto-functionality' );
		return array(
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Rotate with Scroll', 'porto-functionality' ),
				'param_name'  => 'floating_circle',
				'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_floating_circle.gif"/>',
				'value'       => array( __( 'Yes, please', 'porto-functionality' ) => 'yes' ),
				'description' => __( 'Rotate when scrolling page.', 'porto-functionality' ),
				'dependency'  => array(
					'element' => 'animation_type',
					'value'   => array( '' ),
				),
				'group'       => $animation_group,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Floating Transition', 'porto-functionality' ),
				'param_name' => 'floatcircle_transition',
				'value'      => array( __( 'Yes, please', 'porto-functionality' ) => 'yes' ),
				'std'        => 'yes',
				'dependency'  => array(
					'element'   => 'floating_circle',
					'not_empty' => true,
				),
				'group'      => $animation_group,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Transition Duration', 'porto-functionality' ),
				'param_name'  => 'floatcircle_duration',
				'description' => __( 'numerical value (unit: milliseconds). Default is 500ms', 'porto-functionality' ),
				'dependency'  => array(
					'element'   => 'floatcircle_transition',
					'not_empty' => true,
				),
				'group'       => $animation_group,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Floating with Scroll (Start Pos)', 'porto-functionality' ),
				'param_name' => 'floating_start_pos',
				'hint'        => '<img src="' . PORTO_HINT_URL . 'wd_floating_start_pos.gif"/>',
				'value'      => array(
					__( 'Disabled', 'porto-functionality' ) => '',
					__( 'None', 'porto-functionality' )   => 'none',
					__( 'Top', 'porto-functionality' )    => 'top',
					__( 'Bottom', 'porto-functionality' ) => 'bottom',
				),
				'dependency' => array(
					'element'  => 'floating_circle',
					'is_empty' => true,
				),
				'group'      => $animation_group,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Floating Speed', 'porto-functionality' ),
				'param_name'  => 'floating_speed',
				'description' => __( 'numerical value (from 0.0 to 10.0)', 'porto-functionality' ),
				'value'       => '',
				'dependency'  => array(
					'element' => 'floating_start_pos',
					'value'   => array( 'none', 'top', 'bottom' ),
				),
				'group'       => $animation_group,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Floating Transition', 'porto-functionality' ),
				'param_name' => 'floating_transition',
				'value'      => array( __( 'Yes, please', 'porto-functionality' ) => 'yes' ),
				'std'        => 'yes',
				'dependency' => array(
					'element'   => 'floating_speed',
					'not_empty' => true,
				),
				'group'      => $animation_group,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Floating Horizontal', 'porto-functionality' ),
				'param_name' => 'floating_horizontal',
				'value'      => array( __( 'Yes, please', 'porto-functionality' ) => 'yes' ),
				'dependency' => array(
					'element'   => 'floating_speed',
					'not_empty' => true,
				),
				'group'      => $animation_group,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Transition Duration', 'porto-functionality' ),
				'param_name'  => 'floating_duration',
				'description' => __( 'numerical value (unit: milliseconds). Default is 500ms', 'porto-functionality' ),
				'dependency'  => array(
					'element'   => 'floating_speed',
					'not_empty' => true,
				),
				'group'       => $animation_group,
			),
		);
	}
endif;

if ( ! function_exists( 'porto_shortcode_add_floating_options' ) ) :
	function porto_shortcode_add_floating_options( $atts, $return_array = false ) {
		$floating_options = array();
		if ( isset( $atts['floating_circle'] ) && 'yes' == $atts['floating_circle'] ) {
			$floating_options['circle'] = true;
			
			if ( isset( $atts['floatcircle_transition'] ) && 'yes' == $atts['floatcircle_transition'] ) {
				$floating_options['transition'] = true;
				if ( isset( $atts['floatcircle_duration'] ) && $atts['floatcircle_duration'] ) {
					$floating_options['transitionDuration'] = absint( $atts['floatcircle_duration'] );
				}
			} else {
				$floating_options['transition'] = false;
			}
		} else {
			if ( ! isset( $atts['floating_start_pos'] ) || ! isset( $atts['floating_speed'] ) || empty( $atts['floating_start_pos'] ) || empty( $atts['floating_speed'] ) ) {
				return '';
			}
			$floating_options = array(
				'startPos' => $atts['floating_start_pos'],
				'speed'    => $atts['floating_speed'],
			);
			if ( ! isset( $atts['floating_transition'] ) || 'yes' == $atts['floating_transition'] ) {
				$floating_options['transition'] = true;
			} else {
				$floating_options['transition'] = false;
			}
			if ( isset( $atts['floating_horizontal'] ) && $atts['floating_horizontal'] ) {
				$floating_options['horizontal'] = true;
			} else {
				$floating_options['horizontal'] = false;
			}
			if ( isset( $atts['floating_duration'] ) && $atts['floating_duration'] ) {
				$floating_options['transitionDuration'] = absint( $atts['floating_duration'] );
			}
		}
		if ( !empty( $floating_options ) ) {
			if ( $return_array ) {
				return array(
					'data-plugin-float-element' => '',
					'data-plugin-options'       => esc_attr( json_encode( $floating_options ) ),
				);
			}
			return ' data-plugin-float-element data-plugin-options="' . esc_attr( json_encode( $floating_options ) ) . '"';
		}
	}
endif;

if ( ! function_exists( 'porto_elementor_if_dom_optimization' ) ) :

	function porto_elementor_if_dom_optimization() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}
		if ( version_compare( ELEMENTOR_VERSION, '3.18.9', '>' ) ) {
			return true;
		} elseif ( version_compare( ELEMENTOR_VERSION, '3.1.0', '>=' ) ) {
			return \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		} elseif ( version_compare( ELEMENTOR_VERSION, '3.0', '>=' ) ) {
			return ( ! \Elementor\Plugin::instance()->get_legacy_mode( 'elementWrappers' ) );
		}
		return false;
	}
endif;

if ( ! function_exists( 'porto_get_mpx_options' ) ) :
	function porto_get_mpx_options( $atts ) {
		$mpx_opts      = array();
		$mpx_attr_html = '';
		if ( 'yes' == $atts['mouse_parallax'] ) {
			if ( 'yes' == $atts['mouse_parallax_inverse'] ) {
				$mpx_opts['invertX'] = true;
				$mpx_opts['invertY'] = true;
			} else {
				$mpx_opts['invertX'] = false;
				$mpx_opts['invertY'] = false;
			}

			wp_enqueue_script( 'jquery-parallax' );
			$mpx_opts = array(
				'data-plugin'         => 'mouse-parallax',
				'data-options'        => json_encode( $mpx_opts ),
				'data-floating-depth' => empty( $atts['mouse_parallax_speed']['size'] ) ? 0.5 : floatval( $atts['mouse_parallax_speed']['size'] ),
			);
		}

		return $mpx_opts;
	}
endif;

if ( ! function_exists( 'porto_generate_rand' ) ) :
	function porto_generate_rand( $length = 31 ) {

		$valid_characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$rand             = '';
		for ( $n = 0; $n < $length; $n++ ) {

			$which_character = rand( 0, strlen( $valid_characters ) - 1 );
			$rand           .= substr( $valid_characters, $which_character, 1 );
		}

		return $rand;
	}
endif;

/**
 * Echo or Return inline css.
 * This function only uses for composed by style tag.
 *
 * @since 2.3.0
 */
if ( ! function_exists( 'porto_filter_inline_css' ) ) :
	function porto_filter_inline_css( $inline_css, $is_echo = true ) {
		if ( ! class_exists( 'Porto_Performance' ) ) {
			return;
		}
		if ( empty( Porto_Performance::$defer_style ) ) { // not defer loading, only return and echo
			if ( $is_echo ) {
				echo porto_filter_output( $inline_css );
			} else {
				return $inline_css;
			}
		} else {
			if ( 'no' == Porto_Performance::has_merged_css() ) {
				global $porto_body_merged_css;
				if ( isset( $porto_body_merged_css ) ) {
					$inline_css             = str_replace( PHP_EOL, '', $inline_css );
					$inline_css             = preg_replace( '/<style.*?>/s', '', $inline_css ) ? : $inline_css;
					$inline_css             = preg_replace( '/<\/style.*?>/s', '', $inline_css ) ? : $inline_css;
					$porto_body_merged_css .= $inline_css;
				}
			}
			return '';
		}
	}
endif;

/**
 * Get installed time.
 *
 * @since 2.5.0
 */
if ( ! function_exists( 'porto_installed_time' ) ) :
	function porto_installed_time() {
		$installed_time = get_option( 'porto_installed_time' );

		if ( ! $installed_time ) {
			$installed_time = time();

			update_option( 'porto_installed_time', $installed_time );
		}

		return $installed_time;
	}
endif;

/**
 * Get the url of particular id in theme option.
 * 
 * @since 2.7.0
 */
if ( ! function_exists( 'porto_get_theme_option_url' ) ) {
	function porto_get_theme_option_url( $option_id, $type = 'field' ) {
		if ( ! isset ( $GLOBALS['porto_option_style'] ) ) {
			$GLOBALS['porto_option_style'] = get_theme_mod( 'theme_options_use_new_style', false );
		}
		if ( $GLOBALS['porto_option_style'] ) {
			return esc_url( admin_url( 'customize.php?type=' . $type . '#' . $option_id ) );
		} else {
			return esc_url( admin_url( 'admin.php?page=porto_settings#' . $option_id ) );
		}
	}
}

/**
 * Get the box shadow for shortcodes
 * 
 * @since 2.9.4
 */
function porto_get_box_shadow( $content = null, $data = '' ) {

	$result = '';
	if ( $content ) {
		$mainstr = explode( '|', $content );
		$string  = '';
		$mainarr = array();
		if ( ! empty( $mainstr ) && is_array( $mainstr ) ) {
			foreach ( $mainstr as $key => $value ) {
				if ( ! empty( $value ) ) {
					$string = explode( ':', $value );
					if ( is_array( $string ) ) {
						if ( ! empty( $string[1] ) /*&& 'outset' != $string[1] */ ) {
							$mainarr[ $string[0] ] = $string[1];
						}
					}
				}
			}
		}

		$strkeys = '';
		if ( ! empty( $mainarr ) ) {
			if ( isset( $mainarr['color'] ) && $mainarr['color'] && ! empty( $mainarr['style'] ) ) {
				$strkeys .= isset( $mainarr['horizontal'] ) && 'px' != $mainarr['horizontal'] ? $mainarr['horizontal'] : '0';
				$strkeys .= ' ';
				$strkeys .= isset( $mainarr['vertical'] ) && 'px' != $mainarr['vertical'] ? $mainarr['vertical'] : '0';
				$strkeys .= ' ';
				$strkeys .= isset( $mainarr['blur'] ) && 'px' != $mainarr['blur'] ? $mainarr['blur'] : '0';
				$strkeys .= ' ';
				$strkeys .= isset( $mainarr['spread'] ) && 'px' != $mainarr['spread'] ? $mainarr['spread'] : '0';
				$strkeys .= ' ';
				$strkeys .= $mainarr['color'];
				$strkeys .= isset( $mainarr['style'] ) && $mainarr['style'] && 'outset' != $mainarr['style'] ? ' ' . $mainarr['style'] : '';
			} elseif ( isset( $mainarr['style'] ) && in_array( $mainarr['style'], array( 'inherit', 'none' ) ) ) {
				$strkeys .= $mainarr['style'];
			}
		}

		if ( $data ) {
			switch ( $data ) {
				case 'data':
					$result = $strkeys;
					break;
				case 'array':
					$result = $mainarr;
					break;
				case 'css':
				default:
					if ( $strkeys ) {
						$result = 'box-shadow:' . $strkeys . ';';
					}
					break;
			}
		} elseif ( $strkeys ) {
			$result = 'box-shadow:' . $strkeys . ';';
		}
	}

	return $result;
}

/**
 * Get the edit link
 * 
 * @since 2.10.0
 */
function porto_get_edit_link() {
	global $porto_edit_link, $wp_the_query;
	if ( ! isset( $porto_edit_link ) ) {

		$current_object = $wp_the_query->get_queried_object();

		if ( empty( $current_object ) ) {
			return;
		}

		if ( ! empty( $current_object->post_type ) ) {
			$post_type_object = get_post_type_object( $current_object->post_type );
			$edit_post_link   = get_edit_post_link( $current_object->ID );
			if ( $post_type_object
				&& $edit_post_link
				&& current_user_can( 'edit_post', $current_object->ID )
				&& $post_type_object->show_in_admin_bar ) {
				
				$porto_edit_link = $edit_post_link;
				
			}
		} elseif ( ! empty( $current_object->taxonomy ) ) {
			$tax            = get_taxonomy( $current_object->taxonomy );
			$edit_term_link = get_edit_term_link( $current_object->term_id, $current_object->taxonomy );
			if ( $tax && $edit_term_link && current_user_can( 'edit_term', $current_object->term_id ) ) {
				
				$porto_edit_link = $edit_term_link;
			}
		} elseif ( is_a( $current_object, 'WP_User' ) && current_user_can( 'edit_user', $current_object->ID ) ) {
			$edit_user_link = get_edit_user_link( $current_object->ID );
			if ( $edit_user_link ) {
				$porto_edit_link = $edit_user_link;
			}
		}

		if ( empty( $porto_edit_link ) ) {
			$archive_id = -1;
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				$archive_id = wc_get_page_id( 'shop' );
			}
			if ( function_exists( 'is_porto_portfolios_page' ) && is_porto_portfolios_page() && ( $archive_page = porto_portfolios_page_id() ) ) {
				$archive_id = $archive_page;
			} 
			if ( function_exists( 'is_porto_members_page' ) && is_porto_members_page() && ( $archive_page = porto_members_page_id() ) ) {
				$archive_id = $archive_page;
			} 
			if ( function_exists( 'is_porto_faqs_page' ) && is_porto_faqs_page() && ( $archive_page = porto_faqs_page_id() ) ) {
				$archive_id = $archive_page;
			} 
			if ( function_exists( 'is_porto_events_page' ) && is_porto_events_page() && ( $archive_page = porto_events_page_id() ) ) {
				$archive_id = $archive_page;
			}
			if ( $archive_id != -1 ) {
				$porto_edit_link = get_edit_post_link( $archive_id );
			}
		}
		
	}
	return $porto_edit_link;
}

/**
 * Get the xl width
 * 
 * @since 2.11.0
 */
if ( ! function_exists( 'porto_get_xl_width' ) ) {
	function porto_get_xl_width( $xl = true ) {
		global $porto_settings;
		$container_xl = 1140;
		$container_sl = (int) $porto_settings['container-width'];
		if ( (int) $porto_settings['container-width'] < 1360 ) {
			$container_xl = (int) $porto_settings['container-width'];
			$container_sl = 1360;
		}
		if ( $xl ) {
			return $container_xl + (int) $porto_settings['grid-gutter-width'];
		}
		return $container_sl + 2 * (int) $porto_settings['grid-gutter-width'];
	}
}
