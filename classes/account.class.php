<?php

require_once 'database.class.php';

class Account
{
    public $account_id = '';
    public $first_name = '';
    public $last_name = '';
    public $username = '';
    public $password = '';
    public $is_staff = true;
    public $is_admin = true;


    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function add(){//CREATE ACCOUNT FOR STUDENTS
        $sql = "INSERT INTO account(account_id, first_name, last_name, username, password) VALUES (:account_id, :first_name, :last_name, :username, :password);";
   
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':account_id', $this->account_id);
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':username', $this->username);
        $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashpassword);
     

        return $query->execute();
    }

    //CHecks if student account exist
    function useridExist($userID){
        $sql = "SELECT COUNT(*) FROM account WHERE account_id = :account_id"; 

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $userID);

        $count = $query->execute() ? $query->fetchColumn() : 0;

        return $count > 0;

    }

    //check if username exist within
    function userExist($userID, $excludeID = ''){
        $sql = "SELECT COUNT(*) 
            FROM user_list list
        WHERE list.user_id = :user_id";
        if ($excludeID) {
            $sql .= " AND list.user_id != :excludeID";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_id', $userID);
        if ($excludeID) {
            $query->bindParam(':excludeID', $excludeID);
        }
        // $count = $query->execute() ? $query->fetchColumn() : 0;
        $count = $query->execute();
        $count = $query->fetchColumn();


        return $count == 0;
    }

    function checkusernameMatch($userID, $username){
        $sql = "SELECT COUNT(*) 
            FROM user_list 
        WHERE user_id = :user_id AND username = :username"; 

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_id', $userID);
        $query->bindParam(':username', $username);

        // $count = $query->execute() ? $query->fetchColumn() : 0;
        $count = $query->execute();
        $count = $query->fetchColumn();

        return $count == 0;


    }
    // function usernameExist($username, $excludeID = ''){
    //     $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
    //     if ($excludeID) {
    //         $sql .= " and id != :excludeID";
    //     }

    //     $query = $this->db->connect()->prepare($sql);
    //     $query->bindParam(':username', $username);

    //     if ($excludeID) {
    //         $query->bindParam(':excludeID', $excludeID);
    //     }

    //     $count = $query->execute() ? $query->fetchColumn() : 0;

    //     return $count > 0;
    // }



    function login($username, $password){
        $sql = "SELECT * FROM account WHERE username = :username LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':username', $username);

        if ($query->execute()) {
            $data = $query->fetch();
            if ($data && password_verify($password, $data['password'])) {
                return true;
            }
        }

        return false;
    }

    function fetch($username){
        $sql = "SELECT * FROM account acc LEFT JOIN user_list list ON acc.account_id = list.user_id WHERE acc.username = :username LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':username', $username);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }

    function showAllusers($excludeAdmin = 1){
        $sql = "SELECT * FROM account WHERE is_admin != :excludeAdmin;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':excludeAdmin', $excludeAdmin);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }

        return $data;
    }

    function showuserList(){
        $sql = "SELECT * FROM user_list";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
}

// $obj = new Account();

// $obj->add();