<?php
require_once('../classes/room.class.php');

$roomObj = new Room();
$room_code = $_GET['roomCode'];
$room_no = $_GET['roomNo'];
$room = $roomObj->fetchroomlistRecord($room_code, $room_no);

header('Content-Type: application/json');
echo json_encode($room);
