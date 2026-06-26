<?php
require_once '../config.php';
try {
    $stmt = $pdo->query("SELECT * FROM instituicoes WHERE id = 1");
    $dados = $stmt->fetch() ?: [];
    
    $defaults = [
        'executora_proponente' => 'PMMG - POLICIA MILITAR DO ESTADO DE MINAS GERAIS',
        'instituicao_gestora' => 'NPG - Este processo não possui gestora'
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
    <title>Instituições - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">🏛️ Instituições</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Altere livremente. Clique em 📋 para copiar e levar para o EVEREST.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <div class="bg-amber-50 border border-amber-200/70 rounded-xl p-5 mb-6 text-sm text-amber-900/90 space-y-2 leading-relaxed">
                <p class="font-bold flex items-center gap-1.5 text-amber-900">ℹ️ Orientações:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Para seleção da Instituição Executora, o proponente deverá indicar a instituição à qual está vinculado, de acordo com as informações registradas em seu perfil de usuário no sistema Everest.</li>
                    <li>A seleção da Instituição Gestora (Fundação de Apoio) é permitida apenas para Instituições Executoras enquadradas como ICTMG.</li>
                    <li>Nos casos em que não houver indicação de uma Instituição Gestora, deve-se selecionar a opção "NPG - Este processo não possui gestora".</li>
                </ul>
            </div>

            <form id="form-modulo" class="space-y-6">
                <input type="hidden" name="modulo" value="instituicoes_update">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Instituição Executora / Proponente</label>
                    <div class="flex gap-2">
                        <input type="text" id="executora_proponente" name="executora_proponente" value="<?php echo htmlspecialchars($dados['executora_proponente']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm">
                        <button type="button" onclick="copiar('executora_proponente')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition" title="Copiar">📋</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Instituição Gestora</label>
                    <div class="flex gap-2">
                        <input type="text" id="instituicao_gestora" name="instituicao_gestora" value="<?php echo htmlspecialchars($dados['instituicao_gestora']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm">
                        <button type="button" onclick="copiar('instituicao_gestora')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition" title="Copiar">📋</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('form-modulo');
        const statusTxt = document.getElementById('status-salvamento');
        let timeout = null;

        // Função de Cópia Direta para a Área de Transferência
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

        // Autosave instantâneo por digitação
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