<?php
    require_once('../tools/functions.php');
    require_once('../classes/room-status.class.php');

    $roomObj = new RoomStatus();

    $prospectus_id = '';
    if(!empty($_GET['prospectusID'])){
        $prospectus_id = clean_input($_GET['prospectusID']);
        $subject = $roomObj->fetchsubjectOption($prospectus_id);
    }else{
        $subject = $roomObj->fetchsubjectOption();
    }


    header('Content-Type: application/json');
    echo json_encode($subject);

?>
