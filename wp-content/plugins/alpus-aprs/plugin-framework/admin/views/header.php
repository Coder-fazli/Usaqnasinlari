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

<div class="alpus-plugin-notices wrap">
	<?php do_action( 'alpus_plugin_notices' ); ?>
</div>

<?php if ( ! empty( $_GET['page'] ) && 'alpus-addons' != $_GET['page'] ) : ?>
<div class="alpus-plugin-header">
	<div class="alpus-plugin-header-left">
		<a href="#" class="alpus-plugin-logo">
			<img src="<?php echo esc_attr( ALPUS_PLUGIN_FRAMEWORK_ASSETS . 'images/logo-white.png' ); ?>" alt="logo" width="138" height="47" />
		</a>
	</div>
	<div class="alpus-plugin-header-center">
		<h2><?php echo esc_html( $this->plugin_config['name'] ); ?></h2>
	</div>
	<div class="alpus-plugin-header-right">
		<?php echo esc_html__( 'Version ', 'alpus-plugin-framework' ) . get_plugin_data( WP_PLUGIN_DIR . '/' . $this->plugin_config['slug'] . '/init.php' )['Version']; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( ! empty( $_GET['page'] ) && 'alpus-addons' == $_GET['page'] ) : ?>
<div class="alpus-plugin-page-header">
	<div class="alpus-plugin-page-header-left">
		<h3><?php esc_html_e( 'Boosts Conversions and Sales', 'alpus-plugin-framework' ); ?></h3>
		<h2 class="text-stroke"><?php esc_html_e( 'Plugin to', 'alpus-plugin-framework' ); ?></h2>
		<img src="<?php echo esc_attr( ALPUS_PLUGIN_FRAMEWORK_URI . 'assets/images/plugins/boost-sales.png' ); ?>" alt="<?php esc_html_e( 'Boost Sales', 'alpus-plugin-framework' ); ?>">
	</div>
	<div class="alpus-plugin-page-header-right">
		<h1><?php printf( esc_html__( 'We are the world\'s %s.', 'alpus-plugin-framework' ), '<strong>' . esc_html__( 'Alpus', 'alpus-plugin-framework' ) . '</strong>' ); ?></h1>
		<div class="divider"></div>
		<p><?php esc_html_e( 'Thanks for using our Alpus plugin framework. We provide lots of professional plugins with full compatibility with famous themes and advanced features. Activating plugins will boost your site\'s sales and popularity.', 'alpus-plugin-framework' ); ?></p>
	</div>
</div>
<?php endif; ?>