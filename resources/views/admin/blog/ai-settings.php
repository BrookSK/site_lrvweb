<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-white">Blog IA — Geração Automática</h3>
        <p class="text-sm text-gray-400 mt-1">Gere artigos automaticamente com inteligência artificial.</p>
    </div>
    <a href="/admin/configuracoes?tab=openai" class="px-4 py-2 border border-gray-700 text-gray-300 text-sm rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
        <i data-lucide="settings" class="w-4 h-4"></i> Configurações IA
    </a>
</div>

<!-- Status da IA -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Status</p>
        <p class="text-sm font-semibold <?= ($config['blog_enabled'] ?? false) ? 'text-green-400' : 'text-red-400' ?>"><?= ($config['blog_enabled'] ?? false) ? '🟢 Ativado' : '🔴 Desativado' ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Modelo</p>
        <p class="text-sm font-semibold text-white"><?= htmlspecialchars($config['model'] ?? 'gpt-4') ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 mb-1">Frequência</p>
        <p class="text-sm font-semibold text-white"><?= ($config['blog_frequency'] ?? 'weekly') === 'daily' ? 'Diária' : 'Semanal' ?></p>
    </div>
</div>

<!-- GERAR AGORA -->
<div class="bg-purple-900/20 border border-purple-700 rounded-xl p-6 mb-8">
    <h4 class="text-white font-semibold mb-3">🚀 Gerar Post Agora</h4>
    <p class="text-sm text-gray-400 mb-4">Gere um artigo instantaneamente. A IA vai criar título, conteúdo, SEO e buscar uma imagem relacionada automaticamente.</p>

    <form action="/admin/blog/ia/gerar" method="POST" class="flex flex-col sm:flex-row gap-4">
        <?= \Core\View::csrf() ?>
        <div class="flex-1">
            <input type="text" name="topic" placeholder="Tema (opcional). Ex: Benefícios da hospedagem cloud para e-commerce" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
        </div>
        <div>
            <select name="language" class="px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                <option value="pt">Português</option>
                <option value="en">English</option>
                <option value="es">Español</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i data-lucide="sparkles" class="w-4 h-4"></i> Gerar com IA
        </button>
    </form>
</div>

<!-- Como funciona -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 mb-8">
    <h4 class="text-white font-semibold mb-4">Como funciona?</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="flex items-start gap-3 p-3 bg-gray-900/50 rounded-lg">
            <span class="text-purple-400 font-bold text-lg">1</span>
            <div><p class="text-gray-200 font-medium">Geração de conteúdo</p><p class="text-gray-500 text-xs mt-0.5">A IA cria título, artigo completo (800-1200 palavras), meta description e palavras-chave.</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 bg-gray-900/50 rounded-lg">
            <span class="text-purple-400 font-bold text-lg">2</span>
            <div><p class="text-gray-200 font-medium">Imagem automática</p><p class="text-gray-500 text-xs mt-0.5">Busca uma imagem relevante no Unsplash baseada nas palavras-chave do artigo.</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 bg-gray-900/50 rounded-lg">
            <span class="text-purple-400 font-bold text-lg">3</span>
            <div><p class="text-gray-200 font-medium">SEO otimizado</p><p class="text-gray-500 text-xs mt-0.5">Meta title, meta description e keywords são gerados automaticamente para ranquear no Google.</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 bg-gray-900/50 rounded-lg">
            <span class="text-purple-400 font-bold text-lg">4</span>
            <div><p class="text-gray-200 font-medium">Publicação automática</p><p class="text-gray-500 text-xs mt-0.5">O post é publicado automaticamente. Você pode editar depois se quiser ajustar algo.</p></div>
        </div>
    </div>
</div>

<!-- Histórico -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-700">
        <h4 class="text-sm font-semibold text-white">Histórico de Geração</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Idioma</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título Gerado</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th></tr></thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($jobs as $j): ?>
                <tr class="hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm text-gray-400">#<?= $j['id'] ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium <?= match($j['status']) { 'completed' => 'bg-green-900/30 text-green-400', 'failed' => 'bg-red-900/30 text-red-400', 'processing' => 'bg-yellow-900/30 text-yellow-400', default => 'bg-gray-700 text-gray-400' } ?>"><?= match($j['status']) { 'completed' => '✓ Concluído', 'failed' => '✗ Falhou', 'processing' => '⏳ Processando', default => $j['status'] } ?></span></td>
                    <td class="px-4 py-3 text-sm text-gray-400"><?= strtoupper($j['language'] ?? 'pt') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-200"><?= htmlspecialchars($j['generated_title'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($j['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($jobs)): ?><tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Nenhum job executado ainda. Clique em "Gerar com IA" para criar o primeiro post.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
