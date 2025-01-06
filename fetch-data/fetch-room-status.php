<?php
require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();
$class_id = $_GET['classID'];
$subject_type = $_GET['subType'];
$class_day = $_GET['classDay'];

$class_status = $roomObj->fetchroomstatusRecord($class_id, $subject_type, $class_day);

header('Content-Type: application/json');
echo json_encode($class_status);

// $semester_id = $school_year = '';
// if(!empty($_GET['semesterID'] && !empty($_GET['schoolYear']))){
//     $semester_id = clean_input($_GET['semesterID']);
//     $school_year = clean_input($_GET['schoolYear']);

//     $class_status = $roomObj->fetchroomstatusRecord($class_id, $subject_type, $class_day, $semester_id, $school_year);
// }else{
//     $class_status = $roomObj->fetchroomstatusRecord($class_id, $subject_type, $class_day);
// }
