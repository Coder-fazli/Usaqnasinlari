<?php
/**
 * AI Product Review Summary - Archive Product
 * 
 * @since 2.1.1
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'admin/options/class-plugin-options.php';

class Alpus_APRS_Archive_Product {
	/**
	 * The Constructor
     *
	 * @since 2.1.1
	 * @access public
	*/
	public function __construct() {  
        add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'display_summary_before' ), 4 );
        add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'display_summary_after' ), 6 );
	}

    public function display_summary_before() {
        ob_start();
        ?>
        <div class="alpus-aprs-review-wrapper">
        <?php
        $html = ob_get_clean();

        echo apply_filters( 'aprs_archive_product_label_html', $html );
    }

    public function display_summary_after() {
        ob_start();
        
        $id = get_the_ID();
        ?>
            <div class="alpus-aprs-summary-result">
                <?php
                    $summary = apply_filters( 'aprs_archive_product_summary_html', '', $id );

                    if ( empty( $summary ) ) {
                        esc_html_e( 'There is no AI review summary.', 'alpus-aprs' );
                    } else {
                        echo wp_kses_post( $summary );
                    }
                ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        echo apply_filters( 'aprs_archive_product_label_html', $html );
    }

    /**
     * Enqueue JS & Styles for Backend.
     * 
     * @since 2.1.1
     * @access public
     */
    public function enqueue() {
        
    }
}

new Alpus_APRS_Archive_Product;