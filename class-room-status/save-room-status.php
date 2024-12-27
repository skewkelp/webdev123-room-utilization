<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

//var of semester cols
$semester_PK = '';
$semester = $school_year = '';

//var to split the composite PK
$splitclass_PK = $splitsemester_PK = '';

$check_starttime1 = $check_endtime1 = $check_starttime2 = $check_endtime2 = $opening_time = $closing_time = '';

//class id variables
$class_id = $subject_type = $start_time_1 = $end_time_1 = $start_time_2 = $end_time_2 = $day_id_1 = $day_id_2 =  '';

//generalErr = For feed general inputs, generallErr1= sched 1 feed, generalErr2= sched 2 feed
$generalErr = $generalErr1 = $generalErr2 = $class_idErr = $subject_typeErr = $start_time_1Err = $end_time_1Err = $start_time_2Err = $end_time_2Err = $day_id_1Err = $day_id_2Err = $room_id_1Err = $room_id_2Err = '';

$selected_class = $selected_room_1 = $selected_room_2 = $room_id_1 = $room_id_2 = '';
//Split room_id variables holder (LR|1), and SPLIT UP OF room_id = room_code, room_no
$splitroom_PK_1 = $splitroom_PK_2 = $room_code = $room_no = $room_code_lec = $room_no_lec = $room_code_lab = $room_no_lab = '';

