<?php
/** @var array $budget */
/** @var array $blocks */
/** @var array $portfolios */
/** @var array $settings */
$logo = $settings['logo_budget'] ?? $settings['logo_main'] ?? $settings['logo_system'] ?? '';
?>

<div class="max-w-4xl mx-auto px-4 py-12">

    <!-- ============================================ -->
    <!-- HEADER DO ORÇAMENTO -->
    <!-- ============================================ -->
    <div class="text-center mb-10">
        <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-10 mx-auto mb-6 object-contain">
        <?php else: ?>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">LRV Web</h1>
        <?php endif; ?>

        <h2 class="text-2xl md:text-3xl font-bold text-gray-800"><?= htmlspecialchars($budget['name']) ?> — LRV Web</h2>

        <p class="text-gray-500 mt-3">
            Para: <strong class="text-gray-700"><?= htmlspecialchars($budget['client_name'] ?? '') ?></strong>
            <?= $budget['client_company'] ? ' — ' . htmlspecialchars($budget['client_company']) : '' ?>
        </p>
    </div>

    <!-- ============================================ -->
    <!-- METADADOS DO ORÇAMENTO -->
    <!-- ============================================ -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Criado em</p>
                <p class="text-gray-800 font-medium"><?= date('d/m/Y H:i', strtotime($budget['created_at'])) ?></p>
            </div>
            <?php if ($budget['updated_at'] && $budget['updated_at'] !== $budget['created_at']): ?>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Atualizado</p>
                <p class="text-gray-800 font-medium"><?= date('d/m/Y H:i', strtotime($budget['updated_at'])) ?></p>
            </div>
            <?php endif; ?>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Status</p>
                <p class="font-medium <?= match($budget['status']) { 'approved' => 'text-green-600', 'rejected' => 'text-red-600', 'expired' => 'text-gray-500', default => 'text-blue-600' } ?>">
                    <?= match($budget['status']) { 'draft' => '📝 Rascunho', 'sent' => '📤 Enviado', 'viewed' => '👁️ Visualizado', 'approved' => '✅ Aprovado', 'rejected' => '❌ Recusado', 'expired' => '⏰ Expirado', default => $budget['status'] } ?>
                </p>
            </div>
            <?php if ($budget['validity_date']): ?>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Válido até</p>
                <p class="text-gray-800 font-medium"><?= date('d/m/Y', strtotime($budget['validity_date'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- INTRODUÇÃO -->
    <!-- ============================================ -->
    <div class="mb-10">
        <p class="text-gray-600 leading-relaxed">Segue abaixo uma estimativa detalhada para as solicitações:</p>
    </div>

    <!-- ============================================ -->
    <!-- BLOCOS DE SOLICITAÇÃO -->
    <!-- ============================================ -->
    <?php foreach ($blocks as $i => $block): ?>
    <div class="mb-10 pb-8 <?= $i < count($blocks) - 1 ? 'border-b border-gray-200' : '' ?>">
        <!-- Título do bloco -->
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($block['title']) ?></h3>
            <?php if ($block['requested_at']): ?>
                <span class="text-xs text-gray-400 whitespace-nowrap ml-4">(<?= date('d/m', strtotime($block['requested_at'])) ?> — data da solicitação)</span>
            <?php endif; ?>
        </div>

        <!-- Descrição -->
        <?php if ($block['description']): ?>
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-1">Descrição:</p>
            <p class="text-gray-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($block['description'])) ?></p>
        </div>
        <?php endif; ?>

        <!-- Escopo / O que será feito -->
        <?php if ($block['scope']): ?>
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-1">Escopo:</p>
            <p class="text-gray-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($block['scope'])) ?></p>
        </div>
        <?php endif; ?>

        <!-- Funcionalidades / Tópicos -->
        <?php if ($block['features']): ?>
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">O que será feito:</p>
            <ul class="space-y-1.5">
                <?php foreach (explode("\n", $block['features']) as $feature):
                    $feature = trim($feature);
                    if (empty($feature)) continue;
                    $feature = ltrim($feature, '•-· ');
                ?>
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <span class="text-purple-500 mt-0.5">•</span>
                    <?= htmlspecialchars($feature) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Prazo -->
        <?php if ($block['deadline']): ?>
        <p class="text-sm text-gray-700 mb-2"><strong>Prazo:</strong> <?= htmlspecialchars($block['deadline']) ?></p>
        <?php endif; ?>

        <!-- Valor -->
        <?php if ((float)$block['value'] > 0): ?>
        <p class="text-sm text-gray-700 mb-2"><strong>Valor:</strong> R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></p>
        <?php else: ?>
        <p class="text-sm text-gray-700 mb-2"><strong>Valor:</strong> Incluso no pacote geral.</p>
        <?php endif; ?>

        <!-- Observações do bloco -->
        <?php if ($block['notes']): ?>
        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800"><strong>Observação:</strong> <?= nl2br(htmlspecialchars($block['notes'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- ============================================ -->
    <!-- RESUMO GERAL -->
    <!-- ============================================ -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Resumo Geral</h3>

        <div class="space-y-3 mb-4">
            <?php foreach ($blocks as $block): ?>
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <span class="text-sm text-gray-700 font-medium"><?= htmlspecialchars($block['title']) ?></span>
                <div class="text-right">
                    <?php if ((float)$block['value'] > 0): ?>
                        <span class="text-sm font-semibold text-gray-800">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></span>
                    <?php else: ?>
                        <span class="text-sm text-gray-500 italic">Incluso</span>
                    <?php endif; ?>
                    <?php if ($block['deadline']): ?>
                        <span class="text-xs text-gray-400 block"><?= htmlspecialchars($block['deadline']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Totais -->
        <div class="border-t-2 border-gray-300 pt-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">R$ <?= number_format((float)$budget['total_value'], 2, ',', '.') ?></span>
            </div>
            <?php if ((float)($budget['discount_value'] ?? 0) > 0): ?>
            <div class="flex justify-between text-sm">
                <span class="text-green-600">Desconto (<?= $budget['discount_percent'] ?>%)</span>
                <span class="text-green-600 font-medium">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                <span class="text-gray-800">Valor Total Final</span>
                <span class="text-purple-700">R$ <?= number_format((float)$budget['final_value'], 2, ',', '.') ?></span>
            </div>
            <?php if ($budget['payment_type'] === 'installments' && $budget['installments'] > 1): ?>
            <div class="flex justify-between text-sm text-gray-500">
                <span>Parcelamento</span>
                <span><?= $budget['installments'] ?>x de R$ <?= number_format((float)$budget['installment_value'], 2, ',', '.') ?></span>
            </div>
            <?php endif; ?>
            <?php if ($budget['monthly_value']): ?>
            <div class="flex justify-between text-sm text-gray-500">
                <span>Valor mensal</span>
                <span>R$ <?= number_format((float)$budget['monthly_value'], 2, ',', '.') ?>/mês</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- OBSERVAÇÕES GERAIS -->
    <!-- ============================================ -->
    <?php if ($budget['notes']): ?>
    <div class="mb-8 p-5 bg-blue-50 border border-blue-200 rounded-xl">
        <p class="text-sm text-blue-800 leading-relaxed"><?= nl2br(htmlspecialchars($budget['notes'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- VALIDADE -->
    <!-- ============================================ -->
    <?php if ($budget['validity_date']): ?>
    <p class="text-sm text-gray-700 mb-4 font-medium">📅 Orçamento válido até <?= date('d \d\e F \d\e Y', strtotime($budget['validity_date'])) ?>.</p>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- MEIOS DE PAGAMENTO -->
    <!-- ============================================ -->
    <?php if ($budget['payment_pix'] || $budget['payment_card'] || $budget['payment_boleto']): ?>
    <div class="mb-6">
        <p class="text-sm font-semibold text-gray-700 mb-2">Meios de pagamento:</p>
        <p class="text-sm text-gray-600">
            Aceitamos
            <?php
            $methods = [];
            if ($budget['payment_pix']) $methods[] = '<strong>Pix</strong> com 5% de desconto no valor total';
            if ($budget['payment_boleto']) $methods[] = '<strong>Boleto</strong> com valor integral';
            if ($budget['payment_card']) $methods[] = '<strong>Cartão de crédito</strong> (acréscimo conforme operadora)';
            echo implode(', ', $methods) . '.';
            ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Entrada mínima -->
    <?php if ($budget['minimum_entry']): ?>
    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
        <p class="text-sm text-purple-800">💰 Para o início do projeto, solicitamos <strong><?= number_format((float)$budget['minimum_entry'], 0, ',', '.') ?>%</strong> do valor total.</p>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- SOBRE A LRV WEB -->
    <!-- ============================================ -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex items-center gap-3 mb-3">
            <?php if ($logo): ?>
                <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-8 object-contain">
            <?php else: ?>
                <span class="text-lg font-bold text-gray-800">LRV Web</span>
            <?php endif; ?>
        </div>

        <p class="text-sm text-gray-500 leading-relaxed mb-2">
            <a href="https://lrvweb.com.br" target="_blank" class="text-purple-600 hover:underline">https://lrvweb.com.br</a>
        </p>

        <?php $about = $budget['about_company'] ?: ($settings['about_company'] ?? ''); ?>
        <?php if ($about): ?>
            <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($about)) ?></p>
        <?php endif; ?>
    </div>

    <!-- ============================================ -->
    <!-- PROJETOS RECENTES -->
    <!-- ============================================ -->
    <?php if (!empty($portfolios)): ?>
    <div class="mt-8">
        <h4 class="text-base font-bold text-gray-800 mb-4">Projetos Recentes</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($portfolios as $p): ?>
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <?php if ($p['image_cover']): ?>
                    <img src="<?= htmlspecialchars($p['image_cover']) ?>" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                <?php endif; ?>
                <div>
                    <?php if ($p['url']): ?>
                        <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" class="text-sm font-semibold text-purple-700 hover:underline"><?= htmlspecialchars($p['name']) ?></a>
                    <?php else: ?>
                        <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($p['name']) ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-purple-600 font-medium mt-0.5"><?= htmlspecialchars($p['category'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- RODAPÉ -->
    <!-- ============================================ -->
    <div class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-400">
        <p>&copy; <?= date('Y') ?> LRV Web — Todos os direitos reservados.</p>
        <p class="mt-1">contato@lrvweb.com.br · <a href="https://lrvweb.com.br" class="text-purple-500 hover:underline">lrvweb.com.br</a></p>
    </div>
</div>
