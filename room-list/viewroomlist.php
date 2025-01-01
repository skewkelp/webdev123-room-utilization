<?php
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line

?>

<div class="container-fluid">
    <!-- <div class="row admin">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">ADMIN</h1>
            </div>
        </div>
    </div>

    <div class="row staff">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">FACULTY</h1>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">ROOM LIST</h1>
            </div>
        </div>
    </div>
    
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <?php
                    require_once '../classes/room.class.php';
                    session_start();
                    $roomObj = new Room();   
                ?>
                <div class="d-flex justify-content-between align-items-center">
                   
                    <form class="d-flex me-2">
                        <div class="input-group w-100">
                            <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search room...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                    </form>

                    <?php if (hasPermission('admin')): ?>
                    <div class="page-title-right d-flex align-items-center" admin>
                        <a id="add-room" href="#" class="btn btn-primary admin">Add Room</a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- <form class="d-flex card-header justify-content-between align-items-center w-100 px-2">
                    <p>header</p>
                </form> -->

                <div class="card-body p-1 pt-2">
                    <div class="table-responsive">
                        <table id="table-room-list" class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room Name</th>
                                    <th>Room Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                            <?php
                                $i = 1;
                                $array = $roomObj->showAllrooms();

                                foreach ($array as $arr) {
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $arr['room_code'] . ' ' . $arr['room_no'] ?></td>
                                    <td><?= $arr['room_details'] ?></td>
                                    <td class="text-nowrap">
                                        <a href="room-schedule" data-room="<?=  $arr['room_code'] . ' ' . $arr['room_no']?>" class="btn user room-schedule">Schedule</a>
                                        <?php if (hasPermission('admin')): ?>
                                        <a href="" class="btn admin edit-room" data-roomcode="<?= $arr['room_code'] ?>" data-roomno="<?= $arr['room_no'] ?>">Edit</a>
                                        <?php endif; ?>
                                    </td>
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
</div>
