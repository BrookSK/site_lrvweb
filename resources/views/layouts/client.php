<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Área do Cliente') ?> - LRV Web</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php $favicon = \Core\Config::setting('branding.favicon'); ?>
    <?php if ($favicon): ?><link rel="icon" href="<?= htmlspecialchars($favicon) ?>"><?php endif; ?>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen">
    <!-- Header -->
    <header class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/cliente/dashboard" class="flex items-center gap-2">
                <?php
                $clientLogo = \Core\Config::setting('branding.logo_main') ?: \Core\Config::setting('branding.logo_system');
                ?>
                <?php if ($clientLogo): ?>
                    <img src="<?= htmlspecialchars($clientLogo) ?>" alt="LRV Web" class="h-8 object-contain brightness-0 invert">
                <?php else: ?>
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">L</span>
                    </div>
                    <span class="text-lg font-bold text-white">LRV<span class="text-purple-400">Web</span></span>
                <?php endif; ?>
            </a>

            <!-- Nav -->
            <nav class="hidden md:flex items-center gap-1">
                <?php
                $clientUri = $_SERVER['REQUEST_URI'] ?? '';
                $clientNav = [
                    ['/cliente/dashboard', 'Dashboard', 'layout-dashboard'],
                    ['/cliente/projetos', 'Projetos', 'folder'],
                    ['/cliente/orcamentos', 'Orçamentos', 'file-text'],
                    ['/cliente/documentos', 'Documentos', 'file-archive'],
                    ['/cliente/financeiro', 'Financeiro', 'wallet'],
                    ['/cliente/chamados', 'Suporte', 'message-circle'],
                ];
                foreach ($clientNav as $item):
                    $isActive = str_starts_with($clientUri, $item[0]);
                ?>
                <a href="<?= $item[0] ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition <?= $isActive ? 'bg-purple-600/20 text-purple-400 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                    <i data-lucide="<?= $item[2] ?>" class="w-4 h-4"></i>
                    <span class="hidden lg:inline"><?= $item[1] ?></span>
                </a>
                <?php endforeach; ?>
            </nav>

            <!-- User -->
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-400 hidden sm:inline"><?= htmlspecialchars($user['name'] ?? '') ?></span>
                <a href="/logout" class="px-3 py-1.5 text-xs font-medium text-red-400 hover:text-red-300 border border-red-800 hover:border-red-700 rounded-lg transition">Sair</a>
            </div>
        </div>

        <!-- Mobile nav -->
        <div class="md:hidden border-t border-gray-800 px-4 py-2 flex gap-1 overflow-x-auto">
            <?php foreach ($clientNav as $item):
                $isActive = str_starts_with($clientUri, $item[0]);
            ?>
            <a href="<?= $item[0] ?>" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs whitespace-nowrap <?= $isActive ? 'bg-purple-600/20 text-purple-400' : 'text-gray-500' ?>">
                <i data-lucide="<?= $item[2] ?>" class="w-3.5 h-3.5"></i><?= $item[1] ?>
            </a>
            <?php endforeach; ?>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php echo \Core\View::component('admin/flash-messages') ?>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-600">
            &copy; <?= date('Y') ?> LRV Web — Área do Cliente
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
