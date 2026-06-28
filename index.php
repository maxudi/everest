<?php
require_once 'trava.php'; // Protege a página principal
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador FAPEMIG - EVEREST</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div class="max-w-6xl mx-auto px-4 py-8">
        <header class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-blue-900">Formulário FAPEMIG (Espelho)</h1>
            <p class="text-gray-600 mt-2">Preencha offline e envie para o EVEREST quando estiver pronto.</p>
        </header>
        <div class="flex justify-between items-center bg-white border-b border-slate-200 px-6 py-4 mb-6 shadow-sm">
    <div>
        <h1 class="text-xl font-bold text-slate-900">🚀 Painel Espelho FAPEMIG</h1>
        <p class="text-xs text-slate-500">Conectado como: <span class="font-semibold text-blue-600"><?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Usuário'); ?></span></p>
    </div>
    
    <a href="logout.php" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-medium rounded-xl transition flex items-center gap-1.5 border border-rose-200/60">
        <span>Sair</span> 🚪
    </a>
</div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="modulos/dados_bolsa.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">🎓</div>
                <h3 class="text-lg font-semibold text-gray-900">Dados da Bolsa</h3>
                <p class="text-sm text-gray-500 mt-1">Informações gerais, nível e título da proposta.</p>
            </a>

            <a href="modulos/instituicoes.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">🏢</div>
                <h3 class="text-lg font-semibold text-gray-900">Instituições</h3>
                <p class="text-sm text-gray-500 mt-1">Dados da instituição proponente e executoras.</p>
            </a>

            <a href="modulos/instituicao_destino.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📍</div>
                <h3 class="text-lg font-semibold text-gray-900">Instituição de Destino</h3>
                <p class="text-sm text-gray-500 mt-1">Local onde o projeto/bolsa será executado.</p>
            </a>

            <a href="modulos/natureza_proposta.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">🔬</div>
                <h3 class="text-lg font-semibold text-gray-900">Natureza da Proposta</h3>
                <p class="text-sm text-gray-500 mt-1">Área do conhecimento e caracterização.</p>
            </a>

            <a href="modulos/detalhamento_proposta.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📝</div>
                <h3 class="text-lg font-semibold text-gray-900">Detalhamento da Proposta</h3>
                <p class="text-sm text-gray-500 mt-1">Resumo, justificativa e objetivos.</p>
            </a>

            <a href="modulos/produtos_pretendidos.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📦</div>
                <h3 class="text-lg font-semibold text-gray-900">Produtos Pretendidos</h3>
                <p class="text-sm text-gray-500 mt-1">Artigos, patentes, teses esperadas.</p>
            </a>

            <a href="modulos/membros_equipe.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">👥</div>
                <h3 class="text-lg font-semibold text-gray-900">Membros da Equipe</h3>
                <p class="text-sm text-gray-500 mt-1">Pesquisadores, bolsistas e colaboradores.</p>
            </a>

            <a href="modulos/metas.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">🎯</div>
                <h3 class="text-lg font-semibold text-gray-900">Metas</h3>
                <p class="text-sm text-gray-500 mt-1">Alvos principais e indicadores de sucesso.</p>
            </a>

            <a href="modulos/etapas.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📅</div>
                <h3 class="text-lg font-semibold text-gray-900">Etapas</h3>
                <p class="text-sm text-gray-500 mt-1">Cronograma físico de execução.</p>
            </a>

            <a href="modulos/dispendios.php" class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">💰</div>
                <h3 class="text-lg font-semibold text-gray-900">Dispêndios</h3>
                <p class="text-sm text-gray-500 mt-1">Orçamento, material de consumo e permanente.</p>
            </a>

            <a href="modulos/documentos.php" class="block p-6 bg-white rounded-xl shadow-sm border border-blue-200 bg-blue-50/30 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📂</div>
                <h3 class="text-lg font-semibold text-blue-900">Documentos Eletrônicos</h3>
                <p class="text-sm text-gray-500 mt-1">Upload e gerenciamento de PDFs e anexos obrigatórios.</p>
            </a>

            <a href="modulos/formularios.php" class="block p-6 bg-white rounded-xl shadow-sm border border-blue-200 bg-blue-50/30 hover:shadow-md hover:border-blue-500 transition-all duration-200">
                <div class="text-blue-600 text-2xl mb-3">📋</div>
                <h3 class="text-lg font-semibold text-blue-900">Preenchimento de formulários.</h3>
                <p class="text-sm text-gray-500 mt-1">Preenchimento de formulários.</p>
            </a>

        </div>
    </div>

</body>
</html>