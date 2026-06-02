<!DOCTYPE html>
<html lang="<?= \Core\I18n::getLocale() ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO -->
    <title><?= htmlspecialchars($title ?? 'LRV Web') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? '') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical ?? (\Core\Config::get('app.url', ''))) ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'LRV Web') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($canonical ?? '') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image ?? '') ?>">
    <meta property="og:site_name" content="LRV Web">
    <meta property="og:locale" content="<?= \Core\I18n::getLocale() === 'pt' ? 'pt_BR' : \Core\I18n::getLocale() ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($title ?? 'LRV Web') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta_description ?? '') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= \Core\View::asset('img/favicon.ico') ?>">

    <!-- Tailwind CSS via CDN (substituir por build em produção) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        secondary: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a' },
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \Core\View::asset('css/app.css') ?>">

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "LRV Web",
        "url": "<?= \Core\Config::get('app.url', '') ?>",
        "description": "Soluções Digitais - Hospedagem, Sites e Sistemas",
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service"
        }
    }
    </script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">

    <!-- Header/Navbar -->
    <?php echo \Core\View::component('site/header', $data ?? []) ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php echo \Core\View::component('site/footer', $data ?? []) ?>

    <!-- Cookie Banner -->
    <?php echo \Core\View::component('site/cookie-banner') ?>

    <!-- Scripts -->
    <script src="<?= \Core\View::asset('js/app.js') ?>"></script>

    <!-- Dark Mode Toggle -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
