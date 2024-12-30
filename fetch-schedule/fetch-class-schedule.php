<?php
require_once('../tools/functions.php');

require_once('../classes/schedule.class.php');

$scheduleObj = new Schedule();

$semester_pk = $splitSemester = $selected_room = $splitRoom = $room_code = $room_no = '';

$selected_room = clean_input($_GET['selectedRoom']);
$semester_pk = clean_input($_GET['semesterPK']);

if(empty($selected_room) && empty($semester_pk)){

    if(empty($selected_room) && !empty($semester_pk)){
        header('Content-Type: application/json');
        echo json_encode(['error' => true, 'message' => 'Selected room cannot be empty.']);
        exit;
    }

    if(!empty($selected_room) && empty($semester_pk)){
        header('Content-Type: application/json');
        echo json_encode(['error' => true, 'message' => 'Semester_pk is empty.']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'message' => 'Selected Room and semester_pk is empty.']);
    exit;
}else{
    $splitRoom = explode(' ', $selected_room);
    $room_code = $splitRoom[0];
    $room_no = $splitRoom[1];

    $splitSemester = explode('|', $semester_pk);
    $semester = $splitSemester[0];
    $school_year = $splitSemester[1];
}


$schedules = $scheduleObj->getAllSchedules($room_code, $room_no, $semester, $school_year);

header('Content-Type: application/json');
echo json_encode($schedules);