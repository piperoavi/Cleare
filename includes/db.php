<?php

define('DB_HOST',    'localhost');
define('DB_NAME',    'Cleare');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST
     . ";dbname="    . DB_NAME
     . ";charset="   . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {

    error_log($e->getMessage());

    if (
        isset($_SERVER['HTTP_ACCEPT']) &&
        str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')
    ) {
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Database connection failed. Please try again later.']));
    }

    die('Database connection failed. Please try again later.');
}