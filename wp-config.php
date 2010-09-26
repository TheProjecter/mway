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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123456');

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
define('AUTH_KEY',         '@JvPj|eVtU4Xl]_eC+bdhC4|lD2!GK93cf+/T!tpDLrSKErrH2eKD9k},^*Kh7)G');
define('SECURE_AUTH_KEY',  'Ry8)/hox0Os^PjQ8j3w;]/DxmKcR$pkEMdh i$V#9e0TKj%y%ff~pJ#g=O}t^g`*');
define('LOGGED_IN_KEY',    '8&[xOJ71IAtEt/DD>:{vdOeq3+3BkDbXczk7ZiWu~_,?[}ctC;.+WIfcqa.e!<R6');
define('NONCE_KEY',        'p6uzB<Y l<U*edgWxdq,PxGl)QVa=?:AB1^JZYVB<n/kWwZ-LR8nMHl.^}tE7rA5');
define('AUTH_SALT',        ']cV]T>o@&Vp]<) -%FNps^/D/B.T<Jf0Ue!}Gx6TC~;)Gv22.gp$jlni$$DThu,u');
define('SECURE_AUTH_SALT', '#XdvyOsL%WmSjw`[ADkTLB4X_HYW]swBJBwh:>.%/q`k:ogJJ*@t);Vm HJB3TN_');
define('LOGGED_IN_SALT',   'vF74<o2OuO>7cIu6gu>lRM%5<9XUkCOhuS[tHEbGdw$#G8,} g!bS4c0?k_+g;c&');
define('NONCE_SALT',       '4W+3cmxK78]DrwAPT#,6/V~sl`=ov8AAHf$CL8<,|zV<ht/r[{b|(vzOC:*CJ;D7');

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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
