<?php
// logout.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
$_SESSION = [];
session_destroy();

// Chuta o usuário de volta para a tela de login
header("Location: login.php");
exit;