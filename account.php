<?php

require_once 'includes/config.php';

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


    }

}


$footer_message = "I am footer";

require_once DIR.'views/account.view.php';
