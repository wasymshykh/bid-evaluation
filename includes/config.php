<?php

    session_start();

    // Main project directory
    define('DIR', dirname(__DIR__).'/');

    // Either: development/production
    define('PROJECT_MODE', 'development'); 

    if (PROJECT_MODE !== 'development') {
        error_reporting(0);
    }

    // with ending slash '/'
    define ('URL', 'http://localhost/bid-evaluation');

    // Database details
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'bid_evaluation');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    // Mailer settings
        // server settings
    define('SMTP_HOST', 'smtp.mailtrap.io');
    define('SMTP_AUTH', true); // smtp server requires authentication? true or false
    define('SMTP_USERNAME', '512427b219e68e');
    define('SMTP_PASSWORD', '423d8d30747d4f');
    define('SMTP_ENCRYPTION', 'tls'); // either tls or smtps
    define('SMTP_PORT', 587); // default is 468
        // mail settings
    define('MAIL_FROM', 'mail@yourwebsite.com');
    define('MAIL_FROM_NAME', 'Mailer');

    // Timezone setting
    define('TIMEZONE', 'Europe/Berlin');
    date_default_timezone_set(TIMEZONE);

    // Functions
    require_once DIR . 'includes/functions.php';

    $dsn = 'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME;
    try {
        $db = new PDO($dsn, DB_USER, DB_PASS);
    } catch(Exception $e) {
        if (PROJECT_MODE === 'development') {
            die('E.01: Failure. '.$e->getMessage());
        } else {
            die('E.01: Failure');
        }
        return false;
    }
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $errors = [];
    $success = "";
    // checking for session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        if ($_SESSION['message']['type'] === 'success') {
            $success = $_SESSION['message']['data'];
        } else if ($_SESSION['message']['type'] === 'error') {
            $errors[] = $_SESSION['message']['data'];
        }
        unset($_SESSION['message']);
    }
