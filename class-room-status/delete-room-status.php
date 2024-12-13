<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$class_status_id = '';
$class_time_id = $class_day_id = '';

// $class_PK = $subject_id = '';
// $class_id = '';
$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_status_id = clean_input($_POST['class-status-id']);
    $class_day_id = clean_input($_POST['class-day-id']);
    $class_time_id = clean_input($_POST['class-time-id']);
    // $class_PK = clean_input($_POST['class-id']);

    // $splitclass_PK = explode('|', $class_PK);
    // $class_id = $splitclass_PK[0];
    // $subject_id = $splitclass_PK[1];
    


    $roomObj->class_status_id = $class_status_id;
    // $roomObj->class_id = $class_id;
    // $roomObj->subject_id = $subject_id;
    $roomObj->class_day_id = $class_day_id;
    $roomObj->class_time_id = $class_time_id;
    // $roomObj->class_day_id = $class_day_id;

    if($roomObj->deleteroomStatus()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete room status']);
    }
    exit;

}

?>
