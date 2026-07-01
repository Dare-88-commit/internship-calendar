<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$events = app_fetch_events();
$stats = app_dashboard_stats($events);
$pageTitle = 'Dashboard | Tribbbal Internship Calendar';

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="section-header">
        <div>
            <h1 class="section-title">Dashboard</h1>
            <p class="section-subtitle">A quick operational view of the internship calendar.</p>
        </div>
        <div class="toolbar">
            <a class="ghost-btn" href="calendar.php">Open calendar</a>
            <a class="btn" href="add-event.php">Add event</a>
        </div>
    </div>

    <div class="grid week-grid">
        <article class="metric">
            <span class="metric-label">Total Weeks</span>
            <p class="metric-value"><?= (int) $stats['total_weeks'] ?></p>
        </article>
        <article class="metric">
            <span class="metric-label">Total Days</span>
            <p class="metric-value"><?= (int) $stats['total_days'] ?></p>
        </article>
        <article class="metric">
            <span class="metric-label">Total Events</span>
            <p class="metric-value"><?= (int) $stats['total_events'] ?></p>
        </article>
        <article class="metric">
            <span class="metric-label">Current Week</span>
            <p class="metric-value"><?= (int) $stats['current_week'] ?></p>
        </article>
        <article class="metric">
            <span class="metric-label">Completed Events</span>
            <p class="metric-value"><?= (int) $stats['completed_events'] ?></p>
        </article>
        <article class="metric">
            <span class="metric-label">Upcoming Events</span>
            <p class="metric-value"><?= (int) $stats['upcoming_events'] ?></p>
        </article>
    </div>

    <div class="panel progress-card" style="margin-top: 16px;">
        <div class="section-header" style="margin-bottom: 8px;">
            <div>
                <h2 class="section-title" style="font-size: 1.25rem; margin: 0;">Progress</h2>
                <p class="section-subtitle" style="margin: 6px 0 0;">Completion percentage based on event dates.</p>
            </div>
            <span class="pill"><?= (int) $stats['progress_percent'] ?>%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width: <?= (int) $stats['progress_percent'] ?>%;"></div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

