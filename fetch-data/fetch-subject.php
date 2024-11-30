<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new Room();

    $subject = $roomObj->fetchsubjectOption();

    header('Content-Type: application/json');
    echo json_encode($subject);

?>
