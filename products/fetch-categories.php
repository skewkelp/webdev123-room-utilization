<?php
    require_once('../classes/product.class.php');

    $productObj = new Product();

    $categories = $productObj->fetchCategory();

    header('Content-Type: application/json');
    echo json_encode($categories);
?>
