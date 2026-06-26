<?php
require_once '../trava.php';
require_once '../config.php';
try {
    // Busca todos os membros inseridos na grade
    $stmt = $pdo->query("SELECT * FROM membros_equipe ORDER BY id DESC");
    $membros = $stmt->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membros da Equipe - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">👥 Membros da Equipe</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Gerencie os integrantes da equipe. Use o botão 📋 para copiar individualmente cada campo.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-membro" class="space-y-5 bg-gray-50/50 p-6 rounded-2xl border border-gray-200/60 mb-8">
                <input type="hidden" name="modulo" value="membros_equipe_add">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nome Completo *</label>
                        <input type="text" name="nome" placeholder="Nome do integrante" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link do Currículo Lattes *</label>
                        <input type="url" name="lattes" placeholder="http://lattes.cnpq.br/..." class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition shadow-sm" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="reset" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition text-sm">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition text-sm shadow-sm flex items-center gap-1.5">
                        <span>+ Adicionar Integrante</span>
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto border border-gray-200 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4 w-1/3">Nome</th>
                            <th class="p-4 w-1/2">Currículo Lattes</th>
                            <th class="p-4 text-center">Ações rápidas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        <?php if(empty($membros)): ?>
                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-400 font-medium">Nenhum membro adicionado à equipe ainda.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($membros as $memb): ?>
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 font-medium text-gray-900"><?php echo htmlspecialchars($memb['nome']); ?></td>
                                    <td class="p-4 text-blue-600 break-all">
                                        <a href="<?php echo htmlspecialchars($memb['lattes']); ?>" target="_blank" class="hover:underline">
                                            <?php echo htmlspecialchars($memb['lattes']); ?>
                                        </a>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="copiarTexto('<?php echo addslashes($memb['nome']); ?>')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 text-xs font-medium transition" title="Copiar Nome">📋 Nome</button>
                                            <button onclick="copiarTexto('<?php echo addslashes($memb['lattes']); ?>')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 text-xs font-medium transition" title="Copiar Lattes">📋 Lattes</button>
                                            <button onclick="removerMembro(<?php echo $memb['id']; ?>)" class="p-1 text-red-500 hover:bg-red-50 rounded-lg transition ml-2" title="Remover Integrante">🗑️</button>
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
        const form = document.getElementById('form-membro');
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
                    window.location.reload();
                }
            });
        });

        // Remoção assíncrona do integrante
        function removerMembro(id) {
            if(!confirm("Deseja remover este integrante da equipe?")) return;
            
            const fd = new FormData();
            fd.append('modulo', 'membros_equipe_del');
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