<?php
require_once '../config.php';
try {
    $stmt = $pdo->query("SELECT * FROM detalhamento_proposta WHERE id = 1");
    $dados = $stmt->fetch() ?: [];
    
    $campos = [
        'descricao_correlacao', 'descricao_alinhamento', 'informacoes_complementares', 'justificativa_apoio',
        'link_lattes', 'experiencia_exterior', 'responsavel_executora', 'responsavel_gestora',
        'resultados_impactos', 'resumo_leigo', 'unidade_sei'
    ];
    foreach($campos as $c) { if(!isset($dados[$c])) $dados[$c] = ''; }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhamento da Proposta - FAPEMIG</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">📝 Detalhamento da Proposta</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Rascunho livre e autosave. Clique em 📋 para copiar e transferir.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-modulo" class="space-y-6">
                <input type="hidden" name="modulo" value="detalhamento_proposta">

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Breve descrição da correlação entre a atuação do(a) beneficiário(a) na instituição proponente e o conteúdo do curso/capacitação pretendido;</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="descricao_correlacao" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="descricao_correlacao" name="descricao_correlacao" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['descricao_correlacao']); ?></textarea>
                        <button type="button" onclick="copiar('descricao_correlacao')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Breve descrição do alinhamento entre o objeto do curso/capacitação, os objetivos institucionais da Instituição de vínculo do(a) candidato(a) e as atividades previstas no Plano de Trabalho;</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="descricao_alinhamento" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="descricao_alinhamento" name="descricao_alinhamento" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['descricao_alinhamento']); ?></textarea>
                        <button type="button" onclick="copiar('descricao_alinhamento')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Informações relevantes complementares (se houver);</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="informacoes_complementares" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="informacoes_complementares" name="informacoes_complementares" rows="3" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['informacoes_complementares']); ?></textarea>
                        <button type="button" onclick="copiar('informacoes_complementares')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Justificativa fundamentada para o apoio;</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="justificativa_apoio" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="justificativa_apoio" name="justificativa_apoio" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['justificativa_apoio']); ?></textarea>
                        <button type="button" onclick="copiar('justificativa_apoio')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link do currículo atualizado do(a) beneficiário(a) do apoio/Coordenador(a) da proposta na Plataforma Lattes do CNPq;</label>
                    <div class="flex gap-2">
                        <input type="text" id="link_lattes" name="link_lattes" placeholder="http://lattes.cnpq.br/..." value="<?php echo htmlspecialchars($dados['link_lattes']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                        <button type="button" onclick="copiar('link_lattes')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                    </div>
                </div>

                <div class="bg-blue-50/40 border border-blue-100 p-5 rounded-2xl">
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-blue-900 max-w-3xl leading-snug">No caso das Linhas de apoio B (Doutorado Sanduíche) e C, explicitar de que forma a experiência no exterior poderá agregar valor à sua atuação profissional junto à instituição de vínculo...</label>
                        <span class="text-xs text-blue-700 font-medium shrink-0 counter-lbl" data-target="experiencia_exterior" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="experiencia_exterior" name="experiencia_exterior" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['experiencia_exterior']); ?></textarea>
                        <button type="button" onclick="copiar('experiencia_exterior')" class="p-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Nome do responsável da OUTORGADA EXECUTORA pela gestão e fiscalização do Termo de Outorga, conforme Lei Estadual nº 22.929, de 2018.</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="responsavel_executora" data-max="200">200 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="responsavel_executora" name="responsavel_executora" rows="2" maxlength="200" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['responsavel_executora']); ?></textarea>
                        <button type="button" onclick="copiar('responsavel_executora')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Nome do responsável da OUTORGADA GESTORA (Fundação de Apoio, se houver) pela gestão e fiscalização do Termo de Outorga, conforme Lei Estadual nº 22.929, de 2018.</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="responsavel_gestora" data-max="200">200 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="responsavel_gestora" name="responsavel_gestora" rows="2" maxlength="200" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['responsavel_gestora']); ?></textarea>
                        <button type="button" onclick="copiar('responsavel_gestora')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Resultados, benefícios e impactos esperados, evidenciando, inclusive, se for o caso de gerar também benefícios e impactos externos à instituição proponente;</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="resultados_impactos" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="resultados_impactos" name="resultados_impactos" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['resultados_impactos']); ?></textarea>
                        <button type="button" onclick="copiar('resultados_impactos')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="text-sm font-semibold text-gray-700 max-w-3xl leading-snug">Resumo Publicável em linguagem acessível para o público leigo;</label>
                        <span class="text-xs text-gray-400 font-medium shrink-0 counter-lbl" data-target="resumo_leigo" data-max="3000">3000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="resumo_leigo" name="resumo_leigo" rows="4" maxlength="3000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['resumo_leigo']); ?></textarea>
                        <button type="button" onclick="copiar('resumo_leigo')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Unidade SEI do solicitante, para assinatura do Instrumento Jurídico.</label>
                    <div class="flex gap-2">
                        <input type="text" id="unidade_sei" name="unidade_sei" value="<?php echo htmlspecialchars($dados['unidade_sei']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                        <button type="button" onclick="copiar('unidade_sei')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('form-modulo');
        const statusTxt = document.getElementById('status-salvamento');
        let timeout = null;

        // Copiar conteúdo de forma simplificada para a Área de Trabalho
        function copiar(id) {
            const el = document.getElementById(id);
            if (!el) return;
            navigator.clipboard.writeText(el.value).then(() => {
                statusTxt.innerText = "Copiado para o Clipboard! 🚀";
                statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                setTimeout(() => {
                    statusTxt.innerText = "Salvo automaticamente!";
                    statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                }, 1200);
            });
        }

        // Atualização e gestão dinâmica de caracteres em tempo real
        function initCounters() {
            document.querySelectorAll('.counter-lbl').forEach(lbl => {
                const targetId = lbl.getAttribute('data-target');
                const max = parseInt(lbl.getAttribute('data-max'));
                const txtArea = document.getElementById(targetId);
                
                const update = () => {
                    lbl.innerText = `${max - txtArea.value.length} restantes`;
                };
                txtArea.addEventListener('input', update);
                update();
            });
        }
        initCounters();

        // Autosave dinâmico (acionado ao digitar)
        form.addEventListener('input', () => {
            clearTimeout(timeout);
            statusTxt.innerText = "Digitando...";
            statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";
            
            timeout = setTimeout(() => {
                fetch('../salvar.php', { method: 'POST', body: new FormData(form) })
                .then(() => {
                    statusTxt.innerText = "Salvo automaticamente!";
                    statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                });
            }, 1000); // Aguarda 1 segundo após o término da digitação para salvar
        });
    </script>
</body>
</html>