<?php
$page_title = "roomstatus";
session_start();

if (isset($_SESSION['account'])) {
    if (!$_SESSION['account']) {
        header('location: ../account/loginwcss.php');
    }
} else {
    header('location: ../account/loginwcss.php');
}
require_once '../includes/_head.php';
?>

<body id="dashboard">
    
    <div id="alert-card">
        <div id="customAlert"></div>
    </div>

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