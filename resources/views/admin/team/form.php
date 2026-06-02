<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="<?= $member ? '/admin/equipe/' . $member['id'] : '/admin/equipe' ?>" method="POST" class="space-y-5">
            <?= \Core\View::csrf() ?>
            <?php if ($member): ?><?= \Core\View::method('PUT') ?><?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($member['name'] ?? '') ?>" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail *</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($member['email'] ?? '') ?>" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($member['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cargo</label>
                    <select name="position" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">Selecione...</option>
                        <?php
                        $positions = ['CEO', 'CTO', 'Diretor', 'Gerente', 'Desenvolvedor', 'Designer', 'Suporte Técnico', 'Comercial', 'Financeiro', 'Marketing', 'Estagiário', 'Freelancer'];
                        foreach ($positions as $pos):
                        ?>
                        <option value="<?= $pos ?>" <?= ($member['position'] ?? '') === $pos ? 'selected' : '' ?>><?= $pos ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Perfil de Acesso *</label>
                    <select name="role_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">Selecione...</option>
                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= ($member['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['display_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= $member ? 'Nova Senha (deixe vazio para manter)' : 'Senha *' ?></label>
                    <input type="password" name="password" <?= !$member ? 'required' : '' ?> minlength="8" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm" placeholder="Mínimo 8 caracteres">
                </div>
            </div>

            <?php if ($member): ?>
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" <?= ($member['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-purple-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Membro ativo</span>
                </label>
            </div>
            <?php endif; ?>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar</button>
                <a href="/admin/equipe" class="px-6 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
