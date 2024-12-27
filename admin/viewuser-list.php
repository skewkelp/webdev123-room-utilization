<?php
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line
  
?>

<div class="container-fluid">
    <?php
        require_once '../classes/account.class.php';
        $userObj = new Account();  
       
    ?>

    <div class="row admin">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">USER LIST</h1>
            </div>
        </div>
    </div>
    <div class="row admin">
        <div class="col-12">
            <div class="card p-4">
                
                <div class="card-body p-1 pt-2">
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        
                        <div class="input-group w-25">
                            <input type="text" id="search-subject" class="form-control form-control-light" placeholder="Search class details...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                    
                        <!-- 1. Add Class Details   -->
                        <a id="add-subject-details" href="#" class="btn admin btn-primary open-modal-button">Add USER</a>
                    </div>

                    <div class="table-responsive">
                        <!-- 2. Table Class Details -->
                        <table id="table-user-list" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>username</th>
                                    <th>Admin Properties</th>
                                    <th>Staff Properties</th>
                                    <th style="width: 30%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                                    $i = 1;
                                    $array = $userObj->showuserList();
                                    
                                    foreach ($array as $arr) {
                                ?>
                                    <tr>
                                        <td><?= $arr['user_id'] ?></td>
                                        <td><?= $arr['username'] ?></td>
                                        <td><?= $arr['is_admin'] ?></td>
                                        <td><?= $arr['is_staff'] ?></td>
                                        <td class="text-nowrap">
                                            <a href="" class="btn admin edit-room-status" data-id="{$arr['user_id']}">Edit</a>
                                            <a href="" class="btn admin delete delete-room-status" data-id="{$arr['user_id']}">X</a>
                                        </td>
                                    </tr>
                                <?php
                                    
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>


            </div>
        </div>
    </div>



</div>