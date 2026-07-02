<?php

declare(strict_types=1);

global $wo;

$eventId = (int) ($_GET['eid'] ?? $_GET['id'] ?? $_POST['id'] ?? 0);
$event = $eventId > 0 ? Wo_GetInternshipCalendarEvent($eventId) : null;
$errors = [];

if (!$event) {
    $wo['page_title'] = 'Event not found';
    $wo['description'] = 'The selected internship event could not be found.';
    $wo['internship_calendar'] = [];
    $wo['content'] = Wo_LoadPage('internship-calendar/includes/empty-state', [
        'title' => 'Event not found',
        'message' => 'Go back to the calendar and try again.',
        'buttonText' => 'Back to calendar',
        'buttonHref' => 'index.php?link1=internship-calendar',
    ]);
    return;
}

$values = $event;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = array_merge($values, array_map('strval', $_POST));
    $result = Wo_SaveInternshipCalendarEvent($_POST, $eventId);

    if ($result['success']) {
        header('Location: index.php?link1=internship-calendar-event&eid=' . $eventId);
        exit;
    }

    $errors = $result['errors'];
}

$wo['page_title'] = 'Edit Internship Event';
$wo['description'] = 'Edit an internship calendar event.';
$wo['internship_calendar'] = [
    'errors' => $errors,
    'values' => $values,
    'event' => $event,
];
$wo['content'] = Wo_LoadPage('internship-calendar/edit-event', ['wo' => $wo]);

