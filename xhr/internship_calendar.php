<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$s = (string) ($_GET['s'] ?? $_POST['s'] ?? '');

function internship_calendar_json(array $payload, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function internship_calendar_render_events(array $events): string
{
    $html = '';
    $currentWeek = app_current_week($events);
    foreach ($events as $event) {
        $html .= Wo_LoadPage('internship-calendar/includes/event-card', [
            'event' => $event,
            'currentWeek' => $currentWeek,
        ]);
    }

    return $html;
}

switch ($s) {
    case 'search_events':
        $keyword = trim((string) ($_GET['keyword'] ?? $_POST['keyword'] ?? ''));
        $week = isset($_GET['week']) ? (int) $_GET['week'] : (int) ($_POST['week'] ?? 0);
        $events = Wo_GetInternshipCalendarEvents($keyword, $week > 0 ? $week : null);
        $allEvents = Wo_GetInternshipCalendarEvents();
        $currentWeek = app_current_week($allEvents);

        internship_calendar_json([
            'status' => 200,
            'html' => internship_calendar_render_events($events),
            'count' => count($events),
            'current_week' => $currentWeek,
            'message' => 'Results loaded',
        ]);
        break;

    case 'load_week':
        $week = (int) ($_GET['week'] ?? $_POST['week'] ?? 0);
        $events = Wo_GetInternshipCalendarEvents('', $week > 0 ? $week : null);
        $allEvents = Wo_GetInternshipCalendarEvents();
        $currentWeek = app_current_week($allEvents);

        internship_calendar_json([
            'status' => 200,
            'html' => internship_calendar_render_events($events),
            'count' => count($events),
            'current_week' => $currentWeek,
            'message' => 'Week loaded',
        ]);
        break;

    case 'event_stats':
        $events = Wo_GetInternshipCalendarEvents();
        $stats = Wo_GetInternshipCalendarStats($events);
        internship_calendar_json([
            'status' => 200,
            'html' => Wo_LoadPage('internship-calendar/includes/stats-strip', [
                'stats' => $stats,
            ]),
            'current_week' => (int) $stats['current_week'],
            'message' => 'Stats loaded',
        ]);
        break;

    case 'save_event':
    case 'update_event':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $result = Wo_SaveInternshipCalendarEvent($_POST, $s === 'update_event' ? $id : null);

        internship_calendar_json([
            'status' => $result['success'] ? 200 : 400,
            'message' => $result['success'] ? 'Event saved successfully' : 'Event could not be saved',
            'errors' => $result['errors'],
            'id' => $result['id'],
        ], $result['success'] ? 200 : 400);
        break;

    case 'delete_event':
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $deleted = $id > 0 ? Wo_DeleteInternshipCalendarEvent($id) : false;

        internship_calendar_json([
            'status' => $deleted ? 200 : 400,
            'message' => $deleted ? 'Event deleted successfully' : 'Unable to delete event',
        ], $deleted ? 200 : 400);
        break;

    default:
        internship_calendar_json([
            'status' => 404,
            'message' => 'Unknown action',
        ], 404);
}
