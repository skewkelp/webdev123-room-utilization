<?php
    require_once('../classes/room.class.php');

    $roomObj = new Room();

    $room_name = $roomObj->fetchroomList();

    header('Content-Type: application/json');
    echo json_encode($room_name);

?>
