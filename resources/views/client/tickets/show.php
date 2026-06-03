<div class="max-w-3xl">
    <a href="/cliente/chamados" class="text-sm text-purple-600 hover:underline mb-4 inline-block">← Voltar</a>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">#<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['subject']) ?></h3>
                <p class="text-xs text-gray-500 mt-1">Aberto em <?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium <?= match($ticket['priority']) { 'urgent' => 'bg-red-100 text-red-700', 'high' => 'bg-orange-100 text-orange-700', 'medium' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($ticket['priority']) { 'low' => 'Baixa', 'medium' => 'Média', 'high' => 'Alta', 'urgent' => 'Urgente', default => $ticket['priority'] } ?></span>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium <?= match($ticket['status']) { 'open' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-yellow-100 text-yellow-700', 'resolved' => 'bg-green-100 text-green-700', 'closed' => 'bg-gray-100 text-gray-500', default => 'bg-gray-100 text-gray-600' } ?>"><?= match($ticket['status']) { 'open' => 'Aberto', 'in_progress' => 'Em Atendimento', 'waiting_client' => 'Aguardando Você', 'resolved' => 'Resolvido', 'closed' => 'Fechado', default => $ticket['status'] } ?></span>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400"><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
        </div>
    </div>

    <!-- Respostas -->
    <div class="space-y-4 mb-6">
        <?php foreach ($replies as $r): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 <?= ($r['user_id'] ?? 0) == ($user['id'] ?? -1) ? 'ml-6 border-purple-200 dark:border-purple-800' : 'mr-6' ?>">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300"><?= htmlspecialchars($r['user_name'] ?? 'Suporte LRV') ?></p>
                <p class="text-[10px] text-gray-400"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></p>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400"><?= nl2br(htmlspecialchars($r['message'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Responder -->
    <?php if (!in_array($ticket['status'], ['closed', 'resolved'])): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Responder</h4>
        <form action="/cliente/chamados/<?= $ticket['id'] ?>/responder" method="POST" class="space-y-3">
            <?= \Core\View::csrf() ?>
            <textarea name="message" rows="4" required placeholder="Escreva sua resposta..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Enviar Resposta</button>
        </form>
    </div>
    <?php else: ?>
    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-500">Este chamado foi encerrado.</p>
    </div>
    <?php endif; ?>
</div>
