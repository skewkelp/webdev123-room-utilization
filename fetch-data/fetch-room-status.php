<?php
require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();
$class_status_id = $_GET['id'];
$class_status = $roomObj->fetchroomstatusRecord($class_status_id);

header('Content-Type: application/json');
echo json_encode($class_status);