<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="/<?= $locale ?>/portfolio" class="inline-flex items-center gap-2 text-purple-300 hover:text-white text-sm mb-6 transition group" data-animate="fade-up">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <?= \Core\I18n::get('portfolio_back') ?>
        </a>
        <div class="max-w-3xl" data-animate="fade-up" data-delay="100">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider"><?= htmlspecialchars($portfolio['category_name'] ?? '') ?></span>
            <h1 class="text-3xl md:text-4xl font-bold text-white mt-2"><?= htmlspecialchars($portfolio['name']) ?></h1>
        </div>
    </div>
</section>

<!-- CONTEÚDO -->
<section class="section-dark py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Imagem principal -->
        <?php if ($portfolio['image_cover']): ?>
        <div class="rounded-2xl overflow-hidden border border-white/10 mb-12 shadow-2xl" data-animate="scale">
            <img src="<?= htmlspecialchars($portfolio['image_cover']) ?>" alt="<?= htmlspecialchars($portfolio['name']) ?>" class="w-full" loading="lazy">
        </div>
        <?php endif; ?>

        <!-- Grid: Info rápida -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12" data-animate="fade-up">
            <?php if ($portfolio['category_name']): ?>
            <div class="p-4 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Categoria</p>
                <p class="text-sm text-white font-semibold"><?= htmlspecialchars($portfolio['category_name']) ?></p>
            </div>
            <?php endif; ?>
            <?php if ($portfolio['completed_at']): ?>
            <div class="p-4 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Concluído em</p>
                <p class="text-sm text-white font-semibold"><?= date('M/Y', strtotime($portfolio['completed_at'])) ?></p>
            </div>
            <?php endif; ?>
            <?php if ($portfolio['url']): ?>
            <div class="p-4 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Status</p>
                <p class="text-sm text-green-400 font-semibold">🟢 Online</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Descrição -->
        <?php if ($portfolio['description']): ?>
        <div class="max-w-4xl mb-12" data-animate="fade-up">
            <h2 class="text-xl font-bold text-white mb-6">Sobre o Projeto</h2>
            <div class="prose prose-invert prose-purple max-w-none prose-headings:text-white prose-p:text-gray-300 prose-p:leading-relaxed prose-li:text-gray-300 prose-strong:text-white prose-a:text-purple-400">
                <?= html_entity_decode($portfolio['description'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tecnologias -->
        <?php if ($portfolio['technologies']): ?>
        <div class="mb-12" data-animate="fade-up">
            <h2 class="text-xl font-bold text-white mb-4"><?= \Core\I18n::get('portfolio_technologies') ?></h2>
            <div class="flex flex-wrap gap-2">
                <?php foreach (explode(',', $portfolio['technologies']) as $tech): ?>
                    <span class="px-4 py-2 bg-purple-600/10 border border-purple-500/20 rounded-xl text-sm text-purple-300 font-medium"><?= htmlspecialchars(trim($tech)) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- CTA: Visitar + Solicitar -->
        <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-white/5" data-animate="fade-up">
            <?php if ($portfolio['url']): ?>
            <a href="<?= htmlspecialchars($portfolio['url']) ?>" target="_blank" rel="noopener" class="btn-primary">
                <?= \Core\I18n::get('portfolio_visit') ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <?php endif; ?>
            <a href="/<?= $locale ?>/contato" class="btn-outline">
                Quer algo similar? Fale conosco
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
