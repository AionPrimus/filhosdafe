<?php
// =====================================================
// Filhos da Fé — gestor/importar.php
// Importação de Membros em CSV
// =====================================================
if (session_status() === PHP_SESSION_NONE) session_start();
include_once 'conexao.php';

$pageTitle = 'Importar Membros';
$pageIcon  = 'fa fa-file-import';
$pageDesc  = 'Faça o upload de uma planilha CSV para cadastrar ou atualizar membros';

?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <link rel="icon" href="assets/faicon.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — Filhos da Fé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .import-box { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:16px; padding:40px; box-shadow:0 8px 32px rgba(0,0,0,0.1); max-width: 600px; margin: 40px auto; text-align:center; }
        .upload-icon { font-size:48px; color:var(--accent); margin-bottom:20px; }
        .btn-submit { background:linear-gradient(135deg,#a855f7,#7c3aed); color:#fff; border:none; border-radius:12px; padding:14px 32px; font-size:15px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:10px; transition:0.2s; box-shadow:0 4px 15px rgba(168,85,247,0.3); margin-top:20px; }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(168,85,247,0.4); }
        .file-input-wrapper { margin-top: 20px; text-align: left; background: rgba(0,0,0,0.15); padding: 15px; border-radius: 12px; border: 1px solid var(--glass-border); }
        .file-input-wrapper input { color: var(--text-primary); width: 100%; }
        .instructions { text-align: left; font-size: 13px; color: var(--text-secondary); margin-top: 20px; background: rgba(168,85,247,0.08); padding: 15px; border-radius: 12px; border: 1px solid rgba(168,85,247,0.2); }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>
<div class="content-area" style="padding:24px;">

    <div class="import-box">
        <i class="fa fa-cloud-arrow-up upload-icon"></i>
        <h2 style="margin-bottom:10px;">Importação de Planilha</h2>
        <p style="color:var(--text-muted); font-size:14px;">Selecione um arquivo .CSV gerado pela nossa Exportação para importar novos membros ou atualizar existentes (pelo CPF).</p>
        
        <form action="exe_importar.php" method="POST" enctype="multipart/form-data">
            <div class="file-input-wrapper">
                <input type="file" name="arquivo_csv" accept=".csv" required>
            </div>
            
            <div class="instructions">
                <strong><i class="fa fa-circle-info"></i> Atenção:</strong><br>
                1. O arquivo precisa ser no formato <strong>.CSV</strong> separado por ponto-e-vírgula (;).<br>
                2. A primeira linha do arquivo deve conter os cabeçalhos.<br>
                3. Se o <strong>CPF</strong> já existir no banco, o cadastro atual será <strong>ATUALIZADO</strong>.<br>
                4. Se o CPF for novo, será <strong>CADASTRADO</strong> um novo membro.
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa fa-check"></i> Iniciar Importação
            </button>
        </form>
    </div>

</div>
</main>
</body>
</html>
