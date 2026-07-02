<?php

declare(strict_types=1);

$week = isset($_GET['week']) ? (int) $_GET['week'] : 0;
$target = 'index.php?link1=internship-calendar';
if ($week > 0) {
    $target .= '&week=' . $week;
}

header('Location: ' . $target);
exit;

