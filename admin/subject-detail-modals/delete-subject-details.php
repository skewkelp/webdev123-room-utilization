<?php

require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');

$subject_code = $prospectus_id = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $subject_code = clean_input($_GET['subjectID']);
    $prospectus_id = clean_input($_GET['prospectusID']);

    $roomObj->subject_code = $subject_code;
    $roomObj->prospectus_id = $prospectus_id;

    // echo json_encode(['status' => 'error', 'message' => $prospectus_id]);

    if($roomObj->deleteSubjectDetails()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when deleting the subject details.']);
    }
    exit;
}

?>
