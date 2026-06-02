<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
    <!-- Left: Menu toggle + Title -->
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($title ?? '') ?></h2>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-4">
        <!-- Dark Mode Toggle -->
        <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <i data-lucide="moon" class="w-5 h-5 dark:hidden"></i>
            <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
        </button>

        <!-- Notifications -->
        <a href="/admin/notificacoes" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition relative">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center notification-badge hidden">0</span>
        </a>

        <!-- User Menu -->
        <div class="relative" id="user-menu">
            <button onclick="document.getElementById('user-dropdown').classList.toggle('hidden')" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                    <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden md:block"><?= htmlspecialchars($user['name'] ?? '') ?></span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
            </button>

            <!-- Dropdown -->
            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($user['role_display'] ?? '') ?></p>
                </div>
                <a href="/admin/configuracoes" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Configurações</a>
                <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Sair</a>
            </div>
        </div>
    </div>
</header>

<script>
function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}
</script>
