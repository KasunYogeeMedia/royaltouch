<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'royaltouchceylon_web' );
/** Database username */
define( 'DB_USER', 'royaltouchceylon_web' );
/** Database password */
define( 'DB_PASSWORD', '61]Op9fSW!' );
/** Database hostname */
define( 'DB_HOST', 'localhost' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'c8mdlu8rce1pjaxtne1rgsjrsvopsaswfp0xz4elus7lv1tzmbmsjqr1zkebjlso' );
define( 'SECURE_AUTH_KEY',  'af0o1sxdzrchwc3gjmcryv5ouwp0a4hh2l3s1uh9vhu4laovjnwlhtpf6josjcs8' );
define( 'LOGGED_IN_KEY',    'o7pjz7y2jq66ne2okmx3csoz2ymcdch93ckwkr0adlm3uq0pc7i0in8icswj0wls' );
define( 'NONCE_KEY',        'lhh8lbvy9ftr1zbb2bdx419mw2nyyjbitw6kmltlbmigsayrlqiq5flmylelayjz' );
define( 'AUTH_SALT',        'tfjtmjiqt24owntbrcaads5uplmwykus7cnwhuvbb8jm6ickxnexqbdcr36xazfm' );
define( 'SECURE_AUTH_SALT', '1khrbgzk322u7gas9kb0mc1ldqpbxqpseru1bv42qzoxbcajomeobhdzvjzzbhsw' );
define( 'LOGGED_IN_SALT',   'eackrl11hit9vktnhzp3khrlewvbohsg13gub10839hvaqdiwyfkqi2yih3ebnwe' );
define( 'NONCE_SALT',       '40ntijkkwptfnuqj9stibp6bjlxmszc9ijy99rzawhbgtdvu4prbdsr2pvr9zutv' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
