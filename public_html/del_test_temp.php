<?php
if (($_GET['key'] ?? '') !== 'del_test_7781') die();
require_once __DIR__ . '/gestor/conexao.php';
$conn->exec("DELETE FROM cadastro WHERE nome_cadastro = 'MARCOS TESTE ONLINE'");
echo "DELETED_OK";
