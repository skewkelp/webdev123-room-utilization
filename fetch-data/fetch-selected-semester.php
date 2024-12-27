<?php
require_once('../tools/functions.php');

session_start();
$semester_PK = $semester = $school_year = ''; 
$split_PK = []; 

$count_default = clean_input($_POST['count_default']);

// Check if the session variable is set
if (isset($_SESSION['selected_semester_id']) && $count_default == 'false') {
    $semester_PK = $_SESSION['selected_semester_id'];
    $split_PK = explode('|', $semester_PK);
    $semester = $split_PK[0];
    $school_year = $split_PK[1];

    echo json_encode([
        'status' => 'success',
        'countDefault' => 'true',
        'semester' => $semester,
        'school_year' => $school_year
    ]);
    exit;

} elseif(isset($_SESSION['selected_semester_id']) && $count_default == 'true'){
    $semester_PK = $_SESSION['selected_semester_id'];
    $split_PK = explode('|', $semester_PK);
    $semester = $split_PK[0];
    $school_year = $split_PK[1];

    echo json_encode([
        'status' => 'success',
        'semester' => $semester,
        'school_year' => $school_year
    ]);
    exit;
}


else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Something went wrong when fetching semester.'
    ]);
    exit;
}

// Return the response as JSON
?>