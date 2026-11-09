<aside class="admin-sidebar">

    <a href="#" class="admin-logo" title="Janine Cosméticos">J</a>

    <nav class="admin-menu">

        <a href="#" class="admin-menu-item ativo" title="Dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
        </a>

        <a href="#" class="admin-menu-item" title="Produtos">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2 21 7 12 12 3 7 12 2Z"/>
                <path d="M3 7v10l9 5 9-5V7"/>
                <path d="M12 12v10"/>
            </svg>
        </a>

        <a href="#" class="admin-menu-item" title="Vendas">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3v18h18"/>
                <path d="M8 16V9"/>
                <path d="M13 16V5"/>
                <path d="M18 16v-4"/>
            </svg>
        </a>

        <a href="#" class="admin-menu-item" title="Pedidos">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="3" width="14" height="18" rx="2"/>
                <path d="M9 8h6"/>
                <path d="M9 12h6"/>
                <path d="M9 16h4"/>
            </svg>
        </a>

        <a href="#" class="admin-menu-item" title="Relatórios">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3v18h18"/>
                <path d="M8 16V9"/>
                <path d="M13 16V5"/>
                <path d="M18 16V8"/>
            </svg>
        </a>

    </nav>

    <a href="#" class="admin-sair" title="Sair">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 17l5-5-5-5"/>
            <path d="M15 12H3"/>
            <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
        </svg>
    </a>

</aside>

<header class="admin-topbar">

    <span class="admin-topbar-titulo">Administração</span>

    <div class="admin-topbar-acoes">

        <a href="#" class="admin-perfil">
            <span class="admin-avatar">JS</span>

            <span class="admin-perfil-info">
                <strong>Janine Silva</strong>
                <small>Administradora</small>
            </span>
        </a>

    </div>

</header>

<script>
document.querySelectorAll('.admin-menu-item').forEach(function(item) {

    item.addEventListener('click', function() {

        document.querySelectorAll('.admin-menu-item').forEach(function(link) {
            link.classList.remove('ativo');
        });

        this.classList.add('ativo');

    });

});
</script>
