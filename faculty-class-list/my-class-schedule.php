<?php
session_start();
require_once '../tools/functions.php';  // Add this line

?>

<div class="container-fluid">
    <?php
        require_once '../classes/room-status.class.php';
        
        $roomObj = new RoomStatus();   
        
        $split_PK = $semester_PK = '';
        
        $semester_PK = $_SESSION['selected_semester_id'];
        $split_PK = explode('|', $semester_PK);
        $roomObj->semester = $split_PK[0];
        $roomObj->school_year = $split_PK[1];

        $semesterText = $schoolYearText = '';
        $semesterText = clean_input($split_PK[0]);
        $schoolYearText = clean_input($split_PK[1]);

        if($semesterText == '1'){
          $semesterText = '1st Sem|';
        }else if(semesterText == '2'){
          $semesterText = '2nd Sem|';
        }else{
          $semesterText = 'ERROR! semester not initialized';
        }

    ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="page-title">My Class Schedules: <?= $_SESSION['account']['first_name'] . ' ' . $_SESSION['account']['last_name'] ?></h1>
                <h1 class="page-title"><?= $semesterText . $schoolYearText?></h1>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <div class="card-body p-1 pt-2">
                    <div class="table-responsive">
                        <table id="table-class" class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Class ID</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Day</th>
                                    <th>Room</th>
                                    <th>Status</th>
                                    <th style="width: 30%;">Remarks</th>
                                    <th style="width: 30%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $user_id = clean_input($_SESSION['account']['account_id']);
                                $roomObj->faculty_id = $user_id;
                                $array = $roomObj->showFacultyClassSchedules();
                                foreach ($array as $arr) {
                            ?>
                                <tr>
                                    <td><?= $arr['class_id']?></td>
                                    <td><?= $arr['subject_code'] . ' ' . $arr['subject_type']  ?></td>
                                    <td><?= $arr['section_name'] ?></td>
                                    <td><?= $arr['start_time'] ?></td>
                                    <td><?= $arr['end_time'] ?></td>
                                    <td><?= $arr['class_day'] ?></td>
                                    <td><?= $arr['room_name'] ?></td>
                                    <td><?= $arr['room_status'] ?></td>
                                    <td><?= $arr['remarks'] ?></td>
                                    <td class="text-nowrap">
                                        <a href="room-schedule" data-room="<?= $arr['room_name']?>" class="btn room-schedule">Schedule</a>
                                        <a href="" id="class-occupy" class="btn staff" data-classid="<?= $arr['class_id'] ?>" data-subjecttype="<?= $arr['subject_type'] ?>" data-classday="<?= $arr['class_day'] ?>"  data-status="<?= $arr['room_status']?>">Occupy</a>
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
       
    

