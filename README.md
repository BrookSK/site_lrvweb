# LRV Web Platform

Plataforma completa de gestão empresarial, CRM comercial, portal do cliente e site institucional da LRV Web.

## Sobre o Projeto

O LRV Web Platform é uma solução all-in-one desenvolvida 100% em PHP com arquitetura MVC própria. O sistema unifica:

- **Site Institucional** — Vitrine profissional com foco em hospedagem e desenvolvimento web
- **Área do Cliente** — Portal exclusivo para acompanhamento de projetos e financeiro
- **CRM Comercial** — Funil de vendas com Kanban e gestão de leads
- **Gestão de Projetos** — Kanban, tarefas e alocação de equipe
- **Sistema de Orçamentos** — Link público, blocos de serviço e cálculos automáticos
- **Gestão Financeira** — Receitas, despesas, fluxo de caixa e indicadores
- **Blog com IA** — Geração automática via OpenAI com publicação em 3 idiomas
- **Painel Administrativo** — Dashboard completo com métricas e controle total

## Tecnologias Utilizadas

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.3+, MVC próprio, PDO, MySQL |
| Frontend | HTML5, CSS3, JavaScript, Tailwind CSS |
| Dependências | Composer, Monolog, PHPMailer |
| IA | OpenAI API (GPT-4) |
| Qualidade | PHPStan, PHP CS Fixer, PSR-12 |
| Infraestrutura | Apache/Nginx, Cron Jobs, Cache em arquivo |

## Requisitos

- PHP >= 8.3
- MySQL >= 8.0
- Composer >= 2.0
- Apache com mod_rewrite ou Nginx
- Extensões PHP: `pdo`, `pdo_mysql`, `mbstring`, `json`, `openssl`, `curl`, `gd`

## Instalação

```bash
# 1. Clone o repositório
git clone git@github.com:seu-usuario/site_lrvweb.git
cd site_lrvweb

# 2. Instale as dependências
composer install

# 3. Configure o sistema
# Edite config/app.php com suas credenciais de banco e SMTP
# OU faça tudo pelo painel admin após a instalação

# 4. Crie o banco de dados
mysql -u root -p -e "CREATE DATABASE lrvweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# 5. Execute as migrations
php cli/migrate.php run

# 6. Execute os seeders (dados iniciais)
php cli/seed.php run

# 7. Configure permissões de diretórios
chmod -R 775 cache/ logs/ temp/ storage/ config/

# 8. Configure o virtual host apontando para /public
```

### Configuração

Toda a configuração do sistema é feita pelo arquivo `config/app.php` ou pelo **painel administrativo** em `Configurações`.

O painel permite alterar sem editar código:
- Dados da aplicação (nome, URL, idioma)
- Banco de dados
- E-mail SMTP
- Logos e branding
- SEO (meta tags, Analytics)
- Redes Sociais
- Segurança (CSRF, Rate Limit, Sessão)
- OpenAI (Blog com IA)
- Backup
- Orçamentos

### Credenciais Padrão

| Campo | Valor |
|-------|-------|
| Email | admin@lrvweb.com.br |
| Senha | admin@lrvweb2026 |

> ⚠️ **Altere a senha imediatamente após o primeiro login.**

## Estrutura de Pastas

