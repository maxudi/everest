<?php
// config.php

// Tenta ler do ambiente (Easypanel), se não encontrar, usa o padrão local
$host     = getenv('DB_HOST')     ?: '127.0.0.1';
$port     = getenv('DB_PORT')     ?: '3306';
$dbname   = getenv('DB_NAME')     ?: 'geral';
$username = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em produção, evite expor o $e->getMessage() diretamente por segurança,
    // mas para o seu espelho offline, o log direto ajuda no diagnóstico rápido.
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>