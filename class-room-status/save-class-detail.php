<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$semester_PK = '';
$semester = $school_year = '';
//this var refers to the name of the section, room, subject, and teacher selected in the input field
$selected_section = $selected_room = $selected_subject = $selected_teacher = '';

//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$class_id = $section_id = $room_id = $subject_id= $teacher_assigned = '';
$existing_classErr = $class_idErr = $section_idErr = $room_idErr = $subject_idErr = $teacher_assignedErr = '';

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
    $selected_room = clean_input($_POST['room']);

    $selected_subject = explode(' ', $selected_subject)[0];

    error_log("Selected values: section=$selected_section, room=$selected_room, subject=$selected_subject, teacher=$selected_teacher, semester=$semester_PK, school_year=$school_year");
    
    $class_id = clean_input($_POST['class-id']);
    $section_id = clean_input($_POST['section-id']);
    $room_id = clean_input($_POST['room-id']);
    $subject_id = clean_input($_POST['subject-id']);
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

    if(!empty($selected_section) && empty($section_id)){
        $section_idErr = 'Select a section from the dropdown.';
    } else if(empty($selected_section)){
        $section_idErr = 'Section is required.';
    }

    if(!empty($selected_teacher) && empty($teacher_assigned)){
        $teacher_assignedErr = 'Select a teacher from the dropdown.';
    } else if(empty($selected_teacher)){
        $teacher_assignedErr = 'Teacher is required.';
    }

    if(!empty($selected_room) && empty($room_id)){
        $room_idErr = 'Select a room from the dropdown.';
    } else if(empty($selected_room)){
        $room_idErr = 'Room is required.';
    }

    if(!empty($class_idErr) || !empty($subject_idErr) || !empty($section_idErr) || !empty($room_idErr) || !empty($teacher_assignedErr)){
        echo json_encode([
            'status' => 'error',
            'class_idErr' => $class_idErr,
            'subject_idErr' => $subject_idErr,
            'section_idErr' => $section_idErr,
            'teacher_assignedErr' => $teacher_assignedErr,
            'room_idErr' => $room_idErr
        ]);
        exit;
    }

    $roomObj->subject_code = $selected_subject;
    $roomObj->class_id = $class_id;
    $roomObj->subject_id = $subject_id;
    $roomObj->section_id = $section_id;
    $roomObj->teacher_assigned = $teacher_assigned;
    $roomObj->room_id = $room_id;
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;

    
    
    if($roomObj->checkExistingClassDetailsPK($class_id) != null){
        $existing_details = $roomObj->checkExistingClassDetailsPK($class_id);
        $existing_classErr = 'A class with class ID ' . $existing_details['class_id'] . ' already exists for section ' . $existing_details['section_'] . ' with subject ' . $existing_details['subject_'];
        $class_idErr = 'Class ID should be unique for each section class.';
       
        echo json_encode([
            'status' => 'error',
            'existing_classErr' => $existing_classErr,
            'class_idErr' => $class_idErr
        ]);
        exit;
    }

    $noExclude = null;

    $existing_class = $roomObj->checkSubjectSectionExisting($noExclude);
    if($existing_class != null){
        $existing_classErr = 'This subject already exists for this section with class ID ' . $existing_class;
        $subject_idErr = 'Cannot add the same subject for the same section.';
       
        echo json_encode([
            'status' => 'error',
            'existing_classErr' => $existing_classErr,
            'subject_idErr' => $subject_idErr
        ]);
        exit;
    }

    //check condition for class id, if false then same class id can be added
    $match_classDetails = $roomObj->checkConditionClassDetailPK();
    if($match_classDetails == null){
        $existing_classID = $roomObj->checkClassIDExisting($class_id);
        if($existing_classID != null){
            $existing_classErr = 'This class ID already exist for section ' . $existing_classID . ' with a subject';
            $class_idErr = 'Class ID should be unique for each section class.';
        
            echo json_encode([
                'status' => 'error',
                'existing_classErr' => $existing_classErr,
                'class_idErr' => $class_idErr
            ]);
            exit;
        }

    }

    if($match_classDetails != null){    
        if($class_id != $match_classDetails['class_id']){
            $existing_classErr = 'This class matched with ' . $match_classDetails['class_id'] . ' with subject ' . $match_classDetails['subject_code'] . ' for section ' . $match_classDetails['section_name'];
            $class_idErr = 'Class ID should match with the same subject for this section.';
        
            echo json_encode([
                'status' => 'error',
                'existing_classErr' => $existing_classErr,
                'class_idErr' => $class_idErr
            ]);
            exit;
        }
    }



    
    if($roomObj->insertClassDetails()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }
    exit;


}

?>
