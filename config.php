<?php
    require_once('Database.php');
    $database_name='lead_housing';
    $username='root';
    $password='Temp@12345';
    $host='localhost';
    $db = new Database($database_name, $username, $password, $host);
    define('PYTHON_PATH', "/home/livepython/");
    define('PROCESS_KILL',6);
    define('MAX_INSTANCE',3);
    date_default_timezone_set("America/Los_Angeles");
?>
