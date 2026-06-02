<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('hosting_plans') ?></h1>
        <p class="text-blue-100 text-lg">Hospedagem rápida, segura e com suporte especializado.</p>
    </div>
</section>

<!-- Planos -->
<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Básico -->
            <div class="card-hover bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 text-center" data-animate>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Básico</h3>
                <div class="my-6">
                    <span class="text-5xl font-bold text-blue-600">R$ 29</span>
                    <span class="text-gray-500">/mês</span>
                </div>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> 10GB SSD</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> 1 Site</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> SSL Grátis</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Backup Semanal</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Suporte por E-mail</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Painel cPanel</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="block w-full py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition">Contratar</a>
            </div>

            <!-- Profissional -->
            <div class="card-hover bg-blue-600 rounded-xl p-8 shadow-xl text-center text-white relative transform scale-105" data-animate>
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-4 py-1 rounded-full">Mais Popular</span>
                <h3 class="text-xl font-semibold">Profissional</h3>
                <div class="my-6">
                    <span class="text-5xl font-bold">R$ 59</span>
                    <span class="text-blue-200">/mês</span>
                </div>
                <ul class="text-sm text-blue-100 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><span>✓</span> 50GB SSD</li>
                    <li class="flex items-center gap-2"><span>✓</span> Sites Ilimitados</li>
                    <li class="flex items-center gap-2"><span>✓</span> SSL Grátis</li>
                    <li class="flex items-center gap-2"><span>✓</span> Backup Diário</li>
                    <li class="flex items-center gap-2"><span>✓</span> Suporte Prioritário</li>
                    <li class="flex items-center gap-2"><span>✓</span> E-mail Profissional</li>
                    <li class="flex items-center gap-2"><span>✓</span> CDN Incluso</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="block w-full py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition">Contratar</a>
            </div>

            <!-- Empresarial -->
            <div class="card-hover bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 text-center" data-animate>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Empresarial</h3>
                <div class="my-6">
                    <span class="text-5xl font-bold text-blue-600">R$ 129</span>
                    <span class="text-gray-500">/mês</span>
                </div>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-3 mb-8 text-left">
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> 200GB SSD NVMe</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Sites Ilimitados</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> SSL Grátis</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Backup em Tempo Real</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Suporte 24/7</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> VPS Dedicado</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> IP Dedicado</li>
                </ul>
                <a href="/<?= $locale ?>/contato" class="block w-full py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition">Contratar</a>
            </div>
        </div>
    </div>
</section>

<!-- VPS, Backup, E-mail -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center mb-12">Outros Serviços de Infraestrutura</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <span class="text-3xl mb-4 block">🖥️</span>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">VPS</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Servidores virtuais com recursos dedicados, ideal para aplicações que exigem performance.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <span class="text-3xl mb-4 block">💾</span>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Backup em Nuvem</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Proteção total dos seus dados com backups automáticos e restauração sob demanda.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <span class="text-3xl mb-4 block">📧</span>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">E-mail Profissional</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">E-mail com seu domínio, antispam avançado e acesso por qualquer dispositivo.</p>
            </div>
        </div>
    </div>
</section>
