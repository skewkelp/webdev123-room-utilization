<?php
session_start();
require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//(class-details)room-id, subject-id, section-id, teacher-assigned, 
//(class-time)start-time, end-time, day


$semester_PK = '';
$semester = $school_year = '';


$original_class_id = $original_subject_id = $class_day_id = $class_time_id = $original_start_time = $original_end_time = $original_day_id = '';

$splitclass_PK = $splitoriginalclass_PK = '';
//this var refers to room_

$class_status_id = $class_id = '';

$class_PK = $start_time = $end_time = $day_id = '';
$class_PKErr = $start_timeErr = $end_timeErr = $day_idErr = '';


$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $splitsemester_PK = explode('|', $semester_PK);
    $semester = $splitsemester_PK[0];
    $school_year = $splitsemester_PK[1];


    $original_class_id = clean_input($_POST['original-class-id']);
    $splitoriginalclass_PK = explode('|', $original_class_id);
    $original_class_id = $splitoriginalclass_PK[0];
    $original_subject_id = $splitoriginalclass_PK[1];

     // First check if class-id exists in POST
    if(empty($_POST['class-id'])){
        $class_PKErr = 'Class is required.';
    } else {
        $class_PK = clean_input($_POST['class-id']);
        $splitclass_PK = explode('|', $class_PK);
        
        // Check if we got both values after splitting
        if(count($splitclass_PK) !== 2) {
            $class_PKErr = 'Invalid class selection format';
        } else {
            $class_id = $splitclass_PK[0];
            $subject_id = $splitclass_PK[1];
        }
    }

    $class_day_id = clean_input($_POST['class-day-id']);    
    $class_time_id = clean_input($_POST['original-class-time-id']);
    $original_start_time = clean_input($_POST['original-start-time']);
    $original_end_time = clean_input($_POST['original-end-time']);
    $original_day_id = isset($_POST['original-day-id']) ? $_POST['original-day-id'] : [];


    $start_time = clean_input($_POST['start-time']);
    $end_time = clean_input($_POST['end-time']);
    $day_id = clean_input($_POST['day-id']);


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
    //     // Only check new days being added
    //     if($day_id != $original_day_id) {
    //         // Check if this class_time already exists on the selected day
    //         if($roomObj->classTimeExistsOnDay($day_id, $class_time_id, )) {
    //             $day_idErr = "This class time is already scheduled on: " . getDayName($day_id);
    //         }
    //     }
          
    // }   
   


    // if(!empty($day_id)){
    //     // Check each selected day for existing schedules
    //     foreach($day_id as $selected_day) {
    //         // Skip check for the original day that's being edited
    //         if($selected_day != $original_day_id) {
    //             if($roomObj->classTimeExistsOnDay($class_time_id, $selected_day,  $class_day_id) == true) {
    //                 $day_idErr = "This class already has a schedule on " . getDayName($selected_day);
    //                 break;
    //             }
    //         }
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
    // else{
    
    //     echo json_encode(['status' => 'logicerror']);
    //     exit;
    // }


    $roomObj->class_status_id = $class_status_id;
    $roomObj->class_day_id = $class_day_id;
    $roomObj->original_day_id = $original_day_id;
    
    $roomObj->class_time_id = $class_time_id;
    $roomObj->class_id = $class_id;
    $roomObj->subject_id = $subject_id;
    $roomObj->start_time = $start_time;
    $roomObj->end_time = $end_time; 
    $roomObj->day_id = $day_id;
    
    $existingTime = $roomObj->checkExistingClassTime($original_class_id, $original_subject_id);
    if($existingTime != null){
        $occupied_class_id = $existingTime[0];
        $occupied_day_name[] = $existingTime[1];
        $occupied_start_time[] = $existingTime[2];
        $occupied_end_time[] = $existingTime[3];

        $existing_classErr = 'This schedule overlaps with class ID ' .  $class_id . ' on ' . $occupied_day_name . ' from ' . $occupied_start_time . ' to ' . $occupied_end_time; 
        
        $start_timeErr = "Invalid.";
        $end_timeErr = "Invalid.";
        $day_idErr = "Invalid."; 

        echo json_encode([
            'status' => 'error',
            'existing_classErr' => $existing_classErr,
            'start_timeErr' => $start_timeErr,
            'end_timeErr' => $end_timeErr,
            'day_idErr' => $day_idErr
        ]);
        exit;

    }
    
    $class_time_id = $roomObj->updateClassTime();

    $class_day_id = $roomObj->updateClassDay();
    
    
    // // First check if original day is still selected
    // foreach($day_id as $selected_day) {
    //     if($selected_day == $original_day_id) {
    //         $originalDayStillSelected = true;
    //     } else {
    //         $newDaysSelected[] = $selected_day;
    //     }
    // }


    // $newRoom = $newSubject = $newSection = $newTeacher = false;
    // if($originalDayStillSelected) { // Original day is still selected

    //     if(count($newDaysSelected) > 0) { // Original day plus new days selected
    //         $newDay = true;
    //         if( $original_class_id != $class_id && $original_subject_id != $subject_id){
                
    //             $roomObj->subject_id = $subject_id;
                
    //                 $roomObj->updateClassDetails();
    //             }
    //         }
            
    //         if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time
    //             $roomObj->start_time = $start_time;
    //             $roomObj->end_time = $end_time;
    //             if($newTime == true){
    //                 $roomObj->updateClassTime();
    //             }
    //         }
            
    //         foreach($newDaysSelected as $selected_day){
    //             $roomObj->class_time_id = $class_time_id;
    //             $roomObj->day_id = $selected_day;
    //             $class_day_id = $roomObj->insertClassDay();
            
    //             $roomObj->class_day_id = $class_day_id;
    //             $roomObj->insertStatus();
    //         }

    //     }else{// Only original day selected
    //         if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
    //             $newClass = true;
    //             $roomObj->room_id = $room_id; 
    //             $roomObj->subject_id = $subject_id;
    //             $roomObj->section_id = $section_id;
    //             $roomObj->teacher_assigned = $teacher_assigned;
    //             if($newClass == true){
    //                 $roomObj->updateClassDetails();
    //             }
    //         }
            
    //         if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time
    //             $newTime = true;
    //             $roomObj->start_time = $start_time;
    //             $roomObj->end_time = $end_time;
    //             if($newTime == true){
    //                 $roomObj->updateClassTime();
    //             }
    //         }
            
    //     }

    // }else{// New days
    //     if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
    //         $newClass = true;
    //         $roomObj->room_id = $room_id; 
    //         $roomObj->subject_id = $subject_id;
    //         $roomObj->section_id = $section_id;
    //         $roomObj->teacher_assigned = $teacher_assigned;
    //         if($newClass == true){
    //             $roomObj->updateClassDetails();
    //         }
    //     }
        
    //     if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-tim
    //         $roomObj->start_time = $start_time;
    //         $roomObj->end_time = $end_time;




    //         if($newTime == true){
    //             $roomObj->updateClassTime();
    //         }
    //     }
        
    //     foreach($newDaysSelected as $selected_day){
    //         $roomObj->class_time_id = $class_time_id;
    //         $roomObj->day_id = $selected_day;
    //         $roomObj->insertClassDay();

    //         $roomObj->insertStatus();
    //     } selected 

    // }
    

    if(TRUE){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;

    
}

                // if($roomObj->checkExistingClassDayID() != null){
                //     $newDay = true;
                //     $class_day_id = $roomObj->checkExistingClassDayID();
                // }

                // if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time

                //     if($roomObj->checkExistingClassTimeID() != null){
                //     //Checks if there is an existing class time id of the new start-time and end-time
                //         $newTime = true;
                //         $class_time_id = $roomObj->checkExistingClassTimeID();
                //     }
                   
                // }

                // if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
                //     if($roomObj->checkExistingClassDetails() != null){
                //         $newClass = true;
                //         $class_id = $roomObj->checkExistingClassDetails();
                //     }

                //     if($room_id != $original_room_id){
                //         $newRoom = true;
                //     }
                //     if($subject_id != $original_subject_id){
                //         $newSubject = true;
                //     }
                //     if($section_id != $original_section_id){
                //         $newSection = true;
                //     }
                //     if($teacher_assigned != $original_teacher_assigned){
                //         $newTeacher = true;
                //     }
                // }
           
    
        
    
    // $roomObj->newRoom = $newRoom;
    // $roomObj->newSubject = $newSubject;
    // $roomObj->newSection = $newSection;
    // $roomObj->newTeacher = $newTeacher;


    // $roomObj->newDay = $newDay;
    // $roomObj->class_day_id = $class_day_id;
  
    // $roomObj->newTime = $newTime;
    // $roomObj->class_time_id = $class_time_id;

    // $roomObj->newClass = $newClass;
    // $roomObj->class_id = $class_id;


    // if($newClass == true){
    //     $roomObj->updateClassDetails();
    // }

   

    // if($newDay == true){
    //     foreach($day_id as $selected_day){
    //         $roomObj->day_id = $selected_day;
    //         $roomObj->updateClassDay();
    //     }
    // }
?>
