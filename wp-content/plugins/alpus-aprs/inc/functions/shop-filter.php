<?php
/**
 * AI Product Review Summary - Shop Tag Filter
 * 
 * @since 1.1.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'admin/options/class-plugin-options.php';

class Alpus_APRS_Shop_Filter {
	/**
	 * The Constructor
     *
	 * @since 2.1.0
	 * @access public
	*/
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 99 );

        add_action( 'woocommerce_before_main_content', array( $this, 'render_tag_filters' ), 99 );

        add_filter( 'woocommerce_product_query_meta_query', array( $this, 'filter_meta_query' ), 10, 2 );
	}

    /** 
     * Handle Custom Query Var
     * 
     * @since 2.1.0
     * @access public
     */
    public function filter_meta_query( $meta_query, $query_vars ) {
        // Only on shop pages
        if( ! is_shop() ) return $meta_query;

        $params   = array();

        if ( ! empty( $_REQUEST['alpus_aprs'] ) ) {
            $params_string = urldecode( $_REQUEST['alpus_aprs'] );

            $params = explode( ',', $params_string );
        }

        if ( ! empty( $params ) && is_array( $params ) ) {
            $meta_query['relation'] = 'AND';
            foreach( $params as $param ) {
                $meta_query[] = array(
                    'key'     => 'alpus_aprs_sp_tag',
                    'value'   => $param,
                    'compare' => '='
                );
            }
            
        }

        return $meta_query;
    }

    /** 
     * Render Tag Filters Shop Loop
     * 
     * @since 2.1.0
     * @access public
     */
    public function render_tag_filters() {
        if ( ! is_shop() ) {
            return;
        }

        $all_tags = Alpus_Plugin_Options::get_option( 'alpus_aprs_tags' );
        $params   = array();

        if ( ! empty( $all_tags ) ) {
            $all_tags = explode( ', ', $all_tags );
        } else {
            return;
        }

        if ( ! empty( $_REQUEST['alpus_aprs'] ) ) {
            $params_string = urldecode( $_REQUEST['alpus_aprs'] );

            $params = explode( ',', $params_string );
        }

        ob_start();
        ?>
        <div class="alpus-aprs-shop-filters-wrapper">
            <ul class="alpus-aprs-shop-filters">
                <?php foreach( $all_tags as $tag ) { ?>
                    <li class="alpus-aprs-shop-filter<?php echo esc_attr( in_array( sanitize_title( $tag ), $params ) ? ' active' : '' ); ?>"><a href="#<?php echo esc_attr( sanitize_title( $tag ) ); ?>"><?php echo esc_html( $tag ); ?></a></li>
                <?php } ?>
            </ul>

            <a href="#" class="alpus-aprs-shop-filters-clear"><?php esc_html_e( 'Clear All', 'alpus-aprs' ); ?></a>
        </div>
        <?php

        $html = ob_get_clean();

        echo $html;
    }

    /**
     * Enqueue JS & Styles for Backend.
     * 
     * @since 2.1.0
     * @access public
     */
    public function enqueue() {
        
    }
}

new Alpus_APRS_Shop_Filter;