<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($budget['name']) ?></h3>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($budget['client_name'] ?? '') ?> <?= $budget['client_company'] ? '— ' . htmlspecialchars($budget['client_company']) : '' ?></p>
    </div>
    <div class="flex gap-3">
        <a href="/admin/orcamentos/<?= $budget['id'] ?>/editar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Editar</a>
        <a href="/admin/orcamentos/<?= $budget['id'] ?>/duplicar" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Duplicar</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Blocos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Blocos de Serviço</h4>
            <?php if (!empty($budget['blocks'])): ?>
            <div class="space-y-3">
                <?php foreach ($budget['blocks'] as $block): ?>
                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($block['title']) ?></p>
                            <?php if ($block['description']): ?><p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($block['description']) ?></p><?php endif; ?>
                        </div>
                        <span class="text-sm font-bold text-purple-600 whitespace-nowrap">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></span>
                    </div>
                    <?php if ($block['deadline']): ?><p class="text-xs text-gray-400 mt-2">⏱ <?= htmlspecialchars($block['deadline']) ?></p><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p class="text-sm text-gray-500 text-center py-6">Nenhum bloco adicionado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Resumo</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400"><?= $budget['status'] ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="text-gray-800 dark:text-white">R$ <?= number_format((float)($budget['total_value'] ?? 0), 2, ',', '.') ?></span></div>
                <?php if ((float)($budget['discount_value'] ?? 0) > 0): ?>
                <div class="flex justify-between"><span class="text-gray-500">Desconto (<?= $budget['discount_percent'] ?>%)</span><span class="text-green-600">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></span></div>
                <?php endif; ?>
                <div class="flex justify-between pt-2 border-t border-gray-100 dark:border-gray-700"><span class="text-gray-800 dark:text-white font-bold">Valor Final</span><span class="text-purple-600 font-bold text-lg">R$ <?= number_format((float)($budget['final_value'] ?? 0), 2, ',', '.') ?></span></div>
                <?php if ($budget['installments'] > 1): ?>
                <div class="flex justify-between"><span class="text-gray-500"><?= $budget['installments'] ?>x de</span><span class="text-gray-800 dark:text-white">R$ <?= number_format((float)($budget['installment_value'] ?? 0), 2, ',', '.') ?></span></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Detalhes</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Criado em</span><span class="text-gray-800 dark:text-white"><?= date('d/m/Y', strtotime($budget['created_at'])) ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Validade</span><span class="text-gray-800 dark:text-white"><?= $budget['validity_date'] ? date('d/m/Y', strtotime($budget['validity_date'])) : '—' ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Pagamento</span><span class="text-gray-800 dark:text-white"><?= match($budget['payment_type']) { 'one_time' => 'À Vista', 'monthly' => 'Mensal', 'installments' => 'Parcelado', default => '—' } ?></span></div>
            </div>
        </div>

        <!-- Link público -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
            <p class="text-xs font-medium text-purple-700 dark:text-purple-300 mb-2">Link público:</p>
            <a href="/orcamento/<?= $budget['hash'] ?>" target="_blank" class="text-sm text-purple-600 hover:underline break-all"><?= rtrim(\Core\Config::get('app.url', ''), '/') ?>/orcamento/<?= $budget['hash'] ?></a>
        </div>
    </div>
</div>
