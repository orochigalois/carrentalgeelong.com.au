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
define('DB_NAME', 'i806193_wp8');

/** MySQL database username */
define('DB_USER', 'i806193_wp8');

/** MySQL database password */
define('DB_PASSWORD', 'Q]lNhjIun93T&TEt(G@40[@7');

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
define('AUTH_KEY',         'tPQi4kXmG0fz4LgDXyzcZcdUAoZgpPdKyXdxN9X1v2YPOxZSWTDvkBD272PqOFCX');
define('SECURE_AUTH_KEY',  'ryxuVj4IrVw1MlUzplHBA9R8wR5ouVxKEuL9u48R9PeRjjNMaKji0fpteY7aHV4R');
define('LOGGED_IN_KEY',    'OzutfbojpyB42EHTyXi7fapjlhDYrJq2KZywwwDhEZfKFoyhRRvUANEYhDplfkXD');
define('NONCE_KEY',        'oHNWa6ZxIxUuVIk9haXl64tH4OHREVeO4eddVgIsA1DlDcaTGeBpOP85p4eOtDSS');
define('AUTH_SALT',        'O5AUiUWFCPUY69yCeqVWQlbcBEHXxjWxbZ6B6PdNyuV89Nx22v3rmOFsoQl2PPB6');
define('SECURE_AUTH_SALT', 'MdrQ7EsYBXE9Iu45ANKhfZFYok3ZgTAR98JpTbt9NQdqGNE2riJtqrppFuEOoLyw');
define('LOGGED_IN_SALT',   'NnWpZvCIcOxEDg597N2VXP7yILTitQ5iNcW29duGoam9QyqAIgp0L8fvySSIXIgV');
define('NONCE_SALT',       '1O0IqeAHlUsFfwyfGM0otELFbRkxEP6OI3FQTdhIkYvivy4yOEXjrkGqvO1kZJfL');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
