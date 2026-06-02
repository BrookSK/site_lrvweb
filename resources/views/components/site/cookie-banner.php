<!-- Cookie Banner (LGPD) -->
<div id="cookie-banner" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-2xl border-t border-gray-200 dark:border-gray-700 p-4 z-50 hidden">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            <?= \Core\I18n::get('cookie_message') ?>
        </p>
        <div class="flex gap-3 flex-shrink-0">
            <button onclick="rejectCookies()" class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <?= \Core\I18n::get('cookie_reject') ?>
            </button>
            <button onclick="acceptCookies()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <?= \Core\I18n::get('cookie_accept') ?>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    if (!localStorage.getItem('cookie_consent')) {
        document.getElementById('cookie-banner').classList.remove('hidden');
    }
})();

function acceptCookies() {
    localStorage.setItem('cookie_consent', 'accepted');
    document.getElementById('cookie-banner').classList.add('hidden');
}

function rejectCookies() {
    localStorage.setItem('cookie_consent', 'rejected');
    document.getElementById('cookie-banner').classList.add('hidden');
}
</script>
