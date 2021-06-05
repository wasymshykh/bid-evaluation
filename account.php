<?php

use PHPMailer\PHPMailer\PHPMailer;

$read_json = true;
$activate_mailer = true;

require_once 'includes/config.php';
require_once DIR.'vendor/autoload.php';

// checking the login
$logged = check_login();
if (!$logged['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $logged['data']];
    go (URL.'/');
}
$logged = $logged['data'];


if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['update_profile'])) {

        // checking for validation
        
        if (isset($_POST['fname']) && !empty($_POST['fname']) && is_string($_POST['fname']) && !empty(normal_text($_POST['fname']))) {
            $fname = normal_text($_POST['fname']);
            $fullname = $fname;
        } else {
            $errors[] = "First name cannot be empty!";
        }

        if (isset($_POST['lname']) && !empty($_POST['lname']) && is_string($_POST['lname']) && !empty(normal_text($_POST['lname']))) {
            $lname = normal_text($_POST['lname']);
            $fullname = ($fname ?? '').' '.$lname;
        } else {
            $lname = "";
            $fullname = ($fname ?? '');
        }


        if (empty($errors)) {

            if ($fname != $logged['user_fname'] || $lname != $logged['user_lname']) {
                
                $q = "UPDATE `users` SET `user_fullname` = :n WHERE `user_id` = :i";

                $st = $db->prepare($q);

                $st->bindParam(":n", $fullname);
                $st->bindParam(":i", $logged['user_id']);

                if ($st->execute()) {
                    $_SESSION['message'] = ['type' => 'success', 'data' => 'Account updated successfully!'];
                    go(URL.'/account.php');
                } else {
                    $errors[] = "Unable to update the name";
                }


            } else {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'No changes made to the name'];
                go (URL.'/account.php');
            }

        }


    } else if (isset($_POST['send_mail']) || isset($_POST['delete'])) {

        if (isset($_POST['sol_id']) && is_numeric($_POST['sol_id']) && !empty($_POST['sol_id'])) {
            $sol_id = normal_text($_POST['sol_id']);

            // getting sol
            $sol = get_sol_by_id($sol_id);
            if ($sol['status']) {
                $sol = $sol['data'];

                if (($sol['solicitation_user_id'] != $logged['user_id']) && $logged['user_isAdmin'] != '1') {
                    $errors[] = "You don't have permission to access that solicitation";
                } else if (empty($sol['solicitation_blob'])) {
                    $errors[] = "Solicitation wasn't generated properly";
                }

            } else {
                $errors[] = "Solicitation doesn't exists";
            }

        } else {
            $errors[] = "Invalid data sent";
        }

        if (empty($errors)) {

            if (isset($_POST['delete'])) {

                $q = "DELETE FROM `solicitations` WHERE `solicitation_id` = :i";
                $st = $db->prepare($q);
                $st->bindParam(":i", $sol_id);
                if ($st->execute()) {
                    $_SESSION['message'] = ['type' => 'success', 'data' => 'Solicitation successfully deleted'];
                    go(URL.'/account.php');
                } else {
                    $errors[] = "Unable to delete solicitation";
                }

            } else {
                $mail = new PHPMailer(true);
                $r = mail_sender($mail, $settings->email->subject, $settings->email->body, $logged['user_email'], $logged['user_fullname'], [], $sol['solicitation_blob'], "solicitation-$sol_id.pdf");
                if ($r['status']) {
                    $_SESSION['message'] = ['type' => 'success', 'data' => 'Email successfully sent.'];
                    go(URL.'/account.php');
                } else {
                    $errors[] = "Unable to send email";
                }
            }

        }

    }  else if (isset($_POST['update_password'])) {

        if (isset($_POST['password']) && !empty($_POST['password']) && is_string($_POST['password']) && !empty(normal_text($_POST['password']))) {
            $password = normal_text($_POST['password']);

            if (mb_strlen($password) < 9) {
                $password_errors[] = "Password must contain at least 8 characters.";
            } else if (isset($_POST['repassword']) && !empty($_POST['repassword']) && is_string($_POST['repassword']) && !empty(normal_text($_POST['repassword']))) {
                $repassword = normal_text($_POST['repassword']);

                if ($password !== $repassword) {
                    $password_errors[] = "Passwords do not match, retype";
                } else {
                    $password = password_hash($password, PASSWORD_BCRYPT);
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
            
            $st->bindParam(":p", $password);
            $st->bindParam(":i", $logged['user_id']);
            if ($st->execute()) {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'Password updated successfully!'];
                go(URL.'/account.php');
            } else {
                $password_errors[] = "Unable to update the password";
            }
        }


    }
    
}

if ($logged['user_isAdmin'] == '0') {
    $sols = get_sols_by_user_id ($logged['user_id']);
    if ($sols['status']) {
        $sols = $sols['data'];
    } else {
        $sols = [];
    }
} else {

    $sols = get_sols ();
    if ($sols['status']) {
        $sols = $sols['data'];
    } else {
        $sols = [];
    }

}

require_once DIR.'views/account.view.php';
