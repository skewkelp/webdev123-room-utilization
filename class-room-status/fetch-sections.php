<?php
require_once '../classes/room-status.class.php';
session_start();

$courseId = $_GET['course_id'];
$roomObj = new RoomStatus(); // Assuming you have a Room class

if ($courseId === 'ALL') {
    $sections = $roomObj->fetchsectionOption(); // Fetch all sections
} else {
    $sections = $roomObj->fetchSectionsByCourseId($courseId); // Fetch sections for the selected course
}

echo json_encode($sections); // Return sections as JSON

?>