<?php
header('Content-Type: text/plain');
echo "Environment check:\n\n";

echo "getenv() results:\n";
echo "DB_NAME = " . (getenv('DB_NAME') ?: 'NOT SET') . "\n";
echo "DB_USER = " . (getenv('DB_USER') ?: 'NOT SET') . "\n";
echo "DB_PASS = " . (getenv('DB_PASSWORD') ? '***SET***' : 'NOT SET') . "\n";
echo "DB_HOST = " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";

echo "\n\$_ENV:\n";
echo "DB_NAME = " . ($_ENV['DB_NAME'] ?? 'NOT SET') . "\n";
echo "DB_USER = " . ($_ENV['DB_USER'] ?? 'NOT SET') . "\n";

echo "\n\$_SERVER:\n";
echo "DB_NAME = " . ($_SERVER['DB_NAME'] ?? 'NOT SET') . "\n";

echo "\nPHP: " . phpversion() . "\n";
echo "Extensions: " . implode(', ', get_loaded_extensions()) . "\n";
echo "PDO: " . (class_exists('PDO') ? implode(', ', PDO::getAvailableDrivers()) : 'NO') . "\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'yes' : 'no') . "\n";

echo "\nAttempting PDO MySQL connection...\n";
$host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '');
$name = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? '');
$user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? '');
$pass = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');

if ($host && $name) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10,
        ]);
        echo "PDO OK\n";
        echo "Tables: " . $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$name'")->fetchColumn() . "\n";
    } catch (Exception $e) {
        echo "PDO FAILED: " . $e->getMessage() . "\n";
    }
} else {
    echo "Missing DB_HOST or DB_NAME\n";
}
