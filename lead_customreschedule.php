<?php
require_once('config.php');
$getsch=getschlist();
if(count($getsch)>0){
	foreach($getsch as $key=>$val){
		$id=getscheduledetailid($val['sbatchid']);
		$apnlist=explode(',',$id['group_apn']);
		foreach($apnlist as $k=>$v){
			$address= updateschedulestatus($v);
			$dataencode=json_decode($address);
				if($dataencode->status==1){ 
					$newaddress= $dataencode->propertyinfo->Address;
					updateaddress($v,$newaddress);
					updaterestatus($val['id']);
				} 
		} 
	}
}

function updaterestatus($id){
    $db = Database::instance();
    $db->update(
        'custom_scheduled_batch',
        array( // fields to be updated
            'status' =>2
        ),
        array( // 'WHERE' clause
            'id' => $id
        )
    );
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
function getschlist(){
    $db = Database::instance();
    $db->select('custom_scheduled_batch',array('status'=>1), false, false,'ORDER BY id DESC',"*");
    $result=$db->result_array();
    return $result;
}
function updateschedulestatus($id){

	$a=0;
	$b=0;
	$c=$id;
	$command = escapeshellcmd("sudo python /var/www/web/Final/python/search.py $a $b $c" );
	$command_output = shell_exec($command);
	return $command_output;
}

function getscheduledetailid($id){
    $db = Database::instance();
    $db->select('batch', array('id'=>$id),'' , 'id DESC','*');
    $record=$db->result_array();
    return $record[0];   
}
?>