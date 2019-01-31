<?php
require_once('config.php');
$batchid=isset($_REQUEST['batchid']) ? $_REQUEST['batchid'] : '';
if($batchid){
    $command = escapeshellcmd("sudo python ".PYTHON_PATH."LeadScrapAudit.py $batchid");
    $command_output = shell_exec($command);
    $response=array('status'=>'success');

}else {
    $response=array('status'=>'error');

}
echo json_encode($response);
exit();

?>
