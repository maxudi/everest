<?php

require_once 'trava.php'; // Protege a página principal
// salvar.php
require_once 'config.php';

// Força o cabeçalho a retornar JSON e evita cacheamento pelas requisições Fetch
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modulo = $_POST['modulo'] ?? '';

    if (empty($modulo)) {
        http_response_code(400);
        echo json_encode(['status' => 'erro', 'mensagem' => 'Módulo não especificado nas requisições.']);
        exit;
    }

    // =========================================================================
    // 1. MODULO: PRODUTOS PRETENDIDOS (Adicionar e Remover)
    // =========================================================================
    if ($modulo === 'produtos_pretendidos_add') {
        $sql = "INSERT INTO produtos_pretendidos (produto, quantidade, tipo, especificacao) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['produto'] ?? '',
                intval($_POST['quantidade'] ?? 1),
                $_POST['tipo'] ?? '',
                $_POST['especificacao'] ?? ''
            ]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao inserir produto: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($modulo === 'produtos_pretendidos_del') {
        $id = intval($_POST['id'] ?? 0);
        $sql = "DELETE FROM produtos_pretendidos WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao deletar produto: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
    // 2. MODULO: DOCUMENTOS ELETRÔNICOS (Upload e Remoção)
    // =========================================================================
    if ($modulo === 'documentos_upload') {
        $id = intval($_POST['id'] ?? 0);
        
        if ($id === 0 || !isset($_FILES['arquivo_novo']) || $_FILES['arquivo_novo']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Arquivo inválido ou não enviado de forma correta.']);
            exit;
        }

        $fileTmpPath = $_FILES['arquivo_novo']['tmp_name'];
        $fileName = $_FILES['arquivo_novo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Extensão não permitida (Apenas PDF, DOC, DOCX, XLS, XLSX).']);
            exit;
        }

        $uploadFileDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $newFileName = 'doc_' . $id . '_' . time() . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            try {
                $stmtOld = $pdo->prepare("SELECT arquivo_nome FROM documentos WHERE id = ?");
                $stmtOld->execute([$id]);
                $oldFile = $stmtOld->fetchColumn();
                if ($oldFile && file_exists($uploadFileDir . $oldFile)) {
                    @unlink($uploadFileDir . $oldFile);
                }

                $stmt = $pdo->prepare("UPDATE documentos SET arquivo_nome = ? WHERE id = ?");
                $stmt->execute([$newFileName, $id]);

                echo json_encode(['status' => 'sucesso', 'arquivo' => $newFileName]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao mover o arquivo para a pasta uploads.']);
        }
        exit;
    }

    if ($modulo === 'documentos_del') {
        $id = intval($_POST['id'] ?? 0);
        try {
            $stmtFile = $pdo->prepare("SELECT arquivo_nome FROM documentos WHERE id = ?");
            $stmtFile->execute([$id]);
            $arquivo = $stmtFile->fetchColumn();

            if ($arquivo) {
                $filePath = __DIR__ . '/uploads/' . $arquivo;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                
                $stmt = $pdo->prepare("UPDATE documentos SET arquivo_nome = NULL WHERE id = ?");
                $stmt->execute([$id]);
            }
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao remover documento: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
    // 3. MODULO: DISPENDIOS (Orçamento) - Adicionar e Remover
    // =========================================================================
    if ($modulo === 'dispendios_add') {
        $sql = "INSERT INTO dispendios (dispendio, classificacao, etapas_vinculadas, origem_recurso, importado_exterior, quantidade, valor_unitario, num_meses, descricao, justificativa) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $valorLimpo = $_POST['valor_unitario'] ?? '0';
            $valorLimpo = str_replace('.', '', $valorLimpo); 
            $valorLimpo = str_replace(',', '.', $valorLimpo); 
            $valorFloat = floatval($valorLimpo);

            $mesesInput = trim($_POST['num_meses'] ?? '');
            $numMeses = ($mesesInput !== '') ? intval($mesesInput) : null;

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['dispendio'] ?? '',
                $_POST['classificacao'] ?? 'Custeio',
                $_POST['etapas_vinculadas'] ?? '',
                $_POST['origem_recurso'] ?? 'Concedente',
                isset($_POST['importado_exterior']) ? 1 : 0,
                intval($_POST['quantidade'] ?? 1),
                $valorFloat,
                $numMeses,
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

    // =========================================================================
    // 4. MODULO: METAS (Adicionar e Remover)
    // =========================================================================
    if ($modulo === 'metas_add') {
        $descricao = trim($_POST['descricao'] ?? '');
        if (empty($descricao)) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'A descrição da meta não pode estar vazia.']);
            exit;
        }
        
        $sql = "INSERT INTO metas (descricao) VALUES (?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$descricao]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($modulo === 'metas_del') {
        $id = intval($_POST['id'] ?? 0);
        $sql = "DELETE FROM metas WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao deletar meta: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
    // 5. MODULO: ETAPAS (Adicionar e Remover)
    // =========================================================================
    if ($modulo === 'etapas_add') {
        $sql = "INSERT INTO etapas (meta_id, descricao_etapa, indicador_progresso, entregaveis, mes_inicio, mes_fim, peso, responsavel, executores) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                intval($_POST['meta_id'] ?? 0),
                $_POST['descricao_etapa'] ?? '',
                $_POST['indicador_progresso'] ?? '',
                $_POST['entregaveis'] ?? '',
                intval($_POST['mes_inicio'] ?? 1),
                intval($_POST['mes_fim'] ?? 1),
                intval($_POST['peso'] ?? 1),
                $_POST['responsavel'] ?? '',
                $_POST['executores'] ?? ''
            ]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao inserir etapa: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($modulo === 'etapas_del') {
        $id = intval($_POST['id'] ?? 0);
        $sql = "DELETE FROM etapas WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao deletar etapa: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
    // 6. MODULO: MEMBROS DA EQUIPE (Adicionar e Remover)
    // =========================================================================
    if ($modulo === 'membros_equipe_add') {
        $sql = "INSERT INTO membros_equipe (nome, lattes) VALUES (?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['nome'] ?? '',
                $_POST['lattes'] ?? ''
            ]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao inserir membro: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($modulo === 'membros_equipe_del') {
        $id = intval($_POST['id'] ?? 0);
        $sql = "DELETE FROM membros_equipe WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao deletar membro: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
   // =========================================================================
    // 7. MODULO: UPLOAD DE FORMULÁRIOS E ANEXOS (Atualizado)
    // =========================================================================
    if ($modulo === 'upload_anexo') {
        $codigo_anexo = $_POST['codigo_anexo'] ?? '';
        
        if (empty($codigo_anexo) || !isset($_FILES['arquivo_anexo']) || $_FILES['arquivo_anexo']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Arquivo inválido ou dados incompletos para upload.']);
            exit;
        }

        $fileTmpPath = $_FILES['arquivo_anexo']['tmp_name'];
        $fileName = $_FILES['arquivo_anexo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Valida extensões permitidas
        if (!in_array($fileExtension, ['docx', 'doc', 'pdf'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Apenas arquivos Word (.doc, .docx) ou PDF são aceitos.']);
            exit;
        }

        // Novo padrão sugerido por você: "anexo_i_enviado.docx"
        $nome_limpo = strtolower(str_replace(' ', '_', $codigo_anexo));
        $newFileName = $nome_limpo . '_enviado.' . $fileExtension;
        
        $uploadFileDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            try {
                // Atualiza o status e grava o NOME EXATO do arquivo enviado no banco
                $stmt = $pdo->prepare("UPDATE anexos_fapemig SET status_upload = 1, caminho_arquivo = ? WHERE codigo_anexo = ?");
                $stmt->execute([$newFileName, $codigo_anexo]);
                
                echo json_encode([
                    'status' => 'sucesso', 
                    'mensagem' => 'Upload realizado com sucesso!',
                    'arquivo_salvo' => $newFileName
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao mover o arquivo para a pasta uploads.']);
        }
        exit;
    }

// =========================================================================
    // 7.1. MODULO: REMOÇÃO DE ANEXO ENVIADO (Novo)
    // =========================================================================
    if ($modulo === 'upload_anexo_del') {
        $codigo_anexo = $_POST['codigo_anexo'] ?? '';

        if (empty($codigo_anexo)) {
            http_response_code(400);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
            exit;
        }

        try {
            // Busca o nome do arquivo para poder apagá-lo fisicamente
            $stmtFile = $pdo->prepare("SELECT caminho_arquivo FROM anexos_fapemig WHERE codigo_anexo = ?");
            $stmtFile->execute([$codigo_anexo]);
            $arquivo = $stmtFile->fetchColumn();

            if ($arquivo) {
                $filePath = __DIR__ . '/uploads/' . $arquivo;
                if (file_exists($filePath)) {
                    @unlink($filePath); // Remove o arquivo físico da pasta uploads
                }

                // Reseta o status e o caminho no banco de dados
                $stmt = $pdo->prepare("UPDATE anexos_fapemig SET status_upload = 0, caminho_arquivo = NULL WHERE codigo_anexo = ?");
                $stmt->execute([$codigo_anexo]);
            }

            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Arquivo removido com sucesso!']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao remover do banco: ' . $e->getMessage()]);
        }
        exit;
    }

    // =========================================================================
    // 7.2. MODULO: UPLOAD DO ANTEPROJETO (Novo)
    // =========================================================================
    if ($modulo === 'upload_anteprojeto') {
        if (!isset($_FILES['arquivo_anteprojeto']) || $_FILES['arquivo_anteprojeto']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Arquivo inválido para o anteprojeto.']);
            exit;
        }

        $fileTmpPath = $_FILES['arquivo_anteprojeto']['tmp_name'];
        $fileName = $_FILES['arquivo_anteprojeto']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if ($fileExtension !== 'pdf') {
            echo json_encode(['status' => 'erro', 'mensagem' => 'O anteprojeto precisa obrigatoriamente ser em formato PDF.']);
            exit;
        }

        $uploadFileDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        // Nome fixo conforme o seu padrão
        $dest_path = $uploadFileDir . 'anteprojeto.pdf';

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            echo json_encode([
                'status' => 'sucesso', 
                'mensagem' => 'Anteprojeto enviado com sucesso!'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao mover o anteprojeto para a pasta uploads.']);
        }
        exit;
    }

    // =========================================================================
    // 7.3. MODULO: REMOÇÃO DO ANTEPROJETO (Novo)
    // =========================================================================
    if ($modulo === 'upload_anteprojeto_del') {
        $filePath = __DIR__ . '/uploads/anteprojeto.pdf';
        
        if (file_exists($filePath)) {
            if (@unlink($filePath)) {
                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Anteprojeto removido com sucesso!']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'erro', 'mensagem' => 'Não foi possível deletar o arquivo físico.']);
            }
        } else {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'O arquivo já não existia no servidor.']);
        }
        exit;
    }

    // =========================================================================
    // 8. AÇÃO GENÉRICA: ATUALIZAÇÃO EM LOTE AUTOSAVE (Padrão: nome_tabela_update)
    // =========================================================================
    if (strpos($modulo, '_update') !== false) {
        $tabela = str_replace('_update', '', $modulo);
        
        $tabelasPermitidas = [
            'dados_bolsa', 
            'instituicoes', 
            'instituicao_destino', 
            'natureza_proposta', 
            'detalhamento_proposta'
        ];
        
        if (in_array($tabela, $tabelasPermitidas)) {
            $dadosSalvar = $_POST;
            unset($dadosSalvar['modulo']); 

            if (!empty($dadosSalvar)) {
                $setCampos = [];
                foreach ($dadosSalvar as $key => $value) {
                    $setCampos[] = "`$key` = :$key";
                }
                $sqlStr = implode(', ', $setCampos);

                try {
                    $check = $pdo->prepare("SELECT id FROM `$tabela` WHERE id = 1");
                    $check->execute();
                    if (!$check->fetch()) {
                        $pdo->query("INSERT INTO `$tabela` (id) VALUES (1)");
                    }

                    $stmt = $pdo->prepare("UPDATE `$tabela` SET $sqlStr WHERE id = 1");
                    $stmt->execute($dadosSalvar);
                    
                    echo json_encode(['status' => 'sucesso']);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no Autosave: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['status' => 'sem_dados']);
            }
            exit;
        }
    }

    // Retorno para caso a ação não mapeie com nenhum bloco acima
    http_response_code(404);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Ação/Módulo inválido ou não mapeado.']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método inválido. Use POST.']);
    exit;
}