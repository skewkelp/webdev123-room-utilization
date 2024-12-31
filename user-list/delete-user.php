<?php

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$user_id = '';

$userObj = new Account();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = clean_input($_GET['userID']);

    $userObj->user_id = $user_id;

    if($userObj->deleteUser()){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when deleting the class details.']);
    }
    exit;

}

?>
