<?php
// =====================================================
// Filhos da Fé — gestor/sucesso.php
// =====================================================
session_start();
$nome      = $_SESSION['nome_cadastro']      ?? 'Membro';
$matricula = $_SESSION['matricula_cadastro'] ?? '';
$cargo     = $_SESSION['cargo_funcao_cadastro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <link rel="icon" href="assets/faicon.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Realizado — Filhos da Fé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-page">
    <div class="card-glass login-card" style="text-align:center;max-width:480px;">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#25D366,#128c7e);border-radius:24px;display:inline-flex;align-items:center;justify-content:center;font-size:36px;box-shadow:0 8px 28px rgba(37,211,102,0.35);margin-bottom:20px;">
            <i class="fa fa-check" style="color:#fff;"></i>
        </div>
        <h1 style="font-size:22px;font-weight:700;margin-bottom:8px;color:#25D366;">Cadastro Realizado!</h1>
        <p style="color:var(--text-secondary);margin-bottom:24px;font-size:15px;">
            Bem-vindo(a) à Tenda Espírita Filhos da Fé!
        </p>
        <div style="background:rgba(37,211,102,0.08);border:1px solid rgba(37,211,102,0.2);border-radius:12px;padding:16px;margin-bottom:24px;text-align:left;">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">NOME</div>
            <div style="font-weight:700;font-size:15px;margin-bottom:12px;"><?= htmlspecialchars($nome) ?></div>
            <?php if ($matricula): ?>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">MATRÍCULA</div>
            <div style="font-family:monospace;font-size:13px;margin-bottom:12px;"><?= htmlspecialchars($matricula) ?></div>
            <?php endif; ?>
            <?php if ($cargo): ?>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">CARGO</div>
            <div style="font-size:13px;"><?= htmlspecialchars($cargo) ?></div>
            <?php endif; ?>
        </div>
        <p style="font-size:13px;color:var(--text-muted);margin-bottom:24px;">
            A secretaria da tenda verificará seus dados e entrará em contato pelo celular cadastrado com sua carteirinha de identificação.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="../index.php" style="color:var(--text-muted);text-decoration:none;font-size:13px;display:flex;align-items:center;gap:6px;">
                <i class="fa fa-arrow-left"></i> Voltar ao Site
            </a>
        </div>
    </div>
</div>
<script>
const tema = localStorage.getItem('tema') || 'dark';
document.documentElement.setAttribute('data-theme', tema);
</script>
<script src="assets/js/main.js"></script>
</body>
</html>
