<?php
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
                    <table class="table table-hover" id="userTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
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
                                    <td><?= $arr['first_name'] ?></td>
                                    <td><?= $arr['last_name'] ?></td>
                                    <td><?= $arr['username'] ?></td>
                                    <td><?= $arr['role'] ?></td>
                                    <td class="text-nowrap">
                                        <a href="" class="btn room-schedule">Schedule</a>
                                        <a href="" class="btn room-status">Status</a>
                                        <?php if (hasPermission('admin')): ?>
                                        <a href="" class="btn admin edit-room" data-id="<?= $arr['id'] ?>">Edit</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- <td>" . htmlspecialchars($user['id']) . </td>;
                                <td>" . htmlspecialchars($user['first_name']) . "</td>";
                                <td>" . htmlspecialchars($user['last_name']) . "</td>";
                                <td>" . htmlspecialchars($user['username']) . "</td>";
                                <td>" . htmlspecialchars($user['role']) . "</td>";
                                <td>" . ($user['is_admin'] ? 'Active' : 'Inactive') . "</td>"; // Assuming is_admin indicates status
                                </tr>" -->
                               
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
    <?php require_once '../includes/_footer.php'; ?>
    <script src="../js/profile.js"></script>
</div>
       
    


