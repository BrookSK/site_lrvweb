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
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl border border-gray-200 dark:border-gray-700 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Novo Lançamento</h3>
        <form action="/admin/financeiro/lancamentos" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="revenue">💰 Receita</option>
                        <option value="expense">💸 Despesa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="servicos">Serviços</option>
                        <option value="hospedagem">Hospedagem</option>
                        <option value="desenvolvimento">Desenvolvimento</option>
                        <option value="agua">Água</option>
                        <option value="luz">Luz / Energia</option>
                        <option value="telefone">Telefone / Internet</option>
                        <option value="aluguel">Aluguel</option>
                        <option value="software">Software / Ferramentas</option>
                        <option value="marketing">Marketing</option>
                        <option value="salario">Salário</option>
                        <option value="impostos">Impostos</option>
                        <option value="equipamento">Equipamento</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição *</label>
                <input type="text" name="description" required placeholder="Ex: Hospedagem Cloud - Cliente X" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor (R$) *</label>
                    <input type="number" step="0.01" name="value" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data *</label>
                    <input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
            </div>

            <!-- Recorrência -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_recurring" value="1" id="chk-recurring" onchange="toggleRecurring()" class="rounded border-gray-300 text-purple-600">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Conta recorrente / fixa</span>
                </label>

                <div id="recurring-fields" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Frequência</label>
                        <select name="recurring_frequency" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                            <option value="monthly">Mensal</option>
                            <option value="quarterly">Trimestral</option>
                            <option value="yearly">Anual</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Parcelamento -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_installment" value="1" id="chk-installment" onchange="toggleInstallment()" class="rounded border-gray-300 text-purple-600">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Parcelado</span>
                </label>

                <div id="installment-fields" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nº de parcelas</label>
                            <input type="number" name="installments" min="2" max="48" value="3" id="input-installments" oninput="calcInstallments()" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Valor por parcela</label>
                            <input type="text" id="installment-value" readonly class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-100 dark:bg-gray-950 dark:text-gray-400 text-sm" value="—">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500" id="installment-info">Serão criados lançamentos mensais a partir da data selecionada.</p>
                </div>
            </div>

            <!-- Forma de pagamento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Forma de pagamento</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                    <option value="">Não informado</option>
                    <option value="pix">PIX</option>
                    <option value="boleto">Boleto</option>
                    <option value="cartao_credito">Cartão de Crédito</option>
                    <option value="cartao_debito">Cartão de Débito</option>
                    <option value="transferencia">Transferência</option>
                    <option value="dinheiro">Dinheiro</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                <input type="text" name="notes" placeholder="Notas adicionais..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-entry').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar Lançamento</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRecurring() {
    document.getElementById('recurring-fields').classList.toggle('hidden', !document.getElementById('chk-recurring').checked);
    if (document.getElementById('chk-recurring').checked) {
        document.getElementById('chk-installment').checked = false;
        document.getElementById('installment-fields').classList.add('hidden');
    }
}
function toggleInstallment() {
    document.getElementById('installment-fields').classList.toggle('hidden', !document.getElementById('chk-installment').checked);
    if (document.getElementById('chk-installment').checked) {
        document.getElementById('chk-recurring').checked = false;
        document.getElementById('recurring-fields').classList.add('hidden');
        calcInstallments();
    }
}
function calcInstallments() {
    const total = parseFloat(document.querySelector('input[name="value"]')?.value) || 0;
    const parcelas = parseInt(document.getElementById('input-installments')?.value) || 1;
    const perParcel = total / parcelas;
    document.getElementById('installment-value').value = total > 0 ? 'R$ ' + perParcel.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '—';

    const startDate = document.querySelector('input[name="date"]')?.value;
    if (startDate && parcelas > 0) {
        const end = new Date(startDate);
        end.setMonth(end.getMonth() + parcelas - 1);
        document.getElementById('installment-info').textContent = `${parcelas} parcelas de R$ ${perParcel.toFixed(2).replace('.', ',')} · Última em ${end.toLocaleDateString('pt-BR', {month: 'short', year: 'numeric'})}`;
    }
}
// Recalcula ao mudar valor
document.querySelector('input[name="value"]')?.addEventListener('input', calcInstallments);
</script>
