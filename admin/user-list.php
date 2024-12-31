<?php
session_start();

require_once '../tools/functions.php';
$page_title = "user-list";

if(!isset($_SESSION['account'])) {
    header('location: ../account/loginwcss.php');
    exit();
}


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
   
</body>
<?php
    require_once '../includes/_footer.php';
?>
    
</html>