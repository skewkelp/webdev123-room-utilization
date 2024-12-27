<?php
require_once('../tools/functions.php');

require_once('../classes/schedule.class.php');

$scheduleObj = new Schedule();

$selected_room = $splitRoom = $room_code = $room_no = '';

$selected_room = clean_input($_GET['selectedRoom']);
if(empty($selected_room)){
    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'message' => 'Selected room cannot be empty.']);
    exit;
}else{
    $splitRoom = explode(' ', $selected_room);
    $room_code = $splitRoom[0];
    $room_no = $splitRoom[1];
}


$schedules = $scheduleObj->getAllSchedules($room_code, $room_no);

header('Content-Type: application/json');
echo json_encode($schedules);