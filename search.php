<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$query = trim((string) ($_GET['q'] ?? ''));
$events = $query !== '' ? app_fetch_events($query) : [];
$total = $query !== '' ? app_fetch_event_result_count($query) : 0;
$pageTitle = 'Search | Tribbbal Internship Calendar';

require_once __DIR__ . '/includes/header.php';
?>

<section class="form-shell">
    <h1>Search Events</h1>
    <p class="form-hint">Search event titles using one or more keywords.</p>

    <form method="get" class="panel">
        <div class="form-grid">
            <div class="field full">
                <label for="q">Title</label>
                <input type="search" id="q" name="q" placeholder="Search by title..." value="<?= app_escape($query) ?>">
            </div>
        </div>
        <div class="form-actions" style="margin-top: 18px;">
            <button class="btn" type="submit">Search</button>
            <a class="ghost-btn" href="search.php">Reset</a>
        </div>
    </form>

    <?php if ($query !== ''): ?>
        <div class="section" style="margin-top: 18px;">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Results for "<?= app_escape($query) ?>"</h2>
                    <p class="section-subtitle"><?= (int) $total ?> match(es) found.</p>
                </div>
            </div>

            <?php if ($events): ?>
                <div class="grid calendar-grid">
                    <?php foreach ($events as $event): ?>
                        <article class="timeline-event" data-event-date="<?= app_escape($event['event_date']) ?>">
                            <span class="pill accent">Week <?= (int) $event['week'] ?> • <?= app_escape($event['day']) ?></span>
                            <h3 class="timeline-title"><?= app_escape($event['title']) ?></h3>
                            <p class="timeline-desc"><?= app_escape(app_text_preview($event['description'])) ?></p>
                            <div class="timeline-meta">
                                <span class="pill"><?= app_format_date($event['event_date']) ?></span>
                                <a class="ghost-btn" href="event.php?id=<?= (int) $event['id'] ?>">Open</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h2>No matching events</h2>
                    <p>Try a different title or go back and add a new record.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

