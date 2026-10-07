<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="bi bi-people-fill"></i></div>
        <div>
            <strong>Clientes</strong>
            <span>Gestão de clientes</span>
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Navegação principal">
        <a class="nav-item" href="dashboard.php">
            <span><i class="bi bi-house-door"></i></span> Dashboard
        </a>
        <a class="nav-item" href="mostrarClientes.php">
            <span><i class="bi bi-people"></i></span> Clientes
        </a>
        <a class="nav-item" href="formCadastrarCliente.php">
            <span><i class="bi bi-person-plus"></i></span> Novo cliente
        </a>
        <a class="nav-item" href="../controladores/exportarClientes.php">
            <span><i class="bi bi-download"></i></span> Exportar dados
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="theme-item">
            <div class="theme-item-label">
                <span><i class="bi bi-moon-stars" id="themeIcon"></i></span>
                Tema escuro
            </div>
            <label class="theme-switch">
                <input type="checkbox" id="themeToggle" aria-label="Alternar modo escuro">
                <span class="slider"></span>
            </label>
        </div>

        <a class="nav-item danger" href="../controladores/authRouter.php?acao=logout">
            <span style="color: #dc2626;"><i class="bi bi-box-arrow-right"></i></span> Sair
        </a>
    </div>
</aside>

<button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false">
    <i class="bi bi-list"></i>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
(function () {
    const root = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('mobileMenuBtn');
    const overlay = document.getElementById('sidebarOverlay');

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        if (toggle) toggle.checked = theme === 'dark';
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }
    }

    const savedTheme = localStorage.getItem('clientes-theme');
    applyTheme(savedTheme === 'dark' ? 'dark' : 'light');

    if (toggle) {
        toggle.addEventListener('change', function () {
            const theme = this.checked ? 'dark' : 'light';
            localStorage.setItem('clientes-theme', theme);
            applyTheme(theme);
        });
    }

    function closeMenu() {
        if (!sidebar || !menuBtn) return;
        sidebar.classList.remove('open');
        menuBtn.setAttribute('aria-expanded', 'false');
        if (overlay) overlay.classList.remove('show');
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', function () {
            const open = sidebar.classList.toggle('open');
            menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (overlay) overlay.classList.toggle('show', open);
        });
    }

    if (overlay) overlay.addEventListener('click', closeMenu);
    document.querySelectorAll('.sidebar .nav-item').forEach(function (item) {
        item.addEventListener('click', closeMenu);
    });

    const current = window.location.pathname.split('/').pop();
    document.querySelectorAll('.sidebar .nav-item').forEach(function (item) {
        const href = item.getAttribute('href') || '';
        const target = href.split('/').pop().split('?')[0];
        if (target && target === current) item.classList.add('active');
    });
})();
</script>
