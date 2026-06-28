<?php
// modulos/formularios.php

// Correção dos caminhos para buscar os ficheiros de segurança e ligação na raiz
require_once '../trava.php';
require_once '../config.php';

try {
    // Procura os anexos ordenados conforme a estrutura da sua base de dados
    $stmt = $pdo->query("SELECT * FROM anexos_fapemig ORDER BY id ASC");
    $anexos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar anexos do banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Formulários - FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <div class="flex justify-between items-center mb-8 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div>
                <a href="../index.php" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition inline-flex items-center mb-2">
                    ← Voltar ao Painel
                </a>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900">📋 Controle de Formulários e Anexos</h1>
                    <a href="../uploads/anteprojeto.pdf" target="_blank" title="Visualizar Anteprojeto" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-red-700 bg-red-50 border border-red-200 px-2.5 py-1 rounded-md hover:bg-red-100 transition animate-pulse">
                        📄 Anteprojeto
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-1.5">Gerencie os modelos originais e controle as versões preenchidas enviadas para o servidor.</p>
            </div>
            <span id="status-global" class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full">Pronto</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 w-32">Anexo</th>
                        <th class="px-6 py-4">Descrição do Documento</th>
                        <th class="px-6 py-4 text-center w-36">Responsável</th>
                        <th class="px-6 py-4 text-center w-48">Status / Enviado</th>
                        <th class="px-6 py-4 text-center w-40">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php foreach ($anexos as $anexo): ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900"><?php echo htmlspecialchars($anexo['codigo_anexo']); ?></td>
                            <td class="px-6 py-4 text-gray-600 font-medium"><?php echo htmlspecialchars($anexo['titulo']); ?></td>
                            
                            <td class="px-6 py-4 text-center">
                                <?php if (intval($anexo['responsavel']) === 2): ?>
                                    <div class="flex items-center justify-center gap-1.5" title="Responsável: PMMG">
                                        <img src="../uploads/pmmg.png" alt="PMMG" class="w-5 h-5 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                        <span class="text-xs font-semibold text-slate-700">PMMG</span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center justify-center gap-1.5" title="Responsável: Coordenador">
                                        <span class="text-base">👨‍✈️</span>
                                        <span class="text-xs font-semibold text-blue-700">Eu</span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2" id="status-container-<?php echo str_replace(' ', '-', $anexo['codigo_anexo']); ?>">
                                    <?php if ($anexo['status_upload'] == 1): ?>
                                        <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                                            ✓ Pronto
                                        </span>
                                        <a href="../uploads/<?php echo htmlspecialchars($anexo['caminho_arquivo']); ?>" target="_blank" title="Ver documento enviado" class="text-base hover:scale-110 transition">
                                            📄
                                        </a>
                                        <button onclick="excluirArquivo('<?php echo $anexo['codigo_anexo']; ?>')" title="Excluir arquivo enviado" class="text-xs p-1 text-gray-400 hover:text-red-500 transition">
                                            🗑️
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
                                            Pendente
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <?php 
                                        $nome_base = strtolower(str_replace(' ', '_', $anexo['codigo_anexo']));
                                        $extensao = (in_array($nome_base, ['anexo_viii', 'anexo_ix'])) ? 'pdf' : 'docx';
                                        $nome_arquivo_modelo = $nome_base . '.' . $extensao;
                                    ?>
                                    <a href="../uploads/<?php echo $nome_arquivo_modelo; ?>" download title="Baixar modelo original (<?php echo strtoupper($extensao); ?>)" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 border border-gray-200 rounded-xl transition">
                                        📥 
                                    </a>

                                    <label class="p-2 cursor-pointer text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 border border-gray-200 rounded-xl transition flex items-center justify-center" title="Enviar arquivo preenchido">
                                        📤
                                        <input type="file" accept=".doc,.docx,.pdf" class="hidden" onchange="executarUpload(this, '<?php echo $anexo['codigo_anexo']; ?>')">
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function ejecutarUpload(input, codigoAnexo) {
        if (!input.files || input.files.length === 0) return;
        
        const file = input.files[0];
        const statusGlobal = document.getElementById('status-global');
        const containerId = 'status-container-' + codigoAnexo.replace(' ', '-');
        const statusContainer = document.getElementById(containerId);

        statusGlobal.innerText = "Enviando arquivo...";
        statusGlobal.className = "text-xs font-medium text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full";

        const formData = new FormData();
        formData.append('modulo', 'upload_anexo');
        formData.append('codigo_anexo', codigoAnexo);
        formData.append('arquivo_anexo', file);

        // Dispara a requisição subindo um nível para encontrar o salvar.php na raiz
        fetch('../salvar.php', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error("Erro na resposta do servidor.");
            return res.json();
        })
        .then(data => {
            if (data.status === 'sucesso') {
                statusGlobal.innerText = "Pronto";
                statusGlobal.className = "text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full";
                
                // Injeta na hora o check verde, o link do documento e o botão de apagar
                statusContainer.innerHTML = `
                    <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">✓ Pronto</span>
                    <a href="../uploads/${data.arquivo_salvo}" target="_blank" title="Ver documento enviado" class="text-base hover:scale-110 transition">📄</a>
                    <button onclick="excluirArquivo('${codigoAnexo}')" title="Excluir arquivo enviado" class="text-xs p-1 text-gray-400 hover:text-red-500 transition">🗑️</button>
                `;
            } else {
                alert("Erro: " + data.mensagem);
                statusGlobal.innerText = "Falha no envio";
                statusGlobal.className = "text-xs font-medium text-red-600 bg-red-50 px-3 py-1.5 rounded-full";
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro de comunicação ao enviar arquivo.");
        });
    }

    function excluirArquivo(codigoAnexo) {
        if (!confirm(`Tem certeza que deseja apagar o arquivo enviado do ${codigoAnexo}?`)) return;

        const statusGlobal = document.getElementById('status-global');
        const containerId = 'status-container-' + codigoAnexo.replace(' ', '-');
        const statusContainer = document.getElementById(containerId);

        statusGlobal.innerText = "Removendo arquivo...";
        statusGlobal.className = "text-xs font-medium text-red-600 bg-red-50 px-3 py-1.5 rounded-full";

        const formData = new FormData();
        formData.append('modulo', 'upload_anexo_del');
        formData.append('codigo_anexo', codigoAnexo);

        fetch('../salvar.php', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error("Erro na resposta do servidor.");
            return res.json();
        })
        .then(data => {
            if (data.status === 'sucesso') {
                statusGlobal.innerText = "Pronto";
                statusGlobal.className = "text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full";
                
                // Retorna o bloco visual para o estado Pendente de forma reativa
                statusContainer.innerHTML = `
                    <span class="inline-flex items-center text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
                        Pendente
                    </span>
                `;
            } else {
                alert("Erro do servidor: " + data.mensagem);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro de comunicação ao excluir o arquivo.");
        });
    }
    </script>
</body>
</html>