<?php
$db = \Core\Database::getInstance();
// Busca orçamentos vinculados ao cliente do projeto
$linkedBudgets = $db->fetchAll("SELECT id, name, status, final_value FROM budgets WHERE client_id = :cid AND deleted_at IS NULL ORDER BY created_at DESC", ['cid' => $project['client_id'] ?? 0]);
// Busca documentos do projeto
$projectDocs = $db->fetchAll("SELECT id, name, category, created_at FROM documents WHERE project_id = :pid AND deleted_at IS NULL ORDER BY created_at DESC", ['pid' => $project['id']]);
// Busca usuários disponíveis para atribuir tarefas
$availableUsers = $db->fetchAll("SELECT id, name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="/admin/projetos" class="text-sm text-purple-400 hover:underline mb-1 inline-block">← Voltar</a>
        <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($project['name']) ?></h3>
        <p class="text-sm text-gray-400 mt-0.5"><?= htmlspecialchars($project['client_name'] ?? '') ?> · <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($project['status']) { 'completed' => 'bg-green-900/30 text-green-400', 'development' => 'bg-yellow-900/30 text-yellow-400', 'testing' => 'bg-purple-900/30 text-purple-400', default => 'bg-blue-900/30 text-blue-400' } ?>"><?= match($project['status']) { 'planning' => 'Planejamento', 'development' => 'Em Desenvolvimento', 'testing' => 'Testes', 'review' => 'Revisão', 'completed' => 'Concluído', default => $project['status'] } ?></span></p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/ia?chat=new&project=<?= $project['id'] ?>" class="px-3 py-2 border border-gray-700 text-gray-300 text-sm rounded-lg hover:bg-gray-700 transition flex items-center gap-2"><i data-lucide="bot" class="w-4 h-4"></i> IA</a>
        <a href="/admin/projetos/<?= $project['id'] ?>/editar" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition flex items-center gap-2"><i data-lucide="pencil" class="w-4 h-4"></i> Editar</a>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
    <div class="bg-gray-800 rounded-xl p-3 border border-gray-700 text-center">
        <p class="text-[10px] text-gray-500 uppercase">Valor</p>
        <p class="text-sm text-white font-semibold"><?= $project['value'] ? 'R$ ' . number_format((float)$project['value'], 2, ',', '.') : '—' ?></p>
    </div>
    <div class="bg-gray-800 rounded-xl p-3 border border-gray-700 text-center">
        <p class="text-[10px] text-gray-500 uppercase">Início</p>
        <p class="text-sm text-white font-semibold"><?= $project['start_date'] ? date('d/m/Y', strtotime($project['start_date'])) : '—' ?></p>
    </div>
    <div class="bg-gray-800 rounded-xl p-3 border border-gray-700 text-center">
        <p class="text-[10px] text-gray-500 uppercase">Prazo</p>
        <p class="text-sm text-white font-semibold"><?= $project['due_date'] ? date('d/m/Y', strtotime($project['due_date'])) : '—' ?></p>
    </div>
    <div class="bg-gray-800 rounded-xl p-3 border border-gray-700 text-center">
        <p class="text-[10px] text-gray-500 uppercase">Tarefas</p>
        <p class="text-sm text-white font-semibold"><?= count($tasks) ?></p>
    </div>
    <div class="bg-gray-800 rounded-xl p-3 border border-gray-700 text-center">
        <p class="text-[10px] text-gray-500 uppercase">Equipe</p>
        <p class="text-sm text-white font-semibold"><?= count($members) ?></p>
    </div>
</div>

<?php if ($project['description']): ?>
<div class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-6">
    <p class="text-sm text-gray-300"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
</div>
<?php endif; ?>

<!-- TABS -->
<div class="flex gap-1 mb-4 border-b border-gray-700 pb-2 overflow-x-auto" id="project-tabs">
    <button onclick="showTab('tasks')" class="tab-btn px-4 py-2 text-sm rounded-lg bg-purple-600/20 text-purple-300 font-medium" data-tab="tasks">Tarefas (<?= count($tasks) ?>)</button>
    <button onclick="showTab('team')" class="tab-btn px-4 py-2 text-sm rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition" data-tab="team">Equipe (<?= count($members) ?>)</button>
    <button onclick="showTab('budgets')" class="tab-btn px-4 py-2 text-sm rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition" data-tab="budgets">Orçamentos (<?= count($linkedBudgets) ?>)</button>
    <button onclick="showTab('docs')" class="tab-btn px-4 py-2 text-sm rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition" data-tab="docs">Documentos (<?= count($projectDocs) ?>)</button>
</div>

<!-- TAB: TAREFAS -->
<div id="tab-tasks" class="tab-content">
    <!-- Adicionar tarefa -->
    <form action="/admin/projetos/<?= $project['id'] ?>/tarefas" method="POST" class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-4">
        <?= \Core\View::csrf() ?>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="title" required placeholder="Nova tarefa..." class="flex-1 px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500">
            <select name="assigned_to" class="px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                <option value="">Responsável</option>
                <?php foreach ($availableUsers as $u): ?><option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option><?php endforeach; ?>
            </select>
            <select name="priority" class="px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                <option value="medium">Média</option>
                <option value="low">Baixa</option>
                <option value="high">Alta</option>
                <option value="urgent">Urgente</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">+ Adicionar</button>
        </div>
    </form>

    <!-- Lista de tarefas -->
    <div class="space-y-2">
        <?php foreach ($tasks as $t): ?>
        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-xl border border-gray-700">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 <?= match($t['status']) { 'completed' => 'bg-green-500', 'in_progress' => 'bg-yellow-500', 'cancelled' => 'bg-red-400', default => 'bg-gray-500' } ?>"></span>
                <div>
                    <p class="text-sm text-white <?= $t['status'] === 'completed' ? 'line-through text-gray-500' : '' ?>"><?= htmlspecialchars($t['title']) ?></p>
                    <p class="text-[10px] text-gray-500"><?= $t['assigned_name'] ?? 'Sem responsável' ?> · <?= match($t['priority']) { 'urgent' => '🔴 Urgente', 'high' => '🟠 Alta', 'medium' => '🟡 Média', default => '⚪ Baixa' } ?></p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <?php if ($t['status'] !== 'completed'): ?>
                <form action="/admin/projetos/tarefas/<?= $t['id'] ?>" method="POST" class="inline">
                    <?= \Core\View::csrf() ?>
                    <?= \Core\View::method('PUT') ?>
                    <input type="hidden" name="status" value="completed">
                    <button class="p-1.5 text-gray-500 hover:text-green-400 transition" title="Concluir"><i data-lucide="check" class="w-4 h-4"></i></button>
                </form>
                <?php endif; ?>
                <?php if ($t['status'] === 'pending'): ?>
                <form action="/admin/projetos/tarefas/<?= $t['id'] ?>" method="POST" class="inline">
                    <?= \Core\View::csrf() ?>
                    <?= \Core\View::method('PUT') ?>
                    <input type="hidden" name="status" value="in_progress">
                    <button class="p-1.5 text-gray-500 hover:text-yellow-400 transition" title="Iniciar"><i data-lucide="play" class="w-4 h-4"></i></button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($tasks)): ?><p class="text-sm text-gray-500 text-center py-8">Nenhuma tarefa. Adicione acima.</p><?php endif; ?>
    </div>
