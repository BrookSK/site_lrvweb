<div class="max-w-4xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="/admin/paginas/<?= $page['id'] ?>" method="POST" class="space-y-6">
            <?= \Core\View::csrf() ?>
            <?= \Core\View::method('PUT') ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                <input type="text" name="meta_description" value="<?= htmlspecialchars($page['meta_description'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conteúdo (HTML)</label>
                <textarea name="content" rows="20" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm font-mono"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" <?= ($page['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Página ativa</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar</button>
                <a href="/admin/paginas" class="px-6 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Voltar</a>
            </div>
        </form>
    </div>
</div>
