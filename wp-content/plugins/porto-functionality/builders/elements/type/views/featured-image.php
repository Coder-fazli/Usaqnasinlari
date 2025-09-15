<?php

global $porto_settings, $porto_post_image_size, $product;

if ( $porto_post_image_size ) {
	$image_size = $porto_post_image_size;
} else {
	$image_size = isset( $atts['image_size'] ) ? $atts['image_size'] : 'full';
}

$image_id    = false;
$image_link  = '';
$post_title  = '';
$link_target = '';
$zoom_icon   = empty( $atts['zoom_icon'] ) ? 'fas fa-search' : $atts['zoom_icon'];

if ( isset( $atts['add_link'] ) && 'custom' == $atts['add_link'] && ! empty( $atts['custom_url'] ) ) {
	$image_link = $atts['custom_url'];
	if ( isset( $atts['link_target'] ) ) {
		$link_target = $atts['link_target'];
	}
}

if ( ( $current_object = get_queried_object() ) && ! empty( $current_object->term_id ) ) {
	if ( $current_object->taxonomy && in_array( $current_object->taxonomy, array( 'portfolio_cat', 'product_cat', 'event_cat' ) ) ) {
		$image_id = get_metadata( $current_object->taxonomy, $current_object->term_id, 'category_image', true );
		if ( $image_id ) {
			$image_id = porto_get_image_id( esc_url( $image_id ) );
		}
	}
	if ( $current_object->taxonomy && 'pwb-brand' == $current_object->taxonomy ) { // perfect brands plugin
		$image_id = get_term_meta( $current_object->term_id, 'pwb_brand_image', true );
	}
	if ( ! $image_id ) {
		$image_id = get_term_meta( $current_object->term_id, 'thumbnail_id', true );
	}

	if ( ! $image_link && ( ! isset( $atts['add_link'] ) || 'no' != $atts['add_link'] ) ) {
		$image_link = get_term_link( $current_object );
	}
	$post_title = $current_object->label;
} else {
	if ( ! empty( $product ) ) {
		$image_id = $product->get_image_id();
	} else {
		$featured_images = porto_get_featured_images();
		if ( count( $featured_images ) ) {
			$image_id = $featured_images[0]['attachment_id'];
		}
	}
	if ( $image_id ) {
		if ( ! $image_link && ( ! isset( $atts['add_link'] ) || 'no' != $atts['add_link'] ) ) {
			$image_link = get_permalink();
		}
	}
	$post_title = get_the_title();
}

$image_type = isset( $atts['image_type'] ) ? $atts['image_type'] : '';
if ( ! $image_id ) {
	if ( ! empty( $atts['show_content_hover'] ) ) {
		$image_id = -1;
		if ( ! $image_link && ( ! isset( $atts['add_link'] ) || 'no' != $atts['add_link'] ) ) {
			$image_link = get_permalink();
		}
	} elseif ( ( ( isset( $_GET['action'] ) && 'elementor' != $_GET['action'] && is_admin() && ! empty( get_current_screen() ) && get_current_screen()->is_block_editor() ) || wp_is_json_request() ) && ( empty( $_POST['action'] ) || 'elementor_ajax' != $_POST['action'] ) ) { // in the block editor
		$image_id = -1;
	} else {
		if ( 'video' != $image_type ) {
			return;
		}
	}
}


$wrap_cls   = 'porto-tb-featured-image' . ( $image_type ? ' tb-image-type-' . $image_type : ' tb-image-type-default' );
$wrap_attrs = ' data-title="' . esc_attr( $post_title ) . '"';
$video_html = '';

