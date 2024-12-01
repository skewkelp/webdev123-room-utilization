<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $teacher = $roomObj->fetchteacherOption();

    header('Content-Type: application/json');
    echo json_encode($teacher);

?>
