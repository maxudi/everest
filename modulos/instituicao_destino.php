<?php
require_once '../trava.php';
require_once '../config.php';
try {
    $stmt = $pdo->query("SELECT * FROM instituicao_destino WHERE id = 1");
    $dados = $stmt->fetch() ?: [];
    
    // Valores padrão da Universidade do Minho caso o banco seja reiniciado
    $defaults = [
        'razao_social' => 'Universidade do Minho',
        'endereco' => 'Largo do Paço, 4704-553',
        'cidade' => 'Braga',
        'pais' => 'Portugal',
        'email_instituicao' => 'gcrerelations@reitoria.uminho.pt'
    ];
    foreach($defaults as $key => $val) { if(empty($dados[$key])) $dados[$key] = $val; }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituição de Destino - FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="max-w-5xl mx-auto px-4 py-8">
        <a href="../index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition inline-flex items-center mb-6">
            ← Voltar ao Painel
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🏛️ Instituição de Destino</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Modifique os dados se necessário. Clique em 📋 ao lado do campo para copiar.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-modulo" class="space-y-6">
                <input type="hidden" name="modulo" value="instituicao_destino_update">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Razão Social</label>
                    <div class="flex gap-2">
                        <input type="text" id="razao_social" name="razao_social" value="<?php echo htmlspecialchars($dados['razao_social']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                        <button type="button" onclick="copiar('razao_social')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Endereço</label>
                    <div class="flex gap-2">
                        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dados['endereco']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                        <button type="button" onclick="copiar('endereco')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cidade</label>
                        <div class="flex gap-2">
                            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($dados['cidade']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('cidade')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">País</label>
                        <div class="flex gap-2">
                            <input type="text" id="pais" name="pais" value="<?php echo htmlspecialchars($dados['pais']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('pais')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">E-mail da instituição</label>
                    <div class="flex gap-2">
                        <input type="text" id="email_instituicao" name="email_instituicao" value="<?php echo htmlspecialchars($dados['email_instituicao']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                        <button type="button" onclick="copiar('email_instituicao')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('form-modulo');
        const statusTxt = document.getElementById('status-salvamento');
        let timeout = null;

        // Copiar instantâneo para Área de Transferência
        function copiar(id) {
            const el = document.getElementById(id);
            if (!el) return;
            navigator.clipboard.writeText(el.value).then(() => {
                statusTxt.innerText = "Copiado! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Salvo automaticamente!";
                    statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                }, 1200);
            });
        }

        // Monitor de digitação para Autosave sem travar
        form.addEventListener('input', () => {
            clearTimeout(timeout);
            statusTxt.innerText = "Digitando...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";
            
            timeout = setTimeout(() => {
                fetch('../salvar.php', { method: 'POST', body: new FormData(form) })
                .then(res => {
                    if(!res.ok) throw new Error("Erro de rede");
                    return res.json();
                })
                .then(data => {
                    if(data.status === 'sucesso') {
                        statusTxt.innerText = "Salvo automaticamente!";
                        statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                    }
                })
                .catch(err => {
                    statusTxt.innerText = "Erro ao salvar";
                    statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";
                    console.error(err);
                });
            }, 800);
        });
    </script>
</body>
</html>