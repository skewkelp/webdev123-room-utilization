<?php

require_once 'database.class.php';

class RoomStatus{
    public $week_day = '';
    
    public $id = '';
    public $room_code = '';
    //room_list
    public $room_name = '';
    public $room_type = '';
    
    
    public $subject_code = '';
    public $subject_type = '';
    
    
    public $class_name = '';
    public $status = '';
   
    
    // Properties for class details
    public $room_id = '';
    public $subject_id = '';
    public $section_id = '';
    public $teacher_assigned = ''; 
    public $start_time = '';
    public $end_time = ''; 
    public $day_id = [];

    // Properties for IDs and logs
    public $class_id = ''; // PK class_details
    public $class_time_id = ''; // PK class_time
    public $class_day_id = '';
    public $log_cid = []; // Log for class IDs
    public $log_ctid = []; // Log for class time IDs
    public $log_cdid = []; // Log for class day IDs
    public $log_day = []; // Log for day IDs
    public $log_sid = []; // Log for day IDs

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
    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    public $newDay; //bool classDay if existing
    public $newtime; //bool classTime if existing
    public $newClass; //bool classDetails if existing
    public $newRoom; //bool room if existing
    public $newSubject; //bool subject if existing
    public $newSection; //bool section if existing
    public $newTeacher; //bool teacher if existing

    function updateClassDetails(){
        $sql = "UPDATE class_details SET room_id = :room_id, subject_id = :subject_id, section_id = :section_id, teacher_assigned = :teacher_id WHERE id = :class_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':room_id', $this->room_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':section_id', $this->section_id);
        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $query->execute();
        return true;
    }

    function updateClassTime(){
        $sql = "UPDATE class_time SET start_time = :start_time, end_time = :end_time WHERE id = :class_time_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_time_id', $this->class_time_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->execute();
        return true;
    }

    function updateClassDay(){
        $sql = "UPDATE class_day SET day_id = :day_id WHERE id = :class_day_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_day_id', $this->class_day_id);
        $query->bindParam(':day_id', $this->day_id);
        $query->execute();
        return true;
    }

    function insertClassDetails(){
        if($this->newClass == true){
            $sql = "INSERT INTO class_details (room_id, subject_id, section_id, teacher_assigned) VALUES (:room_id, :subject_id, :section_id, :teacher_id);";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':room_id', $this->room_id);
            $query->bindParam(':subject_id', $this->subject_id);
            $query->bindParam(':section_id', $this->section_id);
            $query->bindParam(':teacher_id', $this->teacher_assigned);
            $query->execute();

            $this->class_id = $this->db->connect()->lastInsertId();

            return $this->class_id;
        }

