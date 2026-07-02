<?php

declare(strict_types=1);

global $wo;

$eventId = (int) ($_GET['eid'] ?? $_GET['id'] ?? 0);
$event = $eventId > 0 ? Wo_GetInternshipCalendarEvent($eventId) : null;

if (!$event) {
    $event = Wo_GetInternshipCalendarEvents()[0] ?? null;
}

$wo['page_title'] = 'Internship Calendar Event';
$wo['description'] = 'Single internship event detail view.';
$wo['internship_calendar'] = [
    'event' => $event,
];
$wo['content'] = Wo_LoadPage('internship-calendar/event', ['wo' => $wo]);

