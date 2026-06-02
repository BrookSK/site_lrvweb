<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Páginas (CMS)</h3>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slug</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($pages as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500 font-mono">/pagina/<?= htmlspecialchars($row['slug']) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $row['is_active'] ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 text-gray-600' ?>"><?= $row['is_active'] ? 'Ativa' : 'Inativa' ?></span></td>
                    <td class="px-4 py-3 text-right">
                        <a href="/admin/paginas/<?= $row['id'] ?>/editar" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg transition inline-flex items-center gap-1"><i data-lucide="pencil" class="w-3 h-3"></i> Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
