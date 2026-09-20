<?php
// =====================================================
// Filhos da Fé — gestor/cadastro.php
// =====================================================
require_once __DIR__ . '/views/includes/_auth.php';

date_default_timezone_set('America/Sao_Paulo');
$dataa = date('d/m/Y');
$hora = date('H'); $minutos = date('i'); $segundos = date('s');
$time = "$hora:$minutos:$segundos";
$ip = $_SERVER["REMOTE_ADDR"];
$matricula_cadastro = $dataa.$time;
$matricula_cadastro = preg_replace('/[\/: ]/', '', $matricula_cadastro);

$pageTitle = 'Novo Cadastro';
$pageIcon  = 'fa fa-user-plus';
$pageDesc  = 'Preencha os dados para cadastrar um novo membro';
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
        .form-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; }
        .form-grid-2 { grid-template-columns: 1fr 1fr; }
        .form-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-grid-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
        .field-group { display:flex; flex-direction:column; gap:6px; }
        .field-label { font-size:12px; font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.4px; }
        .field-label .req { color:#ef4444; margin-left:2px; }
        .field-input { background:rgba(255,255,255,0.05); border:1px solid var(--glass-border); border-radius:10px; padding:10px 14px; color:var(--text-primary); font-size:14px; outline:none; transition:0.2s; width:100%; }
        .field-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(168,85,247,0.15); }
        .field-input::placeholder { color:var(--text-muted); }
        select.field-input { cursor:pointer; }
        .btn-submit { background:linear-gradient(135deg,#a855f7,#7c3aed); color:#fff; border:none; border-radius:12px; padding:14px 32px; font-size:15px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:10px; transition:0.2s; box-shadow:0 4px 15px rgba(168,85,247,0.3); }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(168,85,247,0.4); }
        .cpf-valid { color:#25D366; font-size:11px; }
        .cpf-invalid { color:#ef4444; font-size:11px; }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>
<div class="content-area" style="padding:24px;">

<div style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:13px;color:var(--text-secondary);">
    <i class="fa fa-circle-info" style="color:#a855f7;margin-right:8px;"></i>
    Campos marcados com <span style="color:#ef4444;font-weight:bold;">*</span> são obrigatórios. Tenha em mãos uma foto de rosto para o documento de identificação.
</div>

<form name="form1" action="exe_cadastro.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="datatime" value="<?= "$dataa - $time" ?>">
    <input type="hidden" name="ip_cadastro" value="<?= $ip ?>">
    <input type="hidden" name="situacao_cadastral" value="AGUARDANDO">
    <input type="hidden" name="instituicao" value="TENDA ESPÍRITA FILHOS DA FÉ">
    <input type="hidden" name="matricula_cadastro" value="<?= $matricula_cadastro ?>">

    <!-- VÍNCULO E INSTITUIÇÃO -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-sitemap"></i> Vínculo e Instituição</div>
        <div class="form-grid form-grid-3">
            <div class="field-group" style="grid-column: span 3; background:rgba(168,85,247,0.1); padding:12px; border-radius:8px; border:1px solid rgba(168,85,247,0.3);">
                <label class="field-label" style="color:var(--text-primary); font-weight:bold;">NOME DO LÍDER RESPONSÁVEL <span class="req">*</span></label>
                <input type="text" class="field-input" name="nome_lider" required placeholder="Ex: WILLIAN GOMES" style="font-weight:bold; color:var(--accent);">
            </div>
            <div class="field-group" style="grid-column: span 3;">
                <label class="field-label">Casa / Tenda / Terreiro / Centro de Origem</label>
                <input type="text" class="field-input" name="nome_casa" placeholder="Nome da casa de origem (se houver)">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Nome do Dirigente da Casa</label>
                <input type="text" class="field-input" name="nome_dirigente" placeholder="Pai/Mãe de Santo ou Dirigente">
            </div>
            <div class="field-group">
                <label class="field-label">Telefone da Casa / Centro</label>
                <input type="text" class="field-input" name="telefone_casa" placeholder="(00) 00000-0000">
            </div>
        </div>
    </div>

    <!-- DADOS PESSOAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-user"></i> Dados Pessoais</div>
        <div class="form-grid form-grid-3">
            <div class="field-group">
                <label class="field-label">Nome Completo <span class="req">*</span></label>
                <input type="text" class="field-input" name="nome_cadastro" required placeholder="Nome completo">
            </div>
            <div class="field-group">
                <label class="field-label">Data de Nascimento <span class="req">*</span></label>
                <input type="text" class="field-input" name="nascimento_cadastro" id="nascimento" maxlength="10" placeholder="DD/MM/AAAA" required>
            </div>
            <div class="field-group">
                <label class="field-label">CPF <span class="req">*</span> <span id="cpfResponse"></span></label>
                <input type="text" class="field-input" name="cpf_cadastro" id="cpf" maxlength="14" placeholder="000.000.000-00" onkeyup="mCPF(this); cpfCheck(this);" required>
            </div>
            <div class="field-group">
                <label class="field-label">RG</label>
                <input type="text" class="field-input" name="rg_cadastro" placeholder="Número do RG">
            </div>
            <div class="field-group">
                <label class="field-label">Estado Civil</label>
                <select class="field-input" name="estado_civil">
                    <option value="">Selecione</option>
                    <option>Solteiro(a)</option><option>Casado(a)</option>
                    <option>Divorciado(a)</option><option>Viúvo(a)</option><option>União Estável</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Profissão</label>
                <input type="text" class="field-input" name="profissao" placeholder="Profissão">
            </div>
            <div class="field-group">
                <label class="field-label">Escolaridade</label>
                <select class="field-input" name="escolaridade">
                    <option value="">Selecione</option>
                    <option>Fundamental Incompleto</option><option>Fundamental Completo</option>
                    <option>Médio Incompleto</option><option>Médio Completo</option>
                    <option>Superior Incompleto</option><option>Superior Completo</option>
                    <option>Pós-Graduação</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Nome do Pai</label>
                <input type="text" class="field-input" name="nome_pai" placeholder="Nome do pai">
            </div>
            <div class="field-group">
                <label class="field-label">Nome da Mãe</label>
                <input type="text" class="field-input" name="nome_mae" placeholder="Nome da mãe">
            </div>
        </div>
        <div style="margin-top:16px;">
            <div class="field-group" style="max-width:300px;">
                <label class="field-label">Foto do Membro</label>
                <div style="display:flex; align-items:center; gap:16px;">
                    <img id="avatar-preview" style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid var(--glass-border); display:none;" onerror="this.style.display='none'">
                    <input type="file" class="field-input" name="avatar" accept="image/*" style="padding:8px 12px;cursor:pointer;" onchange="previewAvatar(this, 'avatar-preview')">
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE PREVIEW -->
    <script>
    function previewAvatar(input, imgId) {
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

    <!-- DADOS ESPIRITUAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-star"></i> Histórico Espiritual</div>
        <div class="form-grid form-grid-3">
            <div class="field-group">
                <label class="field-label">Nome Religioso</label>
                <input type="text" class="field-input" name="nome_religioso" placeholder="Nome na religião">
            </div>
            <div class="field-group">
                <label class="field-label">Cargo / Função <span class="req">*</span></label>
                <select class="field-input" name="cargo_funcao_cadastro" required>
                    <option value="">Selecione</option>
                    <option>Médium</option><option>Cambono</option><option>Ogã</option>
                    <option>Filho de Santo</option><option>Colaborador</option><option>Visitante</option>
                    <option>Sacerdote</option><option>Sacerdotisa</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Chefe de Coroa</label>
                <input type="text" class="field-input" name="chefe_coroa" placeholder="Orixá de cabeça">
            </div>
            <div class="field-group">
                <label class="field-label">Orixás</label>
                <input type="text" class="field-input" name="orixas" placeholder="Seus orixás">
            </div>
            <div class="field-group">
                <label class="field-label">Entidades</label>
                <input type="text" class="field-input" name="entidades" placeholder="Entidades">
            </div>
            <div class="field-group">
                <label class="field-label">Disponibilidade</label>
                <input type="text" class="field-input" name="disponibilidade" placeholder="Ex: Sábados e Domingos">
            </div>
            <div class="field-group">
                <label class="field-label">Batizado?</label>
                <select class="field-input" name="batizado">
                    <option value="">Selecione</option>
                    <option>Sim</option><option>Não</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Iniciado?</label>
                <select class="field-input" name="iniciado">
                    <option value="">Selecione</option>
                    <option>Sim</option><option>Não</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Em desenvolvimento?</label>
                <select class="field-input" name="desenvolvimento">
                    <option value="">Selecione</option>
                    <option>Sim</option><option>Não</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Data de Apresentação</label>
                <input type="text" class="field-input" name="data_apresentacao" maxlength="10" placeholder="DD/MM/AAAA">
            </div>
            <div class="field-group">
                <label class="field-label">Data de Filiação</label>
                <input type="text" class="field-input" name="data_filiacao" maxlength="10" placeholder="DD/MM/AAAA">
            </div>
        </div>
    </div>

    <!-- CONTATO E ENDEREÇO -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-address-book"></i> Contato e Endereço</div>
        <div class="form-grid form-grid-3">
            <div class="field-group">
                <label class="field-label">Celular <span class="req">*</span></label>
                <input type="text" class="field-input" name="tel_celular" id="tel_celular" maxlength="16" placeholder="(00) 9 0000-0000" required>
            </div>
            <div class="field-group">
                <label class="field-label">Telefone Residencial</label>
                <input type="text" class="field-input" name="tel_resicencial" maxlength="14" placeholder="(00) 0000-0000">
            </div>
            <div class="field-group">
                <label class="field-label">Telefone Emergência</label>
                <input type="text" class="field-input" name="tel_emergencia" maxlength="16" placeholder="(00) 9 0000-0000">
            </div>
            <div class="field-group" style="grid-column: span 3;">
                <label class="field-label">E-mail Principal</label>
                <input type="email" class="field-input" name="email_principal" placeholder="email@exemplo.com">
            </div>
        </div>
        <hr style="border:0; border-top:1px solid rgba(255,255,255,0.05); margin:20px 0;">
        <div class="form-grid form-grid-4">
            <div class="field-group">
                <label class="field-label">CEP</label>
                <input type="text" class="field-input" name="cep_residencial" id="cep" maxlength="9" placeholder="00000-000" onblur="pesquisacep(this.value);">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Logradouro / Rua</label>
                <input type="text" class="field-input" name="logradouro_residencial" id="rua" placeholder="Nome da rua">
            </div>
            <div class="field-group">
                <label class="field-label">Número</label>
                <input type="text" class="field-input" name="numero_residencial" placeholder="Nº">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Bairro</label>
                <input type="text" class="field-input" name="bairro_residencial" id="bairro" placeholder="Bairro">
            </div>
            <div class="field-group">
                <label class="field-label">Complemento</label>
                <input type="text" class="field-input" name="complemento_residencial" placeholder="Apto, casa...">
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label class="field-label">Cidade</label>
                <input type="text" class="field-input" name="cidade_residencial" id="cidade" placeholder="Cidade">
            </div>
            <div class="field-group">
                <label class="field-label">Estado</label>
                <input type="text" class="field-input" name="estado_residencial" id="uf" maxlength="2" placeholder="UF">
            </div>
        </div>
    </div>

    <!-- DADOS ELEITORAIS -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-id-card"></i> Dados Eleitorais</div>
        <div class="form-grid form-grid-3">
            <div class="field-group">
                <label class="field-label">Título de Eleitor</label>
                <input type="text" class="field-input" name="titulo_eleitor" placeholder="Número do título">
            </div>
            <div class="field-group">
                <label class="field-label">Zona Eleitoral</label>
                <input type="text" class="field-input" name="zona_eleitoral" placeholder="Zona">
            </div>
            <div class="field-group">
                <label class="field-label">Seção Eleitoral</label>
                <input type="text" class="field-input" name="secao_eleitoral" placeholder="Seção">
            </div>
        </div>
    </div>

    <!-- OBSERVAÇÕES -->
    <div class="form-section">
        <div class="form-section-title"><i class="fa fa-note-sticky"></i> Observações</div>
        <div class="field-group">
            <label class="field-label">Observações Gerais</label>
            <textarea class="field-input" name="observacoes" rows="4" placeholder="Anotações sobre o membro..."></textarea>
        </div>
    </div>

    <!-- AÇÕES -->
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;padding-bottom:20px;">
        <button type="submit" class="btn-submit">
            <i class="fa fa-check"></i> Cadastrar Membro
        </button>
        <a href="listar.php" style="color:var(--text-muted);text-decoration:none;font-size:14px;">
            <i class="fa fa-arrow-left"></i> Cancelar
        </a>
    </div>

</form>
</div>
</main>

<script>
// Máscaras
function mCPF(el) {
    let v = el.value.replace(/\D/g,"");
    v = v.replace(/(\d{3})(\d)/,"$1.$2");
    v = v.replace(/(\d{3})(\d)/,"$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
    el.value = v;
}

function cpfCheck(el) {
    const r = document.getElementById('cpfResponse');
    const c = el.value.replace(/\D/g,"");
    if (!c) { r.innerHTML = ''; return; }
    r.innerHTML = isCpfValid(c)
        ? '<span class="cpf-valid"><i class="fa fa-check"></i> VÁLIDO</span>'
        : '<span class="cpf-invalid"><i class="fa fa-times"></i> INVÁLIDO</span>';
}

function isCpfValid(c) {
    if (c.length !== 11 || /^(\d)\1+$/.test(c)) return false;
    let s = 0;
    for (let i = 0; i < 9; i++) s += parseInt(c[i]) * (10 - i);
    let r = (s * 10) % 11; if (r >= 10) r = 0;
    if (r !== parseInt(c[9])) return false;
    s = 0;
    for (let i = 0; i < 10; i++) s += parseInt(c[i]) * (11 - i);
    r = (s * 10) % 11; if (r >= 10) r = 0;
    return r === parseInt(c[10]);
}

// CEP
function limpa_formulário_cep() {
    ['rua','bairro','cidade','uf'].forEach(id => { const el = document.getElementById(id); if(el) el.value = ''; });
}
function meu_callback(c) {
    if (!c.erro) {
        document.getElementById('rua').value   = c.logradouro || '';
        document.getElementById('bairro').value = c.bairro || '';
        document.getElementById('cidade').value = c.localidade || '';
        document.getElementById('uf').value     = c.uf || '';
    } else { limpa_formulário_cep(); alert("CEP não encontrado."); }
}
function pesquisacep(v) {
    const cep = v.replace(/\D/g,'');
    if (!cep) { limpa_formulário_cep(); return; }
    if (/^[0-9]{8}$/.test(cep)) {
        ['rua','bairro','cidade','uf'].forEach(id => { const el = document.getElementById(id); if(el) el.value = '...'; });
        const s = document.createElement('script');
        s.src = `https://viacep.com.br/ws/${cep}/json/?callback=meu_callback`;
        document.body.appendChild(s);
    } else { limpa_formulário_cep(); alert("Formato de CEP inválido."); }
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
