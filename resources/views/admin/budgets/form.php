<div class="max-w-4xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="<?= $budget ? '/admin/orcamentos/' . $budget['id'] : '/admin/orcamentos' ?>" method="POST" class="space-y-6">
            <?= \Core\View::csrf() ?>
            <?php if ($budget): ?><?= \Core\View::method('PUT') ?><?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Orçamento *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($budget['name'] ?? '') ?>" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente *</label>
                    <select name="client_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">Selecione...</option>
                        <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($budget['client_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?> <?= $c['company'] ? '(' . htmlspecialchars($c['company']) . ')' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Pagamento</label>
                    <select name="payment_type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="one_time" <?= ($budget['payment_type'] ?? '') === 'one_time' ? 'selected' : '' ?>>À Vista</option>
                        <option value="monthly" <?= ($budget['payment_type'] ?? '') === 'monthly' ? 'selected' : '' ?>>Mensal</option>
                        <option value="installments" <?= ($budget['payment_type'] ?? '') === 'installments' ? 'selected' : '' ?>>Parcelado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Validade</label>
                    <input type="date" name="validity_date" value="<?= $budget['validity_date'] ?? '' ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Desconto (%)</label>
                    <input type="number" step="0.01" name="discount_percent" value="<?= $budget['discount_percent'] ?? 0 ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parcelas</label>
                    <input type="number" name="installments" value="<?= $budget['installments'] ?? 1 ?>" min="1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Mensal (se aplicável)</label>
                    <input type="number" step="0.01" name="monthly_value" value="<?= $budget['monthly_value'] ?? '' ?>" placeholder="0.00" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entrada Mínima (%)</label>
                    <input type="number" step="1" name="minimum_entry" value="<?= $budget['minimum_entry'] ?? '' ?>" placeholder="Ex: 50" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
            </div>

            <!-- Formas de pagamento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formas aceitas</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2"><input type="checkbox" name="payment_pix" value="1" <?= ($budget['payment_pix'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">PIX</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="payment_card" value="1" <?= ($budget['payment_card'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">Cartão</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="payment_boleto" value="1" <?= ($budget['payment_boleto'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">Boleto</span></label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações (visível ao cliente)</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"><?= htmlspecialchars($budget['notes'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar Orçamento</button>
                <a href="/admin/orcamentos" class="px-6 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Blocos (se editando) -->
    <?php if ($budget && !empty($budget['blocks'])): ?>
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Blocos de Serviço (<?= count($budget['blocks']) ?>)</h4>
        <div class="space-y-3">
            <?php foreach ($budget['blocks'] as $block): ?>
            <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($block['title']) ?></p>
                    <p class="text-xs text-gray-500 mt-0.5"><?= $block['deadline'] ? 'Prazo: ' . htmlspecialchars($block['deadline']) : '' ?></p>
                </div>
                <span class="text-sm font-semibold text-purple-600">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="mt-4 text-right text-lg font-bold text-gray-800 dark:text-white">Total: R$ <?= number_format((float)($budget['total_value'] ?? 0), 2, ',', '.') ?></p>
    </div>
    <?php endif; ?>

    <!-- Link público -->
    <?php if ($budget): ?>
    <div class="mt-6 bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
        <p class="text-sm font-medium text-purple-800 dark:text-purple-300 mb-1">Link público do orçamento:</p>
        <div class="flex items-center gap-2">
            <input type="text" readonly value="<?= rtrim(\Core\Config::get('app.url', ''), '/') ?>/orcamento/<?= $budget['hash'] ?>" class="flex-1 px-3 py-2 bg-white dark:bg-gray-900 border border-purple-200 dark:border-purple-700 rounded-lg text-sm text-gray-800 dark:text-white font-mono">
            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='✓ Copiado'" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition">Copiar</button>
        </div>
    </div>

    <!-- Adicionar bloco -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Adicionar Bloco de Solicitação</h4>
        <form action="/admin/orcamentos/<?= $budget['id'] ?>/blocos" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título *</label>
                    <input type="text" name="title" required placeholder="Ex: Desenvolvimento de Sistema" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data da Solicitação</label>
                    <input type="date" name="requested_at" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" rows="3" placeholder="Descrição geral do que será feito..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Escopo</label>
                <textarea name="scope" rows="2" placeholder="Escopo técnico ou funcional..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Funcionalidades / O que será feito (um item por linha)</label>
                <textarea name="features" rows="5" placeholder="Autenticação de usuários&#10;Dashboard com relatórios&#10;Integração com API&#10;..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prazo</label>
                    <input type="text" name="deadline" placeholder="Ex: 30 dias úteis" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="value" placeholder="0.00" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações do bloco</label>
                <textarea name="notes" rows="2" placeholder="Observações específicas desta solicitação..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">+ Adicionar Bloco</button>
        </form>
    </div>
    <?php endif; ?>
</div>
