<?php

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



require_once DIR.'views/gapoutput.view.php';
