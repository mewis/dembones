<?php
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
define('DB_NAME', 'dembones');

/** MySQL database username */
define('DB_USER', 'mewis');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '8 1oxhS!-!`QR<]rW)_6{-Abzyd![5vS[kl%l=UYTxlh,#!mUBPpt@KVqt;@ luq');
define('SECURE_AUTH_KEY',  'f D]O(HVb.o80?oVnJcRWcNU`uI$kSR,XVPmJn_bb:U/ogZNZM3RfuMEvgC3#beS');
define('LOGGED_IN_KEY',    'Y;]!kci%}^Ks{|Y.B4M:B^(uF:T$|u_FgO`vyCW:8ynN25dKiOUX;>uo9iM/;}sj');
define('NONCE_KEY',        'BH!xsI<AAZ`;3CtpZ|w*}^JyQWHl7B|*IGbZf4BY.sNzDBbTqoqW^rfB*p5LbVdb');
define('AUTH_SALT',        '@t0c3.`dWp3RQ(8w+<o5{]o/x>Hl2DHmYw*KfZ!N{<{{{TD$bOO0fnXdx{GXWoNt');
define('SECURE_AUTH_SALT', 'Do5Z~eGCHl.<:4WQ+d%K{u2~qI;$A:~~j}zMS.&TS]1FL s/p>mr0%-P;b/m3*Ax');
define('LOGGED_IN_SALT',   'FDR(9^60^nwIp!Q4-%SP@C]d6hAd*=I0(BNS`KAw3lay(w#4-GDI(Q.R)SLNMlY/');
define('NONCE_SALT',       'e])5eI3ITCb{,#]$-c}L%KIniie0S2<PS%gDmCF`{/ISb8iaQPYlpivw(1{][l_J');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
