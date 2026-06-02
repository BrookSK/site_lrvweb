<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">LRV Web</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Recuperar senha</p>
    </div>

    <?php if ($success = \Core\Session::getInstance()->getFlash('success')): ?>
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="/recuperar-senha" method="POST" class="space-y-5">
        <?= \Core\View::csrf() ?>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail</label>
            <input type="email" id="email" name="email" required autofocus class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="seu@email.com">
        </div>
        <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            Enviar link de recuperação
        </button>
    </form>

    <p class="text-center mt-6 text-sm text-gray-500">
        <a href="/login" class="text-blue-600 hover:underline">Voltar ao login</a>
    </p>
</div>
