<?php

require_once 'includes/config.php';

// checking the login
$logged = check_login();
if (!$logged['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $logged['data']];
    go (URL.'/');
}
$logged = $logged['data'];


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

if (($sol['solicitation_user_id'] != $logged['user_id']) && $logged['user_isAdmin'] != '1') {
    $_SESSION['message'] = ['type'=>'error', 'data' =>"You don't have permission to access that solicitation"];
    go(URL.'/account.php');
}

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=solicitation-$sol_id.pdf");

echo $sol['solicitation_blob'];
