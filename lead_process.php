<?php
require_once('config.php');
$data=gettimeinterval();
$current=date('Y-m-d H:i:s');

if(count($data)>0){
foreach($data as $k=>$val){

   $sttime= $val['schedule_start_time']; 
   $pid=$val['pid'];
   $difference = strtotime($current) - strtotime($sttime);
    $hours = $difference / 3600; // 3600 seconds in an hour
    $minutes = ($hours - floor($hours)) * 60; 
    $final_hours = round($hours,0); 
     if($final_hours >=PROCESS_KILL){
        if($pid >0){
        $command = escapeshellcmd("sudo kill -9 $pid" );
        $command_output = shell_exec($command);
        }
        updatefailedbatch($val['id'],$current);
    }

}

}
 
function updatefailedbatch($id,$current){
    $db = Database::instance();
    $db->update( 'custom_scheduled_lead_task',[ 'status' => 'Failed','schedule_end_time' => $current ], ['id' => $id ]);

}

function gettimeinterval(){
   $db = Database::instance();
$db->select('custom_scheduled_lead_task', array('status' =>'Running'), false, false,'','*');
$result=$db->result_array();
return $result;


}
?>


