<?php

function normal_text($data)
{
    if (gettype($data) !== "array") {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    return '';
}

function normal_text_back($text)
{
    if (gettype($text) !== "array") {
        return htmlspecialchars_decode(trim($text), ENT_QUOTES);
    }
    return '';
}

function normal_date($date, $format = 'M d, Y h:i A')
{
    $d = date_create($date);
    return date_format($d, $format);
}

function current_date($format = 'Y-m-d H:i:s')
{
    return date($format);
}

function normal_to_db_date($date, $format = 'Y-m-d H:i:s')
{
    $d = date_create($date);
    return date_format($d, $format);
}

function go ($URL)
{
    header("location: $URL");
    die();
}

function get_ip() {  
    /* if share internet */
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    }  
    /* if proxy */
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
    /* if remote address */
    else{  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;
}  

function check_login ()
{
    
    if (!isset($_SESSION['logged']) || !isset($_SESSION['logged_type'])) {
        return ['status' => false, 'data' => "You are not logged in"];
    }

    $user_id = $_SESSION['logged'];

    global $db;

    $q = "SELECT * FROM `users` WHERE `user_id` = :i";

    $st = $db->prepare($q);
    $st->bindParam(":i", $user_id);

    if ($st->execute()) {

        if ($st->rowCount() < 1) {
            logout();
            return ['status' => false, 'data' => "Unable to find logged user"];
        }

        $user = $st->fetch();

        if ($user['user_active'] == '1') {
            
            // breaking name into first name last name
            
            $fullname = explode(" ", $user['user_fullname']);
            
            $user['user_fname'] = $fullname[0];
            $user['user_lname'] = "";
            if (count($fullname) > 1) {
                unset($fullname[0]);
                $user['user_lname'] = implode(' ', $fullname);
            }

            return ['status' => true, 'data' => $user];

        } else {
            logout();
            return ['status' => false, 'data' => 'Your account is inactive'];
        }

    } else {
        return ['status' => false, 'data' => "Unable to check if your are logged in"];
    }


}

function logout ()
{
    unset($_SESSION['logged']);
    unset($_SESSION['logged_type']);
}
