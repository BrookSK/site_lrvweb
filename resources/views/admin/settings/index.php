<?php
$tabs = [
    'general' => ['icon' => 'settings', 'label' => 'Geral'],
    'database' => ['icon' => 'database', 'label' => 'Banco de Dados'],
    'mail' => ['icon' => 'mail', 'label' => 'E-mail (SMTP)'],
    'branding' => ['icon' => 'palette', 'label' => 'Identidade Visual'],
    'seo' => ['icon' => 'search', 'label' => 'SEO'],
    'social' => ['icon' => 'share-2', 'label' => 'Redes Sociais'],
    'site' => ['icon' => 'globe', 'label' => 'Dados do Site'],
    'security' => ['icon' => 'shield', 'label' => 'Segurança'],
    'openai' => ['icon' => 'bot', 'label' => 'Blog IA (OpenAI)'],
    'backup' => ['icon' => 'hard-drive', 'label' => 'Backup'],
    'budget' => ['icon' => 'file-text', 'label' => 'Orçamentos'],
];
$fc = $fileConfig;
?>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Tabs Sidebar -->
    <div class="lg:w-64 flex-shrink-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-2">
            <?php foreach ($tabs as $key => $tabInfo): ?>
                <a href="/admin/configuracoes?tab=<?= $key ?>"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $tab === $key ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                    <i data-lucide="<?= $tabInfo['icon'] ?>" class="w-4 h-4"></i>
                    <?= $tabInfo['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">

            <form action="/admin/configuracoes" method="POST" enctype="multipart/form-data">
                <?= \Core\View::csrf() ?>
                <?= \Core\View::method('PUT') ?>
                <input type="hidden" name="tab" value="<?= $tab ?>">

                <?php if ($tab === 'general'): ?>
                <!-- === ABA: GERAL === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Configurações Gerais</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Aplicação</label>
                        <input type="text" name="app_name" value="<?= htmlspecialchars($fc['app']['name'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL do Site</label>
                        <input type="url" name="app_url" value="<?= htmlspecialchars($fc['app']['url'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="https://lrvweb.com.br">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ambiente</label>
                        <select name="app_env" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="development" <?= ($fc['app']['env'] ?? '') === 'development' ? 'selected' : '' ?>>Development</option>
                            <option value="staging" <?= ($fc['app']['env'] ?? '') === 'staging' ? 'selected' : '' ?>>Staging</option>
                            <option value="production" <?= ($fc['app']['env'] ?? '') === 'production' ? 'selected' : '' ?>>Production</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone</label>
                        <input type="text" name="app_timezone" value="<?= htmlspecialchars($fc['app']['timezone'] ?? 'America/Sao_Paulo') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Idioma Padrão</label>
                        <select name="app_locale" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="pt" <?= ($fc['app']['locale'] ?? 'pt') === 'pt' ? 'selected' : '' ?>>Português</option>
                            <option value="en" <?= ($fc['app']['locale'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="es" <?= ($fc['app']['locale'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Idiomas Ativos (separados por vírgula)</label>
                        <input type="text" name="app_locales" value="<?= htmlspecialchars(implode(',', $fc['app']['available_locales'] ?? ['pt','en','es'])) ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="app_debug" value="1" <?= ($fc['app']['debug'] ?? false) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Modo Debug (ativar apenas em desenvolvimento)</span>
                        </label>
                    </div>
                </div>

                <?php elseif ($tab === 'database'): ?>
                <!-- === ABA: BANCO DE DADOS === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Banco de Dados</h3>
                <p class="text-sm text-red-500 mb-6">⚠️ Cuidado: alterações incorretas podem tornar o sistema inacessível.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Host</label>
                        <input type="text" name="db_host" value="<?= htmlspecialchars($fc['database']['host'] ?? 'localhost') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Porta</label>
                        <input type="number" name="db_port" value="<?= $fc['database']['port'] ?? 3306 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Database</label>
                        <input type="text" name="db_database" value="<?= htmlspecialchars($fc['database']['database'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuário</label>
                        <input type="text" name="db_username" value="<?= htmlspecialchars($fc['database']['username'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Senha</label>
                        <input type="password" name="db_password" value="<?= htmlspecialchars($fc['database']['password'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <?php elseif ($tab === 'mail'): ?>
                <!-- === ABA: E-MAIL === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Configurações de E-mail (SMTP)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Servidor SMTP</label>
                        <input type="text" name="mail_host" value="<?= htmlspecialchars($fc['mail']['host'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Porta</label>
                        <input type="number" name="mail_port" value="<?= $fc['mail']['port'] ?? 587 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuário</label>
                        <input type="text" name="mail_username" value="<?= htmlspecialchars($fc['mail']['username'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Senha</label>
                        <input type="password" name="mail_password" value="<?= htmlspecialchars($fc['mail']['password'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Criptografia</label>
                        <select name="mail_encryption" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="tls" <?= ($fc['mail']['encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                            <option value="ssl" <?= ($fc['mail']['encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="" <?= empty($fc['mail']['encryption'] ?? '') ? 'selected' : '' ?>>Nenhuma</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail Remetente</label>
                        <input type="email" name="mail_from_address" value="<?= htmlspecialchars($fc['mail']['from_address'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Remetente</label>
                        <input type="text" name="mail_from_name" value="<?= htmlspecialchars($fc['mail']['from_name'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <?php elseif ($tab === 'branding'): ?>
                <!-- === ABA: IDENTIDADE VISUAL === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Identidade Visual</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php
                    $logos = [
                        'logo_main' => 'Logo Principal',
                        'logo_system' => 'Logo do Sistema (Admin)',
                        'logo_budget' => 'Logo dos Orçamentos',
                        'favicon' => 'Favicon',
                    ];
                    foreach ($logos as $key => $label):
                        $current = $settings['branding'][$key]['value'] ?? '';
                    ?>
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?= $label ?></label>
                        <?php if ($current): ?>
                            <img src="<?= htmlspecialchars($current) ?>" alt="<?= $label ?>" class="h-12 mb-3 object-contain">
                        <?php else: ?>
                            <div class="h-12 mb-3 flex items-center text-sm text-gray-400">Nenhum arquivo</div>
                        <?php endif; ?>
                        <div class="flex gap-2">
                            <input type="file" name="file" accept="image/*" class="text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" form="upload-<?= $key ?>">
                        </div>
                        <form id="upload-<?= $key ?>" action="/admin/configuracoes/logo" method="POST" enctype="multipart/form-data" class="mt-2">
                            <?= \Core\View::csrf() ?>
                            <input type="hidden" name="type" value="<?= $key ?>">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enviar</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($tab === 'seo'): ?>
                <!-- === ABA: SEO === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">SEO - Otimização para Buscadores</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title (padrão)</label>
                        <input type="text" name="meta_title" value="<?= htmlspecialchars($settings['seo']['meta_title']['value'] ?? '') ?>" maxlength="70" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <p class="text-xs text-gray-400 mt-1">Máximo 70 caracteres</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"><?= htmlspecialchars($settings['seo']['meta_description']['value'] ?? '') ?></textarea>
                        <p class="text-xs text-gray-400 mt-1">Máximo 160 caracteres</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Palavras-chave</label>
                        <input type="text" name="meta_keywords" value="<?= htmlspecialchars($settings['seo']['meta_keywords']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="hospedagem, sites, sistemas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Analytics ID</label>
                        <input type="text" name="google_analytics" value="<?= htmlspecialchars($settings['seo']['google_analytics']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="G-XXXXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Tag Manager</label>
                        <input type="text" name="google_tag_manager" value="<?= htmlspecialchars($settings['seo']['google_tag_manager']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="GTM-XXXXXXX">
                    </div>
                </div>

                <?php elseif ($tab === 'social'): ?>
                <!-- === ABA: REDES SOCIAIS === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Redes Sociais</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $socials = ['instagram', 'facebook', 'linkedin', 'youtube', 'github', 'twitter', 'tiktok'];
                    foreach ($socials as $social): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 capitalize"><?= $social ?></label>
                        <input type="url" name="<?= $social ?>" value="<?= htmlspecialchars($settings['social'][$social]['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="https://<?= $social ?>.com/lrvweb">
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($tab === 'site'): ?>
                <!-- === ABA: DADOS DO SITE === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Dados de Contato do Site</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail de Contato</label>
                        <input type="email" name="site_email" value="<?= htmlspecialchars($settings['general']['site_email']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                        <input type="text" name="site_phone" value="<?= htmlspecialchars($settings['general']['site_phone']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="(11) 9999-9999">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label>
                        <input type="text" name="site_whatsapp" value="<?= htmlspecialchars($settings['general']['site_whatsapp']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="5511999999999">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Texto do Footer</label>
                        <input type="text" name="footer_text" value="<?= htmlspecialchars($settings['general']['footer_text']['value'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço</label>
                        <textarea name="site_address" rows="2" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"><?= htmlspecialchars($settings['general']['site_address']['value'] ?? '') ?></textarea>
                    </div>
                </div>

                <?php elseif ($tab === 'security'): ?>
                <!-- === ABA: SEGURANÇA === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Segurança</h3>

                <div class="space-y-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="csrf_enabled" value="1" <?= ($fc['security']['csrf_enabled'] ?? true) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Proteção CSRF ativada</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Limite de requisições por IP</label>
                            <input type="number" name="rate_limit_requests" value="<?= $fc['security']['rate_limit_requests'] ?? 60 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Janela de tempo (segundos)</label>
                            <input type="number" name="rate_limit_window" value="<?= $fc['security']['rate_limit_window'] ?? 60 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempo de sessão (minutos)</label>
                            <input type="number" name="session_lifetime" value="<?= $fc['session']['lifetime'] ?? 120 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="session_secure" value="1" <?= ($fc['session']['secure'] ?? false) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Cookie seguro (HTTPS only)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <?php elseif ($tab === 'openai'): ?>
                <!-- === ABA: OPENAI === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Blog com Inteligência Artificial</h3>

                <div class="space-y-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="openai_enabled" value="1" <?= ($fc['openai']['blog_enabled'] ?? false) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Ativar geração automática de posts</span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API Key (OpenAI)</label>
                        <input type="password" name="openai_api_key" value="<?= htmlspecialchars($fc['openai']['api_key'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="sk-...">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modelo</label>
                            <select name="openai_model" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="gpt-4" <?= ($fc['openai']['model'] ?? '') === 'gpt-4' ? 'selected' : '' ?>>GPT-4</option>
                                <option value="gpt-4o" <?= ($fc['openai']['model'] ?? '') === 'gpt-4o' ? 'selected' : '' ?>>GPT-4o</option>
                                <option value="gpt-3.5-turbo" <?= ($fc['openai']['model'] ?? '') === 'gpt-3.5-turbo' ? 'selected' : '' ?>>GPT-3.5 Turbo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequência</label>
                            <select name="openai_frequency" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="daily" <?= ($fc['openai']['blog_frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Diária</option>
                                <option value="weekly" <?= ($fc['openai']['blog_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Semanal</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Idiomas (separados por vírgula)</label>
                        <input type="text" name="openai_languages" value="<?= htmlspecialchars(implode(',', $fc['openai']['blog_languages'] ?? ['pt','en','es'])) ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="pt,en,es">
                    </div>
                </div>

                <?php elseif ($tab === 'backup'): ?>
                <!-- === ABA: BACKUP === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Configurações de Backup</h3>

                <div class="space-y-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="backup_enabled" value="1" <?= ($fc['backup']['enabled'] ?? true) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Backup automático ativado</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequência</label>
                            <select name="backup_frequency" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="daily" <?= ($fc['backup']['frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Diário</option>
                                <option value="weekly" <?= ($fc['backup']['frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Semanal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Retenção (dias)</label>
                            <input type="number" name="backup_retention" value="<?= $fc['backup']['retention_days'] ?? 30 ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <?php elseif ($tab === 'budget'): ?>
                <!-- === ABA: ORÇAMENTOS === -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Configurações de Orçamentos</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Validade padrão (dias)</label>
                        <input type="number" name="default_validity_days" value="<?= htmlspecialchars($settings['budget']['default_validity_days']['value'] ?? '15') ?>" class="w-full max-w-xs px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sobre a Empresa (exibido nos orçamentos)</label>
                        <textarea name="about_company" rows="5" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"><?= htmlspecialchars($settings['budget']['about_company']['value'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Termos e Condições</label>
                        <textarea name="terms" rows="5" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"><?= htmlspecialchars($settings['budget']['terms']['value'] ?? '') ?></textarea>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Submit (exceto na aba branding que tem forms separados) -->
                <?php if ($tab !== 'branding'): ?>
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                        Salvar Configurações
                    </button>
                </div>
                <?php endif; ?>

            </form>
        </div>
    </div>
</div>
