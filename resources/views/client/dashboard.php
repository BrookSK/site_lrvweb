<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Olá, <?= htmlspecialchars($client['name'] ?? $user['name'] ?? '') ?>! 👋</h3>
    <p class="text-gray-500 mt-1">Bem-vindo à sua área exclusiva.</p>
</div>

<!-- Cards resumo -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"><i data-lucide="folder" class="w-5 h-5 text-purple-600"></i></div>
            <div><p class="text-2xl font-bold text-gray-800 dark:text-white"><?= count($projects) ?></p><p class="text-xs text-gray-500">Projetos</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i></div>
            <div><p class="text-2xl font-bold text-gray-800 dark:text-white"><?= count($budgets) ?></p><p class="text-xs text-gray-500">Orçamentos</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center"><i data-lucide="receipt" class="w-5 h-5 text-amber-600"></i></div>
            <div><p class="text-2xl font-bold text-gray-800 dark:text-white"><?= count($invoices) ?></p><p class="text-xs text-gray-500">Faturas Pendentes</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center"><i data-lucide="message-circle" class="w-5 h-5 text-red-600"></i></div>
            <div><p class="text-2xl font-bold text-gray-800 dark:text-white"><?= count($tickets) ?></p><p class="text-xs text-gray-500">Chamados Abertos</p></div>
        </div>
    </div>
</div>

<!-- Projetos recentes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-gray-800 dark:text-white">Projetos</h4>
            <a href="/cliente/projetos" class="text-xs text-purple-600 hover:underline">Ver todos</a>
        </div>
        <div class="space-y-3">
            <?php foreach (array_slice($projects, 0, 5) as $p): ?>
            <a href="/cliente/projetos/<?= $p['id'] ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($p['name']) ?></p>
                    <p class="text-xs text-gray-500 mt-0.5"><?= $p['due_date'] ? 'Prazo: ' . date('d/m/Y', strtotime($p['due_date'])) : '' ?></p>
                </div>
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400"><?= $p['status'] ?></span>
            </a>
            <?php endforeach; ?>
            <?php if (empty($projects)): ?><p class="text-sm text-gray-500 text-center py-4">Nenhum projeto no momento.</p><?php endif; ?>
        </div>
    </div>

    <!-- Orçamentos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-gray-800 dark:text-white">Orçamentos</h4>
            <a href="/cliente/orcamentos" class="text-xs text-purple-600 hover:underline">Ver todos</a>
        </div>
        <div class="space-y-3">
            <?php foreach (array_slice($budgets, 0, 5) as $b): ?>
            <a href="/cliente/orcamentos/<?= $b['id'] ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($b['name']) ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">R$ <?= number_format((float)($b['final_value'] ?? 0), 2, ',', '.') ?></p>
                </div>
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($b['status']) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'sent','viewed' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($b['status']) { 'draft' => 'Rascunho', 'sent' => 'Enviado', 'viewed' => 'Visualizado', 'approved' => 'Aprovado', 'rejected' => 'Recusado', default => $b['status'] } ?></span>
            </a>
            <?php endforeach; ?>
            <?php if (empty($budgets)): ?><p class="text-sm text-gray-500 text-center py-4">Nenhum orçamento.</p><?php endif; ?>
        </div>
    </div>
</div>
