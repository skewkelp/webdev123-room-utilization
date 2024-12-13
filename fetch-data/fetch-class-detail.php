<?php
require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();
$class_id = $_GET['classId'];
$subject_id = $_GET['subjectId'];
$class_details = $roomObj->fetchclassDetailsRecord($class_id, $subject_id);

header('Content-Type: application/json');
echo json_encode($class_details);