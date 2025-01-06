<?php

require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');

$class_id = $subject_id = '';
$semester_id = $school_year = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_id = clean_input($_POST['class-id']);
    $subtype_id = clean_input($_POST['subtype-id']);
    $semester_id = clean_input($_POST['semester-id']);
    $school_year = clean_input($_POST['school-year']);

    $roomObj->class_id = $class_id;
    $roomObj->subject_type = $subtype_id;
    $roomObj->semester = $semester_id;
    $roomObj->school_year = $school_year;

    
    //// debugging
    // echo json_encode(['status' => 'error', 'message' => "Failed to delete room status: semester; $semester_id; school_year: $school_year"]);

    if($roomObj->deleteClassDetails()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Failed to delete class detail: semester; $semester_id; school_year: $school_year "]);
    }
    exit;

}

?>
