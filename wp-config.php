<?php define( 'UPLOADS', 'wp-content/uploads' ); 
if ( file_exists( dirname( __FILE__ ) . '/gd-config.php' ) ) {
	require_once( dirname( __FILE__ ) . '/gd-config.php' );
	define( 'FS_METHOD', 'direct' );
	define( 'FS_CHMOD_DIR', ( 0705 & ~ umask() ) );
	define( 'FS_CHMOD_FILE', ( 0604 & ~ umask() ) );
}

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

define( 'DB_NAME', "mj9c43879441094" );


/** MySQL database username */

define( 'DB_USER', "mj9c43879441094" );


/** MySQL database password */

define( 'DB_PASSWORD', 'eMy_WM2Mn{k9' );


/** MySQL hostname */

define( 'DB_HOST', "mj9c43879441094.db.43879441.353.hostedresource.net:3308" );


/** Database Charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8' );


/** The Database Collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );


/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',         'szoyg0HJQLd6FxyL3a3d3tHDRnaBiwm99GQvdjYH15dlOatzTTG5uO6PkmQG28yp');

define('SECURE_AUTH_KEY',  'gedffN9T4nKgM2ykiG9Q4EzN3IASDw7codGcA2ChEwfzxwCy0xTrJ73XqYK86aea');

define('LOGGED_IN_KEY',    'eg8GJVYYkCbkTFb27NlgFD5LNjjUYY9YQF2SKMUk3WvsKa6NGBKWXHjbxyiGebxS');

define('NONCE_KEY',        '89jGABVuv7VVKULM2TUSleKLDjdXcTRh5iFlO3mJruw2VdxOezjaiMvEqeraVPZa');

define('AUTH_SALT',        'YeWKhbFwKlke0VZzyOuIJKyy9FcGSjRAH3lJaZ29B6pG4i8aWTOmyQryp6R4lgQy');

define('SECURE_AUTH_SALT', 'WcsbIBJMxORML7Y8WrhPu93bBSTdSTVPod9eToXAEANqCVyYEyTylpWTVOWvws5R');

define('LOGGED_IN_SALT',   'bcuyTMwnuFz1Og95neiJB4wJfApQfJkUVza4MInaA3ateABydkazXDjXt4eQiHa1');

define('NONCE_SALT',       'OpemAag1XQkJWToFqDyGlZ1Ac1NyYV7nxQrobzJvAnCmKaq1RH73IaSr8fvw0rRx');


/**

 * Other customizations.

 */




define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


/**

 * Turn off automatic updates since these are managed externally by Installatron.

 * If you remove this define() to re-enable WordPress's automatic background updating

 * then it's advised to disable auto-updating in Installatron.

 */

define('AUTOMATIC_UPDATER_DISABLED', true);



/**#@-*/


/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = "wp_1r137v5kqq_";


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


/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

