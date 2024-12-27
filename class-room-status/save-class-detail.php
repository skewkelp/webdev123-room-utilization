<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');

$semester_PK = '';
$semester = $school_year = '';
//this var refers to the name of the section, room, subject, and teacher selected in the input field
$selected_section = $selected_subject = $selected_teacher = $selected_teacherLab = '';

//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$class_id = $section_id = $subject_id = $subject_type = $teacher_assigned = $teacher_assigned_lab = '';
$generalErr = $class_idErr = $section_idErr = $subject_idErr = $subject_typeErr = $teacher_assignedErr = $teacher_assigned_labErr = '';



$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    error_log("POST data received: " . print_r($_POST, true));
    
    if($_POST['subject'] == 'List Empty, Please add more subjects for this prospectus.'){
        $generalErr = '<strong>SUBJECT LIST EMPTY!</strong> <br> To add a class detail, Pls add more subjects for this prospectus. Pls exit the form.';
        $subject_idErr = 'INVALID';

        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_idErr' => $subject_idErr
        ]);
        exit;
    }

    $semester_PK = clean_input($_SESSION['selected_semester_id']);
    $split_PK = explode('|', $semester_PK);
    $semester = $split_PK[0];
    $school_year = $split_PK[1];

    $selected_subject = clean_input($_POST['subject']);
    $selected_section = clean_input($_POST['section']);
    $selected_teacher = clean_input($_POST['teacher']);

    $selected_subject = explode(' ', $selected_subject)[0];

    error_log("Selected values: section=$selected_section, subject=$selected_subject, teacher=$selected_teacher, semester=$semester_PK, school_year=$school_year");
    
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
    
    $times = 0;
    if(empty($_POST['subject-type'])){
        $subject_typeErr = 'Subject Type is required.';
    }else if (!empty($_POST['subject-type'])){
        $subject_type = $_POST['subject-type'];
        if(count($subject_type) > 1){
            $times = 2;
        }else{
            $times = 1;
        }

        $check = 0;
        $unitDetails = $checkArray = [];
            //$unitDetails = LEC? lec_units :null,  LAB?  lab_units: null
        foreach($subject_type as $checkType){
            //subjec_type = ['LEC', 'LAB']
            if($times > 1){
                $unitDetails = $roomObj->checkSubjectType($subject_id, $checkType);
                $typeDetails[$check] = $checkType;
                $check++;
                if($unitDetails == null){
                    $checkArray[$check] = $checkType;
                }
            }else{
                $unitDetails = $roomObj->checkSubjectType($subject_id, $checkType);
                $typeDetails[0] = $checkType;
                
                if($unitDetails != null){
                    $checkArray[0] = $checkType;
                }
            }
        }


        if($unitDetails != null){
            $generalErr = '<strong>SUBJECT TYPE INVALID!</strong> <br> This subject has ';
            if($times > 1){
                if(in_array("LEC", $checkArray)){//$unitDetails['lec_units']
                    $generalErr .= 'no lab units but has ' . $unitDetails . ' lec units.'; 
                    $subject_typeErr = 'Uncheck subject type <strong>LAB</strong>.';

                }else if(in_array("LAB", $checkArray)){//$unitDetails['lab_units']
                    $generalErr .= 'no lec units but has .' . $unitDetails . ' lab units.'; 
                    $subject_typeErr = 'Uncheck subject type <strong>LEC</strong>.';

                }else{
                    $generalErr .= 'no lec units and lab units registered on the subject.'; 
                    $subject_typeErr = 'Change and modify subject to have units.';
                }

            }else{
                if($typeDetails[0] == 'LAB'){//$unitDetails['lec_units']
                    $generalErr .= '0 lab units but has ' . $unitDetails . ' lec units registered on the subject.'; 
                    $subject_typeErr = 'Uncheck subject type LAB and check subject type <strong>LEC</strong> instead';
                }else if ($typeDetails[0] == 'LEC'){//$unitDetails['lab_units'] 
                    $generalErr .= '0 lec units but has ' . $unitDetails . ' lab units registered on the subject.';
                    $subject_typeErr = 'Uncheck subject type LEC and check subject type <strong>LAB</strong> instead';
                }
            }
            
            echo json_encode([
                'status' => 'error',
                'generalErr' => $generalErr,
                'subject_typeErr' => $subject_typeErr
            ]);
            exit;
        }
    }

   
    $teacher_assigned = clean_input($_POST['teacher-assigned']);
    
    error_log("ID values: class_id=$class_id, section_id=$section_id, subject_id=$subject_id, teacher_assigned=$teacher_assigned");
    
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

    $determiner = '';
    $determiner = clean_input($_POST['determiner']);

    if(!empty($selected_teacher) && empty($teacher_assigned)){
        $teacher_assignedErr = 'Select a teacher from the dropdown.';
    } else if(empty($selected_teacher)){
        if($determiner == 'true'){
            $teacher_assignedErr = 'Teacher is required for subject type LEC.';
        }else{
            $teacher_assignedErr = 'Teacher is required.';
        }
    }
    
 
    //if checkbox is both checked, then error feed is necessary
    if($determiner == 'true'){
        $selected_teacher_lab = clean_input($_POST['teacher-lab']);
        $teacher_assigned_lab = clean_input($_POST['teacher-assigned-lab']);

        if(!empty($selected_teacher_lab) && empty($teacher_assigned_lab)){
            $teacher_assigned_labErr = 'Select a teacher from the dropdown.';
        } else if(empty($selected_teacher_lab)){
            $teacher_assigned_labErr = 'Teacher is required for subject type LAB.';
        }

    }
    

    if(!empty($class_idErr) || !empty($subject_idErr) || !empty($subject_typeErr) || !empty($section_idErr) || !empty($teacher_assignedErr) && $determiner == "true"){
        echo json_encode([
            'status' => 'error',
            'class_idErr' => $class_idErr,
            'subject_idErr' => $subject_idErr,
            'subject_typeErr' => $subject_typeErr,
            'section_idErr' => $section_idErr,
            'teacher_assignedErr' => $teacher_assignedErr,
            'teacher_assigned_labErr' => $teacher_assigned_labErr
        ]);
        exit;
    }else if (!empty($class_idErr) || !empty($subject_idErr) || !empty($subject_typeErr) || !empty($section_idErr) || !empty($teacher_assignedErr) && $determiner == "false"){
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
    
    
    $roomObj->semester = $semester;
    $roomObj->school_year = $school_year;
    
    
    foreach($subject_type as $type){
        $roomObj->subject_type = $type;
        
        $noExclude = null;
   
        $existing_details = $roomObj->checkExistingClassDetailsPK($class_id, $noExclude);
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


    }   
    
    $counter = 0;
    $success = $error = 0;

    $teacherArray = [];

    if ($determiner == "true") {
        $teacherArray = [$teacher_assigned, $teacher_assigned_lab];
    } else {
        $teacherArray = [$teacher_assigned];
    }

    // Initialize an array to hold typeholders
    $typeholders = [];

    while ($counter < $times) {
        if ($times == 1) {
            $roomObj->teacher_assigned = $teacherArray[0];
        } else if ($times == 2) {
            $roomObj->teacher_assigned = $teacherArray[$counter];
        }

        $roomObj->subject_type = $subject_type[$counter];
        if ($roomObj->insertClassDetails()) {
            $success++;
            // Collect the typeholder in an array
            $typeholders[] = $subject_type[$counter];
        } else {
            $error++;
        }
        
        $counter++;
    }

    if ($success > 0 && $error === 0) {
        echo json_encode(['status' => 'success', 'message' => "$success classes (" . implode(", ", $typeholders) . ") added successfully."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new class status.']);
    }

    exit;
        
    }

?>
