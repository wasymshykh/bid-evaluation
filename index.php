<?php

$read_json = true;

require_once 'includes/config.php';

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['login'])) {

        if (isset($_POST['email']) && !empty($_POST['email']) && is_string($_POST['email']) && !empty(normal_text($_POST['email']))) {
            $email = normal_text($_POST['email']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email address pattern is invalid";
            } else {
                // checking for existing email
                $q = "SELECT * FROM `users` WHERE `user_email` = :e";
                $st = $db->prepare($q);
                $st->bindParam(":e", $email);

                if ($st->execute()) {

                    if ($st->rowCount() < 1) {
                        $errors[] = "Email address doesn't exists.";
                    } else {
                        $user = $st->fetch();
                    }

                } else {
                    $errors[] = "Unable to check email validity";
                }
            }
        } else {
            $errors[] = "Email cannot be empty!";
        }

        if (isset($_POST['password']) && !empty($_POST['password']) && is_string($_POST['password']) && !empty(normal_text($_POST['password']))) {
            $password = normal_text($_POST['password']);
        } else {
            $errors[] = "Password cannot be empty!";
        }

        if (empty($errors)) {

            // checking if the password is correct
            if (!password_verify($password, $user['user_password'])) {
                $errors[] = "Password is incorrect";
            } else {

                // recording last login
                $q = "UPDATE `users` SET `user_lastlogin` = :dt WHERE `user_id` = :i";
                $st = $db->prepare($q);
                $st->bindParam(":dt", current_date());
                $st->bindParam(":i", $user['user_id']);
                $st->execute();

                // updating session
                $_SESSION['logged'] = $user['user_id'];
                $_SESSION['logged_type'] = $user['user_isAdmin'];

                if ($user['user_isAdmin'] == '0') {
                    // account
                    go(URL.'/account.php');
                } else {
                    // admin
                    go(URL.'/admin.php');
                }
                
            }

        }

    } else if (isset($_POST['signup'])) {

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

        if (isset($_POST['remail']) && !empty($_POST['remail']) && is_string($_POST['remail']) && !empty(normal_text($_POST['remail']))) {
            $remail = normal_text($_POST['remail']);

            if (!filter_var($remail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email address pattern is invalid";
            } else {
                // checking for existing email

                $q = "SELECT * FROM `users` WHERE `user_email` = :e";
                $st = $db->prepare($q);
                $st->bindParam(":e", $remail);

                if ($st->execute()) {

                    if ($st->rowCount() > 0) {
                        $errors[] = "Email address already exists.";
                    }

                } else {
                    $errors[] = "Unable to check email validity";
                }
            }
        } else {
            $errors[] = "Email cannot be empty!";
        }
        
        if (isset($_POST['rpassword']) && !empty($_POST['rpassword']) && is_string($_POST['rpassword']) && !empty(normal_text($_POST['rpassword']))) {
            $rpassword = normal_text($_POST['rpassword']);

            if (mb_strlen($rpassword) < 9) {
                $errors[] = "Password must contain at least 8 characters.";
            } else if (isset($_POST['rrepassword']) && !empty($_POST['rrepassword']) && is_string($_POST['rrepassword']) && !empty(normal_text($_POST['rrepassword']))) {
                $rrepassword = normal_text($_POST['rrepassword']);

                if ($rpassword !== $rrepassword) {
                    $errors[] = "Passwords do not match, retype";
                }
            } else {
                $errors[] = "Retype the password!";
            }
        } else {
            $errors[] = "Password cannot be empty!";
        }


        if (empty($errors)) {

            $ip = PROJECT_MODE == 'development' ? '203.101.187.19' : get_ip();

            $rpassword = password_hash($rpassword, PASSWORD_BCRYPT);

            $q = "INSERT INTO `users` (`user_fullname`, `user_email`, `user_password`, `user_ip`) VALUES (:f, :e, :p, :i)";

            $st = $db->prepare($q);
            $st->bindParam(":f", $fullname);
            $st->bindParam(":e", $remail);
            $st->bindParam(":p", $rpassword);
            $st->bindParam(":i", $ip);

            if ($st->execute()) {
                $_SESSION['message'] = ['type' => 'success', 'data' => 'Account created! You can login now.'];
                go(URL.'/');
            } else {
                $errors[] = "Unable to create the account";
            }

        }


    }

}

require_once DIR.'views/index.view.php';
