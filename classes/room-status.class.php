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
    // cl.section_name, 
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
    
    function showAllRooms() {

        $sql = 
            "SELECT 
                cda.week_day, 
                rl.room_name, 
                rl.room_type,
                sd.subject_code, 
                sd.subject_type,
                cl.section_name, 
                ct.start_time, 
                ct.end_time,
                fl.fname,
                rs._status
            FROM 
                class_day cda 
            LEFT JOIN 
                class_time ct ON cda.class_id = ct.id
            LEFT JOIN 
                class_details cdt ON ct.class_id = cdt.id
            LEFT JOIN 
                room_list rl ON cdt.room_id = rl.id
            LEFT JOIN 
                subject_details sd ON cdt.subject_id = sd.id  
            LEFT JOIN 
                class_list cl ON cdt.class_name = cl.section_name
            LEFT JOIN 
                faculty_list fl ON cdt.teacher_assigned = fl.id
            RIGHT JOIN 
                room_status rs ON rs.class_id = cdt.id 
                AND rs.room_id = cdt.room_id 

            WHERE cda.week_day LIKE 
        ;";
    
        // Prepare and execute the statement with PDO
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':room_name', $this->room_name);
        $stmt->bindParam(':room_type', $this->room_type);
        $stmt->bindParam(':subject_code', $this->subject_code);
        $stmt->bindParam(':section_name', $this->section_name);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':teacher_name', $this->teacher_assigned);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // function showAllrooms(){
    // $sql = "SELECT ;
    // ";

    /*//WITH ROOM TYPE ROOM NO
    $sql = "SELECT 
            CONCAT(r.room_type, ' ', r.room_no) AS room_name,
            CONCAT(rt.room_code, '-', rt.room_description) AS room_details
        FROM room_list r
        INNER JOIN room_type rt ON r.room_type = rt.room_code
        WHERE (CONCAT(r.room_type, ' ', r.room_no) LIKE CONCAT('%', :room_name, '%')) 
        AND (:room_type = '' OR rt.room_code = :room_type);
    ";

    */
   
    //     $sql = "SELECT 
    //         CONCAT(r.room_type, ' ', r.room_no) AS room_name,
    //         CONCAT(rt.room_code, '-', rt.room_description) AS room_details
    //         FROM room_list r
    //         INNER JOIN room_type rt ON r.room_type = rt.room_code
    //         WHERE (r.room_no LIKE CONCAT('%', :keyword, '%') OR rt.room_description LIKE CONCAT('%', :keyword, '%'))
    //         AND (:category = '' OR rt.room_code = :category);
    //     ";    $sql =
        
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

    // function codeExists($code, $excludeID = null)
    // {
    //     $sql = "SELECT COUNT(*) FROM product WHERE code = :code";
    //     if ($excludeID) {
    //         $sql .= " AND id != :excludeID";
    //     }
    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':code', $code);
    //     if ($excludeID) {
    //         $query->bindParam(':excludeID', $excludeID);
    //     }
    //     $query->execute();
    //     $count = $query->fetchColumn();
    //     return $count > 0;
    // }

    // public function fetchCategory()
    // {
    //     $sql = "SELECT * FROM category ORDER BY name ASC;";
    //     $query = $this->db->connect()->prepare($sql);
    //     $data = null;
    //     if ($query->execute()) {
    //         $data = $query->fetchAll(PDO::FETCH_ASSOC);
    //     }
    //     return $data;
    // }

    //fetch room type for dropdown
    public function fetchroomType(){
        $sql = " SELECT *, id as type_id, CONCAT(room_code,' ',room_description) as room_type FROM room_type;";
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
