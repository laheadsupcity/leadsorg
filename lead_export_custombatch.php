<?php
require_once('config.php');
$vewid=isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$record=getscheduledetailid($vewid);
$filename = "leadexport.csv";
$fp = fopen('php://output', 'w');
function getscheduledetailid($vewid){
    $db = Database::instance();
    $db->select('batch', array('id'=>$vewid),'' , 'id DESC','*');
    $record=$db->result_array();
    return $record[0];
    
}

$listarr=array();
if(isset($record['group_apn'])){
$listarr=explode(',', $record['group_apn']);
}

$db->leadbatchdata($listarr);
$result=$db->result_array();
foreach ($result as $k=>$v){
    $header[] = array_keys($result[0]);

}
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header[0]);
foreach ($result as $k=>$vp){
    

    fputcsv($fp, $vp);
}
exit;
?>