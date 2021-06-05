<?php

$read_json = true;
$activate_mailer = true;

require_once 'includes/config.php';

// checking the login
if (!isset($_GET['allow'])) {
    $logged = check_login();
    if (!$logged['status']) {
        $_SESSION['message'] = ['type' => 'error', 'data' => $logged['data']];
        go (URL.'/');
    }
    $logged = $logged['data'];
}

if (isset($_GET['s']) && !empty($_GET['s']) && is_numeric($_GET['s'])) {
    $sol_id = normal_text($_GET['s']);
} else {
    $_SESSION['message'] = ['type' => 'error', 'data' => 'Solicitations parameter is incorrect.'];
    go(URL.'/account.php');
}

$sol = get_sol_by_id($sol_id);
if (!$sol['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $sol['data']];
    go(URL.'/account.php');
}
$sol = $sol['data'];

// getting requirements
$reqs = get_requirements_by_sol_id($sol_id);
if (!$reqs['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $reqs['data']];
    go(URL.'/account.php');
}
$reqs = $reqs['data'];

// due date days 
$due_date = new DateTime($sol['solicitation_due_date']);
$current_date = new DateTime(date('Y/m/d'));

$days_due = (int)$current_date->diff($due_date)->format("%r%a");

foreach ($reqs as $i => $req) {
    $gap = $req['requirement_gap'] == '1' ? 'yes' : 'no';
    $ability = (int)$sol['solicitation_ability'];
    $reqs[$i]['calculated_gap'] = calculate_gap ($gap, $ability);
    $reqs[$i]['calculated_risk_rating'] = calculate_risk_rating ($req['requirement_risks_type'], $req['requirement_impacts_type']);
}

if (isset($_POST) && !empty($_POST)) {
    
    // checking for the cc emails
    if (isset($_POST['cc']) && is_array($_POST['cc']) && !empty($_POST['cc'])) {

        $ccs = $_POST['cc'];
        $unique_cc = [];
        foreach ($ccs as $i => $cc) {
            $cc = normal_text($cc);
            if (!empty($cc)) {
                // checking for valid syntax
                if (!filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "CC email (".($i+1).") incorrect format";
                } else if (in_array($cc, $unique_cc)) {
                    $errors[] = "CC email (".($i+1).") is repeated twice";
                } else if ($cc == $logged['user_email']) {
                    $errors[] = "You cannot add your own email to CC.";
                } else {
                    array_push($unique_cc, $cc);
                }
            }
        }

        if (empty($errors)) {
            if (count($unique_cc) > MAXIMUM_CC_FIELDS) {
                $errors[] = "You can add maximum of ".MAXIMUM_CC_FIELDS. " CC emails";
            } else {

                // generating pdf 
                
                // sending email then adding record to the database
                


            }
        }

    }

}

if (isset($_GET['allow'])) {
    require_once DIR.'views/gapoutput.pdf.view.php';
} else {
    require_once DIR.'views/gapoutput.view.php';
}