// Reveal Animation
if ( isset( $atts['animation_reveal_dir'] ) && false !== strpos( $atts['animation_reveal_dir'], 'revealDir' ) ) {
	$wrap_attrs .= ' data-appear-animation="' . esc_attr( $atts['animation_reveal_dir'] ) . '"';
	$wrap_attrs .= ' data-animation-reveal-clr="' . ( ! empty( $atts['animation_reveal_clr'] ) ? esc_attr( $atts['animation_reveal_clr'] ) : '' ) . '"';
	if ( isset( $atts['animation_duration'] ) ) {
		$wrap_attrs .= ' data-appear-animation-duration="' . esc_attr( $atts['animation_duration'] ) . '"';
	}
	if ( isset( $atts['animation_delay'] ) ) {
		$wrap_attrs .= ' data-appear-animation-delay="' . esc_attr( $atts['animation_delay'] ) . '"';
	}
}
// Hover Overlay Image
$dynamic_content = ! empty( $atts['dynamic_content'] ) ? $atts['dynamic_content'] : array();
$overlay_image   = '';
if ( $dynamic_content && ! empty( $dynamic_content['source'] ) ) {
	$field_name = '';
	if ( 'post' == $dynamic_content['source'] ) {
		if ( isset( $dynamic_content['post_info'] ) ) {
			$field_name = $dynamic_content['post_info'];
		}
	} else {
		if ( isset( $dynamic_content[ $dynamic_content['source'] ] ) ) {
			$field_name = $dynamic_content[ $dynamic_content['source'] ];
		}
	}
	if ( $field_name ) {
		$overlay_image = apply_filters( 'porto_dynamic_tags_content', '', null, $dynamic_content['source'], $field_name );
	}

	if ( ! empty( $overlay_image ) ) {
		if ( is_numeric( $overlay_image ) ) {
			$overlay_image = array(
				'id'  => $overlay_image,
				'src' => wp_get_attachment_image_url( $overlay_image, 'full' ),
			);
		} else {
			$overlay_image = array(
				'id'  => rand( 1000, 9999 ),
				'src' => $overlay_image,
			);
		}
	}
}

if ( isset( $atts['hover_effect'] ) ) {
	if ( ( 'hover3d' == $atts['hover_effect'] || 'hover3d-zoom' == $atts['hover_effect'] ) && empty( $image_type ) ) {
		$wrap_cls   .= ' hover-effect-3d';
		$wrap_attrs .= ' data-hover3d-selector=".img-thumbnail"';

		if ( ! wp_style_is( 'jquery-hover3d' ) ) {
			wp_enqueue_script( 'jquery-hover3d', PORTO_SHORTCODES_URL . 'assets/js/jquery.hover3d.min.js', array(), PORTO_FUNC_VERSION, true );
		}
	}
	if ( ! empty( $overlay_image ) ) {
		$wrap_attrs .= ' data-hoverlay-image="' . esc_attr( json_encode( $overlay_image ) ) . '" data-hoverlay-id="' . esc_attr( $overlay_image['id'] ) . '"';
	}
}

if ( ! empty( $atts['show_content_hover'] ) && $content && isset( $atts['hover_start_effect'] ) && 'hoverdir' == $atts['hover_start_effect'] ) {
	$wrap_cls   .= ' hover-effect-dir';
	$wrap_attrs .= ' data-plugin-options="' . esc_attr( json_encode( array( 'hoverElem' => '.tb-hover-content' ) ) ) . '"';
	if ( ! wp_style_is( 'modernizr' ) ) {
		wp_enqueue_script( 'modernizr' );
	}
	wp_enqueue_script( 'jquery-hoverdir' );
}

// image types
$attachment_ids = array();

