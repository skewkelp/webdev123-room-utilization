<?php
    session_start();

    require_once('../tools/functions.php');
    require_once('../classes/room-status.class.php');


    $received_remark = $split_remarks = $temp_remark = $remarks = '';
    $class_id = $subject_type = $class_day = $room_status = $new_room_status = '';
    $roomObj = new RoomStatus();

    
    if(!empty($_GET['classID']) && !empty($_GET['subType']) && !empty($_GET['classDay']) && !empty($_GET['roomStatus']) && !empty($_GET['remark'])){
        $received_remark = clean_input($_GET['remark']);

        $received_remark = explode('::Original Remarks: ', $split_remarks);
        $temp_remark = $split_remarks[0];
        $remarks = $split_remarks[1];


        $class_id = clean_input($_GET['classID']);
        $subject_type = clean_input($_GET['subType']);
        $class_day = clean_input($_GET['classDay']);
        $room_status = clean_input($_GET['roomStatus']);

    }else{
        echo json_encode([
            'status' => 'ERROR', 
            'data' => 'remarks:' . $split_remarks . ' classid:' . $class_id . ' subtype:' . $subject_type . ' classday:' . $class_day . ' roomstat:' . $room_status]);
        exit;
    }


    if($room_status == 'OCCUPIED'){
        $new_room_status = 'AVAILABLE';
    }else if($room_status == 'AVAILABLE'){
        $new_room_status = 'OCCUPIED';
    }else{
        echo json_encode([
            'status' => 'ERROR',
            'data' => 'roomstat:' . $room_status
        ]);
        exit;
    }

    // $generalErr = '<strong>ERROR FORM!</strong><br> ROOM STATUS EMPTY : remarks:' . $remarks;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;

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
        


?>
