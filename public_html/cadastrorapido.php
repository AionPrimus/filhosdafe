<?php
// =====================================================
// PAULO HENRIQUE 30220 — Candidato a Deputado Estadual
// Captação de Apoiadores
// =====================================================
require_once __DIR__ . '/gestor/conexao.php';

// Endpoint AJAX: Busca de Líderes em tempo real
if (isset($_GET['action']) && $_GET['action'] === 'busca_lideres') {
    header('Content-Type: application/json; charset=utf-8');
    $q = trim($_GET['q'] ?? '');
    try {
        if ($q !== '') {
            $stmt = $conn->prepare("SELECT DISTINCT nome_lider FROM cadastro WHERE nome_lider IS NOT NULL AND nome_lider != '' AND nome_lider LIKE ? ORDER BY nome_lider ASC LIMIT 10");
            $stmt->execute(['%' . $q . '%']);
        } else {
            $stmt = $conn->query("SELECT DISTINCT nome_lider FROM cadastro WHERE nome_lider IS NOT NULL AND nome_lider != '' ORDER BY nome_lider ASC LIMIT 10");
        }
        $lideres = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($lideres, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([]);
    }
    exit;
}

$sucesso = false;
$erro = '';
$dadosEnviados = null;

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_lider    = mb_strtoupper(trim($_POST['nome_lider'] ?? ''), 'UTF-8');
    $nome_cadastro = mb_strtoupper(trim($_POST['nome_cadastro'] ?? ''), 'UTF-8');
    $tel_celular   = trim($_POST['tel_celular'] ?? '');

    if (empty($nome_lider)) {
        $erro = 'Por favor, informe ou selecione o Nome da Liderança.';
    } elseif (empty($nome_cadastro)) {
        $erro = 'Por favor, preencha o Nome Completo do Apoiador.';
    } elseif (empty($tel_celular)) {
        $erro = 'Por favor, informe o Telefone / WhatsApp do Apoiador.';
    } else {
        try {
            $datatime = date('d/m/Y - H:i:s');
            $ip_cadastro = $_SERVER['REMOTE_ADDR'] ?? '';
            $matricula_cadastro = date('dmYHis');
            $situacao_cadastral = 'AGUARDANDO';
            $nome_casa = 'PAULO HENRIQUE 30220';
            $nome_dirigente = 'PAULO HENRIQUE';
            $telefone_casa = '';

            $sql = "INSERT INTO cadastro (
                datatime, ip_cadastro, matricula_cadastro, situacao_cadastral,
                nome_lider, nome_cadastro, tel_celular,
                nome_casa, nome_dirigente, telefone_casa
            ) VALUES (
                :datatime, :ip_cadastro, :matricula_cadastro, :situacao_cadastral,
                :nome_lider, :nome_cadastro, :tel_celular,
                :nome_casa, :nome_dirigente, :telefone_casa
            )";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':datatime'           => $datatime,
                ':ip_cadastro'        => $ip_cadastro,
                ':matricula_cadastro' => $matricula_cadastro,
                ':situacao_cadastral' => $situacao_cadastral,
                ':nome_lider'         => $nome_lider,
                ':nome_cadastro'      => $nome_cadastro,
                ':tel_celular'        => $tel_celular,
                ':nome_casa'          => $nome_casa,
                ':nome_dirigente'     => $nome_dirigente,
                ':telefone_casa'      => $telefone_casa
            ]);

            $sucesso = true;
            $dadosEnviados = [
                'nome'      => $nome_cadastro,
                'lider'     => $nome_lider,
                'telefone'  => $tel_celular,
                'matricula' => $matricula_cadastro
            ];
        } catch (Exception $e) {
            $erro = 'Erro ao registrar cadastro. Por favor, tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAULO HENRIQUE 30220 — Candidato a Deputado Estadual</title>
    <link rel="icon" href="ico.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --primary-glow: rgba(139, 92, 246, 0.35);
            --accent: #10b981;
            --accent-glow: rgba(16, 185, 129, 0.35);
            --bg-dark: #090d16;
            --card-bg: rgba(20, 27, 45, 0.85);
            --card-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.5) 0px, transparent 100%);
            background-attachment: fixed;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
        }

        .container {
            width: 100%;
            max-width: 520px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 36px 32px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 40px var(--primary-glow);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #8b5cf6, #3b82f6, #10b981);
        }

        .header-brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-badge {
            width: 76px;
            height: 76px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.25), rgba(16, 185, 129, 0.25));
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .logo-badge i {
            font-size: 36px;
            background: linear-gradient(135deg, #a78bfa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .notice-banner {
            background: rgba(139, 92, 246, 0.12);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #c4b5fd;
            line-height: 1.4;
        }

        .notice-banner i {
            font-size: 20px;
            color: #a78bfa;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .form-label span.badge-hint {
            font-size: 11px;
            font-weight: 500;
            color: #a78bfa;
            background: rgba(139, 92, 246, 0.15);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #64748b;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 14px 16px 14px 46px;
            color: #ffffff;
            font-size: 15px;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            background: rgba(15, 23, 42, 0.95);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.2);
        }

        .form-control:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: #a78bfa;
        }

        /* Sugestões de Autocomplete */
        .suggestions-box {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
            max-height: 220px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 13px;
            color: #f1f5f9;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.15s;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover, .suggestion-item.active {
            background: rgba(139, 92, 246, 0.25);
            color: #ffffff;
        }

        .suggestion-item i {
            color: #a78bfa;
            font-size: 12px;
        }

        .suggestion-help {
            padding: 8px 14px;
            font-size: 11px;
            color: #94a3b8;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border: none;
            border-radius: 14px;
            padding: 16px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.3px;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.4);
            transition: all 0.25s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(124, 58, 237, 0.55);
            background: linear-gradient(135deg, #9061f9, #6d28d9);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fca5a5;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Tela de Sucesso e Agradecimento */
        .success-box {
            text-align: center;
            padding: 10px 0;
        }

        .success-icon-wrapper {
            width: 86px;
            height: 86px;
            background: rgba(16, 185, 129, 0.15);
            border: 2px solid rgba(16, 185, 129, 0.4);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 0 35px var(--accent-glow);
            animation: pulseGlow 2s infinite alternate;
        }

        .success-icon-wrapper i {
            font-size: 42px;
            color: #10b981;
        }

        .thank-you-badge {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(139, 92, 246, 0.15));
            border: 1px solid rgba(16, 185, 129, 0.35);
            border-radius: 20px;
            padding: 24px 20px;
            margin: 24px 0;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .thank-you-title {
            font-family: 'Outfit', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #34d399;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .candidate-tag {
            display: inline-block;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 18px;
            padding: 6px 18px;
            border-radius: 9999px;
            letter-spacing: 1.5px;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .thank-you-msg {
            font-size: 14px;
            color: #e2e8f0;
            line-height: 1.6;
            margin-top: 14px;
        }

        .status-badge-box {
            background: rgba(255,255,255,0.03);
            border: 1px dashed rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 22px;
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-new {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            padding: 14px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-new:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-top: 24px;
        }

        @keyframes pulseGlow {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        @media (max-width: 480px) {
            .card {
                padding: 28px 20px;
                border-radius: 20px;
            }
            h1 {
                font-size: 21px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">

        <?php if ($sucesso): ?>
            <!-- Tela de Sucesso com Agradecimento Especial -->
            <div class="success-box">
                <div class="success-icon-wrapper">
                    <i class="fa fa-check"></i>
                </div>

                <h1 style="font-size: 25px; margin-bottom: 6px;">Cadastro Enviado com Sucesso!</h1>
                <p class="subtitle">Os dados do apoiador <strong><?= htmlspecialchars($dadosEnviados['nome']) ?></strong> foram recebidos com sucesso.</p>

                <div class="thank-you-badge">
                    <div class="thank-you-title">
                        <i class="fa fa-handshake"></i> AGRADECIMENTO ESPECIAL
                    </div>
                    <div class="candidate-tag">
                        PAULO HENRIQUE 30220
                    </div>
                    <p class="thank-you-msg">
                        Agradecemos de coração pelo seu apoio e confiança nesta caminhada!
                    </p>
                </div>

                <div class="status-badge-box">
                    <span>
                        <i class="fa fa-clock" style="color: #f59e0b; margin-right: 6px;"></i>
                        Situação: <strong style="color: #f59e0b;">AGUARDANDO</strong>
                    </span>
                    <span>
                        Matrícula: <strong style="font-family: monospace; color:#fff;"><?= $dadosEnviados['matricula'] ?></strong>
                    </span>
                </div>

                <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'cadastrorapido.php') ?>" class="btn-new">
                    <i class="fa fa-user-plus"></i> Fazer Outro Cadastro de Apoiador
                </a>
            </div>

        <?php else: ?>

            <!-- Cabeçalho -->
            <div class="header-brand">
                <div class="logo-badge">
                    <i class="fa fa-bullhorn"></i>
                </div>
                <h1>PAULO HENRIQUE 30220</h1>
                <p class="subtitle">Candidato a Deputado Estadual &bull; Captação de Apoiadores</p>
            </div>

            <!-- Banner de Aviso de Preenchimento -->
            <div class="notice-banner">
                <i class="fa fa-circle-info"></i>
                <div>
                    <strong>Instruções de Preenchimento:</strong><br>
                    &bull; <strong>Nome da Liderança (Líder):</strong> quem indicou ou fez o convite.<br>
                    &bull; <strong>Nome e Telefone:</strong> são os dados pessoais do <strong>APOIADOR</strong>.
                </div>
            </div>

            <?php if ($erro): ?>
                <div class="alert-error">
                    <i class="fa fa-triangle-exclamation"></i>
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <form method="POST" action="" id="formRapido" autocomplete="off">

                <!-- 1. Nome da Liderança (Líder) com AJAX / Autocomplete -->
                <div class="form-group">
                    <label class="form-label" for="inputLider">
                        <span><i class="fa fa-user-shield" style="margin-right:6px; color:#a78bfa;"></i>Nome da Liderança (Líder)</span>
                        <span class="badge-hint">Liderança / Indicação</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="text" 
                               id="inputLider" 
                               name="nome_lider" 
                               class="form-control" 
                               placeholder="Digite o nome da liderança que te indicou..." 
                               value="<?= htmlspecialchars($_POST['nome_lider'] ?? '') ?>"
                               required
                               autocomplete="off">
                        <i class="fa fa-users input-icon"></i>
                        <div class="suggestions-box" id="suggestionsBox"></div>
                    </div>
                    <div style="font-size: 11.5px; color: #94a3b8; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                        <i class="fa fa-circle-info" style="color: #a78bfa;"></i> Informe o nome da liderança responsável pelo convite.
                    </div>
                </div>

                <!-- 2. Nome Completo do Apoiador -->
                <div class="form-group">
                    <label class="form-label" for="inputNome">
                        <span><i class="fa fa-user" style="margin-right:6px; color:#38bdf8;"></i>Nome Completo do Apoiador</span>
                        <span class="badge-hint">Dados do Apoiador</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="text" 
                               id="inputNome" 
                               name="nome_cadastro" 
                               class="form-control" 
                               placeholder="Ex: Nome Completo do Apoiador" 
                               value="<?= htmlspecialchars($_POST['nome_cadastro'] ?? '') ?>"
                               required>
                        <i class="fa fa-id-card input-icon"></i>
                    </div>
                    <div style="font-size: 11.5px; color: #94a3b8; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                        <i class="fa fa-circle-info" style="color: #38bdf8;"></i> Nome completo do apoiador que está sendo cadastrado.
                    </div>
                </div>

                <!-- 3. Telefone / WhatsApp do Apoiador -->
                <div class="form-group">
                    <label class="form-label" for="inputTel">
                        <span><i class="fa-brands fa-whatsapp" style="margin-right:6px; color:#34d399;"></i>Telefone / WhatsApp do Apoiador</span>
                        <span class="badge-hint">Contato do Apoiador</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="tel" 
                               id="inputTel" 
                               name="tel_celular" 
                               class="form-control" 
                               placeholder="(62) 99999-9999" 
                               value="<?= htmlspecialchars($_POST['tel_celular'] ?? '') ?>"
                               maxlength="15"
                               required>
                        <i class="fa fa-phone input-icon"></i>
                    </div>
                    <div style="font-size: 11.5px; color: #94a3b8; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                        <i class="fa fa-circle-info" style="color: #34d399;"></i> Telefone ou WhatsApp de contato direto do apoiador (com DDD).
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fa fa-paper-plane"></i> Enviar Cadastro do Apoiador
                </button>
            </form>

        <?php endif; ?>

        <div class="footer-note">
            PAULO HENRIQUE 30220 &bull; Candidato a Deputado Estadual
        </div>

    </div>
</div>

<script>
// 1. Máscara de Telefone/WhatsApp (XX) XXXXX-XXXX
const inputTel = document.getElementById('inputTel');
if (inputTel) {
    inputTel.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length > 11) v = v.substring(0, 11);
        
        if (v.length > 10) {
            e.target.value = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        } else if (v.length > 5) {
            e.target.value = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
        } else if (v.length > 2) {
            e.target.value = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
        } else if (v.length > 0) {
            e.target.value = v.replace(/^(\d{0,2})$/, '($1');
        } else {
            e.target.value = '';
        }
    });
}

// 2. Autocomplete com AJAX para o Nome do Líder
const inputLider = document.getElementById('inputLider');
const suggestionsBox = document.getElementById('suggestionsBox');
let debounceTimer = null;

if (inputLider && suggestionsBox) {
    function fetchLideres(query = '') {
        fetch(`?action=busca_lideres&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data && data.length > 0) {
                    data.forEach(nome => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.innerHTML = `<i class="fa fa-user-check"></i> <span>${nome}</span>`;
                        div.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            inputLider.value = nome;
                            suggestionsBox.style.display = 'none';
                        });
                        suggestionsBox.appendChild(div);
                    });
                    const help = document.createElement('div');
                    help.className = 'suggestion-help';
                    help.textContent = 'Ou continue digitando para cadastrar um novo líder';
                    suggestionsBox.appendChild(help);
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.style.display = 'none';
                }
            })
            .catch(() => {
                suggestionsBox.style.display = 'none';
            });
    }

    inputLider.addEventListener('focus', function() {
        fetchLideres(inputLider.value.trim());
    });

    inputLider.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchLideres(inputLider.value.trim());
        }, 200);
    });

    inputLider.addEventListener('blur', function() {
        setTimeout(() => {
            suggestionsBox.style.display = 'none';
        }, 250);
    });
}

// 3. Efeito de carregamento no envio
const form = document.getElementById('formRapido');
if (form) {
    form.addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Gravando...';
    });
}
</script>

</body>
</html>
