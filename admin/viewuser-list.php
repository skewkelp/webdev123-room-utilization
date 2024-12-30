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
                    <div class="d-flex ct1 flex-row align-items-start justify-content-between gap-5">
                        
                        <div class="input-group w-25">
                            <input type="text" id="search-subject" class="form-control form-control-light" placeholder="Search user id...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <label for="user-type" class="me-2 mb-0 label-text">Filter:</label>
                            <select id="user-type" class="form-select">
                                <option value="">--filter user type--</option>
                                <option value="Admin-Faculty">Admin-Faculty</option>
                                <option value="Admin">Admin</option>
                                <option value="Faculty">Faculty</option>
                                <option value="Student">Student</option>
                            </select>
                        </div>

                        <!-- 1. Add Class Details   -->
                        <a id="add-user-details" href="" class="btn admin btn-primary open-modal-button">Add USER</a>
                    </div>

                    <div class="table-responsive">
                        <!-- 2. Table Class Details -->
                        <table id="table-user-list" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">User ID</th>
                                    <th class="text-nowrap">username</th>
                                    <th class="text-nowrap">Admin Properties</th>
                                    <th class="text-nowrap">Staff Properties</th>
                                    <th class="text-nowrap">User Type</th>
                                    <th class="text-nowrap">First name</th>
                                    <th class="text-nowrap">Last name</th>
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
                                        <td>
                                            <?php 
                                            $isAdmin = $isStaff = $userType = '';
                                                $isAdmin = $arr['is_admin'];
                                                $isStaff = $arr['is_staff'];
                                                if($isAdmin == '1' && $isStaff == '1'){
                                                    $userType = 'Admin-Faculty';
                                                }else if($isAdmin == '0' && $isStaff == '1'){
                                                    $userType = 'Faculty';
                                                }else if($isAdmin == '0' && $isStaff == '0'){
                                                    $userType = 'Student';
                                                }else if($isAdmin == '1' && $isStaff == '0'){
                                                    $userType = 'Admin';
                                                }
                                                else{
                                                    $userType = 'Error';
                                                }

                                                echo $userType; 
                                            ?>
                                        </td>
                                        <td><?= $arr['first_name'] ?></td>
                                        <td><?= $arr['last_name'] ?></td>
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