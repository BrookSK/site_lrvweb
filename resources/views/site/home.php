<?php $locale = \Core\I18n::getLocale(); ?>

<!-- ============================================ -->
<!-- HERO -->
<!-- ============================================ -->
<section class="hero-gradient min-h-[90vh] flex items-center relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 border border-purple-500/20 rounded-full mb-8 animate-fade-up">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-sm text-purple-300">Infraestrutura & Desenvolvimento Digital</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-bold leading-[1.15] mb-8 animate-fade-up delay-100">
                    Hospedagem Cloud,<br>Sites e Sistemas que
                    <span class="text-gradient block mt-1" id="typed-text" data-texts='["escalam seu negócio","geram resultados","vendem mais","automatizam processos"]'>escalam seu negócio</span>
                </h1>

                <p class="text-lg text-gray-300 leading-relaxed mb-10 max-w-lg animate-fade-up delay-200">
                    Servidores VPS de alta performance, criação de sites premium e sistemas sob medida. Tudo em um só lugar, com suporte humano de verdade.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 animate-fade-up delay-300">
                    <a href="/<?= $locale ?>/contato" class="btn-primary">
                        Solicitar Orçamento
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="https://cloud.lrvweb.com.br" target="_blank" class="btn-outline">
                        Conheça o Cloud
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <!-- Visual decorativo -->
            <div class="hidden lg:flex justify-center relative">
                <div class="relative w-[420px] h-[420px]">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/15 to-pink-600/5 rounded-full blur-3xl"></div>
                    <!-- Cards flutuantes - posicionados fora do centro -->
                    <div class="absolute top-4 right-0 glass px-4 py-3 rounded-xl animate-float z-10" style="animation-delay:0.5s">
                        <div class="flex items-center gap-2"><span class="w-2 h-2 bg-green-400 rounded-full"></span><span class="text-xs text-gray-300">99.9% Uptime</span></div>
                    </div>
                    <div class="absolute bottom-12 -left-4 glass px-4 py-3 rounded-xl animate-float z-10" style="animation-delay:1.2s">
                        <div class="flex items-center gap-2"><span class="text-lg">⚡</span><span class="text-xs text-gray-300">NVMe SSD</span></div>
                    </div>
                    <div class="absolute top-1/4 -left-8 glass px-4 py-3 rounded-xl animate-float z-10" style="animation-delay:0.8s">
                        <div class="flex items-center gap-2"><span class="text-lg">🛡️</span><span class="text-xs text-gray-300">DDoS Protection</span></div>
                    </div>
                    <!-- Centro -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gradient-to-br from-purple-600 to-purple-900 rounded-3xl shadow-2xl shadow-purple-500/30 flex items-center justify-center animate-glow border border-purple-500/30 z-0">
                        <div class="text-center">
                            <span class="text-5xl block mb-2">☁️</span>
                            <p class="text-white font-bold text-sm">LRV Cloud</p>
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 glass px-4 py-3 rounded-xl animate-float z-10" style="animation-delay:1.8s">
                        <div class="flex items-center gap-2"><span class="text-lg">🚀</span><span class="text-xs text-gray-300">Deploy Rápido</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- MARCAS DE CONFIANÇA -->
<!-- ============================================ -->
<section class="section-dark py-10 border-y border-white/5">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 text-gray-500 text-sm font-medium">
            <span class="opacity-60">Startups</span>
            <span class="opacity-60">E-commerce</span>
            <span class="opacity-60">SaaS</span>
            <span class="opacity-60">Agências</span>
            <span class="opacity-60">Desenvolvedores</span>
            <span class="opacity-60">Empresas</span>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- NÚMEROS -->
<!-- ============================================ -->
<section class="section-dark py-16" data-counter-section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php
            $stats = [
                ['500', 'Projetos Entregues', '+'],
                ['200', 'Clientes Ativos', '+'],
                ['99.9', 'Uptime Garantido', '%'],
                ['5', 'Anos de Mercado', '+'],
            ];
            foreach ($stats as $i => $s): ?>
            <div class="text-center p-6 rounded-2xl bg-white/[0.02] border border-white/5" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <p class="stat-number text-3xl md:text-4xl" data-count="<?= $s[0] ?>"><?= $s[0] ?></p>
                <span class="text-purple-400 text-lg font-bold"><?= $s[2] ?></span>
                <p class="text-gray-400 text-sm mt-1"><?= $s[1] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- O QUE FAZEMOS (Serviços principais) -->
