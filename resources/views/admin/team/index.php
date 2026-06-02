<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Equipe</h3>
    <a href="/admin/equipe/criar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Novo Membro</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">E-mail</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cargo</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perfil</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($users as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3"><div class="flex items-center gap-3"><div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-xs font-bold text-purple-700 dark:text-purple-300"><?= strtoupper(substr($row['name'], 0, 1)) ?></div><span class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($row['name']) ?></span></div></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['position'] ?? '—') ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300"><?= htmlspecialchars($row['role_name'] ?? '—') ?></span></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $row['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>"><?= $row['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="px-4 py-3 text-right">
                        <a href="/admin/equipe/<?= $row['id'] ?>/editar" class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                        <button onclick="confirmDelete('/admin/equipe/<?= $row['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?><tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum membro.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
