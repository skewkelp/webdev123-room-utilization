<?php
session_start();
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">User Information</h1>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <?php
                    require_once '../classes/account.class.php';
                    $accountObj = new Account();   
                ?>
        
                <div class="card-body p-1 pt-2">
                    <div class="table-responsive">
                        <table id="table-profile" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Account Id</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $array = $accountObj->showAllusers();

                                foreach ($array as $arr) {
                                ?>
                                
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $arr['account_id'] ?></td>
                                    <td><?= $arr['first_name'] ?></td>
                                    <td><?= $arr['last_name'] ?></td>
                                    <td><?= $arr['username'] ?></td>
                                </tr>

                                <?php
                                    $i++;  
                                }
                                ?>
                                
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/profile.js"></script>
</div>
       
    

