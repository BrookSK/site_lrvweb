<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">LRV Web</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Redefinir senha</p>
    </div>

    <?php if ($error = \Core\Session::getInstance()->getFlash('error')): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/redefinir-senha" method="POST" class="space-y-5">
        <?= \Core\View::csrf() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nova Senha</label>
            <input type="password" name="password" required minlength="8" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Senha</label>
            <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            Redefinir Senha
        </button>
    </form>
</div>
