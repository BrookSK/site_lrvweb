<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up">Blog</span>
        <h1 class="text-4xl md:text-5xl font-bold mt-4 animate-fade-up delay-100">Insights sobre <span class="text-gradient">tecnologia</span> e negócios</h1>
        <p class="text-gray-300 mt-4 max-w-xl mx-auto animate-fade-up delay-200">Artigos sobre desenvolvimento web, hospedagem, marketing digital e como escalar seu negócio online.</p>
    </div>
</section>

<!-- POSTS -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $i => $post): ?>
            <article class="group" data-animate="fade-up" data-delay="<?= ($i % 6) * 100 ?>">
                <a href="/<?= $locale ?>/blog/<?= $post['slug'] ?>" class="block">
                    <div class="relative aspect-[16/10] bg-gray-900 rounded-2xl overflow-hidden border border-white/5 group-hover:border-purple-500/30 transition-all duration-500 mb-5">
                        <?php if ($post['image']): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-purple-950 to-black flex items-center justify-center"><span class="text-4xl opacity-30">📝</span></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                        <time><?= isset($post['published_at']) ? date('d M Y', strtotime($post['published_at'])) : '' ?></time>
                        <?php if (!empty($post['category_name'])): ?>
                            <span class="w-1 h-1 bg-purple-500 rounded-full"></span>
                            <span class="text-purple-400"><?= htmlspecialchars($post['category_name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-purple-300 transition-colors line-clamp-2"><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="text-sm text-gray-400 mt-2 line-clamp-2"><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($posts)): ?>
            <div class="text-center py-20">
                <span class="text-5xl block mb-4">📝</span>
                <p class="text-gray-400 text-lg">Artigos sendo preparados. Volte em breve!</p>
            </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
        <div class="mt-16 flex justify-center gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium transition-all <?= $i === $currentPage ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/20' : 'bg-white/5 border border-white/10 text-gray-400 hover:border-purple-500/50 hover:text-white' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
