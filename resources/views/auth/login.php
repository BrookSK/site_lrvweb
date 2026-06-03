<?php
$logoMain = \Core\Config::setting('branding.logo_main') ?: \Core\Config::setting('branding.logo_system');
$favicon = \Core\Config::setting('branding.favicon');
?>

<!-- Botão voltar ao site -->
<div class="mb-6 text-center">
    <a href="/" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Voltar ao site
    </a>
</div>

<div class="bg-gray-900/80 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <?php if ($logoMain): ?>
            <img src="<?= htmlspecialchars($logoMain) ?>" alt="LRV Web" class="h-12 mx-auto mb-4 object-contain brightness-0 invert">
        <?php elseif ($favicon): ?>
            <img src="<?= htmlspecialchars($favicon) ?>" alt="LRV Web" class="h-12 w-12 mx-auto mb-4 object-contain">
        <?php else: ?>
            <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/20">
                <span class="text-white font-bold text-2xl">L</span>
            </div>
        <?php endif; ?>
        <p class="text-gray-400 text-sm mt-1">Acesse sua conta</p>
    </div>

    <!-- Flash Messages -->
    <?php if ($error = \Core\Session::getInstance()->getFlash('error')): ?>
        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <?php if ($success = \Core\Session::getInstance()->getFlash('success')): ?>
        <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form action="/login" method="POST" class="space-y-5">
        <?= \Core\View::csrf() ?>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">E-mail</label>
            <input type="email" id="email" name="email" required autofocus autocomplete="email"
                   class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition"
                   placeholder="seu@email.com">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Senha</label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-purple-600 focus:ring-purple-500 focus:ring-offset-0">
                <span class="text-sm text-gray-400">Lembrar-me</span>
            </label>
            <a href="/recuperar-senha" class="text-sm text-purple-400 hover:text-purple-300 transition">Esqueceu a senha?</a>
        </div>

        <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white font-semibold rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-300 hover:-translate-y-0.5">
            Entrar
        </button>
    </form>
</div>

<p class="text-center mt-6 text-gray-500 text-xs">&copy; <?= date('Y') ?> LRV Web. Todos os direitos reservados.</p>
