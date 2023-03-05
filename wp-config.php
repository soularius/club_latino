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
define('DB_NAME', 'club_latino');

/** Database username */
define('DB_USER', 'doadmin');

/** Database password */
define('DB_PASSWORD', 'AVNS_Fjv7Pd1VA7J2L9ueBTQ');

/** Database hostname */
define('DB_HOST', 'db-mysql-nyc1-08363-do-user-8886421-0.b.db.ondigitalocean.com');
define('DB_PORT', 25060);

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         '+R4}Ak.u:~^VT !AG#r8+h7{Sn6)~CIi49-I]#dx^Np(500fueZ]L^mKBKdq7nLO');
define('SECURE_AUTH_KEY',  'PQs>H#}f:%1NX!<UQupfi20Y:AZea).T2Bq0Xl4;a#{h-{.Z<=. NxBf1ZFLaW[&');
define('LOGGED_IN_KEY',    'b%!:ZJ/Hv8n(FPLJs0cBmnFpfR3kZrQ8Jtt*xHDZ*p~0NZ7Zo0%l}E9bbIp!=(Fe');
define('NONCE_KEY',        'GMsb2Qk SRI95QBxBd,17Cdon@?7m<oC2h@IUd~]!f%LSmKKeV{Lic#r:A|ltz@G');
define('AUTH_SALT',        '!HcJ)ewPLR@z|:ZRkj]oBGu/lq{9CU6TWKI16cl~!sB;=)j>TZ|w%ghn0%Dy>r9h');
define('SECURE_AUTH_SALT', 'qc,;Hroj!:}b/N,[H)G3ig& RlW!^{-cEO]5b|G57QTY%liVo2O*y_bWl$PKvwDj');
define('LOGGED_IN_SALT',   ')WYBJ-MFX.wq.eNpg%;qpV1wnQ:{fpZk@47YMA O n@>iJu%NO8]{e9f*BdN++nk');
define('NONCE_SALT',       'v+P20?YAI.sQL#6H/`QM[3BxwWVD(Ue-gm!gnf/{t8h|6H%ZNfu/ufSU(@gbt**r');

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
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
