<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">LRV Web</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Acesse sua conta</p>
    </div>

    <!-- Flash Messages -->
    <?php if ($error = \Core\Session::getInstance()->getFlash('error')): ?>
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success = \Core\Session::getInstance()->getFlash('success')): ?>
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="/login" method="POST" class="space-y-5">
        <?= \Core\View::csrf() ?>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                autofocus
                autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition"
                placeholder="seu@email.com"
            >
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Senha</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition"
                placeholder="••••••••"
            >
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Lembrar-me</span>
            </label>
            <a href="/recuperar-senha" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Esqueceu a senha?</a>
        </div>

        <!-- Submit -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition duration-200 focus:ring-4 focus:ring-blue-300"
        >
            Entrar
        </button>
    </form>
</div>

<!-- Footer -->
<p class="text-center mt-6 text-white/60 text-sm">
    &copy; <?= date('Y') ?> LRV Web. Todos os direitos reservados.
</p>
