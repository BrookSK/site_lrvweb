<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($client['name']) ?></h3>
                <a href="/admin/clientes/<?= $client['id'] ?>/editar" class="px-3 py-1.5 text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 hover:underline font-medium">Editar</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">Empresa:</span><span class="text-white"><?= htmlspecialchars($client['company'] ?? '—') ?></span></div>
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">E-mail:</span><span class="text-white"><?= htmlspecialchars($client['email']) ?></span></div>
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">Telefone:</span><span class="text-white"><?= htmlspecialchars($client['phone'] ?? '—') ?></span></div>
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">WhatsApp:</span><span class="text-white"><?= htmlspecialchars($client['whatsapp'] ?? '—') ?></span></div>
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">CPF/CNPJ:</span><span class="text-white"><?= htmlspecialchars($client['cpf_cnpj'] ?? '—') ?></span></div>
                <div class="flex items-center gap-2"><span class="text-gray-400 dark:text-gray-500 w-20 flex-shrink-0">Website:</span><span class="text-white"><?= $client['website'] ? '<a href="' . htmlspecialchars($client['website']) . '" target="_blank" class="text-purple-400 hover:underline">' . htmlspecialchars($client['website']) . '</a>' : '—' ?></span></div>
            </div>
            <?php if ($client['notes']): ?>
            <div class="mt-4 pt-4 border-t border-gray-700">
                <p class="text-xs text-gray-500 mb-1">Observações</p>
                <p class="text-sm text-gray-300"><?= nl2br(htmlspecialchars($client['notes'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Projetos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-white mb-4">Projetos (<?= count($projects) ?>)</h4>
            <div class="space-y-2">
                <?php foreach ($projects as $p): ?>
                <a href="/admin/projetos/<?= $p['id'] ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-700/50 transition">
                    <span class="text-sm text-gray-200"><?= htmlspecialchars($p['name']) ?></span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($p['status']) { 'completed' => 'bg-green-900/30 text-green-400', 'development' => 'bg-yellow-900/30 text-yellow-400', default => 'bg-blue-900/30 text-blue-400' } ?>"><?= $p['status'] ?></span>
                </a>
                <?php endforeach; ?>
                <?php if (empty($projects)): ?><p class="text-sm text-gray-500">Nenhum projeto.</p><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Orçamentos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-white mb-4">Orçamentos (<?= count($budgets) ?>)</h4>
            <div class="space-y-2">
                <?php foreach ($budgets as $b): ?>
                <a href="/admin/orcamentos/<?= $b['id'] ?>" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-700/50 transition">
                    <span class="text-sm text-gray-300 truncate mr-2"><?= htmlspecialchars($b['name']) ?></span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium flex-shrink-0 <?= match($b['status']) { 'approved' => 'bg-green-900/30 text-green-400', 'rejected' => 'bg-red-900/30 text-red-400', 'sent','viewed' => 'bg-blue-900/30 text-blue-400', default => 'bg-gray-700 text-gray-400' } ?>"><?= $b['status'] ?></span>
                </a>
                <?php endforeach; ?>
                <?php if (empty($budgets)): ?><p class="text-sm text-gray-500">Nenhum orçamento.</p><?php endif; ?>
            </div>
        </div>

        <!-- Financeiro -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-white mb-4">Faturas (<?= count($invoices) ?>)</h4>
            <div class="space-y-2">
                <?php foreach (array_slice($invoices, 0, 5) as $inv): ?>
                <div class="flex items-center justify-between p-2">
                    <span class="text-sm text-gray-300 truncate mr-2"><?= htmlspecialchars($inv['description']) ?></span>
                    <span class="text-xs font-medium <?= $inv['status'] === 'paid' ? 'text-green-400' : ($inv['status'] === 'overdue' ? 'text-red-400' : 'text-yellow-400') ?>">R$ <?= number_format((float)$inv['value'], 2, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($invoices)): ?><p class="text-sm text-gray-500">Nenhuma fatura.</p><?php endif; ?>
            </div>
        </div>
    </div>
</div>
