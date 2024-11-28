<?php

require_once 'database.class.php';

class Room
{
    public $week_day = '';
    public $id = '';
    public $room_code = '';
    //room_list
    public $room_id = '';
    public $room_name = '';
    public $room_type = '';

    //room_list
    public $class_id = '';
    public $subject_code = '';
    public $subject_type = '';
    public $class_name = '';
    public $start_time = '';
    public $end_time = '';
    public $teacher_assigned = '';
    public $status = '';

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
        $sql = "INSERT INTO room_list (room_name, room_type) VALUES (:room_name, :room_type);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_name', $this->room_name);
        $query->bindParam(':room_type', $this->room_type);
        
        return $query->execute();
    }

    function add(){
        $sql = "INSERT INTO product (code, name, category_id, price) VALUES (:code, :name, :category_id, :price);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $this->code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':price', $this->price);
        
        return $query->execute();
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
    
    function showAllStatus($keyword = '', $fweek_day = '', $froom_name = '', $froom_type = '', $fsubject_code = '', $fsubject_type = '', $fsection_name = '', $fstart_time = '', $fend_time = '', $fteacher_name = '', $fstatus = '') {
        $sql = 
            "SELECT
                cdt.id,
                cda.week_day, 
                rl.room_name, 
                rt.room_description AS room_type,
                sd.subject_code, 
                sd.subject_type,
                sec.section_name, 
                ct.start_time, 
                ct.end_time,
                CONCAT(fl.fname, ' ',fl.lname) AS faculty_name,
                std.description AS room_status

            FROM
                _status s
            LEFT JOIN
                status_description std ON s.status_desc_id = std.id
            LEFT JOIN
                class_day cda ON s.class_day_id = cda.id
            LEFT JOIN 
                class_time ct ON cda.class_id = ct.id
            LEFT JOIN 
                class_details cdt ON ct.class_id = cdt.id
            LEFT JOIN 
                room_list rl ON cdt.room_id = rl.id
            LEFT JOIN
                room_type rt ON rl.type_id = rt.id
            LEFT JOIN 
                subject_details sd ON cdt.subject_id = sd.id  
            LEFT JOIN 
                section_details sec ON cdt.section_id = sec.id
            LEFT JOIN 
                faculty_list fl ON cdt.teacher_assigned = fl.id
             
            
            WHERE
            (
                rl.room_name LIKE CONCAT('%', :keyword, '%') OR
                rt.room_description LIKE CONCAT('%', :keyword, '%') OR
                sd.subject_code LIKE CONCAT('%', :keyword, '%') OR
                sd.subject_type LIKE CONCAT('%', :keyword, '%') OR
                sec.section_name LIKE CONCAT('%', :keyword, '%') OR
                ct.start_time LIKE CONCAT('%', :keyword, '%') OR
                ct.end_time LIKE CONCAT('%', :keyword, '%') OR
                fl.fname LIKE CONCAT('%', :keyword, '%') OR
                std.description LIKE CONCAT('%', :keyword, '%')
            )AND(
                cda.week_day LIKE CONCAT('%', :fweek_day, '%') AND
                rl.room_name LIKE CONCAT('%', :froom_name, '%') AND
                rt.room_description LIKE CONCAT('%', :froom_type, '%') AND
                sd.subject_code LIKE CONCAT('%', :fsubject_code, '%') AND
                sd.subject_type LIKE CONCAT('%', :fsubject_type, '%') AND
                sec.section_name LIKE CONCAT('%', :fsection_name, '%') AND
                ct.start_time LIKE CONCAT('%', :fstart_time, '%') AND
                ct.end_time LIKE CONCAT('%', :fend_time, '%') AND
                CONCAT(fl.fname, ' ',fl.lname) LIKE CONCAT('%', :fteacher_name, '%') AND
                std.description LIKE CONCAT('%', :fstatus, '%')
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


    public function fetchstatusOption(){
        $sql = " SELECT sd.description AS status_desc FROM status_description sd;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
    
}
