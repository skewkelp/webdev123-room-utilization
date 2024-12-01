<?php
require_once('../classes/room-status.class.php');

$roomObj = new Room();
$class_status_id = $_GET['id'];
$class_status = $roomObj->fetchroomstatustRecord($class_status_id);

header('Content-Type: application/json');
echo json_encode($class_status);