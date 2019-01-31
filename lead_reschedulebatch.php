<?php
require_once('config.php');
$id=$_POST['id'];
$db->insert(
	'rescheduled_batch',
	array(
		'sbatchid' => $id,
		'status' => 1
	)
);
$val=array('msg'=>'success');
echo json_encode($val);
exit();
?>