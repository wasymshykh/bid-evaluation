<?php

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$read_json = true;
$activate_mailer = true;

require_once 'includes/config.php';
require_once 'vendor/autoload.php';

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

$c_gaps = [];
$c_risks = [];

$ability = (int)$sol['solicitation_ability'];
foreach ($reqs as $i => $req) {
    $gap = $req['requirement_gap'] == '1' ? 'yes' : 'no';
    $reqs[$i]['calculated_gap'] = calculate_gap ($gap, $ability);
    $reqs[$i]['calculated_risk_rating'] = calculate_risk_rating ($req['requirement_risks_type'], $req['requirement_impacts_type']);

    array_push($c_gaps, $reqs[$i]['calculated_gap'][0]);
    array_push($c_risks, $reqs[$i]['calculated_risk_rating'][0]);
}

$c_gaps = array_filter($c_gaps);
$c_gaps_average = 0;
if(count($c_gaps)) {
    $c_gaps_average = round(array_sum($c_gaps)/count($c_gaps));
}
$c_risks = array_filter($c_risks);
$c_risks_average = 0;
if(count($c_risks)) {
    $c_risks_average = round(array_sum($c_risks)/count($c_risks));
}
$pwin = calculate_pwin($c_gaps_average, $c_risks_average, $ability);
$pwin_table_index = get_pwin_table_index($pwin);

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

                $db->beginTransaction();

                try {

                    // generating pdf 
                        // getting contents
                    ob_start();
                    include DIR.'views/gapoutput.pdf.view.php';
                    $response = ob_get_contents();
                    ob_end_clean();

                    $pdf_name = "solicitation-$sol_id.pdf";
                    
                    // setting content to generate pdf
                    try {
                        $html2pdf = new Html2Pdf('P', 'A4', 'en');
                        $html2pdf->setDefaultFont('Arial');
                        $html2pdf->writeHTML($response);
                        $pdf_content = $html2pdf->output($pdf_name, 'S');
                    } catch (Html2PdfException $e) {
                        $html2pdf->clean();
                        $formatter = new ExceptionFormatter($e);
                        throw new Exception("Error: 01 - Unable to send.");
                    }

                    // adding record to the database 
                    $q = "UPDATE `solicitations` SET `solicitation_mailed` = '1', `solicitation_blob` = :b, `solicitation_calculated_pwin` = :p, `solicitation_generated` = :g, `solicitation_cc` = :cc WHERE `solicitation_id` = :i";
                    $st = $db->prepare($q);
                    $st->bindParam(":b", $pdf_content);
                    $st->bindParam(":p", $pwin);
                    $dt = current_date();
                    $st->bindParam(":g", $dt);
                    $f_cc = implode("|", $unique_cc);
                    $st->bindParam(":cc", $f_cc);
                    $st->bindParam(":i", $sol_id);
                    if (!$st->execute()) {
                        throw new Exception("Unable to update record.");
                    }
                    // adding requirements update
                    foreach ($reqs as $req) {
                        $q = "UPDATE `requirements` SET `requirement_calculated_gap` = :g, `requirement_calculated_risk_rating` = :r WHERE `requirement_id` = :i";
                        $st = $db->prepare($q);
                        $st->bindParam(":i", $req['requirement_id']);
                        $g = json_encode($req['calculated_gap']);
                        $st->bindParam(":g", $g);
                        $r = json_encode($req['calculated_risk_rating']);
                        $st->bindParam(":r", $r);
                        if (!$st->execute()) {
                            throw new Exception("Unable to update record.");
                        }
                    }

                    // sending email
                    $mail = new PHPMailer(true);
                    $r = mail_sender ($mail, $settings->email->subject, $settings->email->body, $logged['user_email'], $logged['user_fullname'], $unique_cc, $pdf_content, $pdf_name);
                    if ($r['status']) {
                        $db->commit();
                        go(URL.'/success.php');
                    } else {
                        throw new Exception("Unable to send email.");
                    }
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $errors[] = "Error: 02 - Unable to send.";
                }
                
            }
        }

    }

}

if (isset($_GET['allow'])) {
    require_once DIR.'views/gapoutput.pdf.view.php';
} else {
    require_once DIR.'views/gapoutput.view.php';
}
