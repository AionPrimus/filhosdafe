<?php
// =====================================================
// Filhos da Fé — gestor/index.php (Dashboard)
// =====================================================
require_once __DIR__ . '/views/includes/_auth.php';
include_once 'conexao.php';

// Métricas
$totalMembros = 0;
$totalAtivos  = 0;
$novosMes     = 0;
$totalSituacoes = [];
$ultimos5 = [];

try {
    $totalMembros = $conn->query("SELECT COUNT(*) FROM cadastro")->fetchColumn();
    $totalAtivos  = $conn->query("SELECT COUNT(*) FROM cadastro WHERE situacao_cadastral = 'Ativo'")->fetchColumn();
    $novosMes     = $conn->query("SELECT COUNT(*) FROM cadastro WHERE MONTH(STR_TO_DATE(SUBSTRING(datatime,1,10),'%d/%m/%Y')) = MONTH(CURDATE()) AND YEAR(STR_TO_DATE(SUBSTRING(datatime,1,10),'%d/%m/%Y')) = YEAR(CURDATE())")->fetchColumn();
    $ultimos5     = $conn->query("SELECT matricula_cadastro, nome_cadastro, cargo_funcao_cadastro, tel_celular, situacao_cadastral FROM cadastro ORDER BY id_cadastro DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    $sitRows      = $conn->query("SELECT situacao_cadastral, COUNT(*) as total FROM cadastro GROUP BY situacao_cadastral ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sitRows as $r) {
        $totalSituacoes[$r['situacao_cadastral']] = $r['total'];
    }
} catch(Exception $e) {}

