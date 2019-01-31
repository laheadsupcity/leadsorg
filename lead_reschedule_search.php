<?php
require_once('config.php');
$id=isset($_POST['id']) ? $_POST['id'] : '';
$data=getscheduledetailid($id);
if(!empty($data)) {

    $arr=array("status"=>"success","result"=>$data['data'] );

}
else {

    $arr=array("status"=>"failed","result"=>array() );
}

echo json_encode($arr);
function getscheduledetailid($vewid){
    $db = Database::instance();
    $db->select('scheduled_search', array('id'=>$vewid),'' , 'id DESC','*');
    $record=$db->result_array();

    return $record[0];
    
}

exit();
?>