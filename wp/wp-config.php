<?php
/**
 * ServerlessWP configuration for NewsBlog
 */

// Hardcoded database credentials for diagnostic
define('DB_NAME', 'railway');
define('DB_USER', 'root');
define('DB_PASSWORD', 'eHDoHfZyqIrFGxGNkCrEUVuvnTHpXkjX');
define('DB_HOST', 'acela.proxy.rlwy.net:46798');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('AUTH_KEY', 'p0a#ViMGjUMm>Z4AaOkf.c]Ip)n2)w*35ltAaJ,6 s2UnQ+1Kl)*cAv/e|:?H2!@');
define('SECURE_AUTH_KEY', 'K~JiK=9)kKbK5zSn+-DrTdSEcr{k~Dfe8f#)2_E yJ?ebbjI/tlvGLm:Oo0Am;pc');
define('LOGGED_IN_KEY', 'Tr*U|zZq-8b,umn^!~jGrqJG[I[%+_J/59+zy3#^>_(0H+.mF[T{o]NX#2%@I;dY');
define('NONCE_KEY', 'MExNG_+NXGQI7Z%B_A+mLaTK792}|td@oK:Qt^[|@Na|}<~gomA?-mj(H{~85hfr');
define('AUTH_SALT', '~pcI;vZ ql>y]DHZ/+;5NoL*2&KM2XL0|<]PsK_$H|jPA6+K&{8Q+lDf1WzBzU f');
define('SECURE_AUTH_SALT', 'QCpDjia+Q-ng8od|sgQ8{U.gumh`.C~zZdU<P%_{}KCE]-pU>^rstDHj|,UJSZ^+');
define('LOGGED_IN_SALT', '|Ze3uUyC7H|A`/W^4`Sw ,hnBg(l42hJ3}@W[#O?%ji++8;|#:K{H@<==0w=e_Z');
define('NONCE_SALT', 'E=(w7Az6?avAMG3eLk5ub-p<}CubGpYqvY-#LmwY-*}b|_<DW4G20i_VWsC{!$Aq');

$table_prefix = 'wp_';

// Diagnostic output
header('Content-Type: text/plain');
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Extensions: " . implode(', ', get_loaded_extensions()) . "\n\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n";
echo "PDO: " . (class_exists('PDO') ? implode(', ', PDO::getAvailableDrivers()) : 'NO') . "\n\n";

echo "getenv DB_NAME: " . var_export(getenv('DB_NAME'), true) . "\n";
echo "getenv DB_USER: " . var_export(getenv('DB_USER'), true) . "\n";
echo "getenv DB_HOST: " . var_export(getenv('DB_HOST'), true) . "\n";
echo "getenv DATABASE: " . var_export(getenv('DATABASE'), true) . "\n";
echo "_ENV DB_NAME: " . var_export($_ENV['DB_NAME'] ?? 'NOT SET', true) . "\n";

echo "\nTesting MySQL connection...\n";
$host = DB_HOST;
$port = 3306;
if (strpos($host, ':') !== false) {
    list($host, $port) = explode(':', $host, 2);
}
echo "Connecting to $host:$port as " . DB_USER . "\n";

$mysqli = @new mysqli($host, DB_USER, DB_PASSWORD, DB_NAME, (int)$port);
if ($mysqli->connect_error) {
    echo "FAILED: " . $mysqli->connect_error . "\n";
} else {
    echo "CONNECTED OK\n";
    $result = $mysqli->query("SHOW TABLES");
    echo "Tables: " . $result->num_rows . "\n";
    while ($row = $result->fetch_array()) {
        echo "  - " . $row[0] . "\n";
    }
    $result2 = $mysqli->query("SELECT COUNT(*) AS c FROM wp_options");
    $row2 = $result2->fetch_assoc();
    echo "\nwp_options rows: " . $row2['c'] . "\n";
    $mysqli->close();
}

echo "\nDone.";
