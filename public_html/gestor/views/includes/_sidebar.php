<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$adminNome   = $_SESSION['admin_nome'] ?? 'Admin';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="index.php" class="brand-logo" title="Filhos da Fé">
            <div class="brand-icon" style="background:linear-gradient(135deg,#a855f7,#7c3aed);">
                <i class="fa fa-star" style="color:#fff;"></i>
            </div>
            <div class="brand-text">
                <div class="brand-name">Filhos da Fé</div>
                <div class="brand-sub">Tenda Espírita</div>
            </div>
        </a>
        <button type="button" class="btn-sidebar-toggle" onclick="toggleSidebarMenu(event)" title="Recolher / Expandir Menu">
            <i class="fa fa-bars icon-sidebar-toggle"></i>
        </button>
    </div>

    <style>
    .sidebar-group { margin-bottom: 2px; }
    .sidebar-group-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 15px; cursor: pointer; color: var(--text-muted);
        font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;
        border-radius: 8px; transition: all 0.2s ease; margin: 0 8px;
    }
    .sidebar-group-header:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .sidebar-group-header i.fa-chevron-down { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); font-size:10px; opacity:0.7; }
    .sidebar-group.closed .sidebar-group-header i.fa-chevron-down { transform: rotate(-90deg); }
    .sidebar-group-content {
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
        max-height: 500px; opacity: 1;
    }
    .sidebar-group.closed .sidebar-group-content { max-height: 0; opacity: 0; pointer-events: none; }
    </style>

    <div style="display:flex; justify-content:space-between; align-items:center; margin: 20px 15px 10px;">
        <span style="font-size:11px; text-transform:uppercase; color:var(--text-muted); font-weight:600; letter-spacing:0.5px;">Menu Principal</span>
    </div>

    <nav class="sidebar-nav" id="sidebarNavMain">

        <!-- PAINEL -->
        <div class="sidebar-group <?= ($currentPage === 'index.php') ? '' : 'closed' ?>">
            <div class="sidebar-group-header" onclick="this.parentElement.classList.toggle('closed')">
                <span><i class="fa fa-chart-pie me-2" style="opacity:0.5;"></i> Painel</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="sidebar-group-content">
                <a href="index.php" class="nav-item <?= $currentPage == 'index.php' ? 'active' : '' ?>" data-title="Dashboard">
                    <i class="fa fa-gauge-high"></i> <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- MEMBROS -->
        <div class="sidebar-group <?= in_array($currentPage, ['cadastro.php','listar.php','edit_cadastro.php']) ? '' : 'closed' ?>">
            <div class="sidebar-group-header" onclick="this.parentElement.classList.toggle('closed')">
                <span><i class="fa fa-users me-2" style="opacity:0.5;"></i> Membros</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="sidebar-group-content">
                <a href="cadastro.php" class="nav-item <?= $currentPage == 'cadastro.php' ? 'active' : '' ?>" data-title="Novo Cadastro">
                    <i class="fa fa-user-plus"></i> <span>Novo Cadastro</span>
                </a>
                <a href="listar.php" class="nav-item <?= $currentPage == 'listar.php' ? 'active' : '' ?>" data-title="Lista de Membros">
                    <i class="fa fa-list"></i> <span>Lista de Membros</span>
                </a>
            </div>
        </div>

        <!-- DOCUMENTOS -->
        <div class="sidebar-group <?= in_array($currentPage, ['prolife.php','carteira.php']) ? '' : 'closed' ?>">
            <div class="sidebar-group-header" onclick="this.parentElement.classList.toggle('closed')">
                <span><i class="fa fa-file-lines me-2" style="opacity:0.5;"></i> Documentos</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="sidebar-group-content">
                <a href="prolife.php" class="nav-item <?= $currentPage == 'prolife.php' ? 'active' : '' ?>" data-title="Perfil do Membro">
                    <i class="fa fa-id-card"></i> <span>Perfil do Membro</span>
                </a>
                <a href="carteira.php" class="nav-item <?= $currentPage == 'carteira.php' ? 'active' : '' ?>" data-title="Carteirinha">
                    <i class="fa fa-address-card"></i> <span>Carteirinha</span>
                </a>
            </div>
        </div>

        <div class="nav-label" style="font-size:11px; text-transform:uppercase; color:var(--text-muted); margin:15px 0 10px; padding-left:15px; font-weight:600;">Conta</div>
        <div class="nav-item" style="padding:10px 15px; font-size:13px; color:var(--text-muted);">
            <i class="fa fa-circle-user" style="color:var(--accent);"></i>
            <span><?= htmlspecialchars($adminNome) ?></span>
        </div>
        <a href="sair.php" class="nav-item" data-title="Sair">
            <i class="fa fa-right-from-bracket"></i> <span>Sair</span>
        </a>
    </nav>

    <script>
    function toggleAllSidebar(btn) {
        const isCollapse = btn.getAttribute('data-state') !== 'expand';
        document.querySelectorAll('.sidebar-group').forEach(g => {
            isCollapse ? g.classList.add('closed') : g.classList.remove('closed');
        });
        if (isCollapse) {
            btn.innerHTML = '<i class="fa fa-expand"></i> <span>Expandir Tudo</span>';
            btn.setAttribute('data-state', 'expand');
        } else {
            btn.innerHTML = '<i class="fa fa-compress"></i> <span>Recolher Tudo</span>';
            btn.setAttribute('data-state', 'collapse');
        }
    }
    </script>
</aside>
