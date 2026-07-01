<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Tribbbal Internship Calendar';
$bodyClass = $bodyClass ?? '';
$currentPage = basename($_SERVER['PHP_SELF']);
$flash = app_flash_get();
$eventsForNav = app_fetch_events();
$navItems = [
    ['label' => 'Home', 'href' => 'index.php'],
    ['label' => 'Calendar', 'href' => 'calendar.php'],
    ['label' => 'Dashboard', 'href' => 'dashboard.php'],
    ['label' => 'Add Event', 'href' => 'add-event.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= app_escape($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        window.APP_CONTEXT = <?= json_encode([
            'page' => $currentPage,
            'year' => (int) date('Y'),
            'currentWeek' => app_current_week($eventsForNav),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script defer src="assets/js/script.js"></script>
</head>
<body class="<?= app_escape($bodyClass) ?>">
<div class="page-shell">
    <header class="site-header">
        <a class="brand" href="index.php">
            <span class="brand-mark">TC</span>
            <span>
                <strong>Tribbbal</strong>
                <small>Internship Calendar</small>
            </span>
        </a>

        <button class="nav-toggle" type="button" data-nav-toggle aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" data-nav>
            <?php foreach ($navItems as $item): ?>
                <a class="<?= $currentPage === $item['href'] ? 'active' : '' ?>" href="<?= $item['href'] ?>">
                    <?= app_escape($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </header>

    <?php if ($flash): ?>
        <div class="flash flash-<?= app_escape($flash['type']) ?>">
            <?= app_escape($flash['message']) ?>
        </div>
    <?php endif; ?>

    <main class="main-content">

