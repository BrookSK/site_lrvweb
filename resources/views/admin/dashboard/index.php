<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <!-- Clientes Ativos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['active_clients'] ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clientes Ativos</p>
    </div>

    <!-- Projetos Ativos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="folder-kanban" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['active_projects'] ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Projetos Ativos</p>
    </div>

    <!-- Orçamentos Pendentes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['pending_budgets'] ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Orçam. Pendentes</p>
    </div>

    <!-- Faturamento Mensal -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="trending-up" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">R$ <?= number_format($stats['monthly_revenue'], 2, ',', '.') ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Faturamento Mensal</p>
    </div>

    <!-- Chamados Abertos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="message-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['open_tickets'] ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Chamados Abertos</p>
    </div>

    <!-- Tarefas Pendentes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <i data-lucide="check-square" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['pending_tasks'] ?></p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tarefas Pendentes</p>
    </div>
</div>

<!-- Charts & Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart: Receita Mensal -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Receita Mensal</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <!-- Projetos Recentes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Projetos Recentes</h3>
        <div class="space-y-4">
            <?php foreach ($recentProjects as $project): ?>
            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                <div class="w-2 h-2 rounded-full mt-2 <?= match($project['status']) {
                    'planning' => 'bg-blue-500',
                    'development' => 'bg-yellow-500',
                    'testing' => 'bg-purple-500',
                    'review' => 'bg-orange-500',
                    'completed' => 'bg-green-500',
                    default => 'bg-gray-400',
                } ?>"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate"><?= htmlspecialchars($project['name']) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($project['client_name'] ?? '') ?></p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <?= ucfirst($project['status']) ?>
                </span>
            </div>
            <?php endforeach; ?>

            <?php if (empty($recentProjects)): ?>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Nenhum projeto recente.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Activity Timeline -->
<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Atividades Recentes</h3>
    <div class="space-y-4">
        <?php foreach ($recentActivities as $activity): ?>
        <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="activity" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-800 dark:text-gray-200">
                    <span class="font-medium"><?= htmlspecialchars($activity['user_name'] ?? 'Sistema') ?></span>
                    <?= htmlspecialchars($activity['action']) ?>
                    <span class="text-gray-500 dark:text-gray-400">em <?= htmlspecialchars($activity['module']) ?></span>
                </p>
                <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?></p>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($recentActivities)): ?>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Nenhuma atividade recente.</p>
        <?php endif; ?>
    </div>
</div>

<script>
// Revenue Chart com dados reais
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        <?php
        $chartLabels = [];
        $chartData = [];
        $mesesPT = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        // Preenche os últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $chartLabels[] = $mesesPT[(int)date('n', strtotime($month)) - 1] . '/' . date('y', strtotime($month));
            $found = false;
            foreach ($monthlyRevenue as $mr) {
                if ($mr['month'] === $month) {
                    $chartData[] = (float) $mr['total'];
                    $found = true;
                    break;
                }
            }
            if (!$found) $chartData[] = 0;
        }
        ?>
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Receita (R$)',
                    data: <?= json_encode($chartData) ?>,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#7c3aed',
                    pointBorderColor: '#7c3aed',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#6b7280' } },
                    x: { grid: { display: false }, ticks: { color: '#6b7280' } }
                }
            }
        });
    }
});
</script>
