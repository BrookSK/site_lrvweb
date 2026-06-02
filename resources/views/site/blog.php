<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('blog') ?></h1>
        <p class="text-blue-100 text-lg">Artigos sobre tecnologia, hospedagem e marketing digital.</p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
            <article class="card-hover bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <?php if ($post['image']): ?>
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover" loading="lazy">
                    </div>
                <?php endif; ?>
                <div class="p-5">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                        <time><?= isset($post['published_at']) ? date('d/m/Y', strtotime($post['published_at'])) : '' ?></time>
                        <?php if (!empty($post['category_name'])): ?>
                            <span>•</span>
                            <span class="text-blue-600"><?= htmlspecialchars($post['category_name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white line-clamp-2">
                        <a href="/<?= $locale ?>/blog/<?= $post['slug'] ?>" class="hover:text-blue-600 transition"><?= htmlspecialchars($post['title']) ?></a>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($posts)): ?>
            <p class="text-center text-gray-500 dark:text-gray-400 py-12">Nenhum artigo publicado ainda.</p>
        <?php endif; ?>

        <!-- Paginação -->
        <?php if ($totalPages > 1): ?>
        <div class="mt-12 flex justify-center gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="px-4 py-2 rounded-lg text-sm <?= $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
