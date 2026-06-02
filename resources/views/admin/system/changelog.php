<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Changelog</h3>
        <p class="text-sm text-gray-500">Histórico completo de alterações</p>
    </div>
    <a href="/admin/versoes/changelog/exportar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
        <i data-lucide="download" class="w-4 h-4"></i> Exportar .md
    </a>
</div>

<div class="space-y-6">
    <?php foreach ($versions as $v): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-mono font-bold rounded-lg"><?= htmlspecialchars($v['version']) ?></span>
            <span class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($v['date'])) ?></span>
            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400"><?= $v['type'] ?></span>
        </div>
        <p class="text-gray-700 dark:text-gray-300 text-sm"><?= htmlspecialchars($v['description']) ?></p>
        <?php if ($v['changelog']): ?>
            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-pre-line"><?= htmlspecialchars($v['changelog']) ?></div>
        <?php endif; ?>
        <?php if ($v['modules_affected']): ?>
            <p class="mt-2 text-xs text-gray-500">Módulos: <?= htmlspecialchars($v['modules_affected']) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
