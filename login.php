<?php
// login.php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Lê a lista completa de usuários do ambiente (.env)
$env_users = getenv('APP_USERS') ?: 'max:12345678,admin:12345678,grazy:12345678';
// ADICIONE TEMPORARIAMENTE PARA TESTAR:
 echo "<pre>Variável lida: "; var_dump($env_users); echo "</pre>";

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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Espelho FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans antialiased">

    <div class="w-full max-w-sm p-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-bold text-slate-900">🔒 Acesso Restrito</h1>
                <p class="text-xs text-slate-500">Faça login para gerenciar seus rascunhos.</p>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl p-3 font-medium">
                    ⚠️ <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Usuário</label>
                    <input type="text" name="usuario" required autocomplete="off" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Senha</label>
                    <input type="password" name="senha" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition">
                    Entrar no Painel
                </button>
            </form>
        </div>
    </div>

</body>
</html>