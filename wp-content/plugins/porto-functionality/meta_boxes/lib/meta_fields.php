<?php

/**
 * @see   porto/inc/soft-mode/setup.php
 * @since 2.3.0 Removed meta fields because of soft mode. Instead add meta fields options in theme about legacy mode.
 */
function porto_ct_default_view_meta_fields( $post_type = 'page', $is_taxonomy = '' ) {

	$header_view    = porto_ct_header_view();
	$theme_layouts   = array(
		'widewidth'          => array(
			'title' => esc_html__( 'Wide Width', 'porto-functionality' ),
			'img'  => PORTO_OPTIONS_URI . '/layouts/page_wide.svg',
		),
		'wide-left-sidebar'  => array(
			'title' => esc_html__( 'Wide Left Sidebar', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_wide_left.svg',
		),
		'wide-right-sidebar' => array(
			'title' => esc_html__( 'Wide Right Sidebar', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_wide_right.svg',
		),
		'wide-both-sidebar'  => array(
			'title' => esc_html__( 'Wide Left & Right Sidebars', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_wide_both.svg',
		),
		'fullwidth'          => array(
			'title' => esc_html__( 'Without Sidebar', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_full.svg',
		),
		'left-sidebar'       => array(
			'title' => esc_html__( 'Left Sidebar', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_full_left.svg',
		),
		'right-sidebar'      => array(
			'title' => esc_html__( 'Right Sidebar', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_full_right.svg',
		),
		'both-sidebar'       => array(
			'title' => esc_html__( 'Left & Right Sidebars', 'porto-functionality' ),
			'img'   => PORTO_OPTIONS_URI . '/layouts/page_full_both.svg',
		),
	);
	$sidebar_options = porto_ct_sidebars();

	$theme_option_layout = 'layout';
	if ( 'page' != $post_type ) {
		if ( '' == $is_taxonomy ) {
			$theme_option_layout = $post_type . '-single-layout';
		} else {
			$theme_option_layout = $post_type . '-archive-layout';
		}
	}

	$fields = array(
		// Page Title
		'page_title'     => array(
			'name'  => 'page_title',
			'title' => __( 'Page Title', 'porto-functionality' ),
			'desc'  => sprintf( __( 'Do not show. You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'show-pagetitle' ) . '" target="_blank">', '</a>' ),
			'type'  => 'checkbox',
		),
		// Page Sub Title
		'page_sub_title' => array(
			'name'     => 'page_sub_title',
			'title'    => __( 'Page Sub Title', 'porto-functionality' ),
			'type'     => 'text',
			'required' => array(
				'name'  => 'page_title',
				'value' => '',
			),
		),
		// Layout, Sidebar
		'default'        => array(
			'name'  => 'default',
			'title' => __( 'Page layout & Sidebar', 'porto-functionality' ),
			'desc'  => sprintf( __( 'Change the page layout.  You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( $theme_option_layout ) . '" target="_blank">', '</a>' ),
			'type'  => 'checkbox',
		),
		// Layout
		'layout'         => array(
			'name'     => 'layout',
			'title'    => __( 'Layout', 'porto-functionality' ),
			'type'     => 'imageselect',
			'default'  => 'right-sidebar',
			'required' => array(
				'name'  => 'default',
				'value' => 'default',
			),
			'options'  => $theme_layouts,
		),
		// Sidebar
		'sidebar'        => array(
			'name'     => 'sidebar',
			'title'    => __( 'Sidebar', 'porto-functionality' ),
			'desc'     => __( '<strong>Note</strong>: You can create the sidebar under <strong>Appearance > Sidebars</strong>', 'porto-functionality' ),
			'type'     => 'select',
			'default'  => '',
			'required' => array(
				'name'  => 'default',
				'value' => 'default',
			),
			'options'  => $sidebar_options,
		),
		// Sidebar
		'sidebar2'       => array(
			'name'     => 'sidebar2',
			'title'    => __( 'Sidebar 2', 'porto-functionality' ),
			'desc'     => __( '<strong>Note</strong>: You can create the sidebar under <strong>Appearance > Sidebars</strong>', 'porto-functionality' ),
			'type'     => 'select',
			'default'  => '',
			'required' => array(
				'name'  => 'layout',
				'value' => 'wide-both-sidebar,both-sidebar',
			),
			'options'  => $sidebar_options,
		),
		// Sticky Sidebar
		'sticky_sidebar' => array(
			'name'    => 'sticky_sidebar',
			'title'   => __( 'Sticky Sidebar', 'porto-functionality' ),
			'type'    => 'radio',
			'default' => '',
			'options' => porto_ct_enable_options(),
			'desc'    => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'sticky-sidebar' ) . '" target="_blank">', '</a>' ),
		),
		// Mobile Sidebar
		'mobile_sidebar' => array(
			'name'    => 'mobile_sidebar',
			'title'   => 'Show Mobile Sidebar',
			'desc'    => sprintf( __( 'Show Sidebar in Navigation on mobile. You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'show-mobile-sidebar' ) . '" target="_blank">', '</a>' ),
			'type'    => 'radio',
			'default' => '',
			'options' => porto_ct_enable_options(),
		),
	);

	return apply_filters( 'porto_view_meta_fields', $fields );
}

/**
 * @see   porto/inc/soft-mode/setup.php
 * @since 2.3.0 Removed meta fields because of soft mode. Instead add meta fields options in theme about legacy mode.
 */
function porto_ct_default_skin_meta_fields( $tax_meta_fields = false ) {

	$fields = array(
		'custom_css' => array(
			'name'  => 'custom_css',
			'title' => __( 'Custom CSS', 'porto-functionality' ),
			'type'  => 'textarea',
			'desc'  => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'css-code' ) . '" target="_blank">', '</a>' ),
		),
	);

	if ( current_user_can( 'manage_options' ) ) {
		// JS Code before </head>
		$fields['custom_js_head'] = array(
			'name'  => 'custom_js_head',
			'title' => __( 'JS Code before &lt;/head&gt;', 'porto-functionality' ),
			'type'  => 'textarea',
			'desc'  => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'js-code-head' ) . '" target="_blank">', '</a>' ),
		);
		// JS Code before </body>
		$fields['custom_js_body'] = array(
			'name'  => 'custom_js_body',
			'title' => __( 'JS Code before &lt;/body&gt;', 'porto-functionality' ),
			'type'  => 'textarea',
			'desc'  => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'js-code' ) . '" target="_blank">', '</a>' ),
		);
	}

	return apply_filters( 'porto_skin_meta_fields', $fields, $tax_meta_fields );
}
