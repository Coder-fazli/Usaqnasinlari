<?php
// Porto Single Product Attributes

if ( ! defined( 'PORTO_VERSION' ) ) {
	return;
}

$default_atts = array(
	'table_title'      => '',
	'title_typography' => '',
	'title_color'      => '',
	'attr_source'      => 'all',
	'attr_include'     => '',
	'attr_exclude'     => '',
);
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		$default_atts,
		$atts
	)
);

global $product;
$attributes = array(
    'width'  => __( 'Width', 'porto-functionality' ),
    'height' => __( 'Height', 'porto-functionality' ),
    'length' => __( 'Length', 'porto-functionality' ),
    'weight' => __( 'Weight', 'porto-functionality' ),
);
$wc_attributes = wc_get_attribute_taxonomy_labels();
if ( 'include' == $attr_source ) {
    if ( is_string( $attr_include ) ) {
        $attr_include = explode( ',', $attr_include );
    }
    if ( is_array( $attr_include ) ) {
        foreach ( $attributes as $key => $attribute ) {
            if ( ! in_array( $key, $attr_include ) ) {
                unset( $attributes[ $key ] );
            }
        }
        if ( $wc_attributes ) {
            foreach ( $wc_attributes as $key => $attribute ) {
                if ( in_array( 'pa_' . $key, $attr_include ) ) {
                    $attributes[ 'pa_' . $key ] = $attribute;
                }
            }
        }
    }
} else {
    if ( ! empty( $attr_exclude ) && is_array( $attr_exclude ) ) {
        foreach( $attributes as $key => $value ) {
            if ( in_array( $key, $attr_exclude ) ) {
                unset( $attributes[$key] );
            }
        }
    }
    if ( $wc_attributes ) {
        if ( is_string( $attr_exclude ) ) {
            $attr_exclude = explode( ',', $attr_exclude );
        }
        foreach ( $wc_attributes as $key => $attribute ) {
            if ( 'all' == $attr_source || '' == $attr_exclude || ( is_array( $attr_exclude ) && ! in_array( 'pa_' . $key, $attr_exclude ) && 'exclude' == $attr_source ) ) {
                $attributes[ 'pa_' . $key ] = $attribute;
            }
        }
    }
}
?>
<table class="porto-cp-attr-table wc-attr-table<?php echo ( ! empty( $shortcode_class ) ? esc_attr( $shortcode_class ) : '' ); ?>">
    <?php if ( '' !== $table_title ) { ?>
        <thead>
            <tr>
                <th colspan="2"><span class="porto-attr-title"><?php echo esc_html( $table_title ); ?></span></th>
            </tr>
        </thead>
    <?php } ?>
    <tbody>
        <?php
        if ( ! empty ( $attributes ) ) {
            foreach ( $attributes as $key => $name ) {
                $value = '';
                if ( in_array( $key, array( 'width', 'height', 'weight', 'length' ) )  ) {
                    $value = call_user_func( array( $product, 'get_' . $key ) );
                    if ( '' !== $value ) {
                        if ( 'weight' == $key ) {
                            $value .= get_option( 'woocommerce_weight_unit' );
                        } else {
                            $value .= get_option( 'woocommerce_dimension_unit' );
                        }
                    }
                } else {
                    $value = wc_get_product_terms( $product->get_id(), $key, array( 'fields' => 'names' ) );
                }
                if ( ! empty( $value ) ) {
                    ?>
                    <tr class="porto-attr-data">
                        <th><span class="porto-attr-name"><?php echo esc_html( $name ); ?></span></th>
                        <td><span class="porto-attr-term"><?php echo esc_html( is_array( $value ) ?  implode( ', ', $value ) : $value ); ?></span></td>
                    </tr>
                    <?php }
                }
        }?>
	</tbody>
</table>