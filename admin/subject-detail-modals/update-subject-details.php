<?php
session_start();

require_once('../../tools/functions.php');
require_once('../../classes/room-status.class.php');


$subject_code = $description = $lec_units = $lab_units = $total_units = '';
$prospectus_id = '';

$original_subject_code = $original_description = $original_lec_units = $original_lab_units = $original_total_units = '';

//varErr
$generalErr = $subject_codeErr = $descriptionErr = $lab_unitsErr = $lec_unitsErr = '';

$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $original_subject_code = clean_input($_POST['original-subject-code']);
    $original_description = clean_input($_POST['original-description']);
    $original_lec_units = clean_input($_POST['original-lec-units']);
    $original_lab_units = clean_input($_POST['original-lab-units']);
    $original_total_units = clean_input($_POST['original-total-units']);
    
    $subject_code = clean_input($_POST['subject-code']);
    $description = clean_input($_POST['description']);
    
    // Initialize units
    $lec_units = !empty($_POST['lec-units']) ? clean_input($_POST['lec-units']) : '';
    $lab_units = !empty($_POST['lab-units']) ? clean_input($_POST['lab-units']) : '';


    if (!empty($_POST['lec-units']) && !empty($_POST['lab-units'])) {
        $total_units = (float)$lab_units + (float)$lec_units;
    }

    ////Error template feed
    // $generalErr = '<strong>ERROR FORM!</strong><br> Varlec:' . $lec_units .' varlab'. $lab_units;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;

    // Check prospectus ID
    if (!empty($_POST['prospectus'])) {
        $prospectus_id = clean_input($_POST['prospectus']);
    } else {
        $generalErr = '<strong>ERROR FORM!</strong><br>Prospectus is empty.';
    }

     // Validate subject code
     if (empty($subject_code)) {
        $subject_codeErr = 'Subject Code is required.';
    } else if (!preg_match('/^[A-Z&]{2,8}\d?[0-9]{0,3}$/', $subject_code)) {
        $subject_codeErr = 'Subject Code must be in the format: 2-8 uppercase letters optionally followed by 0-3 digits (e.g., AB123, ABCD).';
    }
    
        
    // Validate description
    if (empty($description)) {
        $descriptionErr = 'Description is required for subject.';
    }
    
    // Validate units
    if (empty($lec_units) || !is_numeric($lec_units) || $lec_units < 0) {
        $lec_unitsErr = 'Lec units must be a positive number.';
    }
    if (empty($lab_units) || !is_numeric($lab_units) || $lab_units < 0) {
        $lab_unitsErr = 'Lab units must be a positive number.';
    }


    if (!empty($lec_units) && !empty($lab_units) && $lec_units == 0 && $lab_units == 0) {
        $generalErr = '<strong>INVALID UNITS!</strong><br> Lec units and lab units cannot both be 0.';
        $lec_unitsErr = 'Invalid';
        $lab_unitsErr = 'Invalid';
    }

    // Check for any errors
    if (!empty($generalErr) || !empty($subject_codeErr) || !empty($descriptionErr) || !empty($lab_unitsErr) || !empty($lec_unitsErr)) {
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_codeErr' => $subject_codeErr,
            'descriptionErr' => $descriptionErr,
            'lec_unitsErr' => $lec_unitsErr,
            'lab_unitsErr' => $lab_unitsErr
        ]);
        exit;
    }

    // Check existing subject code
    $existingSubject = $roomObj->checkExistingSubjectCode($subject_code, $prospectus_id, $original_subject_code, $prospectus_id);
    if ($existingSubject != null) {
        $generalErr = '<strong>SUBJECT CODE ' . $existingSubject . ' ALREADY EXISTS!</strong><br>Subject code should be unique.';
        $subject_codeErr = 'Invalid';
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'subject_codeErr' => $subject_codeErr
        ]);
        exit;
    }

    $roomObj->subject_code = $subject_code;
    $roomObj->description = $description;
    $roomObj->total_units = $total_units;
    $roomObj->lec_units = $lec_units;
    $roomObj->lab_units = $lab_units;
    $roomObj->prospectus_id = $prospectus_id;
    

    if ($roomObj->updateSubjectDetails($original_subject_code)){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new subject details.']);
    }

    exit;
    
}

?>
