<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_2730834' );

/** MySQL database username */
define( 'DB_USER', 'jelastic-1422136' );

/** MySQL database password */
define( 'DB_PASSWORD', 'VTapHi40a0' );

/** MySQL hostname */
define( 'DB_HOST', 'sqldb' );

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
define( 'AUTH_KEY',          'Iv55_bJ5u/9?>+_5hNQu#H*2V1kfJyr{tnupl5gF6km!AOY s<46NF>tAQ{@?l?U' );
define( 'SECURE_AUTH_KEY',   '5V&1,l6Sx@nmQ<[N#:fb46Y`?4Z:[zOR`-^IGhbFaQYk6~J7@ dM}@&9MSJNIX7m' );
define( 'LOGGED_IN_KEY',     '.}%q+}$p3#]Lx}yjS#k<bC+]mNBA:X0al/2;iWYdoE6*`@sAKVidaiSx:%J[.3Me' );
define( 'NONCE_KEY',         'anf#h;d9d?5g*aQ d7#=cQi~8V~s$<4q|g<]AbBg6b386A`&jm]|MsfV:om)OE{O' );
define( 'AUTH_SALT',         '@O/lC;gIeuS$3A.:XwY3/fa{w8vpMg1x=Qhd6RA_FBbrm>]W`/tOK+>4Jz3S&VqE' );
define( 'SECURE_AUTH_SALT',  '*Mu6Hi:,wnhTVA3,&Q4Pkh(RNF2{0e%Gb#4B_,;n97X@(,d}G|~8ntQx/D[6{$RS' );
define( 'LOGGED_IN_SALT',    'uE|%J*gLuc2GmW.MepjaT$Kj<Rr$=&=E$H9QEs^cf9q9&-wU4BF&UDf,McrWu-b=' );
define( 'NONCE_SALT',        'kj*7v3*U2+m.wbzWX?1GSS^;oA;V45(h}fzp>[#lEZyNEts)`JbOg1b;5U8wA-oV' );
define( 'WP_CACHE_KEY_SALT', '1_VI0]W,s4&;j{|WV;NVn)z+&ka0r_C!e@BgpKR`7:gqqzcC.;Z-Vf(wPB:n`Kmn' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-jelastic.php';
require_once ABSPATH . 'wp-settings.php';
