<?php $revenue = $stats['monthly_revenue'] ?? 0; $expense = $stats['monthly_expense'] ?? 0; $profit = $stats['monthly_profit'] ?? 0; ?>

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Receita Mensal</p>
        <p class="text-xl font-bold text-green-600">R$ <?= number_format($revenue, 2, ',', '.') ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Despesa Mensal</p>
        <p class="text-xl font-bold text-red-600">R$ <?= number_format($expense, 2, ',', '.') ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Lucro</p>
        <p class="text-xl font-bold <?= $profit >= 0 ? 'text-green-600' : 'text-red-600' ?>">R$ <?= number_format($profit, 2, ',', '.') ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Ticket Médio</p>
        <p class="text-xl font-bold text-purple-600">R$ <?= number_format($stats['ticket_medio'] ?? 0, 2, ',', '.') ?></p>
    </div>
</div>

<div class="flex items-center justify-between mb-6">
    <div class="flex gap-3">
        <a href="/admin/financeiro/receitas" class="px-3 py-1.5 text-sm rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-200 transition">Receitas</a>
        <a href="/admin/financeiro/despesas" class="px-3 py-1.5 text-sm rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 hover:bg-red-200 transition">Despesas</a>
        <a href="/admin/financeiro/fluxo-caixa" class="px-3 py-1.5 text-sm rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 hover:bg-blue-200 transition">Fluxo de Caixa</a>
    </div>
    <button onclick="document.getElementById('modal-entry').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Novo Lançamento</button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoria</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($entries as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-white"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $row['type'] === 'revenue' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>"><?= $row['type'] === 'revenue' ? 'Receita' : 'Despesa' ?></span></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['category']) ?></td>
                    <td class="px-4 py-3 text-sm font-semibold <?= $row['type'] === 'revenue' ? 'text-green-600' : 'text-red-600' ?>">R$ <?= number_format((float)$row['value'], 2, ',', '.') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['date'])) ?></td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="confirmDelete('/admin/financeiro/lancamentos/<?= $row['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($entries)): ?><tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum lançamento neste período.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Novo Lançamento -->
<div id="modal-entry" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Novo Lançamento</h3>
        <form action="/admin/financeiro/lancamentos" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label><select name="type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"><option value="revenue">Receita</option><option value="expense">Despesa</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição *</label><input type="text" name="description" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor (R$) *</label><input type="number" step="0.01" name="value" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data *</label><input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label><input type="text" name="category" placeholder="Serviços, Hospedagem, Operacional..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-entry').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar</button>
            </div>
        </form>
    </div>
</div>
