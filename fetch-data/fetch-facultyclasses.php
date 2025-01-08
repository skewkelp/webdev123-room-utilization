<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $semester_id = $_GET['semesterID'];
    $school_year = $_GET['schoolYear'];
    $user_id = $_GET['userID'];

    $roomObj->semester = $semester_id;
    $roomObj->school_year = $school_year;
    $roomObj->faculty_id = $user_id;

    $class = $roomObj->showFacultyClassSchedules();

    header('Content-Type: application/json');
    echo json_encode($class);

?>
