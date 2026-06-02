<?php $locale = \Core\I18n::getLocale(); ?>

<section class="hero-gradient py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="/<?= $locale ?>/servicos" class="inline-flex items-center gap-2 text-purple-300 hover:text-white text-sm mb-6 transition" data-animate="fade-up">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <?= \Core\I18n::get('services_subtitle') ?>
        </a>
        <div class="flex items-center gap-4" data-animate="fade-up" data-delay="100">
            <?php if (!empty($service['icon'])): ?>
                <span class="text-5xl"><?= $service['icon'] ?></span>
            <?php endif; ?>
            <div>
                <h1 class="text-3xl md:text-5xl font-bold"><?= htmlspecialchars($service['name']) ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="section-dark py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium" data-animate="fade-up">
            <div class="prose prose-invert prose-purple max-w-none prose-headings:text-white prose-p:text-gray-300">
                <?= $service['description'] ?? '<p>Detalhes deste serviço serão adicionados em breve. Entre em contato para saber mais.</p>' ?>
            </div>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row gap-4" data-animate="fade-up" data-delay="200">
            <a href="/<?= $locale ?>/contato" class="btn-primary">
                <?= \Core\I18n::get('request_quote') ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="/<?= $locale ?>/servicos" class="btn-outline"><?= \Core\I18n::get('view_all') ?></a>
        </div>
    </div>
</section>
