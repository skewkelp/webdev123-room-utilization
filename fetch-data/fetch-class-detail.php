<?php
require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();
$class_id = $_GET['classId'];
$subject_type = $_GET['subType'];
$class_details = $roomObj->fetchclassDetailsRecord($class_id, $subject_type);

header('Content-Type: application/json');
echo json_encode($class_details);