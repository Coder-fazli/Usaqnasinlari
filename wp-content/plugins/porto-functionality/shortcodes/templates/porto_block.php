<?php
$load_posts_only = function_exists( 'porto_is_ajax' ) && porto_is_ajax() && isset( $_GET['load_posts_only'] );

/**
 * Do not render block in ajax requests
 *
 * load_posts_only: 2 means this block is rending template builder content
 */
if ( $load_posts_only && '2' != $_GET['load_posts_only'] ) {
	return false;
}

$output = $id = $name = $animation_type = $animation_duration = $animation_delay = $el_class = '';
extract(
	shortcode_atts(
		array(
			'id'                   => '',
			'name'                 => '',
			'animation_type'       => '',
			'animation_duration'   => 1000,
			'animation_delay'      => 0,
			'animation_reveal_clr' => '',
			'post_type'            => '',
			'not_render_home'      => '',
			'el_class'             => '',
		),
		$atts
	)
);

if ( empty( $post_type ) ) {
	$post_type = 'porto_builder';
}

if ( ( $not_render_home && is_front_page() ) || ! post_type_exists( $post_type ) ) {
	return;
}

if ( $id || $name ) {
	global $wpdb;
	$el_class = porto_shortcode_extract_class( $el_class );

	if ( $id ) {
		$where = is_numeric( $id ) ? 'ID' : 'post_name';
	} else {
		$where = is_numeric( $name ) ? 'ID' : 'post_name';
	}

	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND $where = %s", $post_type, $id ? absint( $id ) : sanitize_text_field( $name ) ) );

	if ( $post_id ) {
		$post_id = porto_multi_lang_post_id( $post_id, $post_type );
	}

	if ( $post_id ) {
		$post_id     = (int) $post_id;
		$before_html = '';
		global $porto_settings;
		// Add edit link for admins.
		if ( empty( $porto_settings['disable-builder-tooltip'] ) && current_user_can( 'edit_pages' ) && ! is_customize_preview() && (
				( ! function_exists( 'vc_is_inline' ) || ! vc_is_inline() ) &&
				( ! function_exists( 'porto_is_elementor_preview' ) || ! porto_is_elementor_preview() ) &&
				( ! function_exists( 'porto_is_vc_preview' ) || ! porto_is_vc_preview() )
				) ) {
			if ( defined( 'VCV_VERSION' ) && 'fe' == get_post_meta( $post_id, 'vcv-be-editor', true ) ) {
				$edit_link = admin_url( 'post.php?post=' . $post_id . '&action=edit&vcv-action=frontend&vcv-source-id=' . $post_id );
			} elseif ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $post_id, '_elementor_edit_mode', true ) ) {
				$edit_link = admin_url( 'post.php?post=' . $post_id . '&action=elementor' );
			} else {
				$edit_link = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
			}
			$builder_type = get_post_meta( $post_id, PortoBuilders::BUILDER_TAXONOMY_SLUG, true );
			if ( ! $builder_type ) {
				$builder_type = __( 'Template', 'porto-functionality' );
			}
			/* translators: template name */
			$before_html = '<div class="pb-edit-link" data-builder-id="' . esc_attr( $post_id ) . '" data-builder-type="' . esc_attr( $builder_type ) . '" data-title="' . sprintf( esc_attr__( 'Edit %1$s: %2$s', 'porto-functionality' ), esc_attr( $builder_type ), esc_attr( get_the_title( $post_id ) ) ) . '" data-link="' . esc_url( $edit_link ) . '"';
			if ( ! empty( $atts['tracking'] ) ) {
				$tracking_title = esc_attr__( 'Track options', 'porto-functionality' );
				if ( 0 === strpos( $atts['tracking'], 'option-' ) ) {
					$url = porto_get_theme_option_url( substr( $atts['tracking'], 7 ) ); // option-content-top
				} else if ( 0 === strpos( $atts['tracking'], 'meta-' ) ) {
					$url = porto_get_edit_link() . '#' . substr( $atts['tracking'], 5 ); // meta-content-inner-top
				} else if ( 0 === strpos( $atts['tracking'], 'layout-' ) ) {
					$url = admin_url( 'admin.php?page=porto-page-layouts#' ) . $atts['tracking']; // layout-header-23
				}
				if ( ! empty( $url ) ) {
					$before_html .= ' data-tracking-title="' . $tracking_title . '" data-tracking-url="' . $url . '"';
				}
				$before_html .= ' data-tracking="' . $atts['tracking'] . '"';
			}
			$before_html .= '></div>';
		}

		$the_post = get_post( $post_id, null, 'display' );

		$shortcodes_custom_css = '';
		$is_elementor_edited   = false;

		if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $post_id, '_elementor_edit_mode', true ) && get_post_meta( $post_id, '_elementor_data', true ) ) {
			$is_elementor_edited = true;
		}

		$inner_container = get_post_meta( $post_id, 'container', true );

		if ( $is_elementor_edited ) {
			if ( ! wp_style_is( 'elementor-frontend', 'enqueued' ) ) {
				wp_enqueue_style( 'elementor-icons' );
				wp_enqueue_style( 'elementor-animations' );
				wp_enqueue_style( 'elementor-frontend' );
				wp_enqueue_style( 'swiper' );
				do_action( 'elementor/frontend/after_enqueue_styles' );
			}
			$css_file               = new Elementor\Core\Files\CSS\Post( $post_id );
			
			$upload        = wp_upload_dir();
			$upload_dir    = $upload['basedir'];
			$upload_url    = $upload['baseurl'];
			$post_css_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post_id . '.css' );

			// filesystem
			global $wp_filesystem;
			// Initialize the WordPress filesystem, no more using file_put_contents function
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$post_css_exists = file_exists( $post_css_path );
			if ( $post_css_exists && 'internal' !== get_option( 'elementor_css_print_method' ) ) {
				if ( ! wp_style_is( 'elementor-post-' . $post_id ) ) {
					$block_css = $wp_filesystem->get_contents( $post_css_path );
					if ( $block_css ) {
						$shortcodes_custom_css .= $block_css;
					}
				}
			} else {
				$post_id_elementor_css  = $css_file->get_content();
				$shortcodes_custom_css .= $post_id_elementor_css;

				// Save block css as elementor post css.
				if ( ! $post_css_exists ) {
					$wp_filesystem->put_contents( $post_css_path, $post_id_elementor_css, FS_CHMOD_FILE );
				}
			}

			$post_content  = $before_html;
			$post_content .= '<div class="porto-block' . ( function_exists( 'porto_is_elementor_preview' ) && porto_is_elementor_preview() ? '" data-elementor-title="' . esc_attr__( 'Template', 'porto-functionality' ) . '" data-elementor-id="' . intval( $post_id ) . '" data-el_cls="elementor elementor-' . intval( $post_id ) : ' elementor elementor-' . intval( $post_id ) ) . '" data-id="' . intval( $post_id ) . '">';
			if ( 'fluid' == $inner_container ) {
				$post_content .= '<div class="container-fluid">';
			}
			ob_start();

			echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $post_id ); //phpcs:ignore

			$post_content .= ob_get_clean();
			if ( 'fluid' == $inner_container ) {
				$post_content .= '</div>';
			}
			$post_content .= '</div>';
		} else {
			$post_content = $the_post->post_content;
			if ( defined( 'VCV_VERSION' ) ) {
				$post_content = vcfilter( 'vcv:frontend:content', $post_content );

				$bundle_url = get_post_meta( $post_id, 'vcvSourceCssFileUrl', true );
				if ( $bundle_url ) {
					if ( 0 !== strpos( $bundle_url, 'http' ) ) {
						if ( false === strpos( $bundle_url, 'assets-bundles' ) ) {
							$bundle_url = '/assets-bundles/' . $bundle_url;
						}
					}
					$handle = 'vcv:assets:source:main:styles:' . vchelper( 'Str' )->slugify( $bundle_url );
					if ( ! wp_style_is( $handle, 'enqueued' ) ) {
						$path                   = vchelper( 'Assets' )->getFilePath( str_replace( '/assets-bundles', '', $bundle_url ) );
						$shortcodes_custom_css .= vchelper( 'File' )->getContents( $path );
					}
				}
			}

			$use_google_map = get_post_meta( $post_id, 'porto_page_use_google_map_api', true );

			if ( '1' === $use_google_map || stripos( $post_content, '[porto_google_map' ) ) {
				wp_enqueue_script( 'googleapis' );
			}
			if ( stripos( $post_content, '[porto_concept ' ) ) {
				wp_enqueue_script( 'modernizr' );
				wp_enqueue_style( 'jquery-flipshow' );
			}


			if ( class_exists( 'Ultimate_VC_Addons' ) ) {
				$isajax              = false;
				$ultimate_ajax_theme = get_option( 'ultimate_ajax_theme' );
				if ( 'enable' == $ultimate_ajax_theme ) {
					$isajax = true;
				}
				$dependancy = array( 'jquery' );

				$bsf_options             = get_option( 'bsf_options' );
				$ultimate_global_scripts = ( isset( $bsf_options['ultimate_global_scripts'] ) ) ? $bsf_options['ultimate_global_scripts'] : false;

				if ( 'enable' !== $ultimate_global_scripts ) {
					if ( stripos( $post_content, 'font_call:' ) ) {
						preg_match_all( '/font_call:(.*?)"/', $post_content, $display );
						enquque_ultimate_google_fonts_optimzed( $display[1] );
					}

					$ultimate_js  = get_option( 'ultimate_js', 'disable' );
					$bsf_dev_mode = ( isset( $bsf_options['dev_mode'] ) ) ? $bsf_options['dev_mode'] : false;

					if ( ( 'enable' == $ultimate_js || $isajax ) && ( 'enable' != $bsf_dev_mode ) ) {
						if (
							stripos( $post_content, '[swatch_container' )
							|| stripos( $post_content, '[ultimate_modal' )
						) {
							wp_enqueue_script( 'ultimate-modernizr' );
						}

						if ( stripos( $post_content, '[ultimate_exp_section' ) ||
							stripos( $post_content, '[info_circle' ) ) {
							wp_enqueue_script( 'jquery_ui' );
						}

						if ( stripos( $post_content, '[icon_timeline' ) ) {
							wp_enqueue_script( 'masonry' );
						}

						if ( $isajax ) { // if ajax site load all js
							wp_enqueue_script( 'masonry' );
						}

						if ( stripos( $post_content, '[ultimate_google_map' ) ) {
							if ( defined( 'DISABLE_ULTIMATE_GOOGLE_MAP_API' ) && ( DISABLE_ULTIMATE_GOOGLE_MAP_API == true || DISABLE_ULTIMATE_GOOGLE_MAP_API == 'true' ) ) {
								$load_map_api = false;
							} else {
								$load_map_api = true;
							}
							if ( $load_map_api ) {
								wp_enqueue_script( 'googleapis' );
							}
						}

						if ( stripos( $post_content, '[ultimate_modal' ) ) {
							//$modal_fixer = get_option('ultimate_modal_fixer');
							//if($modal_fixer === 'enable')
							//wp_enqueue_script('ultimate-modal-all-switched');
							//else
							wp_enqueue_script( 'ultimate-modal-all' );
						}
					} elseif ( 'disable' == $ultimate_js ) {
						wp_enqueue_script( 'ultimate-vc-params' );

						if (
							stripos( $post_content, '[ultimate_spacer' )
							|| stripos( $post_content, '[ult_buttons' )
							|| stripos( $post_content, '[ult_team' )
							|| stripos( $post_content, '[ultimate_icon_list' )
						) {
							wp_enqueue_script( 'ultimate-custom' );
						}
						if (
							stripos( $post_content, '[just_icon' )
							|| stripos( $post_content, '[ult_animation_block' )
							|| stripos( $post_content, '[icon_counter' )
							|| stripos( $post_content, '[ultimate_google_map' )
							|| stripos( $post_content, '[icon_timeline' )
							|| stripos( $post_content, '[bsf-info-box' )
							|| stripos( $post_content, '[info_list' )
							|| stripos( $post_content, '[ultimate_info_table' )
							|| stripos( $post_content, '[interactive_banner_2' )
							|| stripos( $post_content, '[interactive_banner' )
							|| stripos( $post_content, '[ultimate_pricing' )
							|| stripos( $post_content, '[ultimate_icons' )
						) {
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'ultimate-custom' );
						}
						if ( stripos( $post_content, '[ultimate_heading' ) ) {
							wp_enqueue_script( 'ultimate-headings-script' );
						}
						if ( stripos( $post_content, '[ultimate_carousel' ) ) {
							wp_enqueue_script( 'ult-slick' );
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'ult-slick-custom' );
						}
						if ( stripos( $post_content, '[ult_countdown' ) ) {
							wp_enqueue_script( 'jquery.timecircle' );
							wp_enqueue_script( 'jquery.countdown' );
						}
						if ( stripos( $post_content, '[icon_timeline' ) ) {
							wp_enqueue_script( 'masonry' );
						}
						if ( stripos( $post_content, '[ultimate_info_banner' ) ) {
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'utl-info-banner-script' );
						}
						if ( stripos( $post_content, '[ultimate_google_map' ) ) {
							if ( defined( 'DISABLE_ULTIMATE_GOOGLE_MAP_API' ) && ( DISABLE_ULTIMATE_GOOGLE_MAP_API == true || DISABLE_ULTIMATE_GOOGLE_MAP_API == 'true' ) ) {
								$load_map_api = false;
							} else {
								$load_map_api = true;
							}
							if ( $load_map_api ) {
								wp_enqueue_script( 'googleapis' );
							}
						}
						if ( stripos( $post_content, '[swatch_container' ) ) {
							wp_enqueue_script( 'ultimate-modernizr' );
							wp_enqueue_script( 'swatchbook-js' );
						}
						if ( stripos( $post_content, '[ult_ihover' ) ) {
							wp_enqueue_script( 'ult_ihover_js' );
						}
						if ( stripos( $post_content, '[ult_hotspot' ) ) {
							wp_enqueue_script( 'ult_hotspot_tooltipster_js' );
							wp_enqueue_script( 'ult_hotspot_js' );
						}
						if ( stripos( $post_content, '[ult_content_box' ) ) {
							wp_enqueue_script( 'ult_content_box_js' );
						}
						if ( stripos( $post_content, '[bsf-info-box' ) ) {
							wp_enqueue_script( 'info_box_js' );
						}
						if ( stripos( $post_content, '[icon_counter' ) ) {
							wp_enqueue_script( 'flip_box_js' );
						}
						if ( stripos( $post_content, '[ultimate_ctation' ) ) {
							wp_enqueue_script( 'utl-ctaction-script' );
						}
						if ( stripos( $post_content, '[stat_counter' ) ) {
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'ult-stats-counter-js' );
							//wp_enqueue_script('ult-slick-custom');
							wp_enqueue_script( 'ultimate-custom' );
							array_push( $dependancy, 'stats-counter-js' );
						}
						if ( stripos( $post_content, '[ultimate_video_banner' ) ) {
							wp_enqueue_script( 'ultimate-video-banner-script' );
						}
						if ( stripos( $post_content, '[ult_dualbutton' ) ) {
							wp_enqueue_script( 'jquery.dualbtn' );

						}
						if ( stripos( $post_content, '[ult_createlink' ) ) {
							wp_enqueue_script( 'jquery.ult_cllink' );
						}
						if ( stripos( $post_content, '[ultimate_img_separator' ) ) {
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'ult-easy-separator-script' );
							wp_enqueue_script( 'ultimate-custom' );
						}

						if ( stripos( $post_content, '[ult_tab_element' ) ) {
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'ult_tabs_rotate' );
							wp_enqueue_script( 'ult_tabs_acordian_js' );
						}
						if ( stripos( $post_content, '[ultimate_exp_section' ) ) {
							wp_enqueue_script( 'jquery_ui' );
							wp_enqueue_script( 'jquery_ultimate_expsection' );
						}

						if ( stripos( $post_content, '[info_circle' ) ) {
							wp_enqueue_script( 'jquery_ui' );
							wp_enqueue_script( 'ultimate-appear' );
							wp_enqueue_script( 'info-circle' );
							//wp_enqueue_script('info-circle-ui-effect');
						}

						if ( stripos( $post_content, '[ultimate_modal' ) ) {
							wp_enqueue_script( 'ultimate-modernizr' );
							//$modal_fixer = get_option('ultimate_modal_fixer');
							//if($modal_fixer === 'enable')
							//wp_enqueue_script('ultimate-modal-all-switched');
							//else
							wp_enqueue_script( 'ultimate-modal-all' );
						}

						if ( stripos( $post_content, '[ult_sticky_section' ) ) {
							wp_enqueue_script( 'ult_sticky_js' );
							wp_enqueue_script( 'ult_sticky_section_js' );
						}

						if ( stripos( $post_content, '[ult_team' ) ) {
							wp_enqueue_script( 'ultimate-team' );
						}
					}

					$ultimate_css = get_option( 'ultimate_css' );

					if ( 'enable' == $ultimate_css ) {
						if ( stripos( $post_content, '[ultimate_carousel' ) ) {
							wp_enqueue_style( 'ult-icons' );
						}
					} else {

						$ib_2_found = false;
						$ib_found   = false;

						if ( stripos( $post_content, '[ult_animation_block' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
						}
						if ( stripos( $post_content, '[icon_counter' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ult-flip-style' );
						}
						if ( stripos( $post_content, '[ult_countdown' ) ) {
							wp_enqueue_style( 'ult-countdown' );
						}
						if ( stripos( $post_content, '[ultimate_icon_list' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-tooltip' );
						}
						if ( stripos( $post_content, '[ultimate_carousel' ) ) {
							wp_enqueue_style( 'ult-slick' );
							wp_enqueue_style( 'ult-icons' );
							wp_enqueue_style( 'ultimate-animate' );
						}
						if ( stripos( $post_content, '[ultimate_fancytext' ) ) {
							wp_enqueue_style( 'ultimate-fancytext-style' );
						}
						if ( stripos( $post_content, '[ultimate_ctation' ) ) {
							wp_enqueue_style( 'utl-ctaction-style' );
						}
						if ( stripos( $post_content, '[ult_buttons' ) ) {
							wp_enqueue_style( 'ult-btn' );
						}
						if ( stripos( $post_content, '[ultimate_heading' ) ) {
							wp_enqueue_style( 'ultimate-headings-style' );
						}
						if ( stripos( $post_content, '[ultimate_icons' ) || stripos( $post_content, '[single_icon' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-tooltip' );
						}
						if ( stripos( $post_content, '[ult_ihover' ) ) {
							wp_enqueue_style( 'ult_ihover_css' );
						}
						if ( stripos( $post_content, '[ult_hotspot' ) ) {
							wp_enqueue_style( 'ult_hotspot_css' );
							wp_enqueue_style( 'ult_hotspot_tooltipster_css' );
						}
						if ( stripos( $post_content, '[ult_content_box' ) ) {
							wp_enqueue_style( 'ult_content_box_css' );
						}
						if ( stripos( $post_content, '[bsf-info-box' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'info-box-style' );
						}
						if ( stripos( $post_content, '[info_circle' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'info-circle' );
						}
						if ( stripos( $post_content, '[ultimate_info_banner' ) ) {
							wp_enqueue_style( 'utl-info-banner-style' );
							wp_enqueue_style( 'ultimate-animate' );
						}
						if ( stripos( $post_content, '[icon_timeline' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-timeline-style' );
						}
						if ( stripos( $post_content, '[just_icon' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-tooltip' );
						}
						if ( stripos( $post_content, '[interactive_banner_2' ) ) {
							$ib_2_found = true;
						}
						if ( stripos( $post_content, '[interactive_banner' ) && ! stripos( $post_content, '[interactive_banner_2' ) ) {
							$ib_found = true;
						}
						if ( stripos( $post_content, '[interactive_banner ' ) && stripos( $post_content, '[interactive_banner_2' ) ) {
							$ib_found   = true;
							$ib_2_found = true;
						}

						if ( $ib_found && ! $ib_2_found ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ult-interactive-banner' );
						} elseif ( ! $ib_found && $ib_2_found ) {
							wp_enqueue_style( 'ult-ib2-style' );
						} elseif ( $ib_found && $ib_2_found ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ult-interactive-banner' );
							wp_enqueue_style( 'ult-ib2-style' );
						}
						if ( stripos( $post_content, '[info_list' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
						}
						if ( stripos( $post_content, '[ultimate_modal' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-modal' );
						}
						if ( stripos( $post_content, '[ultimate_info_table' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-pricing' );
						}
						if ( stripos( $post_content, '[ultimate_pricing' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ultimate-pricing' );
						}
						if ( stripos( $post_content, '[swatch_container' ) ) {
							wp_enqueue_style( 'swatchbook-css' );
						}
						if ( stripos( $post_content, '[stat_counter' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ult-stats-counter-style' );
						}
						if ( stripos( $post_content, '[ultimate_video_banner' ) ) {
							wp_enqueue_style( 'ultimate-video-banner-style' );
						}
						if ( stripos( $post_content, '[ult_dualbutton' ) ) {
							wp_enqueue_style( 'ult-dualbutton' );
						}
						if ( stripos( $post_content, '[ult_createlink' ) ) {
							wp_enqueue_style( 'ult_cllink' );
						}
						if ( stripos( $post_content, '[ultimate_img_separator' ) ) {
							wp_enqueue_style( 'ultimate-animate' );
							wp_enqueue_style( 'ult-easy-separator-style' );
						}
						if ( stripos( $post_content, '[ult_tab_element' ) ) {
							wp_enqueue_style( 'ult_tabs' );
							wp_enqueue_style( 'ult_tabs_acordian' );
						}
						if ( stripos( $post_content, '[ultimate_exp_section' ) ) {
							wp_enqueue_style( 'style_ultimate_expsection' );
						}
						if ( stripos( $post_content, '[ult_sticky_section' ) ) {
							wp_enqueue_style( 'ult_sticky_section_css' );
						}
						if ( stripos( $post_content, '[ult_team' ) ) {
							wp_enqueue_style( 'ultimate-team' );
						}
					}

					if ( stripos( $post_content, '[ultimate_google_map' ) ) {
						if ( ! wp_script_is( 'googleapis', 'done' ) ) {
							global $porto_settings;
							$api     = 'https://maps.googleapis.com/maps/api/js';
							$map_key = ! empty( $porto_settings['gmap_api'] ) ? $porto_settings['gmap_api'] : '';
							if ( $map_key ) {
								$arr_params = array(
									'key' => $map_key,
								);
								$api        = esc_url( add_query_arg( $arr_params, $api ) );
							}
							echo "<script src='" . $api . "'></script>";
							wp_dequeue_script( 'googleapis' );
						}
					}
				}
			}

			$shortcodes_custom_css .= get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
			if ( $shortcodes_custom_css && defined( 'WPB_VC_VERSION' ) ) {
				global $porto_settings_optimize;
				if ( isset( $porto_settings_optimize['lazyload'] ) && $porto_settings_optimize['lazyload'] && ( ! function_exists( 'vc_is_inline' ) || ! vc_is_inline() ) ) {
					preg_match_all( '/\.vc_custom_([^{]*)[^}]*((background-image):[^}]*|(background):[^}]*url\([^}]*)}/', $shortcodes_custom_css, $matches );
					if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {
						foreach ( $matches[0] as $key => $value ) {
							if ( ! isset( $matches[1][ $key ] ) || empty( $matches[1][ $key ] ) ) {
								continue;
							}
							if ( preg_match( '/\[(porto_interactive_banner|vc_row|vc_column|vc_row_inner|vc_column_inner)\s[^]]*.vc_custom_' . trim( $matches[1][ $key ] ) . '[^]]*\]/', $post_content ) ) {
								if ( ! empty( $matches[3][ $key ] ) ) {
									$shortcodes_custom_css = preg_replace( '/\.vc_custom_' . $matches[1][ $key ] . '([^}]*)(background-image:[^;]*;)/', '.vc_custom_' . $matches[1][ $key ] . '$1', $shortcodes_custom_css );
								} else {
									$shortcodes_custom_css = preg_replace( '/\.vc_custom_' . $matches[1][ $key ] . '([^}]*)(background)(:\s#[A-Fa-f0-9]{3,6}\s)(url\([^)]*\))\s(!important;)/', '.vc_custom_' . $matches[1][ $key ] . '$1background-color$3$5', $shortcodes_custom_css );
								}
							}
						}
					}
				}
			}

			//block Styles
			$css = get_post_meta( $post_id, 'porto_blocks_style_options_css', true );
			if ( $css ) {
				$shortcodes_custom_css .= wp_strip_all_tags( $css );
			}

			if ( function_exists( 'porto_the_content' ) ) {
				$post_content = porto_the_content( $post_content, false );
			} else {
				$post_content = do_shortcode( $post_content );
			}

			$output .= $before_html;
			$output .= '<div class="porto-block' . ( $el_class ? esc_attr( $el_class ) : '' ) . '"';
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
			$output .= ' data-id="' . absint( $post_id ) . '">';

			if ( 'fluid' == $inner_container ) {
				$output .= '<div class="container-fluid">';
			}
		}

		if ( defined( 'WPB_VC_VERSION' ) ) {
			$shortcodes_custom_css .= get_post_meta( $post_id, '_wpb_post_custom_css', true );
		}
		$shortcodes_custom_css .= get_post_meta( $post_id, 'custom_css', true );
		if ( $shortcodes_custom_css ) {
			$inline_style_css  = '<style>';
			$inline_style_css .= wp_strip_all_tags( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $shortcodes_custom_css ) );
			$inline_style_css .= '</style>';
			$output           .= porto_filter_inline_css( $inline_style_css, false );
		}

		if ( 'yes' == $inner_container ) {
			$output .= '<div class="container">';
		}

		$output .= apply_filters( 'porto_lazy_load_images', $post_content );

		if ( 'yes' == $inner_container || ( ! $is_elementor_edited && 'fluid' == $inner_container ) ) {
			$output .= '</div>';
		}

		if ( ! $is_elementor_edited ) {
			$output .= '</div>';
		}

		$shortcodes_custom_js = get_post_meta( $post_id, 'custom_js_body', true );
		if ( $shortcodes_custom_js ) {
			$output .= '<script>';
			$output .= trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $shortcodes_custom_js ) );
			$output .= '</script>';
		}

		echo porto_filter_output( $output );
	}
}
