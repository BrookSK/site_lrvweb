<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Log de Auditoria</h3>
        <p class="text-sm text-gray-500">Registro de todas as ações dos usuários</p>
    </div>
    <a href="/admin/logs" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4"></i> Logs de Sistema
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Usuário</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ação</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Módulo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($logs as $log): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-white"><?= htmlspecialchars($log['user_name'] ?? 'Sistema') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($log['action']) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300"><?= htmlspecialchars($log['module']) ?></span></td>
                    <td class="px-4 py-3 text-xs font-mono text-gray-500"><?= htmlspecialchars($log['ip_address'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Nenhum registro de auditoria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<div class="mt-6 flex justify-center gap-2">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="/admin/logs/auditoria?page=<?= $i ?>" class="px-3 py-1.5 rounded-lg text-sm <?= $i === $currentPage ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
