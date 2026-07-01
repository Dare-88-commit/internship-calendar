<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$events = app_fetch_events();
$stats = app_dashboard_stats($events);
$weekCards = app_week_card_summary($events);
$latestEvents = array_slice($events, 0, 6);
$pageTitle = 'Home | Tribbbal Internship Calendar';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-panel">
        <span class="eyebrow">Premium internship schedule management</span>
        <h1>Tribbbal Internship Calendar</h1>
        <p>
            A polished PHP and MySQL calendar system for managing all internship weeks, daily activities,
            success criteria, and watch-outs from one clean dashboard.
        </p>

        <div class="hero-actions">
            <a class="btn" href="calendar.php">Open Calendar</a>
            <a class="ghost-btn" href="dashboard.php">View Dashboard</a>
            <a class="ghost-btn" href="add-event.php">Add Event</a>
        </div>
    </div>

    <div class="hero-aside">
        <div class="metric-grid">
            <article class="metric">
                <span class="metric-label">Weeks</span>
                <p class="metric-value"><?= (int) $stats['total_weeks'] ?></p>
            </article>
            <article class="metric">
                <span class="metric-label">Events</span>
                <p class="metric-value"><?= (int) $stats['total_events'] ?></p>
            </article>
            <article class="metric">
                <span class="metric-label">Current Week</span>
                <p class="metric-value"><?= (int) $stats['current_week'] ?></p>
            </article>
            <article class="metric">
                <span class="metric-label">Progress</span>
                <p class="metric-value"><?= (int) $stats['progress_percent'] ?>%</p>
            </article>
        </div>

        <div class="panel progress-card">
            <div class="section-header" style="margin-bottom: 8px;">
                <div>
                    <h2 class="section-title" style="font-size: 1.25rem; margin: 0;">Completion</h2>
                    <p class="section-subtitle" style="margin: 6px 0 0;">Completed vs upcoming internship events.</p>
                </div>
            </div>
            <div class="progress-track" aria-hidden="true">
                <div class="progress-fill" style="width: <?= (int) $stats['progress_percent'] ?>%;"></div>
            </div>
            <p class="helper-copy"><?= (int) $stats['completed_events'] ?> completed and <?= (int) $stats['upcoming_events'] ?> upcoming.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Weekly Overview</h2>
            <p class="section-subtitle">Each week card summarizes the internship plan at a glance.</p>
        </div>
        <a class="ghost-btn" href="search.php">Search titles</a>
    </div>

    <?php if ($weekCards): ?>
        <div class="grid week-grid">
            <?php foreach ($weekCards as $week): ?>
                <article class="card">
                    <span class="pill accent">Week <?= (int) $week['week'] ?></span>
                    <h3 class="card-title"><?= app_escape($week['titles'] !== '' ? $week['titles'] : 'No title available') ?></h3>
                    <p class="card-meta"><?= (int) $week['count'] ?> day(s)</p>
                    <div class="card-days">
                        <?php foreach (array_slice(explode(', ', $week['days']), 0, 5) as $day): ?>
                            <span class="pill"><?= app_escape($day) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="toolbar">
                        <a class="ghost-btn" href="calendar.php?week=<?= (int) $week['week'] ?>">Open week</a>
                        <a class="ghost-btn" href="event.php?week=<?= (int) $week['week'] ?>">View detail</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>No events yet</h2>
            <p>Import the SQL file or add your first calendar entry to begin.</p>
            <a class="btn" href="add-event.php">Add event</a>
        </div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Latest Entries</h2>
            <p class="section-subtitle">Quick preview of the most recent schedule records.</p>
        </div>
    </div>

    <?php if ($latestEvents): ?>
        <div class="grid calendar-grid">
            <?php foreach ($latestEvents as $event): ?>
                <article class="timeline-event" data-event-date="<?= app_escape($event['event_date']) ?>">
                    <span class="pill success">Week <?= (int) $event['week'] ?> • <?= app_escape($event['day']) ?></span>
                    <h3 class="timeline-title"><?= app_escape($event['title']) ?></h3>
                    <p class="timeline-desc"><?= app_escape(app_text_preview($event['description'], 170)) ?></p>
                    <div class="timeline-meta">
                        <span class="pill"><?= app_format_date($event['event_date']) ?></span>
                        <a class="ghost-btn" href="event.php?id=<?= (int) $event['id'] ?>">Open</a>
                        <a class="ghost-btn" href="edit-event.php?id=<?= (int) $event['id'] ?>">Edit</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>No recent entries</h2>
            <p>Once you import data, the latest event cards will appear here.</p>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
