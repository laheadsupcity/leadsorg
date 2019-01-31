<?php require_once('config.php');
include('datafunction.php');
$db = Database::instance();
$current=date('Y-m-d H:i:s');
$stopselectbatch=isset($_POST['stopselectbatch']) ? $_POST['stopselectbatch'] : '';
//error_log("==============batchid======================>".print_r($stopselectbatch,true));
$countbatch=count($stopselectbatch);
if($countbatch>0){

	for($x =0; $x <=$countbatch; $x++){
		$batchid=$stopselectbatch[$x];
		

		$db->update( 'custom_scheduled_lead_task',[ 'status' => 'Inactive' ], ['status' => 'Active','batchid' => $batchid]);


	}

	
}
//$db->update( 'custom_scheduled_lead_task',[ 'status' => 'Inactive' ], ['status' => 'Active' ]);
/*$query="delete from custom_scheduled_lead_task where status='Active'";
$db->query($query);*/
$value = array('msg' => 'delete');
echo json_encode($value);
exit();
?>
