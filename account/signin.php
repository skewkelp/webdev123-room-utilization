<?php
$page_title = "CodeLuck - Login";
include_once "../includes/_head.php";
require_once '../tools/functions.php';
require_once '../classes/account.class.php';

?>

<style>
    .divider:after,
    .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
</style>

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="../img/signin.png" class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form>
                    <!-- username input -->
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        <label for="username">Username</label>
                    </div>

                    <!-- password input -->
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>


                    <div class="d-flex justify-content-around align-items-center mb-4">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                            <label class="form-check-label" for="form1Example3"> Remember me </label>
                        </div>
                        <a href="#!">Forgot password?</a>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init
                        class="btn btn-primary btn-lg w-100">Sign in</button>

                    <div class="divider d-flex align-items-center my-4">
                        <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                    </div>

                    <a data-mdb-ripple-init class="btn btn-primary btn-lg w-100 mb-2" style="background-color: #3b5998"
                        href="#!" role="button">
                        <i class="bi bi-facebook me-2"></i>Continue with Facebook
                    </a>
                    <a data-mdb-ripple-init class="btn btn-primary btn-lg w-100 mb-2" style="background-color: #55acee"
                        href="#!" role="button">
                        <i class="bi bi-twitter me-2"></i>Continue with Twitter
                    </a>

                    <p>Don't have an account? <a href="#!" class="link-info">Register here</a></p>
                </form>
            </div>
        </div>
    </div>
</section>