if ( ! empty( $image_type ) && ( 'hover' == $image_type || 'slider' == $image_type || 'gallery' == $image_type ) ) {
	if ( $product ) {
		$attachment_ids = $product->get_gallery_image_ids();
		if ( ! empty( $attachment_ids ) ) {
			array_unshift( $attachment_ids, $image_id );
		}
	}
	if ( empty( $attachment_ids ) ) {
		$attachment_ids = porto_get_featured_images();
	}

	if ( count( $attachment_ids ) > 1 ) {
		if ( 'gallery' == $image_type ) {
			$wrap_cls .= ' has-ccols ccols-2 ccols-md-3';
			if ( empty( $atts['image_size'] ) ) {
				$image_size = ! empty( $porto_settings['enable-portfolio'] ) ? 'portfolio-grid' : 'blog-medium';
			}
		}
	}
} elseif ( 'video' == $image_type ) {
	
	if ( $product ) {
		$video_source = get_post_meta( get_the_ID(), 'porto_video_source', true );
		$video_poster = get_post_meta( get_the_ID(), 'porto_video_thumbnail_poster', true );
		if ( '' == $video_source || 'shortcode' == $video_source ) {
			
			$ids          = get_post_meta( get_the_ID(), 'porto_product_video_thumbnails' );
			if ( ! empty( $ids ) ) {
				$url    = wp_get_attachment_url( $ids[0] );
				$poster = get_the_post_thumbnail_url( $ids[0] );
				if ( ! $poster ) {
					$poster = wp_get_attachment_image_url( $image_id, 'full' );
				}
				$video_html .= do_shortcode( '[video src="' . esc_url( $url ) . '" poster="' . esc_url( $poster ) . '"]' );
			} else {
				// with video thumbnail shortcode
				$video_code = get_post_meta( get_the_ID(), 'porto_product_video_thumbnail_shortcode', true );
				if ( false !== strpos( $video_code, '[video ' ) ) {
					preg_match( '/poster="([^\"]*)"/', $video_code, $poster );
					$poster      = empty( $poster ) ? wp_get_attachment_image_url( $image_id, 'full' ) : $poster[1];
					$video_html .= do_shortcode( preg_replace( '/poster="([^\"]*)"/', 'poster="' . esc_url( $poster ) . '"', $video_code ) );
				} else {
					$youtube_id = preg_match( '/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/', $video_code, $matches );
					if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
						$youtube_id = $matches[1];
					} else {
						$youtube_id = '';
					}
					if ( $youtube_id ) {
						wp_enqueue_script( 'porto-video-api' );
						$video_html .= '<div id="ytplayer_' . porto_generate_rand( 4 ) . '" class="porto-video-social video-youtube" data-video="' . esc_attr( $youtube_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
					} else {
						$vimeo_id = preg_match( '/^(?:https?:\/\/)?(?:www|player\.)?(?:vimeo\.com\/)?(?:video\/|external\/)?(\d+)([^.?&#"\'>]?)/', $video_code, $matches );
						if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
							$vimeo_id = $matches[1];
						} else {
							$vimeo_id = '';
						}
						if ( $vimeo_id ) {
							wp_enqueue_script( 'porto-video-api' );
							$video_html .= '<div id="vmplayer_' . porto_generate_rand( 4 ) . '" class="porto-video-social video-vimeo" data-video="' . esc_attr( $vimeo_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
						}
					}
				}
			}
		} else if ( 'mp4' == $video_source ) {
			$ids          = get_post_meta( get_the_ID(), 'porto_product_video_thumbnails' );
			if ( ! empty( $ids ) && ! empty( $ids[0] ) ) {
				$url    = wp_get_attachment_url( $ids[0] );
				$poster = get_the_post_thumbnail_url( $ids[0] );
				if ( ! $poster ) {
					$poster = wp_get_attachment_image_url( $image_id, 'full' );
				}
				if ( ! $video_poster ) {
					$video_poster = $poster;
				}
				$video_html .= do_shortcode( '[video src="' . esc_url( $url ) . '" poster="' . esc_url( $video_poster ) . '"]' );
			}
		} else if ( 'youtube' == $video_source ) {
			$video_code = get_post_meta( get_the_ID(), 'porto_video_youtube', true );
			$youtube_id = preg_match( '/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/', $video_code, $matches );
			if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
				$youtube_id = $matches[1];
			} else {
				$youtube_id = '';
			}
			if ( $youtube_id ) {
				wp_enqueue_script( 'porto-video-api' );
				$video_html .= '<div id="ytplayer_' . porto_generate_rand( 4 ) . '" class="porto-video-social video-youtube" data-video="' . esc_attr( $youtube_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
			} 
		} else if ( 'vimeo' == $video_source ) {
			$video_code = get_post_meta( get_the_ID(), 'porto_video_vimeo', true );
			$vimeo_id = preg_match( '/^(?:https?:\/\/)?(?:www|player\.)?(?:vimeo\.com\/)?(?:video\/|external\/)?(\d+)([^.?&#"\'>]?)/', $video_code, $matches );
			if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
				$vimeo_id = $matches[1];
			} else {
				$vimeo_id = '';
			}
			if ( $vimeo_id ) {
				wp_enqueue_script( 'porto-video-api' );
				$video_html .= '<div id="vmplayer_' . porto_generate_rand( 4 ) . '" class="porto-video-social video-vimeo" data-video="' . esc_attr( $vimeo_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
			}
		}

	} else {
		$video_html .= do_shortcode( get_post_meta( get_the_ID(), 'video_code', true ) );
	}
}