</div>

<!-- TAB: EQUIPE -->
<div id="tab-team" class="tab-content hidden">
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-4">
        <h4 class="text-sm font-semibold text-white mb-3">Membros do Projeto</h4>
        <div class="space-y-2">
            <?php foreach ($members as $m): ?>
            <div class="flex items-center gap-3 p-2">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-xs text-white font-bold"><?= strtoupper(substr($m['name'], 0, 1)) ?></div>
                <div>
                    <p class="text-sm text-white"><?= htmlspecialchars($m['name']) ?></p>
                    <p class="text-[10px] text-gray-500"><?= $m['role'] ?? 'Membro' ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($members)): ?><p class="text-sm text-gray-500 py-4">Nenhum membro vinculado.</p><?php endif; ?>
        </div>
    </div>
    <p class="text-xs text-gray-500">Para adicionar membros, edite o projeto e selecione os usuários.</p>
</div>

<!-- TAB: ORÇAMENTOS -->
<div id="tab-budgets" class="tab-content hidden">
    <div class="space-y-2">
        <?php foreach ($linkedBudgets as $b): ?>
        <a href="/admin/orcamentos/<?= $b['id'] ?>" class="flex items-center justify-between p-4 bg-gray-800 rounded-xl border border-gray-700 hover:border-purple-500/50 transition">
            <div>
                <p class="text-sm text-white font-medium"><?= htmlspecialchars($b['name']) ?></p>
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium mt-1 <?= match($b['status']) { 'approved' => 'bg-green-900/30 text-green-400', 'rejected' => 'bg-red-900/30 text-red-400', default => 'bg-blue-900/30 text-blue-400' } ?>"><?= $b['status'] ?></span>
            </div>
            <span class="text-sm font-semibold text-purple-400">R$ <?= number_format((float)($b['final_value'] ?? 0), 2, ',', '.') ?></span>
        </a>
        <?php endforeach; ?>
        <?php if (empty($linkedBudgets)): ?><p class="text-sm text-gray-500 text-center py-8">Nenhum orçamento vinculado a este cliente.</p><?php endif; ?>
    </div>
</div>

<!-- TAB: DOCUMENTOS -->
<div id="tab-docs" class="tab-content hidden">
    <!-- Upload -->
    <form action="/admin/documentos/upload" method="POST" enctype="multipart/form-data" class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-4">
        <?= \Core\View::csrf() ?>
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <input type="hidden" name="client_id" value="<?= $project['client_id'] ?>">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="name" placeholder="Nome do documento" class="flex-1 px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500">
            <select name="category" class="px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                <option value="project">Projeto</option>
                <option value="contract">Contrato</option>
                <option value="proposal">Proposta</option>
                <option value="internal">Interno</option>
            </select>
            <input type="file" name="file" required class="text-sm text-gray-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:text-xs file:cursor-pointer">
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">Upload</button>
        </div>
    </form>

    <!-- Lista -->
    <div class="space-y-2">
        <?php foreach ($projectDocs as $d): ?>
        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-xl border border-gray-700">
            <div>
                <p class="text-sm text-white"><?= htmlspecialchars($d['name']) ?></p>
                <p class="text-[10px] text-gray-500"><?= $d['category'] ?> · <?= date('d/m/Y', strtotime($d['created_at'])) ?></p>
            </div>
            <a href="/admin/documentos/<?= $d['id'] ?>/download" class="p-1.5 text-gray-400 hover:text-green-400 transition"><i data-lucide="download" class="w-4 h-4"></i></a>
        </div>
        <?php endforeach; ?>
        <?php if (empty($projectDocs)): ?><p class="text-sm text-gray-500 text-center py-8">Nenhum documento. Faça upload acima.</p><?php endif; ?>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => { btn.classList.remove('bg-purple-600/20','text-purple-300','font-medium'); btn.classList.add('text-gray-400'); });
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelector(`[data-tab="${tab}"]`).classList.add('bg-purple-600/20','text-purple-300','font-medium');
    document.querySelector(`[data-tab="${tab}"]`).classList.remove('text-gray-400');
}
</script>
