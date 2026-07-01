<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
$event = $id > 0 ? app_fetch_event($id) : null;
$errors = [];

if (!$event) {
    app_flash_set('error', 'Event not found.');
    app_redirect('calendar.php');
}

$values = $event;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = array_merge($values, array_map('strval', $_POST));
    $result = app_save_event($_POST, $id);

    if ($result['success']) {
        app_flash_set('success', 'Event updated successfully.');
        app_redirect('event.php?id=' . $id);
    }

    $errors = $result['errors'];
}

$pageTitle = 'Edit Event | Tribbbal Internship Calendar';
require_once __DIR__ . '/includes/header.php';
?>

<section class="form-shell">
    <h1>Edit Event</h1>
    <p class="form-hint">Update the selected internship schedule record.</p>

    <?php if ($errors): ?>
        <div class="flash flash-error">
            <?php foreach ($errors as $error): ?>
                <div><?= app_escape($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="panel">
        <input type="hidden" name="id" value="<?= (int) $id ?>">
        <div class="form-grid">
            <div class="field">
                <label for="week">Week</label>
                <input type="number" min="1" max="8" id="week" name="week" value="<?= app_escape((string) $values['week']) ?>" required>
            </div>

            <div class="field">
                <label for="day">Day</label>
                <select id="day" name="day" required>
                    <?php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    $selectedDay = $values['day'];
                    ?>
                    <option value="">Select day</option>
                    <?php foreach ($days as $day): ?>
                        <option value="<?= app_escape($day) ?>" <?= $selectedDay === $day ? 'selected' : '' ?>><?= app_escape($day) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field full">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= app_escape((string) $values['title']) ?>" required>
            </div>

            <div class="field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= app_escape((string) $values['description']) ?></textarea>
            </div>

            <div class="field full">
                <label for="success_criteria">Success Criteria</label>
                <textarea id="success_criteria" name="success_criteria" required><?= app_escape((string) $values['success_criteria']) ?></textarea>
            </div>

            <div class="field full">
                <label for="traps">Traps / Watch Outs</label>
                <textarea id="traps" name="traps"><?= app_escape((string) ($values['traps'] ?? '')) ?></textarea>
            </div>

            <div class="field">
                <label for="event_date">Date</label>
                <input type="date" id="event_date" name="event_date" value="<?= app_escape((string) $values['event_date']) ?>" required>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 18px;">
            <button class="btn" type="submit">Update Event</button>
            <a class="ghost-btn" href="event.php?id=<?= (int) $id ?>">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

