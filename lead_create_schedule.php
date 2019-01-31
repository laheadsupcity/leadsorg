<?php
require_once('config.php');
include('datafunction.php');
$db = Database::instance();
$startdate=isset($_POST['allstask']) ? $_POST['allstask'] : '';
$interval=isset($_POST['alltaskinterval']) ? $_POST['alltaskinterval'] : '';
$selectbatch=isset($_POST['selectbatch']) ? $_POST['selectbatch'] : '';
$newdate=date("Y-m-d H:i", strtotime($startdate));
#error_log("==============batchid======================>".print_r($newdate,true));
$minutes_to_add =$interval;
$countbatch=count($selectbatch);
if($countbatch>0){
for($x =0; $x <=$countbatch; $x++){
	$batchid=$selectbatch[$x];
	$taskname=getbatchname($selectbatch[$x]);
	$time = new DateTime($newdate);
    $time->add(new DateInterval('PT' . $x*$minutes_to_add . 'M'));
    $stamp = $time->format('Y-m-d H:i');
    if($batchid){

    	$db->insert(
        'custom_scheduled_lead_task',
        array(
            'batchid' => $batchid,
            'taskname' => $taskname,
            'starttask' => $stamp,
            //'endtask' => $stoptask,
            'period' => 'daily',
            'status' => 'Active'
        )
    );


    }

	}
	$value = array('msg' => 'Add');
	}else {

    $value = array('msg' => 'error');
}
echo json_encode($value);
exit();


?>
