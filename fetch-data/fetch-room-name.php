<?php
    require_once('../classes/room.class.php');

    $roomObj = new Room();

    $room = $roomObj->fetchroomList();

    header('Content-Type: application/json');
    echo json_encode($room);

?>
