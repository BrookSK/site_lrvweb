<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('services') ?></h1>
        <p class="text-blue-100 text-lg">Soluções completas para o seu negócio digital.</p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="card-hover bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl"><?= $service['icon'] ?? '🌐' ?></span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2"><?= htmlspecialchars($service['name']) ?></h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4"><?= htmlspecialchars($service['short_description'] ?? '') ?></p>
                <?php if ($service['price_from']): ?>
                    <p class="text-sm text-blue-600 font-medium">A partir de R$ <?= number_format((float)$service['price_from'], 2, ',', '.') ?></p>
                <?php endif; ?>
                <a href="/<?= $locale ?>/servicos/<?= $service['slug'] ?>" class="inline-block mt-4 text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">
                    Saiba mais →
                </a>
            </div>
            <?php endforeach; ?>

            <?php if (empty($services)): ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">Serviços em breve.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