        return true;
    }

    function insertClassTime(){
        if($this->newtime == true){
            $sql = "INSERT INTO class_time (class_id, start_time, end_time) VALUES (:class_id, :start_time, :end_time);";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':class_id', $this->class_id);
            $query->bindParam(':start_time', $this->start_time);
            $query->bindParam(':end_time', $this->end_time);
            $query->execute();

            $this->class_time_id = $this->db->connect()->lastInsertId();

            return $this->class_time_id;
        }
        return true;
    }


    function insertClassDay(){
        if($this->newDay == true){
            $sql = "INSERT INTO class_day (day_id, class_time_id) VALUES (:day_id, :class_time_id);";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':day_id', $this->day_id);
            $query->bindParam(':class_time_id', $this->class_time_id);
            $query->execute();

            $this->class_day_id = $this->db->connect()->lastInsertId();

            return $this->class_day_id;
        }
        return true;
    }

    function insertStatus(){
        $sql = "INSERT INTO _status (class_day_id) VALUES (:class_day_id);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_day_id', $this->class_day_id);
        $query->execute();
        return true;
    }



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

    public $original_day_id = '';

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


    function showAllStatus($keyword = '', $fweek_day = '', $froom_name = '', $froom_type = '', $fsubject_code = '', $fsubject_type = '', $fsection_name = '', $fstart_time = '', $fend_time = '', $fteacher_name = '', $fstatus = ''){
        $sql = 
            "SELECT
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
                status_description sdesc
            RIGHT JOIN 
                _status stat ON stat.status_desc_id = sdesc.id
            LEFT JOIN 
                class_day cday ON stat.class_day_id = cday.id
            LEFT JOIN
                _day d ON cday.day_id = d.id
            LEFT JOIN
                class_time ctime ON cday.class_time_id = ctime.id
            LEFT JOIN
                class_details class ON ctime.class_id = class.id
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
                
            WHERE
            (
                room.room_name LIKE CONCAT('%', :keyword, '%') OR
                rtype.room_description LIKE CONCAT('%', :keyword, '%') OR
                sub.subject_code LIKE CONCAT('%', :keyword, '%') OR
                stdesc.type LIKE CONCAT('%', :keyword, '%') OR
                sec.section_name LIKE CONCAT('%', :keyword, '%') OR
                ctime.start_time LIKE CONCAT('%', :keyword, '%') OR
                ctime.end_time LIKE CONCAT('%', :keyword, '%') OR
                CONCAT(acc.last_name,', ',acc.first_name) LIKE CONCAT('%', :keyword, '%') OR
                sdesc.description LIKE CONCAT('%', :keyword, '%')
            )AND(
                d.day LIKE CONCAT('%', :fweek_day, '%') AND
                room.room_name LIKE CONCAT('%', :froom_name, '%') AND
                rtype.room_description LIKE CONCAT('%', :froom_type, '%') AND
                sub.subject_code LIKE CONCAT('%', :fsubject_code, '%') AND
                stdesc.type LIKE CONCAT('%', :fsubject_type, '%') AND
                sec.section_name LIKE CONCAT('%', :fsection_name, '%') AND
                ctime.start_time LIKE CONCAT('%', :fstart_time, '%') AND
                ctime.end_time LIKE CONCAT('%', :fend_time, '%') AND
                CONCAT(acc.last_name,', ',acc.first_name) LIKE CONCAT('%', :fteacher_name, '%') AND
                sdesc.description LIKE CONCAT('%', :fstatus, '%')
            )
    
        ;";
    
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);
        $query->bindParam(':fweek_day', $fweek_day);
        $query->bindParam(':froom_name', $froom_name);
        $query->bindParam(':froom_type', $froom_type);
        $query->bindParam(':fsubject_code', $fsubject_code);
        $query->bindParam(':fsubject_type', $fsubject_type);
        $query->bindParam(':fsection_name', $fsection_name);
        $query->bindParam(':fstart_time', $fstart_time);
        $query->bindParam(':fend_time', $fend_time);
        $query->bindParam(':fteacher_name', $fteacher_name);
        $query->bindParam(':fstatus', $fstatus);
        
        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }

    

    //CHECK IF AN EXISTING ROW ALREADY EXIST ON TABLE
    function checkExistingClassTimeID(){
        $sql = "SELECT (ct.id) AS class_time_id
        FROM class_time ct
        WHERE ct.start_time = :start_time AND ct.end_time = :end_time AND ct.class_id = :class_id;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':class_id', $this->class_id);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function checkExistingClassDetails(){
        $sql = "SELECT (class.id) AS class_id
        FROM class_details class
        WHERE class.room_id = :room_id AND class.section_id = :section_id AND class.subject_id = :subject_id AND class.teacher_assigned = :teacher_id;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_id', $this->room_id);
        $query->bindParam(':section_id', $this->section_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
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
    
    function fetchroomstatusRecord($recordID){
        $sql = 
        "SELECT
            stat.class_day_id AS class_status_id,
            class.id AS class_id,
            room.room_name AS room_name,
            class.room_id AS room_id,
            sec.section_name,
            class.section_id AS section_id,
            class.teacher_assigned AS teacher_id,
            CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name,
            class.subject_id AS subject_id,
            CONCAT(sub.subject_code,' ', stdesc.type) AS subject_for,
            ctime.id AS class_time_id,
            ctime.start_time AS start_time,
            ctime.end_time AS end_time,
            cday.id AS class_day_id,
            cday.day_id AS day_id
        FROM
            status_description sdesc
        RIGHT JOIN 
            _status stat ON stat.status_desc_id = sdesc.id
        LEFT JOIN 
            class_day cday ON stat.class_day_id = cday.id
        LEFT JOIN
            _day d ON cday.day_id = d.id
        LEFT JOIN
            class_time ctime ON cday.class_time_id = ctime.id
        LEFT JOIN
            class_details class ON ctime.class_id = class.id
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
        // $sql = "
        //     SELECT COUNT(*) 
        //     FROM class_day cd
        //     JOIN class_time ct ON cd.class_time_id = ct.id
        //     WHERE cd.day_id = :day_id 
        //     AND ct.id = :class_time_id
        //     AND cd.class_time_id != :current_class_time_id
        // ";
        $sql = "
            SELECT COUNT(*) 
            FROM class_day cd
            JOIN class_time ct ON cd.class_time_id = ct.id
            WHERE cd.day_id = :day_id 
            AND cd.class_time_id != :class_time_id 
        ";
        
        // $query = $this->db->connect()->prepare($sql);
        // $query->bindParam(':day_id', $selected_day);
        // $query->bindParam(':class_time_id', $class_time_id);
        // $query->bindParam(':current_class_time_id', $this->class_time_id);
        // $query->execute();
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':day_id', $selected_day);
        $query->bindParam(':class_time_id', $class_time_id);
        $query->execute();
    
        return $query->fetchColumn() > 0;
    }

    function deleteroomStatus() {
        $this->log_sid[] = $this->class_status_id;
        // 1. Delete from _status (will cascade delete due to constraints)
        $sql1 = "DELETE FROM _status WHERE class_day_id = :class_day_id;";
        $query1 = $this->db->connect()->prepare($sql1);
        $query1->bindParam(':class_day_id', $this->class_status_id);
        $query1->execute();
        $this->log_cdid[] = $this->class_day_id;
        // 2. Delete from class_day
        $sql2 = "DELETE FROM class_day WHERE id = :class_day_id;";
        $query2 = $this->db->connect()->prepare($sql2);
        $query2->bindParam(':class_day_id', $this->class_status_id); // Fixed parameter name
        $query2->execute();

        $this->log_ctid[] = $this->class_day_id;
        
        // 3. Delete from class_time
        $sql3 = "DELETE FROM class_time WHERE id = :class_time_id;";
        $query3 = $this->db->connect()->prepare($sql3);
        $query3->bindParam(':class_time_id', $this->class_time_id);
        $query3->execute();
        
        $this->log_cid[] = $this->class_id;
        // 4. Delete from class_details
        $sql4 = "DELETE FROM class_details WHERE id = :class_id;";
        $query4 = $this->db->connect()->prepare($sql4);
        $query4->bindParam(':class_id', $this->class_id);
        $query4->execute();
    
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
            "SELECT id as type_id, room_description AS r_type 
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
        $sql = " SELECT * FROM room_list;";
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
        " SELECT sub.id AS subject_id, CONCAT(sub.subject_code,' ', _desc.type) AS subject_for
        FROM subject_details sub LEFT JOIN subject_type_description _desc ON sub.type_id = _desc.id;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


     //for filter dropdown search Teacher
    public function fetchteacherOption(){
        $sql = "SELECT fac.id AS faculty_id, CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name 
        FROM faculty_list fac 
        LEFT JOIN account acc ON fac.account_id = acc.id ;";
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
        $sql = "SELECT * FROM section_details 
        ;";
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
        $sql = "SELECT id, _name FROM course_details;";
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
?>

