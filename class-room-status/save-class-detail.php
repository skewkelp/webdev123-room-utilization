<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$semester_PK = '';
$semester = $school_year = '';
//this var refers to the name of the section, room, subject, and teacher selected in the input field
$selected_section = $selected_room = $selected_subject = $selected_teacher = '';

//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$class_id = $section_id = $room_id = $subject_id = $subject_type = $teacher_assigned = '';
$generalErr = $class_idErr = $section_idErr = $room_idErr = $subject_idErr = $subject_typeErr = $teacher_assignedErr = '';

$times = 0;

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    error_log("POST data received: " . print_r($_POST, true));
    
    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $split_PK = explode('|', $semester_PK);
    $semester = $split_PK[0];
    $school_year = $split_PK[1];

    $selected_subject = clean_input($_POST['subject']);
    $selected_section = clean_input($_POST['section']);
    $selected_teacher = clean_input($_POST['teacher']);

    $selected_subject = explode(' ', $selected_subject)[0];

    error_log("Selected values: section=$selected_section, room=$selected_room, subject=$selected_subject, teacher=$selected_teacher, semester=$semester_PK, school_year=$school_year");
    
    $class_id = clean_input($_POST['class-id']);

    $section_id = clean_input($_POST['section-id']);
   
    if(!empty($selected_section) && empty($section_id)){
        $section_idErr = 'Select a section from the dropdown.';
    }else if(empty($selected_section)){
        $section_idErr = 'Section is required.';
    }else{//split section id from CS|1|A to the variables
        $split_sectionID = explode('|', $section_id);
        $course_abbr = $split_sectionID[0];
        $year_level = $split_sectionID[1];
        $section = $split_sectionID[2];
    }


    $subject_id = clean_input($_POST['subject-id']);

    // if(empty($_POST['subject-type'])){
    //     $subject_typeErr = 'Subject Type is required.';
    // }else{
    //     $subject_type = $_POST['subject-type'];
    //     if(count($subject_type) > 1){
    //         $times = 2;
    //     }else{
    //         $times = 1;
    //     }
    //         $unitDetails = [];
    //     foreach($subject_type as $checkType){
    //         $unitDetails = $roomObj->checkSubjectType($subject_id, $checkType);
    //     }


    //     if($unitDetails == null){
    //         $generalErr = '<strong>SUBJECT TYPE INVALID!</strong> <br> This subject has no ';
    //         $subject_typeErr = 'Invalid':
    //         if($times >= 1){
    //             generalErr .= $unitDetails;
    //         }else if (){
    //             generalErr .= 'lec';

    //         }
    //         echo json_encode([
    //             'status' => 'error',
    //             'generalErr' => $generalErr,
    //             'subject_typeErr' => $subject_typeErr
    //         ]);
    //         exit;
        
    //     }
    // }

    $teacher_assigned = clean_input($_POST['teacher-assigned']);

    error_log("ID values: class_id=$class_id, section_id=$section_id, room_id=$room_id, subject_id=$subject_id, teacher_assigned=$teacher_assigned");
    
    if(empty($class_id)){
        $class_idErr = 'Class ID is required.';
    }else if(!preg_match('/^[A-Z]{3,4}\d{6}$/', $class_id)){
        $class_idErr = 'Class ID must be in the format: 3-4 uppercase letters followed by 6 digits (e.g., ABC123456 or ABCD123456).';
    }

    if(!empty($selected_subject) && empty($subject_id)){
        $subject_idErr = 'Select a subject from the dropdown.';
    } else if(empty($selected_subject)){
        $subject_idErr = 'Subject is required.';
    }

    
    if(!empty($selected_teacher) && empty($teacher_assigned)){
        $teacher_assignedErr = 'Select a teacher from the dropdown.';
    } else if(empty($selected_teacher)){
        $teacher_assignedErr = 'Teacher is required.';
    }


    if(!empty($class_idErr) || !empty($subject_idErr) || !empty($subject_typeErr) || !empty($section_idErr) || !empty($teacher_assignedErr)){
        echo json_encode([
            'status' => 'error',
            'class_idErr' => $class_idErr,
            'subject_idErr' => $subject_idErr,
            'subject_typeErr' => $subject_typeErr,
            'section_idErr' => $section_idErr,
            'teacher_assignedErr' => $teacher_assignedErr
        ]);
        exit;
    }

    $roomObj->class_id = $class_id;
    $roomObj->subject_id = $subject_id;
    
    $roomObj->course_abbr = $course_abbr;
    $roomObj->year_level = $year_level;
    $roomObj->section = $section;
    
    $roomObj->teacher_assigned = $teacher_assigned;
    
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;
    
    
    foreach($subject_type as $type){
        $roomObj->subject_type = $type;

   
        if($roomObj->checkExistingClassDetailsPK($class_id) != null){
            $existing_details = $roomObj->checkExistingClassDetailsPK($class_id);
            $generalErr = '<strong>EXISTING CLASS ID!</strong> <br> A class with class ID ' . $existing_details['class_id'] . ' already exists for section ' . $existing_details['section_'] . ' with subject ' . $existing_details['subject_'];
            $class_idErr = 'Class ID should be unique for each section class.';
        
            echo json_encode([
                'status' => 'error',
                'generalErr' => $generalErr,
                'class_idErr' => $class_idErr
            ]);
            exit;
        }

        $noExclude = null;

        $existing_class = $roomObj->checkSubjectSectionExisting($noExclude);
        if($existing_class != null){
            $generalErr = '<strong>EXISTING DATA!</strong> <br> This subject already exists for this section with class ID ' . $existing_class;
            $subject_idErr = 'Cannot add the same subject for the same section.';
        
            echo json_encode([
                'status' => 'error',
                'generalErr' => $generalErr,
                'subject_idErr' => $subject_idErr
            ]);
            exit;
        }

        //check condition for class id, if false then same class id can be added
        $match_classDetails = $roomObj->checkConditionClassDetailPK();
        if($match_classDetails == null){
            $existing_classID = $roomObj->checkClassIDExisting($class_id);
            if($existing_classID != null){
                $generalErr = '<strong>EXISTING CLASS ID!</strong> <br>This class ID already exist for section ' . $existing_classID . ' with a subject';
                $class_idErr = 'Class ID should be unique for each section class.';
            
                echo json_encode([
                    'status' => 'error',
                    'generalErr' => $generalErr,
                    'class_idErr' => $class_idErr
                ]);
                exit;
            }

        }

        if($match_classDetails != null){    
            if($class_id != $match_classDetails['class_id']){
                $generalErr = '<strong>EXISTING CLASS ID!</strong> <br>This class matched with ' . $match_classDetails['class_id'] . ' with subject ' . $match_classDetails['subject_id'] . ' for section ' . $match_classDetails['section_name'];
                $class_idErr = 'Class ID should match with the same subject for this section.';
            
                echo json_encode([
                    'status' => 'error',
                    'generalErr' => $generalErr,
                    'class_idErr' => $class_idErr
                ]);
                exit;
            }
        }


    }   
    $counter = 0;
    $success = $error = 0;
 
    while($counter < $times){
        
        if($roomObj->insertClassDetails()){
            $success++;
            $typeholder = $subject_type[$counter];
        } else {
            $error++;
        }
        $counter++;
    }

    if($success > 0 && $error === 0){
        echo json_encode(['status' => 'success', 'message' => "$success classes (" . implode(", ", $typeholder) . ") added successfully."]);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;
    
}

?>
