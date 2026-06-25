<?php
require_once '../config.php';
try {
    // Busca todos os produtos inseridos na grade
    $stmt = $pdo->query("SELECT * FROM produtos_pretendidos ORDER BY id DESC");
    $produtos = $stmt->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

// Array completo mapeado a partir dos seus prints dos selects
$opcoes_produtos = [
    "APRESENTAÇÃO DE TRABALHOS EM CONGRESSOS", "ARTIGOS EM REVISTAS ESPECIALIZADAS", 
    "CAPACITAÇÃO DE PESSOAL", "CAPÍTULOS DE LIVROS", "CULTIVAR PROTEGIDO", 
    "DISSERTAÇÕES DE MESTRADO", "LAUDOS, PERÍCIAS", "LIVROS PUBLICADOS", 
    "MAPAS E SIMILARES", "MAQUETES", "MATERIAIS DIDÁTICO-PEDAGÓGICOS", 
    "MICROFILMES", "MODELAGEM", "OUTROS", "PATENTE COMERCIALIZADA", 
    "PATENTE CONCEDIDA", "PATENTE REQUERIDA", "PITCH", "PLANO DE NEGÓCIO", 
    "PLANOS DIRETORES", "PROCESSOS/METODOLOGIAS/PROCEDIMENTOS", "PRODUÇÃO ARTÍSTICA", 
    "PRODUTOS COMERCIAIS", "PRODUTOS INDUSTRIAIS", "PROJETOS ARQUITETÔNICOS", 
    "PROTÓTIPOS", "PUBLICAÇÕES ELETRÔNICAS INDEXADAS", 
    "PUBLICAÇÕES EM JORNAIS E REVISTAS DE DIVULGAÇÃO CULTURAL", 
    "RELATÓRIOS TÉCNICOS", "RESTAURAÇÃO", "RESUMOS PUBLICADOS", 
    "SOFTWARES", "TESES DE DOUTORADO", "TRABALHOS COMPLETOS EM ANAIS DE CONGRESSOS", 
    "VÍDEO-FILME"
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Pretendidos - FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="max-w-6xl mx-auto px-4 py-8">
        <a href="../index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition inline-flex items-center mb-6">
            ← Voltar ao Painel
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📦 Produtos Pretendidos</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Adicione os produtos à lista abaixo. Use o botão 📋 na tabela para copiar os dados gerados.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-produto" class="space-y-5 bg-gray-50/50 p-6 rounded-2xl border border-gray-200/60 mb-8">
                <input type="hidden" name="modulo" value="produtos_pretendidos_add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Produto Pretendido *</label>
                        <select name="produto" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition shadow-sm" required>
                            <option value="">- - - Escolha um produto pretendido - - -</option>
                            <?php foreach($opcoes_produtos as $opcao): ?>
                                <option value="<?php echo $opcao; ?>"><?php echo $opcao; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Quantidade *</label>
                        <input type="number" name="quantidade" value="1" min="1" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipo *</label>
                        <select name="tipo" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition shadow-sm" required>
                            <option value="">- - - Escolha uma opção - - -</option>
                            <option value="Científico">Científico</option>
                            <option value="Tecnológico">Tecnológico</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Especificação *</label>
                        <input type="text" name="especificacao" placeholder="Ex: Descrição ou detalhes do artigo/evento" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="reset" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition text-sm">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition text-sm shadow-sm flex items-center gap-1.5">
                        <span>+ Adicionar</span>
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto border border-gray-200 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4">Produto</th>
                            <th class="p-4">Especificação</th>
                            <th class="p-4 text-center">Qtde</th>
                            <th class="p-4 text-center">Tipo</th>
                            <th class="p-4 text-center">Ações rápidas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        <?php if(empty($produtos)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 font-medium">Nenhum produto pretendido adicionado ainda.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($produtos as $prod): ?>
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 font-medium text-gray-900"><?php echo htmlspecialchars($prod['produto']); ?></td>
                                    <td class="p-4 text-gray-500"><?php echo htmlspecialchars($prod['especificacao']); ?></td>
                                    <td class="p-4 text-center font-semibold"><?php echo $prod['quantidade']; ?></td>
                                    <td class="p-4 text-center"><span class="px-2.5 py-1 text-xs font-semibold rounded-full <?php echo $prod['tipo'] === 'Científico' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-amber-50 text-amber-700 border border-amber-200'; ?>"><?php echo $prod['tipo']; ?></span></td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="copiarTexto('<?php echo addslashes($prod['produto']); ?>')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 text-xs font-medium transition" title="Copiar Nome do Produto">📋 Prod</button>
                                            <button onclick="copiarTexto('<?php echo addslashes($prod['especificacao']); ?>')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 text-xs font-medium transition" title="Copiar Especificação">📋 Esp</button>
                                            <button onclick="copiarTexto('<?php echo $prod['quantidade']; ?>')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 text-xs font-medium transition" title="Copiar Qtd">📋 Qtd</button>
                                            <button onclick="removerProduto(<?php echo $prod['id']; ?>)" class="p-1 text-red-500 hover:bg-red-50 rounded-lg transition ml-2" title="Remover item">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const form = document.getElementById('form-produto');
        const statusTxt = document.getElementById('status-salvamento');

        // Função Genérica para Copiar conteúdo das células
        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                statusTxt.innerText = "Copiado! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Pronto";
                    statusTxt.className = "text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full";
                }, 1000);
            });
        }

        // Submissão do Formulário e inserção no banco
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            statusTxt.innerText = "Adicionando...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";

            fetch('../salvar.php', { method: 'POST', body: new FormData(form) })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sucesso') {
                    window.location.reload(); // Recarrega a página para atualizar a tabela de forma simples
                }
            });
        });

        // Remoção assíncrona do produto da grade
        function removerProduto(id) {
            if(!confirm("Deseja remover este produto da lista?")) return;
            
            const fd = new FormData();
            fd.append('modulo', 'produtos_pretendidos_del');
            fd.append('id', id);

            fetch('../salvar.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sucesso') {
                    window.location.reload();
                }
            });
        }
    </script>
</body>
</html>