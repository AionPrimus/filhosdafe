<?php
if (($_GET['key'] ?? '') !== 'fix_id_7781') {
    die("Acesso negado.");
}
require_once __DIR__ . '/gestor/conexao.php';
if (!isset($conn) || !$conn) {
    die("Erro conexao PDO");
}

try {
    $conn->exec("ALTER TABLE `cadastro` MODIFY COLUMN `id_cadastro` int(11) NOT NULL AUTO_INCREMENT");
    $maxId = $conn->query("SELECT MAX(id_cadastro) FROM cadastro")->fetchColumn();
    $nextId = max(1, intval($maxId) + 1);
    $conn->exec("ALTER TABLE `cadastro` AUTO_INCREMENT = $nextId");

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Tabela cadastro corrigida para INT(11) com sucesso no banco online!',
        'max_id' => $maxId,
        'next_id' => $nextId
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
