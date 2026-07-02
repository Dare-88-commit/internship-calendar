<?php

declare(strict_types=1);

global $wo;

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
    $result = Wo_SaveInternshipCalendarEvent($_POST);

    if ($result['success']) {
        header('Location: index.php?link1=internship-calendar');
        exit;
    }

    $errors = $result['errors'];
}

$wo['page_title'] = 'Add Internship Event';
$wo['description'] = 'Add a new internship calendar event.';
$wo['internship_calendar'] = [
    'errors' => $errors,
    'values' => $values,
];
$wo['content'] = Wo_LoadPage('internship-calendar/add-event', ['wo' => $wo]);

