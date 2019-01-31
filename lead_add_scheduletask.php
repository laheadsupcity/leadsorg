<?php
require_once('config.php');
error_log("======amittest=====>".print_r($_POST,true));
$db = Database::instance();
$batchid=isset($_POST['batchid']) ? $_POST['batchid'] : '';
$taskname=isset($_POST['taskname']) ? $_POST['taskname'] : '';
$sttask=isset($_POST['sttask']) ? $_POST['sttask'] : '';
$stoptask=isset($_POST['stoptask']) ? $_POST['stoptask'] : '';
$taskinterval=isset($_POST['taskinterval']) ? $_POST['taskinterval'] : '';

$db->select('scheduled_lead_task', array('batchid' => $batchid), false, false,'','*');
$result=$db->result_array();
$count= count($result);


if($count==0){

$db->insert(
	'scheduled_lead_task',
	array(
		'batchid' => $batchid,
		'taskname' => $taskname,
		'starttask' => date("Y-m-d H:i", strtotime($sttask)),
		//'endtask' => $stoptask,
		'period' => $taskinterval,
		'status' => 'Active'
	)
);


$db->insert(
	'lead_scheduler',
	array(
		'batchid' => $batchid,
		'scheduleat' => date("Y-m-d H:i", strtotime($sttask))
		
	)
);

$value = array('msg' => 'Add');
echo json_encode($value);

}

else{
$value = array('msg' => 'alredyadd');
echo json_encode($value);

$db->update(
	'scheduled_lead_task',
	array(
		'taskname' => $taskname,
		'starttask' => date("Y-m-d H:i", strtotime($sttask)),
		'period' => $taskinterval,
		'status' => 'Active'
	),

	array( 
		'batchid' => $batchid,
	 )

);


$db->update(
	'lead_scheduler',
	array(
		'scheduleat' => date("Y-m-d H:i", strtotime($sttask))
	),

	array( 
		'batchid' => $batchid,
	 )

);


}
exit();
?>
