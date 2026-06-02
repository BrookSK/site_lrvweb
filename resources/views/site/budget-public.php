<?php
/** @var array $budget */
/** @var array $blocks */
/** @var array $portfolios */
/** @var array $settings */
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <?php $logo = $settings['logo_budget'] ?? $settings['logo_main'] ?? ''; ?>
        <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-12 mx-auto mb-4">
        <?php else: ?>
            <h1 class="text-2xl font-bold text-blue-600 mb-4">LRV Web</h1>
        <?php endif; ?>
        <h2 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($budget['name']) ?></h2>
        <p class="text-gray-500 mt-2">
            Para: <strong><?= htmlspecialchars($budget['client_name'] ?? '') ?></strong>
            <?= $budget['client_company'] ? ' — ' . htmlspecialchars($budget['client_company']) : '' ?>
        </p>
        <div class="flex justify-center gap-4 mt-3 text-sm text-gray-500">
            <span>Criado em: <?= date('d/m/Y', strtotime($budget['created_at'])) ?></span>
            <?php if ($budget['validity_date']): ?>
                <span>Válido até: <?= date('d/m/Y', strtotime($budget['validity_date'])) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Blocos de Serviço -->
    <?php foreach ($blocks as $i => $block): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($block['title']) ?></h3>
                <?php if ($block['requested_at']): ?>
                    <p class="text-xs text-gray-400 mt-1">Solicitado em <?= date('d/m/Y', strtotime($block['requested_at'])) ?></p>
                <?php endif; ?>
            </div>
            <span class="text-lg font-bold text-blue-600">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></span>
        </div>

        <?php if ($block['description']): ?>
            <p class="text-gray-600 mt-4"><?= nl2br(htmlspecialchars($block['description'])) ?></p>
        <?php endif; ?>

        <?php if ($block['scope']): ?>
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-1">Escopo</h4>
                <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($block['scope'])) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($block['features']): ?>
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-1">Funcionalidades</h4>
                <div class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($block['features'])) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($block['deadline']): ?>
            <p class="text-sm text-gray-500 mt-3">⏱️ Prazo: <?= htmlspecialchars($block['deadline']) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- Resumo Financeiro -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumo</h3>
        <table class="w-full text-sm">
            <tbody>
                <?php foreach ($blocks as $block): ?>
                <tr class="border-b border-gray-100">
                    <td class="py-2 text-gray-600"><?= htmlspecialchars($block['title']) ?></td>
                    <td class="py-2 text-right font-medium">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-300">
                    <td class="py-3 font-semibold text-gray-800">Subtotal</td>
                    <td class="py-3 text-right font-semibold">R$ <?= number_format((float)$budget['total_value'], 2, ',', '.') ?></td>
                </tr>
                <?php if ((float)$budget['discount_value'] > 0): ?>
                <tr>
                    <td class="py-2 text-green-600">Desconto (<?= $budget['discount_percent'] ?>%)</td>
                    <td class="py-2 text-right text-green-600">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></td>
                </tr>
                <?php endif; ?>
                <tr class="bg-blue-50">
                    <td class="py-3 px-3 font-bold text-blue-800 text-lg rounded-l-lg">Valor Final</td>
                    <td class="py-3 px-3 text-right font-bold text-blue-800 text-lg rounded-r-lg">R$ <?= number_format((float)$budget['final_value'], 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <?php if ($budget['payment_type'] === 'installments' && $budget['installments'] > 1): ?>
            <p class="mt-4 text-sm text-gray-600">
                💳 <?= $budget['installments'] ?>x de R$ <?= number_format((float)$budget['installment_value'], 2, ',', '.') ?>
            </p>
        <?php endif; ?>

        <!-- Formas de pagamento -->
        <div class="mt-4 flex gap-3 text-sm text-gray-600">
            <?php if ($budget['payment_pix']): ?><span class="px-3 py-1 bg-white border rounded-lg">PIX</span><?php endif; ?>
            <?php if ($budget['payment_card']): ?><span class="px-3 py-1 bg-white border rounded-lg">Cartão</span><?php endif; ?>
            <?php if ($budget['payment_boleto']): ?><span class="px-3 py-1 bg-white border rounded-lg">Boleto</span><?php endif; ?>
        </div>
    </div>

    <!-- Observações -->
    <?php if ($budget['notes']): ?>
    <div class="mt-8 p-6 bg-white rounded-xl border border-gray-200">
        <h3 class="font-semibold text-gray-800 mb-2">Observações</h3>
        <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($budget['notes'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Sobre a Empresa -->
    <?php $about = $budget['about_company'] ?: ($settings['about_company'] ?? ''); ?>
    <?php if ($about): ?>
    <div class="mt-8 p-6 bg-white rounded-xl border border-gray-200">
        <h3 class="font-semibold text-gray-800 mb-2">Sobre a LRV Web</h3>
        <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($about)) ?></p>
    </div>
    <?php endif; ?>

    <!-- Portfólio -->
    <?php if (!empty($portfolios)): ?>
    <div class="mt-8">
        <h3 class="font-semibold text-gray-800 mb-4">Projetos Recentes</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <?php foreach ($portfolios as $p): ?>
            <div class="rounded-lg overflow-hidden border border-gray-200">
                <?php if ($p['image_cover']): ?>
                    <img src="<?= htmlspecialchars($p['image_cover']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full aspect-video object-cover">
                <?php endif; ?>
                <div class="p-3">
                    <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($p['name']) ?></p>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($p['category'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="mt-12 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
        <p>&copy; <?= date('Y') ?> LRV Web. Todos os direitos reservados.</p>
        <p class="mt-1"><?= htmlspecialchars($settings['site_email'] ?? 'contato@lrvweb.com.br') ?></p>
    </div>
</div>
