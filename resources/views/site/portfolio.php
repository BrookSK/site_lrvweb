<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up">Portfólio</span>
        <h1 class="text-4xl md:text-6xl font-bold mt-4 animate-fade-up delay-100">Projetos que <span class="text-gradient">geram resultados</span></h1>
        <p class="text-lg text-gray-300 mt-6 max-w-2xl mx-auto animate-fade-up delay-200">Cada projeto é único. Veja como transformamos ideias em soluções digitais de alto impacto.</p>
    </div>
</section>

<!-- FILTROS + GRID -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <?php if (!empty($categories)): ?>
        <div class="flex flex-wrap gap-2 mb-12 justify-center" data-animate="fade-up">
            <a href="/<?= $locale ?>/portfolio" class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all <?= !$currentCategory ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/20' : 'bg-white/5 border border-white/10 text-gray-300 hover:border-purple-500/50 hover:text-white' ?>">Todos</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/<?= $locale ?>/portfolio?categoria=<?= $cat['slug'] ?>" class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all <?= ($currentCategory ?? '') === $cat['slug'] ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/20' : 'bg-white/5 border border-white/10 text-gray-300 hover:border-purple-500/50 hover:text-white' ?>"><?= htmlspecialchars($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($portfolios as $i => $item): ?>
            <a href="/<?= $locale ?>/portfolio/<?= $item['slug'] ?>" class="group block" data-animate="fade-up" data-delay="<?= ($i % 6) * 100 ?>">
                <div class="relative aspect-[4/3] bg-gray-900 rounded-2xl overflow-hidden border border-white/5 group-hover:border-purple-500/50 transition-all duration-500">
                    <?php if ($item['image_cover']): ?>
                        <img src="<?= htmlspecialchars($item['image_cover']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-950 to-black"><span class="text-5xl opacity-50">🌐</span></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                        <div>
                            <p class="text-white font-semibold"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-purple-300 text-sm mt-1">Ver detalhes →</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-purple-400 font-medium uppercase tracking-wider"><?= htmlspecialchars($item['category_name'] ?? '') ?></span>
                    <h3 class="text-white font-semibold mt-1 group-hover:text-purple-300 transition-colors"><?= htmlspecialchars($item['name']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($portfolios)): ?>
            <div class="text-center py-20">
                <span class="text-5xl block mb-4">🎨</span>
                <p class="text-gray-400 text-lg">Projetos sendo adicionados em breve.</p>
                <a href="/<?= $locale ?>/contato" class="btn-primary mt-6 inline-flex">Solicitar Orçamento</a>
            </div>
        <?php endif; ?>
    </div>
</section>
