<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="<?= $project ? '/admin/projetos/' . $project['id'] : '/admin/projetos' ?>" method="POST" class="space-y-6">
            <?= \Core\View::csrf() ?>
            <?php if ($project): ?><?= \Core\View::method('PUT') ?><?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nome do Projeto *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($project['name'] ?? '') ?>" required class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Cliente *</label>
                    <select name="client_id" required class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                        <option value="">Selecione...</option>
                        <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($project['client_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Data Início</label>
                    <input type="date" name="start_date" value="<?= $project['start_date'] ?? '' ?>" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Prazo de Entrega</label>
                    <input type="date" name="due_date" value="<?= $project['due_date'] ?? '' ?>" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="value" value="<?= $project['value'] ?? '' ?>" placeholder="0.00" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                </div>
                <?php if ($project): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm">
                        <option value="planning" <?= ($project['status'] ?? '') === 'planning' ? 'selected' : '' ?>>Planejamento</option>
                        <option value="development" <?= ($project['status'] ?? '') === 'development' ? 'selected' : '' ?>>Desenvolvimento</option>
                        <option value="testing" <?= ($project['status'] ?? '') === 'testing' ? 'selected' : '' ?>>Testes</option>
                        <option value="review" <?= ($project['status'] ?? '') === 'review' ? 'selected' : '' ?>>Revisão</option>
                        <option value="completed" <?= ($project['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Concluído</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Descrição</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm" placeholder="Descrição do projeto, escopo, objetivos..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
            </div>

            <!-- EQUIPE -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Equipe do Projeto</label>
                <p class="text-xs text-gray-500 mb-2">Selecione os membros que farão parte deste projeto (segure Ctrl/Cmd para selecionar vários).</p>
                <select name="members[]" multiple class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm h-32">
                    <?php
                    $currentMembers = [];
                    if ($project) {
                        $currentMembers = array_column(\Core\Database::getInstance()->fetchAll("SELECT user_id FROM project_members WHERE project_id = :id", ['id' => $project['id']]), 'user_id');
                    }
                    foreach ($users as $u):
                    ?>
                    <option value="<?= $u['id'] ?>" <?= in_array($u['id'], $currentMembers) ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar Projeto</button>
                <a href="<?= $project ? '/admin/projetos/' . $project['id'] : '/admin/projetos' ?>" class="px-6 py-2.5 border border-gray-700 text-gray-300 text-sm rounded-lg hover:bg-gray-700 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
