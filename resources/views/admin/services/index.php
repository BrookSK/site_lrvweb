<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Serviços</h3>
    <button onclick="document.getElementById('modal-service').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Novo Serviço</button>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ícone</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Destaque</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ativo</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ordem</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($services as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-4 py-3 text-xl"><?= $row['icon'] ?? '—' ?></td>
                    <td class="px-4 py-3"><?= $row['is_featured'] ? '⭐' : '—' ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $row['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>"><?= $row['is_active'] ? 'Sim' : 'Não' ?></span></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= $row['sort_order'] ?></td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="confirmDelete('/admin/servicos/<?= $row['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($services)): ?><tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum serviço cadastrado.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Novo Serviço -->
<div id="modal-service" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Novo Serviço</h3>
        <form action="/admin/servicos" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ícone (emoji)</label><input type="text" name="icon" placeholder="🌐" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição curta</label><textarea name="short_description" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordem</label><input type="number" name="sort_order" value="0" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                <div class="flex items-end"><label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-purple-600"><span class="text-sm text-gray-700 dark:text-gray-300">Destaque</span></label></div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-service').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar</button>
            </div>
        </form>
    </div>
</div>
