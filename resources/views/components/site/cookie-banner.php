<!-- Cookie Banner (LGPD) -->
<div id="cookie-banner" class="fixed bottom-6 left-6 right-6 md:left-auto md:right-6 md:max-w-md z-50 hidden">
    <div class="glass bg-black/80 backdrop-blur-xl rounded-2xl p-6 border border-white/10 shadow-2xl">
        <p class="text-sm text-gray-300 mb-4">
            🍪 Utilizamos cookies para melhorar sua experiência. Ao continuar, você concorda com nossa
            <a href="/<?= \Core\I18n::getLocale() ?>/pagina/politica-de-cookies" class="text-purple-400 hover:underline">Política de Cookies</a>.
        </p>
        <div class="flex gap-3">
            <button onclick="acceptCookies()" class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-xl transition">Aceitar</button>
            <button onclick="rejectCookies()" class="px-4 py-2.5 border border-white/10 text-gray-300 text-sm rounded-xl hover:bg-white/5 transition">Recusar</button>
        </div>
    </div>
</div>

<script>
(function() {
    if (!localStorage.getItem('cookie_consent')) {
        setTimeout(() => document.getElementById('cookie-banner').classList.remove('hidden'), 2000);
    }
})();
function acceptCookies() { localStorage.setItem('cookie_consent', 'accepted'); document.getElementById('cookie-banner').classList.add('hidden'); }
function rejectCookies() { localStorage.setItem('cookie_consent', 'rejected'); document.getElementById('cookie-banner').classList.add('hidden'); }
</script>
