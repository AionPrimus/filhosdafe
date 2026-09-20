<?php
// =====================================================
// Filhos da Fé — gestor/edit_cadastro.php
// =====================================================
require_once __DIR__ . '/views/includes/_auth.php';
include_once 'conexao.php';

$id = intval($_GET['id_cadastro'] ?? 0);
$m  = [];

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM cadastro WHERE id_cadastro = ?");
        $stmt->execute([$id]);
        $m = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(Exception $e) {}
}

if (empty($m)) {
    echo '<script>alert("Membro não encontrado.");window.location="listar.php";</script>';
    exit;
}

$v = fn($field) => htmlspecialchars($m[$field] ?? '');

$pageTitle = 'Editar Membro';
$pageIcon  = 'fa fa-pen';
$pageDesc  = 'Editar dados de: ' . ($m['nome_cadastro'] ?? '');
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
        .form-section { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:16px; padding:24px; margin-bottom:20px; }
        .form-section-title { font-size:14px; font-weight:700; color:var(--accent); margin-bottom:18px; display:flex; align-items:center; gap:8px; padding-bottom:12px; border-bottom:1px solid var(--glass-border); }
        .form-grid-3 { display:grid; grid-template-columns: 1fr 1fr 1fr; gap:16px; }
        .form-grid-4 { display:grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap:16px; }
        .field-group { display:flex; flex-direction:column; gap:6px; }
        .field-label { font-size:12px; font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.4px; }
        .field-label .req { color:#ef4444; margin-left:2px; }
        .field-input { background:rgba(255,255,255,0.05); border:1px solid var(--glass-border); border-radius:10px; padding:10px 14px; color:var(--text-primary); font-size:14px; outline:none; transition:0.2s; width:100%; }
        .field-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(168,85,247,0.15); }
        .field-input::placeholder { color:var(--text-muted); }
        select.field-input { cursor:pointer; }
        .btn-submit { background:linear-gradient(135deg,#a855f7,#7c3aed); color:#fff; border:none; border-radius:12px; padding:14px 32px; font-size:15px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:10px; transition:0.2s; box-shadow:0 4px 15px rgba(168,85,247,0.3); }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(168,85,247,0.4); }
        .member-badge { display:inline-flex;align-items:center;gap:10px;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);border-radius:12px;padding:10px 16px;margin-bottom:20px; }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>
<div class="content-area" style="padding:24px;">

    <div class="member-badge">
        <i class="fa fa-id-card" style="color:#a855f7;font-size:20px;"></i>
        <div>
            <div style="font-weight:700;font-size:15px;"><?= $v('nome_cadastro') ?></div>
            <div style="font-size:12px;color:var(--text-muted);">Matrícula: <?= $v('matricula_cadastro') ?></div>
        </div>
    </div>

<form action="exe_edit_cadastro.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_cadastro" value="<?= $m['id_cadastro'] ?>">
    <input type="hidden" name="matricula_cadastro" value="<?= $v('matricula_cadastro') ?>">

    <!-- VÍNCULO E INSTITUIÇÃO -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-sitemap"></i> Vínculo e Instituição</div>
        <div class="form-grid-3">
            <div class="field-group" style="grid-column: span 3; background:rgba(168,85,247,0.1); padding:12px; border-radius:8px; border:1px solid rgba(168,85,247,0.3);">
                <label class="field-label" style="color:var(--text-primary); font-weight:bold;">NOME DO LÍDER RESPONSÁVEL <span class="req">*</span></label>
                <input type="text" class="field-input" name="nome_lider" value="<?= $v('nome_lider') ?>" required placeholder="Ex: WILLIAN GOMES" style="font-weight:bold; color:var(--accent);">
            </div>
            <div class="field-group" style="grid-column: span 3;">
                <label class="field-label">Casa / Tenda / Terreiro / Centro de Origem</label>
                <input type="text" class="field-input" name="nome_casa" value="<?= $v('nome_casa') ?>" placeholder="Nome da casa de origem (se houver)">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Nome do Dirigente da Casa</label>
                <input type="text" class="field-input" name="nome_dirigente" value="<?= $v('nome_dirigente') ?>" placeholder="Pai/Mãe de Santo ou Dirigente">
            </div>
            <div class="field-group">
                <label class="field-label">Telefone da Casa / Centro</label>
                <input type="text" class="field-input" name="telefone_casa" value="<?= $v('telefone_casa') ?>" placeholder="(00) 00000-0000">
            </div>
        </div>
    </div>

    <!-- DADOS PESSOAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-user"></i> Dados Pessoais</div>
        <div class="form-grid-3">
            <div class="field-group">
                <label class="field-label">Nome Completo <span class="req">*</span></label>
                <input type="text" class="field-input" name="nome_cadastro" value="<?= $v('nome_cadastro') ?>" required>
            </div>
            <div class="field-group">
                <label class="field-label">Data de Nascimento</label>
                <input type="text" class="field-input" name="nascimento_cadastro" value="<?= $v('nascimento_cadastro') ?>" maxlength="10">
            </div>
            <div class="field-group">
                <label class="field-label">CPF</label>
                <input type="text" class="field-input" name="cpf_cadastro" value="<?= $v('cpf_cadastro') ?>" maxlength="14">
            </div>
            <div class="field-group">
                <label class="field-label">RG</label>
                <input type="text" class="field-input" name="rg_cadastro" value="<?= $v('rg_cadastro') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Estado Civil</label>
                <select class="field-input" name="estado_civil">
                    <option value="">Selecione</option>
                    <?php foreach(['Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)','União Estável'] as $opt): ?>
                    <option <?= ($m['estado_civil'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Profissão</label>
                <input type="text" class="field-input" name="profissao" value="<?= $v('profissao') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Escolaridade</label>
                <select class="field-input" name="escolaridade">
                    <option value="">Selecione</option>
                    <?php foreach(['Fundamental Incompleto','Fundamental Completo','Médio Incompleto','Médio Completo','Superior Incompleto','Superior Completo','Pós-Graduação'] as $opt): ?>
                    <option <?= ($m['escolaridade'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Nome do Pai</label>
                <input type="text" class="field-input" name="nome_pai" value="<?= $v('nome_pai') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Nome da Mãe</label>
                <input type="text" class="field-input" name="nome_mae" value="<?= $v('nome_mae') ?>">
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;">
            <?php if (!empty($m['avatar'])): ?>
            <div style="text-align:center;">
                <img src="uploads/<?= htmlspecialchars($m['avatar']) ?>" style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid var(--glass-border);" onerror="this.src='assets/no-image.png'">
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Foto atual</div>
            </div>
            <?php endif; ?>
            <div class="field-group">
                <label class="field-label">Nova Foto (opcional)</label>
                <div style="display:flex; align-items:center; gap:16px;">
                    <img id="avatar-preview-edit" style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid var(--glass-border); display:none;" onerror="this.style.display='none'">
                    <input type="file" class="field-input" name="avatar" accept="image/*" style="padding:8px 12px;cursor:pointer;" onchange="previewAvatarEdit(this, 'avatar-preview-edit')">
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE PREVIEW -->
    <script>
    function previewAvatarEdit(input, imgId) {
        const img = document.getElementById(imgId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            img.style.display = 'none';
        }
    }
    </script>

    <!-- SITUAÇÃO CADASTRAL -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-circle-check"></i> Situação Cadastral</div>
        <div class="form-grid-3">
            <div class="field-group">
                <label class="field-label">Situação <span class="req">*</span></label>
                <select class="field-input" name="situacao_cadastral" required>
                    <?php foreach(['AGUARDANDO','Ativo','Inativo','Suspenso','Desligado'] as $opt): ?>
                    <option <?= ($m['situacao_cadastral'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- DADOS ESPIRITUAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-star"></i> Histórico Espiritual</div>
        <div class="form-grid-3">
            <div class="field-group">
                <label class="field-label">Nome Religioso</label>
                <input type="text" class="field-input" name="nome_religioso" value="<?= $v('nome_religioso') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Cargo / Função</label>
                <select class="field-input" name="cargo_funcao_cadastro">
                    <option value="">Selecione</option>
                    <?php foreach(['Médium','Cambono','Ogã','Filho de Santo','Colaborador','Visitante','Sacerdote','Sacerdotisa'] as $opt): ?>
                    <option <?= ($m['cargo_funcao_cadastro'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Chefe de Coroa</label>
                <input type="text" class="field-input" name="chefe_coroa" value="<?= $v('chefe_coroa') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Orixás</label>
                <input type="text" class="field-input" name="orixas" value="<?= $v('orixas') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Entidades</label>
                <input type="text" class="field-input" name="entidades" value="<?= $v('entidades') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Disponibilidade</label>
                <input type="text" class="field-input" name="disponibilidade" value="<?= $v('disponibilidade') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Batizado?</label>
                <select class="field-input" name="batizado">
                    <option value="">Selecione</option>
                    <?php foreach(['Sim','Não'] as $opt): ?>
                    <option <?= ($m['batizado'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Iniciado?</label>
                <select class="field-input" name="iniciado">
                    <option value="">Selecione</option>
                    <?php foreach(['Sim','Não'] as $opt): ?>
                    <option <?= ($m['iniciado'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Em desenvolvimento?</label>
                <select class="field-input" name="desenvolvimento">
                    <option value="">Selecione</option>
                    <?php foreach(['Sim','Não'] as $opt): ?>
                    <option <?= ($m['desenvolvimento'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Data de Apresentação</label>
                <input type="text" class="field-input" name="data_apresentacao" value="<?= $v('data_apresentacao') ?>" maxlength="10">
            </div>
            <div class="field-group">
                <label class="field-label">Data de Filiação</label>
                <input type="text" class="field-input" name="data_filiacao" value="<?= $v('data_filiacao') ?>" maxlength="10">
            </div>
        </div>
    </div>

    <!-- CONTATO E ENDEREÇO -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-address-book"></i> Contato e Endereço</div>
        <div class="form-grid-3">
            <div class="field-group">
                <label class="field-label">Celular</label>
                <input type="text" class="field-input" name="tel_celular" id="tel_celular" value="<?= $v('tel_celular') ?>" maxlength="16">
            </div>
            <div class="field-group">
                <label class="field-label">Telefone Residencial</label>
                <input type="text" class="field-input" name="tel_resicencial" value="<?= $v('tel_resicencial') ?>" maxlength="14">
            </div>
            <div class="field-group">
                <label class="field-label">Telefone Emergência</label>
                <input type="text" class="field-input" name="tel_emergencia" value="<?= $v('tel_emergencia') ?>" maxlength="16">
            </div>
            <div class="field-group" style="grid-column: span 3;">
                <label class="field-label">E-mail Principal</label>
                <input type="email" class="field-input" name="email_principal" value="<?= $v('email_principal') ?>">
            </div>
        </div>
        <hr style="border:0; border-top:1px solid rgba(255,255,255,0.05); margin:20px 0;">
        <div class="form-grid-4">
            <div class="field-group">
                <label class="field-label">CEP</label>
                <input type="text" class="field-input" name="cep_residencial" id="cep" value="<?= $v('cep_residencial') ?>" maxlength="9" onblur="pesquisacep(this.value);">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Logradouro</label>
                <input type="text" class="field-input" name="logradouro_residencial" id="rua" value="<?= $v('logradouro_residencial') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Número</label>
                <input type="text" class="field-input" name="numero_residencial" value="<?= $v('numero_residencial') ?>">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Bairro</label>
                <input type="text" class="field-input" name="bairro_residencial" id="bairro" value="<?= $v('bairro_residencial') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Complemento</label>
                <input type="text" class="field-input" name="complemento_residencial" value="<?= $v('complemento_residencial') ?>">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Cidade</label>
                <input type="text" class="field-input" name="cidade_residencial" id="cidade" value="<?= $v('cidade_residencial') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Estado</label>
                <input type="text" class="field-input" name="estado_residencial" id="uf" value="<?= $v('estado_residencial') ?>" maxlength="2">
            </div>
        </div>
    </div>

    <!-- DADOS ELEITORAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-id-card"></i> Dados Eleitorais</div>
        <div class="form-grid-3">
            <div class="field-group">
                <label class="field-label">Título de Eleitor</label>
                <input type="text" class="field-input" name="titulo_eleitor" value="<?= $v('titulo_eleitor') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Zona Eleitoral</label>
                <input type="text" class="field-input" name="zona_eleitoral" value="<?= $v('zona_eleitoral') ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Seção Eleitoral</label>
                <input type="text" class="field-input" name="secao_eleitoral" value="<?= $v('secao_eleitoral') ?>">
            </div>
        </div>
    </div>

    <!-- OBSERVAÇÕES -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-note-sticky"></i> Observações</div>
        <div class="field-group">
            <label class="field-label">Observações Gerais</label>
            <textarea class="field-input" name="observacoes" rows="4"><?= $v('observacoes') ?></textarea>
        </div>
    </div>

    <!-- AÇÕES -->
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;padding-bottom:20px;">
        <button type="submit" class="btn-submit">
            <i class="fa fa-floppy-disk"></i> Salvar Alterações
        </button>
        <a href="listar.php" style="color:var(--text-muted);text-decoration:none;font-size:14px;">
            <i class="fa fa-arrow-left"></i> Cancelar
        </a>
    </div>

</form>
</div>
</main>

<script>
function meu_callback(c) {
    if (!c.erro) {
        document.getElementById('rua').value    = c.logradouro || '';
        document.getElementById('bairro').value = c.bairro || '';
        document.getElementById('cidade').value = c.localidade || '';
        document.getElementById('uf').value     = c.uf || '';
    } else { alert("CEP não encontrado."); }
}
function pesquisacep(v) {
    const cep = v.replace(/\D/g,'');
    if (!cep || cep.length !== 8) return;
    const s = document.createElement('script');
    s.src = `https://viacep.com.br/ws/${cep}/json/?callback=meu_callback`;
    document.body.appendChild(s);
}
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    $('input[name="cpf_cadastro"]').mask('000.000.000-00', {reverse: true});
    $('input[name="cep_residencial"]').mask('00000-000');
    $('input[name="nascimento_cadastro"], input[name="data_apresentacao"], input[name="data_filiacao"]').mask('00/00/0000');
    
    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
    $('input[name="tel_celular"], input[name="tel_resicencial"], input[name="tel_emergencia"], input[name="telefone_casa"]').mask(SPMaskBehavior, spOptions);
});
</script>
</body>
</html>
