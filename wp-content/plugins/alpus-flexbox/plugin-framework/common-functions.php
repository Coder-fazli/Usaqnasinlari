<?php
/**
 * Common Functions
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Get template for Alpus plguins.
 *
 * @param string  $slug             Template path
 * @param string  $plugin_path      Plugin base path
 * @param array   $atts             Attributes for template
 * @param boolean $echo             Echo the template
 *
 * @return string Template HTML
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_plugin_get_template' ) ) {
	function alpus_plugin_get_template( $slug, $plugin_path, $atts, $echo = true ) {
		// Plugin Path
		$plugin_path = $plugin_path . 'templates/' . $slug . '.php';

		// Theme template path
		$template_path = get_template_directory() . '/alpus/' . $slug . '.php';
		$child_path    = get_stylesheet_directory() . '/alpus/' . $slug . '.php';

		foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $path ) {
			if ( file_exists( ${$path} ) ) {
				if ( ! $echo ) {
					ob_start();
				}
				include ${$path};
				return $echo ? '' : ob_get_clean();
			}
		}
	}
}

if ( ! function_exists( 'alpus_plugin_path' ) ) {
	function alpus_plugin_path( $path, $plugin_name ) {
		$core_path = strstr( $path, $plugin_name );
		if ( defined( 'ALPHA_CORE_VERSION' ) && file_exists( ALPHA_CORE_PATH . '/inc/addons/' . $core_path ) ) {
			return ALPHA_CORE_PATH . '/inc/addons/' . $core_path;
		}
		return $path;
	}
}

if ( ! function_exists( 'alpus_plugin_uri' ) ) {
	function alpus_plugin_uri( $uri, $plugin_name ) {
		$core_uri = strstr( $uri, $plugin_name );
		if ( defined( 'ALPHA_CORE_VERSION' ) && file_exists( ALPHA_CORE_PATH . '/inc/addons/' . $core_uri ) ) {
			return ALPHA_CORE_URI . '/inc/addons/' . $core_uri;
		}

		return $uri;
	}
}

if ( ! function_exists( 'alpus_strip_script_tags' ) ) :

	/**
	 * Strip script and style tags from content.
	 *
	 * @since 1.0
	 *
	 * @param string $content Content to strip script and style tags.
	 *
	 * @return string stripped text
	 */
	function alpus_strip_script_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '/<script.*?\/script>/s', '', $content ) ? : $content;
		$content = preg_replace( '/<style.*?\/style>/s', '', $content ) ? : $content;
		return $content;
	}
endif;

if ( ! function_exists( 'alpus_escaped' ) ) {
	/**
	 * Get already escaped text.
	 *
	 * @since 1.0
	 *
	 * @param string $html_escaped Escaped text
	 *
	 * @return string Original escaped text
	 */
	function alpus_escaped( $html_escaped ) {
		return $html_escaped;
	}
}

if ( ! function_exists( 'alpus_validate_unit' ) ) {
	/**
	 * Validate size with units
	 *
	 * @since 1.0
	 *
	 */
	function alpus_validate_unit( $value ) {
		if ( $value ) {
			$unit = trim( preg_replace( '/[0-9.]/', '', $value ) );
			if ( ! $unit ) {
				$value .= 'px';
			}
		}
		return $value;
	}
}

if ( ! function_exists( 'alpus_current_page_id' ) ) {
	/**
	 * Get the current page id.
	 *
	 * @since 1.0.0
	 */
	function alpus_current_page_id() {
		global $wp_query;
		if ( is_404() ) { // 404 page
			return '404-page';
		}
		if ( is_search() ) { // search page
			if ( ! empty( $_REQUEST['post_type'] ) ) {
				return 'search-page-' . $_REQUEST['post_type'];
			}
			return 'search-page';
		}
		if ( is_home() && get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
			return get_option( 'page_for_posts' );
		}
		if ( ! $wp_query ) {
			return false;
		}
		$page_id = get_queried_object_id();

		// Shop page.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && is_shop() ) {
			return (int) get_option( 'woocommerce_shop_page_id' );
		}
		// Product Category and Tag Page.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && ( ! is_shop() && ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) ) {
			return $page_id . '-archive';
		}
		// Homepage.
		if ( 'posts' === get_option( 'show_on_front' ) && is_home() ) {
			return $page_id;
		}
		if ( ! is_singular() && is_archive() ) {
			if ( empty( $page_id ) ) {
				$page_id = get_post_type();
				if ( is_tax() ) {
					$queried = get_queried_object();
					if ( isset( $queried ) && ! empty( $queried->slug ) && ! empty( $queried->taxonomy ) ) {
						$page_id .= '-' . $queried->slug . '-' . $queried->taxonomy;
					}
				}
			}
			return $page_id . '-archive';
		}
		if ( ! is_singular() ) {
			return false;
		}
		return $page_id;
	}
}

if ( ! function_exists( 'alpus_is_elementor_preview' ) ) {
	/**
	 * Is the elementor preview?
	 *
	 * @since 1.0.0
	 */
	function alpus_is_elementor_preview() {
		return defined( 'ELEMENTOR_VERSION' ) && (
				( isset( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) ||
				isset( $_REQUEST['elementor-preview'] )
			);
	}
}


if ( ! function_exists( 'alpus_minify_css' ) ) {
	/**
	 * Minify css
	 *
	 * @since 1.0
	 */
	function alpus_minify_css( $style ) {
		if ( ! $style ) {
			return;
		}

		// Change ::before, ::after to :before, :after
		$style = str_replace( array( '::before', '::after' ), array( ':before', ':after' ), $style );
		$style = preg_replace( '/\s+/', ' ', $style );
		$style = preg_replace( '/;(?=\s*})/', '', $style );
		$style = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $style );
		$style = preg_replace( '/ (,|;|\{|})/', '$1', $style );
		$style = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $style );
		$style = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $style );
		$style = preg_replace( '/\/\*[^\/]*\*\//', '', $style );
		$style = str_replace( array( '}100%{', ',100%{', ' !important', ' >' ), array( '}to{', ',to{', '!important', '>' ), $style );

		// Trim
		$style = trim( $style );
		return $style;
	}
}
