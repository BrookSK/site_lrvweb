<?php $locale = \Core\I18n::getLocale(); ?>

<article class="section-dark py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back -->
        <a href="/<?= $locale ?>/blog" class="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 text-sm mb-8 transition group" data-animate="fade-up">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar ao Blog
        </a>

        <!-- Meta -->
        <div class="mb-8" data-animate="fade-up" data-delay="100">
            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                <time><?= date('d M Y', strtotime($post['published_at'])) ?></time>
                <span class="w-1 h-1 bg-purple-500 rounded-full"></span>
                <span><?= htmlspecialchars($post['author_name'] ?? 'LRV Web') ?></span>
                <?php if ($post['category_name']): ?>
                    <span class="w-1 h-1 bg-purple-500 rounded-full"></span>
                    <span class="text-purple-400"><?= htmlspecialchars($post['category_name']) ?></span>
                <?php endif; ?>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight"><?= htmlspecialchars($post['title']) ?></h1>
        </div>

        <!-- Image -->
        <?php if ($post['image']): ?>
            <div class="rounded-2xl overflow-hidden border border-white/10 mb-10" data-animate="scale">
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full">
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="prose prose-invert prose-purple max-w-none prose-headings:text-white prose-p:text-gray-300 prose-a:text-purple-400 prose-strong:text-white prose-li:text-gray-300" data-animate="fade-up" data-delay="200">
            <?= $post['content'] ?>
        </div>

        <!-- Tags -->
        <?php if ($post['tags']): ?>
        <div class="mt-12 pt-8 border-t border-white/5">
            <div class="flex flex-wrap gap-2">
                <?php foreach (explode(',', $post['tags']) as $tag): ?>
                    <a href="/<?= $locale ?>/blog/tag/<?= urlencode(trim($tag)) ?>" class="px-4 py-1.5 bg-purple-600/10 border border-purple-500/20 rounded-full text-xs text-purple-300 hover:bg-purple-600/20 hover:border-purple-500/40 transition"><?= htmlspecialchars(trim($tag)) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Relacionados -->
        <?php if (!empty($related)): ?>
        <div class="mt-16 pt-12 border-t border-white/5">
            <h3 class="text-xl font-bold text-white mb-8">Leia também</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($related as $r): ?>
                <a href="/<?= $locale ?>/blog/<?= $r['slug'] ?>" class="group card-premium p-4">
                    <?php if ($r['image']): ?>
                        <div class="aspect-video rounded-lg overflow-hidden mb-3"><img src="<?= htmlspecialchars($r['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"></div>
                    <?php endif; ?>
                    <h4 class="text-sm font-medium text-white group-hover:text-purple-300 transition line-clamp-2"><?= htmlspecialchars($r['title']) ?></h4>
                    <p class="text-xs text-gray-500 mt-2"><?= date('d M Y', strtotime($r['published_at'])) ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</article>
