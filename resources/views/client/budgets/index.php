<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Meus Orçamentos</h3>
    <p class="text-sm text-gray-500 mt-1">Visualize, aprove ou recuse propostas.</p>
</div>

<div class="space-y-4">
    <?php foreach ($budgets as $b): ?>
    <a href="/cliente/orcamentos/<?= $b['id'] ?>" class="block bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-700 hover:shadow-md transition">
        <div class="flex items-start justify-between">
            <div>
                <p class="font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($b['name']) ?></p>
                <p class="text-xs text-gray-500 mt-1">Criado em <?= date('d/m/Y', strtotime($b['created_at'])) ?> <?= $b['validity_date'] ? '· Válido até ' . date('d/m/Y', strtotime($b['validity_date'])) : '' ?></p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-purple-600">R$ <?= number_format((float)($b['final_value'] ?? 0), 2, ',', '.') ?></p>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-medium mt-1 <?= match($b['status']) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'sent','viewed' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($b['status']) { 'draft' => 'Rascunho', 'sent' => 'Enviado', 'viewed' => 'Visualizado', 'approved' => 'Aprovado', 'rejected' => 'Recusado', 'expired' => 'Expirado', default => $b['status'] } ?></span>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
    <?php if (empty($budgets)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-gray-500">Nenhum orçamento disponível.</p>
        </div>
    <?php endif; ?>
</div>
