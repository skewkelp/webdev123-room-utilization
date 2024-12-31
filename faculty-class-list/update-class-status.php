<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/room-status.class.php');



$remarks = '';
$class_id = $subject_type = $class_day = $room_status = $new_room_status = '';
$roomObj = new RoomStatus();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $remarks = clean_input($_POST['remark']);

    if(!empty($_GET['classID']) && !empty($_GET['subType']) && !empty($_GET['classDay']) && !empty($_GET['roomStatus'])){
        $class_id = clean_input($_GET['classID']);
        $subject_type = clean_input($_GET['subType']);
        $class_day = clean_input($_GET['classDay']);
        $room_status = clean_input($_GET['roomStatus']);

    }else{
        $generalErr = '<strong>ERROR FORM!</strong><br> primary key are empty: classID' . $class_id .  ' subType:' . $subject_type . ' classDay:' . $class_day . ' roomStatus:' . $room_status;

        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr
        ]);
        exit;
    }


    
    if($room_status == 'OCCUPIED'){
        $new_room_status = 'AVAILABLE';
    }else if($room_status == 'AVAILABLE'){
        $new_room_status = 'OCCUPIED';
    }else{
        $generalErr = '<strong>ERROR FORM!</strong><br> ROOM STATUS EMPTY : roomStatus:' . $room_status;

        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr
        ]);
        exit;
    }

    // $generalErr = '<strong>ERROR FORM!</strong><br> primary key are empty: classID' . $class_id .  ' subType:' . $subject_type . ' classDay:' . $class_day . ' roomStatus:' . $room_status;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;


    // $generalErr = '<strong>ERROR FORM!</strong><br> ROOM STATUS EMPTY : roomStatus:' . $room_status;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;

    
    // //Error template feed
    // $generalErr = '<strong>ERROR FORM!</strong><br> Var:' . $remarks ;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;

    $roomObj->remarks = $remarks;
    $roomObj->room_status = $new_room_status;

    $roomObj->class_id = $class_id;
    $roomObj->subject_type = $subject_type;
    $roomObj->day_id = $class_day;
    
    

    if ( $roomObj->insertLog() && $roomObj->updateClassStatus()) {
        echo json_encode(['status' => 'success']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when updating class status.']);
    }
    
    exit;
        
}

?>
