<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '__safe_name__');

/** MySQL database username */
define('DB_USER', '__safe_name__');

/** MySQL database password */
define('DB_PASSWORD', '__db_pass__');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'V[e|B<E=Cnj8bs,k4Se<|PyEM0ME8;*:r!5=?e[UNX:zsnj9|Gu}#|VWx&6],rmy');
define('SECURE_AUTH_KEY',  '/O2xfHb^#vnE5@n hK4h1H!vr7Q@MbRi9T6p>qhpKC Ue7{Uk5f&esG3r?vSj`,g');
define('LOGGED_IN_KEY',    '/6(o-_Y;b-p+BY$)#]OyUI(<EH*woB2@BJ--V~p.DPqqXvAV]l48BW?3upP)-P35');
define('NONCE_KEY',        '.tH sS|t~N%xU8HhR- 4Rel1b!VR7:5TwPa>:Bres+H(Vo^S|0 5y{@Tz+Q8m#?s');
define('AUTH_SALT',        'kY$O!s)U>>5E!<j`aVM+8%W0uwvb`><GGr-JeeYqVilf>M{OK)j]SWwRA.4.sQ= ');
define('SECURE_AUTH_SALT', 'sAff8+Gd15CmLX:RXy+82,0wa 3M2gj,.uSb{1_&^G<`K/6ox=<#hW)qI:r^/ZB;');
define('LOGGED_IN_SALT',   'U-@wCyU7^@nfbnUs?AoPb$BJ8zHD#Mc/}?t]kDXk>8{WpD!9bY^~<])ul>3V;L*W');
define('NONCE_SALT',       ')}P$3%_ntU}3F[CjS$+Wy{LAZPWZbs]K%pPo+_}ocbDg T^([Z{&v0d$8iik{qD!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

if (stristr($_SERVER['HTTP_HOST'], 'localhost')) {
    $p = $_SERVER['PHP_SELF'];
	if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
    {
	    $p = str_replace( $_SERVER['PATH_INFO'], '', $p );
    }
    $p = preg_replace('#(\/wp-.*|\/index.php$)#', '', $p);
    define('WP_SITEURL','http://' . $_SERVER['HTTP_HOST'] . $p);
    define('WP_HOME', WP_SITEURL);
} elseif (stristr($_SERVER['HTTP_HOST'], 'aristotle.net')) {
    define('WP_SITEURL','http://' . $_SERVER['HTTP_HOST']);
    define('WP_HOME', WP_SITEURL);
} else {
    define('WP_SITEURL','http://update.domain.in.wp.config.please');
    define('WP_HOME', WP_SITEURL);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
