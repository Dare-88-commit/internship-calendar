<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$events = app_fetch_events();
$weekFilter = isset($_GET['week']) ? (int) $_GET['week'] : 0;
$grouped = app_event_weeks($events);
$pageTitle = 'Calendar | Tribbbal Internship Calendar';

if ($weekFilter > 0) {
    $grouped = array_intersect_key($grouped, [$weekFilter => true]);
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="section-header">
        <div>
            <h1 class="section-title">Calendar Timeline</h1>
            <p class="section-subtitle">Browse internship events grouped by week. Select a week from the home page for a focused view.</p>
        </div>
        <div class="toolbar">
            <a class="ghost-btn" href="index.php">Back home</a>
            <a class="btn" href="add-event.php">Add event</a>
        </div>
    </div>

    <?php if ($grouped): ?>
        <div class="grid calendar-grid">
            <?php foreach ($grouped as $weekNumber => $weekEvents): ?>
                <article class="panel">
                    <h2 class="timeline-week">Week <?= (int) $weekNumber ?></h2>
                    <?php foreach ($weekEvents as $event): ?>
                        <div class="timeline-event" data-event-date="<?= app_escape($event['event_date']) ?>">
                            <span class="pill accent"><?= app_escape($event['day']) ?></span>
                            <h3 class="timeline-title"><?= app_escape($event['title']) ?></h3>
                            <p class="timeline-desc"><?= app_escape(app_text_preview($event['description'])) ?></p>
                            <div class="timeline-meta">
                                <span class="pill"><?= app_format_date($event['event_date']) ?></span>
                                <a class="ghost-btn" href="event.php?id=<?= (int) $event['id'] ?>">Details</a>
                                <a class="ghost-btn" href="edit-event.php?id=<?= (int) $event['id'] ?>">Edit</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>No calendar entries yet</h2>
            <p>Import the SQL seed or create a new event to start the timeline.</p>
            <a class="btn" href="add-event.php">Add event</a>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
