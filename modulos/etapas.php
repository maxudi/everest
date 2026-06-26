<?php
require_once '../trava.php';
require_once '../config.php';
try {
    // Busca as metas para preencher o select
    $stmtMetas = $pdo->query("SELECT id, descricao FROM metas ORDER BY id ASC");
    $metas = $stmtMetas->fetchAll() ?: [];

    // Busca as etapas cadastradas fazendo JOIN simples para mostrar o texto da meta correspondente
    $stmtEtapas = $pdo->query("
        SELECT e.*, m.descricao as meta_texto 
        FROM etapas e 
        LEFT JOIN metas m ON e.meta_id = m.id 
        ORDER BY e.id ASC
    ");
    $etapas = $stmtEtapas->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etapas - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">📅 Cronograma de Etapas (Espelho Livre)</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Planeje as atividades, marcos, prazos e responsabilidades.</p>
                </div>
                <span id="status-sistema" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-xs text-blue-900 space-y-1">
                <p><strong>Etapas:</strong> Grupo de atividades que culminam com a conclusão de um resultado principal.</p>
                <p><strong>Indicador de Progresso (Marco):</strong> Marcos que indicam etapas cumpridas (não precisam ser quantificáveis).</p>
                <p><strong>Peso:</strong> 1-Baixo e 2-Crítico.</p>
            </div>

            <form id="form-etapas" class="space-y-5 mb-10">
                <input type="hidden" name="modulo" value="etapas_add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Relacionada *</label>
                        <select name="meta_id" required class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <option value="">-- Escolha uma meta --</option>
                            <?php foreach($metas as $m): ?>
                                <option value="<?php echo $m['id']; ?>">ID <?php echo $m['id']; ?> - <?php echo htmlspecialchars(mb_strimwidth($m['descricao'], 0, 80, "...")); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição da etapa (até 200 car.) *</label>
                        <input type="text" name="descricao_etapa" maxlength="200" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Indicador de Progresso / Marco (até 100 car.) *</label>
                        <input type="text" name="indicador_progresso" maxlength="100" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Entregável(is) <span class="text-xs font-normal text-gray-400">(Digite os itens livres ou esperados)</span></label>
                        <input type="text" name="entregaveis" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition" placeholder="Ex: Relatório Técnico, Artigo publicado">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mês de Início *</label>
                        <input type="number" name="mes_inicio" min="1" max="48" value="1" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mês de Fim *</label>
                        <input type="number" name="mes_fim" min="1" max="48" value="1" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Peso *</label>
                        <select name="peso" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <option value="1">1 - Baixo</option>
                            <option value="2">2 - Crítico</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Responsável *</label>
                        <input type="text" name="responsavel" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Executor(es) *</label>
                        <input type="text" name="executores" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition" placeholder="Nomes dos membros da equipe">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition">
                        💾 Inserir Etapa
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-xs text-left text-gray-500 whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Meta ID</th>
                            <th class="px-4 py-3">Etapa</th>
                            <th class="px-4 py-3">Marco (Indicador)</th>
                            <th class="px-4 py-3">Entregáveis</th>
                            <th class="px-4 py-3 text-center">Meses</th>
                            <th class="px-4 py-3 text-center">Peso</th>
                            <th class="px-4 py-3">Resp. / Executores</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($etapas)): ?>
                        <tr class="bg-white text-center">
                            <td colspan="8" class="px-4 py-8 text-gray-400 italic text-sm">Nenhuma etapa cadastrada.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($etapas as $etapa): ?>
                            <tr class="bg-white border-b border-gray-100 last:border-0 hover:bg-gray-50/80 transition text-gray-700">
                                <td class="px-4 py-3 font-semibold text-blue-900">
                                    <div class="flex items-center gap-1">
                                        <span>#<?php echo $etapa['meta_id']; ?></span>
                                        <button type="button" onclick="copiarTexto('<?php echo $etapa['meta_id']; ?>')" class="text-[10px] bg-gray-100 p-1 rounded hover:bg-blue-100">📋</button>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1 max-w-[180px] overflow-hidden text-ellipsis">
                                        <span id="etapa-desc-<?php echo $etapa['id']; ?>"><?php echo htmlspecialchars($etapa['descricao_etapa']); ?></span>
                                        <button type="button" onclick="copiarTexto(document.getElementById('etapa-desc-<?php echo $etapa['id']; ?>').innerText)" class="text-[10px] bg-gray-100 p-1 rounded hover:bg-blue-100">📋</button>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1 max-w-[150px] overflow-hidden text-ellipsis">
                                        <span id="etapa-marco-<?php echo $etapa['id']; ?>"><?php echo htmlspecialchars($etapa['indicador_progresso']); ?></span>
                                        <button type="button" onclick="copiarTexto(document.getElementById('etapa-marco-<?php echo $etapa['id']; ?>').innerText)" class="text-[10px] bg-gray-100 p-1 rounded hover:bg-blue-100">📋</button>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <span id="etapa-entreg-<?php echo $etapa['id']; ?>"><?php echo htmlspecialchars($etapa['entregaveis'] ?: '-'); ?></span>
                                        <?php if($etapa['entregaveis']): ?><button type="button" onclick="copiarTexto(document.getElementById('etapa-entreg-<?php echo $etapa['id']; ?>').innerText)" class="text-[10px] bg-gray-100 p-1 rounded hover:bg-blue-100">📋</button><?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-mono">
                                    M<?php echo $etapa['mes_inicio']; ?> até M<?php echo $etapa['mes_fim']; ?>
                                    <button type="button" onclick="copiarTexto('<?php echo $etapa['mes_inicio']; ?>')" class="text-[9px] bg-gray-100 px-1 py-0.5 rounded hover:bg-blue-100" title="Copiar Início">I</button>
                                    <button type="button" onclick="copiarTexto('<?php echo $etapa['mes_fim']; ?>')" class="text-[9px] bg-gray-100 px-1 py-0.5 rounded hover:bg-blue-100" title="Copiar Fim">F</button>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-600"><?php echo $etapa['peso']; ?></td>
                                <td class="px-4 py-3">
                                    <div class="text-[11px]">
                                        <div><strong>R:</strong> <span id="r-<?php echo $etapa['id']; ?>"><?php echo htmlspecialchars($etapa['responsavel']); ?></span> <button type="button" onclick="copiarTexto(document.getElementById('r-<?php echo $etapa['id']; ?>').innerText)" class="text-[9px] bg-gray-100 px-0.5 rounded hover:bg-blue-100">📋</button></div>
                                        <div><strong>E:</strong> <span id="e-<?php echo $etapa['id']; ?>"><?php echo htmlspecialchars($etapa['executores']); ?></span> <button type="button" onclick="copiarTexto(document.getElementById('e-<?php echo $etapa['id']; ?>').innerText)" class="text-[9px] bg-gray-100 px-0.5 rounded hover:bg-blue-100">📋</button></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="removerEtapa(<?php echo $etapa['id']; ?>)" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Remover">🗑️</button>
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
        const form = document.getElementById('form-etapas');
        const statusTxt = document.getElementById('status-sistema');

        // Atalho unificado de cópia rápida para o clipboard
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

        // Adicionar Etapa via AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            statusTxt.innerText = "Inserindo...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";

            fetch('../salvar.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sucesso') {
                    location.reload();
                }
            });
        });

        // Remover Etapa cadastrada
        function removerEtapa(idEtapa) {
            if(!confirm("Deseja remover esta etapa do cronograma?")) return;

            statusTxt.innerText = "Removendo...";
            statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";

            const formData = new FormData();
            formData.append('modulo', 'etapas_del');
            formData.append('id', idEtapa);

            fetch('../salvar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sucesso') {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>