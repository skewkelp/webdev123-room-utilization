<?php

session_start();

if (isset($_SESSION['account'])) {
    if (!$_SESSION['account']['is_staff']) {
        header('location: login.php');
    }
} else {
    header('location: login.php');
}

require_once('../tools/functions.php');
require_once('../classes/product.class.php');
require_once('../classes/stocks.class.php');

$name = $quantity = $status = $reason = '';
$quantityErr = $statusErr = $reasonErr = '';

$productObj = new Product();
$stocksObj = new Stocks();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $productObj->fetchRecord($id);
        if (!empty($record)) {
            $name = $record['name'];
        } else {
            echo 'No product found';
            exit;
        }
    } else {
        echo 'No product found';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'];
    $record = $productObj->fetchRecord($id);
    if (!empty($record)) {
        $name = $record['name'];
    } else {
        echo 'No product found';
        exit;
    }
    $product_id = clean_input($_GET['id']);
    $quantity = clean_input($_POST['quantity']);
    $status = isset($_POST['status']) ? clean_input($_POST['status']) : '';
    $reason = clean_input($_POST['reason']);

    if (empty($quantity)) {
        $quantityErr = 'Quantity is required';
    } elseif (!is_numeric($quantity)) {
        $quantityErr = 'Quantity should be a number';
    } elseif ($quantity < 1) {
        $quantityErr = 'Quantity must be greater than 0';
    } elseif ($status == 'out' && $quantity > $stocksObj->getAvailableStocks($product_id)) {
        $rem = ($stocksObj->getAvailableStocks($product_id)) ? $stocksObj->getAvailableStocks($product_id) : 0;
        $quantityErr = "Quantity must be less than the Available Stocks: $rem";
    }

    if (empty($status)) {
        $statusErr = 'Status is required';
    }

    if (empty($reason) && $status == 'out') {
        $reasonErr = 'Reason is required';
    }

    if (empty($quantityErr) && empty($statusErr) && empty($reasonErr)) {
        $stocksObj->product_id = $product_id;
        $stocksObj->quantity = $quantity;
        $stocksObj->status = $status;
        $stocksObj->reason = $reason;

        if ($stocksObj->addStock()) {
            header('Location: product.php');
        } else {
            echo 'Something went wrong when stocking the product';
        }
    }
}


// session_start();

// if (isset($_SESSION['account'])) {
//     if (!$_SESSION['account']['is_staff']) {
//         header('location: login.php');
//     }
// } else {
//     header('location: login.php');
// }

// require_once('../tools/functions.php');
// require_once('../classes/product.class.php');
// require_once('../classes/stocks.class.php');

// $name = $quantity = $status = $reason = '';
// $quantityErr = $statusErr = $reasonErr = '';
// $productObj = new Product();
// $stocksObj = new Stocks();

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     if (isset($_GET['id'])) {
//         $id = $_GET['id'];
//         $record = $productObj->fetchRecord($id);
//         if (!empty($record)) {
//             $name = $record['name'];
//         } else {
//             echo 'No product found';
//             exit;
//         }
//     } else {
//         echo 'No product found';
//         exit;
//     }
// } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $id = $_GET['id'];
//     $record = $productObj->fetchRecord($id);
//     if (!empty($record)) {
//         $name = $record['name'];
//     } else {
//         echo 'No product found';
//         exit;
//     }
//     $product_id = clean_input($_GET['id']);
//     $quantity = clean_input($_POST['quantity']);
//     $status = isset($_POST['status']) ? clean_input($_POST['status']) : '';
//     $reason = clean_input($_POST['reason']);

//     if (empty($quantity)) {
//         $quantityErr = 'Quantity is required';
//     } elseif (!is_numeric($quantity)) {
//         $quantityErr = 'Quantity should be a number';
//     } elseif ($quantity < 1) {
//         $quantityErr = 'Quantity must be greater than 0';
//     } elseif ($status == 'out' && $quantity > $stocksObj->getAvailableStocks($product_id)) {
//         $rem = ($stocksObj->getAvailableStocks($product_id)) ? $stocksObj->getAvailableStocks($product_id) : 0;
//         $quantityErr = "Quantity must be less than the Available Stocks: $rem";
//     }

//     if (empty($status)) {
//         $statusErr = 'Status is required';
//     }

//     if (empty($reason) && $status == 'out') {
//         $reasonErr = 'Reason is required';
//     }

//     if (empty($quantityErr) && empty($statusErr) && empty($reasonErr)) {
//         $stocksObj->product_id = $product_id;
//         $stocksObj->quantity = $quantity;
//         $stocksObj->status = $status;
//         $stocksObj->reason = $reason;

//         if ($stocksObj->add()) {
//             header('Location: product.php');
//         } else {
//             echo 'Something went wrong when stocking the product';
//         }
//     }
// }
?>
