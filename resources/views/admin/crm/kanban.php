<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">CRM — Funil de Vendas</h3>
        <p class="text-sm text-gray-500">Arraste os cards para mover entre estágios</p>
    </div>
    <button onclick="document.getElementById('modal-lead').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Novo Lead
    </button>
</div>

<!-- Kanban Board -->
<div class="flex gap-4 overflow-x-auto pb-4" style="min-height: 500px;">
    <?php foreach ($stages as $key => $stage): ?>
    <div class="flex-shrink-0 w-72">
        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-3 h-full">
            <div class="flex items-center justify-between mb-3 px-1">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= $stage['label'] ?></h4>
                <span class="text-xs bg-white dark:bg-gray-800 px-2 py-0.5 rounded-full text-gray-500 border border-gray-200 dark:border-gray-700"><?= count($stage['leads']) ?></span>
            </div>
            <div class="space-y-2 min-h-[200px]">
                <?php foreach ($stage['leads'] as $lead): ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition cursor-grab">
                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($lead['name']) ?></p>
                    <?php if ($lead['company']): ?><p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($lead['company']) ?></p><?php endif; ?>
                    <?php if ($lead['value']): ?><p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">R$ <?= number_format((float)$lead['value'], 2, ',', '.') ?></p><?php endif; ?>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs text-gray-400"><?= htmlspecialchars($lead['responsible_name'] ?? '—') ?></span>
                        <span class="text-xs text-gray-400"><?= $lead['source'] ? htmlspecialchars($lead['source']) : '' ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Novo Lead -->
<div id="modal-lead" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Novo Lead</h3>
        <form action="/admin/crm/leads" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail</label><input type="email" name="email" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label><input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Empresa</label><input type="text" name="company" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Origem</label><input type="text" name="source" placeholder="Site, Indicação..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor (R$)</label><input type="number" step="0.01" name="value" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Responsável</label>
                <select name="responsible_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                    <option value="">Nenhum</option>
                    <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-lead').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Criar Lead</button>
            </div>
        </form>
    </div>
</div>
