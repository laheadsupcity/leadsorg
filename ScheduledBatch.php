<?php
 require_once('config.php');
 $command = escapeshellcmd("sudo python ".PYTHON_PATH."ScheduledBatchSearchCron.py" );
 $command_output = shell_exec($command);
 $array= json_decode($command_output);
?>
