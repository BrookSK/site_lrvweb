<?php $locale = \Core\I18n::getLocale(); ?>

<!-- ============================================ -->
<!-- HERO SECTION -->
<!-- ============================================ -->
<section class="hero-gradient min-h-screen flex items-center relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Texto -->
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 border border-purple-500/20 rounded-full mb-6 animate-fade-up">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-sm text-purple-300">Soluções digitais de alto impacto</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 animate-fade-up delay-100">
                    Transformamos seu negócio com
                    <span class="text-gradient block mt-2" id="typed-text" data-texts='["Sites Profissionais","Hospedagem Premium","Sistemas Sob Medida","E-commerce","Automação"]'>Sites Profissionais</span>
                </h1>

                <p class="text-lg text-gray-300 leading-relaxed mb-8 max-w-lg animate-fade-up delay-200">
                    Hospedagem ultrarrápida, desenvolvimento web de excelência e soluções que realmente geram resultados para o seu negócio.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 animate-fade-up delay-300">
                    <a href="/<?= $locale ?>/contato" class="btn-primary">
                        Solicitar Orçamento
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="/<?= $locale ?>/hospedagem" class="btn-outline">
                        Ver Planos de Hospedagem
                    </a>
                </div>

                <!-- Mini stats -->
                <div class="flex gap-8 mt-12 animate-fade-up delay-400">
                    <div>
                        <p class="text-2xl font-bold text-white">99.9%</p>
                        <p class="text-xs text-gray-400">Uptime</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">24/7</p>
                        <p class="text-xs text-gray-400">Suporte</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">500+</p>
                        <p class="text-xs text-gray-400">Projetos</p>
                    </div>
                </div>
            </div>

            <!-- Visual -->
            <div class="hidden lg:flex justify-center relative">
                <div class="relative w-96 h-96" data-parallax="10">
                    <!-- Orb principal -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/30 to-pink-600/10 rounded-full blur-3xl animate-float"></div>
                    <!-- Elementos flutuantes -->
                    <div class="absolute top-10 right-10 w-20 h-20 bg-purple-600/20 border border-purple-500/30 rounded-2xl backdrop-blur-sm flex items-center justify-center animate-float" style="animation-delay: 0.5s">
                        <span class="text-3xl">🚀</span>
                    </div>
                    <div class="absolute bottom-20 left-5 w-16 h-16 bg-purple-600/20 border border-purple-500/30 rounded-2xl backdrop-blur-sm flex items-center justify-center animate-float" style="animation-delay: 1s">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gradient-to-br from-purple-600 to-purple-800 rounded-3xl shadow-2xl shadow-purple-500/30 flex items-center justify-center animate-glow">
                        <span class="text-6xl">🌐</span>
                    </div>
                    <div class="absolute top-32 left-0 w-14 h-14 bg-purple-600/20 border border-purple-500/30 rounded-xl backdrop-blur-sm flex items-center justify-center animate-float" style="animation-delay: 1.5s">
                        <span class="text-xl">💎</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    </div>
</section>

<!-- ============================================ -->
<!-- SERVIÇOS -->
<!-- ============================================ -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">O que fazemos</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-3" data-animate="fade-up" data-delay="100">Soluções completas para sua <span class="text-gradient">presença digital</span></h2>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto" data-animate="fade-up" data-delay="200">Da hospedagem ao desenvolvimento de sistemas complexos, cuidamos de tudo para você focar no que importa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $defaultServices = [
                ['icon' => '🖥️', 'name' => 'Hospedagem Premium', 'desc' => 'Servidores ultrarrápidos com SSD NVMe, SSL grátis e suporte 24/7. Uptime de 99.9%.', 'featured' => true],
                ['icon' => '🌐', 'name' => 'Criação de Sites', 'desc' => 'Sites modernos, responsivos e otimizados para SEO. Design exclusivo para sua marca.'],
                ['icon' => '🛒', 'name' => 'E-commerce', 'desc' => 'Lojas virtuais completas com gateway de pagamento, gestão de estoque e muito mais.'],
                ['icon' => '⚙️', 'name' => 'Sistemas Sob Medida', 'desc' => 'Desenvolvimento de sistemas personalizados para automatizar seus processos.'],
                ['icon' => '💬', 'name' => 'Automação WhatsApp', 'desc' => 'Chatbots, disparo em massa e atendimento automatizado para escalar seu negócio.'],
                ['icon' => '📱', 'name' => 'Social Media', 'desc' => 'Gestão completa de redes sociais com estratégia, criação e análise de resultados.'],
            ];
            $displayServices = !empty($services) ? $services : $defaultServices;
            foreach ($displayServices as $i => $s): ?>
            <div class="card-premium <?= ($s['featured'] ?? false) ? 'border-purple-500/50' : '' ?>" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <?php if ($s['featured'] ?? false): ?>
                    <span class="absolute top-4 right-4 text-xs bg-purple-600 text-white px-2 py-1 rounded-full">Popular</span>
                <?php endif; ?>
                <span class="text-3xl mb-4 block"><?= $s['icon'] ?? '🌐' ?></span>
                <h3 class="text-lg font-semibold text-white mb-2"><?= htmlspecialchars($s['name']) ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= htmlspecialchars($s['desc'] ?? $s['short_description'] ?? '') ?></p>
                <a href="/<?= $locale ?>/servicos/<?= $s['slug'] ?? '#' ?>" class="inline-flex items-center gap-1 text-purple-400 text-sm font-medium mt-4 hover:text-purple-300 transition group">
                    Saiba mais <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- HOSPEDAGEM DESTAQUE -->
