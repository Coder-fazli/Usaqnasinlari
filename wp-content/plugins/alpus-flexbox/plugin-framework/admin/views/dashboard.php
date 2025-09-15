<?php
/**
 * Dashboard Page in Alpus Addon
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || die;

$alpus_plugins = $this->alpus_plugins;
?>
<div class="alpus-plugin-wrap">
	<div class="plguins-content">
		<div class="wp-list-table widefat alpus-plugins">
		<?php
		foreach ( $alpus_plugins as $slug => $plugin ) {

            extract( // @codingStandardsIgnoreLine
				shortcode_atts(
					array(
						'name'          => '',
						'link'          => '#',
						'image'         => '',
						'description'   => '',
						'documentation' => '',
						'url'           => '#',
						'free'          => false,
					),
					$plugin
				)
			);

			?>
			<div class="plugin-card">
				<div class="d-loading"><i></i></div>
				<div class="plugin-card-top">
					<?php
						$disabled = false;
						if ( is_wp_error( validate_plugin( $url ) ) ) {
							$disabled = true;
						}
						if ( defined( 'ALPHA_VERSION' ) && ! $disabled ) {
							$plugins = Alpus_Plugin_Install::get_instance()->_get_plugins();
						}
					?>
					<div class="name column-name">
						<h3>
							<a href="<?php echo esc_url( $link ); ?>" target="_blank" class="plugin-name"><?php echo esc_html( $name ) . ( ( isset( $plugins ) && isset( $plugins['update'][ $slug ] ) ) ? ( '<span>' . esc_html__( 'Update', 'alpus-plugin-framework' ) . '</span>') : '' ) ; ?></a>
							<a href="<?php echo esc_url( $link ); ?>" target="_blank" class="plugin-name">
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( $name ); ?>" class="plugin-icon">
							</a>
						</h3>
					</div>
					<div class="action-links<?php echo ! $disabled && ( ! isset( $plugins ) || ! isset( $plugins['update'][ $slug ] ) ) ? '' : ' disabled' ?>">
						<input class="alpus-switcher alpus_plugin_status" name="alpus_plugin_status" type="checkbox" data-slug="<?php echo esc_attr( $slug ); ?>" data-url="<?php echo esc_attr( $url ); ?>"<?php echo true == $disabled ? ' disabled' : ( is_plugin_active( $url ) ? ' value="1" checked="checked"' : '' ); ?>>
						<?php
						if ( $disabled ) {
							if ( defined( 'ALPHA_VERSION' ) ) { // install plugins
							?>
							<a href="#" class="button button-primary<?php echo ! $free ? '' : ' alpus-plugin-install-button'; ?>"<?php echo 'data-plugin="' . $slug . '"'; ?>><?php echo ! $free ? esc_html__( 'Purchase', 'alpus-plugin-framework' ) : esc_html__( 'Install', 'alpus-plugin-framework' ); ?></a>
							<?php
							} else { // download plugins
								?>
								<a href="<?php echo esc_url( $link ) ?>" class="button button-primary"<?php echo 'data-plugin="' . $slug . '"'; ?> target="_blank"><?php echo ! $free ? esc_html__( 'Purchase', 'alpus-plugin-framework' ) : esc_html__( 'Download', 'alpus-plugin-framework' ); ?></a>
								<?php
							}
						}
						if ( isset( $plugins ) ) { // updated require plugins
							if ( isset( $plugins['update'][ $slug ] ) ) {
								?>
									<a href="#" class="button button-primary<?php echo ! $free ? '' : ' alpus-plugin-install-button'; ?>"<?php echo 'data-plugin="' . $slug . '"'; ?>><?php echo esc_html__( 'Update', 'alpus-plugin-framework' ); ?></a>
								<?php
							}
						}
						?>
					</div>
					<div class="desc column-description">
						<p><?php echo esc_html( $description ); ?></p>
					</div>
					<div class="card-footer">
						<a href="<?php echo esc_url( $documentation ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'alpus-plugin-framework' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</div>
	</div>
</div>
