<?php
// =====================================================
// Filhos da Fé — gestor/login.php
// =====================================================
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/conexao.php';

if (!empty($_SESSION['admin_logado'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->execute([$usuario]);
        $userObj = $stmt->fetch(PDO::FETCH_ASSOC);

        $loginValido = false;
        $nomeAdmin = '';

        if ($userObj) {
            if (password_verify($senha, $userObj['senha_usuario']) || $senha === $userObj['senha_usuario']) {
                $loginValido = true;
                $nomeAdmin = $userObj['nome'];
            }
        }

        if (!$loginValido) {
            $stmtCad = $conn->prepare("SELECT * FROM cadastro WHERE (login = ? OR email_principal = ?) LIMIT 1");
            $stmtCad->execute([$usuario, $usuario]);
            $cadObj = $stmtCad->fetch(PDO::FETCH_ASSOC);
            if ($cadObj && ($cadObj['senha'] === $senha || password_verify($senha, $cadObj['senha']))) {
                $loginValido = true;
                $nomeAdmin = $cadObj['nome_cadastro'];
            }
        }

        if ($loginValido) {
            session_regenerate_id(true);
            $_SESSION['admin_logado'] = true;
            $_SESSION['admin_nome']   = $nomeAdmin;
            $_SESSION['login']        = $usuario;
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Usuário ou senha incorretos.';
        }
    } catch(Exception $e) {
        $erro = 'Erro ao conectar no banco de dados.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <link rel="icon" href="assets/faicon.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Filhos da Fé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .brand-icon-login {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #a855f7, #7c3aed);
            border-radius: 24px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 34px;
            box-shadow: 0 8px 28px rgba(168,85,247,0.35);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<button class="theme-toggle" id="btnTema" style="position:absolute; top:20px; right:20px; background:var(--glass-bg); color:var(--text-primary); border:1px solid var(--glass-border); border-radius:12px; padding:10px 14px; cursor:pointer; font-size:16px; box-shadow:0 4px 15px rgba(0,0,0,0.1); z-index:999; transition:0.3s;" title="Alternar Tema">
    <i class="fa fa-moon"></i>
</button>
<div class="login-page">
    <div class="card-glass login-card">
        <div style="text-align:center; margin-bottom:32px;">
            <div class="brand-icon-login">
                <i class="fa fa-star" style="color:#fff;"></i>
            </div>
            <h1 style="font-size:22px;font-weight:700;margin-bottom:4px;">Filhos da Fé</h1>
            <p style="color:var(--text-muted);font-size:14px;">Tenda Espírita — Área Administrativa</p>
        </div>

        <?php if ($erro): ?>
        <div class="alert-custom alert-danger" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;margin-bottom:20px;color:#ef4444;font-size:13px;">
            <i class="fa fa-circle-exclamation"></i>
            <?= htmlspecialchars($erro) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label" for="usuario">
                    <i class="fa fa-user" style="margin-right:6px;color:var(--accent);"></i>Usuário
                </label>
                <input type="text" id="usuario" name="usuario" class="form-control-custom"
                       placeholder="admin"
                       value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="senha">
                    <i class="fa fa-lock" style="margin-right:6px;color:var(--accent);"></i>Senha
                </label>
                <input type="password" id="senha" name="senha" class="form-control-custom"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-primary-custom" style="width:100%;justify-content:center;margin-top:16px;background:linear-gradient(135deg,#a855f7,#7c3aed);box-shadow:0 4px 15px rgba(168,85,247,0.3);">
                <i class="fa fa-right-to-bracket"></i> Entrar
            </button>
        </form>

        <p style="text-align:center;margin-top:24px;font-size:12px;color:var(--text-muted);">
            <i class="fa fa-shield-halved" style="color:var(--accent);margin-right:4px;"></i>
            Acesso restrito a administradores
        </p>
    </div>
</div>
<script>
    const tema = localStorage.getItem('tema') || 'dark';
    document.documentElement.setAttribute('data-theme', tema);
</script>
<script src="assets/js/main.js"></script>
</body>
</html>
