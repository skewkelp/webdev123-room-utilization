<?php
session_start();
require_once '../tools/functions.php';  // Add this line

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">User Profile: <?= $_SESSION['account']['first_name'] . ' ' . $_SESSION['account']['last_name'] ?></h1>
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
                                    <th>Account Id</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th style="width: 30%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $user_id = '';
                                $user_id = clean_input($_SESSION['account']['account_id']);
                                $arr = $accountObj->showProfile($user_id);
                                ?>
                                <tr>
                                    <td><?= $arr['account_id'] ?></td>
                                    <td><?= $arr['first_name'] ?></td>
                                    <td><?= $arr['last_name'] ?></td>
                                    <td><?= $arr['username'] ?></td>
                                    <td class="text-nowrap" style="">
                                        <a href="" class="btn user edit-user" data-id="<?= $arr['account_id']?>">Edit Profile</a>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div>
        </div>
    </div>
    
</div>
       
    

