<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$errors = [];
$values = [
    'week' => '',
    'day' => '',
    'title' => '',
    'description' => '',
    'success_criteria' => '',
    'traps' => '',
    'event_date' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = array_merge($values, array_map('strval', $_POST));
    $result = app_save_event($_POST);

    if ($result['success']) {
        app_flash_set('success', 'Event added successfully.');
        app_redirect('calendar.php');
    }

    $errors = $result['errors'];
}

$pageTitle = 'Add Event | Tribbbal Internship Calendar';
require_once __DIR__ . '/includes/header.php';
?>

<section class="form-shell">
    <h1>Add Event</h1>
    <p class="form-hint">Create a new internship schedule entry and store it in MySQL.</p>

    <?php if ($errors): ?>
        <div class="flash flash-error">
            <?php foreach ($errors as $error): ?>
                <div><?= app_escape($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="panel">
        <div class="form-grid">
            <div class="field">
                <label for="week">Week</label>
                <input type="number" min="1" max="8" id="week" name="week" value="<?= app_escape($values['week']) ?>" required>
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
                <input type="text" id="title" name="title" value="<?= app_escape($values['title']) ?>" required>
            </div>

            <div class="field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= app_escape($values['description']) ?></textarea>
            </div>

            <div class="field full">
                <label for="success_criteria">Success Criteria</label>
                <textarea id="success_criteria" name="success_criteria" required><?= app_escape($values['success_criteria']) ?></textarea>
            </div>

            <div class="field full">
                <label for="traps">Traps / Watch Outs</label>
                <textarea id="traps" name="traps"><?= app_escape($values['traps']) ?></textarea>
            </div>

            <div class="field">
                <label for="event_date">Date</label>
                <input type="date" id="event_date" name="event_date" value="<?= app_escape($values['event_date']) ?>" required>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 18px;">
            <button class="btn" type="submit">Save Event</button>
            <a class="ghost-btn" href="calendar.php">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

