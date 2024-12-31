<?php
require_once('../classes/account.class.php');

$userObj = new Account();

$user_id = $_GET['userID'];

$user_record = $userObj->showuserList($user_id);

header('Content-Type: application/json');
echo json_encode($user_record);