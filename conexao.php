<?php
$host = 'localhost';
$db = 'deposito';
$user = 'root';
$pass = 'root';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
 
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>