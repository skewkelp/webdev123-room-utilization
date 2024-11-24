<?php

require_once('../tools/functions.php');
require_once('../classes/room.class.php');

//room_list var
$roomid = $_GET['id'];
$name = $type = '';
$nameErr = $typeErr = '';

$roomObj = new Room();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $category = clean_input($_POST['category']);

    if (empty($name)) {
        $nameErr = 'Room name is required.';
    } else if ($roomObj->codeExists($name, $roomid)) { 
        $nameErr = 'Room name already exists';
    }

    
    if (empty($type)) {
        $typeErr = 'Room type is required.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($nameErr) || !empty($typeErr)) {
        echo json_encode([
            'status' => 'error',
            'nameErr' => $nameErr,
            'typeErr' => $typeErr
        ]);
        exit;
    }

    if (empty($nameErr) && empty($typeErr)) {
        $roomObj->id = $roomid;
        $roomObj->room_name = $name;
        $roomObj->room_type = $type;

        if ($roomObj->editRoom()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new room.']);
        }
        exit;
    }
}
