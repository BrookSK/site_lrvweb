<?php /** @var array $services */ ?>
<?php /** @var array $portfolio */ ?>
<?php /** @var array $posts */ ?>

<!-- Hero Section -->
<section class="hero-gradient py-20 md:py-32 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6" data-animate>
            <?= \Core\I18n::get('hero_title') ?>
        </h1>
        <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto" data-animate>
            <?= \Core\I18n::get('hero_subtitle') ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate>
            <a href="/<?= \Core\I18n::getLocale() ?>/contato" class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition">
                <?= \Core\I18n::get('hero_cta') ?>
            </a>
            <a href="/<?= \Core\I18n::getLocale() ?>/hospedagem" class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition">
                <?= \Core\I18n::get('hosting_plans') ?>
            </a>
        </div>
    </div>
</section>

<!-- Serviços -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white" data-animate>
                <?= \Core\I18n::get('our_services') ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="card-hover bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl"><?= $service['icon'] ?? '🌐' ?></span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                    <?= htmlspecialchars($service['name']) ?>
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    <?= htmlspecialchars($service['short_description'] ?? '') ?>
                </p>
                <a href="/<?= \Core\I18n::getLocale() ?>/servicos/<?= $service['slug'] ?>" class="inline-block mt-4 text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">
                    <?= \Core\I18n::get('read_more') ?> →
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Hospedagem Destaque -->
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white" data-animate>
                <?= \Core\I18n::get('hosting_plans') ?>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-3">Hospedagem rápida, segura e com suporte técnico especializado.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Plano Básico -->
            <div class="card-hover bg-white dark:bg-gray-900 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 text-center" data-animate>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Básico</h3>
                <div class="my-4">
                    <span class="text-4xl font-bold text-blue-600">R$ 29</span>
                    <span class="text-gray-500">/mês</span>
                </div>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                    <li>✓ 10GB SSD</li>
                    <li>✓ 1 Site</li>
                    <li>✓ SSL Grátis</li>
                    <li>✓ Backup Semanal</li>
                    <li>✓ Suporte por E-mail</li>
                </ul>
                <a href="/<?= \Core\I18n::getLocale() ?>/contato" class="block w-full py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition">
                    Contratar
                </a>
            </div>

            <!-- Plano Profissional (destaque) -->
            <div class="card-hover bg-blue-600 rounded-xl p-8 shadow-xl text-center text-white relative" data-animate>
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">Popular</span>
                <h3 class="text-lg font-semibold">Profissional</h3>
                <div class="my-4">
                    <span class="text-4xl font-bold">R$ 59</span>
                    <span class="text-blue-200">/mês</span>
                </div>
                <ul class="text-sm text-blue-100 space-y-2 mb-6">
                    <li>✓ 50GB SSD</li>
                    <li>✓ Sites Ilimitados</li>
                    <li>✓ SSL Grátis</li>
                    <li>✓ Backup Diário</li>
                    <li>✓ Suporte Prioritário</li>
                    <li>✓ E-mail Profissional</li>
                </ul>
                <a href="/<?= \Core\I18n::getLocale() ?>/contato" class="block w-full py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition">
                    Contratar
                </a>
            </div>

            <!-- Plano Empresarial -->
            <div class="card-hover bg-white dark:bg-gray-900 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 text-center" data-animate>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Empresarial</h3>
                <div class="my-4">
                    <span class="text-4xl font-bold text-blue-600">R$ 129</span>
                    <span class="text-gray-500">/mês</span>
                </div>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                    <li>✓ 200GB SSD NVMe</li>
                    <li>✓ Sites Ilimitados</li>
                    <li>✓ SSL Grátis</li>
                    <li>✓ Backup em Tempo Real</li>
                    <li>✓ Suporte 24/7</li>
                    <li>✓ VPS Dedicado</li>
                </ul>
                <a href="/<?= \Core\I18n::getLocale() ?>/contato" class="block w-full py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition">
                    Contratar
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Portfólio Recente -->
<?php if (!empty($portfolio)): ?>
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white" data-animate>
                <?= \Core\I18n::get('recent_projects') ?>
            </h2>
            <a href="/<?= \Core\I18n::getLocale() ?>/portfolio" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">
                <?= \Core\I18n::get('view_all') ?> →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($portfolio as $item): ?>
            <a href="/<?= \Core\I18n::getLocale() ?>/portfolio/<?= $item['slug'] ?>" class="card-hover group block" data-animate>
                <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden mb-4">
                    <?php if ($item['image_cover']): ?>
                        <img src="<?= htmlspecialchars($item['image_cover']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                    <?php endif; ?>
                </div>
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium"><?= htmlspecialchars($item['category'] ?? '') ?></span>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mt-1"><?= htmlspecialchars($item['name']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog -->
<?php if (!empty($posts)): ?>
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white" data-animate>
                <?= \Core\I18n::get('latest_posts') ?>
            </h2>
            <a href="/<?= \Core\I18n::getLocale() ?>/blog" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">
                <?= \Core\I18n::get('view_all') ?> →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
            <article class="card-hover bg-white dark:bg-gray-900 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700" data-animate>
                <?php if ($post['image']): ?>
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover" loading="lazy">
                    </div>
                <?php endif; ?>
                <div class="p-5">
                    <time class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($post['published_at'])) ?></time>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mt-2 line-clamp-2">
                        <a href="/<?= \Core\I18n::getLocale() ?>/blog/<?= $post['slug'] ?>" class="hover:text-blue-600 transition">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">
                        <?= htmlspecialchars($post['excerpt'] ?? '') ?>
                    </p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Final -->
<section class="py-20 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center" data-animate>
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para transformar sua presença digital?</h2>
        <p class="text-blue-100 text-lg mb-8">Entre em contato e descubra como podemos ajudar seu negócio a crescer.</p>
        <a href="/<?= \Core\I18n::getLocale() ?>/contato" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition">
            <?= \Core\I18n::get('hero_cta') ?>
        </a>
    </div>
</section>
