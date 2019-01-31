<?php
require_once('config.php');
$id=isset($_POST['id']) ? $_POST['id'] : '';

$db->delete(
	'batch',
	array(
		'id' => $id,
	)
);

$db->delete(
	'custom_scheduled_lead_task',
	array( // 'WHERE' clause
		'batchid' => $id,
	)
);


$db->delete(
	'custom_lead_scheduler',
	array( // 'WHERE' clause
		'batchid' => $id,
	)
);

$value = array('msg' => 'Add');
echo json_encode($value);
exit();
?>