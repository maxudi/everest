<?php
require_once '../trava.php';
require_once '../config.php';
try {
    // Busca todas as metas salvas para listar na tabela
    $stmt = $pdo->query("SELECT * FROM metas ORDER BY id ASC");
    $metas = $stmt->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metas - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">🎯 Metas (Espelho Livre)</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Adicione as metas quantificáveis do seu projeto.</p>
                </div>
                <span id="status-sistema" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-900">
                <strong class="block mb-1 text-blue-900 font-semibold">Definição:</strong>
                Meta é sempre algo quantificável a ser alcançado em determinado prazo (Ex: publicar N artigos, coletar N amostras, etc).
            </div>

            <form id="form-metas" class="space-y-4 mb-8">
                <input type="hidden" name="modulo" value="metas_add">
                
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Descrição da meta (até 200 caracteres) *</label>
                        <span id="char-counter" class="text-xs text-gray-400">200 restantes</span>
                    </div>
                    <textarea id="descricao" name="descricao" rows="3" maxlength="200" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition" placeholder="Ex: Publicar 2 artigos em periódicos indexados na área de Ciência da Computação."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition">
                        💾 Inserir Meta
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">ID</th>
                            <th scope="col" class="px-6 py-3">Meta</th>
                            <th scope="col" class="px-6 py-3 w-28 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($metas)): ?>
                        <tr class="bg-white border-b border-gray-100 last:border-0 text-center">
                            <td colspan="3" class="px-6 py-8 text-gray-400 italic">Nenhuma meta encontrada.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($metas as $meta): ?>
                            <tr class="bg-white border-b border-gray-100 last:border-0 hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-medium text-gray-900"><?php echo $meta['id']; ?></td>
                                <td class="px-6 py-4 text-gray-700 font-sans" id="texto-meta-<?php echo $meta['id']; ?>"><?php echo htmlspecialchars($meta['descricao']); ?></td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button type="button" onclick="copiarMeta('texto-meta-<?php echo $meta['id']; ?>')" class="p-2 bg-gray-100 hover:bg-blue-100 rounded-lg transition" title="Copiar Meta">📋</button>
                                    <button type="button" onclick="removerMeta(<?php echo $meta['id']; ?>)" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Remover">🗑️</button>
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
        const form = document.getElementById('form-metas');
        const desc = document.getElementById('descricao');
        const counter = document.getElementById('char-counter');
        const statusTxt = document.getElementById('status-sistema');

        // Contador regressivo de caracteres
        desc.addEventListener('input', () => {
            counter.innerText = `${200 - desc.value.length} restantes`;
        });

        // Copiar texto da meta selecionada
        function copiarMeta(idElemento) {
            const txt = document.getElementById(idElemento).innerText;
            navigator.clipboard.writeText(txt).then(() => {
                statusTxt.innerText = "Copiado com sucesso! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Pronto";
                    statusTxt.className = "text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full";
                }, 1200);
            });
        }

        // Submissão do Formulário (Adicionar)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if(!desc.value.trim()) return;

            statusTxt.innerText = "Salvando...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";

            fetch('../salvar.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sucesso') {
                    location.reload(); 
                } else {
                    alert("Erro interno: " + data.mensagem);
                }
            })
            .catch(err => {
                statusTxt.innerText = "Erro ao conectar";
                statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";
                console.error(err);
            });
        });

        // Remover Meta cadastrada
        function removerMeta(idMeta) {
            if(!confirm("Deseja realmente remover esta meta?")) return;

            statusTxt.innerText = "Removendo...";
            statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";

            const formData = new FormData();
            formData.append('modulo', 'metas_del');
            formData.append('id', idMeta);

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
    // =========================================================================
    // MODULO: DISPENDIOS (Orçamento)
    // =========================================================================
    if ($modulo === 'dispendios_add') {
        $sql = "INSERT INTO dispendios (dispendio, classificacao, etapas_vinculadas, origem_recurso, importado_exterior, quantidade, valor_unitario, num_meses, descricao, justificativa) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['dispendio'] ?? '',
                $_POST['classificacao'] ?? 'Custeio',
                $_POST['etapas_vinculadas'] ?? '',
                $_POST['origem_recurso'] ?? 'Concedente',
                isset($_POST['importado_exterior']) ? 1 : 0,
                intval($_POST['quantidade'] ?? 1),
                floatval(str_replace(',', '.', $_POST['valor_unitario'] ?? '0')),
                !empty($_POST['num_meses']) ? intval($_POST['num_meses']) : null,
                $_POST['descricao'] ?? '',
                $_POST['justificativa'] ?? ''
            ]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao inserir dispêndio: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($modulo === 'dispendios_del') {
        $id = intval($_POST['id'] ?? 0);
        $sql = "DELETE FROM dispendios WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao deletar dispêndio: ' . $e->getMessage()]);
        }
        exit;
    }
    </script>
</body>
</html>