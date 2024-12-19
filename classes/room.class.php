<?php

require_once 'database.class.php';

class Room{
    //room_list
    public $room_name = '';
    public $room_code = '';
    public $room_no = '';
    public $room_type = '';

    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function addRoom(){
        $sql = "INSERT INTO room_list (room_code, room_no) VALUES (:room_code, :room_no);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_code', $this->room_code);
        $query->bindParam(':room_no', $this->room_no);
        
        return $query->execute();
    }

    
    function showAllrooms(){
        $sql = 
            "SELECT 
                r.room_code AS room_code, r.room_no AS room_no,
                CONCAT(r.room_code, ' ',r.room_no) AS room_name,
                CONCAT(rt.room_type_id, '-', rt.room_description) AS room_details
            FROM 
                room_list r
            LEFT JOIN
                room_type rt ON r.room_code = rt.room_type_id
            ;
        ";
        
        $query = $this->db->connect()->prepare($sql);
        
        
        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $data;
    }

    
    function editRoom($original_room_code, $original_room_no){
        $sql = "UPDATE room_list SET room_code = :room_code, room_no = :room_no WHERE room_code = :original_room_code AND room_no = :original_room_no;";
        $query = $this->db->connect()->prepare($sql);   
        $query->bindParam(':room_code', $this->room_code);
        $query->bindParam(':room_no', $this->room_no);
        $query->bindParam(':original_room_code', $original_room_code);
        $query->bindParam(':original_room_no', $original_room_no);
        return $query->execute();
    }

    //fetch room list record
    function fetchroomlistRecord($roomCode, $roomNo){
        $sql = "SELECT *, CONCAT(room_code, ' ', room_no) AS room_name FROM room_list WHERE room_code = :room_code AND room_no = :room_no;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_code', $roomCode);
        $query->bindParam(':room_no', $roomNo);

        $query->execute();
        $data = $query->fetch();
        
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

    function roomnameExists($room_code, $room_no, $excludeCode = null, $excludeNo = null){
        $sql = "SELECT COUNT(*) FROM room_list WHERE room_code = :room_code AND room_no = :room_no";
        if ($excludeCode && $excludeNo) {
            $sql .= " AND NOT (room_code = :excludeCode AND room_no = :excludeNo)";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_code', $room_code);
        $query->bindParam(':room_no', $room_no);

        if ($excludeCode && $excludeNo){
            $query->bindParam(':excludeCode', $excludeCode);
            $query->bindParam(':excludeNo', $excludeNo);
        }
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    }
        

    function roomnameType(){
        $sql = "SELECT * FROM room_list 
        WHERE (
            (type_id = 1 AND room_name LIKE 'LR%') OR 
            (type_id = 2 AND room_name LIKE 'LAB%')
          
        );";
        
    }
    

    //fetch room type for dropdown
    public function fetchroomType(){
        $sql = 
            "SELECT room_type_id, CONCAT(room_type_id,' ',room_description) AS room_type_desc
            FROM room_type
            ORDER BY room_type_desc ASC;
        
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
        $sql = " SELECT *, CONCAT(room_code, ' ',room_no) AS room_name FROM room_list;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    
}
