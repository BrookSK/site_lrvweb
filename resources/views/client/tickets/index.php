<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Chamados</h3>
        <p class="text-sm text-gray-500 mt-1">Suporte técnico e solicitações.</p>
    </div>
    <a href="/cliente/chamados/criar" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Novo Chamado</a>
</div>

<div class="space-y-3">
    <?php foreach ($tickets as $t): ?>
    <a href="/cliente/chamados/<?= $t['id'] ?>" class="block bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 hover:border-purple-300 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-800 dark:text-white text-sm">#<?= $t['id'] ?> — <?= htmlspecialchars($t['subject']) ?></p>
                <p class="text-xs text-gray-500 mt-0.5"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($t['priority']) { 'urgent' => 'bg-red-100 text-red-700', 'high' => 'bg-orange-100 text-orange-700', 'medium' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($t['priority']) { 'low' => 'Baixa', 'medium' => 'Média', 'high' => 'Alta', 'urgent' => 'Urgente', default => $t['priority'] } ?></span>
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($t['status']) { 'open' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-yellow-100 text-yellow-700', 'resolved' => 'bg-green-100 text-green-700', 'closed' => 'bg-gray-100 text-gray-500', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($t['status']) { 'open' => 'Aberto', 'in_progress' => 'Em Atendimento', 'waiting_client' => 'Aguardando Você', 'resolved' => 'Resolvido', 'closed' => 'Fechado', default => $t['status'] } ?></span>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
    <?php if (empty($tickets)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-gray-500">Nenhum chamado. <a href="/cliente/chamados/criar" class="text-purple-600 hover:underline">Abrir novo chamado</a>.</p>
        </div>
    <?php endif; ?>
</div>
