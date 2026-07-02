<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$f = (string) ($_GET['f'] ?? '');

if ($f === 'internship_calendar') {
    require __DIR__ . '/xhr/internship_calendar.php';
    exit;
}

header('Content-Type: application/json; charset=utf-8');
http_response_code(404);
echo json_encode([
    'status' => 404,
    'message' => 'Unknown request',
]);

