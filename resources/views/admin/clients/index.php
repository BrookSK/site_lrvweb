<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="/admin/clientes/criar" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">+ Novo Cliente</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="table-container">
        <table>
            <thead><tr><th>Nome</th><th>Empresa</th><th>E-mail</th><th>Telefone</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                <tr>
                    <td class="font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['company'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
                    <td><span class="badge <?= $c['is_active'] ? 'badge-success' : 'badge-gray' ?>"><?= $c['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td><a href="/admin/clientes/<?= $c['id'] ?>" class="text-blue-600 hover:underline text-sm">Ver</a></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?><tr><td colspan="6" class="text-center text-gray-500 py-8">Nenhum cliente cadastrado.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
