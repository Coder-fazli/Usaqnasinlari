<?php
/**
 * Skin & Layout page
 *
 * @author AlpusTheme
 * @package Alpus APRS (AI Product Review Summary)
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || die;

$skin = array(
	'alpus_aprs_layout_start' => array(
		'type'  => 'section_start',
		'title' => esc_html__( 'Layout', 'alpus-aprs' ),
	),
	'alpus_aprs_layout_type'  => array(
		'type'    => 'button_set',
		'title'   => esc_html__( 'Layout', 'alpus-aprs' ),
		'desc'    => esc_html__( 'Choose product review summary layout types.', 'alpus-aprs' ),
		'options' => array(
			'default' => esc_html__( 'Default', 'alpus-aprs' ),
			'wide'    => esc_html__( 'Wide', 'alpus-aprs' ),
			'custom'  => esc_html__( 'Custom', 'alpus-aprs' ),
		),
	),
	'alpus_aprs_layout_custom_shortcode'  => array(
		'type'    => 'html',
		'title'   => esc_html__( 'Shortcode', 'alpus-aprs' ),
		'desc'    => esc_html__( 'Please insert the shortcode in single product template as you want.', 'alpus-aprs' ),
		'html'    => '<div class="alpus-aprs-shortcode">[alpus_aprs_ai_summary]</div>',
		'dependency' => array(
			'option'   => 'alpus_aprs_layout_type',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
	'alpus_aprs_layout_end'        => array(
		'type' => 'section_end',
	),
	'alpus_aprs_skin_start' => array(
		'type'  => 'section_start',
		'title' => esc_html__( 'Skin', 'alpus-aprs' ),
	),

	'alpus_aprs_skin_title_size' => array(
		'type'     => 'number',
		'title'    => esc_html__( 'Title Font Size(px)', 'alpus-aprs' ),
		'selectors' => array(
			'.alpus-aprs-wrapper .alpus-aprs-title' => 'font-size: {{VALUE}}px !important;',
		),
	),
	
	'alpus_aprs_skin_title_color' => array(
		'type'     => 'color',
		'title'    => esc_html__( 'Title Color', 'alpus-aprs' ),
		'selectors' => array(
			'.alpus-aprs-wrapper .alpus-aprs-title' => 'color: {{VALUE}} !important;',
		),
	),

	'alpus_aprs_skin_summary_size' => array(
		'type'     => 'number',
		'title'    => esc_html__( 'Content Font Size(px)', 'alpus-aprs' ),
		'selectors' => array(
			'.alpus-aprs-wrapper' => 'font-size: {{VALUE}}px !important;',
		),
	),
	
	'alpus_aprs_skin_summary_color' => array(
		'type'     => 'color',
		'title'    => esc_html__( 'Content Color', 'alpus-aprs' ),
		'selectors' => array(
			'.alpus-aprs-wrapper .alpus-aprs-content' => 'color: {{VALUE}} !important;',
		),
	),
	
	'alpus_aprs_skin_background_color' => array(
		'type'     => 'color',
		'title'    => esc_html__( 'Background Color', 'alpus-aprs' ),
		'selectors' => array(
			'.alpus-aprs-wrapper' => 'background-color: {{VALUE}} !important;',
		),
	),

	'alpus_aprs_skin_end' => array(
		'type'  => 'section_end',
	),
);

return apply_filters( 'alpus_aprs_skin_options', $skin );
