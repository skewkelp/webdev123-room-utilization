<?php

require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');

$class_id = '';
$subject_type = $class_day = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_id = clean_input($_POST['class-id']);
    $subject_type = clean_input($_POST['subject-type']);
    $class_day = clean_input($_POST['class-day']);

    $roomObj->class_id = $class_id;
    $roomObj->subject_type = $subject_type;
    $roomObj->day_id = $class_day;

    if($roomObj->deleteClassSchedule()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete room status']);
    }
    exit;

}

?>
