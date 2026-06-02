<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up">Nossos Serviços</span>
        <h1 class="text-4xl md:text-5xl font-bold mt-4 animate-fade-up delay-100">Soluções digitais <span class="text-gradient">sob medida</span></h1>
        <p class="text-gray-300 mt-4 max-w-2xl mx-auto animate-fade-up delay-200">Cada projeto é único. Analisamos sua necessidade e entregamos a melhor solução — sempre com orçamento personalizado.</p>
    </div>
</section>

<!-- SERVIÇOS PRINCIPAIS -->
<section class="section-dark py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Foco Principal</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Onde somos <span class="text-gradient">especialistas</span></h2>
        </div>

        <!-- 3 grandes serviços -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16">
            <?php
            $mainServices = [
                ['☁️', 'Hospedagem Cloud & VPS', 'Infraestrutura de alto desempenho com servidores dedicados, proteção DDoS, NVMe SSD e suporte 24/7. Para projetos que precisam de performance real.', ['Intel Xeon', 'NVMe SSD', 'DDoS Protection', 'Backup Diário', 'Tráfego Ilimitado', 'Suporte Especializado']],
                ['⚙️', 'Sistemas & Aplicativos', 'Desenvolvimento de sistemas web, aplicativos, painéis administrativos, CRM, ERP e automações. Código limpo, escalável e documentado.', ['PHP, Node.js, React', 'Banco de Dados', 'API REST', 'Painel Admin', 'Integrações', 'Deploy Incluso']],
                ['🌐', 'Criação de Sites', 'Sites institucionais, landing pages e portais com design exclusivo, SEO otimizado e foco total em conversão de visitantes em clientes.', ['Design Exclusivo', 'SEO Otimizado', 'Responsivo', 'Alta Velocidade', 'Painel CMS', 'SSL Incluso']],
            ];
            foreach ($mainServices as $i => $s): ?>
            <div class="card-premium flex flex-col" data-animate="fade-up" data-delay="<?= $i * 120 ?>">
                <div class="w-14 h-14 bg-purple-600/15 border border-purple-500/20 rounded-2xl flex items-center justify-center mb-5">
                    <span class="text-3xl"><?= $s[0] ?></span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3"><?= $s[1] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6"><?= $s[2] ?></p>
                <ul class="space-y-2 mb-6 flex-1">
                    <?php foreach ($s[3] as $feature): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $feature ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center">Solicitar Orçamento</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Serviços complementares -->
        <div class="text-center mb-10">
            <h3 class="text-xl font-bold text-white" data-animate="fade-up">Também oferecemos</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
            $secondary = [
                ['🛒', 'E-commerce', 'Lojas virtuais com checkout otimizado e gestão completa.'],
                ['💬', 'Automação WhatsApp', 'Chatbots, disparos e atendimento automatizado.'],
                ['📱', 'Social Media', 'Gestão de redes com estratégia e criação de conteúdo.'],
                ['🎨', 'Branding', 'Logotipo, identidade visual e materiais gráficos.'],
                ['🔧', 'Manutenção', 'Atualizações, monitoramento e suporte contínuo.'],
                ['💾', 'Backup', 'Proteção dos seus dados com backups automáticos.'],
                ['📧', 'E-mail Profissional', 'E-mail com seu domínio e antispam avançado.'],
                ['📅', 'Agendamento Online', 'Sistema de agendamento com confirmação automática.'],
            ];
            foreach ($secondary as $i => $s): ?>
            <div class="p-5 rounded-xl bg-white/[0.02] border border-white/5 hover:border-purple-500/30 transition-all duration-300 group" data-animate="fade-up" data-delay="<?= $i * 60 ?>">
                <div class="flex items-center gap-3">
                    <span class="text-xl group-hover:scale-110 transition-transform"><?= $s[0] ?></span>
                    <div>
                        <h4 class="text-white text-sm font-semibold"><?= $s[1] ?></h4>
                        <p class="text-gray-500 text-xs mt-0.5"><?= $s[2] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROCESSO -->
<section class="section-gradient py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up">Processo</span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100">Simples, transparente e <span class="text-gradient">eficiente</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['01', '💬', 'Conversa Inicial', 'Entendemos seu negócio e o que você precisa. Sem compromisso, sem jargão técnico.'],
                ['02', '📋', 'Proposta Detalhada', 'Criamos um orçamento com escopo claro, prazo definido e valores transparentes.'],
                ['03', '🚀', 'Desenvolvimento', 'Executamos com entregas parciais para você acompanhar cada etapa do progresso.'],
                ['04', '✅', 'Entrega + Suporte', 'Lançamos seu projeto com acompanhamento e suporte contínuo pós-entrega.'],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="text-center" data-animate="fade-up" data-delay="<?= $i * 120 ?>">
                <div class="w-16 h-16 bg-purple-600/10 border border-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 relative">
                    <span class="text-2xl"><?= $step[1] ?></span>
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-purple-600 text-white text-xs font-bold rounded-full flex items-center justify-center"><?= $step[0] ?></span>
                </div>
                <h3 class="text-white font-semibold mb-2"><?= $step[2] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= $step[3] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-black"></div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-4" data-animate="fade-up">Cada projeto é <span class="text-gradient">único</span></h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100">Conte o que você precisa e montamos a melhor proposta. Sem fórmulas prontas, sem surpresas.</p>
        <a href="/<?= $locale ?>/contato" class="btn-primary text-base px-10" data-animate="fade-up" data-delay="200">
            Solicitar Orçamento Personalizado
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
