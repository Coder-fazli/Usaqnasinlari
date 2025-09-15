<?php
/**
 * Tags page
 *
 * @author AlpusTheme
 * @package Alpus APRS (AI Product Review Summary)
 * @version 2.0.1
 */
defined( 'ABSPATH' ) || die;

$tags = array(
	'alpus_aprs_tags_start' => array(
		'type'  => 'section_start',
		'title' => esc_html__( 'AI Generated Tags', 'alpus-aprs' ),
	),
    'alpus_aprs_tags_enable' => array(
        'type'  => 'button_set',
        'title' => esc_html__( 'Enable Tags', 'alpus-aprs' ),
		'desc'  => esc_html__( 'Enable generating product summary tags by AI.', 'alpus-aprs' ),
		'options' => array(
			'yes' => esc_html__( 'Yes', 'alpus-aprs' ),
			'no'  => esc_html__( 'No', 'alpus-aprs' ),
		),
    ),
	'alpus_aprs_tags' => array(
		'type'  => 'textarea',
		'title' => esc_html__( 'Product Summary Tags', 'alpus-aprs' ),
		'desc'  => esc_html__( 'Please input Product summary tags. For example: Easy to Use, Comfortable, High Price, Low Price, High Quality', 'alpus-aprs' ),
	),
    'alpus_aprs_tags_enable_sp' => array(
        'type'  => 'button_set',
        'title' => esc_html__( 'Show Product Tags', 'alpus-aprs' ),
		'desc'  => esc_html__( 'Please choose wheather show product tags on single product page or not.', 'alpus-aprs' ),
		'options' => array(
			'yes' => esc_html__( 'Yes', 'alpus-aprs' ),
			'no'  => esc_html__( 'No', 'alpus-aprs' ),
		),
    ),
    'alpus_aprs_tags_enable_filter' => array(
        'type'  => 'button_set',
        'title' => esc_html__( 'Enable Filter', 'alpus-aprs' ),
		'desc'  => esc_html__( 'Please enable product tag filter on shop page.', 'alpus-aprs' ),
		'options' => array(
			'yes' => esc_html__( 'Yes', 'alpus-aprs' ),
			'no'  => esc_html__( 'No', 'alpus-aprs' ),
		),
    ),
	'alpus_aprs_tags_end' => array(
		'type'  => 'section_end',
	),
);

return apply_filters( 'alpus_aprs_tags_options', $tags );
