<?php
require_once('config.php');
$runningcount=getrunningcount();
echo $norow=$runningcount['count'];
if($norow ==0){ 
    $db = Database::instance();
    $getsch=$db->getschedulealllist();
    $result = $db->result_array();
    foreach ($result as $row) {
        $batchid = $row["batchid"];
        $scheduleat = $row["scheduleat"];
        $scheduleId = $row["schedule_id"];
        updateStatusRunning($scheduleId, "running");    
        $resultSchedule = getleadsch( $batchid );
        if (!$resultSchedule)
        continue;
        foreach($resultSchedule as $key=>$schRow){
            $period=$schRow['period'];
            $Sbatchid=$schRow['batchid'];            
            $id= getscheduledetailid($Sbatchid);
            $apnlist=explode(',',$id['group_apn']);
            foreach($apnlist as $k=>$v){
                $address= updateschedulestatus($v);
                $dataencode=json_decode($address);
                if($dataencode->status==1){                    
                    $newaddress= $dataencode->propertyinfo->Address;
                    updateaddress($v,$newaddress);
                }
            }            
            setScheduleNext($scheduleId,$scheduleat, $period);
        }
    }
}
function getrunningcount(){
    $db = Database::instance();
    $db->getschedulearunlist();
    $count=$db->result_array();
    return $count[0];
}
function updateaddress($apn,$address){
    $db = Database::instance();
    $db->update(
        'property',
        array( // fields to be updated
            'full_mail_address' =>$address
        ),
        array( // 'WHERE' clause
            'parcel_number' => $apn
            )
        );   
    }
    function updateschedulestatus($id){
        $a=0;
        $b=0;
        $c=$id;
        $command = escapeshellcmd("sudo python /var/www/html/leads/python/search.py $a $b $c" );
        $command_output = shell_exec($command);        
        return $command_output;  
    }    
    function setScheduleNext($schid,$scheduleat, $frequency){
        $detail=getscheduledetail($schid); 
        $scheduleAt = new DateTime($scheduleat);
        $extendHours = 0;
        if ($frequency == 'weekly') {
            $extendHours = 7*24;            
        }else if($frequency == 'monthly'){
            $extendHours = 30*24;
            
        }else if($frequency == 'yearly'){
            $extendHours = 365*24;            
        }
        else if($frequency == 'yearly'){
            $extendHours = 24;
        }
        $nextScheduleDate = $scheduleAt->modify("+".$extendHours." hours");
        $nextScheduleAt = $nextScheduleDate->format('Y-m-d H:i:s');
        updatenextschedule($schid,$nextScheduleAt);        
    }
    function getscheduledetailid($id){
        $db = Database::instance();
        $db->select('batch', array('id'=>$id),'' , 'id DESC','*');
        $record=$db->result_array();
        return $record[0];
    }
    function updatenextschedule($schid,$scheduleat){
        $db = Database::instance();
        $db->update(
            'custom_lead_scheduler',
            array( // fields to be updated
                'status' => 'pending',
                'scheduleat'=>$scheduleat
            ),
            array( // 'WHERE' clause
                'schedule_id' => $schid
                )
            );
        }
        function updateStatusRunning($schid, $status){
            $db = Database::instance();
            $db->update(
                'custom_lead_scheduler',
                array( // fields to be updated
                    'status' => $status
                ),
                array( // 'WHERE' clause
                    'schedule_id' => $schid
                    )
                );                
            }
            function getschlist($curr){
                $db = Database::instance();
                $db->select('custom_lead_scheduler',array('status'=>'pending','scheduleat'=>$curr), false, false,"AND","*");
                $result=$db->result_array();
                return $result;                
            }
            function getleadsch($id){
                $db = Database::instance();
                $db->select('custom_scheduled_lead_task',array('batchid'=>$id), false, false,'',"*");
                $result=$db->result_array();
                return $result;              
            }
            function getscheduledetail($id){
                $db = Database::instance();
                $db->select('custom_lead_scheduler',array('schedule_id'=>$id), false, false,'',"*");
                $result=$db->result_array();
                return $result[0];                
            }
            ?>
            