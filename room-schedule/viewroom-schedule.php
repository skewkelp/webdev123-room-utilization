<?php
require_once '../tools/functions.php';  // Add this line
session_start();
$semester_PK = $_SESSION['selected_semester_id'];
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
                <div class="card-body p-1 pt-2">
                    
                    <form id="filter-room" class="d-flex ct1 flex-row align-items-start justify-content-between" data-semester="<?= $semester_PK?>">
                        <div class="d-flex align-items-center w-25 gap-2">
                            <label for="room" class="text-nowrap">Room Name:</label>
                            <select id="room" class="form-control">
                                <option value="">Select Room</option>
                                <?php
                                require_once '../classes/room.class.php';
                                $roomObj = new Room();
                                $rooms = $roomObj->fetchroomList();
                                foreach ($rooms as $room) {
                                    echo "<option value='{$room['room_name']}'>{$room['room_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Filter Button -->
                        <div class="">
                            <button type="submit" class="btn btn-primary user">Filter</button>
                        </div>
                    </form>
                    <!-- Room Name Title -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="page-title-box">
                            <h4 class="card-title" id="room-name-title">ROOM NAME</h4>
                        </div>
                        <div class="page-title-box">
                            <h4 class="card-title" id="chosen-semester">SEMESTER</h4>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <div id="schedule-count" class="alert text-danger" style="display: none;">There are no class scheduled for this room.</div>

                        <table id="table-room-schedule" class="table table-centered table-nowrap table-hover mb-0">
                            <thead id="schedule-header">
                                <!-- <tr>
                                    <th>Time</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                </tr> -->
                            </thead>
                            <tbody id="schedule-data">
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div>
        </div>
    </div>
</div>



<style>
:root{
    --brand-color: rgba(44, 59, 20);

}
.schedule-cell {
    padding: 0 !important;
    vertical-align: middle !important;
    width: 14.28%;
    position: relative;
    border: 1px solid #dee2e6;
    height: 80px;
}


.time-slot {
    font-weight: bold;
    text-align: center;
    background-color: #f8f9fa;
    vertical-align: middle !important;
    width: 100px;
    padding: 8px !important;
}

.schedule-content {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 8px;
}

.subject {
    font-weight: bold;
    margin-bottom: 3px;
    font-size: 0.9em;
    text-align: center;
    color: white;
}

.section {
    margin-bottom: 2px;
    font-size: 0.85em;
    text-align: center;
    color: white;
}

.teacher {
    font-size: 0.8em;
    font-style: italic;
    color: white;
    text-align: center;
}

.card-title {
    color: black;
    padding: 8px 20px;
    border-radius: 4px;
    display: inline-block;
}

#schedule-table {
    table-layout: fixed;
    border-collapse: collapse;
    width: 100%;
}

#schedule-table th {
    text-align: center;
    background-color:rgb(247, 11, 11);
    padding: 10px;
    border: 1px solid #dee2e6;
}

#schedule-table td {
    border: 1px solid #dee2e6;
}

.schedule-cell.occupied {
    background-color: rgb(103, 138, 48) !important;
}

.class-scheduled{
    border: 3px solid black !important;
}

.dt-column-title{
    color:black !important;
}


</style>