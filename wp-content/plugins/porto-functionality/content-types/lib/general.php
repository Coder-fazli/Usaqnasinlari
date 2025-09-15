<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'porto_supported_post_types' ) ) :

	function porto_supported_post_types() {
		return array( 'post', 'product', 'portfolio', 'member', 'faq' );
	}
endif;


if ( ! function_exists( 'porto_multi_lang_post_id' ) ) :

	/**
	 * Update post id for Multiple Language plugins
	 * 
	 * @since 3.1.14
	 */
	function porto_multi_lang_post_id( $post_id, $post_type = 'page' ) {
		// Polylang
		if ( function_exists( 'pll_get_post' ) && pll_get_post( $post_id ) ) {
			$lang_id = pll_get_post( $post_id );
			if ( $lang_id ) {
				$post_id = $lang_id;
			}
		}

		// WPML
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( function_exists( 'icl_object_id' ) ) {
				$lang_id = icl_object_id( $post_id, $post_type, false, ICL_LANGUAGE_CODE );
			} else {
				$lang_id = apply_filters( 'wpml_object_id', $post_id, $post_type, false, ICL_LANGUAGE_CODE );
			}
			if ( $lang_id ) {
				$post_id = $lang_id;
			}
		}

		return apply_filters( 'porto_multi_lang_post_id', $post_id, $post_type );
	}
endif;
