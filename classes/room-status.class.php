<?php

require_once 'database.class.php';

class Room
{
    public $week_day = '';
    
    public $id = '';
    public $room_code = '';
    //room_list
    public $room_name = '';
    public $room_type = '';
    
    //class_details
    public $room_id = '';//PK room_list
    public $subject_id = '';//PK subject_details
    public $section_id = '';//PK section_details
    public $teacher_assigned = '';//class_details FK->PK faculty_list
    public $class_time_id = '';//PK class_time
    public $class_id = '';//PK class_details
    public $start_time = '';
    public $end_time = '';
    public $status = '';
    public $day_id = '';
    public $class_day_id = '';



    public $subject_code = '';
    public $subject_type = '';


    public $class_name = '';

    
    // cda.week_day, 
    // rl.room_name, 
    // rl.room_type,
    // sd.subject_code, 
    // sd.subject_type,
    // sec.section_name, 
    // ct.start_time, 
    // ct.end_time,
    // fl.fname,
    // rs._status
    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function addRoom(){
        $sql = "INSERT INTO room_list (room_name, type_id) VALUES (:room_name, :room_type);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_name', $this->room_name);
        $query->bindParam(':room_type', $this->room_type);
        
        return $query->execute();
    }

    // public $room_id = '';//PK room_list
    // public $subject_id = '';//PK subject_details
    // public $section_id = '';//PK section_details
    // public $teacher_assigned = '';//class_details FK->PK faculty_list
    // public $class_time_id = '';//PK class_time
    // public $class_id = '';//PK class_details
    // public $start_time = '';
    // public $end_time = '';
    // public $status = '';
    // public $class_day_id = '';
    /*
    Fatal error: Uncaught PDOException: SQLSTATE[23000]: Integrity constraint violation: 
    1452 Cannot add or update a child row: a foreign key constraint fails 
    (`room_utilization`.`class_details`, CONSTRAINT `facultyid_fk` FOREIGN KEY (`teacher_assigned`) 
    REFERENCES `faculty_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE) in 
    C:\xampp\htdocs\templateProg\classes\room-status.class.php:90 Stack trace:
    #0 C:\xampp\htdocs\templateProg\classes\room-status.class.php(90): PDOStatement->execute() 
    #1 C:\xampp\htdocs\templateProg\class-room-status\save-room-status.php(78): Room->addroomStatus() 
    #2 {main} thrown in C:\xampp\htdocs\templateProg\classes\room-status.class.php on line 90
    
    */
    function addroomStatus(){
        //insert on class_details
        $sql = "INSERT INTO class_details (section_id, room_id, subject_id, teacher_assigned) VALUES (:section_id, :room_id, :subject_id, :teacher_id);";
        $query1 = $this->db->connect()->prepare($sql);
        $query1->bindParam(':section_id', $this->section_id);
        $query1->bindParam(':room_id', $this->room_id);
        $query1->bindParam(':subject_id', $this->subject_id);
        $query1->bindParam(':teacher_id', $this->teacher_assigned);
        $query1->execute();

        //last inserted PK id, from class_details
        $this->class_id = $this->db->connect()->lastInsertId();
        //insert class_time
        $sql2 = "INSERT INTO class_time (class_id, start_time, end_time) VALUES (:class_id, :start_time, :end_time)";
        $query2 = $this->db->connect()->prepare($sql);
        $query2->bindParam(':class_id', $this->class_id);
        $query2->bindParam(':start_time', $this->start_time);
        $query2->bindParam(':end_time', $this->end_time);
        $query2->execute();

        //last inserted PK id, from class_time
        $this->class_time_id = $this->db->connect()->lastInsertId();
        
        //insert class_day, _status
        if (!empty($this->day_id)) {
            foreach ($this->day_id as $day) {
                //insert class_day
                $sql3 = "INSERT INTO class_day (day_id, class_id) VALUES (:day_id, :class_time_id)";
                $query3 = $this->db->connect()->prepare($sql);
                $query3->bindParam(':day_id', $this->day_id);
                $query3->bindParam(':class_time_id', $this->class_time_id);
                $query3->execute();
                //last inserted PK id, from class_day
                $this->class_day_id = $this->db->connect()->lastInsertId();
                //insert _status
                $sql4 = "INSERT INTO _status (class_day_id) VALUES (:class_day_id)";
                $query4 = $this->db->connect()->prepare($sql);
                $query4->bindParam(':class_day_id', $this->class_day_id);
                $query4->execute();
                
            }
            
        }
        return true;
        
    }

    function showAll($keyword = '', $category = ''){
        $sql = 
            "SELECT p.*, c.name as category_name, SUM(IF(s.status='in', quantity, 0)) as stock_in, SUM(IF(s.status='out', quantity, 0)) as stock_out 
            FROM product p INNER JOIN category c ON p.category_id = c.id LEFT JOIN stocks s ON p.id = s.product_id 
            WHERE (p.code LIKE CONCAT('%', :keyword, '%') OR 
            p.name LIKE CONCAT('%', :keyword, '%')) AND 
            (c.id LIKE CONCAT('%', :category, '%')) 
            GROUP BY p.id ORDER BY p.name ASC;"
        ;
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);    
        $query->bindParam(':category', $category);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
    
