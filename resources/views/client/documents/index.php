<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Meus Documentos</h3>
    <p class="text-sm text-gray-500 mt-1">Contratos, propostas e documentos do projeto.</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoria</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Download</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($documents as $d): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($d['name']) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300"><?= $d['category'] ?></span></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td class="px-4 py-3 text-right"><a href="/cliente/documentos/<?= $d['id'] ?>/download" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg transition inline-flex items-center gap-1"><i data-lucide="download" class="w-3 h-3"></i> Baixar</a></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($documents)): ?><tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhum documento disponível.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
