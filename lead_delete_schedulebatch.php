<?php
require_once('config.php');
$id=isset($_POST['id']) ? $_POST['id'] : '';

$db->delete(
	'scheduled_batch',
	array(
		'id' => $id,
	)
);

$value = array('msg' => 'Add');
echo json_encode($value);


exit();
?>