<?php
// trava.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Se não existir a variável de sessão "logado", chuta o usuário para o login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Verifica se estamos dentro da pasta modulos/ ou na raiz para ajustar o caminho do redirect
    $caminho_login = file_exists('login.php') ? 'login.php' : '../login.php';
    header("Location: $caminho_login");
    exit;
}