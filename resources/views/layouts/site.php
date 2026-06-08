<!DOCTYPE html>
<html lang="<?= \Core\I18n::getLocale() ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO -->
    <title><?= htmlspecialchars($title ?? 'LRV Web - Soluções Digitais') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'LRV Web - Hospedagem Cloud, Criação de Sites, Sistemas Sob Medida e Soluções Digitais.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'hospedagem de sites, criação de sites, sistemas sob medida, e-commerce, desenvolvimento web, VPS, cloud, LRV Web') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical ?? (\Core\Config::get('app.url', '') . '/' . \Core\I18n::getLocale() . (preg_replace('#^/(pt|en|es)#', '', $_SERVER['REQUEST_URI'] ?? '/') ?: '/'))) ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="LRV Web">
    <meta name="theme-color" content="#2d1b69">

    <!-- Hreflang (SEO multilíngue) -->
    <?php
    $seoBaseUrl = \Core\Config::get('app.url', '');
    $seoPath = preg_replace('#^/(pt|en|es)#', '', $_SERVER['REQUEST_URI'] ?? '/') ?: '/';
    ?>
    <link rel="alternate" hreflang="pt-BR" href="<?= $seoBaseUrl ?>/pt<?= $seoPath ?>">
    <link rel="alternate" hreflang="en" href="<?= $seoBaseUrl ?>/en<?= $seoPath ?>">
    <link rel="alternate" hreflang="es" href="<?= $seoBaseUrl ?>/es<?= $seoPath ?>">
    <link rel="alternate" hreflang="x-default" href="<?= $seoBaseUrl ?>/pt<?= $seoPath ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'LRV Web') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $seoBaseUrl ?>/<?= \Core\I18n::getLocale() ?><?= $seoPath ?>">
    <meta property="og:site_name" content="LRV Web">
    <meta property="og:locale" content="<?= \Core\I18n::getLocale() === 'pt' ? 'pt_BR' : (\Core\I18n::getLocale() === 'es' ? 'es_ES' : 'en_US') ?>">
    <meta property="og:image" content="<?= $seoBaseUrl ?><?= \Core\Config::setting('branding.logo_main') ?: '/assets/img/branding/og-image.png' ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($title ?? 'LRV Web') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
    <meta name="twitter:image" content="<?= $seoBaseUrl ?><?= \Core\Config::setting('branding.logo_main') ?: '/assets/img/branding/og-image.png' ?>">

    <!-- Schema.org - Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "LRV Web",
        "url": "<?= $seoBaseUrl ?>",
        "logo": "<?= $seoBaseUrl ?><?= \Core\Config::setting('branding.logo_main') ?: '' ?>",
        "description": "Soluções Digitais - Hospedagem Cloud, Criação de Sites, Sistemas Sob Medida",
        "sameAs": [
            "https://www.instagram.com/lrvweb.com.br",
            "https://www.linkedin.com/company/lrv-web/",
            "https://www.facebook.com/profile.php?id=61553861960826",
            "https://youtube.com/@LRVWeb"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+55-17-98809-3160",
            "contactType": "customer service",
            "availableLanguage": ["Portuguese", "English", "Spanish"]
        },
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "BR"
        }
    }
    </script>

    <!-- Schema.org - WebSite (busca) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "LRV Web",
        "url": "<?= $seoBaseUrl ?>",
        "inLanguage": ["pt-BR", "en", "es"],
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= $seoBaseUrl ?>/pt/blog?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- Schema.org - LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ProfessionalService",
        "name": "LRV Web",
        "url": "<?= $seoBaseUrl ?>",
        "telephone": "+55-17-98809-3160",
        "email": "contato@lrvweb.com.br",
        "priceRange": "$$",
        "openingHours": ["Mo-Fr 09:00-18:00", "Sa 09:00-13:00"],
        "serviceType": ["Web Hosting", "Web Development", "Custom Software", "E-commerce"],
        "areaServed": {
            "@type": "Country",
            "name": "Brazil"
        }
    }
    </script>

    <!-- Favicon -->
    <?php $favicon = \Core\Config::setting('branding.favicon'); ?>
    <?php if ($favicon): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?= \Core\View::asset('img/favicon.ico') ?>">
    <?php endif; ?>

    <!-- Preconnect e Preload para performance -->
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" as="style">

    <!-- Tailwind CSS (com plugin typography) -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            50: '#faf5ff', 100: '#f3e8ff', 200: '#e9d5ff', 300: '#d8b4fe',
                            400: '#c084fc', 500: '#a855f7', 600: '#7c3aed', 700: '#6d28d9',
                            800: '#5b21b6', 900: '#4c1d95', 950: '#2d1b69'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \Core\View::asset('css/app.css') ?>">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Override Tailwind para botões (garante especificidade) -->
    <style>
        .btn-primary {
            background-image: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            color: #fff !important;
            padding: 0.875rem 2rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.9375rem !important;
            display: inline-flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            white-space: nowrap !important;
            text-decoration: none !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3) !important;
        }
        .btn-outline {
            border: 2px solid rgba(124, 58, 237, 0.5) !important;
            background: transparent !important;
            color: #fff !important;
            padding: 0.875rem 2rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.9375rem !important;
            display: inline-flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            white-space: nowrap !important;
            text-decoration: none !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
        }
        .btn-outline:hover {
            background: #7c3aed !important;
            border-color: #7c3aed !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3) !important;
        }
        .btn-primary svg,
        .btn-outline svg {
            flex-shrink: 0 !important;
            width: 1.125rem !important;
            height: 1.125rem !important;
            display: inline-block !important;
        }
    </style>

    <!-- Google Analytics -->
    <?php $ga = \Core\Config::setting('seo.google_analytics'); ?>
    <?php if ($ga): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga) ?>"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= htmlspecialchars($ga) ?>');</script>
    <?php endif; ?>

    <!-- Google Tag Manager -->
    <?php $gtm = \Core\Config::setting('seo.google_tag_manager'); ?>
    <?php if ($gtm): ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?= htmlspecialchars($gtm) ?>');</script>
    <?php endif; ?>
</head>
<body class="bg-black text-white antialiased font-sans overflow-x-hidden">
    <!-- GTM noscript -->
    <?php if ($gtm ?? null): ?>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= htmlspecialchars($gtm) ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>

    <?php echo \Core\View::component('site/header', $data ?? []) ?>

    <main>
        <?= $content ?>
    </main>

    <?php echo \Core\View::component('site/footer', $data ?? []) ?>
    <?php echo \Core\View::component('site/whatsapp-float') ?>
    <?php echo \Core\View::component('site/chatbot') ?>
    <?php echo \Core\View::component('site/cookie-banner') ?>

    <!-- Scripts -->
    <script src="<?= \Core\View::asset('js/app.js') ?>"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
