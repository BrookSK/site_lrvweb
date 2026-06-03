<aside class="w-64 bg-sidebar text-white flex-shrink-0 overflow-y-auto hidden lg:block">
    <!-- Logo -->
    <div class="p-6 border-b border-white/10">
        <h1 class="text-xl font-bold">LRV Web</h1>
        <p class="text-xs text-gray-400 mt-1">Painel Administrativo</p>
        <a href="/" target="_blank" class="inline-flex items-center gap-1.5 mt-3 text-[11px] text-gray-500 hover:text-purple-400 transition">
            <i data-lucide="external-link" class="w-3 h-3"></i> Ver site institucional
        </a>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        <?php
        $menuItems = [
            ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'url' => '/admin/dashboard', 'permission' => 'dashboard.view'],
            ['icon' => 'users', 'label' => 'Clientes', 'url' => '/admin/clientes', 'permission' => 'clients.view'],
            ['icon' => 'target', 'label' => 'CRM', 'url' => '/admin/crm', 'permission' => 'crm.view'],
            ['icon' => 'folder-kanban', 'label' => 'Projetos', 'url' => '/admin/projetos', 'permission' => 'projects.view'],
            ['icon' => 'file-text', 'label' => 'Orçamentos', 'url' => '/admin/orcamentos', 'permission' => 'budgets.view'],
            ['icon' => 'wallet', 'label' => 'Financeiro', 'url' => '/admin/financeiro', 'permission' => 'financial.view'],
            ['icon' => 'file-archive', 'label' => 'Documentos', 'url' => '/admin/documentos', 'permission' => 'documents.view'],
            ['icon' => 'user-cog', 'label' => 'Equipe', 'url' => '/admin/equipe', 'permission' => 'team.view'],
            ['icon' => 'image', 'label' => 'Portfólio', 'url' => '/admin/portfolio', 'permission' => 'portfolio.view'],
            ['icon' => 'pen-tool', 'label' => 'Blog', 'url' => '/admin/blog', 'permission' => 'blog.view'],
            ['icon' => 'layout', 'label' => 'Páginas', 'url' => '/admin/paginas', 'permission' => 'settings.manage'],
            ['icon' => 'settings', 'label' => 'Configurações', 'url' => '/admin/configuracoes', 'permission' => 'settings.view'],
        ];

        $userPermissions = $user['permissions'] ?? [];
        $currentUri = $_SERVER['REQUEST_URI'] ?? '';

        foreach ($menuItems as $item):
            $hasPermission = in_array('*', $userPermissions) || in_array($item['permission'], $userPermissions);
            if (!$hasPermission) continue;

            $isActive = str_starts_with($currentUri, $item['url']);
            $activeClass = $isActive ? 'bg-sidebar-hover text-white' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white';
        ?>
            <a href="<?= $item['url'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeClass ?>">
                <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>

        <!-- Separador -->
        <div class="border-t border-white/10 my-4"></div>

        <!-- IA -->
        <a href="/admin/ia" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= str_starts_with($currentUri, '/admin/ia') ? 'bg-sidebar-hover text-white' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' ?>">
            <i data-lucide="bot" class="w-5 h-5"></i>
            <span>Assistente IA</span>
        </a>

        <!-- Separador -->
        <div class="border-t border-white/10 my-4"></div>
        <?php if (in_array('*', $userPermissions)): ?>
            <p class="px-3 text-xs text-gray-500 uppercase tracking-wider mb-2">Sistema</p>
            <a href="/admin/versoes" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-sidebar-hover hover:text-white transition">
                <i data-lucide="git-branch" class="w-5 h-5"></i>
                <span>Versionamento</span>
            </a>
            <a href="/admin/backups" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-sidebar-hover hover:text-white transition">
                <i data-lucide="database" class="w-5 h-5"></i>
                <span>Backups</span>
            </a>
            <a href="/admin/logs" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-sidebar-hover hover:text-white transition">
                <i data-lucide="scroll" class="w-5 h-5"></i>
                <span>Logs</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
