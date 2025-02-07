<?php
$page_title = "CodeLuck - Sign Up";
include_once "../includes/_head.php";
require_once '../tools/functions.php';
require_once '../classes/account.class.php';

session_start();

$accountObj = new Account();

$generalErr = '';
$generalErrDisplay = FALSE;

$user_id = $first_name = $last_name = $username = $password = '';
$user_idErr = $first_nameErr = $last_nameErr = $usernameErr = $passwordErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = clean_input($_POST['user-id']);
    $first_name = clean_input($_POST['firstname']);
    $last_name = clean_input($_POST['lastname']);
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    
    if (empty($user_id)) {
        $user_idErr = "Student ID is Required!";
    }

    if (empty($first_name)) {
        $first_nameErr = "First name is Required!";
    }
    if (empty($last_name)) {
        $last_nameErr = "Last name is Required!";
    }
    
    if (empty($username)) {
        $usernameErr = "username is Required!";
    }
    
    

    if(empty($password)) {
        $passwordErr = "password is Required!";
    }

    if($accountObj->useridExist($user_id)) {
        $generalErrDisplay = TRUE;
        $generalErr = "This student ID already has an Account!";
        $user_idErr = "Invalid";
    }elseif($accountObj->userExist($user_id)){
        $generalErrDisplay = TRUE;
        $generalErr = "This student ID is not registered, pls contact an admin to register your ID.";
    }elseif($accountObj->checkusernameMatch($user_id, $username)){
        $generalErrDisplay = TRUE;
        $generalErr = "This username did not match with your student ID.";
        $usernameErr = "Invalid";
    }

    if (empty($generalErr) && empty($user_idErr) && empty($first_nameErr) && empty($last_nameErr) && empty($usernameErr) && empty($passwordErr)) {
        $accountObj->account_id = $user_id;
        $accountObj->first_name = $first_name;
        $accountObj->last_name = $last_name;
        $accountObj->username = $username;
        $accountObj->password = $password;
        $accountObj->insertAccount();
        header("location: loginwcss.php");
        exit();
    }

    
}
?>
<style>
    .hidden{
        display: none;
    }

    .borderErr{
        border: 1px solid red;
    }
    
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, 0.1);
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1),
            inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
    }

    .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
    }

    .bi {
        vertical-align: -0.125em;
        fill: currentColor;
    }

    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
    }

    .btn{
        color: var(--bs-btn-color);
    }

    .bd-mode-toggle {
        z-index: 1500;
    }

    .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
    }

    html,
    body {
        height: 100%;
    }

    .bg-body-tertiary {
    
    background-color: rgba(var(--bs-tertiary-bg-rgb), var(--bs-bg-opacity)) !important;
    }

    

    /* signin background target */
    .bg-body-tertiary{
        background-color: rgba(44, 59, 20, 1) !important; 
    }

    .form-signin {
        max-width: 400px;
        padding: 2rem;
        --bs-bg-opacity: 1;
        --bs-tertiary-bg-rgb: 248, 249, 250;
        background-color: rgba(var(--bs-tertiary-bg-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 24px;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <main class="form-signin w-100 m-auto">
        <form action="signup.php" method="post">
            <img class="mb-4" src="../img/box.png" alt="" width="72" height=72">

            <h1 class="h3 mb-3 fw-normal">Sign Up</h1>

            <div class="form-floating <?php echo $generalErrDisplay ? '' : 'hidden';  ?>">
                <p class="form-control text-danger"><?= $generalErr?></p>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control <?= $user_idErr == '' ? '' : 'borderErr'; ?>" id="user-id" name="user-id" placeholder="202401234" value="<?= $user_id //qb2021 01863?>">
                <label for="user-id">Student ID</label>
                <p class="text-danger"><?= $user_idErr?></p>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control <?= $first_nameErr == '' ? '' : 'borderErr'; ?>" id="firstname" name="firstname" placeholder="Firstname" value="<?= $first_name ?>">
                <label for="firstname">First name</label>
                <p class="text-danger"><?= $first_nameErr ?></p>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control <?= $last_nameErr == '' ? '' : 'borderErr'; ?>" id="lastname" name="lastname" placeholder="Lastname" value="<?= $last_name ?>">
                <label for="lastname">Last name</label>
                <p class="text-danger"><?= $last_nameErr ?></p>
            </div>
          
            <div class="form-floating">
                <input type="text" class="form-control <?= $usernameErr == '' ? '' : 'borderErr'; ?>" id="username" name="username" placeholder="Username" value="<?= $username ?>">
                <label for="username">Username</label>
                <p class="text-danger"><?= $usernameErr ?></p>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control <?= $passwordErr == '' ? '' : 'borderErr'; ?>" id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
                <p class="text-danger"><?= $passwordErr ?></p>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Sign Up</button>
            <p class="mt-5 mb-3 text-body-secondary">&copy; 2024–2025</p>
        </form>
    </main>
    <?php
    require_once '../includes/_footer.php';
    ?>
</body>

</html>