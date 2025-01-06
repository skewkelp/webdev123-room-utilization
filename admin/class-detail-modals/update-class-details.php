<?php

require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');


$original_class_id = $original_subject_id = $original_subject_type = $original_section_id = '';
$original_course_abbr = $original_year_level = $original_section = '';

//this var refers to the name of the section, room, subject, and teacher selected in the input field
$selected_section = $selected_subject = $selected_teacher = '';

//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$class_id = $section_id = $subject_id = $subject_type = $teacher_assigned = '';
$generalErr = $class_idErr = $section_idErr = $subject_idErr = $subject_typeErr = $teacher_assignedErr = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['subject'] == 'List Empty, Please add more subjects for this prospectus.'){
        $generalErr = '<strong>SUBJECT LIST EMPTY!</strong> <br> To edit a class detail, Pls add more subjects on the selected prospectus. Pls exit the form.';
        $subject_idErr = 'INVALID';

        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_idErr' => $subject_idErr
        ]);
        exit;
    }
    
    error_log("POST data received: " . print_r($_POST, true));
    $original_class_id = clean_input($_POST['original-class-id']);
    $original_subject_id = clean_input($_POST['original-subject-id']);
    $original_subject_type = clean_input($_POST['original-subtype-id']);

    
    if(!empty($_POST['original-section-id'])){
        $original_section_id = clean_input($_POST['original-section-id']);
        $split_original_sectionID = explode('|', $original_section_id);
        $original_course_abbr = $split_original_sectionID[0];
        $original_year_level = $split_original_sectionID[1];
        $original_section = $split_original_sectionID[2];
    }
    
    error_log("Original Selected Id: classid=$original_class_id, subjectid=$original_subject_id, sectionid=$original_section_id, teacher=$selected_teacher");

    $selected_subject = clean_input($_POST['subject']);
    $selected_section = clean_input($_POST['section']);
    $selected_teacher = clean_input($_POST['teacher']);

    $selected_subject = explode(' ', $selected_subject)[0];

    error_log("Selected values: section=$selected_section, subject=$selected_subject, teacher=$selected_teacher");
    
    $class_id = clean_input($_POST['class-id']);
    
    $subject_id = clean_input($_POST['subject-id']);

    $times = 0;
    if(empty($_POST['subject-type'])){
        $subject_typeErr = 'Subject Type is required.';
    }else{
        $subject_type = $_POST['subject-type'];
        $unitDetails = '';

        $unitDetails = $roomObj->checkSubjectType($subject_id, $subject_type);
    
    }

    if($unitDetails != null){
        $generalErr = '<strong>SUBJECT TYPE INVALID!</strong> <br> This subject has ';
        if($subject_type == 'LAB'){
            $generalErr .= '0 lab units but has ' . $unitDetails['lec_units'] . ' lec units registered on the subject.'; 
            $subject_typeErr = 'Uncheck subject type LEC and check subject type <strong>LAB</strong> instead';
        }else if ($subject_type == 'LEC'){
            $generalErr .= '0 lec units but has ' . $unitDetails['lab_units'] . ' lab units registered on the subject.';
            $subject_typeErr = 'Uncheck subject type LAB and check subject type <strong>LEC</strong> instead';
        }
        
        
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_typeErr' => $subject_typeErr
        ]);
        exit;
    }

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


    $teacher_assigned = clean_input($_POST['teacher-assigned']);

    error_log("ID values: class_id=$class_id, section_id=$section_id,  subject_id=$subject_id, teacher_assigned=$teacher_assigned");
    
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



    if(!empty($class_idErr) || !empty($subject_idErr) || !empty($subject_typErr) || !empty($section_idErr) || !empty($teacher_assignedErr)){
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
    $roomObj->subject_code = $selected_subject;
    $roomObj->original_subject_id = $original_subject_id;
    
    $roomObj->original_class_id = $original_class_id;
    $roomObj->original_subject_type = $original_subject_type;
    // $roomObj->class_id = $class_id;
    // $roomObj->subject_id = $subject_id;
    // $roomObj->section_id = $section_id;
    // $roomObj->teacher_assigned = $teacher_assigned;
    // $roomObj->room_id = $room_id;


    $roomObj->class_id = $class_id;
    $roomObj->subject_type = $subject_type;
    $roomObj->subject_id = $subject_id;
    
    $roomObj->course_abbr = $course_abbr;
    $roomObj->year_level = $year_level;
    $roomObj->section = $section;
    
    $roomObj->teacher_assigned = $teacher_assigned;

    $existing_details = $roomObj->checkExistingClassDetailsPK($class_id, $original_class_id);
    //if data is received and not null, therefore an existing class detail exist
    if($existing_details != null){
        $generalErr = '<strong>EXISTING CLASS ID!</strong> <br> A class with class ID ' . $existing_details['class_id'] . ' already exists for section ' . $existing_details['section_'] . ' with subject ' . $existing_details['subject_'];
        $class_idErr = 'Class ID should be unique for each section class.';
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'class_idErr' => $class_idErr
        ]);
        exit;
    }

    $existing_class = $roomObj->checkSubjectSectionExisting($original_subject_type);
    if($existing_class != null){
        $generalErr = '<strong>EXISTING DATA!</strong> <br> This subject already exists for this section with class ID ' . $existing_class;
        $subject_idErr = 'Cannot update a subject that exist for this section.';
    
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_idErr' => $subject_idErr
        ]);
        exit;
    }

      
    $existing_classID = '';
    //check condition for class id, if false then same class id can be added
    $match_classDetails = $roomObj->checkConditionClassDetailPK();
    if($match_classDetails == null){
        $existing_classID = $roomObj->checkClassIDExisting($class_id, $original_class_id);
        //if true, existing class_id,

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
    $condition = false;

    if($match_classDetails != null ){
        if($roomObj->checkClassTypeCount()){
            $condition = true;
        } 

        if($class_id != $match_classDetails['class_id'] && $condition == true){
            $generalErr = '<strong>MATCHED CLASS ID!</strong> <br>This class matched with <strong>' . $match_classDetails['class_id'] . '</strong> with subject ' . $match_classDetails['subject_id'] . ' for section ' . $match_classDetails['section_name'];
            $class_idErr = 'Class ID should match with the same subject for this section.';
        
            echo json_encode([
                'status' => 'error',
                'generalErr' => $generalErr,
                'class_idErr' => $class_idErr
            ]);
            exit;
        }
    }


    if($roomObj->updateClassDetails()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status ' => 'error', 'message' => 'Something went wrong when updating class detail.']);
    }
    exit;


}

?>
