<?php

require_once('../../tools/functions.php');
require_once('../../classes/room.class.php');


$split_PK = $room_code = $room_no = '';
//this var refers to room_
$room_name = $room_type = '';
$generalErr = $room_nameErr = $room_typeErr = '';

$roomObj = new Room();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(empty($_POST['room-name'])){
        $room_nameErr = 'Room name is required.';
    } else {
        $room_name = clean_input($_POST['room-name']);
        $split_PK = explode(' ', $room_name);
        
        // Check if we got both values after splitting
        if(count($split_PK) !== 2) {
            $generalErr = '<strong>INVALID ROOM NAME FORMAT!</strong> <br> Room name must be in the format room code (LR/LAB) followed by a single space " " and its room no.';
        } else {
            $room_code = $split_PK[0];
            $room_no = $split_PK[1];
        }
    }
    
    $room_type = clean_input($_POST['room-type']);


    if(!preg_match('/^[A-Z]+ \d+$/', $room_name)){
        $room_nameErr = 'Example: (LR 1)';
    }elseif($roomObj->roomnameExists($room_code, $room_no)){
        $room_nameErr = 'Room name already exists.';
    }

    if(empty($room_type)){
        $room_typeErr = 'Room type is required.';
    }

    // If there are validation errors, return them as JSON
    if(!empty($generalErr) || !empty($room_nameErr) || !empty($room_typeErr)){
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'room_nameErr' => $room_nameErr,
            'room_typeErr' => $room_typeErr
        ]);
        exit;
    }elseif($room_code != $room_type){
        $generalErr = "<strong>INPUTS NOT MATCHING!</strong><br> Inputted Room code ({$room_code}) on room name field did not match with selected room type.";
        $room_nameErr = 'Should match room type';
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'room_nameErr' => $room_nameErr
        ]);
        exit;
    }

    $roomObj->room_code = $room_code;
    $roomObj->room_no = $room_no;

    
    if($roomObj->addRoom()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
    }
    exit;

    // if(empty($nameErr) && empty($typeErr)){
    //     $roomObj->room_name = $name;
    //     $roomObj->room_type = $type;

    //     if($roomObj->addRoom()){
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
    //     }
    //     exit;
    // }
    
}

?>
