<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Blog</h3>
        <p class="text-sm text-gray-500"><?= count($posts) ?> posts</p>
    </div>
    <div class="flex gap-3">
        <a href="/admin/blog/ia/configuracoes" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2"><i data-lucide="bot" class="w-4 h-4"></i> IA</a>
        <a href="/admin/blog/criar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Novo Post</a>
    </div>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoria</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Idioma</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($posts as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['category_name'] ?? '—') ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $row['status'] === 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' ?>"><?= $row['status'] ?></span></td>
                    <td class="px-4 py-3 text-xs text-gray-500 uppercase"><?= $row['language'] ?? 'pt' ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= $row['published_at'] ? date('d/m/Y', strtotime($row['published_at'])) : '—' ?></td>
                    <td class="px-4 py-3 text-right">
                        <a href="/admin/blog/<?= $row['id'] ?>/editar" class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition" title="Editar"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                        <button onclick="confirmDelete('/admin/blog/<?= $row['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Excluir"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($posts)): ?><tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum post. <a href="/admin/blog/criar" class="text-purple-600 hover:underline">Criar primeiro post</a>.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
