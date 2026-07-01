<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function app_db(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    try {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        die(
            'Database connection failed. ' .
            'Create the `internship_app` MySQL user, import `sql/internship_calendar.sql`, ' .
            'and update `config/database.php`. ' .
            'Details: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        );
    }

    if (!$connection) {
        http_response_code(500);
        die(
            'Database connection failed. ' .
            'Create the `internship_app` MySQL user, import `sql/internship_calendar.sql`, ' .
            'and update `config/database.php`.'
        );
    }

    mysqli_set_charset($connection, 'utf8mb4');

    return $connection;
}

function app_escape(?string $value): string
{
    return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
}

function app_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function app_flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function app_flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function app_text_lines(?string $text): array
{
    $text = trim((string) $text);

    if ($text === '') {
        return [];
    }

    $text = str_replace(["\r\n", "\r"], "\n", $text);

    return array_values(array_filter(array_map('trim', explode("\n", $text))));
}

function app_text_preview(?string $text, int $limit = 120): string
{
    $lines = app_text_lines($text);
    $preview = implode(' ', array_slice($lines, 0, 2));

    if ($preview === '') {
        return '';
    }

    return strlen($preview) > $limit ? substr($preview, 0, $limit - 1) . '…' : $preview;
}

function app_format_date(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $timestamp = strtotime($date);

    return $timestamp ? date('F j, Y', $timestamp) : '-';
}

function app_is_valid_date(?string $date): bool
{
    if (!$date) {
        return false;
    }

    $timestamp = strtotime($date);

    return $timestamp !== false;
}

function app_validate_event(array $data): array
{
    $errors = [];

    if (empty($data['week']) || (int) $data['week'] < 1) {
        $errors[] = 'Week is required.';
    }

    if (empty($data['day'])) {
        $errors[] = 'Day is required.';
    }

    if (empty($data['title'])) {
        $errors[] = 'Title is required.';
    }

    if (empty($data['description'])) {
        $errors[] = 'Description is required.';
    }

    if (empty($data['success_criteria'])) {
        $errors[] = 'Success criteria is required.';
    }

    if (empty($data['event_date']) || !app_is_valid_date($data['event_date'])) {
        $errors[] = 'A valid event date is required.';
    }

    return $errors;
}

function app_normalize_event(array $data): array
{
    return [
        'week' => (int) ($data['week'] ?? 0),
        'day' => trim((string) ($data['day'] ?? '')),
        'title' => trim((string) ($data['title'] ?? '')),
        'description' => trim((string) ($data['description'] ?? '')),
        'success_criteria' => trim((string) ($data['success_criteria'] ?? '')),
        'traps' => trim((string) ($data['traps'] ?? '')),
        'event_date' => trim((string) ($data['event_date'] ?? '')),
    ];
}

function app_fetch_events(string $search = ''): array
{
    $conn = app_db();
    $sql = 'SELECT * FROM calendar_events';

    if ($search !== '') {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " WHERE title LIKE '%{$search}%'";
    }

    $sql .= ' ORDER BY event_date ASC, week ASC, day ASC, id ASC';

    $result = mysqli_query($conn, $sql);
    $events = [];

    if ($result instanceof mysqli_result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        mysqli_free_result($result);
    }

    return $events;
}

function app_fetch_event(int $id): ?array
{
    $conn = app_db();
    $id = (int) $id;
    $sql = "SELECT * FROM calendar_events WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result instanceof mysqli_result) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        return $row ?: null;
    }

    return null;
}

function app_fetch_event_result_count(string $search = ''): int
{
    $conn = app_db();
    $sql = 'SELECT id FROM calendar_events';

    if ($search !== '') {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " WHERE title LIKE '%{$search}%'";
    }

    $result = mysqli_query($conn, $sql);

    if (!$result instanceof mysqli_result) {
        return 0;
    }

    $count = mysqli_num_rows($result);
    mysqli_free_result($result);

    return (int) $count;
}

function app_event_weeks(array $events): array
{
    $weeks = [];

    foreach ($events as $event) {
        $week = (int) $event['week'];
        if (!isset($weeks[$week])) {
            $weeks[$week] = [];
        }
        $weeks[$week][] = $event;
    }

    ksort($weeks);

    return $weeks;
}

