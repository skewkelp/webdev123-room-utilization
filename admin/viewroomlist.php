<?php
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line
require_once '../classes/room.class.php';


$roomObj = new Room();   
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">ROOM LIST</h1>
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-light" id="dash-daterange">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-calendar3"></i>
                            </span>
                        </div>
                    </form>
                </div>
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
                    
                    <form class="d-flex flex-column align-items-start gap-1">
                        <div class="d-flex align-items-center">
                            <label for="roomname-filter" class="label-text">Room Name:</label>
                            <select id="roomname-filter" class="form-select">
                                <option value="choose">Choose...</option>
                                <option value="">All</option>
                                <?php
                                $roomList = $roomObj->fetchroomList();
                                foreach ($roomList as $rmlst) {
                                ?>
                                    <option value="<?= $rmlst['room_name'] ?>"><?= $rmlst['room_name']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <label for="roomtype-filter" class="me-2 label-text">Room Type:</label>
                            <select id="roomtype-filter" class="form-select">
                                <option value="choose">Choose...</option>
                                <option value="">All</option>
                                <?php
                                $roomTypeList = $roomObj->fetchroomType();
                                foreach ($roomTypeList as $rmt) {
                                ?>
                                    <option value="<?= $rmt['r_type'] ?>"><?= $rmt['r_type'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    
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
                        <a id="add-room" href="#" class="btn btn-primary brand-bg-color">Add Room</a>
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
                                        <td><?= $arr['room_name'] ?></td>
                                        <td><?= $arr['room_details'] ?></td>
                                        <td class="text-nowrap">
                                            <a href="" class="btn room-schedule">Schedule</a>
                                            <a href="" class="btn room-status">Status</a>
                                            <?php if (hasPermission('admin')): ?>
                                            <a href="" class="btn admin edit-room" data-id="<?= $arr['id'] ?>">Edit</a>
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
