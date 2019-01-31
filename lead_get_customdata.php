<?php
require_once('config.php');
$name=isset($_POST['name']) ? $_POST['name'] : '';
$searchdata=getrecordbyname($name);
error_log("=======searchdata=========>".print_r($searchdata,true));
echo json_encode($searchdata);
exit();


function getrecordbyname($sname){
    $name=array();
    $db = Database::instance();
    $db->select('custom_search', array('name' => $sname), false, false,'','data');
    $result=$db->result_array();
    foreach($result as $key=>$val){
    
     $name=$val['data'];
    }
    
    return $name;
}


?>