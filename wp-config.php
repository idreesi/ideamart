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
$url = parse_url(getenv('DATABASE_URL') ? getenv('DATABASE_URL') : getenv('CLEARDB_DATABASE_URL'));

/** The name of the database for WordPress */
define('DB_NAME', trim($url['path'], '/'));

/** MySQL database username */
define('DB_USER', $url['user']);

/** MySQL database password */
define('DB_PASSWORD', $url['pass']);

/** MySQL hostname */
define('DB_HOST', $url['host']);

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
define('AUTH_KEY',         'MPkZ fYIX;:,IE1_&)*x6>4qzx}>$[ej:oL;bUE9OALa#=l}*}Cdj0l*U:h(qGe=');
define('SECURE_AUTH_KEY',  'uc/wv8Avcs[)eU}>tBm+TNDF:z-.!RNg8vT.=FU3 >c,:ovF.>V~1a2LS;ZrkqVI');
define('LOGGED_IN_KEY',    'n{JMkc5ZD^2i!Cstsw*qJJ;{DVks`lDyA%a&H:i|J!n/K`t9Mm!K9tqe@;B9jl6n');
define('NONCE_KEY',        '&du}*~PXQG~yAy%1`xTU5[M>`b8`6!?jkn7+JlP<ekDd<nZ?-Fm}XdhUQ}W.t_L~');
define('AUTH_SALT',        'jd-7x,~y hbgg`U(M/#a]~(u:rtIj|E[/yy0c+=ZD~h,B<2/31/}n37nUT7gTb8[');
define('SECURE_AUTH_SALT', '>_*+nDwE444VRnYE!~gUO5Q@S}wf(&|;Un#2s:hC%IJ?Ot:z^)9FSm0k&h8M$t8M');
define('LOGGED_IN_SALT',   'Qi#W}5HZJ-Y2gy%u%/I76nW[X%`Ggp{8*uM(*k]pR[$dg|KdI,}yne)lneD7aT([');
define('NONCE_SALT',       'Op!h?gY!95PPvgbP(PIRzj~E){(4fl{9g(8:~0SFQr^y0B{]I03Z]vs>kylqN6?s');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'impk_';

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
