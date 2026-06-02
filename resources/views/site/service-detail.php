<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($service['name']) ?></h1>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose dark:prose-invert max-w-none">
            <?= $service['description'] ?? '<p>Descrição em breve.</p>' ?>
        </div>

        <?php if ($service['price_from']): ?>
        <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
            <p class="text-lg font-semibold text-gray-800 dark:text-white">A partir de R$ <?= number_format((float)$service['price_from'], 2, ',', '.') ?></p>
        </div>
        <?php endif; ?>

        <div class="mt-8">
            <a href="/<?= $locale ?>/contato" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Solicitar Orçamento
            </a>
            <a href="/<?= $locale ?>/servicos" class="inline-block ml-4 text-blue-600 hover:underline">← Voltar</a>
        </div>
    </div>
</section>
