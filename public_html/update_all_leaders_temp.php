<?php
if (($_GET['key'] ?? '') !== 'upd_ldr_9918') {
    die("Acesso negado.");
}
require_once __DIR__ . '/gestor/conexao.php';
if (!isset($conn) || !$conn) {
    die("Erro conexao PDO");
}

try {
    $stmt = $conn->prepare("UPDATE cadastro SET 
        nome_lider = 'WILLIAN GOMES', 
        nome_casa = 'TENDA ESPÍRITA FILHOS DA FÉ', 
        nome_dirigente = 'WILLIAN GOMES', 
        telefone_casa = '(62) 99882-8682'");
    $stmt->execute();
    $count = $stmt->rowCount();

    $sample = $conn->query("SELECT id_cadastro, nome_cadastro, nome_lider, nome_casa, nome_dirigente, telefone_casa FROM cadastro LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'updated_count' => $count,
        'sample' => $sample
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
