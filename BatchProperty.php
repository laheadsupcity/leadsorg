<?php
 require_once('config.php');
 $limit=getrunningcount();
 $count=getsitestatus();
 if($limit <= MAX_INSTANCE && $count>=1){
    $command = escapeshellcmd("sudo python ".PYTHON_PATH."BatchPropertyCron.py" );
    $command_output = shell_exec($command);
    $array= json_decode($command_output);
 }
 function getrunningcount(){
    $db = Database::instance();
    $count=$db->select('custom_scheduled_lead_task', ['status' =>'Running'])->count();
    return $count;
 }
  
function getsitestatus(){
    $db = Database::instance();
    $count=$db->select('scrapper_setting', array('site_status'=>1), false, false,'','*')->count();
    return $count;
}

?>
