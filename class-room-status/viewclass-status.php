<?php
// Add these at the top of viewroomlist.php
require_once '../tools/functions.php';  // Add this line
  
?>

<div class="container-fluid">
    <?php
        require_once '../classes/room-status.class.php';
        session_start();
        
        $roomObj = new RoomStatus();   
        
        $split_PK = $semester_PK = '';
        
        $semester_PK = $_SESSION['selected_semester_id'];
        $split_PK = explode('|', $semester_PK);
        $roomObj->semester = $split_PK[0];
        $roomObj->school_year = $split_PK[1];
    ?>

    <div class="row admin">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">SUBJECT LIST</h1>
            </div>
        </div>
    </div>
    <div class="row admin">
        <div class="col-12">
            <div class="card p-4">
                
                <div class="card-body p-1 pt-2">
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        
                        <div class="input-group w-25">
                            <input type="text" id="search-class-details" class="form-control form-control-light" placeholder="Search class details...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>

                        <a id="change-semester" href="#" class="btn admin btn-primary open-modal-button">Change Prospectus</a>
                        
                        <div class="input-group w-25">
                            <select name="" id="">
                                <option value="">2024-2025</option>
                            </select>
                        </div>

                        <!-- 1. Add Class Details   -->
                        <a id="add-subject-details" href="#" class="btn admin btn-primary open-modal-button">Add Subject</a>
                    </div>

                    <div class="table-responsive">
                        <!-- 2. Table Class Details -->
                        <table id="table-class-details" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                                    <th>Room</th>
                                    <th style="width: 30%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                               
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>


            </div>
        </div>
    </div>


   
    <div class="row admin">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">CLASS DETAILS LIST</h1>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>

    <div class="row admin">
        <div class="col-12">
            <div class="card p-4">
                
                <div class="card-body p-1 pt-2">
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        
                        <div class="input-group w-25">
                            <input type="text" id="search-class-details" class="form-control form-control-light" placeholder="Search class details...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>

                        <!-- 1. Add Class Details   -->
                        <a id="add-class-details" href="#" class="btn admin btn-primary open-modal-button">Add Class Details</a>
                    </div>
                    <div class="table-responsive">
                        <!-- 2. Table Class Details -->
                        <table id="table-class-details" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                                    <th style="width: 30%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    

                                    $array = $roomObj->showAllClassDetails();
                                    
                                    foreach ($array as $arr) {
                                ?>
                                    <tr>
                                        <td><?= $arr['class_id'] ?></td>
                                        <td><?= $arr['subject_'] ?></td>
                                        <td><?= $arr['section_'] ?></td>
                                        <td><?= $arr['teacher_'] ?></td>
                                        <td class="text-nowrap" style="">
                                            <a href="" class="btn admin w-50 edit-class-details" data-id="<?= $arr['id']?>">Edit</a>
                                            <a href="" class="btn admin w-50 delete delete-class-details" data-id="<?= $arr['id']?>">Delete</a>
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



    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">CLASS STATUS LIST</h1>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>

    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                
                <div class="d-flex justify-content-between align-items-center gap-4">
                    
                    <div class="d-flex ct1 flex-row align-items-start gap-5">
                        <form id ="room-form" class="d-flex flex-column justify-content-between align-items-start gap-5"><!-- #room_form handling filter form-->
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
                                        <option value="OCCUPIED">OCCUPIED</option>
                                        <option value="AVAILABLE">AVAILABLE</option>
                                        
                                        <!-- <option value="">All</option> -->
                                        
                                       
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
                                        <option value="LEC">LEC</option>                                      
                                        <option value="LEC">LAB</option>                                      
                                        <!-- <option value="">All</option> -->
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
                                        <label for="<?= $crs['course_name'] ?>">
                                            <input type="radio" name="options" id="<?= $crs['course_name'] ?>" value="<?= $crs['course_abbr'] ?>" onclick="updateSelectOptions()"> <?= $crs['course_name'] ?>
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
                            <!-- <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search ...">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-search"></i>
                            </span> -->
                        </div>
                        <?php if (hasPermission('admin')): ?>
                            <a id="add-room-status" href="#" class="btn admin btn-primary open-modal-button">Add Room Status</a>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table id="table-room-status" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room</th>
                                    <th>Room Type</th>
                                    <th>Subject</th>
                                    <th>Subject Type</th>
                                    <th>Section</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
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
                                        <td><?= $arr['remarks'] ?></td>
                                        <td class="text-nowrap">
                                            <a href="" class="btn room-schedule">Schedule</a>
                                            <a href="" class="btn staff room-status" data-classid="{$arr['class_id']}" data-classday="{$arr['class_day']}" data-subjecttype="{$arr['subject_type']}">Occupy</a>
                                            <?php if (hasPermission('admin')): ?>
                                                <a href="" class="btn admin edit-room-status" data-classid="{$arr['class_id']}" data-classday="{$arr['class_day']}" data-subjecttype="{$arr['subject_type']}">Edit</a>
                                                <a href="" class="btn admin display-row">Display</a> <!-- hidden or displayed  -->
                                                <a href="" class="btn admin delete delete-room-status" data-classid="{$arr['class_id']}" data-classday="{$arr['class_day']}" data-subjecttype="{$arr['subject_type']}">X</a>
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