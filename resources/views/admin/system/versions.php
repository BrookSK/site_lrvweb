<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Versionamento</h3>
        <p class="text-sm text-gray-500">Histórico de versões do sistema</p>
    </div>
    <div class="flex gap-3">
        <a href="/admin/versoes/changelog" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4"></i> Changelog
        </a>
        <button onclick="document.getElementById('modal-version').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Nova Versão
        </button>
    </div>
</div>

<!-- Lista de versões -->
<div class="space-y-4">
    <?php foreach ($versions as $v): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-mono font-bold rounded-lg"><?= htmlspecialchars($v['version']) ?></span>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($v['description']) ?></p>
                    <p class="text-xs text-gray-500 mt-0.5"><?= date('d/m/Y', strtotime($v['date'])) ?> · <?= htmlspecialchars($v['responsible_name'] ?? 'Sistema') ?></p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= match($v['type']) { 'major' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400', 'minor' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400', 'patch' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400', default => 'bg-gray-100 dark:bg-gray-700 text-gray-600' } ?>">
                <?= $v['type'] ?>
            </span>
        </div>
        <?php if ($v['modules_affected']): ?>
            <p class="mt-2 text-xs text-gray-500">Módulos: <?= htmlspecialchars($v['modules_affected']) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if (empty($versions)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-100 dark:border-gray-700 text-center">
            <i data-lucide="git-branch" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
            <p class="text-gray-500">Nenhuma versão registrada.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Nova Versão -->
<div id="modal-version" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Registrar Nova Versão</h3>
        <form action="/admin/versoes" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Versão *</label>
                    <input type="text" name="version" required placeholder="1.2.0" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="patch">Patch</option>
                        <option value="minor" selected>Minor</option>
                        <option value="major">Major</option>
                        <option value="hotfix">Hotfix</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição *</label>
                <textarea name="description" rows="3" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Módulos afetados</label>
                <input type="text" name="modules_affected" placeholder="CRM, Orçamentos, Blog" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-version').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar</button>
            </div>
        </form>
    </div>
</div>
