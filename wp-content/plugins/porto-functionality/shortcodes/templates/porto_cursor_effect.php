<?php
$default_atts = array(
	'cursor_shape'    => '',
	'shapes_size'     => '10,30,50',
	'shapes_color'    => '#5AE2FF, #3CC4FF, #1EA6EA',
	'selector'        => '',
	'hover_effect'    => 'plus',
	'inner_icon'      => '',
	'icon_type'       => 'fontawesome',
	'icon_simpleline' => '',
	'icon_porto'      => '',
	'cursor_w'        => '',
	'el_id'           => '',
	'builder'         => 'wpb',
);
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		$default_atts,
		$atts
	)
);

switch ( $icon_type ) {
	case 'simpleline':
		$inner_icon = $icon_simpleline;
		break;
	case 'porto':
		$inner_icon = $icon_porto;
		break;
}

wp_enqueue_script( 'porto-cursor-effect' );

$cursor_cls = '';
if ( ! empty( $shortcode_class ) ) {
	$cursor_cls .= trim( $shortcode_class );
} elseif ( ! empty( $el_id ) ) {
	$cursor_cls = 'cursor-element-' . $el_id;
}

if ( $cursor_shape ) :
	if ( $shapes_size ) {
		$sizes  = explode( ',', $shapes_size );
		$colors = explode( ',', $shapes_color );
		if ( ! empty( $sizes ) ) {
			wp_enqueue_script( 'porto-gsap' );
			if ( 'elementor' == $builder ) {
				$spots_wrapper = '.elementor-section';
			} else {
				$spots_wrapper = '.vc_row';
			}
			$options = array(
				'id'           => $cursor_cls,
				'size'         => $sizes,
				'color'        => $colors,
				'spotsWrapper' => $spots_wrapper,
			);
			echo '<div class="cursor-shapes" data-cursor-shape data-plugin-options="' . esc_attr( json_encode( $options ) ) . '"></div>';
		}
	}
	return;
endif;

if ( ! function_exists( 'vc_is_inline' ) || ! vc_is_inline() ) :
	?>
<script>
	if ( typeof window.porto_cursor_effects == 'undefined' ) {
		window.porto_cursor_effects = [];
	}
	window.porto_cursor_effects.forEach( function( i, index ) {
		if ( i.id && '<?php echo esc_js( $cursor_cls ); ?>' == i.id ) {
			window.porto_cursor_effects.splice( index, 1 );
			return false;
		}
	} );
	window.porto_cursor_effects.push( { id: '<?php echo esc_js( $cursor_cls ); ?>', selector: '<?php echo sanitize_text_field( str_replace( '&gt;', '>', $selector ) ); ?>', hover_effect: '<?php echo esc_js( $hover_effect ); ?>', icon: '<?php echo esc_js( $inner_icon ); ?>', cursor_w: <?php echo (int) $cursor_w; ?> } );
</script>
	<?php
elseif ( ! empty( $shortcode_class ) ) :
	echo '<div class="opacity-0"></div>';
	echo '<span class="shortcode-class d-none">' . esc_html( $shortcode_class ) . '</span>';
endif;
