<?php
// =====================================================
// Filhos da Fé — gestor/listar.php
// =====================================================
@ini_set('memory_limit', '256M');
if (session_status() === PHP_SESSION_NONE) session_start();
include_once 'conexao.php';

$membros = [];
$total   = 0;
$porPagina = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
if (!in_array($porPagina, [10, 20, 50, 100])) $porPagina = 50;

$pagina    = max(1, intval($_GET['pagina'] ?? 1));
$busca     = trim($_GET['busca'] ?? '');
$filtro_lider = trim($_GET['filtro_lider'] ?? '');
$offset    = ($pagina - 1) * $porPagina;

$lideres = [];
try {
    $stmtLideres = $conn->query("SELECT DISTINCT nome_lider FROM cadastro WHERE nome_lider IS NOT NULL AND nome_lider != '' ORDER BY nome_lider");
    $lideres = $stmtLideres->fetchAll(PDO::FETCH_COLUMN);
} catch(Exception $e) {}

try {
    $where = [];
    $params = [];
    
    if ($busca !== '') {
        $like = '%' . $busca . '%';
        $where[] = "(nome_cadastro LIKE ? OR cpf_cadastro LIKE ? OR matricula_cadastro LIKE ? OR nome_lider LIKE ?)";
        array_push($params, $like, $like, $like, $like);
    }
    
    if ($filtro_lider !== '') {
        $where[] = "nome_lider = ?";
        $params[] = $filtro_lider;
    }
    
    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

    // Count
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM cadastro $whereSql");
    $stmtCount->execute($params);
    $total = $stmtCount->fetchColumn();

    // Fetch
    $sql = "SELECT id_cadastro, matricula_cadastro, nome_cadastro, nome_casa, cargo_funcao_cadastro, tel_celular, situacao_cadastral, nome_lider FROM cadastro $whereSql ORDER BY id_cadastro DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    
    $paramIndex = 1;
    foreach ($params as $p) {
        $stmt->bindValue($paramIndex++, $p);
    }
    $stmt->bindValue($paramIndex++, $porPagina, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, $offset,    PDO::PARAM_INT);
    
    $stmt->execute();
    $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(Exception $e) {}

$totalPaginas = max(1, ceil($total / $porPagina));

$pageTitle = 'Lista de Membros';
$pageIcon  = 'fa fa-list';
$pageDesc  = 'Todos os membros cadastrados na tenda';

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
        .tbl-members { width:100%; border-collapse:collapse; font-size:13px; }
        .tbl-members th { text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); padding:10px 14px; border-bottom:1px solid var(--glass-border); background:rgba(255,255,255,0.02); }
        .tbl-members td { padding:12px 14px; border-bottom:1px solid rgba(255,255,255,0.03); vertical-align:middle; }
        .tbl-members tr:hover td { background:rgba(255,255,255,0.02); }
        .badge-status { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-ativo    { background:rgba(37,211,102,0.15); color:#25D366; }
        .badge-inativo  { background:rgba(239,68,68,0.15); color:#ef4444; }
        .badge-pendente { background:rgba(245,158,11,0.15); color:#f59e0b; }
        .btn-icon { display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;transition:0.2s; text-decoration:none; }
        .btn-icon-edit   { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .btn-icon-edit:hover { background:rgba(59,130,246,0.3); }
        .btn-icon-del    { background:rgba(239,68,68,0.15); color:#ef4444; }
        .btn-icon-del:hover { background:rgba(239,68,68,0.3); }
        .btn-icon-view   { background:rgba(168,85,247,0.15); color:#a855f7; }
        .btn-icon-view:hover { background:rgba(168,85,247,0.3); }
        .search-box { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:10px; padding:10px 16px; color:var(--text-primary); font-size:14px; width:280px; outline:none; transition:0.2s; }
        .search-box:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(168,85,247,0.15); }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>
<div class="content-area" style="padding:24px;">

    <div class="card-glass" style="padding:20px; border-radius:16px;">

        <!-- Toolbar -->
        <form id="filterForm" method="GET" action="listar.php">
            
            <!-- Row 1: Actions and Counters -->
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:15px;">
                <div style="display:flex; align-items:center; gap: 15px; flex-wrap:wrap;">
                    <span style="font-size:14px;color:var(--text-muted); font-weight: 500;">
                        Total: <strong style="color:var(--text-primary);"><?= $total ?></strong> membros
                    </span>
                    <select name="limit" onchange="document.getElementById('filterForm').submit();" class="search-box" style="width: auto; padding: 6px 12px; font-size: 13px;">
                        <option value="10" <?= $porPagina == 10 ? 'selected' : '' ?>>10 por pág.</option>
                        <option value="20" <?= $porPagina == 20 ? 'selected' : '' ?>>20 por pág.</option>
                        <option value="50" <?= $porPagina == 50 ? 'selected' : '' ?>>50 por pág.</option>
                        <option value="100" <?= $porPagina == 100 ? 'selected' : '' ?>>100 por pág.</option>
                    </select>
                    <?php if ($busca || $filtro_lider): ?>
                    <a href="listar.php" style="font-size:12px;color:var(--accent); text-decoration:none; background: rgba(168,85,247,0.1); padding: 4px 10px; border-radius: 20px;"><i class="fa fa-times"></i> Limpar Filtros</a>
                    <?php endif; ?>
                </div>
                
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="exportar.php" target="_blank" class="btn-primary-custom" style="background:rgba(37,211,102,0.15);border:1px solid rgba(37,211,102,0.3);color:#25D366;box-shadow:none; padding: 8px 16px; font-size: 13px;">
                        <i class="fa fa-file-export"></i> Exportar
                    </a>
                    <a href="importar.php" class="btn-primary-custom" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);color:#60a5fa;box-shadow:none; padding: 8px 16px; font-size: 13px;">
                        <i class="fa fa-file-import"></i> Importar
                    </a>
                    <a href="cadastro.php" class="btn-primary-custom" style="background:linear-gradient(135deg,#a855f7,#7c3aed);box-shadow:0 4px 15px rgba(168,85,247,0.3); padding: 8px 16px; font-size: 13px;">
                        <i class="fa fa-plus"></i> Novo Membro
                    </a>
                </div>
            </div>

            <!-- Row 2: Search and Filters -->
            <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
                <select name="filtro_lider" onchange="document.getElementById('filterForm').submit();" class="search-box" style="width: 250px;">
                    <option value="">Todos os Líderes</option>
                    <?php foreach ($lideres as $ldr): ?>
                        <option value="<?= htmlspecialchars($ldr) ?>" <?= $filtro_lider === $ldr ? 'selected' : '' ?>><?= htmlspecialchars($ldr) ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="position:relative; flex: 1; min-width: 250px;">
                    <i class="fa fa-search" style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--text-muted);"></i>
                    <input type="text" name="busca" class="search-box" placeholder="Buscar por nome, CPF, matrícula..." value="<?= htmlspecialchars($busca) ?>" style="width: 100%; padding-left: 40px; box-sizing: border-box;">
                </div>
                <button type="submit" style="display:none;"></button>
            </div>
            
        </form>

        <?php 
        $htmlPaginacao = '';
        if ($totalPaginas > 1) {
            $htmlPaginacao .= '<div class="pagination-container" style="display:flex;justify-content:center;align-items:center;gap:8px;margin-bottom:20px;margin-top:20px;flex-wrap:wrap;">';
            if ($pagina > 1) {
                $htmlPaginacao .= '<a href="?pagina='.($pagina-1).'&busca='.urlencode($busca).'&limit='.$porPagina.'&filtro_lider='.urlencode($filtro_lider).'" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:var(--glass-bg);border:1px solid var(--glass-border);color:var(--text-primary);text-decoration:none;"><i class="fa fa-chevron-left" style="font-size:12px;"></i></a>';
            }
            $inicio = max(1, $pagina - 2);
            $fim    = min($totalPaginas, $pagina + 2);
            for ($i = $inicio; $i <= $fim; $i++) {
                $bg = $i === $pagina ? 'linear-gradient(135deg,#a855f7,#7c3aed)' : 'var(--glass-bg)';
                $border = $i === $pagina ? 'transparent' : 'var(--glass-border)';
                $color = $i === $pagina ? '#fff' : 'var(--text-primary)';
                $weight = $i === $pagina ? '700' : '400';
                $htmlPaginacao .= '<a href="?pagina='.$i.'&busca='.urlencode($busca).'&limit='.$porPagina.'&filtro_lider='.urlencode($filtro_lider).'" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:'.$bg.';border:1px solid '.$border.';color:'.$color.';text-decoration:none;font-size:13px;font-weight:'.$weight.';">'.$i.'</a>';
            }
            if ($pagina < $totalPaginas) {
                $htmlPaginacao .= '<a href="?pagina='.($pagina+1).'&busca='.urlencode($busca).'&limit='.$porPagina.'&filtro_lider='.urlencode($filtro_lider).'" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:var(--glass-bg);border:1px solid var(--glass-border);color:var(--text-primary);text-decoration:none;"><i class="fa fa-chevron-right" style="font-size:12px;"></i></a>';
            }
            $htmlPaginacao .= '<span style="font-size:12px;color:var(--text-muted);margin-left:8px;">Página '.$pagina.' de '.$totalPaginas.'</span>';
            $htmlPaginacao .= '</div>';
        }
        
        echo $htmlPaginacao;
        ?>

        <!-- Tabela -->
        <div style="overflow-x:auto;">
            <table class="tbl-members" id="tblMembers">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Líder</th>
                        <th>Casa / Terreiro</th>
                        <th>Cargo / Função</th>
                        <th>Telefone</th>
                        <th>Situação</th>
                        <th style="text-align:center;">Ações</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                <?php if (empty($membros)): ?>
                    <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="fa fa-users" style="font-size:32px;margin-bottom:10px;display:block;opacity:0.3;"></i>
                        Nenhum membro cadastrado ainda.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($membros as $m): ?>
                    <tr>
                        <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($m['matricula_cadastro']) ?></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($m['nome_cadastro']) ?></td>
                        <td style="color:var(--text-secondary);font-size:12px;"><?= htmlspecialchars($m['nome_lider'] ?? '') ?></td>
                        <td style="color:var(--text-secondary);font-size:12px;"><?= htmlspecialchars($m['nome_casa'] ?? '') ?></td>
                        <td style="color:var(--text-secondary);font-size:12px;"><?= htmlspecialchars($m['cargo_funcao_cadastro'] ?? '') ?></td>
                        <td style="font-size:13px; white-space: nowrap;">
                            <?= htmlspecialchars($m['tel_celular'] ?? '') ?>
                            <?php if (!empty($m['tel_celular'])): 
                                $zap = preg_replace('/[^0-9]/', '', $m['tel_celular']);
                                if (strlen($zap) >= 10 && substr($zap, 0, 2) !== '55') $zap = '55' . $zap;
                            ?>
                            <a href="https://api.whatsapp.com/send?phone=<?= $zap ?>" target="_blank" style="color:#25D366; margin-left: 5px; font-size: 16px; text-decoration: none;" title="Chamar no WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $sit = strtolower($m['situacao_cadastral'] ?? '');
                            $cls = str_contains($sit,'ativo') ? 'badge-ativo' : (str_contains($sit,'inativo') ? 'badge-inativo' : 'badge-pendente');
                            ?>
                            <span class="badge-status <?= $cls ?>"><?= htmlspecialchars($m['situacao_cadastral'] ?? '') ?></span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:inline-flex;gap:6px;">
                                <a href="prolife.php?id_cadastro=<?= $m['id_cadastro'] ?>" class="btn-icon btn-icon-view" title="Ver Perfil">
                                    <i class="fa fa-eye" style="font-size:13px;"></i>
                                </a>
                                <a href="edit_cadastro.php?id_cadastro=<?= $m['id_cadastro'] ?>" class="btn-icon btn-icon-edit" title="Editar">
                                    <i class="fa fa-pen" style="font-size:13px;"></i>
                                </a>
                                <a href="excluir.php?id_cadastro=<?= $m['id_cadastro'] ?>" class="btn-icon btn-icon-del" title="Excluir"
                                   onclick="return confirm('Tem certeza que deseja excluir <?= htmlspecialchars(addslashes($m['nome_cadastro'])) ?>?')">
                                    <i class="fa fa-trash" style="font-size:13px;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação Inferior -->
        <?php echo $htmlPaginacao; ?>
    </div>

</div>
</main>
<script>
let searchTimeout;
const searchInput = document.querySelector('input[name="busca"]');
const filterForm = document.getElementById('filterForm');

searchInput?.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        // Build URL
        const url = new URL(window.location.href);
        url.searchParams.set('busca', this.value);
        url.searchParams.set('limit', filterForm.querySelector('select[name="limit"]').value);
        url.searchParams.set('filtro_lider', filterForm.querySelector('select[name="filtro_lider"]').value);
        url.searchParams.set('pagina', 1); // Reset to page 1 on new search

        // Add loading state
        const tbody = document.getElementById('tableBody');
        tbody.style.opacity = '0.5';

        // Fetch new data
        fetch(url.toString())
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update Table Body
                const newTbody = doc.getElementById('tableBody');
                if (newTbody) tbody.innerHTML = newTbody.innerHTML;
                tbody.style.opacity = '1';

                // Update Pagination
                const currentPaginations = document.querySelectorAll('.pagination-container');
                const newPagination = doc.querySelector('.pagination-container');
                
                if (currentPaginations.length > 0 && newPagination) {
                    currentPaginations.forEach(el => el.innerHTML = newPagination.innerHTML);
                } else if (currentPaginations.length === 0 && newPagination) {
                    // Inject top pagination
                    const form = document.getElementById('filterForm');
                    const cloneTop = newPagination.cloneNode(true);
                    form.parentNode.insertBefore(cloneTop, form.nextSibling);

                    // Inject bottom pagination
                    const tableContainer = document.querySelector('div[style="overflow-x:auto;"]');
                    const cloneBottom = newPagination.cloneNode(true);
                    tableContainer.parentNode.insertBefore(cloneBottom, tableContainer.nextSibling);
                } else if (currentPaginations.length > 0 && !newPagination) {
                    currentPaginations.forEach(el => el.remove());
                }

                // Update Total Count Text
                const currentTotal = document.querySelector('strong'); // The total members text
                const newTotal = doc.querySelector('strong');
                if (currentTotal && newTotal) {
                    currentTotal.innerHTML = newTotal.innerHTML;
                }

                // Update URL in browser without reloading
                window.history.pushState({}, '', url.toString());
            })
            .catch(err => {
                console.error("Erro na busca:", err);
                tbody.style.opacity = '1';
            });
            
    }, 400); // 400ms debounce
});

// Prevent form submission on enter since it's now live
searchInput?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') e.preventDefault();
});
</script>
</body>
</html>
