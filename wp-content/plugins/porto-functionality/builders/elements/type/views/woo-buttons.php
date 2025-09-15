<?php

$attrs = '';
global $product;
if ( ! empty( $product ) && isset( $atts['link_source'] ) ) {
	$common_cls = 'porto-tb-woo-link';

	global $porto_settings;
	$icon_html        = '';
	$simple_cart_html = '';
	if ( 'cart' == $atts['link_source'] && ! empty( $atts['icon_cls_variable'] ) && ( $product->is_type( 'variable' ) || ! ( $product->is_purchasable() && $product->is_in_stock() ) ) ) {
		$icon_html .= '<i class="' . esc_attr( $atts['icon_cls_variable'] ) . '"></i>';
		if ( empty( $atts['hide_title'] ) ) {
			$common_cls .= ' porto-tb-icon-' . ( ! empty( $atts['icon_pos'] ) ? $atts['icon_pos'] : 'left' );
		}
	} elseif ( ! empty( $atts['icon_cls'] ) ) {
		$icon_html .= '<i class="' . esc_attr( $atts['icon_cls'] ) . '"></i>';
		if ( empty( $atts['hide_title'] ) ) {
			$common_cls .= ' porto-tb-icon-' . ( ! empty( $atts['icon_pos'] ) ? $atts['icon_pos'] : 'left' );
		}
	}
	if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
		$common_cls .= ' ' . esc_attr( $atts['el_class'] );
	}
	if ( ! empty( $atts['className'] ) ) {
		$common_cls .= ' ' . esc_attr( trim( $atts['className'] ) );
	}

	if ( 'cart' == $atts['link_source'] ) {
		global $porto_tb_catalog_mode;

		if ( $porto_tb_catalog_mode && empty( $porto_settings['catalog-readmore'] ) ) {
			return;
		}

		if ( $product->is_type( 'variable' ) ) {
			$simple_cart_icon = '';
			if ( ! empty( $atts['icon_cls'] ) ) {
				$simple_cart_icon = '<i class="' . esc_attr( $atts['icon_cls'] ) . '"></i>';
			}
			$simple_cart_html  = 'data-cart-html="';
			$simple_cart_html .= esc_attr( $porto_tb_catalog_mode ? esc_html( $porto_settings['catalog-readmore-label'] ) : ( ! isset( $atts['icon_pos'] ) || 'right' != $atts['icon_pos'] ? $simple_cart_icon : '' ) . ( empty( $atts['hide_title'] ) ? __( 'Add to cart', 'woocommerce' ) : '' ) . ( isset( $atts['icon_pos'] ) && 'right' == $atts['icon_pos'] ? $simple_cart_icon : '' ) );
			$simple_cart_html .= '"';
		}

		$args = array(
			'quantity'              => 1,
			'attributes'            => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);
		if ( method_exists( $product, 'add_to_cart_aria_describedby' ) ) {
			$args['aria-describedby_text'] = $product->add_to_cart_aria_describedby();
		}

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $args, $product );

		if ( $porto_settings['category-addlinks-convert'] ) {
			$tag = 'span';
		} else {
			$tag = 'a';
		}

		$btn_classes = $common_cls . ' porto-tb-addcart product_type_' . $product->get_type() . ' viewcart-style-' . ( $porto_settings['add-to-cart-notification'] ? (int) $porto_settings['add-to-cart-notification'] : '1' );
		if ( isset( $args['class'] ) ) {
			$btn_classes .= ' ' . trim( $args['class'] );
		}

		if ( ! $porto_tb_catalog_mode ) {
			$btn_classes .= ' add_to_cart_button';
			if ( $product->is_purchasable() && $product->is_in_stock() ) {
				if ( $product->supports( 'ajax_add_to_cart' ) ) {
					$btn_classes .= ' ajax_add_to_cart';
				}
			} else {
				$btn_classes .= ' add_to_cart_read_more';
			}
		} else {
			$btn_classes .= ' add_to_cart_read_more';
		}

		if ( apply_filters( 'porto_product_loop_show_price', true ) ) {

			$more_target = '';
			if ( $porto_tb_catalog_mode ) {

				unset( $args['attributes']['data-product_id'] );
				unset( $args['attributes']['data-product_sku'] );

				$btn_link = $product->get_permalink();
				if ( $porto_settings['catalog-readmore'] && 'all' === $porto_settings['catalog-readmore-archive'] ) {
					$link = get_post_meta( $product->get_id(), 'product_more_link', true );
					if ( $link ) {
						$btn_link = $link;
					}
					$more_target = $porto_settings['catalog-readmore-target'] ? ' target="' . esc_attr( $porto_settings['catalog-readmore-target'] ) . '"' : '';
				}
			} else {
				$btn_link = apply_filters( 'porto_cpo_add_to_cart_url', $product->add_to_cart_url(), $product );
			}

			if ( ! empty( $atts['show_quantity_input'] )  && ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) && $product->is_purchasable() && $product->is_in_stock() ) {
				if ( $product->is_type( 'variable' ) ) {
					ob_start();
				}
				woocommerce_quantity_input(
					array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
					), $product
				);
				if ( $product->is_type( 'variable' ) ) {
					$qty_html = ob_get_clean();
					$qty_html = esc_attr( $qty_html );
				}
			}
			if ( ! empty( $qty_html ) ) {
				$qty_html = 'data-qty-html="' . $qty_html . '"';
			}

			$aria_describedby = isset( $args['aria-describedby_text'] ) ? sprintf( ' aria-describedby="woocommerce_loop_add_to_cart_link_describedby_%s"', esc_attr( $product->get_id() ) ) : '';

			echo apply_filters(
				'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
				sprintf(
					'<%s %s %s title="%s" href="%s"%s data-quantity="%s" class="' . esc_attr( apply_filters( 'porto_elements_wrap_css_class', $btn_classes, $atts, 'porto-tb/porto-woo-buttons' ) ) . ' %s" %s%s>%s</%s>',
					$tag,
					! empty( $qty_html ) ? $qty_html : '',
					$simple_cart_html,
					esc_html( $product->add_to_cart_text() ),
					esc_url( $btn_link ),
					$aria_describedby,
					esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
					esc_attr( ( isset( $args['class'] ) ? $args['class'] : '' ) /*. ( $product->is_purchasable() && $product->is_in_stock() ? '' : ' add_to_cart_read_more' )*/ ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					$more_target,
					$porto_tb_catalog_mode ? esc_html( $porto_settings['catalog-readmore-label'] ) : ( ! isset( $atts['icon_pos'] ) || 'right' != $atts['icon_pos'] ? $icon_html : '' ) . ( empty( $atts['hide_title'] ) ? esc_html( $product->add_to_cart_text() ) : '' ) . ( isset( $atts['icon_pos'] ) && 'right' == $atts['icon_pos'] ? $icon_html : '' ),
					$tag
				),
				$product,
				isset( $args ) ? $args : array()
			);

			if ( isset( $args['aria-describedby_text'] ) ) {
				echo '<span id="woocommerce_loop_add_to_cart_link_describedby_' . esc_attr( $product->get_id() ) . '" class="screen-reader-text">' . esc_html( $args['aria-describedby_text'] ) . '</span>';
			}
		}
	} elseif ( 'wishlist' == $atts['link_source'] && defined( 'YITH_WCWL' ) ) {
		if ( function_exists( 'yith_wcwl_wishlists' ) ) {
			$exists = yith_wcwl_wishlists()->is_product_in_wishlist( $product->get_id() );
		} else {
			$exists    = YITH_WCWL()->is_product_in_wishlist( $product->get_id() );
		}
		$shortcode = '[yith_wcwl_add_to_wishlist';
		/*if ( ! empty( $atts['icon_cls'] ) ) {
			$shortcode .= ' icon="' . esc_attr( $atts['icon_cls'] ) . '"';
		}*/
		$shortcode .= ']';
		if ( ! empty( $atts['hide_title'] ) ) {
			$common_cls .= ' hide-title';
		}
		echo '<div class="' . esc_attr( apply_filters( 'porto_elements_wrap_css_class', $common_cls . ' porto-tb-wishlist' . ( $exists ? ' exists' : '' ), $atts, 'porto-tb/porto-woo-buttons' ) ) . '">';
		echo do_shortcode( $shortcode );
		echo '</div>';
	} elseif ( 'compare' == $atts['link_source'] && function_exists( 'porto_template_loop_compare' ) ) {
		porto_template_loop_compare( apply_filters( 'porto_elements_wrap_css_class', $common_cls . ' porto-tb-compare', $atts, 'porto-tb/porto-woo-buttons' ), isset( $atts['hide_title'] ) ? $atts['hide_title'] : false, isset( $atts['icon_cls'] ) ? $atts['icon_cls'] : '', isset( $atts['icon_pos'] ) ? $atts['icon_pos'] : 'left', isset( $atts['icon_cls_added'] ) ? $atts['icon_cls_added'] : '' );
	} elseif ( 'quickview' == $atts['link_source'] ) {
		if ( ! wp_script_is( 'wc-add-to-cart-variation' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		$label = ! empty( $porto_settings['product-quickview-label'] ) ? $porto_settings['product-quickview-label'] : __( 'Quick View', 'porto' );

		$inner_html_escaped = '';
		if ( empty( $atts['hide_title'] ) ) {
			$inner_html_escaped = esc_html( $label );
		}
		if ( $icon_html ) {
			if ( ! isset( $atts['icon_pos'] ) || 'right' != $atts['icon_pos'] ) {
				$inner_html_escaped = $icon_html . $inner_html_escaped;
			} else {
				$inner_html_escaped .= $icon_html;
			}
		}
		echo '<div class="' . esc_attr( apply_filters( 'porto_elements_wrap_css_class', $common_cls . ' porto-tb-quickview quickview', $atts, 'porto-tb/porto-woo-buttons' ) ) . '" data-id="' . absint( $product->get_id() ) . '" title="' . esc_attr( $label ) . '">' . $inner_html_escaped . '</div>';

	} elseif ( 'swatch' == $atts['link_source'] && function_exists( 'porto_woocommerce_display_variation_on_shop_page' ) ) {
		if ( ! wp_script_is( 'wc-add-to-cart-variation' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}
		if ( ! empty( $atts['quick_shop'] ) ) {
			if ( ! wp_script_is( 'porto-quick-shop' ) ) {
				wp_enqueue_script( 'porto-quick-shop' );
			}
			$common_cls .= ' quick-shop';
		}
		if ( isset( $atts['font_settings'] ) && ! empty( $atts['font_settings']['textAlign'] ) ) {
			$common_cls .= ' ' . $atts['font_settings']['textAlign'];
		}
		
		porto_woocommerce_display_variation_on_shop_page( apply_filters( 'porto_elements_wrap_css_class', $common_cls . ' porto-tb-swatch', $atts, 'porto-tb/porto-woo-buttons' ), ! empty( $atts['quick_shop'] ) );
	}
}
