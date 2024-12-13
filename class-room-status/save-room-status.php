<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//var of semester cols
$semester_PK = '';
$semester = $school_year = '';

//var to split the composite PK
$splitclass_PK = $splitsemester_PK = '';

$subject_id = $class_id = '';

$class_PK = $start_time = $end_time = '';
$class_PKErr = $start_timeErr = $end_timeErr = $day_idErr = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $splitsemester_PK = explode('|', $semester_PK);
    $semester = $splitsemester_PK[0];
    $school_year = $splitsemester_PK[1];

    // First check if class-id exists in POST
    if(empty($_POST['class-id'])){
        $class_PKErr = 'Class is required.';
    }else{
        $class_PK = clean_input($_POST['class-id']);
        $splitclass_PK = explode('|', $class_PK);
        
        // Check if we got both values after splitting
        if(count($splitclass_PK) !== 2) {
            $class_PKErr = 'Invalid class selection format';
        }else{
            $class_id = $splitclass_PK[0];
            $subject_id = $splitclass_PK[1];
        }
    }

    $start_time = clean_input($_POST['start-time']);
    $end_time = clean_input($_POST['end-time']);
    $day_id = isset($_POST['day-id']) ? $_POST['day-id'] : [];
    
    if(empty($class_PK)){
        $class_PKErr = 'Class is required.';
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
    if(!empty($class_PKErr) || !empty($start_timeErr) || !empty($end_timeErr) || !empty($day_idErr)){
        echo json_encode([
            'status' => 'error',
            'class_PKErr' => $class_PKErr,
            'start_timeErr' => $start_timeErr,
            'end_timeErr' => $end_timeErr,
            'day_idErr' => $day_idErr
        ]);
        exit;
    }
    
    $roomObj->class_id = $class_id;
    $roomObj->subject_id = $subject_id;
    $roomObj->start_time = $start_time;
    $roomObj->end_time = $end_time;
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;

    $occupied_class_id = $occupied_start_time = $occupied_end_time = [];
    $checker = 0;
    foreach($day_id as $selected_day){
        $roomObj->day_id = $selected_day;
        $existingTime = $roomObj->checkExistingClassTime();
        if($existingTime != null){
            $occupied_class_id[] = $existingTime[0];
            $occupied_day_name[] = $existingTime[1];
            $occupied_start_time[] = $existingTime[2];
            $occupied_end_time[] = $existingTime[3];
            $checker++;
        }
    }

    if($checker > 0){

        if($checker == 1){
            $existing_classErr = 'This schedule overlaps with class ID '; 
        }else{
            $existing_classErr = 'This schedule overlaps with multiple classes: ' . "\n"; 
        }
        
        $index = 0;      
        foreach($occupied_class_id as $class_id){
            $existing_classErr .= $class_id . ' on ' . $occupied_day_name[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . "\n";
            $index++;
        }

        echo json_encode([
            'status' => 'error',
            'existing_classErr' => $existing_classErr
        ]);
        exit;
    }

    $class_time_id = $roomObj->insertClassTime();
    foreach($day_id as $selected_day){
        $roomObj->class_time_id = $class_time_id;
        $roomObj->day_id = $selected_day;


        $class_day_id = $roomObj->insertClassDay();
    }

    

    if(TRUE){   
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;
}

?>
