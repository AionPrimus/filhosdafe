<?php
if (($_GET['key'] ?? '') !== 'update_pwd_7789') {
    die("Acesso negado.");
}
require_once __DIR__ . '/gestor/conexao.php';
if (!isset($conn) || !$conn) {
    die("Erro conexao PDO");
}

try {
    $hash = password_hash('magico21', PASSWORD_DEFAULT);

    // 1. Inserir ou atualizar na tabela usuarios
    $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE usuario = 'wgomes'");
    $stmtCheck->execute();
    if ($row = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $stmtUp = $conn->prepare("UPDATE usuarios SET senha_usuario = ?, nome = 'Willian da Silva Gomes' WHERE usuario = 'wgomes'");
        $stmtUp->execute([$hash]);
    } else {
        $stmtIn = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha_usuario) VALUES ('Willian da Silva Gomes', 'wgomes', ?)");
        $stmtIn->execute([$hash]);
    }

    // 2. Atualizar tambem o usuario aion com magico21
    $stmtAion = $conn->prepare("UPDATE usuarios SET senha_usuario = ? WHERE usuario = 'aion'");
    $stmtAion->execute([$hash]);

    // 3. Atualizar na tabela cadastro (se existir registro do Willian)
    $conn->exec("UPDATE cadastro SET login = 'wgomes', senha = 'magico21' WHERE nome_cadastro LIKE '%WILLIAN%'");

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Usuario wgomes configurado com a senha magico21 com sucesso!'
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
