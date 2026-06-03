<div class="mb-6">
    <a href="/cliente/projetos" class="text-sm text-purple-600 hover:underline mb-2 inline-block">← Voltar</a>
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($project['name']) ?></h3>
        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?= match($project['status']) { 'completed' => 'bg-green-100 text-green-700', 'development' => 'bg-yellow-100 text-yellow-700', 'testing' => 'bg-purple-100 text-purple-700', default => 'bg-blue-100 text-blue-700' } ?>">
            <?= match($project['status']) { 'planning' => 'Planejamento', 'development' => 'Em Desenvolvimento', 'testing' => 'Testes', 'review' => 'Revisão', 'completed' => 'Concluído', default => $project['status'] } ?>
        </span>
    </div>
</div>

<!-- Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div><p class="text-xs text-gray-400">Início</p><p class="font-medium text-gray-800 dark:text-white"><?= $project['start_date'] ? date('d/m/Y', strtotime($project['start_date'])) : '—' ?></p></div>
        <div><p class="text-xs text-gray-400">Prazo</p><p class="font-medium text-gray-800 dark:text-white"><?= $project['due_date'] ? date('d/m/Y', strtotime($project['due_date'])) : '—' ?></p></div>
        <div><p class="text-xs text-gray-400">Valor</p><p class="font-medium text-gray-800 dark:text-white"><?= $project['value'] ? 'R$ ' . number_format((float)$project['value'], 2, ',', '.') : '—' ?></p></div>
        <div><p class="text-xs text-gray-400">Criado em</p><p class="font-medium text-gray-800 dark:text-white"><?= date('d/m/Y', strtotime($project['created_at'])) ?></p></div>
    </div>
    <?php if ($project['description']): ?>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
    <?php endif; ?>
</div>

<!-- Tarefas -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
    <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Tarefas (<?= count($tasks) ?>)</h4>
    <div class="space-y-2">
        <?php foreach ($tasks as $t): ?>
        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full <?= match($t['status']) { 'completed' => 'bg-green-500', 'in_progress' => 'bg-yellow-500', 'cancelled' => 'bg-red-400', default => 'bg-gray-400' } ?>"></span>
                <span class="text-sm text-gray-700 dark:text-gray-300 <?= $t['status'] === 'completed' ? 'line-through text-gray-400' : '' ?>"><?= htmlspecialchars($t['title']) ?></span>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium <?= match($t['status']) { 'completed' => 'bg-green-100 text-green-700', 'in_progress' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-500' } ?>"><?= match($t['status']) { 'pending' => 'Pendente', 'in_progress' => 'Em Andamento', 'completed' => 'Concluída', 'cancelled' => 'Cancelada', default => $t['status'] } ?></span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($tasks)): ?><p class="text-sm text-gray-500 text-center py-4">Nenhuma tarefa registrada.</p><?php endif; ?>
    </div>
</div>
