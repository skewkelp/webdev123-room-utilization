<?php
    require_once('../tools/functions.php');
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $semester_id = $school_year = '';
    if(!empty($_GET['semesterID'] && !empty($_GET['schoolYear']))){
        $semester_id = clean_input($_GET['semesterID']);
        $school_year = clean_input($_GET['schoolYear']);

        $classes = $roomObj->fetchClassesOption($semester_id, $school_year);
    }else{
        $classes = $roomObj->fetchClassesOption();
    }

    header('Content-Type: application/json');
    echo json_encode($classes);

?>
