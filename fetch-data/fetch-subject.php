<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new Room();

    $subject_option = $roomObj->fetchsubjectOption();

    header('Content-Type: application/json');
    echo json_encode($subject_option);

?>
