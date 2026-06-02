<?php
/** @var array $budget */
/** @var array $blocks */
/** @var array $portfolios */
/** @var array $settings */
$logo = $settings['logo_budget'] ?? $settings['logo_main'] ?? $settings['logo_system'] ?? '';

$meses = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
function dataPT($date) {
    global $meses;
    $ts = strtotime($date);
    return date('d', $ts) . ' de ' . $meses[(int)date('n', $ts)] . ' de ' . date('Y', $ts);
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
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { 50:'#f5f3ff',100:'#ede9fe',500:'#7c3aed',600:'#6d28d9',700:'#5b21b6',800:'#4c1d95',900:'#2d1b69' } } } } }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #fafafa; }
        .header-gradient { background: linear-gradient(135deg, #2d1b69 0%, #4c1d95 50%, #6d28d9 100%); }
        .card { background: white; border-radius: 16px; border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03); }
        .block-card { background: white; border-radius: 12px; border: 1px solid #eee; padding: 24px; margin-bottom: 20px; transition: box-shadow 0.2s; }
        .block-card:hover { box-shadow: 0 4px 20px rgba(124,58,237,0.06); }
        .feature-dot { width: 6px; height: 6px; border-radius: 50%; background: #7c3aed; margin-top: 7px; flex-shrink: 0; }
        @media print { .no-print { display: none; } body { background: white; } }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header-gradient">
    <div class="max-w-3xl mx-auto px-6 py-10 text-center">
        <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-10 mx-auto mb-5 object-contain brightness-0 invert">
        <?php else: ?>
            <p class="text-white/80 text-sm font-medium mb-3">LRV WEB</p>
        <?php endif; ?>
        <h1 class="text-white text-2xl md:text-[28px] font-bold leading-tight"><?= htmlspecialchars($budget['name']) ?></h1>
        <p class="text-purple-200 mt-3 text-sm">
            Proposta para <strong class="text-white"><?= htmlspecialchars($budget['client_name'] ?? '') ?></strong><?= $budget['client_company'] ? ' · ' . htmlspecialchars($budget['client_company']) : '' ?>
        </p>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 -mt-6 pb-12 relative z-10">

    <!-- METADADOS -->
    <div class="card p-5 mb-8">
        <div class="grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Criado em</p>
                <p class="text-sm text-gray-800 font-semibold"><?= date('d/m/Y', strtotime($budget['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Status</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= match($budget['status']) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'sent','viewed' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-700' } ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= match($budget['status']) { 'approved' => 'bg-green-500', 'rejected' => 'bg-red-500', 'sent','viewed' => 'bg-blue-500', default => 'bg-gray-500' } ?>"></span>
                    <?= match($budget['status']) { 'draft' => 'Rascunho', 'sent' => 'Enviado', 'viewed' => 'Visualizado', 'approved' => 'Aprovado', 'rejected' => 'Recusado', 'expired' => 'Expirado', default => $budget['status'] } ?>
                </span>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Validade</p>
                <p class="text-sm text-gray-800 font-semibold"><?= $budget['validity_date'] ? date('d/m/Y', strtotime($budget['validity_date'])) : '—' ?></p>
            </div>
        </div>
    </div>

    <!-- INTRO -->
    <p class="text-gray-500 text-[15px] mb-8 leading-relaxed">Segue abaixo uma estimativa detalhada para as solicitações:</p>

    <!-- BLOCOS -->
    <?php foreach ($blocks as $i => $block): ?>
    <div class="block-card">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center flex-shrink-0">
                    <span class="text-brand-600 font-bold text-sm"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                </div>
                <h2 class="text-base font-bold text-gray-900"><?= htmlspecialchars($block['title']) ?></h2>
            </div>
            <?php if ($block['requested_at']): ?>
                <span class="text-[11px] text-gray-400 whitespace-nowrap bg-gray-50 px-2 py-1 rounded-md"><?= date('d/m/Y', strtotime($block['requested_at'])) ?></span>
            <?php endif; ?>
        </div>

        <?php if ($block['description']): ?>
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Descrição</p>
            <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($block['description'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($block['scope']): ?>
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Escopo</p>
            <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($block['scope'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($block['features']): ?>
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">O que será feito</p>
            <div class="space-y-1.5">
                <?php foreach (array_filter(explode("\n", $block['features']), 'trim') as $feat): ?>
                <div class="flex items-start gap-2.5">
                    <div class="feature-dot"></div>
                    <span class="text-sm text-gray-700"><?= htmlspecialchars(ltrim(trim($feat), '•-· ')) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Prazo e Valor -->
        <div class="flex items-center gap-6 mt-4 pt-4 border-t border-gray-100">
            <?php if ($block['deadline']): ?>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-gray-600"><span class="font-medium">Prazo:</span> <?= htmlspecialchars($block['deadline']) ?></span>
            </div>
            <?php endif; ?>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php if ((float)$block['value'] > 0): ?>
                <span class="text-sm font-semibold text-brand-600">R$ <?= number_format((float)$block['value'], 2, ',', '.') ?></span>
                <?php else: ?>
                <span class="text-sm text-gray-500 italic">Incluso no pacote</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($block['notes']): ?>
        <div class="mt-4 p-3 bg-amber-50 border-l-[3px] border-amber-400 rounded-r-lg">
            <p class="text-xs text-amber-800"><span class="font-semibold">Obs:</span> <?= nl2br(htmlspecialchars($block['notes'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- RESUMO GERAL -->
    <div class="card overflow-hidden mb-8 mt-10">
        <div class="px-6 py-4 bg-gradient-to-r from-brand-900 to-brand-700">
            <h3 class="text-white font-bold text-base">Resumo Geral</h3>
        </div>
        <div class="p-6">
            <table class="w-full mb-5">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-[10px] text-gray-400 uppercase tracking-widest font-bold pb-2">Serviço</th>
                        <th class="text-right text-[10px] text-gray-400 uppercase tracking-widest font-bold pb-2">Valor</th>
                        <th class="text-right text-[10px] text-gray-400 uppercase tracking-widest font-bold pb-2">Prazo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blocks as $block): ?>
                    <tr class="border-b border-gray-50">
                        <td class="py-3 text-sm text-gray-700 font-medium"><?= htmlspecialchars($block['title']) ?></td>
                        <td class="py-3 text-sm text-right font-semibold text-gray-800"><?= (float)$block['value'] > 0 ? 'R$ ' . number_format((float)$block['value'], 2, ',', '.') : '<span class="text-gray-400 font-normal italic">Incluso</span>' ?></td>
                        <td class="py-3 text-sm text-right text-gray-500"><?= htmlspecialchars($block['deadline'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="border-t-2 border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-semibold text-gray-800">R$ <?= number_format((float)$budget['total_value'], 2, ',', '.') ?></span></div>
                <?php if ((float)($budget['discount_value'] ?? 0) > 0): ?>
                <div class="flex justify-between text-sm"><span class="text-green-600">Desconto (<?= rtrim(rtrim(number_format((float)$budget['discount_percent'], 2, ',', '.'), '0'), ',') ?>%)</span><span class="text-green-600 font-semibold">- R$ <?= number_format((float)$budget['discount_value'], 2, ',', '.') ?></span></div>
                <?php endif; ?>
                <div class="flex justify-between items-center pt-3 mt-2 border-t border-gray-200">
                    <span class="text-base font-bold text-gray-900">Valor Total Final</span>
                    <span class="text-xl font-extrabold text-brand-600">R$ <?= number_format((float)$budget['final_value'], 2, ',', '.') ?></span>
                </div>
                <?php if ($budget['payment_type'] === 'installments' && $budget['installments'] > 1): ?>
                <div class="flex justify-between text-sm text-gray-500 pt-1"><span>Parcelado em</span><span class="font-medium"><?= $budget['installments'] ?>x de R$ <?= number_format((float)$budget['installment_value'], 2, ',', '.') ?></span></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- OBSERVAÇÕES -->
    <?php if ($budget['notes']): ?>
    <div class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-xl">
        <p class="text-sm text-blue-800 leading-relaxed"><?= nl2br(htmlspecialchars($budget['notes'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- VALIDADE + PAGAMENTO -->
    <div class="card p-6 mb-8 space-y-4">
        <?php if ($budget['validity_date']): ?>
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm text-gray-700">Orçamento válido até <strong><?= dataPT($budget['validity_date']) ?></strong>.</p>
        </div>
        <?php endif; ?>

        <?php if ($budget['payment_pix'] || $budget['payment_card'] || $budget['payment_boleto']): ?>
        <div>
            <p class="text-sm font-semibold text-gray-700 mb-1.5">Meios de pagamento:</p>
            <div class="flex flex-wrap gap-2">
                <?php if ($budget['payment_pix']): ?><span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-xs font-medium text-green-700">💰 Pix <span class="text-green-500 font-normal">· 5% desc.</span></span><?php endif; ?>
                <?php if ($budget['payment_boleto']): ?><span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-200 text-xs font-medium text-gray-700">📄 Boleto <span class="text-gray-400 font-normal">· valor integral</span></span><?php endif; ?>
                <?php if ($budget['payment_card']): ?><span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-xs font-medium text-blue-700">💳 Cartão <span class="text-blue-400 font-normal">· acréscimo</span></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($budget['minimum_entry']): ?>
        <div class="p-4 bg-brand-50 border border-brand-100 rounded-xl">
            <p class="text-sm text-brand-800 font-medium">Para o início do projeto, solicitamos uma entrada de <strong><?= (int)$budget['minimum_entry'] ?>%</strong> do valor total.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- SOBRE A LRV -->
    <section class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex items-center gap-3 mb-3">
            <?php if ($logo): ?>
                <img src="<?= htmlspecialchars($logo) ?>" alt="LRV Web" class="h-7 object-contain">
            <?php endif; ?>
            <div>
                <p class="text-sm font-bold text-gray-800">Sobre a LRV Web</p>
                <a href="https://lrvweb.com.br" target="_blank" class="text-xs text-brand-600 hover:underline">lrvweb.com.br</a>
            </div>
        </div>
        <?php $about = $budget['about_company'] ?: ($settings['about_company'] ?? ''); ?>
        <?php if ($about): ?>
            <p class="text-sm text-gray-600 leading-relaxed mt-2"><?= nl2br(htmlspecialchars($about)) ?></p>
        <?php endif; ?>
    </section>

    <!-- PROJETOS RECENTES -->
    <?php if (!empty($portfolios)): ?>
    <section class="mt-8">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Projetos Recentes</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <?php foreach ($portfolios as $p): ?>
            <a href="<?= htmlspecialchars($p['url'] ?? '#') ?>" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-brand-200 hover:bg-brand-50/30 transition group">
                <?php if ($p['image_cover']): ?>
                    <img src="<?= htmlspecialchars($p['image_cover']) ?>" class="w-11 h-11 rounded-lg object-cover flex-shrink-0 border border-gray-100">
                <?php else: ?>
                    <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><span class="text-lg">🌐</span></div>
                <?php endif; ?>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-700 truncate transition"><?= htmlspecialchars($p['name']) ?></p>
                    <p class="text-xs text-gray-400"><?= htmlspecialchars($p['category'] ?? '') ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<!-- FOOTER -->
<footer class="border-t border-gray-100 py-6 bg-white">
    <div class="max-w-3xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
        <p>&copy; <?= date('Y') ?> LRV Web — Todos os direitos reservados.</p>
        <p>contato@lrvweb.com.br · <a href="https://lrvweb.com.br" class="text-brand-500 hover:underline">lrvweb.com.br</a></p>
    </div>
</footer>

</body>
</html>
