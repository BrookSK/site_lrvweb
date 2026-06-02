<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('portfolio') ?></h1>
        <p class="text-blue-100 text-lg">Conheça alguns dos nossos projetos.</p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <?php if (!empty($categories)): ?>
        <div class="flex flex-wrap gap-2 mb-10 justify-center">
            <a href="/<?= $locale ?>/portfolio" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$currentCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' ?> transition">Todos</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/<?= $locale ?>/portfolio?categoria=<?= $cat['slug'] ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $currentCategory === $cat['slug'] ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' ?> transition"><?= htmlspecialchars($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($portfolios as $item): ?>
            <a href="/<?= $locale ?>/portfolio/<?= $item['slug'] ?>" class="card-hover group block" data-animate>
                <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden mb-4">
                    <?php if ($item['image_cover']): ?>
                        <img src="<?= htmlspecialchars($item['image_cover']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400"><span class="text-4xl">🌐</span></div>
                    <?php endif; ?>
                </div>
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium"><?= htmlspecialchars($item['category_name'] ?? '') ?></span>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mt-1"><?= htmlspecialchars($item['name']) ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"><?= htmlspecialchars($item['description'] ?? '') ?></p>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($portfolios)): ?>
            <p class="text-center text-gray-500 dark:text-gray-400 py-12">Projetos em breve.</p>
        <?php endif; ?>
    </div>
</section>
