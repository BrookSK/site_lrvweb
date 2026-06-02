<?php
/** @var array $budget */
/** @var array $blocks */
/** @var array $portfolios */
/** @var array $settings */
$logo = $settings['logo_budget'] ?? $settings['logo_main'] ?? $settings['logo_system'] ?? '';

// Meses em PT
$meses = ['January'=>'Janeiro','February'=>'Fevereiro','March'=>'Março','April'=>'Abril','May'=>'Maio','June'=>'Junho','July'=>'Julho','August'=>'Agosto','September'=>'Setembro','October'=>'Outubro','November'=>'Novembro','December'=>'Dezembro'];
function dataPT($date) {
    global $meses;
    $d = date('d', strtotime($date));
    $m = $meses[date('F', strtotime($date))] ?? date('F', strtotime($date));
    $y = date('Y', strtotime($date));
    return "{$d} de {$m} de {$y}";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($budget['name']) ?> — LRV Web</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #2d1b69 0%, #1a0f40 100%); }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

<!-- ============================================ -->
<!-- HEADER -->
<!-- ============================================ -->
<header class="gradient-header py-8">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-10 mx-auto mb-4 object-contain">
        <?php else: ?>
            <p class="text-white text-xl font-bold mb-4">LRV Web</p>
        <?php endif; ?>
        <h1 class="text-white text-2xl md:text-3xl font-bold"><?= htmlspecialchars($budget['name']) ?></h1>
        <p class="text-purple-200 mt-2 text-sm">
            Para: <strong class="text-white"><?= htmlspecialchars($budget['client_name'] ?? '') ?></strong>
            <?= $budget['client_company'] ? ' — ' . htmlspecialchars($budget['client_company']) : '' ?>
        </p>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-10">

    <!-- ============================================ -->
    <!-- METADADOS -->
    <!-- ============================================ -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 p-4 bg-gray-50 rounded-xl border border-gray-100">
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Criado em</p>
            <p class="text-sm text-gray-700 font-medium"><?= date('d/m/Y', strtotime($budget['created_at'])) ?></p>
        </div>
        <?php if ($budget['updated_at'] !== $budget['created_at']): ?>
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Atualizado</p>
            <p class="text-sm text-gray-700 font-medium"><?= date('d/m/Y', strtotime($budget['updated_at'])) ?></p>
        </div>
        <?php endif; ?>
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Status</p>
            <p class="text-sm font-semibold <?= match($budget['status']) { 'approved' => 'text-green-600', 'rejected' => 'text-red-600', default => 'text-purple-600' } ?>">
                <?= match($budget['status']) { 'draft' => 'Rascunho', 'sent' => 'Enviado', 'viewed' => 'Visualizado', 'approved' => 'Aprovado', 'rejected' => 'Recusado', 'expired' => 'Expirado', default => $budget['status'] } ?>
            </p>
        </div>
        <?php if ($budget['validity_date']): ?>
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Válido até</p>
            <p class="text-sm text-gray-700 font-medium"><?= date('d/m/Y', strtotime($budget['validity_date'])) ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Intro -->
    <p class="text-gray-600 mb-8 text-[15px]">Segue abaixo uma estimativa detalhada para as solicitações:</p>

    <!-- ============================================ -->
    <!-- BLOCOS DE SOLICITAÇÃO -->
    <!-- ============================================ -->
    <?php foreach ($blocks as $i => $block): ?>
    <section class="mb-8 pb-8 <?= $i < count($blocks) - 1 ? 'border-b border-gray-200' : '' ?>">
        <div class="flex items-baseline justify-between mb-3">
            <h2 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($block['title']) ?></h2>
            <?php if ($block['requested_at']): ?>
                <span class="text-xs text-gray-400 ml-3 whitespace-nowrap">(<?= date('d/m', strtotime($block['requested_at'])) ?> — data da solicitação)</span>
            <?php endif; ?>
        </div>

        <?php if ($block['description']): ?>
        <p class="text-sm text-gray-600 leading-relaxed mb-3"><span class="font-semibold text-gray-700">Descrição: </span><?= nl2br(htmlspecialchars($block['description'])) ?></p>
        <?php endif; ?>

        <?php if ($block['scope']): ?>
        <p class="text-sm text-gray-600 leading-relaxed mb-3"><span class="font-semibold text-gray-700">Escopo: </span><?= nl2br(htmlspecialchars($block['scope'])) ?></p>
        <?php endif; ?>

        <?php if ($block['features']): ?>
        <div class="mb-3">
            <p class="text-sm font-semibold text-gray-700 mb-1.5">O que será feito:</p>
            <ul class="space-y-1 ml-1">
                <?php foreach (array_filter(explode("\n", $block['features']), 'trim') as $feat): ?>
                <li class="text-sm text-gray-600 flex items-start gap-2">
                    <span class="text-purple-500 mt-1 text-[8px]">●</span>
                    <?= htmlspecialchars(ltrim(trim($feat), '•-· ')) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($block['deadline']): ?>
        <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Prazo:</span> <?= htmlspecialchars($block['deadline']) ?></p>
        <?php endif; ?>

        <?php if ((float)$block['value'] > 0): ?>
        <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Valor:</span> R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></p>
        <?php else: ?>
        <p class="text-sm text-gray-500 italic mb-1">Valor: Incluso no pacote geral.</p>
        <?php endif; ?>

        <?php if ($block['notes']): ?>
        <div class="mt-3 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
            <p class="text-sm text-amber-800"><span class="font-semibold">Observação:</span> <?= nl2br(htmlspecialchars($block['notes'])) ?></p>
        </div>
        <?php endif; ?>
    </section>
    <?php endforeach; ?>

    <!-- ============================================ -->
    <!-- RESUMO GERAL -->
    <!-- ============================================ -->
    <section class="mb-8 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 bg-gray-100 border-b border-gray-200">
            <h3 class="text-base font-bold text-gray-800">Resumo Geral</h3>
        </div>
        <div class="px-5 py-4">
            <!-- Itens -->
            <table class="w-full mb-4">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="text-left pb-2 font-semibold">Serviço</th>
                        <th class="text-right pb-2 font-semibold">Valor</th>
                        <th class="text-right pb-2 font-semibold">Prazo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blocks as $block): ?>
                    <tr class="border-b border-gray-50">
                        <td class="py-2 text-sm text-gray-700"><?= htmlspecialchars($block['title']) ?></td>
                        <td class="py-2 text-sm text-right font-medium text-gray-800"><?= (float)$block['value'] > 0 ? 'R$ ' . number_format((float)$block['value'], 2, ',', '.') : 'Incluso' ?></td>
                        <td class="py-2 text-sm text-right text-gray-500"><?= htmlspecialchars($block['deadline'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Totais -->
            <div class="border-t-2 border-gray-200 pt-3 space-y-1.5">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-medium text-gray-800">R$ <?= number_format((float)$budget['total_value'], 2, ',', '.') ?></span></div>
                <?php if ((float)($budget['discount_value'] ?? 0) > 0): ?>
                <div class="flex justify-between text-sm"><span class="text-green-600">Desconto (<?= $budget['discount_percent'] ?>%)</span><span class="text-green-600 font-medium">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></span></div>
                <?php endif; ?>
                <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                    <span class="text-gray-900">Valor Total Final</span>
                    <span class="text-purple-700">R$ <?= number_format((float)$budget['final_value'], 2, ',', '.') ?></span>
                </div>
                <?php if ($budget['payment_type'] === 'installments' && $budget['installments'] > 1): ?>
                <div class="flex justify-between text-sm text-gray-500"><span>Parcelamento</span><span><?= $budget['installments'] ?>x de R$ <?= number_format((float)$budget['installment_value'], 2, ',', '.') ?></span></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- OBSERVAÇÕES -->
    <!-- ============================================ -->
    <?php if ($budget['notes']): ?>
    <p class="text-sm text-gray-600 leading-relaxed mb-6"><?= nl2br(htmlspecialchars($budget['notes'])) ?></p>
    <?php endif; ?>

    <!-- Validade -->
    <?php if ($budget['validity_date']): ?>
    <p class="text-sm text-gray-700 font-medium mb-5">Orçamento válido até <?= dataPT($budget['validity_date']) ?>.</p>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- MEIOS DE PAGAMENTO -->
    <!-- ============================================ -->
    <?php if ($budget['payment_pix'] || $budget['payment_card'] || $budget['payment_boleto']): ?>
    <div class="mb-5">
        <p class="text-sm font-semibold text-gray-700 mb-1">Meios de pagamento:</p>
        <p class="text-sm text-gray-600">
            Aceitamos
            <?php
            $methods = [];
            if ($budget['payment_pix']) $methods[] = '**Pix** com 5% de desconto no valor total';
            if ($budget['payment_boleto']) $methods[] = '**Boleto** com valor integral';
            if ($budget['payment_card']) $methods[] = '**Cartão de crédito** (acréscimo conforme operadora)';
            $text = implode(', ', $methods) . '.';
            echo str_replace('**', '', preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text));
            ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Entrada -->
    <?php if ($budget['minimum_entry']): ?>
    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
        <p class="text-sm text-purple-800 font-medium">Para o início do projeto, solicitamos <strong><?= (int)$budget['minimum_entry'] ?>%</strong> do valor total.</p>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- SOBRE A LRV WEB -->
    <!-- ============================================ -->
    <section class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex items-center gap-3 mb-2">
            <?php if ($logo): ?>
                <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-7 object-contain">
            <?php endif; ?>
            <span class="text-sm font-bold text-gray-800">Sobre a LRV Web</span>
        </div>
        <p class="text-sm text-gray-500 mb-1"><a href="https://lrvweb.com.br" target="_blank" class="text-purple-600 hover:underline">https://lrvweb.com.br</a></p>
        <?php $about = $budget['about_company'] ?: ($settings['about_company'] ?? ''); ?>
        <?php if ($about): ?>
            <p class="text-sm text-gray-600 leading-relaxed mt-2"><?= nl2br(htmlspecialchars($about)) ?></p>
        <?php endif; ?>
    </section>

    <!-- ============================================ -->
    <!-- PROJETOS RECENTES -->
    <!-- ============================================ -->
    <?php if (!empty($portfolios)): ?>
    <section class="mt-8">
        <h4 class="text-sm font-bold text-gray-800 mb-4">Projetos Recentes</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <?php foreach ($portfolios as $p): ?>
            <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                <?php if ($p['image_cover']): ?>
                    <img src="<?= htmlspecialchars($p['image_cover']) ?>" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                <?php endif; ?>
                <div class="min-w-0">
                    <?php if ($p['url']): ?>
                        <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" class="text-sm font-semibold text-purple-700 hover:underline truncate block"><?= htmlspecialchars($p['name']) ?></a>
                    <?php else: ?>
                        <p class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($p['name']) ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($p['category'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<!-- FOOTER -->
<footer class="border-t border-gray-100 py-6 mt-8">
    <div class="max-w-3xl mx-auto px-6 text-center text-xs text-gray-400">
        <p>&copy; <?= date('Y') ?> LRV Web — Todos os direitos reservados.</p>
        <p class="mt-1">contato@lrvweb.com.br · <a href="https://lrvweb.com.br" class="text-purple-500 hover:underline">lrvweb.com.br</a></p>
    </div>
</footer>

</body>
</html>
