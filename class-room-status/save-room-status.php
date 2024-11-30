<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//(class-details)room-id, subject-id, section-id, teacher-assigned, 
//(class-time)start-time, end-time, day
//
//this var refers to room_
$room_id = $subject_id = $section_id = $teacher_assigned = $start_time = $end_time = $day_id = '';
$room_idErr = $subject_idErr = $section_idErr = $teacher_assignedErr = $start_timeErr = $end_timeErr = $day_idErr = '';

$roomObj = new Room();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $room_id = clean_input($_POST['room-id']);
    $subject_id = clean_input($_POST['subject-id']);
    $section_id = clean_input($_POST['section-id']);
    $teacher_assigned = clean_input($_POST['teacher-assigned']);
    $start_time = clean_input($_POST['start-time']);
    $end_time = clean_input($_POST['end-time']);
    $day_id = clean_input($_POST['day-id']);
  

    if(empty($name)){
        $nameErr = 'Room name is required.';
    } else if ($roomObj->roomnameExists($name)){
        $nameErr = 'Room name already exists.';
    }

    if(empty($type)){
        $typeErr = 'Room type is required.';
    }
    

    // If there are validation errors, return them as JSON
    if(!empty($nameErr) || !empty($typeErr)){
        echo json_encode([
            'status' => 'error',
            'nameErr' => $nameErr,
            'typeErr' => $typeErr
        ]);
        exit;
    }

    $roomObj->room_name = $name;
    $roomObj->room_type = $type;
    
    if($roomObj->addRoom()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
    }
    exit;

    // if(empty($nameErr) && empty($typeErr)){
    //     $roomObj->room_name = $name;
    //     $roomObj->room_type = $type;

    //     if($roomObj->addRoom()){
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
    //     }
    //     exit;
    // }
    
}

?>
