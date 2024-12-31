<?php

require_once 'database.class.php';

class Account
{
    public $account_id = '';
    public $user_id = '';
    public $first_name = '';
    public $last_name = '';
    public $username = '';
    public $password = '';
    public $is_staff = '';
    public $is_admin = '';


    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function insertAccount(){//CREATE ACCOUNT FOR STUDENTS
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

    function insertUser(){//CREATE ACCOUNT FOR STUDENTS
        $sql = "INSERT INTO user_list(user_id, username, is_admin, is_staff) VALUES (:user_id, :username, :is_admin, :is_staff);";
   
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':user_id', $this->user_id);
        $query->bindParam(':username', $this->username);
        $query->bindParam(':is_admin', $this->is_admin);
        $query->bindParam(':is_staff', $this->is_staff);
       
        return $query->execute();
    }


    function updateUser($originalUserID){
        $sql = "UPDATE user_list 
            SET user_id = :user_id,
                username = :username, 
                is_admin = :is_admin, 
                is_staff = :is_staff 
                
            WHERE user_id = :originalUserID;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_id', $this->user_id);
        $query->bindParam(':username', $this->username);
        $query->bindParam(':is_admin', $this->is_admin);
        $query->bindParam(':is_staff', $this->is_staff);
        
        $query->bindParam(':originalUserID', $originalUserID);
        $query->execute();
        return true;
    }

    function updateAccount($originalUserID){
        $sql = "UPDATE account 
            SET account_id = :account_id,
                first_name = :first_name, 
                last_name = :last_name, 
                username = :username, 
                `password` = :password 
                
            WHERE account_id = :originalUserID;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->account_id);
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':username', $this->username);
        $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashpassword);
        $query->bindParam(':originalUserID', $originalUserID);
        $query->execute();
        return true;
    }

    function updateProfile($accountID){
        $sql = "UPDATE account 
            SET 
                first_name = :first_name, 
                last_name = :last_name, 
                `password` = :password 
                
            WHERE account_id = :accountID;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashpassword);
        $query->bindParam(':accountID', $accountID);
        $query->execute();
        return true;
    }

    //CHecks if student account exist for signup
    function useridExist($userID){
        $sql = "SELECT COUNT(*) FROM account WHERE account_id = :account_id"; 

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $userID);

        $count = $query->execute() ? $query->fetchColumn() : 0;

        return $count > 0;
    }

    //check if user exist on list for signup
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

    //checks is user id exist on user list for page userlist
    function userIdExistList($userID, $excludeID = null){
        $sql = "SELECT * 
            FROM user_list list
        WHERE list.user_id = :user_id";
        if ($excludeID !== null) {
            $sql .= " AND list.user_id != :excludeID";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_id', $userID);

        if($excludeID !== null){
            $query->bindParam(':excludeID', $excludeID);
        }

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        
            return $data? true : false;
        }
  
        return $data;
    }

    //check username match on signup
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

    //SHOW PROFILE 
    function showProfile($accountID){
        $sql = "SELECT 
            account_id, 
            first_name,
            last_name,
            username

        FROM account
        
        WHERE account_id = :accountID; 
        ";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':accountID', $accountID);
        
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }

        return $data;
    }

    //SHOW AND FETCH USER RECORD
    function showuserList($recordID = null){
        $sql = "SELECT 
            ulist.user_id AS user_id,
            ulist.username AS username,
            ulist.is_admin AS is_admin,
            ulist.is_staff AS is_staff,
            acc.first_name AS first_name,
            acc.last_name AS last_name
        
        FROM user_list ulist
        LEFT JOIN account acc ON ulist.user_id = acc.account_id
        ";

        if($recordID !== null){
            $sql .= " WHERE user_id = :recordID;";
        }

        $query = $this->db->connect()->prepare($sql);
        if($recordID !== null){
            $query->bindParam(':recordID', $recordID);
            $query->execute();
            $data = $query->fetch();
            return $data;
        }        
        
        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }


    function checkExistingUser($userID, $username, $excludeID = null, $excludeUsername = null){
        $sql = "SELECT user_id, username 
            FROM user_list 
        WHERE user_id = :userID AND username = :username"; 

        if($excludeID !== null && $excludeUsername !== null){
            $sql .= " AND (user_id != :excludeID AND username != :excludeUsername);";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':userID', $userID);
        $query->bindParam(':username', $username);
        
        if($excludeID !== null && $excludeUsername !== null){
            $query->bindParam(':excludeID', $excludeID);
            $query->bindParam(':excludeUsername', $excludeUsername);
        }

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        
            return $data? true : false;
        }
  
        return $data;
    }

    function deleteUser(){
        $sql = "DELETE FROM user_list WHERE user_id = :user_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':user_id', $this->user_id);
        $query->execute();
        return true;
    }

    
}

// $obj = new Account();

// $obj->add();