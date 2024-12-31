<?php
session_start();

require_once('../tools/functions.php');
require_once('../classes/account.class.php');


$pass_key = $account_id = $first_name = $last_name = $password = '';
//this var refers to the id of the section, room, subject, and teacher selected in the dropdown list
$generalErr = $first_nameErr = $last_nameErr = $passwordErr = $confirm_passwordErr = '';

$userObj = new Account();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $account_id = clean_input($_POST['account-id']);
    $first_name = clean_input($_POST['first-name']);
    $last_name = clean_input($_POST['last-name']);

    if($_POST['change-passkey'] == 'true'){
        $password = clean_input($_POST['password']);
        $confirm_password = clean_input($_POST['confirm-password']);
    }
    
    // //Error template feed
    // $pass_key = clean_input($_POST['change-passkey']);
    // $generalErr = '<strong>ERROR FORM!</strong><br> Var:' . $pass_key ;

    // echo json_encode([
    //     'status' => 'error',
    //     'generalErr' => $generalErr
    // ]);
    // exit;
    
    if(empty($first_name)) {
        $first_nameErr = 'First name is required';
    }

    if(empty($last_name)) {
        $last_nameErr = 'Last name is required';
    }

    if($_POST['change-passkey'] == 'true'){
        if(empty($password)){
            $passwordErr = 'Password is required';
        }     
        
        if(empty($confirm_password)){
            $confirm_passwordErr = 'Password is required';
        }  
        
        if(!empty($password) && !empty($confirm_password)){
            if($password != $confirm_password){
                $generalErr = '<strong>PASSWORD MISMATCHED!</strong><br>To confirm and change password, the password inputs must match.';
                $passwordErr = 'Invalid';
                $confirm_passwordErr = 'Invalid';
            }
        }
    }
    
    // Check for any errors
    if (!empty($generalErr) || !empty($first_nameErr) || !empty($last_nameErr) || !empty($passwordErr)) {
        echo json_encode([
            'status' => 'error',
            'generalErr' => $generalErr,
            'first_nameErr' => $first_nameErr,
            'last_nameErr' => $last_nameErr,
            'passwordErr' => $passwordErr,
            'confirm_passwordErr' => $confirm_passwordErr
        ]);
        exit;
    }

    $userObj->first_name = $first_name;
    $userObj->last_name = $last_name;
    $userObj->password = $password;
    
    if ($userObj->updateProfile($account_id)) {
        echo json_encode(['status' => 'success']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when updating user and account details.']);
    }
    


    exit;
        
}

?>
