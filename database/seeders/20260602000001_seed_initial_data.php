<?php

/**
 * Seeder: Dados iniciais do sistema
 * 
 * Popula roles, permissões, idiomas, configurações e usuário admin.
 */

declare(strict_types=1);

class Seeder_20260602000001_seed_initial_data
{
    public function run(PDO $pdo): void
    {
        // === ROLES ===
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrador', 'description' => 'Acesso total ao sistema'],
            ['name' => 'socio', 'display_name' => 'Sócio', 'description' => 'Sócio da empresa com acesso amplo'],
            ['name' => 'desenvolvedor', 'display_name' => 'Desenvolvedor', 'description' => 'Equipe de desenvolvimento'],
            ['name' => 'suporte', 'display_name' => 'Suporte', 'description' => 'Equipe de suporte técnico'],
            ['name' => 'comercial', 'display_name' => 'Comercial', 'description' => 'Equipe comercial e vendas'],
            ['name' => 'financeiro', 'display_name' => 'Financeiro', 'description' => 'Gestão financeira'],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'description' => 'Acesso à área do cliente'],
        ];

        $stmt = $pdo->prepare("INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)");
        foreach ($roles as $role) {
            $stmt->execute([$role['name'], $role['display_name'], $role['description']]);
        }

        // === PERMISSÕES ===
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'Visualizar Dashboard', 'module' => 'dashboard'],

            // Clientes
            ['name' => 'clients.view', 'display_name' => 'Visualizar Clientes', 'module' => 'clients'],
            ['name' => 'clients.create', 'display_name' => 'Criar Clientes', 'module' => 'clients'],
            ['name' => 'clients.edit', 'display_name' => 'Editar Clientes', 'module' => 'clients'],
            ['name' => 'clients.delete', 'display_name' => 'Excluir Clientes', 'module' => 'clients'],

            // CRM
            ['name' => 'crm.view', 'display_name' => 'Visualizar CRM', 'module' => 'crm'],
            ['name' => 'crm.manage', 'display_name' => 'Gerenciar Leads', 'module' => 'crm'],

            // Projetos
            ['name' => 'projects.view', 'display_name' => 'Visualizar Projetos', 'module' => 'projects'],
            ['name' => 'projects.create', 'display_name' => 'Criar Projetos', 'module' => 'projects'],
            ['name' => 'projects.edit', 'display_name' => 'Editar Projetos', 'module' => 'projects'],
            ['name' => 'projects.delete', 'display_name' => 'Excluir Projetos', 'module' => 'projects'],

            // Orçamentos
            ['name' => 'budgets.view', 'display_name' => 'Visualizar Orçamentos', 'module' => 'budgets'],
            ['name' => 'budgets.create', 'display_name' => 'Criar Orçamentos', 'module' => 'budgets'],
            ['name' => 'budgets.edit', 'display_name' => 'Editar Orçamentos', 'module' => 'budgets'],
            ['name' => 'budgets.delete', 'display_name' => 'Excluir Orçamentos', 'module' => 'budgets'],

            // Financeiro
            ['name' => 'financial.view', 'display_name' => 'Visualizar Financeiro', 'module' => 'financial'],
            ['name' => 'financial.manage', 'display_name' => 'Gerenciar Financeiro', 'module' => 'financial'],

            // Documentos
            ['name' => 'documents.view', 'display_name' => 'Visualizar Documentos', 'module' => 'documents'],
            ['name' => 'documents.manage', 'display_name' => 'Gerenciar Documentos', 'module' => 'documents'],

            // Equipe
            ['name' => 'team.view', 'display_name' => 'Visualizar Equipe', 'module' => 'team'],
            ['name' => 'team.manage', 'display_name' => 'Gerenciar Equipe', 'module' => 'team'],

            // Blog
            ['name' => 'blog.view', 'display_name' => 'Visualizar Blog', 'module' => 'blog'],
            ['name' => 'blog.manage', 'display_name' => 'Gerenciar Blog', 'module' => 'blog'],

            // Portfólio
            ['name' => 'portfolio.view', 'display_name' => 'Visualizar Portfólio', 'module' => 'portfolio'],
            ['name' => 'portfolio.manage', 'display_name' => 'Gerenciar Portfólio', 'module' => 'portfolio'],

            // Configurações
            ['name' => 'settings.view', 'display_name' => 'Visualizar Configurações', 'module' => 'settings'],
            ['name' => 'settings.manage', 'display_name' => 'Gerenciar Configurações', 'module' => 'settings'],

            // Sistema
            ['name' => 'versions.view', 'display_name' => 'Visualizar Versões', 'module' => 'system'],
            ['name' => 'backups.manage', 'display_name' => 'Gerenciar Backups', 'module' => 'system'],
            ['name' => 'logs.view', 'display_name' => 'Visualizar Logs', 'module' => 'system'],
        ];

        $stmt = $pdo->prepare("INSERT INTO permissions (name, display_name, module) VALUES (?, ?, ?)");
        foreach ($permissions as $perm) {
            $stmt->execute([$perm['name'], $perm['display_name'], $perm['module']]);
        }

        // Atribui TODAS as permissões ao Super Admin (role_id = 1)
        $pdo->exec("INSERT INTO role_permissions (role_id, permission_id) SELECT 1, id FROM permissions");

        // === IDIOMAS ===
        $pdo->exec("INSERT INTO languages (code, name, native_name, is_active, is_default, sort_order) VALUES
            ('pt', 'Português', 'Português (Brasil)', 1, 1, 1),
            ('en', 'English', 'English', 1, 0, 2),
            ('es', 'Español', 'Español', 1, 0, 3)
        ");

        // === CONFIGURAÇÕES INICIAIS ===
        $settings = [
            ['general', 'site_name', 'LRV Web', 'text'],
            ['general', 'site_description', 'Soluções Digitais - Hospedagem, Sites e Sistemas', 'text'],
            ['general', 'site_email', 'contato@lrvweb.com.br', 'text'],
            ['general', 'site_phone', '', 'text'],
            ['general', 'site_whatsapp', '', 'text'],
            ['general', 'site_address', '', 'textarea'],
            ['branding', 'logo_main', '', 'image'],
            ['branding', 'logo_system', '', 'image'],
            ['branding', 'logo_budget', '', 'image'],
            ['branding', 'favicon', '', 'image'],
            ['social', 'instagram', '', 'text'],
            ['social', 'facebook', '', 'text'],
            ['social', 'linkedin', '', 'text'],
            ['social', 'youtube', '', 'text'],
            ['social', 'github', '', 'text'],
            ['seo', 'meta_title', 'LRV Web - Soluções Digitais', 'text'],
            ['seo', 'meta_description', 'Hospedagem de sites, desenvolvimento web, sistemas sob medida e soluções digitais.', 'text'],
            ['seo', 'meta_keywords', 'hospedagem, sites, sistemas, desenvolvimento web, lrv web', 'text'],
            ['seo', 'google_analytics', '', 'text'],
            ['seo', 'google_tag_manager', '', 'text'],
            ['budget', 'about_company', 'A LRV Web é uma empresa especializada em soluções digitais, oferecendo serviços de hospedagem, desenvolvimento de sites, sistemas sob medida e muito mais.', 'textarea'],
            ['budget', 'default_validity_days', '15', 'number'],
            ['ai', 'blog_enabled', '0', 'boolean'],
            ['ai', 'blog_frequency', 'weekly', 'text'],
            ['ai', 'blog_languages', 'pt,en,es', 'text'],
        ];

        $stmt = $pdo->prepare("INSERT INTO settings (`group`, `key`, value, type) VALUES (?, ?, ?, ?)");
        foreach ($settings as $setting) {
            $stmt->execute($setting);
        }

        // === CATEGORIAS PORTFÓLIO ===
        $categories = [
            ['Site', 'site'],
            ['Sistema', 'sistema'],
            ['E-commerce', 'e-commerce'],
            ['Hospedagem', 'hospedagem'],
            ['Social Media', 'social-media'],
            ['Automação', 'automacao'],
        ];

        $stmt = $pdo->prepare("INSERT INTO portfolio_categories (name, slug, sort_order) VALUES (?, ?, ?)");
        foreach ($categories as $i => $cat) {
            $stmt->execute([$cat[0], $cat[1], $i + 1]);
        }

        // === USUÁRIO ADMIN PADRÃO ===
        $adminPassword = password_hash('admin@lrvweb2026', PASSWORD_ARGON2ID);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id, is_active) VALUES (?, ?, ?, 1, 1)");
        $stmt->execute(['Administrador', 'admin@lrvweb.com.br', $adminPassword]);

        // === PÁGINAS INICIAIS ===
        $pages = [
            ['politica-de-privacidade', 'Política de Privacidade', ''],
            ['termos-de-uso', 'Termos de Uso', ''],
            ['politica-de-cookies', 'Política de Cookies', ''],
        ];

        $stmt = $pdo->prepare("INSERT INTO pages (slug, title, content) VALUES (?, ?, ?)");
        foreach ($pages as $page) {
            $stmt->execute($page);
        }
    }
}
