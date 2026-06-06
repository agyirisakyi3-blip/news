<?php
/**
 * Database connection debug - DELETE AFTER USE
 */
function dbenv($key, $default = 'NOT SET') {
    $val = getenv($key);
    if ($val !== false && $val !== '') return $val;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

header('Content-Type: text/plain');
echo "Env vars:\n";
echo "DB_NAME = " . dbenv('DB_NAME') . "\n";
echo "DB_USER = " . dbenv('DB_USER') . "\n";
echo "DB_PASS = " . (dbenv('DB_PASSWORD') !== 'NOT SET' ? '***SET***' : 'NOT SET') . "\n";
echo "DB_HOST = " . dbenv('DB_HOST') . "\n";
echo "\nPHP info:\n";
echo "PHP version: " . phpversion() . "\n";
echo "PDO drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'yes' : 'no') . "\n";

// Test connection
echo "\nConnection test:\n";
$host = dbenv('DB_HOST');
$name = dbenv('DB_NAME');
$user = dbenv('DB_USER');
$pass = dbenv('DB_PASSWORD');

if ($host !== 'NOT SET' && $name !== 'NOT SET') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        echo "PDO connection: OK\n";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables: " . count($tables) . " found\n";
        if (count($tables) > 0) {
            echo "First 5: " . implode(', ', array_slice($tables, 0, 5)) . "\n";
            $opt = $pdo->query("SELECT COUNT(*) FROM wp_options")->fetchColumn();
            echo "wp_options count: $opt\n";
        }
    } catch (Exception $e) {
        echo "PDO connection FAILED: " . $e->getMessage() . "\n";
    }

    // mysqli test
    if (extension_loaded('mysqli')) {
        $parts = explode(':', $host);
        $h = $parts[0];
        $p = isset($parts[1]) ? (int)$parts[1] : 3306;

        $mysqli = @new mysqli($h, $user, $pass, $name, $p);
        if ($mysqli->connect_error) {
            echo "MySQLi connection FAILED: " . $mysqli->connect_error . "\n";
        } else {
            echo "MySQLi connection: OK\n";
            $r = $mysqli->query("SHOW TABLES");
            echo "MySQLi tables: " . $r->num_rows . "\n";
            $mysqli->close();
        }
    }
} else {
    echo "Skipping connection test (missing env vars)\n";
}
