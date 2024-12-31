<?php
require_once('../classes/account.class.php');

$userObj = new Account();

$account_id = $_GET['accountID'];

$account_record = $userObj->showProfile($account_id);

header('Content-Type: application/json');
echo json_encode($account_record);