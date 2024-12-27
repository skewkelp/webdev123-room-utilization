<?php
require_once '../classes/schedule.class.php';
session_start();

$scheduleObj = new Schedule();
$room_id = isset($_POST['room_id']) ? $_POST['room_id'] : '';
$schedules = $scheduleObj->viewSchedule($room_id);

$timeSlots = [
    '07:00', '07:30', '08:00', '8:30', '09:00', '9:30', '10:00', '10:30', '11:00', 
    '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', 
    '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'
];

$scheduleArray = [];
$rowspans = [];

if ($schedules) {
    foreach ($schedules as $schedule) {
        $startTime = date('H:i', strtotime($schedule['start_time']));
        $endTime = date('H:i', strtotime($schedule['end_time']));
        $day = $schedule['day_id'];
        
        $startIndex = array_search($startTime, $timeSlots);
        $endIndex = array_search($endTime, $timeSlots);
        $span = $endIndex - $startIndex;
        
        if ($startIndex !== false) {
            $scheduleArray[$startTime][$day] = [
                'subject_code' => $schedule['subject_code'],
                'section_name' => $schedule['section_name'],
                'teacher_name' => $schedule['teacher_name'],
                'rowspan' => $span
            ];
            
            for ($i = 1; $i < $span; $i++) {
                $skipTime = $timeSlots[$startIndex + $i];
                $rowspans[$skipTime][$day] = true;
            }
        }
    }
}

// Output schedule rows
foreach ($timeSlots as $time) {
    echo "<tr>";
    echo "<td class='time-slot'>" . date('g:i A', strtotime($time)) . "</td>";
    
    for ($day = 1; $day <= 6; $day++) {
        if (isset($rowspans[$time][$day])) {
            continue;
        }
        
        if (isset($scheduleArray[$time][$day])) {
            $class = $scheduleArray[$time][$day];
            $rowspan = $class['rowspan'] > 1 ? " rowspan='{$class['rowspan']}'" : "";
            
            // Single color for occupied cells
            echo "<td class='schedule-cell occupied'$rowspan>";
            echo "<div class='schedule-content'>";
            echo "<div class='subject'>{$class['subject_code']}</div>";
            echo "<div class='section'>{$class['section_name']}</div>";
            echo "<div class='teacher'>{$class['teacher_name']}</div>";
            echo "</div>";
            echo "</td>";
        } else {
            echo "<td class='schedule-cell'></td>";
        }
    }
    echo "</tr>";
}
?>