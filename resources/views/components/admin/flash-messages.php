<?php
$session = \Core\Session::getInstance();
$success = $session->getFlash('success');
$error = $session->getFlash('error');
$warning = $session->getFlash('warning');
?>

<?php if ($success): ?>
<div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3" role="alert">
    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
    <span class="text-green-800 dark:text-green-300 text-sm"><?= htmlspecialchars($success) ?></span>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-3" role="alert">
    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
    <span class="text-red-800 dark:text-red-300 text-sm"><?= htmlspecialchars($error) ?></span>
</div>
<?php endif; ?>

<?php if ($warning): ?>
<div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg flex items-center gap-3" role="alert">
    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
    <span class="text-yellow-800 dark:text-yellow-300 text-sm"><?= htmlspecialchars($warning) ?></span>
</div>
<?php endif; ?>
