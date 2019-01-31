<?php
 require_once('config.php');
 $command = escapeshellcmd("sudo python ".PYTHON_PATH."ScheduledSearchCron.py" );
 $command_output = shell_exec($command);
 $array= json_decode($command_output);
 error_log("=============schcron==============>".print_r($command_output,true));

?>
