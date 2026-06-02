<?php $locale = \Core\I18n::getLocale(); ?>

<section class="hero-gradient py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="/<?= $locale ?>/portfolio" class="inline-flex items-center gap-2 text-purple-300 hover:text-white text-sm mb-6 transition" data-animate="fade-up">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <?= \Core\I18n::get('portfolio_back') ?>
        </a>
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider block" data-animate="fade-up" data-delay="100"><?= htmlspecialchars($portfolio['category_name'] ?? 'Projeto') ?></span>
        <h1 class="text-3xl md:text-5xl font-bold mt-3" data-animate="fade-up" data-delay="200"><?= htmlspecialchars($portfolio['name']) ?></h1>
    </div>
</section>

<section class="section-dark py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($portfolio['image_cover']): ?>
            <div class="rounded-2xl overflow-hidden border border-white/10 mb-12 shadow-2xl" data-animate="scale">
                <img src="<?= htmlspecialchars($portfolio['image_cover']) ?>" alt="<?= htmlspecialchars($portfolio['name']) ?>" class="w-full">
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2" data-animate="fade-left">
                <?php if ($portfolio['description']): ?>
                    <div class="prose prose-invert prose-purple max-w-none text-gray-300 leading-relaxed">
                        <?= $portfolio['description'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6" data-animate="fade-right">
                <?php if ($portfolio['technologies']): ?>
                <div class="card-premium">
                    <h3 class="font-semibold text-white mb-3"><?= \Core\I18n::get('portfolio_technologies') ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (explode(',', $portfolio['technologies']) as $tech): ?>
                            <span class="px-3 py-1.5 bg-purple-600/10 border border-purple-500/20 rounded-lg text-xs text-purple-300"><?= htmlspecialchars(trim($tech)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($portfolio['client_name']): ?>
                <div class="card-premium">
                    <h3 class="font-semibold text-white mb-2"><?= \Core\I18n::get('portfolio_client') ?></h3>
                    <p class="text-gray-400 text-sm"><?= htmlspecialchars($portfolio['client_name']) ?></p>
                </div>
                <?php endif; ?>

                <?php if ($portfolio['url']): ?>
                <a href="<?= htmlspecialchars($portfolio['url']) ?>" target="_blank" rel="noopener" class="btn-primary w-full justify-center">
                    <?= \Core\I18n::get('portfolio_visit') ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
