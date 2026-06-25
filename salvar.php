<?php
// salvar.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modulo = $_POST['modulo'] ?? '';

    // Ação: Adicionar Membro da Equipe
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
            echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
        }
        exit;
    }

    // Ação: Remover Membro da Equipe
    if ($modulo === 'membros_equipe_del') {
        $sql = "DELETE FROM membros_equipe WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([intval($_POST['id'] ?? 0)]);
            echo json_encode(['status' => 'sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
        }
        exit;
    }

    // ... manter os outros blocos de outros módulos ...
}