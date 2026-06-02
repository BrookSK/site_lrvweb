<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Backups</h3>
        <p class="text-sm text-gray-500">Gerenciamento de backups do banco de dados</p>
    </div>
    <form action="/admin/backups/criar" method="POST">
        <?= \Core\View::csrf() ?>
        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2" onclick="return confirm('Criar backup agora?')">
            <i data-lucide="database" class="w-4 h-4"></i> Criar Backup
        </button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Arquivo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamanho</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Criado por</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($backups as $b): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                    <td class="px-4 py-3 text-sm font-mono text-gray-800 dark:text-white"><?= htmlspecialchars($b['filename']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= $b['size'] ? number_format($b['size'] / 1024 / 1024, 2) . ' MB' : '—' ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $b['type'] === 'automatic' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
                            <?= $b['type'] === 'automatic' ? 'Automático' : 'Manual' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($b['created_by_name'] ?? 'Sistema') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/backups/<?= $b['id'] ?>/download" class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 transition" title="Download"><i data-lucide="download" class="w-4 h-4"></i></a>
                            <form action="/admin/backups/<?= $b['id'] ?>/restaurar" method="POST" class="inline" onsubmit="return confirm('Tem certeza? Isso vai sobrescrever o banco atual.')">
                                <?= \Core\View::csrf() ?>
                                <button class="p-1.5 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition" title="Restaurar"><i data-lucide="rotate-ccw" class="w-4 h-4"></i></button>
                            </form>
                            <button onclick="confirmDelete('/admin/backups/<?= $b['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Excluir"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($backups)): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum backup realizado. Clique em "Criar Backup" para gerar o primeiro.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
