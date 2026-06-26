<?php
require_once '../trava.php';
require_once '../config.php';
try {
    $stmt = $pdo->query("SELECT * FROM natureza_proposta WHERE id = 1");
    $dados = $stmt->fetch() ?: [];
    
    $defaults = [
        'categoria_financiamento' => 'Linha B - Bolsa de Doutorado Sanduíche',
        'avaliacao_capes' => 'Não se aplica'
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
    <title>Natureza da Proposta - FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="max-w-5xl mx-auto px-4 py-8">
        <a href="../index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition inline-flex items-center mb-6">
            ← Voltar ao Painel
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📄 Natureza da Proposta</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Suas opções já estão salvas. Clique em 📋 para copiar a opção selecionada.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-modulo" class="space-y-8">
                <input type="hidden" name="modulo" value="natureza_proposta_update">

                <div class="bg-gray-50/60 border border-gray-200/60 p-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200/50">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Categoria de Financiamento</label>
                        <button type="button" onclick="copiarRadio('categoria_financiamento')" class="p-2 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition text-sm flex items-center gap-1.5 font-medium" title="Copiar opção marcada">
                            <span>Copiar Selecionado</span> 📋
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="categoria_financiamento" value="Linha A - Formação em cursos de pós-graduação" <?php echo $dados['categoria_financiamento'] === 'Linha A - Formação em cursos de pós-graduação' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Linha A - Formação em cursos de pós-graduação</span>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="categoria_financiamento" value="Linha B - Bolsa de Doutorado Sanduíche" <?php echo $dados['categoria_financiamento'] === 'Linha B - Bolsa de Doutorado Sanduíche' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Linha B - Bolsa de Doutorado Sanduíche</span>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="categoria_financiamento" value="Linha C - Bolsa de Estágio Pós-Doutoral no exterior" <?php echo $dados['categoria_financiamento'] === 'Linha C - Bolsa de Estágio Pós-Doutoral no exterior' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Linha C - Bolsa de Estágio Pós-Doutoral no exterior</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50/60 border border-gray-200/60 p-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200/50">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider max-w-xl leading-snug">
                            No caso da Linha A, o(a) candidato(a) está cursando ou cursará mestrado/doutorado em programa avaliado, no mínimo, com conceito 3 pela CAPES?
                        </label>
                        <button type="button" onclick="copiarRadio('avaliacao_capes')" class="p-2 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition text-sm flex items-center gap-1.5 font-medium shrink-0" title="Copiar opção marcada">
                            <span>Copiar Selecionado</span> 📋
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="avaliacao_capes" value="Sim" <?php echo $dados['avaliacao_capes'] === 'Sim' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Sim</span>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="avaliacao_capes" value="Não" <?php echo $dados['avaliacao_capes'] === 'Não' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Não</span>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/30 transition">
                            <input type="radio" name="avaliacao_capes" value="Não se aplica" <?php echo $dados['avaliacao_capes'] === 'Não se aplica' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Não se aplica</span>
                        </label>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('form-modulo');
        const statusTxt = document.getElementById('status-salvamento');

        // Função para copiar o valor do Radio button selecionado
        function copiarRadio(nomeGrupo) {
            const selecionado = document.querySelector(`input[name="${nomeGrupo}"]:checked`);
            if (!selecionado) return;
            
            navigator.clipboard.writeText(selecionado.value).then(() => {
                statusTxt.innerText = "Copiado! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Salvo automaticamente!";
                    statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                }, 1200);
            });
        }

        // Autosave engatado no evento 'change' (clique nos radio buttons)
        form.addEventListener('change', () => {
            statusTxt.innerText = "Salvando...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";
            
            fetch('../salvar.php', { method: 'POST', body: new FormData(form) })
            .then(() => {
                statusTxt.innerText = "Salvo automaticamente!";
                statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
            });
        });
    </script>
</body>
</html>