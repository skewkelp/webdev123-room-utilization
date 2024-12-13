<?php
session_start();
require_once '../tools/functions.php';

error_log("POST data received: " . print_r($_POST, true));



$semester_id = $semester_idErr = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $semester_id = clean_input($_POST['semester-id']);

    if(empty($semester_id)){
        $semester_idErr = 'Semester is required.';
    }


    if(!empty($semester_idErr)){
        echo json_encode([
            'status' => 'error',
            'semester_idErr' => $semester_idErr
        ]);
        exit;
    }


    $_SESSION['semester_picked'] = true;
    $_SESSION['selected_semester'] = $_POST['semester'] ?? null;
    $_SESSION['selected_semester_id'] = $_POST['semester-id'] ?? null;//hidden  id
    
    echo json_encode(['status' => 'success']);
}
