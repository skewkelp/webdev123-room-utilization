<?php
require_once '../tools/functions.php';
session_start();
?>

<div class="container-fluid" style="height: 80vh;">
    <?php
        require_once '../classes/room-status.class.php';
        $roomObj = new RoomStatus();   
    ?>


    <div class="row justify-content-center align-items-center" style="height: 100%;">
        <div class="col-md-6">
            <div class="card p-4" style="border-radius: 20px;">
                <div class="banner-container">
                </div>    

                <div class="text-center mb-4">
                    <h1 class="page-title">CHOOSE SEMESTER</h1>
                </div>

                <form action="" method="post" id="form-semester">
                    
                    <div class="mb-2">
                        <div class="dropdown">
                            <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-semester" name="semester">
                            <input type="hidden" id="hidden-semester-id" name="semester-id"/>
                            <div class="dropdown-list" id="dropdown-list-semester">
                                <!-- Options will be populated here by JavaScript -->
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary brand-bg-color w-100">Continue</button>
                </form>
            </div>
        </div>
    </div>
</div>