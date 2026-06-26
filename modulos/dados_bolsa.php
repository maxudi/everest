<?php
require_once '../trava.php';
require_once '../config.php';

try {
    $stmt = $pdo->query("SELECT * FROM dados_bolsa WHERE id = 1");
    $dados = $stmt->fetch() ?: [];
    
    // Valores padrão focados na sua área caso o banco esteja limpo
    $defaults = [
        'data_inicio_bolsa' => '', 'titulo' => '', 'tipo_bolsa' => '', 'data_inicio_curso' => '', 'data_termino_curso' => '', 
        'area_conhecimento' => 'Ciências Exatas e da Terra', 'camara' => 'Câmara de Ciências Exatas e da Terra - CEX', 
        'sub_area_conhecimento' => 'CCOMP - Ciência da Computação', 'especialidade' => 'Banco de Dados e Big Data', 
        'resumo_plano' => '', 'palavra_chave_1' => '', 'palavra_chave_2' => '', 'palavra_chave_3' => '', 'palavra_chave_4' => '', 'palavra_chave_5' => '', 'palavra_chave_6' => ''
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
    <title>Formulário Auxiliar - FAPEMIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flatpickr para calendário -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <style>
        /* Estilização personalizada do Flatpickr */
        .flatpickr-calendar {
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
            border: 1px solid #e5e7eb !important;
        }
        .flatpickr-day.selected {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
        .flatpickr-day:hover {
            background: #dbeafe !important;
        }
        .flatpickr-input {
            cursor: pointer !important;
        }
        .date-input-wrapper {
            position: relative;
        }
        .date-input-wrapper .calendar-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 1.2rem;
            pointer-events: none;
            z-index: 10;
        }
        .date-input-wrapper input {
            padding-right: 40px !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="max-w-5xl mx-auto px-4 py-8">
        <a href="../index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition inline-flex items-center mb-6">
            ← Voltar ao Painel
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🎓 Dados da Bolsa (Espelho Livre)</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Sem campos obrigatórios. Digite o que quiser. Clique em 📋 para copiar.</p>
                </div>
                <span id="status-salvamento" class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pronto</span>
            </div>

            <form id="form-modulo" class="space-y-6">
                <input type="hidden" name="modulo" value="dados_bolsa_update">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de início da bolsa</label>
                        <div class="flex gap-2">
                            <div class="date-input-wrapper flex-1">
                                <input type="text" id="data_inicio_bolsa" name="data_inicio_bolsa" 
                                       placeholder="dd/mm/aaaa" 
                                       value="<?php echo htmlspecialchars($dados['data_inicio_bolsa']); ?>" 
                                       class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition date-input">
                                <span class="calendar-icon">📅</span>
                            </div>
                            <button type="button" onclick="copiar('data_inicio_bolsa')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Título</label>
                        <div class="flex gap-2">
                            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($dados['titulo']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('titulo')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipo de Bolsa</label>
                        <div class="flex gap-2">
                            <input type="text" id="tipo_bolsa" name="tipo_bolsa" placeholder="Ex: BIP, BIC, BPD" value="<?php echo htmlspecialchars($dados['tipo_bolsa']); ?>" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('tipo_bolsa')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de início do curso</label>
                        <div class="flex gap-2">
                            <div class="date-input-wrapper flex-1">
                                <input type="text" id="data_inicio_curso" name="data_inicio_curso" 
                                       placeholder="dd/mm/aaaa" 
                                       value="<?php echo htmlspecialchars($dados['data_inicio_curso']); ?>" 
                                       class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition date-input">
                                <span class="calendar-icon">📅</span>
                            </div>
                            <button type="button" onclick="copiar('data_inicio_curso')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de término do curso</label>
                        <div class="flex gap-2">
                            <div class="date-input-wrapper flex-1">
                                <input type="text" id="data_termino_curso" name="data_termino_curso" 
                                       placeholder="dd/mm/aaaa" 
                                       value="<?php echo htmlspecialchars($dados['data_termino_curso']); ?>" 
                                       class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition date-input">
                                <span class="calendar-icon">📅</span>
                            </div>
                            <button type="button" onclick="copiar('data_termino_curso')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-blue-50/50 p-5 rounded-2xl border border-blue-100/60">
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1.5">Área de conhecimento</label>
                        <div class="flex gap-2">
                            <input type="text" id="area_conhecimento" name="area_conhecimento" value="<?php echo htmlspecialchars($dados['area_conhecimento']); ?>" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('area_conhecimento')" class="p-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1.5">Câmara</label>
                        <div class="flex gap-2">
                            <input type="text" id="camara" name="camara" value="<?php echo htmlspecialchars($dados['camara']); ?>" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('camara')" class="p-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1.5">Sub-área de conhecimento</label>
                        <div class="flex gap-2">
                            <input type="text" id="sub_area_conhecimento" name="sub_area_conhecimento" value="<?php echo htmlspecialchars($dados['sub_area_conhecimento']); ?>" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('sub_area_conhecimento')" class="p-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1.5">Especialidade</label>
                        <div class="flex gap-2">
                            <input type="text" id="especialidade" name="especialidade" value="<?php echo htmlspecialchars($dados['especialidade']); ?>" class="w-full rounded-xl border border-gray-300 p-3 bg-white outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="copiar('especialidade')" class="p-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-xl transition">📋</button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Resumo do Plano de Trabalho do Bolsista (até 2.000 car.)</label>
                        <span id="char-counter" class="text-xs text-gray-400">2000 restantes</span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea id="resumo" name="resumo_plano" rows="6" maxlength="2000" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-blue-500 transition"><?php echo htmlspecialchars($dados['resumo_plano']); ?></textarea>
                        <button type="button" onclick="copiar('resumo')" class="p-3 bg-gray-100 hover:bg-blue-100 rounded-xl transition h-12 flex items-center justify-center">📋</button>
                    </div>
                </div>

                <div class="bg-gray-100/60 p-5 rounded-2xl border border-gray-200/60">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Palavras-chave</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php for($i = 1; $i <= 6; $i++): ?>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Palavra chave <?php echo $i; ?></label>
                            <div class="flex gap-2">
                                <input type="text" id="kw_<?php echo $i; ?>" name="palavra_chave_<?php echo $i; ?>" value="<?php echo htmlspecialchars($dados['palavra_chave_'.$i]); ?>" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-blue-500 bg-white">
                                <button type="button" onclick="copiar('kw_<?php echo $i; ?>')" class="px-3 bg-white border border-gray-200 hover:bg-blue-100 rounded-lg transition text-sm">📋</button>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        // ====== CONFIGURAÇÃO DOS CALENDÁRIOS E MÁSCARAS ======
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração base para todos os campos de data
            const dateConfig = {
                locale: 'pt',
                dateFormat: 'd/m/Y',
                allowInput: true,
                altInput: false,
                disableMobile: true,
                // Máscara de entrada
                onKeyDown: function(selectedDates, dateStr, instance) {
                    // Permite apenas números e barras
                    const input = instance.input;
                    const oldValue = input.value;
                    
                    // Máscara automática
                    input.addEventListener('input', function(e) {
                        let value = this.value.replace(/\D/g, '');
                        if (value.length > 8) value = value.slice(0, 8);
                        
                        // Aplica a máscara dd/mm/aaaa
                        if (value.length >= 3) {
                            value = value.slice(0, 2) + '/' + value.slice(2);
                        }
                        if (value.length >= 6) {
                            value = value.slice(0, 5) + '/' + value.slice(5);
                        }
                        
                        this.value = value;
                    });
                },
                // Validação da data
                onClose: function(selectedDates, dateStr, instance) {
                    const input = instance.input;
                    if (input.value && input.value.length === 10) {
                        // Verifica se a data é válida
                        const parts = input.value.split('/');
                        if (parts.length === 3) {
                            const day = parseInt(parts[0]);
                            const month = parseInt(parts[1]) - 1;
                            const year = parseInt(parts[2]);
                            const date = new Date(year, month, day);
                            
                            if (date.getFullYear() === year && 
                                date.getMonth() === month && 
                                date.getDate() === day) {
                                // Data válida
                                input.classList.remove('border-red-400');
                                input.classList.add('border-green-400');
                                setTimeout(() => {
                                    input.classList.remove('border-green-400');
                                }, 2000);
                            } else {
                                // Data inválida
                                input.classList.add('border-red-400');
                                setTimeout(() => {
                                    input.classList.remove('border-red-400');
                                }, 3000);
                            }
                        }
                    }
                }
            };

            // Inicializa os calendários
            const dateInputs = document.querySelectorAll('.date-input');
            dateInputs.forEach(input => {
                const config = {...dateConfig};
                
                // Se for data de término, define data mínima como início do curso
                if (input.id === 'data_termino_curso') {
                    const inicioCurso = document.getElementById('data_inicio_curso');
                    if (inicioCurso && inicioCurso.value) {
                        const parts = inicioCurso.value.split('/');
                        if (parts.length === 3) {
                            const minDate = new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
                            config.minDate = minDate;
                        }
                    }
                }
                
                // Inicializa o Flatpickr
                const fp = flatpickr(input, config);
                
                // Botão de ícone para abrir o calendário
                const wrapper = input.closest('.date-input-wrapper');
                if (wrapper) {
                    const icon = wrapper.querySelector('.calendar-icon');
                    if (icon) {
                        icon.addEventListener('click', function(e) {
                            e.preventDefault();
                            fp.open();
                        });
                    }
                }
            });

            // Atualiza data mínima de término quando início do curso muda
            const inicioCurso = document.getElementById('data_inicio_curso');
            const terminoCurso = document.getElementById('data_termino_curso');
            
            if (inicioCurso && terminoCurso) {
                inicioCurso.addEventListener('change', function() {
                    if (this.value && this.value.length === 10) {
                        const parts = this.value.split('/');
                        if (parts.length === 3) {
                            const minDate = new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
                            const fp = terminoCurso._flatpickr;
                            if (fp) {
                                fp.set('minDate', minDate);
                            }
                        }
                    }
                });
            }
        });

        // ====== FUNÇÕES DE UTILIDADE ======
        const form = document.getElementById('form-modulo');
        const statusTxt = document.getElementById('status-salvamento');
        const resumoTxt = document.getElementById('resumo');
        const charCounter = document.getElementById('char-counter');
        let timeout = null;

        // Função de cópia com fallback
        function copiar(id) {
            const el = document.getElementById(id);
            if (!el) return;
            
            // Tenta usar a API Clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(el.value).then(() => {
                    statusTxt.innerText = "Copiado para o Clipboard! 🚀";
                    statusTxt.className = "text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full";
                    setTimeout(() => {
                        statusTxt.innerText = "Salvo automaticamente!";
                        statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                    }, 1200);
                }).catch(() => {
                    // Fallback
                    el.select();
                    document.execCommand('copy');
                    statusTxt.innerText = "Copiado!";
                    statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                });
            } else {
                // Fallback para navegadores antigos
                el.select();
                document.execCommand('copy');
                statusTxt.innerText = "Copiado!";
                statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
            }
        }

        // Contador de caracteres do Resumo
        if (resumoTxt && charCounter) {
            resumoTxt.addEventListener('input', () => {
                const remaining = 2000 - resumoTxt.value.length;
                charCounter.innerText = `${remaining} restantes`;
                if (remaining < 100) {
                    charCounter.className = "text-xs text-red-600 font-medium";
                } else if (remaining < 500) {
                    charCounter.className = "text-xs text-orange-500 font-medium";
                } else {
                    charCounter.className = "text-xs text-gray-400";
                }
            });
            charCounter.innerText = `${2000 - resumoTxt.value.length} restantes`;
        }

        // Autosave contínuo por digitação
        if (form) {
            form.addEventListener('input', () => {
                clearTimeout(timeout);
                statusTxt.innerText = "Digitando...";
                statusTxt.className = "text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full";
                
                timeout = setTimeout(() => {
                    const formData = new FormData(form);
                    
                    fetch('../salvar.php', { 
                        method: 'POST', 
                        body: formData 
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erro na resposta do servidor');
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'sucesso') {
                            statusTxt.innerText = "Salvo automaticamente!";
                            statusTxt.className = "text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full";
                        } else {
                            throw new Error(data.mensagem || 'Erro desconhecido');
                        }
                    })
                    .catch(err => {
                        console.error('Erro no autosave:', err);
                        statusTxt.innerText = "Erro ao salvar";
                        statusTxt.className = "text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full";
                    });
                }, 1000);
            });

            // Salva ao perder o foco
            form.addEventListener('blur', (e) => {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    form.dispatchEvent(new Event('input'));
                }
            }, true);

            // Previne saída sem salvar
            window.addEventListener('beforeunload', (e) => {
                e.preventDefault();
                e.returnValue = 'Você tem alterações não salvas. Tem certeza que deseja sair?';
            });
        }

        console.log('🎓 Módulo de Dados da Bolsa carregado com sucesso!');
    </script>
</body>
</html>