<?php
$locale = \Core\I18n::getLocale();
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';

function isActive(string $path, string $uri): string {
    if ($path === '/' && ($uri === '/' || preg_match('#^/(pt|en|es)/?$#', $uri))) return 'active';
    if ($path !== '/' && str_contains($uri, $path)) return 'active';
    return '';
}
?>
<header id="main-header" class="fixed w-full top-0 z-50 transition-all duration-500">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="/<?= $locale ?>/" class="flex items-center gap-2 group">
                <?php
                $logoMain = \Core\Config::setting('branding.logo_main');
                $logoSystem = \Core\Config::setting('branding.logo_system');
                $logo = $logoMain ?: $logoSystem;
                ?>
                <?php if ($logo): ?>
                    <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-9 object-contain">
                <?php else: ?>
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center group-hover:shadow-lg group-hover:shadow-purple-500/30 transition-all duration-300">
                        <span class="text-white font-bold text-lg">L</span>
                    </div>
                    <span class="text-xl font-bold text-white">LRV<span class="text-purple-400">Web</span></span>
                <?php endif; ?>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center gap-1">
                <?php
                $menuItems = [
                    ['path' => '/', 'label' => \Core\I18n::get('home')],
                    ['path' => '/sobre', 'label' => \Core\I18n::get('about')],
                    ['path' => '/servicos', 'label' => \Core\I18n::get('services')],
                    ['path' => '/hospedagem', 'label' => \Core\I18n::get('hosting')],
                    ['path' => '/portfolio', 'label' => \Core\I18n::get('portfolio')],
                    ['path' => '/blog', 'label' => \Core\I18n::get('blog')],
                    ['path' => '/contato', 'label' => \Core\I18n::get('contact')],
                ];
                foreach ($menuItems as $item):
                    $active = isActive($item['path'], $currentUri);
                ?>
                <a href="/<?= $locale ?><?= $item['path'] === '/' ? '/' : $item['path'] ?>"
                   class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300
                          <?= $active ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                    <?= $item['label'] ?>
                    <?php if ($active): ?>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-purple-400 rounded-full"></span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Right -->
            <div class="flex items-center gap-3">
                <!-- Language -->
                <div class="hidden md:flex items-center gap-1 bg-white/5 rounded-lg p-1">
                    <?php
                    // Manter a página atual ao trocar idioma
                    $currentPath = preg_replace('#^/(pt|en|es)#', '', $currentUri) ?: '/';
                    foreach (\Core\I18n::getAvailableLocales() as $lang): ?>
                        <a href="/<?= $lang ?><?= $currentPath ?>" class="px-2.5 py-1 rounded-md text-xs font-medium transition-all <?= $lang === $locale ? 'bg-purple-600 text-white shadow-sm' : 'text-gray-400 hover:text-white' ?>">
                            <?= strtoupper($lang) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- CTA -->
                <a href="/<?= $locale ?>/contato" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-300 hover:-translate-y-0.5">
                    <?= \Core\I18n::get('hero_cta') ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                <!-- Mobile Toggle -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 text-gray-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-black/95 backdrop-blur-xl border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 py-6 space-y-2">
            <?php foreach ($menuItems as $item):
                $active = isActive($item['path'], $currentUri);
            ?>
            <a href="/<?= $locale ?><?= $item['path'] === '/' ? '/' : $item['path'] ?>"
               class="block px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $active ? 'text-white bg-purple-600/20 border-l-4 border-purple-500' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                <?= $item['label'] ?>
            </a>
            <?php endforeach; ?>
            <div class="pt-4 flex gap-2">
                <?php foreach (\Core\I18n::getAvailableLocales() as $lang): ?>
                    <a href="/<?= $lang ?><?= $currentPath ?>" class="px-4 py-2 rounded-lg text-sm <?= $lang === $locale ? 'bg-purple-600 text-white' : 'bg-white/5 text-gray-400' ?>"><?= strtoupper($lang) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</header>
<!-- Spacer -->
<div class="h-20"></div>

<script>
// Header scroll effect
const header = document.getElementById('main-header');
let lastScroll = 0;
window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;
    if (scrollY > 50) {
        header.classList.add('bg-black/90', 'backdrop-blur-xl', 'shadow-2xl', 'shadow-purple-900/10');
    } else {
        header.classList.remove('bg-black/90', 'backdrop-blur-xl', 'shadow-2xl', 'shadow-purple-900/10');
    }
    lastScroll = scrollY;
});

// Mobile menu
document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>
