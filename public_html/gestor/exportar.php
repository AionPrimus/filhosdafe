<?php
// =====================================================
// Filhos da Fé — gestor/exportar.php
// Exporta todos os cadastros para CSV
// =====================================================
@ini_set('memory_limit', '256M');
if (session_status() === PHP_SESSION_NONE) session_start();
include_once 'conexao.php';

// Fetch all members
$stmt = $conn->query("SELECT * FROM cadastro ORDER BY id_cadastro DESC");
$membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nome do arquivo
$filename = 'membros_filhosdafe_' . date('Ymd_His') . '.csv';

// Headers para forçar download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Output stream
$output = fopen('php://output', 'w');

// Adiciona BOM para o Excel ler acentos em UTF-8 corretamente
fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Cabeçalhos
$colunas = [
    'Matricula', 'Líder', 'Nome Completo', 'Nascimento', 'CPF', 'RG', 
    'Título Eleitor', 'Zona', 'Seção',
    'Nome Religioso', 'Cargo/Função', 'Telefone', 'Email', 
    'Situação Cadastral'
];
fputcsv($output, $colunas, ';');

foreach ($membros as $m) {
    fputcsv($output, [
        $m['matricula_cadastro'],
        $m['nome_lider'] ?? '',
        $m['nome_cadastro'],
        $m['nascimento_cadastro'],
        $m['cpf_cadastro'],
        $m['rg_cadastro'],
        $m['titulo_eleitor'] ?? '',
        $m['zona_eleitoral'] ?? '',
        $m['secao_eleitoral'] ?? '',
        $m['nome_religioso'],
        $m['cargo_funcao_cadastro'],
        $m['tel_celular'],
        $m['email_principal'],
        $m['situacao_cadastral']
    ], ';');
}

fclose($output);
exit();