<!-- ============================================ -->
<section class="section-gradient py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Hospedagem</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-3" data-animate="fade-up" data-delay="100">Performance <span class="text-gradient">absurda</span> para seu site</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            <!-- Básico -->
            <div class="card-premium text-center" data-animate="fade-up" data-delay="100">
                <h3 class="text-lg font-semibold text-white">Starter</h3>
                <div class="my-6"><span class="text-4xl font-bold text-white">R$ 29</span><span class="text-gray-400">/mês</span></div>
                <ul class="text-sm text-gray-400 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>10GB SSD</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>1 Site</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>SSL Grátis</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Backup Semanal</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center">Contratar</a>
            </div>

            <!-- Pro (destaque) -->
            <div class="pricing-featured card-premium text-center relative transform md:-translate-y-4" data-animate="scale" data-delay="200">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-orange-500 text-black text-xs font-bold px-4 py-1 rounded-full">Mais Vendido</span>
                <h3 class="text-lg font-semibold text-white mt-2">Professional</h3>
                <div class="my-6"><span class="text-5xl font-bold text-white">R$ 59</span><span class="text-purple-200">/mês</span></div>
                <ul class="text-sm text-gray-300 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>50GB SSD NVMe</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Sites Ilimitados</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>SSL + CDN Grátis</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Backup Diário</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>E-mail Profissional</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Suporte Prioritário</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-primary w-full justify-center">Contratar Agora</a>
            </div>

            <!-- Enterprise -->
            <div class="card-premium text-center" data-animate="fade-up" data-delay="300">
                <h3 class="text-lg font-semibold text-white">Enterprise</h3>
                <div class="my-6"><span class="text-4xl font-bold text-white">R$ 129</span><span class="text-gray-400">/mês</span></div>
                <ul class="text-sm text-gray-400 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>200GB NVMe</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>VPS Dedicado</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>IP Dedicado</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Backup Real-time</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Suporte 24/7</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center">Contratar</a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- STATS / NÚMEROS -->
<!-- ============================================ -->
<section class="section-dark py-20 border-y border-white/5" data-counter-section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-animate="fade-up" data-delay="0">
                <p class="stat-number" data-count="500">0</p>
                <p class="text-gray-400 text-sm mt-2">Projetos Entregues</p>
            </div>
            <div data-animate="fade-up" data-delay="100">
                <p class="stat-number" data-count="150">0</p>
                <p class="text-gray-400 text-sm mt-2">Clientes Ativos</p>
            </div>
            <div data-animate="fade-up" data-delay="200">
                <p class="stat-number" data-count="99">0</p>
                <p class="text-gray-400 text-sm mt-2">% Uptime</p>
            </div>
            <div data-animate="fade-up" data-delay="300">
                <p class="stat-number" data-count="8">0</p>
                <p class="text-gray-400 text-sm mt-2">Anos de Experiência</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PORTFÓLIO RECENTE -->
<!-- ============================================ -->
<?php if (!empty($portfolio)): ?>
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Portfólio</span>
                <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Projetos que <span class="text-gradient">falam por si</span></h2>
            </div>
            <a href="/<?= $locale ?>/portfolio" class="hidden md:inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 font-medium transition group" data-animate="fade-up">
                Ver todos <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($portfolio as $i => $item): ?>
            <a href="/<?= $locale ?>/portfolio/<?= $item['slug'] ?>" class="group block" data-animate="fade-up" data-delay="<?= $i * 100 ?>">
                <div class="relative aspect-video bg-gray-900 rounded-2xl overflow-hidden border border-white/5 group-hover:border-purple-500/50 transition-all duration-300">
                    <?php if ($item['image_cover']): ?>
                        <img src="<?= htmlspecialchars($item['image_cover']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                        <span class="text-white font-medium">Ver projeto →</span>
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
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl md:text-5xl font-bold mb-6" data-animate="fade-up">
            Pronto para <span class="text-gradient">transformar</span> sua presença digital?
        </h2>
        <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto" data-animate="fade-up" data-delay="100">
            Converse com nossa equipe e descubra como podemos ajudar seu negócio a crescer online. Sem compromisso.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate="fade-up" data-delay="200">
            <a href="/<?= $locale ?>/contato" class="btn-primary text-lg px-10">
                Iniciar Projeto
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="/<?= $locale ?>/portfolio" class="btn-outline text-lg px-10">
                Ver Nosso Trabalho
            </a>
        </div>
    </div>
</section>
