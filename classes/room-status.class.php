<?php

require_once 'database.class.php';

class RoomStatus{
    public $semester = '';
    public $school_year = '';

    public $week_day = '';
    
    public $id = '';
    public $room_code = '';
    //room_list
    public $room_name = '';
    public $room_type = '';
    

    //subject_Details PK
    public $subject_code = '';
    
    
    public $class_name = '';
    public $status = '';
   
    
    // Properties for class details
    public $class_id = ''; // PK class_details
    public $subject_type = '';//PK

    public $subject_id = '';

    public $section_id = '';
    public $course_abbr = '';
    public $year_level = '';
    public $section = '';

    public $teacher_assigned = ''; 
    public $room_id = '';

    public $original_class_id = '';
    public $original_subject_id = '';

    public $start_time = '';
    public $end_time = ''; 
    public $day_id = '';

    public $class_status_id = '';

    // Properties for IDs and logs
    public $class_time_id = ''; // PK class_time
    public $class_day_id = '';
    public $log_cid = []; // Log for class IDs
    public $log_ctid = []; // Log for class time IDs
    public $log_cdid = []; // Log for class day IDs
    public $log_day = []; // Log for day IDs
    public $log_sid = []; // Log for day IDs

    protected $db;

    function __construct(){
        $this->db = new Database();
    }


