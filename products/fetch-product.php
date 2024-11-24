<?php
require_once('../classes/product.class.php');

$productObj = new Product();

$id = $_GET['id'];
$product = $productObj->fetchRecord($id);

header('Content-Type: application/json');
echo json_encode($product);
