<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
$event = $id > 0 ? app_fetch_event($id) : null;

if (!$event) {
    app_flash_set('error', 'Event not found.');
    app_redirect('calendar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (app_delete_event($id)) {
        app_flash_set('success', 'Event deleted successfully.');
        app_redirect('calendar.php');
    }

    app_flash_set('error', 'Unable to delete event.');
    app_redirect('event.php?id=' . $id);
}

$pageTitle = 'Delete Event | Tribbbal Internship Calendar';
require_once __DIR__ . '/includes/header.php';
?>

<section class="form-shell">
    <div class="empty-state">
        <span class="pill warning">Delete confirmation</span>
        <h1>Delete this event?</h1>
        <p>
            <strong><?= app_escape($event['title']) ?></strong><br>
            Week <?= (int) $event['week'] ?> • <?= app_escape($event['day']) ?> • <?= app_format_date($event['event_date']) ?>
        </p>

        <form method="post" class="form-actions" data-confirm="This will permanently delete the event. Continue?">
            <input type="hidden" name="id" value="<?= (int) $id ?>">
            <button class="danger-btn" type="submit">Yes, delete it</button>
            <a class="ghost-btn" href="event.php?id=<?= (int) $id ?>">Cancel</a>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