$determiner_type = '';
//determine-type? true : false
//false = LEC OR LAB, true = LEC AND LAB
//room_id_1/room_id_2 = LR|1

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $splitsemester_PK = explode('|', $semester_PK);
    $semester = $splitsemester_PK[0];
    $school_year = $splitsemester_PK[1];
    
    $selected_class = clean_input($_POST['class']);
    
    $selected_room_1 = clean_input($_POST['room-input-1']);
    $selected_room_2 = clean_input($_POST['room-input-2']);
    
    $determiner_type = clean_input($_POST['determiner-type']);

    
    // First check if class-id exists in POST
    if(empty($_POST['class-id'])){
        $class_idErr = 'Class is required.';
    }else if(!empty($selected_class) && empty($_POST['class-id'])){
        $class_idErr = 'Select a class from the dropdown.';
    }else{
        $class_id = clean_input($_POST['class-id']);
    }
    
    $subject_type = [];
 
    if(empty($_POST['subject-type'])){
        $generalErr = '<strong>SUBJECT TYPE REQUIRED!</strong><br>Atleast check <strong>1</strong> subject type.';
        $subject_typeErr = 'Invalid, missing input.';
    
    }else{
        foreach($_POST['subject-type'] as $type){
            $subject_type[] = clean_input($type);
        }
    }

    

    $classSubtypeFound = $nonExistingType = $alternateSubtype = [];
    $i = $checkCount = $notexistingCount = 0;
    $hasLecUnit = $hasLabUnit = true;
    
    // //debugging template
    // $generalErr .= '<strong>ERROR,DEBUG!</strong><br>Var: ' . $type;
    // $subject_typeErr = 'Invalid';
    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr,
    //     'subject_typeErr' => $subject_typeErr
    // ]);
    // exit;

    //BSCS123212 SIPP125
    if(!empty($class_id) && !empty($subject_type)){

        foreach ($subject_type as $type) {
            $classSubtypeFound[] = $roomObj->checkClassSubtypeExisting($class_id, $type);
            
            if($classSubtypeFound[$i] === null){

                $nonExistingType[] = $type;
                $alternateSubtype[] = $roomObj->alternateClassSubtype($class_id);
                
                $notexistingCount++; 
            }else{
                if (isset($classSubtypeFound[$i]) && $classSubtypeFound[$i]['lec_units'] == 0) {
                    $hasLecUnit = false;
                }
                if (isset($classSubtypeFound[$i]) && $classSubtypeFound[$i]['lab_units'] == 0) {
                    $hasLabUnit = false;
                }
            }

            $i++;
        }  

        if($notexistingCount == 1){//CHECKING IF
            $generalErr = '<strong>CLASS DETAIL NOT EXISTING!</strong><br>Chosen subject type';
                
            foreach($nonExistingType as $type){
                if(count($subject_type) == 1){
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

                }else if(count($subject_type) > 1){
                    if ($type == 'LEC' && $hasLecUnit == true){
                        $generalErr .= ' <strong>"LEC"</strong> does not exist yet for ' . $class_id . '. Consider Adding a new class detail first to ' . $class_id . ' for class <strong>LEC</strong> be available for adding class schedule';
                        $subject_typeErr = 'Uncheck LEC to proceed on adding class schedule for LAB.';
                    
                    }else if ($type == 'LEC' && $hasLecUnit == false){
                        $generalErr .= ' <strong>"LEC"</strong> does not exist for ' . $class_id . '. This class only has a class for the subject type ' . $alternateSubtype[0] . '.';
                        $subject_typeErr = 'Uncheck the current subject type and choose ' . $alternateSubtype[0];
                    }
        
                    if($type == 'LAB' && $hasLabUnit == true){
                        $generalErr .= ' <strong>"LAB"</strong> does not exist yet for ' . $class_id . '. Consider Adding a new class detail first to ' . $class_id . ' for class <strong>LAB</strong> be available for adding class schedule';
                        $subject_typeErr = 'Uncheck LAB to proceed on adding class schedule for LEC.';
                    }else if($type == 'LAB' && $hasLabUnit == false){
                        $generalErr .= ' <strong>"LAB"</strong> does not exist for ' . $class_id . '. This class only has a class for the subject type ' . $alternateSubtype[0] . '.';
                        $subject_typeErr = 'Uncheck the current subject type and choose ' . $alternateSubtype[0];
                    }

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
    if($determiner_type == 'false'){
        if(!empty($selected_room_1) && empty($_POST['room-id-1'])){
            $room_id_1Err = 'Select a room from the dropdown.';
        }else if(empty($_POST['room-id-1'])){
            $room_id_1Err = 'Room ID is required.';
        }else{
            $room_id_1 = clean_input($_POST['room-id-1']);
            $splitroom_PK_1 = explode('|', $room_id_1);
            $room_code = $splitroom_PK_1[0];
            $room_no = $splitroom_PK_1[1];
        }

    }else if($determiner_type == 'true'){
        //room lec error feedback, initialization
        if(!empty($selected_room_1) && empty($_POST['room-id-1'])){
            $room_id_1Err = 'Select a room from the dropdown.';
        }else if(empty($_POST['room-id-1'])){
            $room_id_1Err = 'Room ID for the Lecture Class is required .';
        }else{
            $room_id_1 = clean_input($_POST['room-id-1']);
            $splitroom_PK_1 = explode('|', $room_id_1);
            $room_code_lec = $splitroom_PK_1[0];
            $room_no_lec = $splitroom_PK_1[1];
        }

        //room lab error feedback, initialization
        if(!empty($selected_room_2) && empty($_POST['room-id-2'])){
            // $errorVar = $_POST['room-id-2'];
            $room_id_2Err = 'Select a room from the dropdown.d';
        
        }else if(empty($_POST['room-id-2'])){
            $room_id_2Err = 'Room ID for the Lab Class is required.';
        }else{
            $room_id_2 = clean_input($_POST['room-id-2']);
            $splitroom_PK_2 = explode('|', $room_id_2);
            $room_code_lab = $splitroom_PK_2[0];
            $room_no_lab = $splitroom_PK_2[1];

        }   
    }
     
    if($determiner_type == 'false'){
        $start_time_1 =  clean_input($_POST['start-time-1']);
        $end_time_1 = clean_input($_POST['end-time-1']);

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

    }else if($determiner_type == 'true'){
        $end_time_1 = clean_input($_POST['end-time-1']);
        $start_time_1 = clean_input($_POST['start-time-1']);

        
        if(empty($start_time_1)){
            $start_time_1Err = 'Start time for the class LEC is required.';
        }
        
        if(empty($end_time_1)){
            $end_time_1Err = 'End time for the class LEC is required.';
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

        $start_time_2 = clean_input($_POST['start-time-2']);
        $end_time_2 = clean_input($_POST['end-time-2']);

        if(empty($start_time_2)){
            $start_time_2Err = 'Start time for the class LAB is required.';
        }
        if(empty($end_time_2)){
            $end_time_2Err = 'End time for the class LAB is required.';
        }

        if(!empty($start_time_2) && !empty($end_time_2)){
            $check_starttime2 = DateTime::createFromFormat('H:i', $start_time_2);
            $check_endtime2 = DateTime::createFromFormat('H:i', $end_time_2);
            $opening_time = DateTime::createFromFormat('H:i', '06:00');
            $closing_time = DateTime::createFromFormat('H:i', '20:00');

            if($start_time_2 > $end_time_2){
                $start_time_2Err = 'Start time must be less than end time.';
            }
            elseif($start_time_2 == $end_time_2){
                $start_time_2Err = 'Start time should not be the same as the end time.';
                $end_time_2Err = 'End time should not be the same as the start time.';
            }

            if($check_starttime2 < $opening_time){
                $start_time_2Err = 'Start time should not be before the opening time 6:00AM .';
            }
            if($check_endtime2 > $closing_time){
                $end_time_2Err = 'End time should not go past the closing time 8:00PM.';
            }


        }
    }


    if($determiner_type == 'false'){
        $day_id_1 = isset($_POST['day-id-1']) ? $_POST['day-id-1'] : [];

        if(empty($day_id_1)){
            $generalErr1 = '<strong>DAY REQUIRED!</strong><br>Atleast check <strong>1</strong> class Day.';
            $day_id_1Err = 'INVALID, missing input.';
        }

    }else if($determiner_type == 'true'){
        $day_id_1 = isset($_POST['day-id-1']) ? $_POST['day-id-1'] : [];

        if(empty($day_id_1)){
            $generalErr1 = '<strong>DAY REQUIRED!</strong><br>Atleast check <strong>1</strong> class Day.';
            $day_id_1Err = 'Class Days is required for LEC class.';
        }

        $day_id_2 = isset($_POST['day-id-2']) ? $_POST['day-id-2'] : [];

        if(empty($day_id_2)){
            $generalErr2 = '<strong>DAY REQUIRED!</strong><br>Atleast check <strong>1</strong> class Day.';
            $day_id_2Err = 'Class Days is required for LAB class.';
        }
    }
    
    // // FOR DEBUGGING
    // $generalErr = '<strong>ERROR, DEBUGGING!</strong><br>determiner-type: ' . $determiner_type;

    // echo json_encode([//debbuggging
    //     'status' => 'error',
    //     'generalErr' => $generalErr,
    //     'generalErr1' => $generalErr1,
    //     'generalErr2' => $generalErr2,
    //     'class_idErr' => $class_idErr,
    //     'subject_typeErr' => $subject_typeErr,
    //     'start_time_1Err' => $start_time_1Err,
    //     'end_time_1Err' => $end_time_1Err,
    //     'start_time_2Err' => $start_time_2Err,
    //     'end_time_2Err' => $end_time_2Err,
    //     'day_id_1Err' => $day_id_1Err,
    //     'day_id_2Err' => $day_id_2Err,
    //     'room_id_1Err' => $room_id_1Err,
    //     'room_id_2Err' => $room_id_2Err
    // ]);
    // exit;


    // If there are validation errors, return them as JSON
    if($determiner_type == 'false' && (!empty($generalErr) || !empty($generalErr1) || !empty($class_idErr) || !empty($subject_typeErr) || !empty($start_time_1Err) || !empty($end_time_1Err) || !empty($day_id_1Err) || !empty($room_id_1Err))){
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
    }else if($determiner_type == 'true' && (!empty($generalErr) || !empty($generalErr1) || !empty($generalErr2) || !empty($class_idErr) || !empty($subject_typeErr) || !empty($start_time_1Err) || !empty($end_time_1Err) || !empty($start_time_2Err) || !empty($end_time_2Err) || !empty($day_id_1Err) || !empty($day_id_2Err) || !empty($room_id_1Err) || !empty($room_id_2Err))){
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'generalErr1' => $generalErr1,
            'generalErr2' => $generalErr2,
            'class_idErr' => $class_idErr,
            'subject_typeErr' => $subject_typeErr,
            'start_time_1Err' => $start_time_1Err,
            'end_time_1Err' => $end_time_1Err,
            'start_time_2Err' => $start_time_2Err,
            'end_time_2Err' => $end_time_2Err,
            'day_id_1Err' => $day_id_1Err,
            'day_id_2Err' => $day_id_2Err,
            'room_id_1Err' => $room_id_1Err,
            'room_id_2Err' => $room_id_2Err
        ]);
        exit;
    }
    
    $roomObj->class_id = $class_id;
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;

    $occupied_class_id = $occupied_day_name = $occupied_start_time = $occupied_end_time = $occupied_room = $classExist = [];
    $checker1 = $checker2 = 0;

    $room_codeArr = [];
    $room_noArr = [];
    
    $room_codeArr = [$room_code_lec, $room_code_lab];
    $room_noArr = [$room_no_lec, $room_no_lab];

    foreach($day_id_1 as $selected_day){
        $roomObj->day_id = $selected_day;
        $roomObj->start_time = $start_time_1;
        $roomObj->end_time = $end_time_1;
        $roomObj->subject_type = $subject_type[0];
        
        if($determiner_type == 'false'){
            $roomObj->room_code = $room_code;
            $roomObj->room_no = $room_no;
        }else{
            $roomObj->room_code = $room_codeArr[0];
            $roomObj->room_no = $room_noArr[0];
        }

        $classExist = $roomObj->checkClassDayAlreadyExist();
        if($classExist != null){
            $occupied_class_id[] = $classExist[0];
            $occupied_day_name[] = $classExist[1];
            $occupied_start_time[] = $classExist[2];
            $occupied_end_time[] = $classExist[3];
            $occupied_room[] = $classExist[4];
            $checker1++;
            
        }else{
            $existingTime = $roomObj->checkExistingClassTime();
            if($existingTime != null){
                $occupied_class_id[] = $existingTime[0];
                $occupied_day_name[] = $existingTime[1];
                $occupied_start_time[] = $existingTime[2];
                $occupied_end_time[] = $existingTime[3];
                $occupied_room[] = $existingTime[4];
                $checker2++;
            }
        }
    }

    // //debugging template
    // $test = '';
    // $generalErr .= '<strong>ERROR,DEBUG!</strong><br>check1: ' . $checker1 . ' check2:' . $checker2 . '<br>';
    // $subject_typeErr = 'Invalid';
    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr,
    //     'subject_typeErr' => $subject_typeErr
    // ]);
    // exit;
    $firstDisplay = false;

    if($checker1 > 0){
        $firstDisplay = true;
        if($determiner_type == 'false'){
            if($checker1 == 1){
                $generalErr1 = '<strong>CLASS DAY SCHEDULE ALREADY EXIST!</strong><br>This class is already scheduled on '; 
            }
        }else if($determiner_type == 'true'){
            if($checker1 == 1){
                $generalErr1 = '<strong>CLASS LEC SCHEDULED DAY ALREADY EXIST!</strong><br>This class is already scheduled on '; 
                $day_id_1Err = 'Class can only be scheduled ONCE on the same day.';
            }else{
                $generalErr1 = '<strong>CLASS LEC SCHEDULED DAY ALREADY EXIST!</strong><br>This class is already scheduled on multiple days: <br>'; 
                $day_id_1Err = 'Class can only be scheduled ONCE on the same day.';

            }
        }
        
        $index = 0;      
        foreach($occupied_class_id as $class_id){
            $generalErr1 .= $occupied_day_name[$index]  . ' on ' . $occupied_room[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
            $index++;
        }

        if($determiner_type == 'false'){
            echo json_encode([
                'status' => 'error',
                'generalErr1' => $generalErr1,
                'day_id_1Err' => $day_id_1Err
            ]);
            exit;
        }
    }else{
        if($checker2 > 0){
            
            if($determiner_type == 'false'){//Error feed when single subtype chosen
                if($checker == 1){
                    $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with class ID '; 
                }else{
                    $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with multiple classes: <br>'; 
                }
            }else if($determiner_type == 'true'){//Error feed when 2 subtypes chosen
                if($checker == 1){
                    $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule for LEC overlaps with class ID '; 
                }else{
                    $generalErr1 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule for LEC overlaps with multiple classes: <br>'; 
                }
            }
    
            $index = 0;      
            foreach($occupied_class_id as $class_id){
                $generalErr1 .= $class_id . ' on ' . $occupied_room[$index] . ' scheduled by ' . $occupied_day_name[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
                $index++;
            }
            
            if($determiner_type == 'false'){
                echo json_encode([
                    'status' => 'error',
                    'generalErr1' => $generalErr1
                ]);
                exit;
            }
        }
    }

    $occupied_class_id = $occupied_day_name = $occupied_start_time = $occupied_end_time = $occupied_room = $classExist = [];
    $checker1 = $checker2 = 0;

    if($determiner_type == 'true'){
        foreach($day_id_2 as $selected_day){
            $roomObj->start_time = $start_time_2;
            $roomObj->end_time = $end_time_2;
            $roomObj->day_id = $selected_day;
            $roomObj->room_code = $room_codeArr[1];
            $roomObj->room_no = $room_noArr[1];
            $roomObj->subject_type = $subject_type[1];

            $classExist = $roomObj->checkClassDayAlreadyExist();
            if($classExist != null){
                $occupied_class_id[] = $classExist[0];
                $occupied_day_name[] = $classExist[1];
                $occupied_start_time[] = $classExist[2];
                $occupied_end_time[] = $classExist[3];
                $occupied_room[] = $classExist[4];
                $checker1++;
            }else{
                $existingTime = $roomObj->checkExistingClassTime();
                if($existingTime != null){
                    $occupied_class_id[] = $existingTime[0];
                    $occupied_day_name[] = $existingTime[1];
                    $occupied_start_time[] = $existingTime[2];
                    $occupied_end_time[] = $existingTime[3];
                    $occupied_room[] = $existingTime[4];
                    $checker2++;
                }
            }
            
            $conflictingDay = [];
            $conflictingDay = array_intersect($day_id_1, $day_id_2);
            // Check for overlap with LEC time
            if ($start_time_2 <= $end_time_1 && $end_time_2 >= $start_time_1 && $room_id_1 == $room_id_2 && !empty($conflictingDay)){
                $generalErr2 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule for LAB overlaps with the LEC schedule on the same room.';
                $conflictingDayList = implode(', ', $conflictingDay);
                 
                $day_id_1Err = $day_id_2Err = 'Days conflicting ' . $conflictingDayList;
                
                echo json_encode([
                    'status' => 'error', 
                    'generalErr2' => $generalErr2,
                    'day_id_1Err' => $day_id_1Err,
                    'day_id_2Err' => $day_id_2Err
                ]);
                exit;
            }
        }

        if($checker1 > 0){
            if($checker1 == 1){
                $generalErr2 = '<strong>CLASS LAB SCHEDULED DAY ALREADY EXIST!</strong><br>This class is already scheduled on '; 
                $day_id_2Err = 'Class can only be scheduled ONCE on the same day.';
            }else{
                $generalErr2 = '<strong>CLASS LAB SCHEDULED DAY ALREADY EXIST!</strong><br>This class is already scheduled on multiple days: <br>'; 
                $day_id_2Err = 'Class can only be scheduled ONCE on the same day.';
            }

            
            $index = 0;      
            foreach($occupied_class_id as $class_id){
                $generalErr2 .= $occupied_day_name[$index]  . ' on ' . $occupied_room[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
                $index++;
            }

            if($firstDisplay == true){
                echo json_encode([
                    'status' => 'error',
                    'generalErr1' => $generalErr1,
                    'day_id_1Err' => $day_id_1Err,
                    'generalErr2' => $generalErr2,
                    'day_id_2Err' => $day_id_2Err
                ]);
                exit;
            }
    
            echo json_encode([
                'status' => 'error',
                'generalErr2' => $generalErr2,
                'day_id_2Err' => $day_id_2Err
            ]);
            exit;


        }else{
            if($checker2 > 0){
                if($checker2 == 1){
                    $generalErr2 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with class ID '; 
                }else{
                    $generalErr2 = '<strong>OVERLAPPING SCHEDULE!</strong><br>This schedule overlaps with multiple classes: <br>'; 
                }
                
                $index = 0;      
                foreach($occupied_class_id as $class_id){
                    $generalErr2 .= $class_id . ' on ' . $occupied_room[$index] . ' scheduled by ' . $occupied_day_name[$index] . ' from ' . $occupied_start_time[$index] . ' to ' . $occupied_end_time[$index] . '<br>';
                    $index++;
                }
        
                echo json_encode([
                    'status' => 'error',
                    'generalErr2' => $generalErr2
                ]);
                exit;
            }
        }

    }

    $countIndex = 0;

    foreach($day_id_1 as $selected_day){
        $roomObj->day_id = $selected_day;
        $roomObj->start_time = $start_time_1;
        $roomObj->end_time = $end_time_1;
        
        if($determiner_type == 'false'){
            $roomObj->room_code = $room_code;
            $roomObj->room_no = $room_no;
            $roomObj->subject_type = $subject_type[0];
        }else{
            $roomObj->room_code = $room_codeArr[0];
            $roomObj->room_no = $room_noArr[0];
            $roomObj->subject_type = $subject_type[0];
        }
    
        $roomObj->insertScheduleDay();
    }


    if($determiner_type == 'true'){
        foreach($day_id_2 as $selected_day){
            $roomObj->day_id = $selected_day;
            $roomObj->start_time = $start_time_2;
            $roomObj->end_time = $end_time_2;    
        
            $roomObj->room_code = $room_codeArr[1];
            $roomObj->room_no = $room_noArr[1];
            $roomObj->subject_type = $subject_type[1];
        
            $roomObj->insertScheduleDay();
        }
    }


    if(TRUE){   
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;
}



 ////with subject type checking static, if determiner is false,
            // if($splitroom_PK_1[0] == 'LEC'){
            //     $room_code_lec = $splitroom_PK_1[0];
            //     $room_no_lec = $splitroom_PK_1[1];
                
            // }elseif($splitroom_PK[0] == 'LAB'){
            //     $room_code_lab = $splitroom_PK_1[0];
            //     $room_no_lab = $splitroom_PK_1[1];
            // }else{//debugging error
            //     $generalErr = '<strong>ADMIN FORM ERROR!</strong><br>Debugging Error mismatched, .';
            //     echo json_encode([
            //         'status' => 'error',
            //         'generalErr' => $generalErr
            //     ]);
            //     exit;
            // }
?>
