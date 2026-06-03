<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
    <form action="<?= $client ? '/admin/clientes/' . $client['id'] : '/admin/clientes' ?>" method="POST" class="space-y-6">
        <?= \Core\View::csrf() ?>
        <?php if ($client): ?><?= \Core\View::method('PUT') ?><?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label><input type="text" name="name" value="<?= htmlspecialchars($client['name'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Empresa</label><input type="text" name="company" value="<?= htmlspecialchars($client['company'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail *</label><input type="email" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label><input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label><input type="text" name="whatsapp" value="<?= htmlspecialchars($client['whatsapp'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF/CNPJ</label><input type="text" name="cpf_cnpj" value="<?= htmlspecialchars($client['cpf_cnpj'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label><input type="url" name="website" value="<?= htmlspecialchars($client['website'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instagram</label><input type="text" name="social_instagram" value="<?= htmlspecialchars($client['social_instagram'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></div>
        </div>

        <!-- Acesso ao sistema -->
        <div class="mt-6 p-4 bg-purple-50 dark:bg-purple-900/10 rounded-xl border border-purple-200 dark:border-purple-800">
            <h4 class="text-sm font-semibold text-purple-800 dark:text-purple-300 mb-3">🔐 Acesso à Área do Cliente</h4>
            <p class="text-xs text-purple-600 dark:text-purple-400 mb-3">Defina uma senha para o cliente acessar a área exclusiva dele no sistema.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= $client ? 'Nova Senha (deixe vazio para manter)' : 'Senha de Acesso *' ?></label>
                    <input type="password" name="client_password" <?= !$client ? 'required' : '' ?> minlength="6" placeholder="Mínimo 6 caracteres" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex items-end">
                    <p class="text-xs text-gray-500 dark:text-gray-400">O cliente vai usar o <strong>e-mail</strong> cadastrado acima + esta senha para logar em <strong>/login</strong></p>
                </div>
            </div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label><textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"><?= htmlspecialchars($client['notes'] ?? '') ?></textarea></div>
        <div class="flex gap-3"><button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">Salvar</button><a href="/admin/clientes" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50">Cancelar</a></div>
    </form>
</div>
