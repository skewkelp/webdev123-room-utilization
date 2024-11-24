<?php
require_once('../classes/product.class.php');

$roomObj = new Room();

$id = $_GET['id'];
$room = $roomObj->fetchRecord($id);

header('Content-Type: application/json');
echo json_encode($room);
