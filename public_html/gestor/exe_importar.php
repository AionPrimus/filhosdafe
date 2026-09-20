<?php
// =====================================================
// Filhos da Fé — gestor/exe_importar.php
// Processa o CSV de Importação
// =====================================================
@ini_set('memory_limit', '256M');
if (session_status() === PHP_SESSION_NONE) session_start();
include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['arquivo_csv'])) {
    die("Acesso inválido.");
}

$file = $_FILES['arquivo_csv'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    die("Erro no upload do arquivo.");
}

$filename = $file['tmp_name'];

// Abre o arquivo
$handle = fopen($filename, "r");
if ($handle !== FALSE) {
    
    // Pega os cabeçalhos (primeira linha)
    $headers = fgetcsv($handle, 1000, ";");
    // Remove o BOM (Byte Order Mark) do primeiro header, se existir
    if (isset($headers[0])) {
        $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
    }

    $qtd_inseridos = 0;
    $qtd_atualizados = 0;
    $qtd_erros = 0;

    // Loop nas linhas
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if (count($data) < 14) continue; // Linha inválida/curta

        // Mapear colunas do nosso exportar.php:
        // 0: Matricula, 1: Líder, 2: Nome Completo, 3: Nascimento, 4: CPF, 5: RG,
        // 6: Título, 7: Zona, 8: Seção, 9: Nome Religioso, 10: Cargo, 11: Tel, 
        // 12: Email, 13: Situação

        $matricula_cadastro = $data[0] ?? '';
        $nome_lider         = $data[1] ?? '';
        $nome_cadastro      = $data[2] ?? '';
        $nascimento         = $data[3] ?? '';
        $cpf                = $data[4] ?? '';
        $rg                 = $data[5] ?? '';
        $titulo             = $data[6] ?? '';
        $zona               = $data[7] ?? '';
        $secao              = $data[8] ?? '';
        $nome_religioso     = $data[9] ?? '';
        $cargo              = $data[10] ?? '';
        $tel                = $data[11] ?? '';
        $email              = $data[12] ?? '';
        $situacao           = $data[13] ?? 'AGUARDANDO';

        // Verifica se o CPF não é vazio, o CPF é nossa chave
        if (empty($cpf) || trim($cpf) == '') {
            // Se não tiver CPF, tentamos achar pela Matrícula, mas pra facilitar:
            $qtd_erros++;
            continue;
        }

        // Checar se já existe no DB
        $stmtCheck = $conn->prepare("SELECT id_cadastro FROM cadastro WHERE cpf_cadastro = ? LIMIT 1");
        $stmtCheck->execute([$cpf]);
        $existe = $stmtCheck->fetchColumn();

        if ($existe) {
            // ATUALIZAR
            $stmtUp = $conn->prepare("UPDATE cadastro SET 
                matricula_cadastro = ?,
                nome_lider = ?,
                nome_cadastro = ?,
                nascimento_cadastro = ?,
                rg_cadastro = ?,
                titulo_eleitor = ?,
                zona_eleitoral = ?,
                secao_eleitoral = ?,
                nome_religioso = ?,
                cargo_funcao_cadastro = ?,
                tel_celular = ?,
                email_principal = ?,
                situacao_cadastral = ?
                WHERE id_cadastro = ?
            ");
            $success = $stmtUp->execute([
                $matricula_cadastro, $nome_lider, $nome_cadastro, $nascimento,
                $rg, $titulo, $zona, $secao, $nome_religioso, $cargo, $tel, $email, $situacao, $existe
            ]);
            if ($success) $qtd_atualizados++; else $qtd_erros++;
        } else {
            // INSERIR NOVO
            $stmtIn = $conn->prepare("INSERT INTO cadastro 
                (datatime, situacao_cadastral, matricula_cadastro, nome_lider, nome_cadastro, nascimento_cadastro, cpf_cadastro, rg_cadastro, titulo_eleitor, zona_eleitoral, secao_eleitoral, nome_religioso, cargo_funcao_cadastro, tel_celular, email_principal) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $datatime = date('d/m/Y - H:i:s');
            $success = $stmtIn->execute([
                $datatime, $situacao, $matricula_cadastro, $nome_lider, $nome_cadastro, $nascimento,
                $cpf, $rg, $titulo, $zona, $secao, $nome_religioso, $cargo, $tel, $email
            ]);
            if ($success) $qtd_inseridos++; else $qtd_erros++;
        }
    }
    fclose($handle);

    echo "<script>
        alert('Importação Finalizada!\\n\\nCadastros Inseridos: {$qtd_inseridos}\\nCadastros Atualizados: {$qtd_atualizados}\\nErros ou ignorados: {$qtd_erros}');
        window.location = 'listar.php';
    </script>";

} else {
    echo "Erro ao ler o arquivo CSV.";
}
