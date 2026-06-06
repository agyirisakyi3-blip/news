<?php
/**
 * ServerlessWP configuration for NewsBlog
 */

// ** Database settings - from environment variables ** //
if (isset($_ENV['DB_NAME'])) {
  define( 'DB_NAME', $_ENV['DB_NAME'] );
}
if (isset($_ENV['DB_USER'])) {
  define( 'DB_USER', $_ENV['DB_USER'] );
}
if (isset($_ENV['DB_PASSWORD'])) {
  define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );
}
if (isset($_ENV['DB_HOST'])) {
  define( 'DB_HOST', $_ENV['DB_HOST'] );
}

define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication keys and salts.
 */
define( 'AUTH_KEY',         'p0a#ViMGjUMm>Z4AaOkf.c]Ip)n2)w*35ltAaJ,6 s2UnQ+1Kl)*cAv/e|:?H2!@' );
define( 'SECURE_AUTH_KEY',  'K~JiK=9)kKbK5zSn+-DrTdSEcr{k~Dfe8f#)2_E yJ?ebbjI/tlvGLm:Oo0Am;pc' );
define( 'LOGGED_IN_KEY',    'Tr*U|zZq-8b,umn^!~jGrqJG[I[%+_J/59+zy3#^>_(0H+.mF[T{o]NX#2%@I;dY' );
define( 'NONCE_KEY',        'MExNG_+NXGQI7Z%B_A+mLaTK792}|td@oK:Qt^[|@Na|}<~gomA?-mj(H{~85hfr' );
define( 'AUTH_SALT',        '~pcI;vZ ql>y]DHZ/+;5NoL*2&KM2XL0|<]PsK_$H|jPA6+K&{8Q+lDf1WzBzU f' );
define( 'SECURE_AUTH_SALT', 'QCpDjia+Q-ng8od|sgQ8{U.gumh`.C~zZdU<P%_{}KCE]-pU>^rstDHj|,UJSZ^+' );
define( 'LOGGED_IN_SALT',   '|Ze3uUyC7H|A`/W^4`Sw ,hnBg(l42hJ}3;@W[#O?%ji++8;|#:K{H@<==0w=e_Z' );
define( 'NONCE_SALT',       'E=(w7Az6?avAMG3eLk5ub-p<}CubGpYqvY-#LmwY-*}b|_<DW4G20i_VWsC{!$Aq' );
/**#@-*/

$table_prefix = isset($_ENV['TABLE_PREFIX']) ? $_ENV['TABLE_PREFIX'] : 'wp_';

define( 'WP_DEBUG', false );

$_SERVER['HTTPS'] = 'on';

// Dynamic site URL based on request host
$headers = getallheaders();
if (isset($headers['injectHost'])) {
  $_SERVER['HTTP_HOST'] = $headers['injectHost'];
}
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);

// Disable file modification (Vercel is read-only filesystem)
define('DISALLOW_FILE_EDIT', true );
define('DISALLOW_FILE_MODS', true );

// MySQL SSL
if (!isset($_ENV['SKIP_MYSQL_SSL'])) {
  define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