<!-- ============================================ -->
<section class="section-dark py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Soluções</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-3" data-animate="fade-up" data-delay="100">O que a LRV Web <span class="text-gradient">faz por você</span></h2>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto" data-animate="fade-up" data-delay="150">Somos especialistas em infraestrutura cloud, desenvolvimento web e automação digital.</p>
        </div>

        <!-- Grid principal (3 colunas destaque) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- HOSPEDAGEM (destaque) -->
            <div class="card-premium border-purple-500/30 lg:row-span-2 flex flex-col justify-between" data-animate="fade-up" data-delay="100">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center"><span class="text-2xl">☁️</span></div>
                        <span class="text-xs bg-purple-600/20 text-purple-300 px-2 py-1 rounded-full border border-purple-500/20">Principal</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Hospedagem Cloud & VPS</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">Servidores VPS com recursos dedicados, proteção DDoS nativa, uptime 99.9% e suporte técnico especializado. Infraestrutura enterprise para seu projeto.</p>
                    <ul class="space-y-2 text-sm text-gray-400 mb-6">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Intel Xeon + NVMe SSD</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Proteção DDoS inclusa</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Tráfego ilimitado</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Suporte humanizado</li>
                    </ul>
                </div>
                <a href="https://cloud.lrvweb.com.br" target="_blank" class="btn-primary w-full justify-center">
                    Ver Planos Cloud
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>

            <!-- SITES -->
            <div class="card-premium" data-animate="fade-up" data-delay="200">
                <div class="w-10 h-10 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center mb-4"><span class="text-xl">🌐</span></div>
                <h3 class="text-lg font-semibold text-white mb-2">Criação de Sites</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Sites profissionais, rápidos e otimizados para conversão. Design exclusivo que representa sua marca.</p>
                <a href="/<?= $locale ?>/contato" class="inline-flex items-center gap-1 text-purple-400 text-sm font-medium mt-4 hover:text-purple-300 transition group">Solicitar orçamento <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>

            <!-- SISTEMAS -->
            <div class="card-premium" data-animate="fade-up" data-delay="300">
                <div class="w-10 h-10 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center mb-4"><span class="text-xl">⚙️</span></div>
                <h3 class="text-lg font-semibold text-white mb-2">Sistemas Sob Medida</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Sistemas, painéis, CRM, ERP e automações desenvolvidos especificamente para resolver seus problemas.</p>
                <a href="/<?= $locale ?>/contato" class="inline-flex items-center gap-1 text-purple-400 text-sm font-medium mt-4 hover:text-purple-300 transition group">Solicitar orçamento <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>

            <!-- E-COMMERCE -->
            <div class="card-premium" data-animate="fade-up" data-delay="350">
                <div class="w-10 h-10 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center mb-4"><span class="text-xl">🛒</span></div>
                <h3 class="text-lg font-semibold text-white mb-2">E-commerce</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Lojas virtuais completas com checkout otimizado, integrações de pagamento e gestão de estoque.</p>
                <a href="/<?= $locale ?>/contato" class="inline-flex items-center gap-1 text-purple-400 text-sm font-medium mt-4 hover:text-purple-300 transition group">Solicitar orçamento <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>

            <!-- AUTOMAÇÃO -->
            <div class="card-premium" data-animate="fade-up" data-delay="400">
                <div class="w-10 h-10 bg-purple-600/20 border border-purple-500/30 rounded-xl flex items-center justify-center mb-4"><span class="text-xl">💬</span></div>
                <h3 class="text-lg font-semibold text-white mb-2">Automação & WhatsApp</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Chatbots, disparos, atendimento automatizado e integrações que escalam sua operação comercial.</p>
                <a href="/<?= $locale ?>/contato" class="inline-flex items-center gap-1 text-purple-400 text-sm font-medium mt-4 hover:text-purple-300 transition group">Solicitar orçamento <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- POR QUE NÓS (Diferenciais - estilo cloud) -->
