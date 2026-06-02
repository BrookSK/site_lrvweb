<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up">Hospedagem Premium</span>
        <h1 class="text-4xl md:text-6xl font-bold mt-4 animate-fade-up delay-100">Seu site <span class="text-gradient">voando</span> na velocidade que merece</h1>
        <p class="text-lg text-gray-300 mt-6 max-w-2xl mx-auto animate-fade-up delay-200">Servidores NVMe de última geração, CDN global e suporte humano. Sua hospedagem nunca mais vai ser um problema.</p>
        <div class="flex flex-wrap gap-6 justify-center mt-10 animate-fade-up delay-300">
            <div class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>99.9% Uptime</div>
            <div class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>SSL Grátis</div>
            <div class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Backup Automático</div>
            <div class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Suporte 24/7</div>
        </div>
    </div>
</section>

<!-- PLANOS -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            <!-- Starter -->
            <div class="card-premium" data-animate="fade-up" data-delay="100">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-white">Starter</h3>
                    <p class="text-xs text-gray-500 mt-1">Ideal para sites pessoais e landing pages</p>
                    <div class="mt-4"><span class="text-4xl font-bold text-white">R$ 29</span><span class="text-gray-400 text-sm">/mês</span></div>
                </div>
                <ul class="space-y-3 mb-8">
                    <?php foreach (['10GB SSD','1 Domínio','3 Contas de E-mail','SSL Let\'s Encrypt','Backup Semanal','Painel cPanel','Suporte por E-mail','Tráfego Ilimitado'] as $f): ?>
                    <li class="flex items-center gap-3 text-sm text-gray-300"><svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center">Quero Este</a>
            </div>

            <!-- Professional -->
            <div class="pricing-featured card-premium relative transform md:-translate-y-4" data-animate="scale" data-delay="200">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-orange-500 text-black text-xs font-bold px-4 py-1 rounded-full shadow-lg">🔥 Mais Vendido</span>
                <div class="text-center mb-6 mt-2">
                    <h3 class="text-lg font-semibold text-white">Professional</h3>
                    <p class="text-xs text-purple-200 mt-1">Para negócios que precisam de performance</p>
                    <div class="mt-4"><span class="text-5xl font-bold text-white">R$ 59</span><span class="text-purple-200 text-sm">/mês</span></div>
                </div>
                <ul class="space-y-3 mb-8">
                    <?php foreach (['50GB SSD NVMe','Sites Ilimitados','E-mails Ilimitados','SSL + CDN Global','Backup Diário','LiteSpeed Web Server','Suporte Prioritário','E-mail Profissional','Anti-DDoS','Staging Environment'] as $f): ?>
                    <li class="flex items-center gap-3 text-sm text-gray-200"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-primary w-full justify-center">Quero Este</a>
            </div>

            <!-- Enterprise -->
            <div class="card-premium" data-animate="fade-up" data-delay="300">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-white">Enterprise</h3>
                    <p class="text-xs text-gray-500 mt-1">Para projetos de alta demanda</p>
                    <div class="mt-4"><span class="text-4xl font-bold text-white">R$ 129</span><span class="text-gray-400 text-sm">/mês</span></div>
                </div>
                <ul class="space-y-3 mb-8">
                    <?php foreach (['200GB NVMe RAID','VPS Dedicado','IP Dedicado','Backup Real-time','Suporte 24/7 WhatsApp','LiteSpeed + Redis','Firewall Avançado','Monitoramento 24h','Migração Gratuita','SLA 99.99%'] as $f): ?>
                    <li class="flex items-center gap-3 text-sm text-gray-300"><svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center">Quero Este</a>
            </div>
        </div>
    </div>
</section>

<!-- INFRAESTRUTURA -->
<section class="section-gradient py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Infraestrutura</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Tecnologia de <span class="text-gradient">ponta</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $infra = [
                ['🖥️', 'VPS Cloud', 'Servidores virtuais com recursos dedicados e escalabilidade sob demanda.'],
                ['💾', 'Backup em Nuvem', 'Backups automáticos com retenção de 30 dias e restauração em 1 clique.'],
                ['📧', 'E-mail Profissional', 'E-mail com seu domínio, antispam IA e acesso via IMAP/POP3/Webmail.'],
                ['🛡️', 'Segurança Total', 'Firewall, anti-DDoS, WAF, malware scan e isolamento de contas.'],
            ];
            foreach ($infra as $i => $item): ?>
            <div class="card-premium text-center" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <span class="text-3xl block mb-4"><?= $item[0] ?></span>
                <h3 class="text-white font-semibold mb-2"><?= $item[1] ?></h3>
                <p class="text-gray-400 text-sm"><?= $item[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-6" data-animate="fade-up">Migração <span class="text-gradient">gratuita</span> de qualquer provedor</h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100">Nossa equipe cuida de tudo. Sem downtime, sem dor de cabeça.</p>
        <a href="/<?= $locale ?>/contato" class="btn-primary text-lg px-10" data-animate="fade-up" data-delay="200">Migrar Agora — É Grátis</a>
    </div>
</section>
