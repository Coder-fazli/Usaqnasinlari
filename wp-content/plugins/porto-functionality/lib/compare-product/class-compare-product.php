<?php

/**
 * Porto Compare Products
 * 
 * @author   Porto Themes
 * @category Porto Compare Products 
 * @since    3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Porto_Compare_Product' ) ) :
	class Porto_Compare_Product {

        public function __construct() {
            add_action( 'woocommerce_product_options_related', array( $this, 'input_compare_product' ) );
            add_action( 'admin_init', array( $this, 'save_compare_ids' ) );
		}

        // Insert compare product field
        public function input_compare_product() {
            global $post;
            ?>
                <div class="options_group">
                    <p class="form-field hide_if_grouped hide_if_external">
                        <label for="compare_ids"><?php esc_html_e( 'Compare Products', 'porto-functionality' ); ?></label>
                        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="compare_ids" name="compare_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
                            <?php $product_ids = get_post_meta( intval( $post->ID ), '_porto_compare_ids', true );
                            if ( is_array( $product_ids ) ){
                                foreach ( $product_ids as $product_id ) {
                                    $product = wc_get_product( $product_id );
                                    if ( is_object( $product ) ) {
                                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                                    }
                                }
                            } ?>
                        </select>
                        <span class="description" style="display: block; width: 50%; margin: 0;"><?php 
                        echo sprintf( esc_html__( '%1$sCompare Similar Products%2$s are products which are similar to the currently viewed product. You can show these products by using %1$sLinked Products%2$s widget of %3$sSingle Product%4$s builder.', 'porto-functionality' ), '<b><i>', '</i></b>', '<a href="' . esc_url( admin_url( 'edit.php?post_type=porto_builder&porto_builder_type=product' ) ) . '" target="_blank">', '</a>' );
                        ?></span>

                    </p>
                </div>
            <?php
        }

        // Save selected products on product edit page
        public function save_compare_ids() {
            if ( isset( $_POST['action'] ) && isset( $_POST['post_ID'] ) && 'editpost' == $_POST['action'] && isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] && ! empty( $_POST['compare_ids'] ) ) {
                update_post_meta( $_POST['post_ID'], '_porto_compare_ids', $_POST['compare_ids'] );
            }
        }
    }

	new Porto_Compare_Product();
endif;