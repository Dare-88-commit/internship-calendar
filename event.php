<?php

declare(strict_types=1);

$eid = isset($_GET['eid']) ? (int) $_GET['eid'] : (int) ($_GET['id'] ?? 0);
$target = 'index.php?link1=internship-calendar-event';

if ($eid > 0) {
    $target .= '&eid=' . $eid;
}

header('Location: ' . $target);
exit;

