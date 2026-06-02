<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($client['name']) ?></h3>
                <a href="/admin/clientes/<?= $client['id'] ?>/editar" class="text-sm text-blue-600 hover:underline">Editar</a>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Empresa:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['company'] ?? '-') ?></span></div>
                <div><span class="text-gray-500">E-mail:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['email']) ?></span></div>
                <div><span class="text-gray-500">Telefone:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['phone'] ?? '-') ?></span></div>
                <div><span class="text-gray-500">WhatsApp:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['whatsapp'] ?? '-') ?></span></div>
                <div><span class="text-gray-500">CPF/CNPJ:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['cpf_cnpj'] ?? '-') ?></span></div>
                <div><span class="text-gray-500">Website:</span> <span class="text-gray-800 dark:text-white"><?= htmlspecialchars($client['website'] ?? '-') ?></span></div>
            </div>
        </div>
        <!-- Projetos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Projetos (<?= count($projects) ?>)</h4>
            <?php foreach ($projects as $p): ?><div class="py-2 border-b border-gray-100 dark:border-gray-700 last:border-0 flex justify-between"><span class="text-sm"><?= htmlspecialchars($p['name']) ?></span><span class="badge badge-info text-xs"><?= $p['status'] ?></span></div><?php endforeach; ?>
            <?php if (empty($projects)): ?><p class="text-sm text-gray-500">Nenhum projeto.</p><?php endif; ?>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Orçamentos (<?= count($budgets) ?>)</h4>
            <?php foreach ($budgets as $b): ?><div class="py-2 border-b border-gray-100 dark:border-gray-700 last:border-0 text-sm"><?= htmlspecialchars($b['name']) ?> <span class="badge badge-<?= $b['status'] === 'approved' ? 'success' : 'warning' ?> text-xs ml-2"><?= $b['status'] ?></span></div><?php endforeach; ?>
            <?php if (empty($budgets)): ?><p class="text-sm text-gray-500">Nenhum orçamento.</p><?php endif; ?>
        </div>
    </div>
</div>
