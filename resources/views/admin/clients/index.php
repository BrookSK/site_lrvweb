<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Clientes</h3>
        <p class="text-sm text-gray-500"><?= count($clients) ?> cadastrados</p>
    </div>
    <div class="flex gap-3">
        <input type="text" id="search-clients" placeholder="Buscar..." class="px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent" onkeyup="filterTable('search-clients','clients-table')">
        <a href="/admin/clientes/criar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Novo Cliente
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="clients-table">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Empresa</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">E-mail</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telefone</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($clients as $c): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                    <td class="px-4 py-3">
                        <a href="/admin/clientes/<?= $c['id'] ?>" class="font-medium text-gray-800 dark:text-white hover:text-purple-600 transition"><?= htmlspecialchars($c['name']) ?></a>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($c['company'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($c['email']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $c['is_active'] ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
                            <?= $c['is_active'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/clientes/<?= $c['id'] ?>" class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition" title="Ver"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            <a href="/admin/clientes/<?= $c['id'] ?>/editar" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="Editar"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                            <button onclick="confirmDelete('/admin/clientes/<?= $c['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Excluir"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">Nenhum cliente cadastrado. <a href="/admin/clientes/criar" class="text-purple-600 hover:underline">Adicionar primeiro cliente</a>.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
