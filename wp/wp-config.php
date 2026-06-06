<?php
/**
 * ServerlessWP configuration for NewsBlog
 */

// Helper: get env var from getenv() or $_ENV
function env($key, $default = null) {
    $v = getenv($key);
    if ($v !== false && $v !== '') return trim($v);
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return trim($_ENV[$key]);
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return trim($_SERVER[$key]);
    return $default;
}

// Database
define('DB_NAME', env('DB_NAME', 'railway'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', 'eHDoHfZyqIrFGxGNkCrEUVuvnTHpXkjX'));
define('DB_HOST', env('DB_HOST', 'acela.proxy.rlwy.net:46798'));
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

$table_prefix = env('TABLE_PREFIX', 'wp_');

// Keys & Salts
define('AUTH_KEY', 'p0a#ViMGjUMm>Z4AaOkf.c]Ip)n2)w*35ltAaJ,6 s2UnQ+1Kl)*cAv/e|:?H2!@');
define('SECURE_AUTH_KEY', 'K~JiK=9)kKbK5zSn+-DrTdSEcr{k~Dfe8f#)2_E yJ?ebbjI/tlvGLm:Oo0Am;pc');
define('LOGGED_IN_KEY', 'Tr*U|zZq-8b,umn^!~jGrqJG[I[%+_J/59+zy3#^>_(0H+.mF[T{o]NX#2%@I;dY');
define('NONCE_KEY', 'MExNG_+NXGQI7Z%B_A+mLaTK792}|td@oK:Qt^[|@Na|}<~gomA?-mj(H{~85hfr');
define('AUTH_SALT', '~pcI;vZ ql>y]DHZ/+;5NoL*2&KM2XL0|<]PsK_$H|jPA6+K&{8Q+lDf1WzBzU f');
define('SECURE_AUTH_SALT', 'QCpDjia+Q-ng8od|sgQ8{U.gumh`.C~zZdU<P%_{}KCE]-pU>^rstDHj|,UJSZ^+');
define('LOGGED_IN_SALT', '|Ze3uUyC7H|A`/W^4`Sw ,hnBg(l42hJ3}@W[#O?%ji++8;|#:K{H@<==0w=e_Z');
define('NONCE_SALT', 'E=(w7Az6?avAMG3eLk5ub-p<}CubGpYqvY-#LmwY-*}b|_<DW4G20i_VWsC{!$Aq');

define('WP_DEBUG', false);

// Dynamic site URL
$_SERVER['HTTPS'] = 'on';
define('WP_SITEURL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'newsblog-taupe-two.vercel.app'));
define('WP_HOME', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'newsblog-taupe-two.vercel.app'));

// Security
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);

// ABSPATH
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
