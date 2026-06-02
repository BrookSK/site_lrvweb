<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up">Nossos Serviços</span>
        <h1 class="text-4xl md:text-6xl font-bold mt-4 animate-fade-up delay-100">Tudo que seu negócio precisa em <span class="text-gradient">um só lugar</span></h1>
        <p class="text-lg text-gray-300 mt-6 max-w-2xl mx-auto animate-fade-up delay-200">Do primeiro pixel ao servidor em produção. Soluções completas que geram resultados de verdade.</p>
    </div>
</section>

<!-- LISTA DE SERVIÇOS -->
<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        $allServices = !empty($services) ? $services : [
            ['icon' => '🖥️', 'name' => 'Hospedagem de Sites', 'slug' => 'hospedagem', 'short_description' => 'Servidores ultra-rápidos com SSD NVMe, SSL grátis, backup automático e suporte especializado. Uptime de 99.9% garantido.', 'price_from' => 29],
            ['icon' => '🌐', 'name' => 'Criação de Sites', 'slug' => 'criacao-sites', 'short_description' => 'Sites modernos, responsivos e otimizados para SEO. Design exclusivo que converte visitantes em clientes.', 'price_from' => 1500],
            ['icon' => '🛒', 'name' => 'E-commerce', 'slug' => 'e-commerce', 'short_description' => 'Lojas virtuais completas com checkout otimizado, gateway de pagamento integrado e gestão de estoque.', 'price_from' => 3000],
            ['icon' => '⚙️', 'name' => 'Sistemas Sob Medida', 'slug' => 'sistemas', 'short_description' => 'Sistemas personalizados para automatizar processos, CRM, ERP, painéis de gestão e integrações.', 'price_from' => 5000],
            ['icon' => '💬', 'name' => 'Automação WhatsApp', 'slug' => 'automacao-whatsapp', 'short_description' => 'Chatbots inteligentes, disparo em massa, atendimento automatizado e integração com seus sistemas.', 'price_from' => 500],
            ['icon' => '📱', 'name' => 'Social Media', 'slug' => 'social-media', 'short_description' => 'Gestão completa de redes sociais: estratégia, criação de conteúdo, design e análise de resultados.', 'price_from' => 800],
            ['icon' => '🎨', 'name' => 'Criação de Marca', 'slug' => 'criacao-marca', 'short_description' => 'Logotipo, identidade visual, manual de marca e materiais gráficos que posicionam seu negócio.', 'price_from' => 1200],
            ['icon' => '🔧', 'name' => 'Manutenção e Suporte', 'slug' => 'manutencao', 'short_description' => 'Manutenção preventiva, atualizações de segurança, monitoramento e suporte técnico contínuo.', 'price_from' => 200],
            ['icon' => '📅', 'name' => 'Agendamento Online', 'slug' => 'agendamento', 'short_description' => 'Sistema de agendamento integrado ao seu site com confirmação automática e lembretes via WhatsApp.', 'price_from' => 300],
        ];

        foreach ($allServices as $i => $s): ?>
        <div class="flex flex-col md:flex-row gap-8 items-center py-12 <?= $i > 0 ? 'border-t border-white/5' : '' ?>" data-animate="<?= $i % 2 === 0 ? 'fade-left' : 'fade-right' ?>">
            <div class="flex-shrink-0 w-20 h-20 bg-gradient-to-br from-purple-600/20 to-purple-800/10 rounded-2xl border border-purple-500/20 flex items-center justify-center">
                <span class="text-4xl"><?= $s['icon'] ?? '🌐' ?></span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-white mb-2"><?= htmlspecialchars($s['name']) ?></h3>
                <p class="text-gray-400 leading-relaxed"><?= htmlspecialchars($s['short_description'] ?? '') ?></p>
            </div>
            <div class="flex-shrink-0 text-right">
                <?php if (!empty($s['price_from'])): ?>
                    <p class="text-sm text-gray-500">A partir de</p>
                    <p class="text-2xl font-bold text-purple-400">R$ <?= number_format((float)$s['price_from'], 0, ',', '.') ?></p>
                <?php endif; ?>
                <a href="/<?= $locale ?>/contato" class="inline-flex items-center gap-2 mt-3 text-sm text-purple-400 hover:text-purple-300 font-medium transition group">
                    Solicitar <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- PROCESSO -->
<section class="section-gradient py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Como funciona</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Nosso processo em <span class="text-gradient">4 etapas</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['01', 'Conversa', 'Entendemos seu negócio, objetivos e necessidades em uma conversa sem compromisso.'],
                ['02', 'Proposta', 'Criamos uma proposta detalhada com escopo, prazo e investimento transparentes.'],
                ['03', 'Execução', 'Desenvolvemos com metodologia ágil, entregas parciais e feedback constante.'],
                ['04', 'Entrega', 'Lançamos seu projeto com suporte contínuo e acompanhamento de resultados.'],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="text-center relative" data-animate="fade-up" data-delay="<?= $i * 150 ?>">
                <div class="w-14 h-14 bg-purple-600/20 border border-purple-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-purple-400 font-bold"><?= $step[0] ?></span>
                </div>
                <h3 class="text-white font-semibold mb-2"><?= $step[1] ?></h3>
                <p class="text-gray-400 text-sm"><?= $step[2] ?></p>
                <?php if ($i < 3): ?>
                    <div class="hidden md:block absolute top-7 left-full w-full h-px bg-gradient-to-r from-purple-500/30 to-transparent -translate-x-1/2"></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-6" data-animate="fade-up">Não sabe por onde começar?</h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100">Fale com a gente. Vamos entender sua necessidade e indicar a melhor solução.</p>
        <a href="/<?= $locale ?>/contato" class="btn-primary text-lg px-10" data-animate="fade-up" data-delay="200">Falar com Especialista</a>
    </div>
</section>
