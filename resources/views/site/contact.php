<?php $locale = \Core\I18n::getLocale(); ?>

<section class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= \Core\I18n::get('contact_title') ?></h1>
        <p class="text-blue-100 text-lg"><?= \Core\I18n::get('contact_subtitle') ?></p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if ($success = \Core\Session::getInstance()->getFlash('success')): ?>
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="/<?= $locale ?>/contato" method="POST" class="space-y-6">
            <?= \Core\View::csrf() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= \Core\I18n::get('your_name') ?> *</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= \Core\I18n::get('your_email') ?> *</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= \Core\I18n::get('your_phone') ?></label>
                    <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= \Core\I18n::get('subject') ?> *</label>
                    <input type="text" name="subject" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= \Core\I18n::get('message') ?> *</label>
                <textarea name="message" rows="6" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
                <?= \Core\I18n::get('send_message') ?>
            </button>
        </form>
    </div>
</section>
