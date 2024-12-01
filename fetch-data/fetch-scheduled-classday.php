<?php
require_once '../classes/room-status.class.php'; // Include your class
session_start();

$roomObj = new RoomStatus(); // Create an instance of your Room class

// Get the selected day from the AJAX request
$fweek_day = isset($_POST['fweek_day']) ? $_POST['fweek_day'] : '';

// Fetch data based on the selected day
$array = $roomObj->showAllStatus('', $fweek_day);

if ($array) {
    foreach ($array as $i => $arr) {
    echo "<tr>
            <td>" . ($i + 1) . "</td>
            <td>{$arr['room_name']}</td>
            <td>{$arr['room_type']}</td>
            <td>{$arr['subject_code']}</td>
            <td>{$arr['subject_type']}</td>
            <td>{$arr['section_name']}</td>
            <td>{$arr['start_time']}</td>
            <td>{$arr['end_time']}</td>
            <td>{$arr['faculty_name']}</td>
            <td>{$arr['room_status']}</td>
            <td class='text-nowrap'>
                <a href='' class='btn room-schedule'>Schedule</a>
                <a href='' class='btn staff room-status'>Occupy</a>
                <?php if (hasPermission('admin')): ?>
                    <a href='' class='btn admin edit-room-status' data-id='{$arr['class_status_id']}'>Edit</a>
                    <a href='' class='btn admin display-row'>Display</a>
                    <a href='' class='btn admin delete delete-room-status' data-id='{$arr['class_status_id']}'>X</a>
                <?php endif; ?>
            </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='11'>No data found for the selected day.</td></tr>";
}
?>