    //NEW QUERIES UPDATED
    function updateClassDetails(){
        $sql = "UPDATE class_details 
            SET id = :class_id, 
                subject_id = :subject_id, 
                section_id = :section_id, 
                teacher_assigned = :teacher_id, 
                room_id = :room_id 
            WHERE id = :original_class_id AND subject_id = :original_subject_id;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':section_id', $this->section_id);
        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $query->bindParam(':room_id', $this->room_id);
        $query->bindParam(':original_class_id', $this->original_class_id);
        $query->bindParam(':original_subject_id', $this->original_subject_id);
        $query->execute();
        return true;
    }

    function updateClassTime(){
        $sql = "UPDATE class_time 
            SET class_id = :class_id, 
                subject_id = :subject_id, 
                start_time = :start_time, 
                end_time = :end_time 
            WHERE id = :class_time_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':class_time_id', $this->class_time_id);
        $query->execute();
        return true;
    }


    function updateClassDay(){
        $sql = "UPDATE class_day SET day_id = :day_id WHERE id = :class_day_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':day_id', $this->day_id);
        $query->bindParam(':class_day_id', $this->class_day_id);
        $query->execute();
        return true;
    }

 

    //UPDATED 
    function insertClassDetails(){

        $sql = "INSERT INTO class_details (class_id, subject_type, subject_id, course_abbr, year_level, section, teacher_assigned, semester, school_year) VALUES (:class_id, :subject_type, :subject_id, :course_abbr, :year_level, :section, :teacher_id, :semester, :school_year);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->execute();
        return true;
    }

    
    function insertClassTime(){
        $sql = "INSERT INTO class_time (class_id, subject_id, start_time, end_time) VALUES (:class_id, :subject_id, :start_time, :end_time);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->execute();
        $this->class_time_id = $this->db->connect()->lastInsertId();

        return $this->class_time_id;
    }

    function insertClassDay(){
        $sql = "INSERT INTO class_day (class_time_id, day_id) VALUES (:class_time_id, :day_id);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_time_id', $this->class_time_id);
        $query->bindParam(':day_id', $this->day_id);
        $query->execute();
        $this->class_day_id = $this->db->connect()->lastInsertId();

        $this->insertStatus();

        return true;
    }

    function insertStatus(){
        $sql = "INSERT INTO scheduled_statuses (class_day_id, semester, school_year) VALUES (:class_day_id, :semester, :school_year);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_day_id', $this->class_day_id);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->execute();

        return true;
    }


    
    public $original_day_id = '';

   
    function showTeacherSchedule(){
        $sql = "SELECT
                stat.class_day_id AS class_status_id,
                cday.id AS cday_id,
                d.day AS week_day,
                room.room_name AS room_name,
                rtype.room_description AS room_type,
                sub.subject_code AS subject_code,
                stdesc.type AS subject_type,
                sec.section_name AS section_name,
                ctime.start_time AS start_time,
                ctime.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                sdesc.description AS room_status

            FROM
                semester sem
            LEFT JOIN 
                scheduled_statuses stat ON sem.semester = stat.semester AND sem.school_year = stat.school_year
            LEFT JOIN 
                status_description sdesc ON stat.status_desc_id = sdesc.id
            LEFT JOIN 
                class_day cday ON stat.class_day_id = cday.id
            LEFT JOIN
                _day d ON cday.day_id = d.id
            LEFT JOIN
                class_time ctime ON cday.class_time_id = ctime.id
            LEFT JOIN
                class_details class ON ctime.class_id = class.id AND ctime.subject_id = class.subject_id
            LEFT JOIN
                room_list room ON class.room_id = room.id
            LEFT JOIN
                room_type rtype ON room.type_id = rtype.id
            LEFT JOIN
                section_details sec ON class.section_id = sec.id
            LEFT JOIN
                course_details course ON sec.course_id = course.id
            LEFT JOIN
                subject_details sub ON class.subject_id = sub.id
            LEFT JOIN
                subject_type_description stdesc ON sub.type_id = stdesc.id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.id
            LEFT JOIN 
                account acc ON fac.account_id = acc.id
                
            WHERE sem.semester = :semester AND sem.school_year = :school_year
            AND class.teacher_assigned = :teacher_id
        
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->bindParam(':teacher_id', $this->teacher_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;


    }

    function showAllStatus($selectedDay = null){
        $sql = 
            "SELECT
                sched.class_id AS class_id,
                sched.subject_type AS subject_type,
                sched.day AS class_day,

                CONCAT(sched.room_code, ' ', sched.room_no) AS room_name,
                rtype.room_description AS room_type,

                class.subject_id AS subject_code,

                CONCAT(class.course_abbr, class.year_level, class.section) AS section_name,

                sched.start_time AS start_time,
                sched.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                
                sched.status AS room_status,
                sched.remarks AS remarks

            FROM
                semester sem
            LEFT JOIN 
                class_schedule sched ON sem.semester = sched.semester AND sem.school_year = sched.school_year
            LEFT JOIN 
                class_details class ON sched.class_id = class.class_id  AND sched.subject_type = class.subject_type
            LEFT JOIN
                room_list room ON sched.room_code = room.room_code AND sched.room_no = room.room_no
            LEFT JOIN
                room_type rtype ON room.room_code = rtype.room_type_id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN 
                user_list user ON fac.user_id = user.user_id
            LEFT JOIN 
                account acc ON user.user_id = acc.account_id
        
                
            WHERE sem.semester = :semester AND sem.school_year = :school_year
        
        ;";

        if($selectedDay){
            $sql .= " AND sched.day = :selectedDay";
        }
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        
        if($selectedDay){
            $query->bindParam(':selectedDay', $selectedDay);
        }


        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }


    function showAllClassDetails(){
        $sql = 
            "SELECT 
                class.class_id AS class_id,
                CONCAT (class.class_id ,'|', class.subject_type) AS id,
                CONCAT(class.subject_id,' ', class.subject_type) AS subject_, 
                CONCAT(class.course_abbr, class.year_level, class.section) AS section_, 
                CONCAT(acc.last_name,', ',acc.first_name) AS teacher_ 

            FROM class_details class 
            LEFT JOIN section_details sec ON class.course_abbr = sec.course_abbr AND class.year_level = sec.year_level AND class.section = sec.section
            LEFT JOIN subject_details sub ON class.subject_id = sub.subject_code
            LEFT JOIN faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN user_list user ON fac.user_id = user.user_id
            LEFT JOIN account acc ON user.user_id = acc.account_id
            LEFT JOIN semester sem ON class.semester = sem.semester

            WHERE sem.semester = :semester AND sem.school_year = :school_year
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);

        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }
    
    //check if subject has a LEC and LAB units
    function checkSubjectType($subject_id, $type){
        $sql = "SELECT subject_id, lec_units, lab_units
            FROM subject_details sub
            WHERE subject_code = :subject_id       
          ";
        if($type="LEC"){
            $sql .= " AND lec_units > 0";
        }else if($type="LAB"){
            $sql .= " AND lab_units > 0";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_id', $this->subject_id);

        if ($query->execute()) {
            $data = $query->fetch();

            if($type="LEC"){
                return $data ? $data['lec_units'] : null;
            }else if ($type="LAB"){
                return $data ? $data['lab_units'] : null;
            }
        }
        return null;
    }
    

    //cannot have same class id and subject id
    function checkExistingClassDetailsPK($recordID){
        $sql = "SELECT 
            class.class_id AS class_id, 
            CONCAT(class.subject_id,' ', class.subject_type) AS subject_,
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_
        FROM class_details class
        
        WHERE class.class_id = :class_id
        AND (class.subject_id = :subject_id AND class.subject_type)
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $recordID);
        $query->bindParam(':subject_id', $this->subject_id);
        
        if ($query->execute()) {
            $data = $query->fetch();
            return $data ?['class_id' => $data['class_id'], 'section_' => $data['section_'], 'subject_' => $data['subject_']] : null;  // Return just the ID if found
        }
        return null;
    }

 
    //CHECK IF EXISTING SUBJECT EXIST ON A SECTION
    function checkSubjectSectionExisting($excludeID){
        $sql = "SELECT class.class_id AS class_id
        FROM class_details class
        WHERE subject_id = :subject_id AND (course_abbr = :course_abbr AND year_level = :year_level AND section = :section)";
        
        if($excludeID != null){
            $sql .= " AND class.id != :class_id";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        
        if($excludeID != null){
            $query->bindParam(':class_id', $excludeID);   
        }

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? $data['class_id'] : null;
        }
        return null;
    }

    function checkClassIDExisting($recordID){
        $sql = "SELECT 
            DISTINCT class.class_id AS class_id, 
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_

        FROM class_details class 

        WHERE class.class_id = :class_id
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $recordID);

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? $data['section_'] : null; 
        }
        return null;

    }


    //CHECK IF EXISTING SUBJECT_NAME AND SECTION_NAME ALREADY EXIST, condition for class id when it can be unique
    // function checkConditionClassDetailPK(){
    //     $sql = "SELECT
    //         DISTINCT sub.subject_code AS subject_code,
    //         class.class_id AS class_id,
    //         sec.section_name AS section_name

    //     FROM class_details class
    //     LEFT JOIN subject_details sub ON class.subject_id = sub.id
    //     LEFT JOIN subject_type_description stdesc ON sub.type_id = stdesc.id
    //     LEFT JOIN section_details sec ON class.section_id = sec.id

    //     WHERE sub.subject_code = :subject_code AND class.section_id = :section_id
    //     ;";

    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':subject_code', $this->subject_code);
    //     $query->bindParam(':section_id', $this->section_id);
    //     $query->execute();
    //     $data = $query->fetch();
    //     return $data ? ['class_id' => $data['class_id'], 'subject_code' => $data['subject_code'], 'section_name' => $data['section_name']] : null;
    // }
    function checkConditionClassDetailPK(){
        $sql = "SELECT
            DISTINCT class.subject_id AS subject_id,
            class.class_id AS class_id,
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_name

        FROM class_details class

        WHERE class.subject_id = :subject_id AND (class.course_abbr = :course_abbr AND class.year_level = :year_level AND class.section = :section)
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        $query->execute();
        $data = $query->fetch();
        return $data ? ['class_id' => $data['class_id'], 'subject_id' => $data['subject_id'], 'section_name' => $data['section_name']] : null;
    }

    //CHECK IF AN EXISTING ROW ALREADY EXIST ON TABLE
    function checkExistingClassTime($exclude_class_id = null, $exclude_subject_id = null){
        $sql = "SELECT      
            ct.class_id AS class_id,
            d.day AS day_name,
            ct.start_time AS start_time,
            ct.end_time AS end_time

        FROM class_day cday
        LEFT JOIN class_time ct ON cday.class_time_id = ct.id
        LEFT JOIN _day d ON cday.day_id = d.id

        WHERE cday.day_id = :day_id
        AND (
            (ct.start_time <= :end_time AND ct.end_time >= :start_time)
            OR (ct.start_time >= :start_time AND ct.start_time < :end_time)
            OR (ct.end_time > :start_time AND ct.end_time <= :end_time)
        )";

        if ($exclude_class_id !== null && $exclude_subject_id !== null) {
            $sql .= " AND (ct.class_id != :exclude_class_id AND ct.subject_id != :exclude_subject_id)";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':day_id', $this->day_id);
        
        if ($exclude_class_id !== null) {
            $query->bindParam(':exclude_class_id', $exclude_class_id);
            $query->bindParam(':exclude_subject_id', $exclude_subject_id);
        }
        
        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? [$data['class_id'], $data['day_name'], $data['start_time'], $data['end_time']] : null;
        } 
        return null;
    }


    function checkExistingClassDayID(){
        $sql = "SELECT (cday.id) AS class_day_id
        FROM class_day cday
        WHERE cday.day_id = :day_id AND cday.class_time_id = :class_time_id;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':day_id', $this->day_id);
        $query->bindParam(':class_time_id', $this->class_time_id);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }   

    //fetch room list record
    function fetchroomlistRecord($recordID){
        $sql = "SELECT * FROM room_list WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }


    function fetchclassDetailsRecord($classID, $subjectID){
        // $sql = "SELECT * FROM class_details WHERE id = :recordID;";
        $sql = 
            "SELECT 
                cl.id AS id,
                cl.subject_id,

                CONCAT(subd.subject_code,' ', stdesc.type) AS subject_, 
                cl.subject_id AS subject_id,

                secd.section_name AS section_, 
                cl.section_id AS section_id,
                
                CONCAT(acc.last_name,', ',acc.first_name) AS teacher_, 
                cl.teacher_assigned AS teacher_id,
                
                rl.room_name AS room_,
                cl.room_id AS room_id


            FROM class_details cl 
            LEFT JOIN room_list rl ON cl.room_id = rl.id
            LEFT JOIN section_details secd ON cl.section_id = secd.id
            LEFT JOIN subject_details subd ON cl.subject_id = subd.id
            LEFT JOIN subject_type_description stdesc ON subd.type_id = stdesc.id
            LEFT JOIN faculty_list fac ON cl.teacher_assigned = fac.id
            LEFT JOIN account acc ON fac.account_id = acc.id
            
            WHERE cl.id = :classID AND cl.subject_id = :subjectID
        ;";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':classID', $classID);
        $query->bindParam(':subjectID', $subjectID);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function fetchroomstatusRecord($recordID){
        $sql = 
          "SELECT
                stat.class_day_id AS class_status_id,
                cday.id AS class_day_id,
                cday.day_id AS day_id,
                ctime.id AS class_time_id,
                CONCAT(class.id,'|',class.subject_id) AS class_id,
                CONCAT(class.id,' ', sub.subject_code, ' ',stdesc.type) AS class_display,
                room.room_name AS room_name,
                rtype.room_description AS room_type,
                sub.subject_code AS subject_code,
                stdesc.type AS subject_type,
                sec.section_name AS section_name,
                ctime.start_time AS start_time,
                ctime.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                sdesc.description AS room_status

            FROM
                semester sem
            LEFT JOIN 
                scheduled_statuses stat ON sem.semester = stat.semester AND sem.school_year = stat.school_year
            LEFT JOIN 
                status_description sdesc ON stat.status_desc_id = sdesc.id
            LEFT JOIN 
                class_day cday ON stat.class_day_id = cday.id
            LEFT JOIN
                _day d ON cday.day_id = d.id
            LEFT JOIN
                class_time ctime ON cday.class_time_id = ctime.id
            LEFT JOIN
                class_details class ON ctime.class_id = class.id AND ctime.subject_id = class.subject_id
            LEFT JOIN
                room_list room ON class.room_id = room.id
            LEFT JOIN
                room_type rtype ON room.type_id = rtype.id
            LEFT JOIN
                section_details sec ON class.section_id = sec.id
            LEFT JOIN
                course_details course ON sec.course_id = course.id
            LEFT JOIN
                subject_details sub ON class.subject_id = sub.id
            LEFT JOIN
                subject_type_description stdesc ON sub.type_id = stdesc.id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.id
            LEFT JOIN 
                account acc ON fac.account_id = acc.id
            
        WHERE stat.class_day_id = :recordID;";

        
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function classTimeExistsOnDay($selected_day, $class_time_id) {
       
        $sql = "SELECT 
                COUNT(*) 
            FROM class_day cd
            LEFT JOIN class_time ct ON cd.class_time_id = ct.id
            WHERE cd.day_id = :day_id 
            AND cd.class_time_id != :class_time_id 
        ;";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':day_id', $selected_day);
        $query->bindParam(':class_time_id', $class_time_id);
        $query->execute();
    
        return $query->fetchColumn() > 0;
    }

    


    function deleteClassDetails(){
        $sql = "DELETE FROM class_details WHERE id = :class_id AND subject_id = :subject_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->execute();
        return true;
    }

    function deleteroomStatus(){
        $sql = "DELETE FROM scheduled_statuses WHERE class_day_id = :class_status_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_status_id', $this->class_status_id);
        $query->execute();


        $sql2 = "DELETE FROM class_day WHERE id = :class_day_id;";
        $query2 = $this->db->connect()->prepare($sql2);
        $query2->bindParam(':class_day_id', $this->class_day_id);
        $query2->execute();


        $sql3 = "DELETE FROM class_time WHERE id = :class_time_id;";
        $query3 = $this->db->connect()->prepare($sql3);
        $query3->bindParam(':class_time_id', $this->class_time_id);
        $query3->execute();

        return true;

    }

    function fetchRoomName($recordID){
        $sql = "SELECT room_name FROM room_list WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    
    function roomnameExists($room_name, $excludeID = null){
        $sql = "SELECT COUNT(*) FROM room_list WHERE room_name = :room_name";
        if ($excludeID) {
            $sql .= " AND id != :excludeID";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_name', $room_name);
        if ($excludeID) {
            $query->bindParam(':excludeID', $excludeID);
        }
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    }

    //fetch room type for dropdown
    public function fetchroomType(){
        $sql = 
            "SELECT room_type_id as type_id, room_description AS r_type 
            FROM room_type
            ORDER BY r_type ASC;
        
        ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown, room_name in room list
    public function fetchroomList(){
        $sql = " SELECT *, CONCAT(room_code, ' ', room_no) AS room_name FROM room_list;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


    //for filter dropdown status list
    public function fetchstatusOption(){
        $sql = " SELECT sd.description AS status_desc FROM status_description sd;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown search subject code
    public function fetchsubjectOption(){
        $sql = 
        " SELECT sub.subject_code AS subject_id
        FROM subject_details sub;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for semester dropdown
    public function fetchsemesterOption(){
        $sql = "SELECT 
                sem.semester AS semester_id,
                sem.description AS semester_desc,
                sem.school_year AS school_year

            FROM semester sem
            
            ORDER BY school_year ASC;
            
            ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


    //for semester dropdown
    public function fetchclassesOption(){
        $sql = "SELECT 
                CONCAT(class.id,' ', sub.subject_code, ' ',stdesc.type) AS class_display,
                class.id AS class_id,
                class.subject_id AS subject_id

            FROM class_details class
            LEFT JOIN subject_details sub ON class.subject_id = sub.id
            LEFT JOIN subject_type_description stdesc ON sub.type_id = stdesc.id
            
            ORDER BY class_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

     //for filter dropdown search Teacher
    public function fetchteacherOption(){
        $sql = "SELECT fac.faculty_id AS faculty_id, CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name 
        FROM faculty_list fac 
        LEFT JOIN user_list user ON fac.user_id = user.user_id
        LEFT JOIN account acc ON user.user_id = acc.account_id;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown subject code
    public function fetchsubjectnameOption(){
        $sql = " SELECT DISTINCT subject_code FROM subject_details;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
    
    //for filter dropdown subject type
    public function fetchsubtypeOption(){
        $sql = " SELECT std.type AS subject_type FROM subject_type_description std;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown section
    public function fetchsectionOption(){
        $sql = "SELECT * FROM section_details;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown Day
    public function fetchdayOption(){
        $sql = "SELECT section_name FROM section_details 
        ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

   

    
    //fetch course for radio button
    public function fetchCourse(){
        $sql = "SELECT course_abbr, course_name FROM course_details;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    // Method to fetch sections based on the selected course ID
    public function fetchSectionsByCourseId($courseId) {
        $sql = "SELECT section_name FROM section_details WHERE course_id = :course_id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':course_id', $courseId);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

}

    //cday.id AS cday_id,
    // d.day AS week_day,
    // room.room_name AS room_name,
    // rtype.room_description AS room_type,
    // sub.subject_code AS subject_code,
    // stdesc.type AS subject_type,
    // sec.section_name AS section_name,
    // ctime.start_time AS start_time,
    // ctime.end_time AS end_time,
    // CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
    // sdesc.description AS room_status

   //OLD QUERIES
    //OLD updateClassDetails
    // function updateClassDetails(){
    //     $sql = "UPDATE class_details SET room_id = :room_id, subject_id = :subject_id, section_id = :section_id, teacher_assigned = :teacher_id WHERE id = :class_id;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':class_id', $this->class_id);
    //     $query->bindParam(':room_id', $this->room_id);
    //     $query->bindParam(':subject_id', $this->subject_id);
    //     $query->bindParam(':section_id', $this->section_id);
    //     $query->bindParam(':teacher_id', $this->teacher_assigned);
    //     $query->execute();
    //     return true;
    // }

    // function updateClassTime(){
    //     $sql = "UPDATE class_time SET start_time = :start_time, end_time = :end_time WHERE id = :class_time_id;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':class_time_id', $this->class_time_id);
    //     $query->bindParam(':start_time', $this->start_time);
    //     $query->bindParam(':end_time', $this->end_time);
    //     $query->execute();
    //     return true;
    // }

    // function updateClassDay(){
    //     $sql = "UPDATE class_day SET day_id = :day_id WHERE id = :class_day_id;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':class_day_id', $this->class_day_id);
    //     $query->bindParam(':day_id', $this->day_id);
    //     $query->execute();
    //     return true;
    // }


//OLD
    // function insertClassDetails(){
    //     if($this->newClass == true){
    //         $sql = "INSERT INTO class_details (room_id, subject_id, section_id, teacher_assigned) VALUES (:room_id, :subject_id, :section_id, :teacher_id);";
    //         $query = $this->db->connect()->prepare($sql);
    //         $query->bindParam(':room_id', $this->room_id);
    //         $query->bindParam(':subject_id', $this->subject_id);
    //         $query->bindParam(':section_id', $this->section_id);
    //         $query->bindParam(':teacher_id', $this->teacher_assigned);
    //         $query->execute();

    //         $this->class_id = $this->db->connect()->lastInsertId();

    //         return $this->class_id;
    //     }

    //     return true;
    // }

    // function insertClassTime(){
    //     if($this->newtime == true){
    //         $sql = "INSERT INTO class_time (class_id, start_time, end_time) VALUES (:class_id, :start_time, :end_time);";
    //         $query = $this->db->connect()->prepare($sql);
    //         $query->bindParam(':class_id', $this->class_id);
    //         $query->bindParam(':start_time', $this->start_time);
    //         $query->bindParam(':end_time', $this->end_time);
    //         $query->execute();

    //         $this->class_time_id = $this->db->connect()->lastInsertId();

    //         return $this->class_time_id;
    //     }
    //     return true;
    // }


    // function insertClassDay(){
    //     if($this->newDay == true){
    //         $sql = "INSERT INTO class_day (day_id, class_time_id) VALUES (:day_id, :class_time_id);";
    //         $query = $this->db->connect()->prepare($sql);
    //         $query->bindParam(':day_id', $this->day_id);
    //         $query->bindParam(':class_time_id', $this->class_time_id);
    //         $query->execute();

    //         $this->class_day_id = $this->db->connect()->lastInsertId();

    //         return $this->class_day_id;
    //     }
    //     return true;
    // }

    // function insertStatus(){
    //     $sql = "INSERT INTO _status (class_day_id) VALUES (:class_day_id);";
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':class_day_id', $this->class_day_id);
    //     $query->execute();
    //     return true;
    // }



    // function addroomStatus() {// 1. Insert into class_details
        
    //     if($this->newClass == false){
    //         $sql1 = "INSERT INTO class_details (room_id, subject_id, section_id, teacher_assigned) VALUES (:room_id, :subject_id, :section_id, :teacher_id);";
    //         $query1 = $this->db->connect()->prepare($sql1);
    //         $query1->bindParam(':room_id', $this->room_id);
    //         $query1->bindParam(':subject_id', $this->subject_id);
    //         $query1->bindParam(':section_id', $this->section_id);
    //         $query1->bindParam(':teacher_id', $this->teacher_assigned);
    //         $query1->execute();

    //         // 2. Get the last inserted ID from class_details
    //         $this->class_id = $this->db->connect()->lastInsertId();
    //     }          
        
    //     if ($this->newtime == false){//if there is no existing class time id
    //         $this->log_cid[]= $this->class_id; 

    //         // 3. Insert into class_time
    //         $sql2 = "INSERT INTO class_time (class_id, start_time, end_time) VALUES (:class_id, :start_time, :end_time)";
    //         $query2 = $this->db->connect()->prepare($sql2);
    //         $query2->bindParam(':class_id', $this->class_id);
    //         $query2->bindParam(':start_time', $this->start_time);
    //         $query2->bindParam(':end_time', $this->end_time);
    //         $query2->execute();
    //         $this->class_time_id = $this->db->connect()->lastInsertId();
    //     }


    //     foreach ($this->day_id as $day) {
    //         if (!$this->insertDayStatus($this->class_time_id, $day)) {
    //             return false; // Stop if insertion fails
    //         }
    //     }
        
    //     return true;
    // }

    
    // function insertDayStatus($time_id, $day) {// 1. Insert into class_day
    //     if($this->newDay == false){
    //         $this->log_ctid[]=$time_id; 
    //         $this->log_day[]=$day; 
    //         $sql1 = "INSERT INTO class_day (day_id, class_time_id) VALUES (:day_id, :class_time_id)";
    //         $query1 = $this->db->connect()->prepare($sql1);
    //         $query1->bindParam(':day_id', $day); // Bind the current day_id
    //         $query1->bindParam(':class_time_id', $time_id); // Use the class_time_id
            
    //         // Check if the insertion was successful
    //         if ($query1->execute()) {
    //             // 2. Get the last inserted ID from class_day
    //             $this->class_day_id = $this->db->connect()->lastInsertId();
    //             $this->log_cdid[] = $this->class_day_id; 

    //             // 3. Insert into _status
    //             $sql2 = "INSERT INTO _status (class_day_id, status_desc_id) VALUES (:class_day_id, :status_desc_id)";
    //             $query2 = $this->db->connect()->prepare($sql2);
    //             $query2->bindParam(':class_day_id', $this->class_day_id); // Bind the current class_day_id
    //             $default_status_desc_id = 2; // Assuming 2 is the default status ID
    //             $query2->bindParam(':status_desc_id', $default_status_desc_id);
    //             $query2->execute();

    //             return true; // Indicate successful insertion
    //         }
            
    //         return false; // Indicate failure to insert
    //     }else{
    //         $sql = "UPDATE class_day SET id=:class_day_id, day_id = :day_id , class_time_id= :class_time_id";




    //         return false; // Indicate failure to insert
    //     }

    // }

 // function editroomStatus() {
    //     try {
    //         // 1. Update class_details
    //         if($this->newClass == true){
                
    //             $sql1 = 
    //             "UPDATE class_details 
    //             SET room_id = :room_id, 
    //                 subject_id = :subject_id, 
    //                 section_id = :section_id, 
    //                 teacher_assigned = :teacher_id 
    //             WHERE id = :class_id";
    //             $query1 = $this->db->connect()->prepare($sql1);
    //             $query1->bindParam(':class_id', $this->class_id);
    //             $query1->bindParam(':room_id', $this->room_id);
    //             $query1->bindParam(':subject_id', $this->subject_id);
    //             $query1->bindParam(':section_id', $this->section_id);
    //             $query1->bindParam(':teacher_id', $this->teacher_assigned);
    //             $query1->execute();
    //         }
                
    //         // 2. Update class_time
    //         if($this->newtime == true){
    //             $sql2 = 
    //             "UPDATE class_time 
    //             SET start_time = :start_time, 
    //                 end_time = :end_time 
    //             WHERE id = :class_time_id";
    //             $query2 = $this->db->connect()->prepare($sql2);
    //             $query2->bindParam(':class_time_id', $this->class_time_id);
    //             $query2->bindParam(':start_time', $this->start_time);
    //             $query2->bindParam(':end_time', $this->end_time);
    //             $query2->execute();
    //         }
            
           

    //         if($this->newDay == true){
    //             foreach ($this->day_id as $day) {
    //                 if (!$this->insertDayStatus($this->class_time_id, $day)) {
    //                     return false; // Stop if insertion fails
    //                 }
    //             }
    //         }
        
           
    //         return true;
    //     } catch (PDOException $e) {
    //         $this->db->connect()->rollBack();
    //         error_log("Error in editroomStatus: " . $e->getMessage());
    //         return false;
    //     }
    // }


        // $sql ="SELECT
        //     sub.subject_code AS subject_code
        // FROM class_details class
        // LEFT JOIN subject_details sub ON class.subject_id = sub.id
        // LEFT JOIN subject_type_description stdesc ON sub.type_id = stdesc.id
        // LEFT JOIN section_details sec ON class.section_id = sec.id
        
        // WHERE sub.subject_code = 'CC103' AND class.section_id = 8;";



    //CHECK IF AN EXISTING ROW ALREADY EXIST ON TABLE, USED ON SAVE-CLASS-DETAIL.PHP
    // function checkExistingClassDetails(){
    //     $sql = "SELECT (class.id) AS class_id
    //     FROM class_details class
    //     WHERE class.room_id = :room_id 
    //     AND class.section_id = :section_id 
    //     AND class.subject_id = :subject_id 
    //     AND class.teacher_assigned = :teacher_id;";

    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':room_id', $this->room_id);
    //     $query->bindParam(':section_id', $this->section_id);
    //     $query->bindParam(':subject_id', $this->subject_id);
    //     $query->bindParam(':teacher_id', $this->teacher_assigned);
        
    //     if ($query->execute()) {
    //         $data = $query->fetch();
    //         return $data ? $data['class_id'] : null;  // Return just the ID if found
    //     }
    //     return null;
    // }

?>

