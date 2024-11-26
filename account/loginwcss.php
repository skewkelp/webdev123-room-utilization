<?php
$page_title = "Login";
include_once '../includes/_login_head.php';
require_once '../tools/functions.php';
require_once '../classes/account.class.php';

session_start();

$username = $password = '';
$accountObj = new Account();
$loginErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input(($_POST['username']));
    $password = clean_input($_POST['password']);

    if ($accountObj->login($username, $password)) {
        $data = $accountObj->fetch($username);
        $_SESSION['account'] = $data;
        header('location: ../admin/room-list.php');
    } else {
        $loginErr = 'Invalid username/password';
    }
} else {
    if (isset($_SESSION['account'])) {
        if ($_SESSION['account']['is_staff']) {
            header('location: ../admin/room-list.php');
        }
    }
}
?>

<body>
        <form action="loginwcss.php" method="post" class="form">

            <img class="mb-4" src="../img/box.png" alt="" width="128px" height="128px">
            <h1>ROOM UTILIZATION</h1>
            <!-- <h2 class="h3 mb-3 fw-normal">Login</h2> -->

            <div class="input-Container">
                <label for="username" class="blabel">Username</label>
                <input type="text" class="input" id="username" name="username" placeholder="Username">
            </div>
            <div class="input-Container">
                <label for="password" class="blabel">Password</label>
                <input type="password" class="input" id="password" name="password" placeholder="Password">
            </div>
            <p class="text-danger"><?= $loginErr ?></p>
            <button  class="buttonContinue" type="submit">Continue</button>
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Remember me
                </label>
            </div>
            <div class="signupContainer">
                <a class="text-link" href="signup.php">Create an Account</a>
            </div>

            <p class="mt-5 mb-3 text-body-secondary">&copy; 2024–2025</p>
        </form>
    <?php
    require_once '../includes/_footer.php';
    ?>
</body>

</html>