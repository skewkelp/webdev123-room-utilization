<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $semester = $roomObj->fetchprospectusOption();

    header('Content-Type: application/json');
    echo json_encode($semester);

?>
