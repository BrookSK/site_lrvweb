<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 border border-purple-500/20 rounded-full mb-6 animate-fade-up">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <span class="text-sm text-purple-300">Servidores disponíveis agora</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold animate-fade-up delay-100">Infraestrutura VPS para <span class="text-gradient">escalar seu negócio</span></h1>
        <p class="text-gray-300 mt-5 max-w-2xl mx-auto text-lg animate-fade-up delay-200">Servidores VPS com recursos dedicados, proteção DDoS nativa, uptime 99.9% e suporte técnico — ativação em até 6 dias úteis.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 animate-fade-up delay-300">
            <a href="#planos" class="btn-primary">Ver Planos e Preços</a>
            <a href="https://cloud.lrvweb.com.br/contato" target="_blank" class="btn-outline">Falar com Consultor</a>
        </div>
    </div>
</section>

<!-- DIFERENCIAIS (estilo cloud.lrvweb) -->
<section class="section-dark py-16 border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $diffs = [
                ['⚡', 'Desempenho Superior', 'Processadores Intel Xeon de última geração para máximo throughput e mínima latência.'],
                ['🛡️', 'Proteção DDoS Nativa', 'Infraestrutura blindada contra ataques volumétricos. Incluída em todos os planos.'],
                ['🌐', 'Hiperconectividade', 'Portas de 1 a 10 Gbps com tráfego ilimitado. Dentro do hub de conectividade dos DCs.'],
                ['📈', 'Upgrades sem Burocracia', 'Escale recursos com rapidez. Ajuste CPU, RAM e armazenamento sob demanda.'],
                ['🖥️', 'Hardware Exclusivo', 'Controle total do servidor dedicado. Sem compartilhamento, sem vizinhos barulhentos.'],
                ['💰', 'Custos Transparentes', 'Preços claros, sem taxas ocultas. Reajuste anual pelo IGPM, sempre previsível.'],
            ];
            foreach ($diffs as $i => $d): ?>
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-purple-500/30 transition-all duration-300 group" data-animate="fade-up" data-delay="<?= $i * 80 ?>">
                <span class="text-3xl block mb-3 group-hover:scale-110 transition-transform"><?= $d[0] ?></span>
                <h3 class="text-white font-semibold mb-2"><?= $d[1] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= $d[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PLANOS VPS (dados reais do Cloud) -->
<section id="planos" class="section-dark py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Planos VPS</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Escolha o ideal para seu <span class="text-gradient">projeto</span></h2>
            <p class="text-gray-400 mt-3" data-animate="fade-up" data-delay="150">Todos incluem proteção DDoS, SSL gratuito e suporte especializado.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $plans = [
                ['Startup', 'O básico para começar', '90', ['1 vCPU', '2 GB RAM', '20 GB SSD', 'Proteção DDoS', 'SSL Gratuito', 'Suporte E-mail'], false],
                ['Essential', 'Ideal para startups', '297', ['2 vCPU', '4 GB RAM', '40 GB SSD', 'Proteção DDoS', 'SSL Gratuito', 'Suporte Especializado'], false],
                ['Professional', 'Para empresas em crescimento', '697', ['4 vCPU', '8 GB RAM', '80 GB SSD', 'Proteção DDoS', 'SSL Gratuito', 'Suporte Prioritário'], true],
                ['Business', 'Para operações de alto volume', '957', ['8 vCPU', '16 GB RAM', '160 GB SSD', 'Proteção DDoS', 'SSL Gratuito', 'Suporte 24/7'], false],
                ['Enterprise', 'Infraestrutura dedicada', '1.497', ['16 vCPU', '32 GB RAM', '300 GB SSD', 'Proteção DDoS', 'SSL Gratuito', 'Suporte Dedicado'], false],
            ];
            foreach ($plans as $i => $plan): ?>
            <div class="<?= $plan[4] ? 'pricing-featured' : '' ?> card-premium flex flex-col relative <?= $plan[4] ? 'md:-translate-y-2' : '' ?>" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <?php if ($plan[4]): ?>
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-orange-500 text-black text-xs font-bold px-4 py-1 rounded-full shadow-lg">🔥 Popular</span>
                <?php endif; ?>
                <div class="mb-4 <?= $plan[4] ? 'mt-2' : '' ?>">
                    <h3 class="text-lg font-bold text-white"><?= $plan[0] ?></h3>
                    <p class="text-gray-500 text-xs mt-1"><?= $plan[1] ?></p>
                </div>
                <div class="mb-6">
                    <span class="text-3xl font-bold text-white">R$ <?= $plan[2] ?></span>
                    <span class="text-gray-400 text-sm">/mês</span>
                </div>
                <ul class="space-y-2.5 mb-8 flex-1">
                    <?php foreach ($plan[3] as $f): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="https://cloud.lrvweb.com.br/#planos" target="_blank" class="<?= $plan[4] ? 'btn-primary' : 'btn-outline' ?> w-full justify-center">Contratar</a>
            </div>
            <?php endforeach; ?>

            <!-- Sob Medida -->
            <div class="card-premium flex flex-col border-dashed border-purple-500/30" data-animate="fade-up" data-delay="500">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-purple-300">Sob Medida</h3>
                    <p class="text-gray-500 text-xs mt-1">Precisa de mais recursos?</p>
                </div>
                <div class="mb-6">
                    <span class="text-2xl font-bold text-white">Consultar</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-8 flex-1">Montamos um plano exclusivo para seu projeto. CPU, RAM e disco sob medida com gerenciamento completo.</p>
                <a href="https://cloud.lrvweb.com.br/contato" target="_blank" class="btn-outline w-full justify-center">Falar com Consultor</a>
            </div>
        </div>

        <!-- Adicionais -->
        <div class="mt-10 text-center" data-animate="fade-up">
            <p class="text-gray-500 text-sm">Serviços adicionais: <span class="text-gray-300">Backup Diário (+R$ 90/mês)</span> · <span class="text-gray-300">Suporte WhatsApp (+R$ 290/mês)</span></p>
        </div>
    </div>
</section>

<!-- CONDIÇÕES GERAIS (estilo numerado como no Cloud) -->
<section class="section-dark py-16 border-t border-white/5">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Transparência Total</span>
            <h2 class="text-2xl font-bold mt-3" data-animate="fade-up" data-delay="100">Condições Gerais</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" data-animate="fade-up" data-delay="200">
            <?php
            $conditions = [
                'Valores incluem impostos conforme legislação vigente.',
                'Serviços pré-pagos. Cobrança após ativação.',
                'Faturas mensais com 15 dias de antecedência.',
                'Reajuste anual baseado no IGPM.',
                'Renovação automática por períodos iguais.',
                'Prazo de ativação: até 6 dias úteis.',
                'Backup ativado em até 4 dias úteis adicionais.',
                'Cancelamento: comunicar com 60 dias de antecedência.',
            ];
            foreach ($conditions as $i => $c): ?>
            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5">
                <span class="w-6 h-6 bg-purple-600/20 border border-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-bold text-purple-400"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <p class="text-gray-400 text-sm"><?= $c ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-4" data-animate="fade-up">Ative seu servidor em até <span class="text-gradient">6 dias úteis</span></h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100">Entre em contato e receba uma proposta personalizada.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate="fade-up" data-delay="200">
            <a href="https://cloud.lrvweb.com.br/contato" target="_blank" class="btn-primary">Solicitar Proposta</a>
            <a href="https://cloud.lrvweb.com.br" target="_blank" class="btn-outline">Acessar LRV Cloud</a>
        </div>
    </div>
</section>
