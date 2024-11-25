<?php
require_once('../../classes/room.class.php');

$roomObj = new Room();
$room_id = $_GET['id'];
$room = $roomObj->fetchroomlistRecord($room_id);

header('Content-Type: application/json');
echo json_encode($room);
