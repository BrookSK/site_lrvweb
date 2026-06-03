<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="/cliente/orcamentos" class="text-sm text-purple-600 hover:underline mb-2 inline-block">← Voltar</a>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($budget['name']) ?></h3>
    </div>
    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?= match($budget['status']) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'sent','viewed' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($budget['status']) { 'draft' => 'Rascunho', 'sent' => 'Enviado', 'viewed' => 'Visualizado', 'approved' => '✅ Aprovado', 'rejected' => '❌ Recusado', 'expired' => 'Expirado', default => $budget['status'] } ?></span>
</div>

<!-- Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div><p class="text-xs text-gray-400">Criado em</p><p class="font-medium text-gray-800 dark:text-white"><?= date('d/m/Y', strtotime($budget['created_at'])) ?></p></div>
        <div><p class="text-xs text-gray-400">Validade</p><p class="font-medium text-gray-800 dark:text-white"><?= $budget['validity_date'] ? date('d/m/Y', strtotime($budget['validity_date'])) : '—' ?></p></div>
        <div><p class="text-xs text-gray-400">Valor Total</p><p class="font-bold text-purple-600 text-lg">R$ <?= number_format((float)($budget['final_value'] ?? 0), 2, ',', '.') ?></p></div>
        <div><p class="text-xs text-gray-400">Parcelas</p><p class="font-medium text-gray-800 dark:text-white"><?= ($budget['installments'] ?? 1) > 1 ? $budget['installments'] . 'x de R$ ' . number_format((float)($budget['installment_value'] ?? 0), 2, ',', '.') : 'À vista' ?></p></div>
    </div>
</div>

<!-- Blocos -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6">
    <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Detalhes do Orçamento</h4>
    <div class="space-y-4">
        <?php foreach ($blocks as $i => $block): ?>
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between mb-2">
                <h5 class="font-semibold text-gray-800 dark:text-white text-sm"><?= htmlspecialchars($block['title']) ?></h5>
                <span class="text-sm font-bold text-purple-600"><?= (float)$block['value'] > 0 ? 'R$ ' . number_format((float)$block['value'], 2, ',', '.') : 'Incluso' ?></span>
            </div>
            <?php if ($block['description']): ?><p class="text-xs text-gray-600 dark:text-gray-400 mb-2"><?= nl2br(htmlspecialchars($block['description'])) ?></p><?php endif; ?>
            <?php if ($block['features']): ?>
            <div class="mt-2">
                <?php foreach (array_filter(explode("\n", $block['features']), 'trim') as $feat): ?>
                <p class="text-xs text-gray-500 flex items-start gap-2 py-0.5"><span class="text-purple-400 mt-0.5">•</span><?= htmlspecialchars(ltrim(trim($feat), '•-· ')) ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ($block['deadline']): ?><p class="text-xs text-gray-500 mt-2">⏱ <?= htmlspecialchars($block['deadline']) ?></p><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php if (empty($blocks)): ?><p class="text-sm text-gray-500 text-center py-4">Sem detalhes.</p><?php endif; ?>
    </div>
</div>

<!-- Resumo -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6">
    <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Resumo</h4>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-medium">R$ <?= number_format((float)($budget['total_value'] ?? 0), 2, ',', '.') ?></span></div>
        <?php if ((float)($budget['discount_value'] ?? 0) > 0): ?>
        <div class="flex justify-between"><span class="text-green-600">Desconto (<?= $budget['discount_percent'] ?>%)</span><span class="text-green-600">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></span></div>
        <?php endif; ?>
        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700 text-base font-bold"><span>Total Final</span><span class="text-purple-600">R$ <?= number_format((float)($budget['final_value'] ?? 0), 2, ',', '.') ?></span></div>
    </div>
</div>

<!-- AÇÕES: Aprovar / Recusar -->
<?php if (in_array($budget['status'], ['sent', 'viewed', 'draft'])): ?>
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
    <h4 class="font-semibold text-gray-800 dark:text-white mb-4">O que deseja fazer?</h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Aprovar -->
        <form action="/cliente/orcamentos/<?= $budget['id'] ?>/aprovar" method="POST" class="space-y-3">
            <?= \Core\View::csrf() ?>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Observação (opcional)</label>
                <textarea name="observation" rows="2" placeholder="Alguma observação sobre a aprovação..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2" onclick="return confirm('Confirmar aprovação deste orçamento?')">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Aprovar Orçamento
            </button>
        </form>

        <!-- Recusar -->
        <form action="/cliente/orcamentos/<?= $budget['id'] ?>/recusar" method="POST" class="space-y-3">
            <?= \Core\View::csrf() ?>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Motivo da recusa *</label>
                <textarea name="observation" rows="2" required placeholder="Informe o motivo..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2" onclick="return confirm('Confirmar recusa deste orçamento?')">
                <i data-lucide="x-circle" class="w-4 h-4"></i> Recusar Orçamento
            </button>
        </form>
    </div>
</div>
<?php elseif ($budget['status'] === 'approved'): ?>
<div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
    <p class="text-green-800 dark:text-green-300 font-medium flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5"></i> Este orçamento foi aprovado em <?= $budget['approved_at'] ? date('d/m/Y', strtotime($budget['approved_at'])) : '—' ?>.</p>
</div>
<?php elseif ($budget['status'] === 'rejected'): ?>
<div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-5 border border-red-200 dark:border-red-800">
    <p class="text-red-800 dark:text-red-300 font-medium flex items-center gap-2"><i data-lucide="x-circle" class="w-5 h-5"></i> Este orçamento foi recusado em <?= $budget['rejected_at'] ? date('d/m/Y', strtotime($budget['rejected_at'])) : '—' ?>.</p>
</div>
<?php endif; ?>
