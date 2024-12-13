<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $classes = $roomObj->fetchclassesOption();

    header('Content-Type: application/json');
    echo json_encode($classes);

?>
