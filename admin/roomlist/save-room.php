<?php

require_once('../../tools/functions.php');
require_once('../../classes/room.class.php');

//this var refers to room_
$name = $type = '';
$nameErr = $typeErr = '';

$roomObj = new Room();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $name = clean_input($_POST['room-name']);
    $type = clean_input($_POST['room-type']);

    if(empty($name)){
        $nameErr = 'Room name is required.';
    } else if ($roomObj->roomnameExists($name)){
        $nameErr = 'Room name already exists.';
    }

    if(empty($type)){
        $typeErr = 'Room type is required.';
    }
    

    // If there are validation errors, return them as JSON
    if(!empty($nameErr) || !empty($typeErr)){
        echo json_encode([
            'status' => 'error',
            'nameErr' => $nameErr,
            'typeErr' => $typeErr
        ]);
        exit;
    }

    $roomObj->room_name = $name;
    $roomObj->room_type = $type;
    
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
