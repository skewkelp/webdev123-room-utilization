<?php 
require_once '../tools/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor//bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../vendor/datatable-2.1.8/datatables.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <?php if(isset($_SESSION['account'])): ?>
    <meta name="user-id" content="<?php echo $_SESSION['account']['account_id']; ?>">
    <?php endif; ?>
</head>

<script>
    window.userPermissions = {
        isAdmin: <?php echo isset($_SESSION['account']) && $_SESSION['account']['is_admin'] == 1 ? 'true' : 'false'; ?>,
        isStaff: <?php echo isset($_SESSION['account']) && $_SESSION['account']['is_staff'] == 1 ? 'true' : 'false'; ?>
    };
</script>