<!-- ============================================ -->
<section class="section-gradient py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Por que a LRV Web?</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-3" data-animate="fade-up" data-delay="100">Tecnologia de ponta com <span class="text-gradient">suporte humano</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $features = [
                ['⚡', 'Desempenho Superior', 'Processadores Intel Xeon e armazenamento NVMe para máximo throughput e mínima latência.'],
                ['🛡️', 'Proteção DDoS Nativa', 'Infraestrutura blindada contra ataques volumétricos. Inclusa em todos os serviços.'],
                ['🌐', 'Hiperconectividade', 'Portas de 1 a 10 Gbps com tráfego ilimitado. Conexão direta com CloudFlare, AWS e Google.'],
                ['📈', 'Escalabilidade Real', 'Ajuste CPU, RAM e armazenamento sob demanda sem downtime. Cresça sem limites.'],
                ['🤝', 'Suporte Humanizado', 'Atendimento real por especialistas. Nada de robôs. Resolução rápida e eficiente.'],
                ['💰', 'Custos Transparentes', 'Preços claros, sem taxas ocultas. Tudo combinado previamente na proposta.'],
            ];
            foreach ($features as $i => $f): ?>
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-purple-500/30 transition-all duration-300 group" data-animate="fade-up" data-delay="<?= $i * 80 ?>">
                <span class="text-3xl block mb-4 group-hover:scale-110 transition-transform duration-300"><?= $f[0] ?></span>
                <h3 class="text-white font-semibold mb-2"><?= $f[1] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= $f[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- CLOUD / HOSPEDAGEM DESTAQUE -->
<!-- ============================================ -->
<section class="section-dark py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div data-animate="fade-left">
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider">LRV Cloud Manager</span>
                <h2 class="text-3xl font-bold mt-3 mb-6">Infraestrutura VPS para <span class="text-gradient">escalar seu negócio</span></h2>
                <p class="text-gray-400 leading-relaxed mb-6">Servidores VPS com recursos dedicados, proteção DDoS nativa, uptime 99.9% e suporte técnico. Ativação em até 6 dias úteis.</p>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                        <p class="text-white font-bold text-lg">1-16 vCPU</p>
                        <p class="text-gray-500 text-xs">Processamento</p>
                    </div>
                    <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                        <p class="text-white font-bold text-lg">2-32GB RAM</p>
                        <p class="text-gray-500 text-xs">Memória</p>
                    </div>
                    <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                        <p class="text-white font-bold text-lg">20-300GB</p>
                        <p class="text-gray-500 text-xs">SSD NVMe</p>
                    </div>
                    <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                        <p class="text-white font-bold text-lg">Ilimitado</p>
                        <p class="text-gray-500 text-xs">Tráfego</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="https://cloud.lrvweb.com.br/#planos" target="_blank" class="btn-primary">Ver Planos e Preços</a>
                    <a href="https://cloud.lrvweb.com.br/contato" target="_blank" class="btn-outline">Falar com Consultor</a>
                </div>
            </div>

            <div class="relative" data-animate="fade-right">
                <div class="rounded-3xl border border-purple-500/20 bg-gradient-to-br from-purple-950/50 to-black p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-600/10 rounded-full blur-3xl"></div>
                    <div class="space-y-4 relative">
                        <?php
                        $plans = [
                            ['Startup', '1 vCPU · 2GB RAM · 20GB', 'R$ 90/mês'],
                            ['Essential', '2 vCPU · 4GB RAM · 40GB', 'R$ 297/mês'],
                            ['Professional', '4 vCPU · 8GB RAM · 80GB', 'R$ 697/mês'],
                            ['Business', '8 vCPU · 16GB RAM · 160GB', 'R$ 957/mês'],
                            ['Enterprise', '16 vCPU · 32GB RAM · 300GB', 'R$ 1.497/mês'],
                        ];
                        foreach ($plans as $i => $plan): ?>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.03] border border-white/5 hover:border-purple-500/30 transition group">
                            <div>
                                <p class="text-white font-medium text-sm"><?= $plan[0] ?></p>
                                <p class="text-gray-500 text-xs"><?= $plan[1] ?></p>
                            </div>
                            <span class="text-purple-400 text-sm font-semibold"><?= $plan[2] ?></span>
                        </div>
                        <?php endforeach; ?>
                        <div class="flex items-center justify-between p-3 rounded-xl border border-dashed border-purple-500/30 bg-purple-500/5">
                            <div>
                                <p class="text-purple-300 font-medium text-sm">Sob Medida</p>
                                <p class="text-gray-500 text-xs">Configuração personalizada</p>
                            </div>
                            <span class="text-purple-400 text-sm font-medium">Consultar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PROCESSO -->
