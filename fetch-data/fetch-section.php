<?php
    require_once('../classes/room-status.class.php');

    $roomObj = new Room();

    $section = $roomObj->fetchsectionOption();

    header('Content-Type: application/json');
    echo json_encode($section);

?>
