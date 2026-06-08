<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up"><?= \Core\I18n::get('services_subtitle') ?></span>
        <h1 class="text-4xl md:text-5xl font-bold mt-4 animate-fade-up delay-100"><?= \Core\I18n::get('services_title') ?></h1>
        <p class="text-gray-300 mt-4 max-w-2xl mx-auto animate-fade-up delay-200"><?= \Core\I18n::get('services_desc') ?></p>
    </div>
</section>

<!-- SERVIÇOS PRINCIPAIS -->
<section class="section-dark py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up"><?= \Core\I18n::get('services_main_focus') ?></span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('services_experts_title') ?></h2>
        </div>

        <!-- 3 grandes serviços -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16">
            <?php
            $mainServices = [
                ['cloud', \Core\I18n::get('service_hosting'), \Core\I18n::get('service_hosting_desc'), ['Intel Xeon', 'NVMe SSD', 'DDoS Protection', 'Backup Diário', 'Tráfego Ilimitado', 'Suporte Especializado']],
                ['settings', \Core\I18n::get('service_systems'), \Core\I18n::get('service_systems_desc'), ['PHP, Node.js, React', 'Banco de Dados', 'API REST', 'Painel Admin', 'Integrações', 'Deploy Incluso']],
                ['globe', \Core\I18n::get('service_websites'), \Core\I18n::get('service_websites_desc'), ['Design Exclusivo', 'SEO Otimizado', 'Responsivo', 'Alta Velocidade', 'Painel CMS', 'SSL Incluso']],
            ];
            foreach ($mainServices as $i => $s): ?>
            <div class="card-premium flex flex-col" data-animate="fade-up" data-delay="<?= $i * 120 ?>">
                <div class="w-14 h-14 bg-purple-600/15 border border-purple-500/20 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="<?= $s[0] ?>" class="w-8 h-8 text-purple-400"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3"><?= $s[1] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6"><?= $s[2] ?></p>
                <ul class="space-y-2 mb-6 flex-1">
                    <?php foreach ($s[3] as $feature): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-300"><svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><?= $feature ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/<?= $locale ?>/contato" class="btn-outline w-full justify-center"><?= \Core\I18n::get('request_quote') ?></a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Serviços complementares -->
        <div class="text-center mb-10">
            <h3 class="text-xl font-bold text-white" data-animate="fade-up"><?= \Core\I18n::get('services_also_offer') ?></h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
            $secondary = [
                ['shopping-cart', \Core\I18n::get('service_ecommerce'), \Core\I18n::get('service_ecommerce_desc')],
                ['message-circle', \Core\I18n::get('service_automation'), \Core\I18n::get('service_automation_desc')],
                ['smartphone', \Core\I18n::get('service_social'), \Core\I18n::get('service_social_desc')],
                ['palette', \Core\I18n::get('service_branding'), \Core\I18n::get('service_branding_desc')],
                ['wrench', \Core\I18n::get('service_maintenance'), \Core\I18n::get('service_maintenance_desc')],
                ['hard-drive', \Core\I18n::get('service_backup'), \Core\I18n::get('service_backup_desc')],
                ['mail', \Core\I18n::get('service_email'), \Core\I18n::get('service_email_desc')],
                ['calendar', \Core\I18n::get('service_scheduling'), \Core\I18n::get('service_scheduling_desc')],
            ];
            foreach ($secondary as $i => $s): ?>
            <div class="p-5 rounded-xl bg-white/[0.02] border border-white/5 hover:border-purple-500/30 transition-all duration-300 group" data-animate="fade-up" data-delay="<?= $i * 60 ?>">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-purple-600/10 border border-purple-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="<?= $s[0] ?>" class="w-5 h-5 text-purple-400"></i>
                    </div>
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
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider" data-animate="fade-up"><?= \Core\I18n::get('services_process') ?></span>
            <h2 class="text-3xl font-bold mt-3" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('services_process_title') ?></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['01', 'message-circle', \Core\I18n::get('step_1_title'), \Core\I18n::get('step_1_desc')],
                ['02', 'clipboard', \Core\I18n::get('step_2_title'), \Core\I18n::get('step_2_desc')],
                ['03', 'rocket', \Core\I18n::get('step_3_title'), \Core\I18n::get('step_3_desc')],
                ['04', 'check-circle', \Core\I18n::get('step_4_title'), \Core\I18n::get('step_4_desc')],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="text-center" data-animate="fade-up" data-delay="<?= $i * 120 ?>">
                <div class="w-16 h-16 bg-purple-600/10 border border-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 relative">
                    <i data-lucide="<?= $step[1] ?>" class="w-6 h-6 text-purple-400"></i>
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
        <h2 class="text-3xl font-bold mb-4" data-animate="fade-up"><?= \Core\I18n::get('services_unique') ?></h2>
        <p class="text-gray-300 mb-8 text-lg" data-animate="fade-up" data-delay="100"><?= \Core\I18n::get('services_unique_desc') ?></p>
        <a href="/<?= $locale ?>/contato" class="btn-primary text-base px-10" data-animate="fade-up" data-delay="200">
            <?= \Core\I18n::get('request_custom_quote') ?>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
