<?php
// login.php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Lê a lista completa de usuários do ambiente (.env)
// Se não houver nada configurado no Easypanel, ele usa o padrão local abaixo:
$env_users = getenv('APP_USERS') ?: 'max:12345678,admin:12345678,grazy:12345678';

// 2. Processa a string do ambiente e transforma em um Array estruturado
$usuarios_permitidos = [];
$pares = explode(',', $env_users);

foreach ($pares as $par) {
    if (strpos($par, ':') !== false) {
        list($user, $pass) = explode(':', $par, 2);
        $usuarios_permitidos[trim($user)] = trim($pass);
    }
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_submetido = trim($_POST['usuario'] ?? '');
    $senha_submetida   = $_POST['senha'] ?? '';

    // 3. Valida as credenciais contra o mapa gerado pelo ambiente
    if (array_key_exists($usuario_submetido, $usuarios_permitidos) && $usuarios_permitidos[$usuario_submetido] === $senha_submetida) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario_submetido;
        header('Location: index.php');
        exit;
    } else {
        $erro = 'Usuário ou senha inválidos.';
    }
}
?>