$pageTitle = 'Dashboard';
$pageIcon  = 'fa fa-gauge-high';
$pageDesc  = 'Visão geral do sistema';
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
        /* Animated Background / Parallax Effect */
        .dashboard-wrapper {
            position: relative;
            padding: 32px;
            min-height: calc(100vh - 70px);
            overflow: hidden;
            z-index: 1;
        }
        .dashboard-bg-glow {
            position: absolute;
            top: -20%; left: -10%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(168,85,247,0.15) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
            animation: floatGlow 15s ease-in-out infinite alternate;
        }
        .dashboard-bg-glow-2 {
            position: absolute;
            bottom: -20%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(37,211,102,0.1) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
            animation: floatGlow 12s ease-in-out infinite alternate-reverse;
        }
        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* Dashboard Cards */
        .dash-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 24px; 
            margin-bottom: 32px;
        }
        .stat-card {
            background: var(--glass-bg); 
            border: 1px solid var(--glass-border); 
            border-radius: 20px;
            padding: 24px; 
            display: flex; 
            align-items: center; 
            gap: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .stat-card::before {
            content: ''; position: absolute; top:0; left:0; right:0; height:4px;
            background: linear-gradient(90deg, #a855f7, #25d366);
            opacity: 0; transition: 0.4s;
        }
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 12px 30px rgba(0,0,0,0.2); 
            border-color: rgba(168,85,247,0.3);
        }
        .stat-card:hover::before { opacity: 1; }

        .stat-icon { 
            width: 60px; height: 60px; 
            border-radius: 16px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 26px; 
            flex-shrink: 0;
            transition: transform 0.4s;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-value { font-size: 32px; font-weight: 800; line-height: 1.2; letter-spacing: -0.5px; }
        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Secondary Grid */
        .dash-secondary-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        @media (max-width: 992px) {
            .dash-secondary-grid { grid-template-columns: 1fr; }
            .dashboard-wrapper { padding: 20px; }
        }

        .panel-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 24px;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .panel-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--glass-border);
        }
        .panel-title {
            font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0; color: var(--text-primary);
        }

        /* Table */
        .table-modern { width: 100%; border-collapse: separate; border-spacing: 0 8px; font-size: 14px; }
        .table-modern th { text-align:left; font-size:12px; text-transform:uppercase; color:var(--text-muted); padding:0 16px 8px; font-weight: 600; letter-spacing: 0.5px; border-bottom: none; }
        .table-modern td { padding:16px; background: rgba(255,255,255,0.02); vertical-align: middle; transition: 0.3s; border-bottom: 1px solid rgba(255,255,255,0.01); }
        .table-modern tr td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .table-modern tr td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
        .table-modern tbody tr:hover td { background: rgba(255,255,255,0.04); transform: scale(1.01); }

        /* Progress */
        .progress-item { margin-bottom: 18px; }
        .progress-header { display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary); }
        .progress-track { background: rgba(0,0,0,0.2); border-radius: 6px; height: 8px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); }
        .progress-fill { height: 100%; border-radius: 6px; transition: width 1.5s cubic-bezier(0.16, 1, 0.3, 1); }

        .badge-status { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; display:inline-block; }
        .badge-ativo   { background:rgba(37,211,102,0.15); color:#25D366; border: 1px solid rgba(37,211,102,0.3); }
        .badge-inativo { background:rgba(239,68,68,0.15); color:#ef4444; border: 1px solid rgba(239,68,68,0.3); }
        .badge-pendente { background:rgba(245,158,11,0.15); color:#f59e0b; border: 1px solid rgba(245,158,11,0.3); }
        
        .btn-modern { 
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #a855f7, #7c3aed);
            color: #fff; border: none; padding: 12px 24px; border-radius: 12px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            transition: all 0.3s; box-shadow: 0 4px 15px rgba(168,85,247,0.3);
        }
        .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(168,85,247,0.4); }
        .btn-modern-outline {
            background: rgba(255,255,255,0.05); color: var(--text-primary);
            border: 1px solid var(--glass-border); box-shadow: none;
        }
        .btn-modern-outline:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>

<div class="dashboard-wrapper">
    <!-- Efeitos Parallax -->
    <div class="dashboard-bg-glow"></div>
    <div class="dashboard-bg-glow-2"></div>

    <!-- Cards de Métricas -->
    <div class="dash-grid animate-fade">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(168,85,247,0.15); color:#a855f7;"><i class="fa fa-users"></i></div>
            <div>
                <div class="stat-value"><?= number_format($totalMembros) ?></div>
                <div class="stat-label">Total de Membros</div>
            </div>
        </div>
        <div class="stat-card delay-1">
            <div class="stat-icon" style="background:rgba(37,211,102,0.15); color:#25D366;"><i class="fa fa-user-check"></i></div>
            <div>
                <div class="stat-value"><?= number_format($totalAtivos) ?></div>
                <div class="stat-label">Membros Ativos</div>
            </div>
        </div>
        <div class="stat-card delay-2">
            <div class="stat-icon" style="background:rgba(59,130,246,0.15); color:#60a5fa;"><i class="fa fa-user-plus"></i></div>
            <div>
                <div class="stat-value"><?= number_format($novosMes) ?></div>
                <div class="stat-label">Novos este Mês</div>
            </div>
        </div>
        <div class="stat-card delay-3">
            <div class="stat-icon" style="background:rgba(245,158,11,0.15); color:#f59e0b;"><i class="fa fa-chart-pie"></i></div>
            <div>
                <div class="stat-value"><?= count($totalSituacoes) ?></div>
                <div class="stat-label">Status Diversos</div>
            </div>
        </div>
    </div>

    <!-- Seção Secundária -->
    <div class="dash-secondary-grid animate-fade delay-1">
        
        <!-- Últimos Cadastros -->
        <div class="panel-glass">
            <div class="panel-header">
                <h3 class="panel-title">
                    <div style="background:rgba(168,85,247,0.15);color:#a855f7;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-clock-rotate-left"></i>
                    </div>
                    Recentes
                </h3>
                <a href="listar.php" style="font-size:13px;color:var(--accent);text-decoration:none;font-weight:600;padding:6px 12px;background:rgba(168,85,247,0.05);border-radius:8px;border:1px solid rgba(168,85,247,0.1);">Ver todos <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
            
            <?php if (empty($ultimos5)): ?>
                <div style="padding:40px 20px;text-align:center;color:var(--text-muted);">
                    <i class="fa fa-folder-open" style="font-size:32px;opacity:0.3;margin-bottom:10px;display:block;"></i>
                    Nenhum membro cadastrado
                </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="table-modern">
                    <thead><tr><th>Nome / Função</th><th>Contato</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($ultimos5 as $m): ?>
                    <tr>
                        <td>
                            <div style="font-weight:700; color:var(--text-primary); font-size:15px; margin-bottom:2px;"><?= htmlspecialchars($m['nome_cadastro']) ?></div>
                            <div style="color:var(--text-muted);font-size:12px; font-weight:500;"><i class="fa fa-id-badge" style="margin-right:4px;"></i> <?= htmlspecialchars($m['cargo_funcao_cadastro'] ?: 'Sem Função') ?></div>
                        </td>
                        <td style="color:var(--text-secondary); font-size:13px;">
                            <?php if(!empty($m['tel_celular'])): ?>
                                <i class="fa fa-phone" style="font-size:10px;margin-right:4px;opacity:0.5;"></i> <?= htmlspecialchars($m['tel_celular']) ?>
                            <?php else: ?>
                                <span style="opacity:0.5;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $sit = strtolower($m['situacao_cadastral'] ?? '');
                            $cls = str_contains($sit,'ativo') ? 'badge-ativo' : (str_contains($sit,'inativo') ? 'badge-inativo' : 'badge-pendente');
                            ?>
                            <span class="badge-status <?= $cls ?>"><?= htmlspecialchars($m['situacao_cadastral']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Distribuição por Situação -->
        <div class="panel-glass">
            <div class="panel-header">
                <h3 class="panel-title">
                    <div style="background:rgba(37,211,102,0.15);color:#25d366;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-chart-simple"></i>
                    </div>
                    Distribuição
                </h3>
            </div>
            
            <div style="padding-top:10px;">
                <?php if (empty($totalSituacoes)): ?>
                    <p style="color:var(--text-muted); font-size:13px; text-align:center;">Sem dados estatísticos.</p>
                <?php else:
                    $maxSit = max($totalSituacoes);
                    $colors = ['linear-gradient(90deg, #25d366, #128c7e)', 'linear-gradient(90deg, #a855f7, #7c3aed)', 'linear-gradient(90deg, #f59e0b, #d97706)', 'linear-gradient(90deg, #ef4444, #b91c1c)'];
                    $colorIdx = 0;
                    foreach ($totalSituacoes as $sit => $cnt):
                        $pct = $maxSit > 0 ? ($cnt / $totalMembros) * 100 : 0; // % do total para ser mais real
                        $bg = $colors[$colorIdx % count($colors)];
                        $colorIdx++;
                ?>
                <div class="progress-item">
                    <div class="progress-header">
                        <span><?= htmlspecialchars($sit ?: 'Não informado') ?></span>
                        <span style="color:var(--text-primary);"><?= $cnt ?> (<?= number_format($pct, 1) ?>%)</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width:<?= $pct ?>%; background: <?= $bg ?>;"></div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
            
            <!-- Quick Action Inside Panel -->
            <div style="margin-top: 32px;">
                <a href="cadastro.php" class="btn-modern" style="width:100%; justify-content:center;">
                    <i class="fa fa-user-plus"></i> Novo Membro
                </a>
            </div>
        </div>

    </div>

</div>
</main>
</body>
</html>
