<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Login') ?> - LRV Web</title>
    <meta name="robots" content="noindex, nofollow">
    <?php $favicon = \Core\Config::setting('branding.favicon'); ?>
    <?php if ($favicon): ?><link rel="icon" href="<?= htmlspecialchars($favicon) ?>"><?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glow-orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-black relative overflow-hidden">
    <!-- Orbs decorativos -->
    <div class="glow-orb w-96 h-96 bg-purple-600 -top-48 -right-48" style="position:absolute"></div>
    <div class="glow-orb w-64 h-64 bg-purple-800 -bottom-32 -left-32" style="position:absolute;animation-delay:2s"></div>

    <div class="w-full max-w-md mx-4 relative z-10">
        <?= $content ?>
    </div>
</body>
</html>
