<?php
// config.php
/*
$host = 'painel.netminas.com';
$port = '3306';
$dbname = 'geral';
$username = 'max';
$password = '148ciapm';

try {
    // Cria a conexão usando PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configura o retorno padrão como array associativo (facilita ler os dados)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Se der erro na conexão, mata a execução e mostra o erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
    */


// config.php (Versão Local)

$host = '127.0.0.1'; // ou 'localhost'
$port = '3306';
$dbname = 'geral';   // certifique-se de criar este banco localmente
$username = 'root';  // usuário padrão local
$password = '';      // a maioria das ferramentas locais vem com a senha em branco

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados local: " . $e->getMessage());
}
?>