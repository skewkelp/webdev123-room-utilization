<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new Room();

    $section_option = $roomObj->fetchsectOption();

    header('Content-Type: application/json');
    echo json_encode($section_option);

?>
