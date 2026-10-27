<?php
$host = 'localhost';
$db = 'biogerminacion';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
try { $pdo = new PDO($dsn, $user, $pass, $options); }
catch (PDOException $e) { exit('Error de conexión. Verifica que MySQL esté iniciado y que hayas importado database.sql.'); }
if (session_status() === PHP_SESSION_NONE) session_start();
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function project_base_url() { return rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/'))), '/'); }
function require_login() { if (empty($_SESSION['user'])) { header('Location: ' . project_base_url() . '/auth/login.php'); exit; } }
function require_role($role) { require_login(); if ($_SESSION['user']['role'] !== $role) { header('Location: ' . project_base_url() . '/index.php'); exit; } }
?>