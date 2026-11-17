<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

// Central PDO connection for the EcoSprout application.
$host = getenv('ECOSPROUT_DB_HOST') ?: 'localhost';
$dbName = getenv('ECOSPROUT_DB_NAME') ?: 'ecosprout';
$username = getenv('ECOSPROUT_DB_USER') ?: 'root';
$password = getenv('ECOSPROUT_DB_PASS') !== false ? getenv('ECOSPROUT_DB_PASS') : '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $exception) {
    http_response_code(500);
    exit('Database connection failed. Please import database/ecosprout.sql and check config/db.php.');
}
