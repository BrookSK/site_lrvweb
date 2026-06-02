<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Documentos</h3>
    <button onclick="document.getElementById('modal-upload').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2"><i data-lucide="upload" class="w-4 h-4"></i> Enviar Documento</button>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-gray-900/50"><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoria</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cliente</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Enviado por</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th><th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($documents as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400"><?= $row['category'] ?></span></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['client_name'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($row['uploaded_by_name'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    <td class="px-4 py-3 text-right">
                        <a href="/admin/documentos/<?= $row['id'] ?>/download" class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 transition" title="Download"><i data-lucide="download" class="w-4 h-4"></i></a>
                        <button onclick="confirmDelete('/admin/documentos/<?= $row['id'] ?>')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Excluir"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($documents)): ?><tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum documento.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Upload -->
<div id="modal-upload" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Enviar Documento</h3>
        <form action="/admin/documentos/upload" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label><input type="text" name="name" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm" placeholder="Nome do documento"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                    <option value="other">Outro</option><option value="contract">Contrato</option><option value="proposal">Proposta</option><option value="internal">Interno</option><option value="financial">Financeiro</option><option value="project">Projeto</option>
                </select>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo *</label><input type="file" name="file" required class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700"></div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-upload').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Enviar</button>
            </div>
        </form>
    </div>
</div>
