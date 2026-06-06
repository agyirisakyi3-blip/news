<?php
/**
 * ServerlessWP configuration for NewsBlog
 */

// Helper: get env var from $_ENV or getenv()
function env($key, $default = null) {
    $val = getenv($key);
    if ($val !== false && $val !== '') return $val;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

// ** Database settings - from environment variables ** //
$db_name = env('DB_NAME');
if ($db_name) define('DB_NAME', $db_name);

$db_user = env('DB_USER');
if ($db_user) define('DB_USER', $db_user);

$db_pass = env('DB_PASSWORD');
if ($db_pass) define('DB_PASSWORD', $db_pass);

$db_host = env('DB_HOST');
if ($db_host) define('DB_HOST', $db_host);

// Debug: write env status
$dblog = '/tmp/wpdbg_' . md5(__FILE__) . '.log';
$d = date('Y-m-d H:i:s');
$envs = "DB_NAME=" . env('DB_NAME', 'NOT SET') . " DB_USER=" . env('DB_USER', 'NOT SET') . " DB_HOST=" . env('DB_HOST', 'NOT SET');
$defined = "DEFINED: DB_NAME=" . (defined('DB_NAME') ? DB_NAME : 'NO') . " DB_USER=" . (defined('DB_USER') ? DB_USER : 'NO');
@file_put_contents($dblog, "[$d] $envs | $defined\n", FILE_APPEND);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

/**#@+
 * Authentication keys and salts.
 */
define('AUTH_KEY', 'p0a#ViMGjUMm>Z4AaOkf.c]Ip)n2)w*35ltAaJ,6 s2UnQ+1Kl)*cAv/e|:?H2!@');
define('SECURE_AUTH_KEY', 'K~JiK=9)kKbK5zSn+-DrTdSEcr{k~Dfe8f#)2_E yJ?ebbjI/tlvGLm:Oo0Am;pc');
define('LOGGED_IN_KEY', 'Tr*U|zZq-8b,umn^!~jGrqJG[I[%+_J/59+zy3#^>_(0H+.mF[T{o]NX#2%@I;dY');
define('NONCE_KEY', 'MExNG_+NXGQI7Z%B_A+mLaTK792}|td@oK:Qt^[|@Na|}<~gomA?-mj(H{~85hfr');
define('AUTH_SALT', '~pcI;vZ ql>y]DHZ/+;5NoL*2&KM2XL0|<]PsK_$H|jPA6+K&{8Q+lDf1WzBzU f');
define('SECURE_AUTH_SALT', 'QCpDjia+Q-ng8od|sgQ8{U.gumh`.C~zZdU<P%_{}KCE]-pU>^rstDHj|,UJSZ^+');
define('LOGGED_IN_SALT', '|Ze3uUyC7H|A`/W^4`Sw ,hnBg(l42hJ3}@W[#O?%ji++8;|#:K{H@<==0w=e_Z');
define('NONCE_SALT', 'E=(w7Az6?avAMG3eLk5ub-p<}CubGpYqvY-#LmwY-*}b|_<DW4G20i_VWsC{!$Aq');
/**#@-*/

$table_prefix = env('TABLE_PREFIX', 'wp_');

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

$_SERVER['HTTPS'] = 'on';

// Dynamic site URL based on request host
$headers = getallheaders();
if (isset($headers['injectHost'])) {
  $_SERVER['HTTP_HOST'] = $headers['injectHost'];
}
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);

// Disable file modification (Vercel is read-only filesystem)
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);

// MySQL SSL (Railway requires SSL)
if (!env('SKIP_MYSQL_SSL')) {
  define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
