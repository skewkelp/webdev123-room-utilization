<?php
session_start();
// //  session_start() to see what's in the session
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// exit;
require_once '../tools/functions.php';

if(!isset($_SESSION['account'])) {
    header('location: ../account/loginwcss.php');
    exit();
}

// // Then check user permissions
// if (!hasPermission('both')){
//     // If user is neither staff nor admin, redirect
//     header('location: ../account/loginwcss.php');
//     exit();
// }

$page_title = "room-list";

require_once '../includes/_head.php';
?>

<body id="dashboard">
    <div class="wrapper">
        <?php
        require_once '../includes/_topnav.php';
        require_once '../includes/_sidebar.php';
        ?>
        <div class="content-page px-3">
            <!-- dynamic content here -->
        </div>
    </div>
    <?php
    require_once '../includes/_footer.php';
    ?>
</body>

</html>