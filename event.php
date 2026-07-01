<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Event Details | Tribbbal Internship Calendar';
$event = null;
$events = app_fetch_events();

if (isset($_GET['id'])) {
    $event = app_fetch_event((int) $_GET['id']);
} elseif (isset($_GET['week'])) {
    $week = (int) $_GET['week'];
    foreach ($events as $candidate) {
        if ((int) $candidate['week'] === $week) {
            $event = $candidate;
            break;
        }
    }
}

if (!$event) {
    $fallback = $events[0] ?? null;
    if ($fallback) {
        $event = $fallback;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="form-shell">
    <?php if (!$event): ?>
        <div class="empty-state">
            <h1>No event found</h1>
            <p>Add a record first or return to the calendar view.</p>
            <a class="btn" href="add-event.php">Add event</a>
        </div>
    <?php else: ?>
        <div class="section-header">
            <div>
                <span class="pill accent">Week <?= (int) $event['week'] ?> • <?= app_escape($event['day']) ?></span>
                <h1 class="section-title"><?= app_escape($event['title']) ?></h1>
                <p class="section-subtitle">Event date: <?= app_format_date($event['event_date']) ?></p>
            </div>
            <div class="toolbar">
                <a class="ghost-btn" href="edit-event.php?id=<?= (int) $event['id'] ?>">Edit</a>
                <a class="ghost-btn" href="delete-event.php?id=<?= (int) $event['id'] ?>">Delete</a>
            </div>
        </div>

        <div class="panel event-detail" data-event-date="<?= app_escape($event['event_date']) ?>">
            <div>
                <h2 class="card-title">Description</h2>
                <p><?= nl2br(app_escape($event['description'])) ?></p>
            </div>

            <div>
                <h2 class="card-title">Success Criteria</h2>
                <?php $successLines = app_text_lines($event['success_criteria']); ?>
                <?php if ($successLines): ?>
                    <ul class="detail-list">
                        <?php foreach ($successLines as $line): ?>
                            <li><?= app_escape($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>-</p>
                <?php endif; ?>
            </div>

            <div>
                <h2 class="card-title">Traps / Watch Outs</h2>
                <?php $trapLines = app_text_lines($event['traps']); ?>
                <?php if ($trapLines): ?>
                    <ul class="detail-list">
                        <?php foreach ($trapLines as $line): ?>
                            <li><?= app_escape($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>-</p>
                <?php endif; ?>
            </div>

            <div class="timeline-meta">
                <span class="pill">Event ID: <?= (int) $event['id'] ?></span>
                <span class="pill">Date: <?= app_format_date($event['event_date']) ?></span>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

