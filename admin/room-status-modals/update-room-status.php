<?php
session_start();
require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');

//(class-details)room-id, subject-id, section-id, teacher-assigned, 
//(class-time)start-time, end-time, day

//var of semester cols
$semester_PK = '';
$semester = $school_year = '';


$check_starttime1 = $check_endtime1 = $opening_time = $closing_time = '';

//var to split the composite PK
$original_splitroom_PK = $splitsemester_PK = $original_room_code = $original_room_no = '';
//class id variables
$class_id = $subject_type = $start_time_1 = $end_time_1 = $day_id_1 = '';

//generalErr = For feed general inputs, generallErr1= sched 1 feed, generalErr2= sched 2 feed
$generalErr = $generalErr1 = $generalErr2 = $class_idErr = $subject_typeErr = $start_time_1Err = $end_time_1Err =  $day_id_1Err = $room_id_1Err = '';


$selected_room_1 = $room_id_1 = '';
//Split room_id variables holder (LR|1), and SPLIT UP OF room_id = room_code, room_no
$splitroom_PK_1 = $room_code = $room_no = $room_code_lec = $room_no_lec = $room_code_lab = $room_no_lab = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $splitsemester_PK = explode('|', $semester_PK);
    $semester = $splitsemester_PK[0];
    $school_year = $splitsemester_PK[1];

    $original_class_id = clean_input($_POST['original-class-id']);
    $original_subject_type = clean_input($_POST['original-subtype']);
    $original_class_day = clean_input($_POST['original-day-id']);

    $original_start_time = clean_input($_POST['original-class-id']);
    $original_end_time = clean_input($_POST['original-subtype']);

    $original_room_id = clean_input($_POST['original-room']);
    $original_splitroom_PK = explode(' ', $original_room_id);
    $original_room_code = $original_splitroom_PK[0];
    $original_room_no = $original_splitroom_PK[1];
    
    $selected_class = clean_input($_POST['class']);
    $selected_room_1 = clean_input($_POST['room-input-1']);
    // First check if class-id exists in POST
    
    // First check if class-id exists in POST
    if(empty($_POST['class-id'])){
        $class_idErr = 'Class is required.';
    }else if(!empty($selected_class) && empty($_POST['class-id'])){
        $class_idErr = 'Select a class from the dropdown.';
    }else{
        $class_id = clean_input($_POST['class-id']);
    }

    $subject_type = '';

    if(empty($_POST['subject-type'])){
        $generalErr = '<strong>SUBJECT TYPE REQUIRED!</strong><br>Atleast check <strong>1</strong> subject type.';
        $subject_typeErr = 'Invalid, missing input.';
    
    }else{
        $subject_type = clean_input($_POST['subject-type']);
    }

    $classSubtypeFound = $nonExistingType = $alternateSubtype = [];
    $i = $checkCount = $notexistingCount = 0;
    $hasLecUnit = $hasLabUnit = true;

    if(!empty($class_id) && !empty($subject_type)){

        $classSubtypeFound[] = $roomObj->checkClassSubtypeExisting($class_id, $subject_type);
        
        if($classSubtypeFound[0] === null){

            $nonExistingType[] = $subject_type;
            $alternateSubtype[] = $roomObj->alternateClassSubtype($class_id);
            
            $notexistingCount++; 
        }else{
            if (isset($classSubtypeFound[0]) && $classSubtypeFound[0]['lec_units'] == 0) {
                $hasLecUnit = false;
            }
            if (isset($classSubtypeFound[0]) && $classSubtypeFound[0]['lab_units'] == 0) {
                $hasLabUnit = false;
            }
        }

        if($notexistingCount == 1){//CHECKING IF
            $generalErr = '<strong>CLASS DETAIL NOT EXISTING!</strong><br>Chosen subject type';
                
            foreach($nonExistingType as $type){
               
                if ($type == 'LEC' && $hasLecUnit == false){
                    $generalErr .= ' <strong>"LEC"</strong> does not exist for ' . $class_id . '. This class only has a class for the subject type ' . $alternateSubtype[0];
                    $subject_typeErr = 'Uncheck the current subject type and instead choose ' . $alternateSubtype[0];
                }else if($type == 'LEC' && $hasLecUnit == true){
                    $generalErr .= ' <strong>"LEC"</strong> does not exist yet for ' . $class_id . '. Consider Adding a new class detail first to ' . $class_id . ' for class <strong>LEC</strong> be available for adding class schedule';
                    $subject_typeErr = 'Uncheck the current subject type LEC and instead choose ' . $alternateSubtype[0] . ' to proceed on adding class schedule for this class.';
                }
    
                if($type == 'LAB' && $hasLabUnit == false){
                    $generalErr .= ' <strong>"LAB"</strong> does not exist for ' . $class_id . '. This class only has a class for the subject type ' . $alternateSubtype[0];
                    $subject_typeErr = 'Uncheck the current subject type and instead choose ' . $alternateSubtype[0];
                }else if($type == 'LAB' && $hasLabUnit == true){
                    $generalErr .= ' <strong>"LAB"</strong> does not exist yet for ' . $class_id . '. Consider Adding a new class detail first to ' . $class_id . ' for class <strong>LAB</strong> be available for adding class schedule';
                    $subject_typeErr = 'Uncheck the current subject type LAB and instead choose ' . $alternateSubtype[0] . ' to proceed on adding class schedule for this class.';
                }
            }
             
        }else if($notexistingCount == 2){//error debug
            $generalErr = '<strong>ERROR, CLASS DETAIL NOT EXISTING!</strong><br>No subject type found for class id ' . $class_id;
            $subject_typeErr = 'Invalid';
        }

        if($notexistingCount > 0){
            echo json_encode([
                'status' => 'error',
                'generalErr' => $generalErr,
                'subject_typeErr' => $subject_typeErr
            ]);
            exit;
        }
    }
    

    //CHECK WHETHER EMPTY INPUT AND IF DROPDOWN OPTION SELECTED for room
    if(!empty($selected_room_1) && empty($_POST['room-id-1'])){
        $room_id_1Err = 'Select a class from the dropdown.';
    }else if(empty($_POST['room-id-1'])){
        $room_id_1Err = 'Room ID is required.';
    }else{
        $room_id_1 = clean_input($_POST['room-id-1']);
        $splitroom_PK_1 = explode('|', $room_id_1);
        $room_code = $splitroom_PK_1[0];
        $room_no = $splitroom_PK_1[1];
    }

    $start_time_1 =  clean_input($_POST['start-time']);
    $end_time_1 = clean_input($_POST['end-time']);

    if(empty($start_time_1)){
        $start_time_1Err = 'Start time is required is required.';
    }
    
    if(empty($end_time_1)){
        $end_time_1Err = 'End time is required.';
    }

    if(!empty($start_time_1) && !empty($end_time_1)){
        $check_starttime1 = DateTime::createFromFormat('H:i', $start_time_1);
        $check_endtime1 = DateTime::createFromFormat('H:i', $end_time_1);
        $opening_time = DateTime::createFromFormat('H:i', '06:00');
        $closing_time = DateTime::createFromFormat('H:i', '20:00');

        if($start_time_1 > $end_time_1){
            $start_time_1Err = 'Start time must be less than end time.';
        }
        elseif($start_time_1 == $end_time_1){
            $start_time_1Err = 'Start time should not be the same as the end time.';
            $end_time_1Err = 'End time should not be the same as the start time.';
        }

        if($check_starttime1 < $opening_time){
            $start_time_1Err = 'Start time should not be before the opening time 6:00AM .';
        }
        if($check_endtime1 > $closing_time){
            $end_time_1Err = 'End time should not go past the closing time 8:00PM.';
        }

    }


    $day_id_1 = clean_input($_POST['day-id']);

    if(empty($day_id_1)){
        $generalErr1 = '<strong>DAY REQUIRED!</strong><br>Atleast check <strong>1</strong> class Day.';
        $day_id_1Err = 'INVALID, missing input.';
    }

    
    // // FOR DEBUGGING
    // $generalErr = '<strong>ERROR, DEBUGGING!</strong><br>determiner-type: ' . $determiner_type;

    // echo json_encode([//debbuggging
    //     'status' => 'error',
    //     'generalErr' => $generalErr,
    //     'generalErr1' => $generalErr1,
    //     'class_idErr' => $class_idErr,
    //     'subject_typeErr' => $subject_typeErr,
    //     'start_time_1Err' => $start_time_1Err,
    //     'end_time_1Err' => $end_time_1Err,
    //     'day_id_1Err' => $day_id_1Err,
    //     'room_id_1Err' => $room_id_1Err,
    // ]);
    // exit;


    // If there are validation errors, return them as JSON
    if(!empty($generalErr) || !empty($generalErr1) || !empty($class_idErr) || !empty($subject_typeErr) || !empty($start_time_1Err) || !empty($end_time_1Err) || !empty($day_id_1Err) || !empty($room_id_1Err)){
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'generalErr1' => $generalErr1,
            'class_idErr' => $class_idErr,
            'subject_typeErr' => $subject_typeErr,
            'start_time_1Err' => $start_time_1Err,
            'end_time_1Err' => $end_time_1Err,
            'day_id_1Err' => $day_id_1Err,
            'room_id_1Err' => $room_id_1Err
        ]);
        exit;
    }
    
    $roomObj->class_id = $class_id;
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;
    
    $roomObj->original_class_id = $original_class_id;
    $roomObj->original_subject_type = $original_subject_type;
    $roomObj->original_class_day = $original_class_day;

    $occupied_class_id = $occupied_day_name = $occupied_start_time = $occupied_end_time = $occupied_room = $classExist = [];
    $checker1 = $checker2 = 0;
  
    $roomObj->day_id = $day_id_1;
    $roomObj->start_time = $start_time_1;
    $roomObj->end_time = $end_time_1;
    $roomObj->subject_type = $subject_type;
    $roomObj->room_code = $room_code;
    $roomObj->room_no = $room_no;
    

    $classExist = $roomObj->checkClassDayAlreadyExist($original_class_id, $original_subject_type, $original_class_day);
    if($classExist != null){
        $occupied_class_id[] = $classExist[0];
        $occupied_day_name[] = $classExist[1];
        $occupied_start_time[] = $classExist[2];
        $occupied_end_time[] = $classExist[3];
        $occupied_room[] = $classExist[4];
        $checker1++;
        
    }else{
        $existingTime = $roomObj->checkExistingClassTime($original_class_id, $original_subject_type, $original_class_day);
        if($existingTime != null){
            $occupied_class_id[] = $existingTime[0];
            $occupied_day_name[] = $existingTime[1];
            $occupied_start_time[] = $existingTime[2];
            $occupied_end_time[] = $existingTime[3];
            $occupied_room[] = $existingTime[4];
            $checker2++;
        }
    }
    
    // //debugging template
    // $generalErr .= '<strong>ERROR,DEBUG!</strong><br>check1: ' . $checker1 ;
    // $subject_typeErr = 'Invalid';
    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr,
    //     'subject_typeErr' => $subject_typeErr
    // ]);
    // exit;


    if($checker1 > 0){
        if($checker1 == 1){
            $generalErr1 = '<strong>CLASS DAY SCHEDULE ALREADY EXIST!</strong><br>This class is already scheduled on '; 
        }
        
        $index = 0;      
        foreach($occupied_class_id as $class_id){
            $generalErr1 .= $occupied_day_name[$index]  . ' on ' . $occupied_room[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
            $index++;
        }

        echo json_encode([
            'status' => 'error',
            'generalErr1' => $generalErr1,
            'day_id_1Err' => $day_id_1Err
        ]);
        exit;
        
    }else{
        if($checker2 > 0){
            
            if($checker == 1){
                $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with class ID '; 
            }else{
                $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with multiple classes: <br>'; 
            }
    
            $index = 0;      
            foreach($occupied_class_id as $class_id){
                $generalErr1 .= $class_id . ' on ' . $occupied_room[$index] . ' scheduled by ' . $occupied_day_name[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
                $index++;
            }
            
            echo json_encode([
                'status' => 'error',
                'generalErr1' => $generalErr1
            ]);
            exit;
        }
    }

    
    if($roomObj->updateScheduleDay()){   
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;
}

?>