    // function showAllStatus($keyword = '', $fweek_day = '', $froom_name = '', $froom_type = '', $fsubject_code = '', $fsubject_type = '', $fsection_name = '', $fstart_time = '', $fend_time = '', $fteacher_name = '', $fstatus = '') {
    //     $sql = 
    //         "SELECT
    //             cdt.id, 
    //             rl.room_name, 
    //             rt.room_description AS room_type,
    //             sd.subject_code, 
    //             subtype.type AS subject_type,
    //             sec.section_name, 
    //             ct.start_time, 
    //             ct.end_time,
    //             CONCAT(fl.fname, ' ',fl.lname) AS faculty_name,
    //             std.description AS room_status
    //         FROM
    //             _status s
    //         LEFT JOIN
    //             status_description std ON s.status_desc_id = std.id
    //         LEFT JOIN
    //             class_day cda ON s.class_day_id = cda.id
    //         LEFT JOIN 
    //             class_time ct ON cda.class_id = ct.id
    //         LEFT JOIN 
    //             class_details cdt ON ct.class_id = cdt.id
    //         LEFT JOIN 
    //             room_list rl ON cdt.room_id = rl.id
    //         LEFT JOIN
    //             room_type rt ON rl.type_id = rt.id
    //         LEFT JOIN 
    //             subject_details sd ON cdt.subject_id = sd.id 
    //         LEFT JOIN
    //             subject_type_description subtype ON sd.type_id = subtype.id 
    //         LEFT JOIN 
    //             section_details sec ON cdt.section_id = sec.id
    //         LEFT JOIN 
    //             faculty_list fl ON cdt.teacher_assigned = fl.id
             
            
    //         WHERE
    //         (
    //             rl.room_name LIKE CONCAT('%', :keyword, '%') OR
    //             rt.room_description LIKE CONCAT('%', :keyword, '%') OR
    //             sd.subject_code LIKE CONCAT('%', :keyword, '%') OR
    //             subtype.type LIKE CONCAT('%', :keyword, '%') OR
    //             sec.section_name LIKE CONCAT('%', :keyword, '%') OR
    //             ct.start_time LIKE CONCAT('%', :keyword, '%') OR
    //             ct.end_time LIKE CONCAT('%', :keyword, '%') OR
    //             fl.fname LIKE CONCAT('%', :keyword, '%') OR
    //             std.description LIKE CONCAT('%', :keyword, '%')
    //         )AND(
    //             cda.week_day LIKE CONCAT('%', :fweek_day, '%') AND
    //             rl.room_name LIKE CONCAT('%', :froom_name, '%') AND
    //             rt.room_description LIKE CONCAT('%', :froom_type, '%') AND
    //             sd.subject_code LIKE CONCAT('%', :fsubject_code, '%') AND
    //             subtype.type LIKE CONCAT('%', :fsubject_type, '%') AND
    //             sec.section_name LIKE CONCAT('%', :fsection_name, '%') AND
    //             ct.start_time LIKE CONCAT('%', :fstart_time, '%') AND
    //             ct.end_time LIKE CONCAT('%', :fend_time, '%') AND
    //             CONCAT(fl.fname, ' ',fl.lname) LIKE CONCAT('%', :fteacher_name, '%') AND
    //             std.description LIKE CONCAT('%', :fstatus, '%')
    //         )
    
    //     ;";
    
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':keyword', $keyword);
    //     $query->bindParam(':fweek_day', $fweek_day);
    //     $query->bindParam(':froom_name', $froom_name);
    //     $query->bindParam(':froom_type', $froom_type);
    //     $query->bindParam(':fsubject_code', $fsubject_code);
    //     $query->bindParam(':fsubject_type', $fsubject_type);
    //     $query->bindParam(':fsection_name', $fsection_name);
    //     $query->bindParam(':fstart_time', $fstart_time);
    //     $query->bindParam(':fend_time', $fend_time);
    //     $query->bindParam(':fteacher_name', $fteacher_name);
    //     $query->bindParam(':fstatus', $fstatus);
    
    //     $data = null;
    //     if ($query->execute()){
    //         $data = $query->fetchAll();
    //     }
    //     return $data;
    // }

    function showAllStatus($keyword = '', $fweek_day = '', $froom_name = '', $froom_type = '', $fsubject_code = '', $fsubject_type = '', $fsection_name = '', $fstart_time = '', $fend_time = '', $fteacher_name = '', $fstatus = ''){
        $sql = 
            "SELECT
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
                class_time ctime ON cday.class_id = ctime.id
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

    
    function editRoom(){
        $sql = "UPDATE room_list SET room_name = :room_name, room_type = :room_type WHERE id = :room_id;";
        $query = $this->db->connect()->prepare($sql);   
        $query->bindParam(':room_name', $this->room_name);
        $query->bindParam(':room_type', $this->room_type);
        $query->bindParam(':room_id', $this->room_id);
        return $query->execute();
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

    function delete($recordID){
        $sql = "DELETE FROM product WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        return $query->execute();
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
        " SELECT sub.id AS subject_id, CONCAT(sub.subject_code,' ', _desc.type) AS subject_option
        FROM subject_details sub LEFT JOIN subject_type_description _desc ON sub.type_id = _desc.id;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    // //for filter dropdown search section
    // public function fetchsectOption(){
    //     $sql = "SELECT id AS section_name, section_name FROM section_details;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $data = null;
    //     if ($query->execute()) {
    //         $data = $query->fetchAll(PDO::FETCH_ASSOC);
    //     }
    //     return $data;
    // }

     //for filter dropdown search Teacher
    public function fetchteacherOption(){
        $sql = "SELECT fac.id, CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name 
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
