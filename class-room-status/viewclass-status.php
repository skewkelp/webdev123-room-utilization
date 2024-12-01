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
                <div class="d-flex justify-content-between align-items-center gap-4">
                    
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        <form id ="room-form" class="d-flex flex-column justify-content-between align-items-start gap-5">
                            <div class="d-flex width flex-column align-items-start gap-1">
                                <div class="d-flex width justify-content-between">
                                    <label for="room-name-filter" class="label-text">Room Name:</label>
                                    <select id="room-name-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- <option value="">All</option> -->
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
                                
                                <div class="d-flex width align-items-center">
                                    <label for="room-type-filter" class="me-2 label-text">Room Type:</label>
                                    <select id="room-type-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- <option value="">All</option> -->
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

                                <div class="d-flex width align-items-center">
                                    <label for="room-status-filter" class="me-2 label-text">Status:</label>
                                    <select id="room-status-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- <option value="">All</option> -->
                                        <?php
                                        $statusList = $roomObj->fetchstatusOption();
                                        foreach ($statusList as $sl) {
                                        ?>
                                            <option value="<?= $sl['status_desc'] ?>"><?= $sl['status_desc'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-row justify-content-between align-items-center" style="width: 60%;">
                                <label class="me-2 label-text">Filter: </label>
                                <div class="d-flex width flex-row justify-content-end align-items-center gap-3">
                                    <button class="btn user btn-primary" value="all">All</button>
                                    <button  class="btn user btn-primary" value="filter">Filter</button>
                                </div>
                            </div>
                        </form>

                        <form id="class-form" class="d-flex flex-column justify-content-between align-items-start gap-3">
                            <div class="d-flex width flex-column align-items-start gap-1">
                                <div class="d-flex width align-items-center">
                                    <label for="subject-code-filter" class="label-text">Subject Code:</label>
                                    <select id="subject-code-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- <option value="">All</option> -->
                                        <?php
                                        $subjectCode = $roomObj->fetchsubjectnameOption();
                                        foreach ($subjectCode as $subco){
                                        ?>
                                            <option value="<?= $subco['subject_code'] ?>"><?= $subco['subject_code']?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="d-flex width align-items-center">
                                    <label for="subject-type-filter" class="me-2 label-text">Subject Type:</label>
                                    <select id="subject-type-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- <option value="">All</option> -->
                                        <?php
                                        $subjectType = $roomObj->fetchsubtypeOption();
                                        foreach ($subjectType as $stype) {
                                        ?>
                                            <option value="<?= $stype['subject_type'] ?>"><?= $stype['subject_type'] ?></option>                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="d-flex width align-items-center">
                                    <label for="section-filter" class="me-2 label-text">Section:</label>
                                    <select id="section-filter" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                
                                <div class="d-flex width align-items-center gap-2" style="padding-left: 20px;">
                                    <label class="me-2 label-text">Option Filter:</label>
                                    <label for="ALL">
                                        <input type="radio" name="options" id="ALL" value="ALL" checked onclick="updateSelectOptions()"> ALL
                                    </label>
                                    <?php
                                    $courses = $roomObj->fetchCourse(); // Fetch courses for radio buttons
                                    foreach ($courses as $crs) {
                                    ?>
                                        <label for="<?= $crs['_name'] ?>">
                                            <input type="radio" name="options" id="<?= $crs['_name'] ?>" value="<?= $crs['id'] ?>" onclick="updateSelectOptions()"> <?= $crs['_name'] ?>
                                        </label>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                                
                            <div class="d-flex flex-row justify-content-between align-items-center" style="width: 60%;">
                                <label class="me-2 label-text">Filter: </label>
                                <div class="d-flex width flex-row justify-content-end align-items-center gap-3">
                                    <button type="submit" class="btn user btn-primary" value="all">All</button>
                                    <button type="submit" class="btn user btn-primary" value="filter">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- <form class="d-flex ct2 me-2">
                        <div class="d-flex width flex-column align-content-between align-items-start gap-5 ">
                            <div class="d-flex width flex-column align-content-between align-items-start gap-1">
                                <div class="d-flex width justify-content-between">
                                    <label for="start-time" class="label-text">Start:</label>
                                    <select id="start-time" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <option value="">All</option>
                                        
                                    </select>
                                </div>
                                    
                                <div class="d-flex width align-items-center">
                                    <label for="end-time" class="me-2 label-text">End: </label>
                                    <select id="end-time" class="form-select">
                                        <option value="choose">Choose...</option>
                                        <option value="">All</option>
                                    </select>
                                </div> 
                            </div>  
                        </div>
                    </form> -->

                    <form class="d-flex ct3 flex-column align-items-start gap-1">
                        
                        <div class="d-flex width align-items-center justify-content-between">
                            <label for="day" class="label-text text-center">Day:</label>
                            <select id="day" class="form-select" >
                                <option value=" ">Choose...</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                    </form>
                </div> 

                <div class="card-body p-1 pt-2">
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        <div class="input-group w-100">
                            <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search ...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                        <a id="add-room-status" href="#" class="btn admin btn-primary open-modal-button">Add Room Status</a>
                    </div>
                    <div class="table-responsive">
                        <table id="table-room-status" class="table table-centered table-nowrap table-hover mb-0">
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
                                            <a href="" class="btn room-status">Occupy</a>
                                            <a href="" class="btn admin edit-room-status" data-id="<?= $arr['class_status_id'] ?>">Edit</a>
                                            <a href="" class="btn admin display-row">Display</a> <!-- hidden or displayed  -->
                                            <a href="" class="btn admin delete delete-room-status"data-id="<?= $arr['class_status_id'] ?>">X</a>
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