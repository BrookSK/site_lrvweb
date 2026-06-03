<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Meus Projetos</h3>
    <p class="text-sm text-gray-500 mt-1">Acompanhe o andamento dos seus projetos.</p>
</div>

<div class="space-y-4">
    <?php foreach ($projects as $p): ?>
    <a href="/cliente/projetos/<?= $p['id'] ?>" class="block bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 hover:border-purple-300 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($p['name']) ?></p>
                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                    <?php if ($p['start_date']): ?><span>Início: <?= date('d/m/Y', strtotime($p['start_date'])) ?></span><?php endif; ?>
                    <?php if ($p['due_date']): ?><span>Prazo: <?= date('d/m/Y', strtotime($p['due_date'])) ?></span><?php endif; ?>
                </div>
            </div>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium <?= match($p['status']) { 'completed' => 'bg-green-100 text-green-700', 'development' => 'bg-yellow-100 text-yellow-700', 'testing' => 'bg-purple-100 text-purple-700', default => 'bg-blue-100 text-blue-700' } ?>">
                <?= match($p['status']) { 'planning' => 'Planejamento', 'development' => 'Em Desenvolvimento', 'testing' => 'Testes', 'review' => 'Revisão', 'completed' => 'Concluído', default => $p['status'] } ?>
            </span>
        </div>
    </a>
    <?php endforeach; ?>
    <?php if (empty($projects)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-gray-500">Nenhum projeto no momento.</p>
        </div>
    <?php endif; ?>
</div>
