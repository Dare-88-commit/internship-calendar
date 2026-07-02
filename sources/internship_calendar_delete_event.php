<?php

declare(strict_types=1);

global $wo;

$eventId = (int) ($_GET['eid'] ?? $_GET['id'] ?? $_POST['id'] ?? 0);
$event = $eventId > 0 ? Wo_GetInternshipCalendarEvent($eventId) : null;

if (!$event) {
    $wo['page_title'] = 'Delete Internship Event';
    $wo['description'] = 'Delete an internship calendar event.';
    $wo['content'] = Wo_LoadPage('internship-calendar/includes/empty-state', [
        'title' => 'Event not found',
        'message' => 'The event you tried to delete does not exist anymore.',
        'buttonText' => 'Back to calendar',
        'buttonHref' => 'index.php?link1=internship-calendar',
    ]);
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Wo_DeleteInternshipCalendarEvent($eventId)) {
        header('Location: index.php?link1=internship-calendar');
        exit;
    }
}

$wo['page_title'] = 'Delete Internship Event';
$wo['description'] = 'Delete an internship calendar event.';
$wo['internship_calendar'] = [
    'event' => $event,
];
$wo['content'] = Wo_LoadPage('internship-calendar/delete-event', ['wo' => $wo]);

