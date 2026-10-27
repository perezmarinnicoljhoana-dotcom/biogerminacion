<?php
function getPDO(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = '127.0.0.1';
    $db = 'biogerminacion';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        exit('No fue posible conectar con la base de datos. Importa database.sql desde phpMyAdmin y verifica la configuración de includes/db.php.');
    }
    return $pdo;
}
