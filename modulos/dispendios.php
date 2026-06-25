<?php
require_once '../config.php';
try {
    // Busca as etapas cadastradas para o campo de vínculos
    $stmtEtapas = $pdo->query("SELECT id, descricao_etapa FROM etapas ORDER BY id ASC");
    $etapas = $stmtEtapas->fetchAll() ?: [];

    // Busca os dispêndios cadastrados
    $stmtDisp = $pdo->query("SELECT * FROM dispendios ORDER BY id ASC");
    $dispendios = $stmtDisp->fetchAll() ?: [];
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispêndios - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">💰 Dispêndios (Espelho Orçamentário)</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Cadastre bolsas, auxílios e despesas do projeto.</p>
                </div>
                <span id="status-sistema" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-dispendios" class="space-y-5 mb-10">
                <input type="hidden" name="modulo" value="dispendios_add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Dispêndio *</label>
                        <select id="dispendio-select" name="dispendio" required class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <option value="">--- Escolha um dispêndio ---</option>
                            <option value="[BDS] AUXILIO INSTALAÇÃO - MAIS DE 500 KM (E$) - EUROPA">[BDS] AUXILIO INSTALAÇÃO - MAIS DE 500 KM (E$) - EUROPA</option>
                            <option value="[BDS] AUXILIO INSTALAÇÃO - MAIS DE 500 KM (US$) - AMÉRICA DO NORTE, JAPÃO E AUSTRÁLIA">[BDS] AUXILIO INSTALAÇÃO - MAIS DE 500 KM (US$) - AMÉRICA DO NORTE, JAPÃO E AUSTRÁLIA</option>
                            <option value="[BDS] BOLSA DE DOUTORADO SANDUICHE - AMÉRICA DO NORTE, JAPÃO E AUSTRÁLIA -">[BDS] BOLSA DE DOUTORADO SANDUICHE - AMÉRICA DO NORTE, JAPÃO E AUSTRÁLIA -</option>
                            <option value="[BDS] BOLSA DE DOUTORADO SANDUICHE - EUROPA -">[BDS] BOLSA DE DOUTORADO SANDUICHE - EUROPA -</option>
                            <option value="AUXÍLIO INSTALAÇÃO">AUXÍLIO INSTALAÇÃO</option>
                            <option value="BOLSA DE DOUTORADO - ÚNICO">BOLSA DE DOUTORADO - ÚNICO</option>
                            <option value="BOLSA DE MESTRADO - ÚNICO">BOLSA DE MESTRADO - ÚNICO</option>
                            <option value="ESTÁGIO PÓS-DOUTORAL NO EXTERIOR -">ESTÁGIO PÓS-DOUTORAL NO EXTERIOR -</option>
                            <option value="PASSAGEM INTERNACIONAL">PASSAGEM INTERNACIONAL</option>
                            <option value="PASSAGEM NACIONAL">PASSAGEM NACIONAL</option>
                            <option value="SEGURO SAÚDE">SEGURO SAÚDE</option>
                            <option value="TAXAS ESCOLARES">TAXAS ESCOLARES</option>
                        </select>
                    </div>

                    <div id="info-box" class="hidden md:col-span-3 bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-900">
                        <strong>Informações do Dispêndio:</strong> <span id="info-text"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Classificação Econômica</label>
                        <input type="text" id="classificacao" name="classificacao" value="Custeio" readonly class="w-full rounded-xl border border-gray-200 p-3 bg-gray-100 text-gray-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Etapas Vinculadas *</label>
                        <select name="etapas_vinculadas" required class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <option value="">Selecione a etapa correspondente</option>
                            <?php foreach($etapas as $e): ?>
                                <option value="Etapa <?php echo $e['id']; ?>">Etapa <?php echo $e['id']; ?> - <?php echo htmlspecialchars(mb_strimwidth($e['descricao_etapa'], 0, 60, "...")); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Origem do Recurso *</label>
                        <select name="origem_recurso" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <option value="Concedente">Concedente</option>
                            <option value="Contrapartida">Contrapartida</option>
                        </select>
                    </div>

                    <div class="flex items-center h-full pt-6 pl-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" name="importado_exterior" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-gray-300">
                            <span>Importado/Pagamento no Exterior</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Quantidade *</label>
                        <input type="number" name="quantidade" min="1" value="1" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Valor (R$) *</label>
                        <input type="text" id="valor_unitario" name="valor_unitario" placeholder="0,00" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div id="wrapper-meses" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nº de meses *</label>
                        <input type="number" id="num_meses" name="num_meses" min="1" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição *</label>
                        <input type="text" name="descricao" required placeholder="Ex: Mensalidades de bolsa de doutorado sanduíche no exterior." class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-3">
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Justificativa *</label>
                            <span id="char-counter" class="text-xs text-gray-400">500 restantes</span>
                        </div>
                        <textarea id="justificativa" name="justificativa" rows="3" maxlength="500" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition" placeholder="Justifique a necessidade deste dispêndio para a execução da etapa."></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition">
                        💾 Inserir Dispêndio
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Dispêndio / Descrição</th>
                            <th class="px-4 py-3 text-center">Vínculo</th>
                            <th class="px-4 py-3 text-center">Qtd. / Meses</th>
                            <th class="px-4 py-3 text-right">Vlr. Unitário</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dispendios)): ?>
                        <tr class="bg-white text-center">
                            <td colspan="6" class="px-4 py-8 text-gray-400 italic text-sm">Nenhum dispêndio orçado.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($dispendios as $d): 
                                $totalItem = $d['quantidade'] * $d['valor_unitario'];
                                if(!empty($d['num_meses'])) { $totalItem *= $d['num_meses']; }
                            ?>
                            <tr class="bg-white border-b border-gray-100 last:border-0 hover:bg-gray-50/80 transition text-gray-700">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900 flex items-center gap-1">
                                        <span id="disp-title-<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['dispendio']); ?></span>
                                        <button type="button" onclick="copiarTxt('disp-title-<?php echo $d['id']; ?>')" class="text-[10px] bg-gray-100 p-0.5 rounded hover:bg-blue-100">📋</button>
                                    </div>
                                    <div class="text-gray-400 mt-0.5 flex items-center gap-1">
                                        <span id="disp-desc-<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['descricao']); ?></span>
                                        <button type="button" onclick="copiarTxt('disp-desc-<?php echo $d['id']; ?>')" class="text-[10px] bg-gray-100 p-0.5 rounded hover:bg-blue-100">📋</button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-medium text-blue-800">
                                    <span id="disp-vinc-<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['etapas_vinculadas']); ?></span>
                                    <button type="button" onclick="copiarTxt('disp-vinc-<?php echo $d['id']; ?>')" class="text-[9px] bg-gray-100 px-1 rounded hover:bg-blue-100">📋</button>
                                </td>
                                <td class="px-4 py-3 text-center font-mono">
                                    Qtd: <span id="disp-qtd-<?php echo $d['id']; ?>"><?php echo $d['quantidade']; ?></span> <button type="button" onclick="copiarTxt('disp-qtd-<?php echo $d['id']; ?>')" class="text-[9px] bg-gray-100 px-0.5 rounded hover:bg-blue-100">📋</button>
                                    <?php if($d['num_meses']): ?>
                                        <br>Meses: <span id="disp-mes-<?php echo $d['id']; ?>"><?php echo $d['num_meses']; ?></span> <button type="button" onclick="copiarTxt('disp-mes-<?php echo $d['id']; ?>')" class="text-[9px] bg-gray-100 px-0.5 rounded hover:bg-blue-100">📋</button>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right font-mono">
                                    R$ <span id="disp-vlr-<?php echo $d['id']; ?>"><?php echo number_format($d['valor_unitario'], 2, ',', '.'); ?></span>
                                    <button type="button" onclick="copiarVal('<?php echo number_format($d['valor_unitario'], 2, ',', '.'); ?>')" class="text-[9px] bg-gray-100 p-0.5 rounded hover:bg-blue-100">📋</button>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 font-mono">
                                    R$ <?php echo number_format($totalItem, 2, ',', '.'); ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="removerDispendio(<?php echo $d['id']; ?>)" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">🗑️</button>
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
        const selectDisp = document.getElementById('dispendio-select');
        const wrapperMeses = document.getElementById('wrapper-meses');
        const numMeses = document.getElementById('num_meses');
        const valorUnitario = document.getElementById('valor_unitario');
        const infoBox = document.getElementById('info-box');
        const infoText = document.getElementById('info-text');
        
        const form = document.getElementById('form-dispendios');
        const just = document.getElementById('justificativa');
        const counter = document.getElementById('char-counter');
        const statusTxt = document.getElementById('status-sistema');

        // Monitor de limite do textarea
        just.addEventListener('input', () => {
            counter.innerText = `${500 - just.value.length} restantes`;
        });

        // Comportamento dinâmico igual ao EVEREST real
        selectDisp.addEventListener('change', () => {
            const val = selectDisp.value;
            if(val.includes("BOLSA DE DOUTORADO SANDUICHE")) {
                wrapperMeses.classList.remove('hidden');
                numMeses.setAttribute('required', 'true');
                valorUnitario.value = "1300,00"; // Exemplo de valor padrão do edital
                infoBox.classList.remove('hidden');
                infoText.innerText = "Número de mensalidades máximo: 12. Mínimo: 3. Valor de referência: R$ 1.300,00";
            } else {
                wrapperMeses.classList.add('hidden');
                numMeses.removeAttribute('required');
                numMeses.value = "";
                valorUnitario.value = "";
                infoBox.classList.add('hidden');
            }
        });

        // Funções de Clipboard rápidos
        function copiarTxt(id) {
            navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => feedbackCopia());
        }
        function copiarVal(valor) {
            navigator.clipboard.writeText(valor).then(() => feedbackCopia());
        }
        function feedbackCopia() {
            statusTxt.innerText = "Copiado! 🚀";
            statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
            setTimeout(() => {
                statusTxt.innerText = "Pronto";
                statusTxt.className = "text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full";
            }, 1000);
        }

        // Adicionar Registro via AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            fetch('../salvar.php', { method: 'POST', body: new FormData(form) })
            .then(res => res.json()).then(data => { if(data.status === 'sucesso') location.reload(); });
        });

        // Remover Registro via AJAX
        function removerDispendio(idItem) {
            if(!confirm("Remover este dispêndio da listagem?")) return;
            const fd = new FormData();
            fd.append('modulo', 'dispendios_del');
            fd.append('id', idItem);
            fetch('../salvar.php', { method: 'POST', body: fd })
            .then(res => res.json()).then(data => { if(data.status === 'sucesso') location.reload(); });
        }
    </script>
</body>
</html>