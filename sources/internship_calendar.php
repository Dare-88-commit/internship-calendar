<?php

declare(strict_types=1);

global $wo;

$search = trim((string) ($_GET['q'] ?? ''));
$week = isset($_GET['week']) ? (int) $_GET['week'] : 0;

$events = Wo_GetInternshipCalendarEvents($search, $week > 0 ? $week : null);
$allEvents = Wo_GetInternshipCalendarEvents();
$stats = Wo_GetInternshipCalendarStats($allEvents);
$weekCards = Wo_GetInternshipCalendarWeekCards($allEvents);
$currentWeek = (int) $stats['current_week'];

$wo['page_title'] = 'Internship Calendar';
$wo['description'] = 'Tribbbal internship calendar aligned with the Wondertag frontend structure.';
$wo['internship_calendar'] = [
    'search' => $search,
    'week' => $week,
    'events' => $events,
    'all_events' => $allEvents,
    'stats' => $stats,
    'week_cards' => $weekCards,
    'current_week' => $currentWeek,
    'latest_events' => array_slice($allEvents, 0, 8),
];
$wo['content'] = Wo_LoadPage('internship-calendar/content', ['wo' => $wo]);