<!-- ============================================ -->
<section class="section-dark py-20 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Como funciona</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Do primeiro contato à <span class="text-gradient">entrega</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['💬', '01', 'Conversa', 'Entendemos seu projeto, objetivos e necessidades. Sem compromisso.'],
                ['📋', '02', 'Proposta', 'Montamos uma proposta detalhada com escopo, prazo e valores transparentes.'],
                ['🚀', '03', 'Execução', 'Desenvolvemos com entregas parciais e feedback constante.'],
                ['✅', '04', 'Entrega', 'Lançamos com suporte contínuo e acompanhamento de resultados.'],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="text-center" data-animate="fade-up" data-delay="<?= $i * 120 ?>">
                <div class="w-16 h-16 bg-purple-600/10 border border-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 relative">
                    <span class="text-2xl"><?= $step[0] ?></span>
                    <span class="absolute -top-2 -right-2 w-6 h-6 bg-purple-600 text-white text-xs font-bold rounded-full flex items-center justify-center"><?= $step[1] ?></span>
                </div>
                <h3 class="text-white font-semibold mb-2"><?= $step[2] ?></h3>
                <p class="text-gray-400 text-sm"><?= $step[3] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PORTFÓLIO -->
<!-- ============================================ -->
<?php if (!empty($portfolio)): ?>
<section class="section-dark py-20 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Portfólio</span>
                <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Projetos <span class="text-gradient">recentes</span></h2>
            </div>
            <a href="/<?= $locale ?>/portfolio" class="hidden md:inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 font-medium transition group">
                Ver todos <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($portfolio as $i => $item): ?>
            <a href="/<?= $locale ?>/portfolio/<?= $item['slug'] ?>" class="group block" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <div class="relative aspect-[4/3] bg-gray-900 rounded-2xl overflow-hidden border border-white/5 group-hover:border-purple-500/30 transition-all duration-500">
                    <?php if ($item['image_cover']): ?>
                        <img src="<?= htmlspecialchars($item['image_cover']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-purple-950 to-black flex items-center justify-center"><span class="text-5xl opacity-30">🌐</span></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                        <span class="text-white font-medium text-sm">Ver detalhes →</span>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-purple-400 font-medium"><?= htmlspecialchars($item['category'] ?? '') ?></span>
                    <h3 class="text-white font-semibold mt-1 group-hover:text-purple-300 transition-colors"><?= htmlspecialchars($item['name']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- CTA FINAL -->
<!-- ============================================ -->
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-600/5 rounded-full blur-3xl"></div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <span class="text-purple-300 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Pronto para começar?</span>
        <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-6" data-animate="fade-up" data-delay="100">
            Vamos transformar sua ideia em <span class="text-gradient">realidade</span>
        </h2>
        <p class="text-gray-300 mb-10 text-lg max-w-2xl mx-auto" data-animate="fade-up" data-delay="150">
            Entre em contato e receba uma proposta personalizada. Nossa equipe técnica está pronta para atender.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate="fade-up" data-delay="200">
            <a href="/<?= $locale ?>/contato" class="btn-primary text-base px-8">
                Solicitar Orçamento
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="https://cloud.lrvweb.com.br/contato" target="_blank" class="btn-outline text-base px-8">
                Falar com Consultor
            </a>
        </div>
    </div>
</section>
