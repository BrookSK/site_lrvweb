<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-white">Newsletter</h3>
        <p class="text-sm text-gray-500"><?= count($subscribers) ?> inscrito(s)</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">E-mail</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data de Cadastro</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($subscribers as $s): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm text-white font-medium"><?= htmlspecialchars($s['email']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-400"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= $s['is_active'] ? 'bg-green-900/30 text-green-400' : 'bg-gray-700 text-gray-400' ?>"><?= $s['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="confirmDelete('/admin/newsletter/<?= $s['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Remover"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($subscribers)): ?>
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhum inscrito ainda.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
