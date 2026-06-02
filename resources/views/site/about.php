<?php $locale = \Core\I18n::getLocale(); ?>

<!-- Hero -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('about') ?></h1>
        <p class="text-blue-100 text-lg">Conheça a LRV Web e nossa missão.</p>
    </div>
</section>

<!-- História -->
<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Nossa História</h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
            A LRV Web nasceu com o propósito de oferecer soluções digitais completas para empresas que desejam crescer no ambiente online. 
            Combinamos tecnologia de ponta, design moderno e suporte dedicado para entregar resultados reais.
        </p>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            Nosso foco principal é hospedagem de sites, desenvolvimento web e sistemas sob medida, sempre com qualidade premium e atendimento humanizado.
        </p>
    </div>
</section>

<!-- Missão, Visão, Valores -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center" data-animate>
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🎯</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Missão</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Transformar negócios através de soluções digitais inovadoras, acessíveis e de alta qualidade.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center" data-animate>
                <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🔭</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Visão</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Ser referência nacional em hospedagem e desenvolvimento web, reconhecida pela excelência e inovação.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center" data-animate>
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">💎</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Valores</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Transparência, compromisso, inovação, qualidade e foco no cliente.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-blue-600 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-4">Vamos trabalhar juntos?</h2>
        <p class="text-blue-100 mb-8">Entre em contato e vamos conversar sobre o seu projeto.</p>
        <a href="/<?= $locale ?>/contato" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transition">
            Fale Conosco
        </a>
    </div>
</section>
