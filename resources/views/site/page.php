<?php $locale = \Core\I18n::getLocale(); ?>

<section class="hero-gradient py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold animate-fade-up"><?= htmlspecialchars($page['title']) ?></h1>
    </div>
</section>

<section class="section-dark py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium">
            <div class="prose prose-invert prose-purple max-w-none prose-headings:text-white prose-p:text-gray-300 prose-a:text-purple-400 prose-li:text-gray-300">
                <?= $page['content'] ?>
            </div>
        </div>
    </div>
</section>
