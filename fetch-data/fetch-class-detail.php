<?php
    require_once('../tools/functions.php');
    require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();
$class_id = $_GET['classId'];
$subject_type = $_GET['subType'];

$class_details = $roomObj->fetchclassDetailsRecord($class_id, $subject_type);


header('Content-Type: application/json');
echo json_encode($class_details);

// $semester_id = $school_year = '';
// if(!empty($_GET['semesterID'] && !empty($_GET['schoolYear']))){
//     $semester_id = clean_input($_GET['semesterID']);
//     $school_year = clean_input($_GET['schoolYear']);

//     $class_details = $roomObj->fetchclassDetailsRecord($class_id, $subject_type, $semester_id, $school_year);
// }else{
//     $class_details = $roomObj->fetchclassDetailsRecord($class_id, $subject_type);
// }
