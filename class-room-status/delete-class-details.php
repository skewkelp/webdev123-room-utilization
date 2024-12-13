<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$class_id = $subject_id = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_id = clean_input($_POST['class-id']);
    $subject_id = clean_input($_POST['subject-id']);

    $roomObj->class_id = $class_id;
    $roomObj->subject_id = $subject_id;

    if($roomObj->deleteClassDetails()){
        echo json_encode(['status' => 'success', 'debug' => [
            'class_id deleted' => $roomObj->log_cid,
        ]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when deleting the class details.']);
    }
    exit;

}

?>
