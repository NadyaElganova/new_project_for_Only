<?php

$config = require __DIR__ . '/config.php';
$db = $config['database'];

$dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";

try {
    $pdo = new PDO($dsn, $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
