<?php
if (($_GET['key'] ?? '') !== 'mig_cols_7789') {
    die("Acesso negado.");
}
require_once __DIR__ . '/gestor/conexao.php';
if (!isset($conn) || !$conn) {
    die("Erro conexao PDO");
}

$results = [];

$colsToAdd = [
    'nome_lider' => "ALTER TABLE `cadastro` ADD COLUMN `nome_lider` varchar(255) NULL AFTER `matricula_cadastro`",
    'titulo_eleitor' => "ALTER TABLE `cadastro` ADD COLUMN `titulo_eleitor` varchar(50) NULL AFTER `rg_cadastro`",
    'zona_eleitoral' => "ALTER TABLE `cadastro` ADD COLUMN `zona_eleitoral` varchar(50) NULL AFTER `titulo_eleitor`",
    'secao_eleitoral' => "ALTER TABLE `cadastro` ADD COLUMN `secao_eleitoral` varchar(50) NULL AFTER `zona_eleitoral`"
];

foreach ($colsToAdd as $col => $sql) {
    try {
        // Verifica se a coluna ja existe
        $chk = $conn->query("SHOW COLUMNS FROM `cadastro` LIKE '$col'")->fetchAll();
        if (empty($chk)) {
            $conn->exec($sql);
            $results[$col] = 'Adicionada com sucesso!';
        } else {
            $results[$col] = 'Ja existia.';
        }
    } catch (Exception $e) {
        $results[$col] = 'Erro: ' . $e->getMessage();
    }
}

// Testar a consulta do listar.php
try {
    $stmt = $conn->query("SELECT id_cadastro, matricula_cadastro, nome_cadastro, nome_casa, cargo_funcao_cadastro, tel_celular, situacao_cadastral, nome_lider FROM cadastro ORDER BY id_cadastro DESC LIMIT 5");
    $sample = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $conn->query("SELECT COUNT(*) FROM cadastro")->fetchColumn();
    $results['total_membros'] = $count;
    $results['amostra'] = $sample;
    $results['status'] = 'success';
} catch (Exception $e) {
    $results['status'] = 'error';
    $results['query_error'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
