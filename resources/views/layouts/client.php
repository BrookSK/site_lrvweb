<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Área do Cliente') ?> - LRV Web</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= \Core\View::asset('css/admin.css') ?>">
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Top Nav -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="/cliente/dashboard" class="text-xl font-bold text-blue-600">LRV Web</a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/cliente/projetos" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Projetos</a>
                <a href="/cliente/orcamentos" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Orçamentos</a>
                <a href="/cliente/documentos" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Documentos</a>
                <a href="/cliente/financeiro" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Financeiro</a>
                <a href="/cliente/chamados" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Suporte</a>
            </nav>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 dark:text-gray-300"><?= htmlspecialchars($user['name'] ?? '') ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:underline">Sair</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php echo \Core\View::component('admin/flash-messages') ?>
        <?= $content ?>
    </main>

    <script>lucide.createIcons();</script>
    <script>if(localStorage.theme==='dark')document.documentElement.classList.add('dark');</script>
</body>
</html>
