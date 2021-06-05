<?php

$read_json = true;

require_once 'includes/config.php';

// checking the login
$logged = check_login();
if (!$logged['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $logged['data']];
    go (URL.'/');
}
$logged = $logged['data'];

// checking if the user is admin
if ($logged['user_isAdmin'] !== '1') {
    $_SESSION['message'] = ['type' => 'error', 'data' => 'You dont have permission to access that page.'];
    go (URL.'/account.php');
}

$profile_errors = [];
$password_errors = [];
$app_errors = [];
$smtp_errors = [];

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['update_profile'])) {

        $change = "";
        $d = [];
        if (isset($_POST['fullname']) && !empty($_POST['fullname']) && is_string($_POST['fullname']) && !empty(normal_text($_POST['fullname']))) {
            $fullname = normal_text($_POST['fullname']);

            if ($fullname != $logged['user_fullname']) {
                $change .= "`user_fullname` = :f";
                $d[':f'] = $fullname;
            }
        } else {
            $profile_errors[] = "Name field cannot be empty";
        }

        if (isset($_POST['email']) && !empty($_POST['email']) && is_string($_POST['email']) && !empty(normal_text($_POST['email']))) {
            $email = normal_text($_POST['email']);

            if ($email != $logged['user_email']) {
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $profile_errors[] = "Email address pattern is invalid";
                } else {
                    
                    // checking if the email already exists in the database
                    $q = "SELECT * FROM `users` WHERE `user_email` = :e";
                    $st = $db->prepare($q);
                    $st->bindParam(":e", $email);
                    if ($st->execute()) {
                        if ($st->rowCount() > 0) {
                            $profile_errors[] = "Email address already exists.";
                        } else {
                            if (!empty($change)) { $change .= ", "; }
                            $change .= "`user_email` = :e";
                            $d[':e'] = $email;
                        }
                    } else {
                        $profile_errors[] = "Unable to check email validity";
                    }

                }

            }
        } else {
            $profile_errors[] = "Email field cannot be empty";
        }

        if (empty($profile_errors)) {

            if (!empty($change)) {

                $d[':id'] = $logged['user_id'];
                $q = "UPDATE `users` SET $change WHERE `user_id` = :id";
                $st = $db->prepare($q);
                
                if ($st->execute($d)) {

                    $_SESSION['message'] = ['type' => 'success', 'data' => 'Profile settings has been updated!'];
                    go(URL.'/admin.php');

                } else {
                    $profile_errors[] = "Unable to update the profile settings";
                }

            } else {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'No changes made to profile settings'];
                go(URL.'/admin.php');
            }

        }


    } else if (isset($_POST['update_password'])) {

        if (isset($_POST['password']) && !empty($_POST['password']) && is_string($_POST['password']) && !empty(normal_text($_POST['password']))) {
            $password = normal_text($_POST['password']);

            if (mb_strlen($password) < 9) {
                $password_errors[] = "Password must contain at least 8 characters.";
            } else if (isset($_POST['repassword']) && !empty($_POST['repassword']) && is_string($_POST['repassword']) && !empty(normal_text($_POST['repassword']))) {
                $repassword = normal_text($_POST['repassword']);

                if ($password !== $repassword) {
                    $password_errors[] = "Passwords do not match, retype";
                }
            } else {
                $password_errors[] = "Retype the password!";
            }
        } else {
            $password_errors[] = "Password cannot be empty!";
        }

        if (empty($password_errors)) {
            
            $q = "UPDATE `users` SET `user_password` = :p WHERE `user_id` = :i";
    
            $st = $db->prepare($q);
    
            $st->bindParam(":p", password_hash($password, PASSWORD_BCRYPT));
            $st->bindParam(":i", $logged['user_id']);
    
            if ($st->execute()) {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'Admin password updated successfully!'];
                go(URL.'/admin.php');
            } else {
                $password_errors[] = "Unable to update the password";
            }

        }


    } else if (isset($_POST['update_settings'])) {
        
        if (isset($_POST['txt_welcome']) && !empty($_POST['txt_welcome']) && is_string($_POST['txt_welcome']) && !empty(normal_text($_POST['txt_welcome']))) {
            $settings->website->welcome = normal_text($_POST['txt_welcome']);
        } else {
            $app_errors[] = "Welcome field cannot be empty";
        }

        if (isset($_POST['txt_footer']) && !empty($_POST['txt_footer']) && is_string($_POST['txt_footer']) && !empty(normal_text($_POST['txt_footer']))) {
            $settings->website->footer = normal_text($_POST['txt_footer']);
        } else {
            $app_errors[] = "Footer field cannot be empty";
        }

        if (isset($_POST['txt_consider']) && !empty($_POST['txt_consider']) && is_string($_POST['txt_consider']) && !empty(normal_text($_POST['txt_consider']))) {
            $settings->website->consider = normal_text($_POST['txt_consider']);
        } else {
            $app_errors[] = "Consider field cannot be empty";
        }

        if (empty($app_errors)) {

            $r = save_json_data (DATA_FILE_PATH, $settings);

            if ($r['status']) {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'Website settings are successfully updated!'];
                go(URL.'/admin.php');
            } else {
                $app_errors[] = "Unable to update the website settings";
            }

        }

    } else if (isset($_POST['smtp_settings'])) {

        if (isset($_POST['email_enable'])) {
            $settings->email->enable = true;
        } else {
            $settings->email->enable = false;
        }

        if (isset($_POST['smtp_server']) && !empty($_POST['smtp_server']) && is_string($_POST['smtp_server']) && !empty(normal_text($_POST['smtp_server']))) {
            $settings->email->smtp->server = normal_text($_POST['smtp_server']);
        } else {
            $smtp_errors[] = "Server field cannot be empty";
        }
        
        if (isset($_POST['smtp_port']) && !empty($_POST['smtp_port']) && is_numeric($_POST['smtp_port']) && !empty(normal_text($_POST['smtp_port']))) {
            $settings->email->smtp->port = normal_text($_POST['smtp_port']);
        } else {
            $smtp_errors[] = "Port field cannot be empty or non-numeric";
        }

        if (isset($_POST['smtp_connection_type']) && !empty($_POST['smtp_connection_type']) && is_string($_POST['smtp_connection_type']) && !empty(normal_text($_POST['smtp_connection_type']))) {
            $settings->email->smtp->connection_type = normal_text($_POST['smtp_connection_type']);
        } else {
            $smtp_errors[] = "Select connection type";
        }

        if (isset($_POST['smtp_auth'])) {
            $settings->email->smtp->auth = true;
            
            if (isset($_POST['smtp_username']) && !empty($_POST['smtp_username']) && is_string($_POST['smtp_username']) && !empty(normal_text($_POST['smtp_username']))) {
                $settings->email->smtp->username = normal_text($_POST['smtp_username']);
            } else {
                $smtp_errors[] = "Username field cannot be empty";
            }

            if (isset($_POST['smtp_password']) && !empty($_POST['smtp_password']) && is_string($_POST['smtp_password']) && !empty(normal_text($_POST['smtp_password']))) {
                $settings->email->smtp->password = normal_text($_POST['smtp_password']);
            } else {
                $smtp_errors[] = "Password field cannot be empty";
            }
            
        } else {
            $settings->email->smtp->auth = false;
            $settings->email->smtp->username = $settings->email->smtp->username;
            $settings->email->smtp->password = $settings->email->smtp->password;
        }

        
        if (isset($_POST['smtp_sender']) && !empty($_POST['smtp_sender']) && is_string($_POST['smtp_sender']) && !empty(normal_text($_POST['smtp_sender']))) {
            $settings->email->sender_address = normal_text($_POST['smtp_sender']);
        } else {
            $smtp_errors[] = "Email Sender Address field cannot be empty";
        }
        if (isset($_POST['smtp_sender_name']) && !empty($_POST['smtp_sender_name']) && is_string($_POST['smtp_sender_name']) && !empty(normal_text($_POST['smtp_sender_name']))) {
            $settings->email->sender_name = normal_text($_POST['smtp_sender_name']);
        } else {
            $smtp_errors[] = "Email Sender Name field cannot be empty";
        }

        if (isset($_POST['smtp_subject']) && !empty($_POST['smtp_subject']) && is_string($_POST['smtp_subject']) && !empty(normal_text($_POST['smtp_subject']))) {
            $settings->email->subject = normal_text($_POST['smtp_subject']);
        } else {
            $smtp_errors[] = "Email subject field cannot be empty";
        }

        if (isset($_POST['smtp_email_body']) && !empty($_POST['smtp_email_body']) && is_string($_POST['smtp_email_body']) && !empty(normal_text($_POST['smtp_email_body']))) {
            $settings->email->body = normal_text($_POST['smtp_email_body']);
        } else {
            $smtp_errors[] = "Email body field cannot be empty";
        }


        if (empty($smtp_errors)) {
            
            $r = save_json_data (DATA_FILE_PATH, $settings);

            if ($r['status']) {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'SMTP settings are successfully updated!'];
                go(URL.'/admin.php#');
            } else {
                $smtp_errors[] = "Unable to update the smtp settings";
            }

        }
        

    }

}


require_once DIR.'views/admin.view.php';
