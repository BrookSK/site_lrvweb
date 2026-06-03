<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Financeiro</h3>
    <p class="text-sm text-gray-500 mt-1">Faturas e histórico de pagamentos.</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Vencimento</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($invoices as $inv): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($inv['description']) ?></td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-white">R$ <?= number_format((float)$inv['value'], 2, ',', '.') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y', strtotime($inv['due_date'])) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-medium <?= match($inv['status']) { 'paid' => 'bg-green-100 text-green-700', 'overdue' => 'bg-red-100 text-red-700', 'pending' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($inv['status']) { 'pending' => 'Pendente', 'paid' => 'Pago', 'overdue' => 'Atrasado', 'cancelled' => 'Cancelado', default => $inv['status'] } ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($invoices)): ?><tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhuma fatura.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
