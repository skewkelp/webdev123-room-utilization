<?php
require_once 'database.class.php';

class Schedule {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Function to get all schedules with related information
    public function getAllSchedules($roomCode = null, $roomNo = null) {
        try {
            $sql = "SELECT 
                        sched.class_id AS class_id,
                        CONCAT(sched.room_code, ' ',sched.room_no) AS room_name,
                        CONCAT(class.course_abbr, class.year_level, class.section) AS section_name,
                        CONCAT(class.subject_id,' ', class.subject_type) AS subject_code,
                        sd.description AS subject_name,
                        CONCAT(a.first_name, ' ', a.last_name) AS instructor_name,
                        sched.start_time AS start_time,
                        sched.end_time AS end_time,
                        sched.day AS class_day

                    FROM class_schedule sched
                    LEFT JOIN 
                        room_list rl ON sched.room_code = rl.room_code AND sched.room_no = rl.room_no
                    LEFT JOIN 
                        class_details class ON sched.class_id = class.class_id AND sched.subject_type = class.subject_type 
                    LEFT JOIN 
                        subject_details sd ON class.subject_id = sd.subject_code
                    LEFT JOIN 
                        faculty_list fl ON class.teacher_assigned = fl.faculty_id
                    LEFT JOIN 
                        user_list ul ON fl.user_id = ul.user_id
                    LEFT JOIN 
                        account a ON ul.user_id = a.account_id
                    
                    ";

            if($roomCode != null && $roomNo != null){
                $sql .= " WHERE rl.room_code = :roomCode AND rl.room_no = :roomNo
                    GROUP BY sched.class_id, room_name, sd.subject_code, sd.description, 
                             a.first_name, a.last_name, sched.start_time, sched.end_time
                    ORDER BY room_name, sched.start_time";
            }else{
                $sql .= " GROUP BY sched.class_id, room_name, sd.subject_code, sd.description, 
                             a.first_name, a.last_name, sched.start_time, sched.end_time
                    ORDER BY room_name, sched.start_time";
            }

            $query = $this->db->connect()->prepare($sql);

            if($roomCode != null && $roomNo != null){
                $query->bindParam(':roomCode', $roomCode);
                $query->bindParam(':roomNo', $roomNo);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Function to get schedule by ID
    public function getScheduleById($class_id, $subject_type) {
        try {
            $sql = "SELECT 
                sched.class_id,
                sched.subject_type,
                sched.room_code,
                sched.room_no,
                sd.subject_code,
                sd.description as subject_name,
                class.teacher_assigned,
                sched.start_time,
                sched.end_time,
                sched.day,
                sched.status,
                sched.remarks,
                sched.semester,
                sched.school_year
            FROM class_schedule sched
            JOIN class_details class ON sched.class_id = class.class_id 
                AND sched.subject_type = class.subject_type
            JOIN subject_details sd ON class.subject_id = sd.subject_code
            WHERE sched.class_id = :class_id AND sched.subject_type = :subject_type";

            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':class_id', $class_id);
            $query->bindParam(':subject_type', $subject_type);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Function to update schedule
    public function updateSchedule($scheduleData) {
        try {
            $this->db->connect()->beginTransaction();

            // Update schedule details
            $sql = "UPDATE schedule 
                    SET room_id = :room_id,
                        subject_id = :subject_id,
                        instructor_id = :instructor_id
                    WHERE id = :schedule_id";

            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([
                ':schedule_id' => $scheduleData['schedule_id'],
                ':room_id' => $scheduleData['room_id'],
                ':subject_id' => $scheduleData['subject_id'],
                ':instructor_id' => $scheduleData['instructor_id']
            ]);

            // Update schedule time
            $sql = "UPDATE schedule_time 
                    SET start_time = :start_time,
                        end_time = :end_time
                    WHERE schedule_id = :schedule_id";

            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([
                ':schedule_id' => $scheduleData['schedule_id'],
                ':start_time' => $scheduleData['start_time'],
                ':end_time' => $scheduleData['end_time']
            ]);

            // Update schedule days
            $sql = "DELETE FROM schedule_day
                    WHERE schedule_time_id IN (
                        SELECT id FROM schedule_time WHERE schedule_id = :schedule_id
                    )";

            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([':schedule_id' => $scheduleData['schedule_id']]);

            $sql = "INSERT INTO schedule_day (day_id, schedule_time_id)
                    SELECT :day_id, st.id
                    FROM schedule_time st
                    WHERE st.schedule_id = :schedule_id";

            $stmt = $this->db->connect()->prepare($sql);
            foreach ($scheduleData['days'] as $day_id) {
                $stmt->execute([
                    ':day_id' => $day_id,
                    ':schedule_id' => $scheduleData['schedule_id']
                ]);
            }

            $this->db->connect()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->connect()->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    // Function to get available rooms
    public function getAvailableRooms($day_id, $start_time, $end_time) {
        try {
            $sql = "SELECT DISTINCT rl.id, rl.room_name
                    FROM room_list rl
                    WHERE rl.id NOT IN (
                        SELECT sched.room_id
                        FROM class_schedule sched
                        JOIN schedule_time ct ON ct.schedule_id = sched.id
                        JOIN schedule_day sd ON sd.schedule_time_id = ct.id
                        WHERE sd.day_id = :day_id
                        AND (
                            (ct.start_time <= :start_time AND ct.end_time > :start_time)
                            OR (ct.start_time < :end_time AND ct.end_time >= :end_time)
                            OR (:start_time <= ct.start_time AND :end_time >= ct.end_time)
                        )
                    )
                    ORDER BY rl.room_name";

            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([
                ':day_id' => $day_id,
                ':start_time' => $start_time,
                ':end_time' => $end_time
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Function to check schedule conflicts
    public function checkScheduleConflict($room_id, $day_id, $start_time, $end_time, $exclude_id = null) {
        try {
            $sql = "SELECT COUNT(*) AS conflict_count
                    FROM schedule sched
                    JOIN schedule_time ct ON ct.schedule_id = sched.id
                    JOIN schedule_day sd ON sd.schedule_time_id = ct.id
                    WHERE sched.room_id = :room_id
                    AND sd.day_id = :day_id
                    AND sched.id != COALESCE(:exclude_id, 0)
                    AND (
                        (ct.start_time <= :start_time AND ct.end_time > :start_time)
                        OR (ct.start_time < :end_time AND ct.end_time >= :end_time)
                        OR (:start_time <= ct.start_time AND :end_time >= ct.end_time)
                    )";

            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([
                ':room_id' => $room_id,
                ':day_id' => $day_id,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':exclude_id' => $exclude_id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['conflict_count'] > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return true; // Return true to indicate potential conflict in case of error
        }
    }
}
?>


