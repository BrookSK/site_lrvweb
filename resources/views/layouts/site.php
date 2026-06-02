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
    <meta property="og:site_name" content="LRV Web">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= \Core\View::asset('img/favicon.ico') ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \Core\View::asset('css/app.css') ?>">

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

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "LRV Web",
        "url": "<?= \Core\Config::get('app.url', '') ?>",
        "description": "Soluções Digitais - Hospedagem, Sites e Sistemas"
    }
    </script>
</head>
<body class="bg-black text-white antialiased font-sans overflow-x-hidden">

    <?php echo \Core\View::component('site/header', $data ?? []) ?>

    <main>
        <?= $content ?>
    </main>

    <?php echo \Core\View::component('site/footer', $data ?? []) ?>
    <?php echo \Core\View::component('site/whatsapp-float') ?>
    <?php echo \Core\View::component('site/cookie-banner') ?>

    <!-- Scripts -->
    <script src="<?= \Core\View::asset('js/app.js') ?>"></script>
</body>
</html>
