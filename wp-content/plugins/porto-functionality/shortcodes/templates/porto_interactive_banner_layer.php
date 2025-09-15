<?php
if ( ! empty( $atts['className'] ) ) {
	$atts['el_class'] = $atts['className'];
}

extract(
	shortcode_atts(
		array(
			'width'                => '',
			'height'               => '',
			'horizontal'           => 50,
			'vertical'             => 50,
			'horizontal_tablet'    => '',
			'vertical_tablet'      => '',
			'horizontal_mobile'    => '',
			'vertical_mobile'      => '',
			'layer_link'           => '',
			'css_ibanner_layer'    => '',
			'animation_type'       => '',
			'animation_duration'   => 1000,
			'animation_delay'      => 0,
			'animation_reveal_clr' => '',
			'el_class'             => '',
		),
		$atts
	)
);

$output        = '';
$inline_styles = '';
$css_ib_styles = '';
if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
	$css_ib_styles = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_ibanner_layer, ' ' ), 'porto_interactive_banner_layer', $atts );
}
$el_class      = porto_shortcode_extract_class( $el_class );

$classes = 'porto-ibanner-layer';
if ( $css_ib_styles ) {
	$classes .= ' ' . trim( $css_ib_styles );
}
if ( $el_class ) {
	$classes .= ' ' . trim( $el_class );
}
if ( ! empty( $shortcode_class ) ) {
	$classes .= $shortcode_class;
}

$position = array(
	'horizontal'         => $horizontal,
	'vertical'           => $vertical,
	'horizontal_tablet'  => $horizontal_tablet,
	'vertical_tablet'    => $vertical_tablet,
	'horizontal_mobile'  => $horizontal_mobile,
	'vertical_mobile'    => $vertical_mobile,
);
$pos_class = 'banner-pos-' . hash( 'md5',  json_encode( $position ) );
if ( ! empty( $pos_class ) ) {
	$classes .= ' ' . $pos_class;
}

if ( $width ) {
	$unit = trim( preg_replace( '/[0-9.]/', '', $width ) );
	if ( ! $unit ) {
		$width .= '%';
	}
	$inline_styles .= 'width:' . esc_attr( $width ) . ';';
}
if ( $height ) {
	$unit = trim( preg_replace( '/[0-9.]/', '', $height ) );
	if ( ! $unit ) {
		$height .= '%';
	}
	$inline_styles .= 'height:' . esc_attr( $height ) . ';';
}

$extra_styles = '';
if ( ! function_exists ( 'porto_banner_pos_css' ) ) {
	function porto_banner_pos_css( $horizontal, $vertical, $shortcode_class, $breakpoint = false ) {
		if ( '' == $horizontal && '' == $vertical ) {
			return '';
		}
		$extra_styles = '';
		if ( is_rtl() ) {
			$left  = 'right';
			$right = 'left';
		} else {
			$left  = 'left';
			$right = 'right';
		}
	
		if ( $breakpoint ) {
			$extra_styles .= '@media(max-width:' . $breakpoint . ') {';
		}
		$extra_styles .= '.' . $shortcode_class . ' {';
		if ( '' !== $horizontal ) {
			if ( 50 === (int) $horizontal ) {
				if ( 50 === (int) $vertical ) {
					$extra_styles .= 'left: 50%;right: unset;top: 50%;bottom: unset;transform: translate(-50%, -50%);';
				} else {
					$extra_styles .= 'left: 50%;right: unset;transform: translateX(-50%);';
				}
			} elseif ( 50 > (int) $horizontal ) {
				$extra_styles .= $left . ':' . $horizontal . '%;' . $right . ': unset;';
			} else {
				$extra_styles .= $right . ':' . ( 100 - $horizontal ) . '%;' . $left . ': unset;';
			}
		}
		if ( '' !== $vertical ) {
			if ( 50 === (int) $vertical ) {
				if ( 50 !== (int) $horizontal ) {
					$extra_styles .= 'top: 50%;bottom: unset;transform: translateY(-50%);';
				}
			} elseif ( 50 > (int) $vertical ) {
				$extra_styles .= 'top:' . $vertical . '%; bottom: unset;';
			} else {
				$extra_styles .= 'bottom:' . ( 100 - $vertical ) . '%; top: unset;';
			}
		}
		if ( $breakpoint ) {
			if( ( '' !== $horizontal || '' !== $vertical ) && 50 !== (int) $horizontal && 50 !== (int) $vertical ) {
				$extra_styles .= 'transform: none;'	;
			}
			$extra_styles .= '}';
		}
		$extra_styles .= '}';
		
		return $extra_styles;
	}
}
if ( ! empty( $pos_class ) ) {
	$extra_styles .= porto_banner_pos_css( $horizontal, $vertical, $pos_class );
	$extra_styles .= porto_banner_pos_css( $horizontal_tablet, $vertical_tablet, $pos_class, '991px' );
	$extra_styles .= porto_banner_pos_css( $horizontal_mobile, $vertical_mobile, $pos_class, '767px' );
}

$attrs = '';
if ( $animation_type ) {
	$attrs .= ' data-appear-animation="' . esc_attr( $animation_type ) . '"';
	if ( $animation_delay ) {
		$attrs .= ' data-appear-animation-delay="' . esc_attr( $animation_delay ) . '"';
	}
	if ( $animation_duration && 1000 != $animation_duration ) {
		$attrs .= ' data-appear-animation-duration="' . esc_attr( $animation_duration ) . '"';
	}
	if ( false !== strpos( $animation_type, 'revealDir' ) ) {
		$attrs .= ' data-animation-reveal-clr="' . ( ! empty( $animation_reveal_clr ) ? esc_attr( $animation_reveal_clr ) : '' ) . '"';
	}
}

$output = '<div class="' . esc_attr( $classes ) . '"';
if ( $inline_styles ) {
	$output .= ' style="' . esc_attr( $inline_styles ) . '"';
}
$output .= '>';

if ( $animation_type ) {
	$output .= '<div';
	$output .= $attrs;
	$output .= '>';
}

$output .= do_shortcode( $content );
if( '' !== $extra_styles ) {
	$output .= '<style>' . $extra_styles . '</style>';
}
if ( $animation_type ) {
	$output .= '</div>';
}

$output .= '</div>';

echo porto_filter_output( $output );
