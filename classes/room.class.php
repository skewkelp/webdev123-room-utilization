<?php

require_once 'database.class.php';

class Room
{
    public $id = '';

    public $room_code = '';


    //room_list
    public $room_id = '';
    public $room_name = '';
    public $room_type = '';
    public $room_no = '';

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
        $sql = "SELECT p.code AS product_code, p.name AS product_name, c.name AS category_name, p.price, SUM(s.quantity) AS total_stocks, SUM(CASE WHEN s.status = 'IN' THEN s.quantity ELSE 0 END) AS available_stocks
                FROM product p
                INNER JOIN
                category c ON p.category_id = c.id
                LEFT JOIN
                stocks s ON p.id = s.product_id
                GROUP BY
                p.id, p.code, p.name, c.name, p.price
                ORDER BY
                p.name ASC;
        ";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);    
        $query->bindParam(':category', $category);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
    
    function showAllrooms(){
        $sql = 
            "SELECT 
                r.id, room_name,
                CONCAT(rt.room_code, '-', rt.room_description) AS room_details
            FROM 
                room_list r
            LEFT JOIN
                room_type rt ON r.type_id = rt.id
            WHERE (CONCAT(rt.room_code, '-', rt.room_description) LIKE CONCAT('%', :room_name, '%')) 
            AND (:room_type = '' OR rt.room_code = :room_type);
        ";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_name', $this->room_name);
        $query->bindParam(':room_type', $this->room_type);
        
        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        
        return $data;
    }


    //     $sql = "SELECT 
    //         CONCAT(r.room_type, ' ', r.room_no) AS room_name,
    //         CONCAT(rt.room_code, '-', rt.room_description) AS room_details
    //         FROM room_list r
    //         INNER JOIN room_type rt ON r.room_type = rt.room_code
    //         WHERE (r.room_no LIKE CONCAT('%', :keyword, '%') OR rt.room_description LIKE CONCAT('%', :keyword, '%'))
    //         AND (:category = '' OR rt.room_code = :category);
    //     ";
    /*
    SELECT
    CONCAT(r.room_type, r.room_no) AS room_name,
    CONCAT(rt.room_code, '-', rt.room_description) AS room_details
    FROM room_list r
    INNER JOIN room_type rt ON r.room_type = rt.room_code;
    WHERE (r.room_no LIKE CONCAT('%', :keyword, '%') OR rt.room_description LIKE CONCAT('%', :keyword, '%'))
    AND (:category = '' OR rt.room_code = :category);
    */
     
    /*
    roomname, roomtype
    SELECT CONCAT(room_type, room_no) as room_type 
    FROM room_list rl 
    
SELECT
    CONCAT(r.room_type, r.room_no) AS room_type,
    CONCAT(rt.room_code, '-', rt.room_description) AS room_details
FROM room_list r
INNER JOIN room_type rt ON r.room_type = rt.room_code;

    */

    // function edit()
    // {
    //     $sql = "UPDATE product SET code = :code, name = :name, category_id = :category_id, price = :price WHERE id = :id;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':code', $this->code);
    //     $query->bindParam(':name', $this->name);
    //     $query->bindParam(':category_id', $this->category_id);
    //     $query->bindParam(':price', $this->price);
    //     $query->bindParam(':id', $this->id);
    //     return $query->execute();
    // }
    
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
            "SELECT id as type_id, CONCAT(room_code,' ',room_description) AS r_type 
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

    
}
