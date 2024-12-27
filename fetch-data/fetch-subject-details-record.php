<?php
require_once('../classes/room-status.class.php');

$roomObj = new RoomStatus();

$subject_code = $_GET['subjectID'];
$prospectus_id = $_GET['prospectusID'];

$subject_details = $roomObj->fetchsubjectdetailsRecord($subject_code, $prospectus_id);

header('Content-Type: application/json');
echo json_encode($subject_details);