if ( ! empty( $product ) ) {
	$wrap_cls .= ' product-image';
}

if ( ! empty( $atts['hover_effect'] ) && ( empty( $image_type ) || 'slider' == $image_type || 'gallery' == $image_type ) ) {
	if ( 'hover3d-zoom' == $atts['hover_effect'] ) {
		$wrap_cls .= ' porto-img-zoom';
	} elseif ( 'hover3d' != $atts['hover_effect'] ) {
		$wrap_cls .= ' porto-img-' . $atts['hover_effect'];
	}
}

if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( 'porto_elements_wrap_css_class', $wrap_cls, $atts, 'porto-tb/porto-featured-image' ) ) . '"' . $wrap_attrs . '>';

if ( ! empty( $atts['show_badges'] ) && ! empty( $product ) ) {
	woocommerce_show_product_loop_sale_flash();
}

if ( count( $attachment_ids ) > 1 && ( 'slider' == $image_type || 'gallery' == $image_type ) ) {

	if ( 'slider' == $image_type ) {
		echo '<div class="porto-carousel owl-carousel nav-inside nav-inside-center nav-style-2 show-nav-hover has-ccols ccols-1" data-plugin-options="' . esc_attr( json_encode( array( 'loop' => false, 'nav' => false ) ) ) . '">';
	}


	$large_src = array();
	foreach ( $attachment_ids as $img_id ) {
		$attachment = porto_get_attachment( is_array( $img_id ) ? $img_id['attachment_id'] : $img_id );
		if ( ! $attachment ) {
			continue;
		}

		echo '<a aria-label="post featured image carousel" href="' . esc_url_raw( $image_link ) . '"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . ' class="img-thumbnail">';

		echo wp_get_attachment_image( is_array( $img_id ) ? $img_id['attachment_id'] : $img_id, $image_size, false, array( 'class' => 'img-responsive' ) );
		if ( 'gallery' == $image_type && ! empty( $atts['zoom'] ) ) {
			echo '<span class="zoom ' . esc_attr( $zoom_icon ) . '" ' . ( 'slider' == $image_type ? 'data-src' : 'data-mfp-src' ) . '="' . esc_url( $attachment['src'] ) . '" data-title="' . esc_attr( $attachment['caption'] ) . '"></span>';
		}

		$large_src[] = array( $attachment['src'], $attachment['caption'] );

		echo '</a>';
	}

	if ( 'slider' == $image_type ) {

		echo '</div>';
		if ( ! empty( $atts['zoom'] ) ) {
			echo '<div class="zoom ' . esc_attr( $zoom_icon ) . '">';
			foreach ( $large_src as $full_src ) {
				echo '<a data-title="' . esc_attr( $full_src[1] ) . '" href="' . esc_url( $full_src[0] ) . '"></a>';
			}
			echo '</div>';
		}
	}
} else {

	if ( $image_link && ! $video_html ) { // Compatibility Yith badge management
		echo apply_filters( 'yith_wcbm_product_thumbnail_container', '<a aria-label="post featured image" href="' . esc_url_raw( $image_link ) . '"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . ' class="img-thumbnail">' );
	}

	if ( ! empty( $video_html ) ) {
		wp_enqueue_script( 'jquery-fitvids' );
		echo '<div class="img-thumbnail fit-video">';
		echo porto_filter_output( $video_html );
		echo '</div>';
	} else {
		if ( ! $image_link ) {
			echo '<div class="img-thumbnail">';
		}
		
		if ( -1 == $image_id ) {
			if ( ! empty( $product ) ) {
				$place_link = wc_placeholder_img_src( 'woocommerce_single' );
			} else {
				$place_link = PORTO_URI . '/images/placeholder.jpg';
			}
			echo '<img class="img-responsive" src="' . $place_link . '" />';
		} else {
			echo wp_get_attachment_image( $image_id, $image_size, false, array( 'class' => 'img-responsive' ) );
		}
		if ( ! $image_link ) {
			echo '</div>';
		}
	}

	if ( 'hover' == $image_type && count( $attachment_ids ) > 1 ) {
		echo wp_get_attachment_image( is_array( $attachment_ids[1] ) ? $attachment_ids[1]['attachment_id'] : $attachment_ids[1], $image_size, false, array( 'class' => 'img-responsive hover-image' ) );
	}

	if ( $image_link && ! $video_html ) {
		echo '</a>';
	}
}

if ( ! empty( $atts['show_content_hover'] ) && $content ) {
	if ( isset( $atts['hover_start_effect'] ) && 'mouse_tracking' == $atts['hover_start_effect'] ) {
		wp_enqueue_script( 'porto-gsap' );
		if ( empty( $atts['offset'] ) ) {
			$atts['offset'] = 0;
		}
	}
	echo '<div class="tb-hover-content' . ( empty( $atts['hover_start_effect'] ) ? '' : ' hover-start-' . esc_attr( $atts['hover_start_effect'] ) ) . ( isset( $atts['hover_start_effect'] ) && 'mouse_tracking' == $atts['hover_start_effect'] ?  ' thumb-info-floating-element-wrapper' : '' ) . ( $image_link ? ' with-link' : '' ) . '"' . ( isset( $atts['hover_start_effect'] ) && 'mouse_tracking' == $atts['hover_start_effect'] ? ( ' data-plugin-tfloating={"offset":' . $atts['offset'] . '}' ) : '' ) . '>';
	if ( $image_link ) {
		echo '<a aria-label="post content" href="' . esc_url_raw( $image_link ) . '" class="porto-tb-link"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . '></a>';
	}
		echo do_blocks( $content );

	echo '</div>';
}

// image lightbox
if ( ! empty( $atts['zoom'] ) && 'slider' != $image_type && 'gallery' != $image_type && empty( $video_html ) ) {
	$attachment = porto_get_attachment( $image_id );
	
	if ( -1 == $image_id ) {
		$place_link = PORTO_URI . '/images/placeholder.jpg';
		if ( ! empty( $product ) ) {
			$place_link = wc_placeholder_img_src( 'woocommerce_single' );
		}
		$attachment = array(
			'src'     => $place_link,
			'caption' => esc_html__( 'Porto Placeholder', 'porto-functionality' ), 
		);
	}
	if ( $attachment ) {
		echo '<span class="zoom ' . esc_attr( $zoom_icon ) . '" data-mfp-src="' . esc_url( $attachment['src'] ) . '" data-title="' . esc_attr( $attachment['caption'] ) . '"></span>';
	}
}

echo '</div>';
