<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Logs do Sistema</h3>
        <p class="text-sm text-gray-500">Visualização dos arquivos de log</p>
    </div>
    <a href="/admin/logs/auditoria" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
        <i data-lucide="shield" class="w-4 h-4"></i> Auditoria
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Lista de arquivos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Arquivos</h4>
        <div class="space-y-1 max-h-96 overflow-y-auto">
            <?php foreach ($files as $f): ?>
            <a href="/admin/logs?file=<?= urlencode($f) ?>" class="block px-3 py-2 rounded-lg text-xs font-mono transition <?= $f === $selectedFile ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                <?= htmlspecialchars($f) ?>
            </a>
            <?php endforeach; ?>
            <?php if (empty($files)): ?>
                <p class="text-xs text-gray-500 px-3">Nenhum arquivo de log.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Conteúdo do log -->
    <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?= htmlspecialchars($selectedFile ?: 'Selecione um arquivo') ?></span>
        </div>
        <div class="p-4 overflow-auto" style="max-height: 600px;">
            <?php if ($content): ?>
                <pre class="text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($content) ?></pre>
            <?php else: ?>
                <p class="text-sm text-gray-500 text-center py-12">Selecione um arquivo para visualizar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
