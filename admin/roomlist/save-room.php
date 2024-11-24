<?php

require_once('../tools/functions.php');
require_once('../classes/room.class.php');

//this var refers to room_
$name = $type = '';
$nameErr = $typeErr = '';

$roomObj = new Room();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $name = clean_input($_POST['room_name']);
    $type = clean_input($_POST['room_type']);

    if(empty($name)){
        $nameErr = 'Room name is required.';
    } else if ($roomObj->codeExists($name)){
        $nameErr = 'Room name already exists.';
    }

    if(empty($room_type)){
        $room_typeErr = 'Category is required.';
    }

    // If there are validation errors, return them as JSON
    if(!empty($nameErr) || !empty($typeErr)){
        echo json_encode([
            'status' => 'error',
            'room_nameErr' => $Err,
            'room_typeErr' => $typeErr
        ]);
        exit;
    }

    if(empty($room_nameErr) && empty($room_typeErr)){
        $roomObj->room_name = $name;
        $roomObj->room_type = $type;
    

        if($roomObj->add()){
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
        }
        exit;
    }
}
?>