```
site_lrvweb/
├── app/                    # Código da aplicação
│   ├── Controllers/        # Controllers (Admin, Site, Client, API, Auth)
│   └── Models/             # Models (User, Client, Budget, Project, etc.)
├── bootstrap/              # Inicialização da aplicação
├── cache/                  # Cache de arquivos
├── cli/                    # Scripts de linha de comando
│   ├── cron/               # Cron Jobs (Blog IA, Backup)
│   └── migrate.php         # Sistema de migrations
├── config/                 # Configuração central
│   └── app.php             # TODAS as configurações (banco, mail, IA, etc.)
├── core/                   # Framework MVC
│   ├── Application.php     # Kernel da aplicação
│   ├── Config.php          # Gerenciador de configurações
│   ├── Controller.php      # Controller base
│   ├── Database.php        # Conexão PDO Singleton
│   ├── I18n.php            # Internacionalização
│   ├── Logger.php          # Sistema de logs
│   ├── Middleware/          # Middlewares (Auth, CSRF, RateLimit)
│   ├── Model.php           # ORM simplificado
│   ├── Request.php         # Requisição HTTP
│   ├── Response.php        # Resposta HTTP
│   ├── Router.php          # Sistema de rotas
│   ├── Session.php         # Gerenciamento de sessões
│   ├── Validator.php       # Validação de dados
│   └── View.php            # Engine de templates
├── database/
│   ├── migrations/         # Migrations do banco
│   └── seeders/            # Dados iniciais
├── logs/                   # Logs da aplicação
├── public/                 # Document root (único diretório exposto)
│   ├── assets/             # CSS, JS, Imagens
│   ├── .htaccess           # Rewrite rules e segurança
│   └── index.php           # Front controller
├── resources/
│   ├── lang/               # Traduções (pt, en, es)
│   └── views/              # Templates PHP
│       ├── admin/          # Views do painel
│       ├── auth/           # Views de autenticação
│       ├── components/     # Componentes reutilizáveis
│       ├── errors/         # Páginas de erro
│       ├── layouts/        # Layouts (site, admin, auth)
│       └── site/           # Views do site público
├── routes/                 # Definição de rotas
│   ├── admin.php           # Rotas administrativas
│   ├── api.php             # API REST
│   ├── client.php          # Área do cliente
│   └── web.php             # Site público
├── storage/                # Uploads e backups
├── temp/                   # Arquivos temporários
├── tests/                  # Testes unitários
├── .gitignore              # Arquivos ignorados pelo Git
├── .php-cs-fixer.php       # Configuração PHP CS Fixer
├── CHANGELOG.md            # Histórico de versões
├── composer.json           # Dependências PHP
├── LICENSE                 # Licença proprietária
├── phpstan.neon            # Configuração PHPStan
└── README.md               # Este arquivo
```

## Deploy

### Produção

1. Clone ou faça pull no servidor
2. Execute `composer install --no-dev --optimize-autoloader`
3. Configure `config/app.php` com `'env' => 'production'` e `'debug' => false`
4. Execute migrations: `php cli/migrate.php run`
5. Configure o virtual host apontando para `/public`
6. Configure os cron jobs:

```crontab
# Blog com IA (semanal, segunda 8h)
0 8 * * 1 php /var/www/lrvweb/cli/cron/blog_ai.php >> /var/www/lrvweb/logs/cron.log 2>&1

# Backup automático (diário, 3h)
0 3 * * * php /var/www/lrvweb/cli/cron/backup.php >> /var/www/lrvweb/logs/cron.log 2>&1
```

### Nginx (exemplo)

```nginx
server {
    listen 80;
    server_name lrvweb.com.br;
    root /var/www/lrvweb/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(env|git|htaccess) {
        deny all;
    }
}
```

## Backup

```bash
# Backup manual do banco
mysqldump -u root -p lrvweb > backup_$(date +%Y%m%d).sql

# Backup via sistema
php cli/cron/backup.php
```

## Segurança

- Proteção CSRF em todos os formulários
- Sanitização de inputs (XSS Protection)
- Queries parametrizadas (SQL Injection Prevention)
- Rate Limiting por IP
- Senha com hash Argon2ID
- Sessões com regeneração periódica de ID
- Headers de segurança (X-Frame-Options, X-XSS-Protection, etc.)
- Arquivos sensíveis bloqueados via .htaccess
- Logs de auditoria em todas as ações administrativas

## Padrão de Commits

```
feat:      Nova funcionalidade
fix:       Correção de bug
refactor:  Refatoração de código
docs:      Documentação
style:     Formatação, espaçamento (sem mudança lógica)
test:      Testes
chore:     Manutenção, configuração, dependências
```

**Exemplos:**

```
feat(crm): adiciona gestão de leads com funil Kanban
fix(auth): corrige recuperação de senha com token expirado
refactor(projects): otimiza queries de listagem
docs(readme): atualiza instruções de instalação
```

## Ambientes

| Ambiente | Descrição |
|----------|-----------|
| `development` | Desenvolvimento local, debug ativado |
| `staging` | Ambiente de testes pré-produção |
| `production` | Produção, debug desativado |

## Roadmap

- [ ] Integração com gateways de pagamento
- [ ] Integração WhatsApp Business API
- [ ] App mobile (PWA)
- [ ] Preparação para SaaS multi-tenant
- [ ] CI/CD com GitHub Actions
- [ ] Testes automatizados completos

## Licença

Software proprietário. Copyright © 2026 LRV Web. Todos os direitos reservados.
Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.
