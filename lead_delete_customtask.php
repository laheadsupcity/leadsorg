<?php
require_once('config.php');
$db = Database::instance();
$batchid=isset($_POST['batchid']) ? $_POST['batchid'] : '';
$taskname=isset($_POST['taskname']) ? $_POST['taskname'] : '';
$sttask=isset($_POST['sttask']) ? $_POST['sttask'] : '';
$stoptask=isset($_POST['stoptask']) ? $_POST['stoptask'] : '';
$taskinterval=isset($_POST['taskinterval']) ? $_POST['taskinterval'] : '';

/*$db->delete(
	'custom_scheduled_lead_task',
	array( // 'WHERE' clause
		'batchid' => $batchid,
		'status' => 'Active'
	)
);


$db->delete(
	'custom_lead_scheduler',
	array( // 'WHERE' clause
		'batchid' => $batchid,
	)
);*/
$db->update( 'custom_scheduled_lead_task',[ 'status' => 'Inactive' ], ['status' => 'Active','batchid' => $batchid]);

$value = array('msg' => 'delete');
echo json_encode($value);

exit();
?>
