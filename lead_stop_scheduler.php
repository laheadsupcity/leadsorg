<?php
require_once('config.php');
$db = Database::instance();
$batchid=isset($_POST['batchid']) ? $_POST['batchid'] : '';
$taskname=isset($_POST['taskname']) ? $_POST['taskname'] : '';
$sttask=isset($_POST['sttask']) ? $_POST['sttask'] : '';
$stoptask=isset($_POST['stoptask']) ? $_POST['stoptask'] : '';
$taskinterval=isset($_POST['taskinterval']) ? $_POST['taskinterval'] : '';

$db->delete(
	'scheduled_lead_task',
	array( // 'WHERE' clause
		'batchid' => $batchid,
	)
);


$db->delete(
	'lead_scheduler',
	array( // 'WHERE' clause
		'batchid' => $batchid,
	)
);

$value = array('msg' => 'delete');
echo json_encode($value);

exit();
?>