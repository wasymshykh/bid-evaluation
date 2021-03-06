<?php

use PHPMailer\PHPMailer\PHPMailer;

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

function get_sol_by_id ($sol_id)
{
    global $db;
    $q = "SELECT * FROM `solicitations` JOIN `users` ON `solicitation_user_id` = `user_id` WHERE `solicitation_id` = :s";
    $st = $db->prepare($q);
    $st->bindParam(":s", $sol_id);
    if ($st->execute()) {
        if ($st->rowCount() > 0) {
            return ['status' => true, 'data' => $st->fetch()];
        } 
        return ['status' => false, 'data' => 'Solicitation not found'];
    }
    
    return ['status' => false, 'data' => 'Unable to get the data'];
}

function get_sols_by_user_id ($user_id)
{
    global $db;
    $q = "SELECT * FROM `solicitations` JOIN `users` ON `solicitation_user_id` = `user_id` WHERE `solicitation_user_id` = :u AND `solicitation_calculated_pwin` IS NOT NULL";
    $st = $db->prepare($q);
    $st->bindParam(":u", $user_id);
    if ($st->execute()) {
        if ($st->rowCount() > 0) {
            return ['status' => true, 'data' => $st->fetchAll()];
        } 
        return ['status' => false, 'data' => 'Solicitation not found'];
    }
    
    return ['status' => false, 'data' => 'Unable to get the data'];
}

function get_sols ()
{
    global $db;
    $q = "SELECT * FROM `solicitations` JOIN `users` ON `solicitation_user_id` = `user_id` WHERE `solicitation_calculated_pwin` IS NOT NULL";
    $st = $db->prepare($q);
    if ($st->execute()) {
        if ($st->rowCount() > 0) {
            return ['status' => true, 'data' => $st->fetchAll()];
        } 
        return ['status' => false, 'data' => 'Solicitation not found'];
    }
    
    return ['status' => false, 'data' => 'Unable to get the data'];
}

function get_requirements_by_sol_id ($sol_id)
{
    global $db;
    $q = "SELECT * FROM `requirements` WHERE `requirement_solicitation_id` = :s";
    $st = $db->prepare($q);
    $st->bindParam(":s", $sol_id);
    if ($st->execute()) {
        if ($st->rowCount() > 0) {
            return ['status' => true, 'data' => $st->fetchAll()];
        } 
        return ['status' => false, 'data' => 'Requirements not found'];
    }
    
    return ['status' => false, 'data' => 'Unable to get the data'];
}

function calculate_gap ($gap, $ability)
{
    if ($gap == 'yes') {

        if ($ability >= 0 && $ability <= 25) {
            return [0, 'Low', 'red'];
        } else if ($ability >= 26 && $ability <= 50) {
            return [1, 'Low', 'orange'];
        } else if ($ability >= 51 && $ability <= 75) {
            return [2, 'Medium', 'yellow'];
        } else if ($ability >= 76 && $ability <= 100) {
            return [3, 'Medium', 'green'];
        }

    } else if ($gap == 'no') {

        if ($ability >= 0 && $ability <= 25) {
            return [3, 'Low', 'orange'];
        } else if ($ability >= 26 && $ability <= 50) {
            return [4, 'Medium', 'yellow'];
        } else if ($ability >= 51 && $ability <= 75) {
            return [5, 'High', 'green'];
        } else if ($ability >= 76 && $ability <= 100) {
            return [6, 'High', 'green'];
        }

    }

    return [0, '-', ''];
}

function calculate_risk_rating ($risk, $impact)
{
    if ($impact == 'low' && $risk == 'low') {
        return [1, 'Low', 'yellowgreen'];
    } else if ($impact == 'low' && $risk == 'med') {
        return [2, 'Low', 'yellowgreen'];
    } else if ($impact == 'low' && $risk == 'high') {
        return [3, 'Medium', 'yellow'];
    } else if ($impact == 'med' && $risk == 'low') {
        return [2, 'Low', 'yellowgreen'];
    } else if ($impact == 'med' && $risk == 'med') {
        return [4, 'Medium', 'yellow'];
    } else if ($impact == 'med' && $risk == 'high') {
        return [6, 'High', 'red'];
    } else if ($impact == 'high' && $risk == 'low') {
        return [3, 'Medium', 'yellow'];
    } else if ($impact == 'high' && $risk == 'med') {
        return [6, 'High', 'red'];
    } else if ($impact == 'high' && $risk == 'high') {
        return [9, 'High', 'red'];
    }

    return [0, '-', ''];
}

function calculate_pwin ($gaps_average, $risks_average, $ability)
{
    if (($gaps_average == 0 || $gaps_average == 1) && $risks_average == 1 && ($ability >= 76 && $ability <= 100)) {
        return "BID";
    }
    if ($gaps_average == 2 && $risks_average == 2 && ($ability >= 51 && $ability <= 75)) {
        return "Agree/Likely";
    }
    
    if ($gaps_average == 3 && $risks_average == 3 && ($ability >= 26 && $ability <= 50)) {
        return "Agree/Maybe";
    }
    
    if ($gaps_average == 4 &&  ($risks_average == 4 || $risks_average == 5) && ($ability >= 26 && $ability <= 50)) {
        return "Neutral";
    }
    
    if ($gaps_average == 5 && ($risks_average == 6 || $risks_average == 7) && ($ability >= 0 && $ability <= 25)) {
        return "Low P-win";
    }

    if ($gaps_average == 5 && ($risks_average == 8 || $risks_average == 9) && ($ability >= 0 && $ability <= 25)) {
        return "No Bid";
    }

    return "----";
}

function get_pwin_table_index ($pwin)
{
    if ($pwin == "BID") { return 0; }
    if ($pwin == "Agree/Likely") { return 1; }
    if ($pwin == "Agree/Maybe") { return 2; }
    if ($pwin == "Neutral") { return 3; }
    if ($pwin == "Low P-win") { return 4; }
    if ($pwin == "No Bid") { return 5; }
    return -1;
}

function read_json_data ($file)
{
    
    // check if the file exists
    if (file_exists($file)) {
        // read-only file mode
        $handle = fopen($file, "r+") or die("Unable to read data configuration file.");
        $file_size = filesize($file);
        if ($file_size === 0) { $file_size = 1; }
        $contents = fread($handle, $file_size);
        fclose($handle);
        $json = json_decode($contents);
        return $json;
    } else {
        // error is printed
        die('Data configuration file not found.');
    }
    
}

function save_json_data ($file, $settings)
{
    
    if (file_exists($file) && is_writable($file) && filesize($file) != 0) {
        $handle = fopen($file, "w+") or die("Unable to read data configuration file.");
        fwrite($handle, json_encode($settings));
        fclose($handle);

        return ['status' => true];
    } 

    return ['status' => false];
}


use PHPMailer\PHPMailer\SMTP;

function mail_sender (PHPMailer $mail, $subject, $body, $to, $to_name, $ccs, $attachment, $attachment_name) {

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;

        $mail->SMTPAuth = SMTP_AUTH;
        if (SMTP_AUTH == true) {
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
        }

        $mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
    
        //Recipients
        
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);

        $mail->addAddress($to, $to_name);

        foreach ($ccs as $cc) {
            $mail->addCC($cc);
        }
        
        //Attachments
        if ($attachment) {
            $mail->addStringAttachment($attachment, $attachment_name);
        }

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
    
        $mail->send();

        return ['status' => true];
    } catch (Exception $e) {
        return ['status' => false, 'data' => $mail->ErrorInfo];
    }

}

function logout ()
{
    unset($_SESSION['logged']);
    unset($_SESSION['logged_type']);
}
