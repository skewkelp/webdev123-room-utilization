<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//(class-details)room-id, subject-id, section-id, teacher-assigned, 
//(class-time)start-time, end-time, day
//
//this var refers to room_
$room_id = $subject_id = $section_id = $teacher_assigned = $start_time = $end_time = $day_id = '';
$room_idErr = $subject_idErr = $section_idErr = $teacher_assignedErr = $start_timeErr = $end_timeErr = $day_idErr = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $room_id = clean_input($_POST['room-id']);
    $subject_id = clean_input($_POST['subject-id']);
    $section_id = clean_input($_POST['section-id']);
    $teacher_assigned = clean_input($_POST['teacher-assigned']);
    $start_time = clean_input($_POST['start-time']);
    $end_time = clean_input($_POST['end-time']);
    // $day_id = isset($_POST['day-id']) ? $_POST['day-id'] : [];
    $day_id = isset($_POST['day-id']) ? $_POST['day-id'] : [];
    
    if(empty($room_id)){
        $room_idErr = 'Room is required.';
    } 

    if(empty($subject_id)){
        $subject_idErr = 'Subject is required.';
    }

    if(empty($section_id)){
        $section_idErr = 'Section is required.';
    }

    if(empty($teacher_assigned)){
        $teacher_assignedErr = 'Teacher is required.';
    }

    if(empty($start_time)){
        $start_timeErr = 'Start time is required is required.';
    }
    
    if(empty($end_time)){
        $end_timeErr = 'End time is required.';
    }
    
    if(empty($day_id)){
        $day_idErr = 'Class Days is required.';
    }

    if(!empty($start_time) && !empty($end_time)){
        if($start_time > $end_time){
            $start_timeErr = "Start time must be less than end time.";
        }
        elseif($start_time == $end_time){
            $start_timeErr = "Start time should not be the same as end time.";
            $end_timeErr = "End time should not be the same as start time.";
        }
    }

    
    // if(!empty($day_id)){
    //     $dayCount = count($day_id);
    //     $dayIndex = 0; 
    //     $conflictDays = [];
    //     $conflictCount = 0;

    //     foreach($day_id as $selected_day) {
    //         // Only check new days being added
            
    //             // Check if this class_time already exists on the selected day
    //         if($roomObj->classTimeExistsOnDay($selected_day, $class_time_id)) {
    //             $conflictDays[] = getDayName($selected_day);
    //             $conflictCount++;
    //         }
            
    //         $dayIndex++;
    //     }
        
    //     if($conflictCount > 0){
    //         $day_idErr = "This class time is already scheduled on: " . implode(', ', $conflictDays);
    //     }

    // }   

    // If there are validation errors, return them as JSON
    if(!empty($room_idErr) || !empty($subject_idErr) || !empty($section_idErr)  || !empty($teacher_assignedErr) || !empty($start_timeErr) || !empty($end_timeErr) || !empty($day_idErr)){
        echo json_encode([
            'status' => 'error',
            'room_idErr' => $room_idErr,
            'subject_idErr' => $subject_idErr,
            'section_idErr' => $section_idErr,
            'teacher_assignedErr' => $teacher_assignedErr,
            'start_timeErr' => $start_timeErr,
            'end_timeErr' => $end_timeErr,
            'day_idErr' => $day_idErr
        ]);
        exit;
    }

    $roomObj->room_id = $room_id;
    $roomObj->subject_id = $subject_id;
    $roomObj->section_id = $section_id;
    $roomObj->teacher_assigned = $teacher_assigned;
    $roomObj->start_time = $start_time;
    $roomObj->end_time = $end_time;
    $roomObj->day_id = $day_id;


    $class_id = $class_time_id = '';
    $newTime = $newClass = $newDay = true;

    if($roomObj->checkExistingClassDetails() != null){//not new class
        $newClass = false;
        $class_id = $roomObj->checkExistingClassDetails();

    }else{
        $class_id = $roomObj->insertClassDetails();
    }

    if($roomObj->checkExistingClassTimeID() != null){//not new time
        $newTime =false;
        $class_time_id = $roomObj->checkExistingClassTimeID();
    }else{
        $class_time_id = $roomObj->insertClassTime();
    }

    $roomObj->newClass = $newClass;
    $roomObj->newTime = $newTime;
    $roomObj->newDay = $newDay;

    $roomObj->class_id = $class_id;
    $roomObj->class_time_id = $class_time_id;

    foreach($day_id as $selected_day){
        $roomObj->class_time_id = $class_time_id;
        $roomObj->day_id = $selected_day;
        $class_day_id = $roomObj->insertClassDay();
    }

    

    

    
    // if($roomObj->checkExistingClassDetails() != null){
    //     //Checks if there is an existing class time id of the new start-time and end-time
    //     $newClass = true;
    //     $class_id = $roomObj->checkExistingClassDetails();
    // }

    // if($roomObj->checkExistingClassTimeID() != null){
    //     //Checks if there is an existing class time id of the new start-time and end-time
    //     $newTime = true;
    //     $class_time_id = $roomObj->checkExistingClassTimeID();
    // }

    
    // $roomObj->newClass = $newClass;
    // $roomObj->class_id = $class_id; 

    // $roomObj->newTime = $newTime;
    // $roomObj->class_time_id = $class_time_id;
    

    if(TRUE){
        echo json_encode(['status' => 'success', 'debug' => [
            'class_id created' => $roomObj->log_cid,
            'class_time_id created' => $roomObj->log_ctid,
            'Day_id inserted' => $roomObj->log_day,
            'class_day_id created' => $roomObj->log_cdid
        ]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;
}

?>
