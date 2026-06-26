<?php
require_once '../trava.php';
require_once '../config.php';
try {
    // Carrega a listagem fixa de documentos guardada no banco
    $stmt = $pdo->query("SELECT * FROM documentos ORDER BY id ASC");
    $documentos = $stmt->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Eletrônicos - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">📂 Documentos Eletrônicos (Espelho)</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Gerencie os arquivos locais para anexar posteriormente no EVEREST.</p>
                </div>
                <span id="status-sistema" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-900">
                    <strong>AVISO!</strong> Só é possível o envio de um arquivo por vez.<br>
                    <strong>Extensões permitidas:</strong> pdf, doc, docx, xls, xlsx<br>
                    <strong>Tamanho máximo:</strong> 3MB
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-900 flex items-center">
                    <div>
                        <strong>Lembrete:</strong> Guarde os arquivos oficiais organizados aqui nesta tela para agilizar o upload definitivo no momento da submissão.
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 w-1/3">Descrição</th>
                            <th class="px-3 py-3 text-center">Obs.</th>
                            <th class="px-4 py-3">Arquivo Atual</th>
                            <th class="px-4 py-3 text-center">Enviar Novo Arquivo</th>
                            <th class="px-3 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documentos as $doc): ?>
                        <tr class="bg-white border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition">
                            <td class="px-4 py-4 font-medium text-gray-900 text-xs">
                                <?php echo htmlspecialchars($doc['descricao']); ?>
                            </td>
                            <td class="px-3 py-4 text-center text-xs text-gray-400">
                                <?php echo htmlspecialchars($doc['observacao']); ?>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <?php if ($doc['arquivo_nome']): ?>
                                    <div class="flex items-center gap-1.5 text-green-600 font-mono bg-green-50 px-2 py-1 rounded-lg border border-green-100 w-fit">
                                        <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                                        <a href="../uploads/<?php echo $doc['arquivo_nome']; ?>" target="_blank" class="hover:underline max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap" title="Clique para abrir">
                                            <?php echo htmlspecialchars($doc['arquivo_nome']); ?>
                                        </a>
                                        <button type="button" onclick="copiarNome('<?php echo htmlspecialchars($doc['arquivo_nome']); ?>')" class="text-gray-400 hover:text-blue-600 pl-1" title="Copiar Nome do Arquivo">📋</button>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Não foi enviado nenhum arquivo ainda.</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <form id="form-doc-<?php echo $doc['id']; ?>" class="flex items-center justify-center gap-2">
                                    <input type="hidden" name="modulo" value="documentos_upload">
                                    <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                    
                                    <label class="cursor-pointer text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-1.5 px-3 rounded-lg border border-gray-300 shadow-sm transition">
                                        <span>Escolher arquivo</span>
                                        <input type="file" name="arquivo_novo" class="hidden" onchange="enviarArquivo(<?php echo $doc['id']; ?>)">
                                    </label>
                                </form>
                            </td>
                            <td class="px-3 py-4 text-center">
                                <?php if ($doc['arquivo_nome']): ?>
                                    <button type="button" onclick="removerDoc(<?php echo $doc['id']; ?>)" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Remover Arquivo">
                                        🗑️
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const statusTxt = document.getElementById('status-sistema');

        // Copiar o nome limpo do arquivo para colar no campo do EVEREST
        function copiarNome(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                statusTxt.innerText = "Nome copiado! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Pronto";
                    statusTxt.className = "text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full";
                }, 1000);
            });
        }

        // Executa o envio dinâmico do input file assim que ele é selecionado
       // Substitua a função antiga por esta no final do seu modulos/documentos.php
function enviarArquivo(idDoc) {
    const formElement = document.getElementById(`form-doc-${idDoc}`);
    
    // Criamos o FormData a partir do formulário da linha
    const formData = new FormData(formElement);
    
    // Forçamos explicitamente os valores para garantir que cheguem ao salvar.php
    formData.set('modulo', 'documentos_upload');
    formData.set('id', idDoc);

    statusTxt.innerText = "Fazendo upload...";
    statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";

    fetch('../salvar.php', {
        method: 'POST',
        body: formData // Envia o formulário completo e corrigido
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'sucesso') {
            location.reload();
        } else {
            alert("Erro no upload: " + data.mensagem);
            statusTxt.innerText = "Falha no envio";
            statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";
        }
    })
    .catch(err => {
        console.error(err);
        alert("Erro ao conectar com o servidor.");
    });
}

        // Remove o vínculo do arquivo e apaga ele da pasta uploads
        function removerDoc(idDoc) {
            if (!confirm("Deseja realmente excluir este documento do espelho?")) return;

            statusTxt.innerText = "Removendo...";
            statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";

            const formData = new FormData();
            formData.append('modulo', 'documentos_del');
            formData.append('id', idDoc);

            fetch('../salvar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'sucesso') {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>