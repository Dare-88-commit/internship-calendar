<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$wo = [
    'page' => 'internship-calendar',
];

$link1 = trim((string) ($_GET['link1'] ?? 'internship-calendar'));

switch ($link1) {
    case 'internship-calendar-dashboard':
        require __DIR__ . '/sources/internship_calendar_dashboard.php';
        break;

    case 'internship-calendar-event':
        require __DIR__ . '/sources/internship_calendar_event.php';
        break;

    case 'internship-calendar':
    default:
        require __DIR__ . '/sources/internship_calendar.php';
        break;
}

echo Wo_LoadPage('container', ['wo' => $wo]);

