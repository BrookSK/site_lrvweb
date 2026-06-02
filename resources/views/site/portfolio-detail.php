<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-2"><?= htmlspecialchars($portfolio['name']) ?></h1>
        <p class="text-blue-100"><?= htmlspecialchars($portfolio['category_name'] ?? '') ?></p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($portfolio['image_cover']): ?>
            <img src="<?= htmlspecialchars($portfolio['image_cover']) ?>" alt="<?= htmlspecialchars($portfolio['name']) ?>" class="w-full rounded-xl shadow-lg mb-8">
        <?php endif; ?>

        <?php if ($portfolio['description']): ?>
            <div class="prose dark:prose-invert max-w-none mb-8"><?= $portfolio['description'] ?></div>
        <?php endif; ?>

        <?php if ($portfolio['technologies']): ?>
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Tecnologias</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($portfolio['technologies']) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($portfolio['url']): ?>
            <a href="<?= htmlspecialchars($portfolio['url']) ?>" target="_blank" rel="noopener" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Visitar Site →</a>
        <?php endif; ?>

        <div class="mt-8">
            <a href="/<?= $locale ?>/portfolio" class="text-blue-600 hover:underline">← Voltar ao Portfólio</a>
        </div>
    </div>
</section>
