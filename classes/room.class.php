<?php

require_once 'database.class.php';

class Product
{
    public $id = '';
    public $code = '';
    public $name = '';
    public $category_id = '';
    public $price = '';

    public $room_name = '';
    public $room_no = '';
    public $room_type = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function add()
    {
        $sql = "INSERT INTO product (code, name, category_id, price) VALUES (:code, :name, :category_id, :price);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $this->code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':price', $this->price);
        return $query->execute();
    }

    function showAll($keyword = '', $category = '')
    {
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
    $sql = "SELECT 
            CONCAT(r.room_type, ' ', r.room_no) AS room_name,
            CONCAT(rt.room_code, '-', rt.room_description) AS room_details
        FROM room_list r
        INNER JOIN room_type rt ON r.room_type = rt.room_code
        WHERE (CONCAT(r.room_type, ' ', r.room_no) LIKE CONCAT('%', :room_name, '%')) 
        AND (:room_type = '' OR rt.room_code = :room_type);
    ";
    
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':room_name', $this->room_name);
    $query->bindParam(':room_type', $this->room_type);
    
    $data = null;
    if ($query->execute()) {
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

    function edit()
    {
        $sql = "UPDATE product SET code = :code, name = :name, category_id = :category_id, price = :price WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $this->code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':price', $this->price);
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    function fetchRoomName($recordID)
    {
        $sql = "SELECT room_type, room_no FROM room_list WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function delete($recordID)
    {
        $sql = "DELETE FROM product WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        return $query->execute();
    }

    function codeExists($code, $excludeID = null)
    {
        $sql = "SELECT COUNT(*) FROM product WHERE code = :code";
        if ($excludeID) {
            $sql .= " AND id != :excludeID";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $code);
        if ($excludeID) {
            $query->bindParam(':excludeID', $excludeID);
        }
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    }

    public function fetchCategory()
    {
        $sql = "SELECT * FROM category ORDER BY name ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    public function fetchroomType()
    {
        $sql = " SELECT room_code, room_description FROM room_type;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    public function fetchroomList()
    {
        $sql = " SELECT * FROM room_list;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

}