function app_current_week(array $events): int
{
    if (!$events) {
        return 0;
    }

    $today = strtotime(date('Y-m-d'));
    $ranges = [];

    foreach ($events as $event) {
        $week = (int) $event['week'];
        $timestamp = strtotime((string) $event['event_date']);

        if ($timestamp === false) {
            continue;
        }

        if (!isset($ranges[$week])) {
            $ranges[$week] = ['start' => $timestamp, 'end' => $timestamp];
            continue;
        }

        $ranges[$week]['start'] = min($ranges[$week]['start'], $timestamp);
        $ranges[$week]['end'] = max($ranges[$week]['end'], $timestamp);
    }

    if (!$ranges) {
        return 0;
    }

    ksort($ranges);

    $firstWeek = (int) array_key_first($ranges);
    $lastWeek = (int) array_key_last($ranges);

    if ($today < $ranges[$firstWeek]['start']) {
        return $firstWeek;
    }

    if ($today > $ranges[$lastWeek]['end']) {
        return $lastWeek;
    }

    foreach ($ranges as $week => $range) {
        if ($today >= $range['start'] && $today <= $range['end']) {
            return (int) $week;
        }
    }

    return $firstWeek;
}

function app_dashboard_stats(array $events): array
{
    $today = strtotime(date('Y-m-d'));
    $totalEvents = count($events);
    $completed = 0;
    $upcoming = 0;

    foreach ($events as $event) {
        $timestamp = strtotime((string) $event['event_date']);
        if ($timestamp === false) {
            continue;
        }

        if ($timestamp < $today) {
            $completed++;
        } else {
            $upcoming++;
        }
    }

    return [
        'total_weeks' => count(array_unique(array_map(static fn(array $event): int => (int) $event['week'], $events))),
        'total_days' => $totalEvents,
        'total_events' => $totalEvents,
        'current_week' => app_current_week($events),
        'completed_events' => $completed,
        'upcoming_events' => $upcoming,
        'progress_percent' => $totalEvents > 0 ? (int) round(($completed / $totalEvents) * 100) : 0,
    ];
}

function app_week_card_summary(array $events): array
{
    $summaries = [];
    $grouped = app_event_weeks($events);

    foreach ($grouped as $week => $items) {
        $titles = array_map(static fn(array $item): string => $item['title'], $items);
        $days = array_map(static fn(array $item): string => $item['day'], $items);
        $summaries[] = [
            'week' => (int) $week,
            'count' => count($items),
            'days' => implode(', ', array_values(array_unique($days))),
            'titles' => implode(' • ', array_slice($titles, 0, 2)),
            'range_start' => $items[0]['event_date'],
            'range_end' => $items[count($items) - 1]['event_date'],
        ];
    }

    return $summaries;
}

function app_save_event(array $data, ?int $id = null): array
{
    $conn = app_db();
    $payload = app_normalize_event($data);
    $errors = app_validate_event($payload);

    if ($errors) {
        return [
            'success' => false,
            'errors' => $errors,
            'id' => $id,
        ];
    }

    if ($id !== null) {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE calendar_events SET week = ?, day = ?, title = ?, description = ?, success_criteria = ?, traps = ?, event_date = ? WHERE id = ?'
        );

        if (!$stmt) {
            return [
                'success' => false,
                'errors' => ['Unable to prepare update statement.'],
                'id' => $id,
            ];
        }

        mysqli_stmt_bind_param(
            $stmt,
            'issssssi',
            $payload['week'],
            $payload['day'],
            $payload['title'],
            $payload['description'],
            $payload['success_criteria'],
            $payload['traps'],
            $payload['event_date'],
            $id
        );
    } else {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO calendar_events (week, day, title, description, success_criteria, traps, event_date) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            return [
                'success' => false,
                'errors' => ['Unable to prepare insert statement.'],
                'id' => null,
            ];
        }

        mysqli_stmt_bind_param(
            $stmt,
            'issssss',
            $payload['week'],
            $payload['day'],
            $payload['title'],
            $payload['description'],
            $payload['success_criteria'],
            $payload['traps'],
            $payload['event_date']
        );
    }

    $executed = mysqli_stmt_execute($stmt);
    $stmtError = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    if (!$executed) {
        return [
            'success' => false,
            'errors' => [$stmtError ?: 'Database action failed.'],
            'id' => $id,
        ];
    }

    return [
        'success' => true,
        'errors' => [],
        'id' => $id ?? (int) mysqli_insert_id($conn),
    ];
}

function app_delete_event(int $id): bool
{
    $conn = app_db();
    $stmt = mysqli_prepare($conn, 'DELETE FROM calendar_events WHERE id = ?');

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    $executed = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $executed;
}
