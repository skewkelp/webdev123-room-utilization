<?php

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//(class-details)room-id, subject-id, section-id, teacher-assigned, 
//(class-time)start-time, end-time, day
//
//this var refers to room_
$class_status_id = $class_id = $class_time_id = $class_day_id = '';
$room_id = $subject_id = $section_id = $teacher_assigned = $start_time = $end_time = $day_id = $original_day_id = '';
$room_idErr = $subject_idErr = $section_idErr = $teacher_assignedErr = $start_timeErr = $end_timeErr = '';
$day_idErr = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class_status_id = clean_input($_POST['class-status-id']);
    $class_id = clean_input($_POST['class-id']);
    $class_time_id = clean_input($_POST['class-time-id']);
    $class_day_id = clean_input($_POST['class-day-id']);

    $original_room_id = clean_input($_POST['original-room-id']);
    $original_subject_id = clean_input($_POST['original-subject-id']);
    $original_section_id = clean_input($_POST['original-section-id']);
    $original_teacher_assigned = clean_input($_POST['original-teacher-assigned']);

    $original_start_time = clean_input($_POST['original-start-time']);
    $original_end_time = clean_input($_POST['original-end-time']);
    $original_day_id = clean_input($_POST['original-day-id']);

    $room_id = clean_input($_POST['room-id']);
    $subject_id = clean_input($_POST['subject-id']);
    $section_id = clean_input($_POST['section-id']);
    $teacher_assigned = clean_input($_POST['teacher-assigned']);
    $start_time = clean_input($_POST['start-time']);
    $end_time = clean_input($_POST['end-time']);
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





    if(!empty($day_id)){
        $dayCount = count($day_id);
        $dayIndex = 0; 
        $conflictDays = [];
        $conflictCount = 0;

        foreach($day_id as $selected_day) {
            // Only check new days being added
            if($selected_day != $original_day_id) {
                // Check if this class_time already exists on the selected day
                if($roomObj->classTimeExistsOnDay($selected_day, $class_time_id, )) {
                    $conflictDays[] = getDayName($selected_day);
                    $conflictCount++;
                }
            }
            $dayIndex++;
        }
        
        if($conflictCount > 0){
            $day_idErr = "This class time is already scheduled on: " . implode(', ', $conflictDays);
        }

    }   
   


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
    // else{
    
    //     echo json_encode(['status' => 'logicerror']);
    //     exit;
    // }


    $roomObj->class_status_id = $class_status_id;
    $roomObj->class_day_id = $class_day_id;
    $roomObj->original_day_id = $original_day_id;
    
    
    $originalDayStillSelected = false;
    $newDaysSelected = [];
    
    // First check if original day is still selected
    foreach($day_id as $selected_day) {
        if($selected_day == $original_day_id) {
            $originalDayStillSelected = true;
        } else {
            $newDaysSelected[] = $selected_day;
        }
    }

        
     
    $newTime = $newClass = $newDay = false;
    $newRoom = $newSubject = $newSection = $newTeacher = false;
    if($originalDayStillSelected) { // Original day is still selected

        if(count($newDaysSelected) > 0) { // Original day plus new days selected
            $newDay = true;
            if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
                $newClass = true;
                $roomObj->room_id = $room_id; 
                $roomObj->subject_id = $subject_id;
                $roomObj->section_id = $section_id;
                $roomObj->teacher_assigned = $teacher_assigned;
                if($newClass == true){
                    $roomObj->updateClassDetails();
                }
            }
            
            if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time
                $newTime = true;
                $roomObj->start_time = $start_time;
                $roomObj->end_time = $end_time;
                if($newTime == true){
                    $roomObj->updateClassTime();
                }
            }
            
            foreach($newDaysSelected as $selected_day){
                $roomObj->class_time_id = $class_time_id;
                $roomObj->day_id = $selected_day;
                $class_day_id = $roomObj->insertClassDay();
            
                $roomObj->class_day_id = $class_day_id;
                $roomObj->insertStatus();
            }

        }else{// Only original day selected
            if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
                $newClass = true;
                $roomObj->room_id = $room_id; 
                $roomObj->subject_id = $subject_id;
                $roomObj->section_id = $section_id;
                $roomObj->teacher_assigned = $teacher_assigned;
                if($newClass == true){
                    $roomObj->updateClassDetails();
                }
            }
            
            if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time
                $newTime = true;
                $roomObj->start_time = $start_time;
                $roomObj->end_time = $end_time;
                if($newTime == true){
                    $roomObj->updateClassTime();
                }
            }
            
        }

    }else{// New days
        if($room_id != $original_room_id || $subject_id != $original_subject_id || $section_id != $original_section_id || $teacher_assigned != $original_teacher_assigned){
            $newClass = true;
            $roomObj->room_id = $room_id; 
            $roomObj->subject_id = $subject_id;
            $roomObj->section_id = $section_id;
            $roomObj->teacher_assigned = $teacher_assigned;
            if($newClass == true){
                $roomObj->updateClassDetails();
            }
        }
        
        if($start_time != $original_start_time || $end_time != $original_end_time){// Check if start-time and end-time is different from original start-time and end-time
            $newTime = true;
            $roomObj->start_time = $start_time;
            $roomObj->end_time = $end_time;
            if($newTime == true){
                $roomObj->updateClassTime();
            }
        }
        
        foreach($newDaysSelected as $selected_day){
            $roomObj->class_time_id = $class_time_id;
            $roomObj->day_id = $selected_day;
            $roomObj->insertClassDay();

            $roomObj->insertStatus();
        } selected 

    }
    

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
