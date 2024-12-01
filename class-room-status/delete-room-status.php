<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$class_status_id = $class_id = $class_time_id = $class_day_id = '';

$roomObj = new Room();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_status_id = clean_input($_POST['class-status-id']);
    $class_id = clean_input($_POST['class-id']);
    $class_time_id = clean_input($_POST['class-time-id']);
    $class_day_id = clean_input($_POST['class-day-id']);


    $roomObj->class_status_id = $class_status_id;
    $roomObj->class_id = $class_id;
    $roomObj->class_time_id = $class_time_id;
    $roomObj->class_day_id = $class_day_id;

    if($roomObj->deleteroomStatus()){
        echo json_encode(['status' => 'success', 'debug' => [
            'class_id deleted' => $roomObj->log_cid,
            'class_time_id deleted' => $roomObj->log_ctid,
            'class_day_id deleted' => $roomObj->log_cdid,
            'class_status_id deleted' => $roomObj->log_sid
        ]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;

}

?>
