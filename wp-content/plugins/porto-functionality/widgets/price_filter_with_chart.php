<?php

use Automattic\Jetpack\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Update WC Price Chart widget to Porto's
add_action( 'widgets_init', 'porto_load_price_filter_with_chart_widget', 99 );
function porto_load_price_filter_with_chart_widget() {
	unregister_widget( 'WC_Widget_Price_Filter' );
    register_widget( 'Porto_WC_Widget_Price_Filter_Chart' );
}

/**
 * Update WooCommerce Price Filter with Chart
 * 
 * @since 7.2.0
 */
class Porto_WC_Widget_Price_Filter_Chart extends WC_Widget_Price_Filter {
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
    	$this->settings     = array(
			'title'      => array(
				'type'  => 'text',
				'std'   => __( 'Filter by price', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' ),
			),
			'show_chart' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Price Chart', 'porto-functionality' ),
			),
			'step_count' => array(
				'type'  => 'text',
				'std'   => 50,
				'label' => __( 'Chart Steps Count ( Min: 20, Max: 100 )', 'porto-functionality' ),
			),
		);
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		global $wp;

		// Requires lookup table added in 3.6.
		if ( version_compare( get_option( 'woocommerce_db_version', null ), '3.6', '<' ) ) {
			return;
		}

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		// If there are not posts and we're not filtering, hide the widget.
		if ( ! WC()->query->get_main_query()->post_count && ! isset( $_GET['min_price'] ) && ! isset( $_GET['max_price'] ) ) { // WPCS: input var ok, CSRF ok.
			return;
		}

		wp_enqueue_script( 'wc-price-slider' );

		// Round values to nearest 10 by default.
		$step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );

		// Find min and max price in current result set.
		$prices    = $this->get_filtered_price();
		$min_price = $prices->min_price;
		$max_price = $prices->max_price;

		// Check to see if we should add taxes to the prices if store are excl tax but display incl.
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

		if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
			$tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
			$tax_rates = WC_Tax::get_rates( $tax_class );

			if ( $tax_rates ) {
				$min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
				$max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
			}
		}

		$min_price = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $min_price / $step ) * $step );
		$max_price = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $max_price / $step ) * $step );

		// If both min and max are equal, we don't need a slider.
		if ( $min_price === $max_price ) {
			return;
		}

		$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
		$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

		$this->widget_start( $args, $instance );

		if ( '' === get_option( 'permalink_structure' ) ) {
			$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
		}

		$show_chart = isset( $instance['show_chart'] ) ? $instance['show_chart'] : $this->settings['show_chart']['std'];
		$prices     = array();
		if ( 1 == $show_chart ) {
			$step_count = isset( $instance['step_count'] ) ? $instance['step_count'] : $this->settings['step_count']['std'];

			wp_enqueue_script( 'porto-price-filter-chart', PORTO_FUNC_URL . 'widgets/apexcharts/apexcharts.js', array(), PORTO_VERSION, true );
			$prices = $this->get_filter_all_price( $min_price, $max_price, $step_count, $step );
		}
		wc_get_template(
			'content-widget-price-filter.php',
			array(
				'form_action'       => $form_action,
				'step'              => $step,
				'min_price'         => $min_price,
				'max_price'         => $max_price,
				'current_min_price' => $current_min_price,
				'current_max_price' => $current_max_price,
				'show_chart'        => $show_chart,
				'prices'            => $prices,
			)
		);

		$this->widget_end( $args );
	}

	/**
	 * Generate transient name
	 * 
	 * @since 7.2.4
	 */
	protected function get_transient_name( $sql ) {
		return 'porto_price_chart_' . md5( $sql );
	}

	/**
	 * Get All Prices
	 *
	 * @return array
	 */
	protected function get_filter_all_price( $min_price, $max_price, $step_count, $default_step ) {
		global $wpdb;

		if ( wc()->query->get_main_query() ) {
			$args = wc()->query->get_main_query()->query_vars;
		} else {
			$args = array();
		}
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = '';

		if ( wc()->query->get_main_query() && $search = WC_Query::get_main_search_query_sql() ) { // @codingStandardsIgnoreLine
			$search_query_sql = ' AND ' . $search;
		}

		$was_disabled = 'yes' !== get_option( 'woocommerce_attribute_lookup_enabled' );

		$sql = "
			SELECT product.min_price AS price, post.post_parent AS parent
			FROM {$wpdb->wc_product_meta_lookup} product
			INNER JOIN {$wpdb->posts} post ON product.product_id = post.ID
			WHERE product.min_price = product.max_price AND 
				( post.post_parent = 0 OR post.post_parent IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d ) ) AND " . ( $was_disabled ? '(' : '' ) . "
				product.product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product', 'product_variation' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . "
			)";

		if ( $was_disabled ) {
			$sql .= " OR post.post_parent IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product', 'product_variation' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . "
				) )";
		}

		$transient_name    = $this->get_transient_name( $sql . Porto_WPML::get_instance()->get_active_language() );
		$transient_version = Porto_Cache::get_transient_version( 'query_product' );
		$chart_transient   = get_transient( $transient_name );

		$meta_values = array();
		if ( isset( $chart_transient['value'], $chart_transient['version'] ) && $chart_transient['version'] === $transient_version ) {
			$meta_values = $chart_transient['value'];
		} else {
			$variable_term = get_term_by( 'slug', 'variable', 'product_type' );
			if ( ! empty( $variable_term ) && ! is_wp_error( $variable_term ) && $variable_term->term_id ) {
				$meta_values = $wpdb->get_results( $wpdb->prepare( $sql, (int) $variable_term->term_id ), 'ARRAY_A' );
				$transient_value = array(
					'version' => $transient_version,
					'value'   => $meta_values,
				);
				set_transient( $transient_name, $transient_value, 4 * DAY_IN_SECONDS );
			}
		}

		$length      = $max_price - $min_price;
		$prices      = array();
		$parent_ids  = array();

		// Check Step Count Value
		if ( is_numeric( $step_count ) ) {
			if ( $step_count < 20 ) {
				$step_count = 20;
			} else if ( $step_count > 100 ) {
				$step_count = 100;
		}
		} else {
			$step_count = $this->settings['step_count']['std'];	// Default Value
		}

		if ( $length >= ( $step_count * $default_step ) ) {
			$prices    = array_fill( 1, $step_count + 1, 0 ); // Create an array that its length is $steps, each items will be 0 as a initial value.
			$parent_id = array_fill( 1, $step_count + 1, array() );
			$step      = $length / $step_count;	
		} else {
			$count     = floor( ( $max_price - $min_price ) / $default_step );
			$prices    = array_fill( 1, $count + 1, 0 );
			$parent_id = array_fill( 1, $count + 1, array() );
			$step      = $default_step;
		}

		foreach ( $meta_values as $meta_value ) {
			$key = floor( ( $meta_value[ 'price' ] - $min_price ) / $step ) + 1;

			if ( isset( $parent_id[ $key ] ) && empty( $parent_id[ $key ][ $meta_value[ 'parent' ] ] ) ) {
				if ( '0' !== $meta_value[ 'parent' ] ) {
					$parent_id[ $key ][ $meta_value[ 'parent' ] ] = 1;
				}

				if ( isset( $prices[ $key ] ) ) {
					$prices[ $key ] += 1;
				}
			}
		}

		return $prices;
	}
}
