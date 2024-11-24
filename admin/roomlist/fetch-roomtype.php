<?php
    // require_once('../classes/room.class.php');

    // $roomObj = new Room();

    // $room_type = $roomObj->fetchroomType();

    // header('Content-Type: application/json');
    // echo json_encode($room_type);


    require_once('../classes/room.class.php');

    $roomObj = new Room();

    try {
        $room_type = $roomObj->fetchroomType();
        header('Content-Type: application/json');
        echo json_encode($room_type);
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }

?>
