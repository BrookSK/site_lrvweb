<?php $locale = \Core\I18n::getLocale(); ?>
<header class="fixed w-full top-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/<?= $locale ?>/" class="flex items-center gap-2">
                <span class="text-xl font-bold text-blue-600">LRV Web</span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-6">
                <a href="/<?= $locale ?>/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('home') ?></a>
                <a href="/<?= $locale ?>/sobre" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('about') ?></a>
                <a href="/<?= $locale ?>/servicos" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('services') ?></a>
                <a href="/<?= $locale ?>/hospedagem" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('hosting') ?></a>
                <a href="/<?= $locale ?>/portfolio" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('portfolio') ?></a>
                <a href="/<?= $locale ?>/blog" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('blog') ?></a>
                <a href="/<?= $locale ?>/contato" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 transition"><?= \Core\I18n::get('contact') ?></a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <!-- Language Switcher -->
                <div class="hidden md:flex items-center gap-1 text-xs">
                    <?php foreach (\Core\I18n::getAvailableLocales() as $lang): ?>
                        <a href="/<?= $lang ?>/" class="px-2 py-1 rounded <?= $lang === $locale ? 'bg-blue-600 text-white' : 'text-gray-500 hover:text-blue-600' ?> transition">
                            <?= strtoupper($lang) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Dark Mode -->
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <!-- CTA -->
                <a href="/<?= $locale ?>/contato" class="hidden md:inline-flex px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    <?= \Core\I18n::get('hero_cta') ?>
                </a>

                <!-- Mobile Menu -->
                <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Nav -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 p-4">
        <div class="space-y-3">
            <a href="/<?= $locale ?>/" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('home') ?></a>
            <a href="/<?= $locale ?>/sobre" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('about') ?></a>
            <a href="/<?= $locale ?>/servicos" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('services') ?></a>
            <a href="/<?= $locale ?>/hospedagem" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('hosting') ?></a>
            <a href="/<?= $locale ?>/portfolio" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('portfolio') ?></a>
            <a href="/<?= $locale ?>/blog" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('blog') ?></a>
            <a href="/<?= $locale ?>/contato" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600"><?= \Core\I18n::get('contact') ?></a>
        </div>
    </div>
</header>
<!-- Spacer -->
<div class="h-16"></div>

<script>
function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}
document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>
