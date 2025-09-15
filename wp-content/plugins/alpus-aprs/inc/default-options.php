<?php
/**
 * Default Options for Alpus APRS
 * 
 * @since 1.0.0
 * @since 2.1.2 - Update ChatGPT Default Text Model to gpt-3.5-turbo-1106
 */
return apply_filters(
	'alpus_aprs_default_options',
	array(
		'alpus_aprs_api_key'               => '',
        'alpus_aprs_summary_title'         => esc_html__( 'Reviews Summary by AI:', 'alpus-aprs'),
		'alpus_aprs_layout_type'           => 'default',
		'alpus_aprs_skin_title_color'      => '#222',
		'alpus_aprs_skin_title_size'	   => 16,
		'alpus_aprs_skin_summary_color'    => '#666',
		'alpus_aprs_skin_summary_size'     => 13,
		'alpus_aprs_skin_background_color' => '#f2f3f5',
		'alpus_aprs_clear_timeout'         => 0,
		'alpus_aprs_tags'                  => 'High Quality, Easy to Use, High Price, Low Price, Comfortable, Fashion Design',
		'alpus_aprs_tags_enable'           => 'yes',
		'alpus_aprs_tags_enable_sp'        => 'yes',
		'alpus_aprs_tags_enable_filter'    => 'yes',
		'alpus_aprs_show_tooltip'		   => 'yes',
	)
);
