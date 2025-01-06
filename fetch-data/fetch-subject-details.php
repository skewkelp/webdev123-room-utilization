<?php
    require_once '../classes/room-status.class.php'; // Include your class

    $roomObj = new RoomStatus(); // Create an instance of your Room class

    // Get the selected day from the AJAX request
    // $selected_day = isset($_POST['selected_day']) ? $_POST['selected_day'] : '';
    $selected_prospectus = $_POST['selected_prospectus'];
    // Fetch data based on the selected day
    $array = $roomObj->showAllSubjectDetails($selected_prospectus);

    if ($array) {
        foreach ($array as $arr) {
        echo "<tr>
            <td>{$arr['subject_id']}</td>
            <td>{$arr['sub_desc']}</td>
            <td>{$arr['total_units']}</td>
            <td>{$arr['lec_units']}</td>
            <td>{$arr['lab_units']}</td>
            <td class='text-nowrap'>
                <a href='' class='btn admin w-50 edit-subject-details'  data-subjectid='{$arr['subject_id']}'>Edit</a>
                <a href='' class='btn admin w-50 delete delete-subject-details' data-subjectid='{$arr['subject_id']}'>Delete</a>
            </td>
        </tr>";
    }
} else {
echo "<tr><td colspan='6'>No data found for the selected Prospectus.</td></tr>";
}
?>