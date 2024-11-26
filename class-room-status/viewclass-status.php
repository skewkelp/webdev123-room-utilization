<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">STATUS LIST</h1>
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
                    require_once '../classes/room-status.class.php';
                    session_start();
                    $roomObj = new Room();   
                ?>
                <div class="d-flex justify-content-between align-items-center">
                    
                    <form class="d-flex flex-column align-items-start gap-1">
                        <div class="d-flex align-items-center">
                            <label for="category-filter" class="label-text">Room Name:</label>
                                <select id="category-filter" class="form-select">
                                    <option value="choose">Choose...</option>
                                    <option value="">All</option>
                                    <?php
                                        $roomList = $roomObj->fetchroomList();
                                        foreach ($roomList as $rmlst) {
                                    ?>
                                    <option value="<?= $rmlst['room_type'] . ' ' . $rmlst['room_no'] ?>"><?= $rmlst['room_type'] . ' ' . $rmlst['room_no']  ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <label for="category-filter" class="me-2 label-text">Room Type:</label>
                                <select id="category-filter" class="form-select">
                                    <option value="choose">Choose...</option>
                                    <option value="">All</option>
                                    <?php
                                        $roomTypeList = $roomObj->fetchroomType();
                                        foreach ($roomTypeList as $rmt) {
                                    ?>
                                    <option value="<?= $rmt['type_id'] ?>"><?= $rmt['room_type'] ?></option>
                                    <?php
                                    }
                                    ?>
                            </select>
                        </div>
                    </form>
                    
                    <form class="d-flex me-2">
                        <div class="input-group w-100">
                            <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search products...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                    </form>
                    <div class="page-title-right d-flex align-items-center">
                        <a id="add-room-status" href="#" class="btn btn-primary brand-bg-color">Add Room Status</a>
                    </div>
                </div>
                
                <!-- <form class="d-flex card-header justify-content-between align-items-center w-100 px-2">
                    <p>header</p>
                </form> -->

                <div class="card-body p-1 pt-2">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room Name</th>
                                    <th>Room Type</th>
                                    <th>Subject Code</th>
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
                                $array = $roomObj->showAllrooms();

                                foreach ($array as $arr) {
                                ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $arr['room_name'] ?></td>
                                        <td><?= $arr['room_details'] ?></td>
                                        <td class="text-nowrap">
                                            <a href="" class="btn room-schedule">Schedule</a>
                                            <a href="" class="btn room-status">Occupy</a>
                                            <a href="" class="btn edit-room-status" data-id="<?= $arr['id'] ?>">Edit</a>
                                            <!-- <a href="../admin/roomstatus.php?id=<= $arr['id'] ?>" class="btn room-status">Status</a>
                                            <a href="" class="btn edit-product" data-id="<= $arr['id'] ?>">Edit</a> -->
                                            <!-- data-id="<= $arr['id'] ?>" -->
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
