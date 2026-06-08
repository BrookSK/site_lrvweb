<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up"><?= \Core\I18n::get('about_badge') ?></span>
            <h1 class="text-4xl md:text-6xl font-bold mt-4 animate-fade-up delay-100"><?= \Core\I18n::get('about_title') ?></h1>
            <p class="text-lg text-gray-300 mt-6 max-w-2xl mx-auto animate-fade-up delay-200"><?= \Core\I18n::get('about_subtitle') ?></p>
        </div>
    </div>
</section>

<!-- HISTÓRIA -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-animate="fade-left">
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider"><?= \Core\I18n::get('about_history') ?></span>
                <h2 class="text-3xl font-bold mt-3 mb-6"><?= \Core\I18n::get('about_history_title') ?></h2>
                <p class="text-gray-400 leading-relaxed mb-4"><?= \Core\I18n::get('about_history_p1') ?></p>
                <p class="text-gray-400 leading-relaxed mb-4"><?= \Core\I18n::get('about_history_p2') ?></p>
                <p class="text-gray-400 leading-relaxed"><?= \Core\I18n::get('about_history_p3') ?></p>
            </div>
            <div class="relative" data-animate="fade-right">
                <div class="w-full aspect-square max-w-md mx-auto relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-purple-800/5 rounded-3xl border border-purple-500/20"></div>
                    <div class="absolute top-8 left-8 right-8 bottom-8 bg-gradient-to-br from-purple-950 to-black rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <i data-lucide="rocket" class="w-16 h-16 text-purple-400"></i>
                            <p class="text-2xl font-bold text-white">+8 Anos</p>
                            <p class="text-purple-300 text-sm">de Experiência</p>
                        </div>
                    </div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-purple-600/20 border border-purple-500/30 rounded-2xl flex items-center justify-center animate-float"><i data-lucide="zap" class="w-5 h-5 text-purple-400"></i></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center animate-float" style="animation-delay:1s"><i data-lucide="gem" class="w-5 h-5 text-purple-400"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MISSÃO, VISÃO, VALORES -->
<section class="section-gradient py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up"><?= \Core\I18n::get('about_pillars') ?></span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('about_pillars_title') ?></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-premium text-center" data-animate="fade-up" data-delay="100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600/20 to-purple-800/10 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-purple-500/20">
                    <i data-lucide="target" class="w-8 h-8 text-purple-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3"><?= \Core\I18n::get('about_mission') ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= \Core\I18n::get('about_mission_desc') ?></p>
            </div>
            <div class="card-premium text-center" data-animate="fade-up" data-delay="200">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600/20 to-purple-800/10 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-purple-500/20">
                    <i data-lucide="telescope" class="w-8 h-8 text-purple-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3"><?= \Core\I18n::get('about_vision') ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= \Core\I18n::get('about_vision_desc') ?></p>
            </div>
            <div class="card-premium text-center" data-animate="fade-up" data-delay="300">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600/20 to-purple-800/10 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-purple-500/20">
                    <i data-lucide="gem" class="w-8 h-8 text-purple-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3"><?= \Core\I18n::get('about_values') ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= \Core\I18n::get('about_values_desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- DIFERENCIAIS -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up"><?= \Core\I18n::get('about_why') ?></span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('about_why_title') ?></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $diffs = [
                ['shield', \Core\I18n::get('diff_security'), \Core\I18n::get('diff_security_desc')],
                ['zap', \Core\I18n::get('diff_performance'), \Core\I18n::get('diff_performance_desc')],
                ['headphones', \Core\I18n::get('diff_support'), \Core\I18n::get('diff_support_desc')],
                ['bar-chart-3', \Core\I18n::get('diff_results'), \Core\I18n::get('diff_results_desc')],
            ];
            foreach ($diffs as $i => $d): ?>
            <div class="text-center p-6" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <i data-lucide="<?= $d[0] ?>" class="w-8 h-8 text-purple-400 mx-auto mb-4"></i>
                <h3 class="text-white font-semibold mb-2"><?= $d[1] ?></h3>
                <p class="text-gray-400 text-sm"><?= $d[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-6" data-animate="fade-up"><?= \Core\I18n::get('about_cta_title') ?></h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('about_cta_desc') ?></p>
        <a href="/<?= $locale ?>/contato" class="btn-primary text-lg px-10" data-animate="fade-up" data-delay="200">
            <?= \Core\I18n::get('about_cta_btn') ?>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
