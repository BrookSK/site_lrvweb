<?php $locale = \Core\I18n::getLocale(); ?>

<article class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Meta -->
        <div class="mb-6">
            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                <time><?= date('d/m/Y', strtotime($post['published_at'])) ?></time>
                <span>•</span>
                <span><?= htmlspecialchars($post['author_name'] ?? 'LRV Web') ?></span>
                <?php if ($post['category_name']): ?>
                    <span>•</span>
                    <span class="text-blue-600"><?= htmlspecialchars($post['category_name']) ?></span>
                <?php endif; ?>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($post['title']) ?></h1>
        </div>

        <?php if ($post['image']): ?>
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full rounded-xl mb-8">
        <?php endif; ?>

        <!-- Conteúdo -->
        <div class="prose dark:prose-invert max-w-none">
            <?= $post['content'] ?>
        </div>

        <!-- Tags -->
        <?php if ($post['tags']): ?>
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap gap-2">
                <?php foreach (explode(',', $post['tags']) as $tag): ?>
                    <a href="/<?= $locale ?>/blog/tag/<?= trim($tag) ?>" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs text-gray-600 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 transition"><?= htmlspecialchars(trim($tag)) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Relacionados -->
        <?php if (!empty($related)): ?>
        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Artigos Relacionados</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($related as $r): ?>
                <a href="/<?= $locale ?>/blog/<?= $r['slug'] ?>" class="group">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200 group-hover:text-blue-600 transition line-clamp-2"><?= htmlspecialchars($r['title']) ?></h4>
                    <p class="text-xs text-gray-500 mt-1"><?= date('d/m/Y', strtotime($r['published_at'])) ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-8">
            <a href="/<?= $locale ?>/blog" class="text-blue-600 hover:underline">← Voltar ao Blog</a>
        </div>
    </div>
</article>
