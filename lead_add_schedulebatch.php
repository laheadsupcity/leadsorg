<?php
require_once('config.php');
$check=isset($_POST['check']) ? $_POST['check'] : '';
$name=isset($_POST['name']) ? $_POST['name'] : '';

$db->select('scheduled_batch', array('batchname' => $name), false, false,'','*');
$result=$db->result_array();
$count= count($result);



//error_log("======batchcount=====>".print_r($count,true));

if($count==0){
if(!empty($check)){
$db->insert(
	'scheduled_batch',
	array(
		'batchname' => $name,
		'group_apn' => serialize($check)
	)
);

$value = array('msg' => 'Add');
echo json_encode($value);


}

}

else{
$value = array('msg' => 'alredyadd');
echo json_encode($value);

}
exit();
?>