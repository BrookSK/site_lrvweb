<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> - LRV Web</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        sidebar: { DEFAULT: '#1e293b', hover: '#334155' },
                    }
                }
            }
        }
    </script>

    <!-- Icons (Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= \Core\View::asset('css/admin.css') ?>">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <?php echo \Core\View::component('admin/sidebar', ['user' => $user ?? null]) ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Bar -->
            <?php echo \Core\View::component('admin/topbar', ['user' => $user ?? null, 'title' => $title ?? '']) ?>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                <?php echo \Core\View::component('admin/flash-messages') ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= \Core\View::asset('js/admin.js') ?>"></script>
    <script>
        lucide.createIcons();

        // Dark mode
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
