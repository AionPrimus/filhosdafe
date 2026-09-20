<script>
(function() {
    try {
        const tema = localStorage.getItem('tema') || 'dark';
        document.documentElement.setAttribute('data-theme', tema);
        const isCollapsed = localStorage.getItem('sidebar_collapsed') === '1';
        if (isCollapsed && window.innerWidth >= 992) {
            document.documentElement.classList.add('sidebar-collapsed');
            document.addEventListener('DOMContentLoaded', function() {
                if (document.body) document.body.classList.add('sidebar-collapsed');
                const sb = document.getElementById('sidebar');
                if (sb) sb.classList.add('sidebar-collapsed');
            });
        }
    } catch(e) {}
})();

function toggleSidebarMenu(e) {
    if (e && e.preventDefault) e.preventDefault();
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    if (window.innerWidth < 992) {
        sidebar.classList.toggle('open');
    } else {
        const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
        if (document.body) document.body.classList.toggle('sidebar-collapsed', isCollapsed);
        sidebar.classList.toggle('sidebar-collapsed', isCollapsed);
        try { localStorage.setItem('sidebar_collapsed', isCollapsed ? '1' : '0'); } catch(err) {}
    }
}
</script>
<header class="topbar">
    <div id="btnHamburger" style="display:none;"></div>
    <div style="display:flex;align-items:center;gap:12px;">
        <div>
            <h1 style="font-size:20px; font-weight:600; margin:0; display:flex; align-items:center; gap:8px;">
                <?= isset($pageIcon) ? '<i class="'.$pageIcon.'" style="color:var(--accent);"></i>' : '' ?>
                <?= $pageTitle ?? 'Gestor' ?>
            </h1>
            <?php if(!empty($pageDesc)): ?>
            <p style="color:var(--text-secondary); margin:5px 0 0; font-size:13px;"><?= $pageDesc ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="topbar-actions" style="display:flex; align-items:center; gap:16px;">
        <div id="clock_sys" style="background:var(--glass-bg); padding:6px 12px; border-radius:6px; font-size:12px; color:var(--text-secondary); border:1px solid var(--glass-border); display:flex; align-items:center; gap:6px;">
            <i class="fa fa-clock"></i> <span id="clock_time"><?= date('d/m/Y H:i:s') ?></span>
        </div>
        <button class="theme-toggle" id="btnTema" style="background:var(--glass-bg); color:var(--text-primary); border:1px solid var(--glass-border); border-radius:6px; padding:6px 12px; cursor:pointer;" title="Alternar Tema">
            <i class="fa fa-moon"></i>
        </button>
    </div>
</header>
<script src="assets/js/main.js?v=<?= time() ?>"></script>
<script>
setInterval(() => {
    const el = document.getElementById('clock_time');
    if(el) {
        const now = new Date();
        el.textContent = now.toLocaleDateString('pt-BR') + ' ' + now.toLocaleTimeString('pt-BR');
    }
}, 1000);
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() { this.src = 'assets/no-image.png'; });
    });
});
</script>
