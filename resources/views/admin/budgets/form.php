<div class="max-w-5xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="<?= $budget ? '/admin/orcamentos/' . $budget['id'] : '/admin/orcamentos' ?>" method="POST" class="space-y-8" id="budget-form">
            <?= \Core\View::csrf() ?>
            <?php if ($budget): ?><?= \Core\View::method('PUT') ?><?php endif; ?>

            <!-- =============================== -->
            <!-- INFORMAÇÕES GERAIS -->
            <!-- =============================== -->
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Informações Gerais</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Orçamento *</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($budget['name'] ?? '') ?>" required placeholder="Ex: Sistema Multiplataforma de Pesquisa" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
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
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formas aceitas</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2"><input type="checkbox" name="payment_pix" value="1" <?= ($budget['payment_pix'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">PIX</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="payment_card" value="1" <?= ($budget['payment_card'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">Cartão</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="payment_boleto" value="1" <?= ($budget['payment_boleto'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-600 dark:text-gray-400">Boleto</span></label>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações (visível ao cliente)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm" placeholder="Ex: Esse valor inclui testes, validações e ajustes..."><?= htmlspecialchars($budget['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- =============================== -->
            <!-- BLOCOS DE SOLICITAÇÃO -->
            <!-- =============================== -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white">Blocos de Solicitação</h4>
                    <button type="button" onclick="addBlock()" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i> Adicionar Bloco
                    </button>
                </div>

                <div id="blocks-container" class="space-y-4">
                    <!-- Blocos existentes (ao editar) -->
                    <?php if ($budget && !empty($budget['blocks'])): ?>
                        <?php foreach ($budget['blocks'] as $idx => $block): ?>
                        <div class="block-item border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-gray-50 dark:bg-gray-900/50 relative">
                            <button type="button" onclick="this.closest('.block-item').remove(); updateBlockIndexes()" class="absolute top-3 right-3 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Remover bloco"><i data-lucide="x" class="w-4 h-4"></i></button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Título *</label><input type="text" name="blocks[<?= $idx ?>][title]" value="<?= htmlspecialchars($block['title']) ?>" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                                <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data Solicitação</label><input type="date" name="blocks[<?= $idx ?>][requested_at]" value="<?= $block['requested_at'] ?? '' ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                            </div>
                            <div class="mb-3"><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Descrição</label><textarea name="blocks[<?= $idx ?>][description]" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"><?= htmlspecialchars($block['description'] ?? '') ?></textarea></div>
                            <div class="mb-3"><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">O que será feito (um por linha)</label><textarea name="blocks[<?= $idx ?>][features]" rows="4" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"><?= htmlspecialchars($block['features'] ?? '') ?></textarea></div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Prazo</label><input type="text" name="blocks[<?= $idx ?>][deadline]" value="<?= htmlspecialchars($block['deadline'] ?? '') ?>" placeholder="30 dias úteis" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                                <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Valor (R$)</label><input type="number" step="0.01" name="blocks[<?= $idx ?>][value]" value="<?= $block['value'] ?? '' ?>" placeholder="0.00" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                                <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Escopo</label><input type="text" name="blocks[<?= $idx ?>][scope]" value="<?= htmlspecialchars($block['scope'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
                            </div>
                            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Observações do bloco</label><textarea name="blocks[<?= $idx ?>][notes]" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"><?= htmlspecialchars($block['notes'] ?? '') ?></textarea></div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <p id="no-blocks-msg" class="text-sm text-gray-400 text-center py-6 <?= ($budget && !empty($budget['blocks'])) ? 'hidden' : '' ?>">Nenhum bloco adicionado. Clique em "Adicionar Bloco" para começar.</p>
            </div>

            <!-- =============================== -->
            <!-- PREVIEW DE TOTAIS (atualiza em tempo real) -->
            <!-- =============================== -->
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                <h4 class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-3">📊 Preview — Resumo Financeiro</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">Subtotal</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white" id="preview-subtotal">R$ 0,00</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">Desconto</p>
                        <p class="text-lg font-bold text-green-600" id="preview-discount">- R$ 0,00</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">Total Final</p>
                        <p class="text-xl font-bold text-purple-700 dark:text-purple-300" id="preview-total">R$ 0,00</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">Por Parcela</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white" id="preview-installment">R$ 0,00</p>
                    </div>
                </div>
            </div>

            <!-- =============================== -->
            <!-- SALVAR -->
            <!-- =============================== -->
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar Orçamento</button>
                <a href="/admin/orcamentos" class="px-6 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Link público (só se já salvo) -->
    <?php if ($budget): ?>
    <div class="mt-6 bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
        <p class="text-sm font-medium text-purple-800 dark:text-purple-300 mb-1">Link público do orçamento:</p>
        <div class="flex items-center gap-2">
            <input type="text" readonly value="<?= rtrim(\Core\Config::get('app.url', ''), '/') ?>/orcamento/<?= $budget['hash'] ?>" class="flex-1 px-3 py-2 bg-white dark:bg-gray-900 border border-purple-200 dark:border-purple-700 rounded-lg text-sm text-gray-800 dark:text-white font-mono">
            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='✓ Copiado'" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition whitespace-nowrap">Copiar</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- JavaScript para adicionar blocos dinamicamente -->
<script>
let blockIndex = <?= ($budget && !empty($budget['blocks'])) ? count($budget['blocks']) : 0 ?>;

function addBlock() {
    document.getElementById('no-blocks-msg').classList.add('hidden');
    const container = document.getElementById('blocks-container');
    const idx = blockIndex++;

    const html = `
    <div class="block-item border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-gray-50 dark:bg-gray-900/50 relative" style="animation: fadeInUp 0.3s ease">
        <button type="button" onclick="this.closest('.block-item').remove(); updateBlockIndexes()" class="absolute top-3 right-3 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Remover bloco"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Título *</label><input type="text" name="blocks[${idx}][title]" required placeholder="Ex: Desenvolvimento de Sistema" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data Solicitação</label><input type="date" name="blocks[${idx}][requested_at]" value="${new Date().toISOString().split('T')[0]}" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
        </div>
        <div class="mb-3"><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Descrição</label><textarea name="blocks[${idx}][description]" rows="2" placeholder="Descrição geral do que será feito..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea></div>
        <div class="mb-3"><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">O que será feito (um item por linha)</label><textarea name="blocks[${idx}][features]" rows="4" placeholder="Autenticação de usuários\nDashboard com relatórios\nIntegração com API\n..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Prazo</label><input type="text" name="blocks[${idx}][deadline]" placeholder="30 dias úteis" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Valor (R$)</label><input type="number" step="0.01" name="blocks[${idx}][value]" placeholder="0.00" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
            <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Escopo</label><input type="text" name="blocks[${idx}][scope]" placeholder="Escopo técnico" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></div>
        </div>
        <div><label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Observações</label><textarea name="blocks[${idx}][notes]" rows="2" placeholder="Observações específicas..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea></div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    container.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function updateBlockIndexes() {
    const blocks = document.querySelectorAll('.block-item');
    if (blocks.length === 0) {
        document.getElementById('no-blocks-msg').classList.remove('hidden');
    }
    updatePreview();
}

function updatePreview() {
    // Soma valores de todos os blocos
    let subtotal = 0;
    document.querySelectorAll('.block-item input[name*="[value]"]').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });

    // Pega desconto e parcelas
    const discountPercent = parseFloat(document.querySelector('input[name="discount_percent"]')?.value) || 0;
    const installments = parseInt(document.querySelector('input[name="installments"]')?.value) || 1;

    const discountValue = subtotal * (discountPercent / 100);
    const total = subtotal - discountValue;
    const perInstallment = installments > 0 ? total / installments : total;

    // Formata
    const fmt = (v) => 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    document.getElementById('preview-subtotal').textContent = fmt(subtotal);
    document.getElementById('preview-discount').textContent = '- ' + fmt(discountValue);
    document.getElementById('preview-total').textContent = fmt(total);
    document.getElementById('preview-installment').textContent = installments > 1 ? `${installments}x ${fmt(perInstallment)}` : fmt(total);
}

// Listeners para atualizar preview automaticamente
document.addEventListener('input', function(e) {
    if (e.target.name && (e.target.name.includes('[value]') || e.target.name === 'discount_percent' || e.target.name === 'installments')) {
        updatePreview();
    }
});

// Calcula ao carregar (para quando editar)
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
