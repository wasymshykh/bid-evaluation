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

    
    // Timezone setting
    define('TIMEZONE', 'Europe/Berlin');
    date_default_timezone_set(TIMEZONE);
    
    // Functions
    require_once DIR . 'includes/functions.php';

    // reading the data.json file
    if (isset($read_json) && $read_json) {
       define ('DATA_FILE_PATH', DIR.'data.json');
       $settings = read_json_data(DATA_FILE_PATH);
   
       if (isset($activate_mailer) && $activate_mailer) {
           // Mailer settings
               // server settings
           define('SMTP_HOST', $settings->email->smtp->server);
           define('SMTP_AUTH', $settings->email->smtp->auth); // smtp server requires authentication? true or false
           define('SMTP_USERNAME', $settings->email->smtp->username);
           define('SMTP_PASSWORD', $settings->email->smtp->password);
           define('SMTP_ENCRYPTION', $settings->email->smtp->connection_type); // either tls or smtps
           define('SMTP_PORT', $settings->email->smtp->port); // default is 468
               // mail settings
           define('MAIL_FROM', $settings->email->sender_address);
           define('MAIL_FROM_NAME', $settings->email->sender_name);
        }
    }

    // cc fields are shown in output page
    define('MAXIMUM_CC_FIELDS', 4);

    $dsn = 'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME;
    try {
        $db = new PDO($dsn, DB_USER, DB_PASS);
    } catch(Exception $e) {
        if (PROJECT_MODE === 'development') {
            die('E.01: Failure. '.$e->getMessage());
        } else {
            die('E.01: Failure');
        }
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

   