<?php
require_once('config.php');
$batchid=isset($_POST['batchid']) ? $_POST['batchid'] : '';
$taskname=isset($_POST['taskname']) ? $_POST['taskname'] : '';
$sttask=isset($_POST['sttask']) ? $_POST['sttask'] : '';
$stoptask=isset($_POST['stoptask']) ? $_POST['stoptask'] : '';
$taskinterval=isset($_POST['taskinterval']) ? $_POST['taskinterval'] : '';
$db->select('custom_scheduled_lead_task', array('batchid' => $batchid,'status'=>'Active'), false, false,'AND','date_format(`starttask`, "%m/%d/%Y %h:%i %p") as date, taskname,period');
$result=$db->result_array();
//error_log("===========leadtaskcount=============".print_r($result, true));
$count= count($result);
if($count==0){
	$value = array('msg' => 'Add');
	echo json_encode($value);
}
else{ 
	$value = array('msg' => 'alredyadd', 'result'=>$result[0]);
	echo json_encode($value); 
}
exit();
?>


