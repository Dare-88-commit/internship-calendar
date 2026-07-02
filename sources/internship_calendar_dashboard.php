<?php

declare(strict_types=1);

global $wo;

$events = Wo_GetInternshipCalendarEvents();
$stats = Wo_GetInternshipCalendarStats($events);

$wo['page_title'] = 'Internship Calendar Dashboard';
$wo['description'] = 'Progress summary and operational overview for the internship calendar.';
$wo['internship_calendar'] = [
    'events' => $events,
    'stats' => $stats,
    'current_week' => (int) $stats['current_week'],
    'latest_events' => array_slice($events, 0, 6),
];
$wo['content'] = Wo_LoadPage('internship-calendar/dashboard', ['wo' => $wo]);

