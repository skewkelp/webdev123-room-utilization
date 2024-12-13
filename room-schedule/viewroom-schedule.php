<?php
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line
  
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">ROOM SCHEDULE</h1>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <?php
                    require_once '../classes/room-status.class.php';
                    session_start();
                    $roomObj = new RoomStatus();   
                ?>
                
                <div class="card-body p-1 pt-2">
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        <div class="input-group w-100">
                            <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search ...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                        <?php if (hasPermission('admin')): ?>
                            <a id="add-room-status" href="#" class="btn admin btn-primary open-modal-button">Add Room Status</a>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table id="table-room-schedule" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room Name</th>
                                    <th>Room Type</th>
                                    <th>Subject Code</th>
                                    <th>Subject Type</th>
                                    <th>Section Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $i = 1;
                                    $array = $roomObj->showAllStatus();
                                    
                                    foreach ($array as $arr) {
                                ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $arr['room_name'] ?></td>
                                        <td><?= $arr['room_type'] ?></td>
                                        <td><?= $arr['subject_code'] ?></td>
                                        <td><?= $arr['subject_type'] ?></td>
                                        <td><?= $arr['section_name'] ?></td>
                                        <td><?= $arr['start_time'] ?></td>
                                        <td><?= $arr['end_time'] ?></td>
                                        <td><?= $arr['faculty_name'] ?></td>
                                        <td><?= $arr['room_status'] ?></td>
                                        <td class="text-nowrap">
                                            <a href="" class="btn room-schedule">Schedule</a>
                                            <a href="" class="btn staff room-status">Occupy</a>
                                            <?php if (hasPermission('admin')): ?>
                                                <a href="" class="btn admin edit-room-status" data-id="<?= $arr['class_status_id'] ?>">Edit</a>
                                                <a href="" class="btn admin display-row">Display</a> <!-- hidden or displayed  -->
                                                <a href="" class="btn admin delete delete-room-status"data-id="<?= $arr['class_status_id'] ?>">X</a>
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