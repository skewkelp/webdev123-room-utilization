<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/account.class.php');


$user_id = $username = $user_role = '';
$is_admin = $is_staff = '';
$determiner = $first_name = $last_name = $password = '';
//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$generalErr = $user_idErr = $usernameErr = $user_roleErr = '';
$generalErr1 = $first_nameErr = $last_nameErr = $passwordErr = '';

$userObj = new Account();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = clean_input($_POST['user-id']);
    $username = clean_input($_POST['username']);
    $user_role = clean_input($_POST['user-role']);
    
    $determiner = clean_input($_POST['determiner']);
   
    if($_POST['determiner'] == 'true'){
        $first_name = clean_input($_POST['first-name']);
        $last_name = clean_input($_POST['last-name']);
        $password = clean_input($_POST['password']);
    }
    
    $user_id_valid = $username_valid = true;

    // Validate user_id
    if (empty($user_id)) {
        $user_id_valid = false;
        $user_idErr = 'User ID is required.';
    } else if (!preg_match('/^\d{9}$/', $user_id)) {
        $user_id_valid = false;
        $user_idErr = 'User ID must be in the format: 9 digits (e.g., 202401234).';
    }else if($userObj->useridExistList($user_id)){
        $user_id_valid = false;
        $generalErr = '<strong>USER ID EXIST!</strong><br> User ID must be unique to each users.';
        $user_idErr = 'Invalid.';
    }
        
    // Validate username
    if (empty($username)) {
        $username_valid = false;
        $usernameErr = 'User ID is required.';
    } else if (!preg_match('/^[a-z]{2}\d{9}$/', $username)) {
        $username_valid = false;
        $usernameErr = 'Username must be in the format: 2 lowercase letters followed by 9 digits, the users id. (e.g., 202401234).';
        
    }
    

    // //Error template feed
    // $generalErr = '<strong>ERROR FORM!</strong><br> Var:' . $usernameErr ;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;
    
    if(empty($user_role)) {
        $user_roleErr = 'User role is required';
    }else{
        if($user_role == 'student'){
            $is_admin = 0;
            $is_staff = 0;
            
        }else if($user_role == 'faculty'){
            $is_admin = 0;
            $is_staff = 1;
            
        }else if($user_role == 'admin'){
            $is_admin = 1;
            $is_staff = 0;
            
        }else if($user_role == 'admin-faculty'){
            $is_admin = 1;
            $is_staff = 1;   
        }

    }

    if ($user_id_valid == true && $username_valid == true) {
        // Example username: ab202012345
        $split_input = [];
        
        if (preg_match('/^([a-z]{2})(\d{9})$/', $username, $split_input)) {
            $letters = $split_input[1]; // 'ab'
            $user_idChecker = $split_input[2]; // '202012345'
            
            // Check if the numeric part matches the user ID
            if ($user_id != $user_idChecker) {
                $generalErr = '<strong>MISMATCH ID AND USERNAME!</strong><br> Username must have the same digits as User ID.';
                $user_idErr = 'Invalid';
                $usernameErr = 'Invalid';
            }
        } 
    }

    if($_POST['determiner'] == 'true'){
        if(empty($first_name)) {
            $first_nameErr = 'First name is required';
        }

        if(empty($last_name)) {
            $last_nameErr = 'Last name is required';
        }

        if(empty($password)){
            $passwordErr = 'Password is required';
        }        
    }

      // Check for any errors
    if (!empty($generalErr) || !empty($user_idErr) || !empty($usernameErr) || !empty($user_roleErr) && $determiner == 'false') {
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'user_idErr' => $user_idErr,
            'usernameErr' => $usernameErr,
            'user_roleErr' => $user_roleErr
        ]);
        exit;
    }else if (!empty($generalErr1) || !empty($first_nameErr) || !empty($last_nameErr) || !empty($passwordErr) || !empty($generalErr) || !empty($user_idErr) || !empty($usernameErr) || !empty($user_roleErr) && $determiner == 'true') {
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'user_idErr' => $user_idErr,
            'usernameErr' => $usernameErr,
            'user_roleErr' => $user_roleErr,
            'generalErr1' => $generalErr1,
            'first_nameErr' => $first_nameErr,
            'last_nameErr' => $last_nameErr,
            'passwordErr' => $passwordErr
        ]);
        exit;
    }

    // Check existing subject code
    if ($userObj->checkExistingUser($user_id, $username)) {
        $generalErr = '<strong>USER ID ' . $user_id . ' AND ' . $username . ' ALREADY EXISTS!</strong><br>this user already exist on the list.';
        $user_idErr = 'Invalid';
        $usernameErr = 'Invalid';
        echo json_encode([
            'status' => 'error',
            'user_idErr' => $user_idErr,
            'usernameErr' => $usernameErr
        ]);
        exit;
    }

    $userObj->user_id = $user_id;
    $userObj->username = $username;
    $userObj->is_admin = $is_admin;
    $userObj->is_staff = $is_staff;
    
    if($determiner == 'false'){
        if ($userObj->insertUser()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new subject details.']);
        }
    }else if($determiner == 'true'){
        $userObj->account_id = $user_id;
        $userObj->first_name = $first_name;
        $userObj->last_name = $last_name;
        $userObj->password = $password;
        
        if ($userObj->insertUser() && $userObj->insertAccount()) {
            echo json_encode(['status' => 'success']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new subject details.']);
        }
    }


    exit;
        
}

?>
