<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8"><?= htmlspecialchars($page['title']) ?></h1>
        <div class="prose dark:prose-invert max-w-none">
            <?= $page['content'] ?>
        </div>
    </div>
</section>
