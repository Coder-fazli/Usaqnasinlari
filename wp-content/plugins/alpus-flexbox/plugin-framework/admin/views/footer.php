<?php
/**
 * Alpus Plugin Header
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || die;

?>

<nav class="alpus-plugin-footer">
	<?php
	foreach ( $this->plugin_config['admin']['links'] as $key => $item ) {
		$url       = isset( $item['url'] ) ? $item['url'] : '#';
		$label     = isset( $item['label'] ) ? $item['label'] : '';
		$desc      = isset( $item['description'] ) ? $item['description'] : '';
		$button    = isset( $item['button'] ) ? $item['button'] : '#';
		$icon      = isset( $item['icon'] ) ? $item['icon'] : '';
		$icon_html = ! isset( $item['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : esc_html( $icon );

		echo '<div class="alpus-external-link">';
			echo '<div class="external-link-box">';
				echo '<h3>' . $icon_html . esc_html( $label ) . '</h3>';
				echo '<p>' . $desc . '</p>';
				echo '<a href="' . $url . '" class="button-primary" target="_blank">' . $button . '</a>';
			echo '</div>';
		echo '</div>';
	}
	?>
</nav>
