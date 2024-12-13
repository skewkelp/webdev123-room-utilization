<?php
require_once '../tools/functions.php';
session_start();

$response = array(
    'semesterPicked' => isset($_SESSION['semester_picked']) && $_SESSION['semester_picked']
);

echo json_encode($response); 