<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u217165591_ckjtsasdas' );
/** MySQL database username */
define( 'DB_USER', 'u217165591_saddasd' );
/** MySQL database password */
define( 'DB_PASSWORD', 'K|t9nF7XVo' );
/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ':C`y;!rf@4;I>xN,WRl*&kDu;DJP>:4ws)y)]P7JhaeDN;m<0nFtv_.:]DLtCb|>' );
define( 'SECURE_AUTH_KEY',   ' j<] o5qm<o}NP9-Q<]~LJ<!Up=noeA/ri/}tNG%#!H`z&:.v~gxvB1=l3rH15GM' );
define( 'LOGGED_IN_KEY',     'V*x5Wm3~ziHx;JKxUk9XOI3R0Vv]s,EZ<)<1_ ;&CeJf%TI=}wB>rQo.f(anrXi3' );
define( 'NONCE_KEY',         'IT;?_Cm(q#aVzKs27Wdh9IqF<~(9Kp8:x.RP((=OD<r!3/-lbG$B(hb{5}%-jg*N' );
define( 'AUTH_SALT',         'HYi@yxF{%jJ|rKysocw%&]U/:~`wu]3;~{($@)#Uqaf+o|HA.1,N; txz9Qy1h]c' );
define( 'SECURE_AUTH_SALT',  'N`ygh@G`| +1cyu9-;Rkmd[2kcV$5 uimzI=VP8> Af6-0XfL[^dH~>Ws?4V<}aR' );
define( 'LOGGED_IN_SALT',    'fuI{D iw9X,ie%ES^vF/.7:K(g:x9Lc`.;adHn1t!5)#`N.bo|lj{0hymHf?~)9!' );
define( 'NONCE_SALT',        's(tsJeQMY_=>q>X64,W??o4yB0M<] HVGVWQmfFFA.~y:d1 D[qQ<P4YEOOu8NX)' );
define( 'WP_CACHE_KEY_SALT', 'zY#j%j-G><zy1#xvSy5<B3_2!2;raT~MNvUYbtoy@:|b[~t415?Pj~W6Zo_(^H?@' );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'FS_METHOD', 'direct' );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';