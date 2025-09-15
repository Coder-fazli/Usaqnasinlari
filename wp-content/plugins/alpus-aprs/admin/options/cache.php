<?php
/**
 * Cache Options
 *
 * @author AlpusTheme
 * @package Alpus APRS (AI Product Review Summary)
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || die;

$cache = array(
	'alpus_aprs_generate_start' => array(
		'type'  => 'section_start',
		'title' => esc_html__( 'Generate all summaries.', 'alpus-aprs' ),
	),
	'alpus_aprs_generate_summary_action' => array(
		'type'  => 'action',
		'title' => esc_html__( 'Generate all summaries', 'alpus-aprs' ),
		'label' => esc_html__( 'Start', 'alpus-aprs' ),
		'class' => 'alpus-aprs-generate-all',
	),
	'alpus_aprs_generate_end' => array(
		'type'  => 'section_end',
	),
	'alpus_aprs_clear_reviews_start' => array(
		'type'  => 'section_start',
		'title' => esc_html__( 'Cache', 'alpus-aprs' ),
	),
	'alpus_aprs_clear_reviews'   => array(
		'type'  => 'action',
		'title' => esc_html__( 'Reviews Summary Cache', 'alpus-aprs' ),
		'label' => esc_html__( 'Clear All', 'alpus-aprs' ),
		'class' => 'alpus-aprs-clear-all',
	),
	'alpus_aprs_clear_timeout' => array(
		'type'       => 'select',
		'title'      => esc_html__( 'Expiration Time', 'alpus-aprs' ),
		'desc'       => esc_html__( 'Select expiration time for AI product review summary cache.', 'alpus-aprs' ),
		'options'    => array(
			DAY_IN_SECONDS   => esc_html__( 'Day', 'alpus-aprs' ),
			WEEK_IN_SECONDS  => esc_html__( 'Week', 'alpus-aprs' ),
			MONTH_IN_SECONDS => esc_html__( 'Month', 'alpus-aprs' ),
			YEAR_IN_SECONDS  => esc_html__( 'Year', 'alpus-aprs' ),
			0                => esc_html__( 'Unlimited', 'alpus-aprs' ),
		),
	),
	'alpus_aprs_clear_reviews_end' => array(
		'type'  => 'section_end',
	),
);

return apply_filters( 'alpus_aprs_cache_options', $cache );

