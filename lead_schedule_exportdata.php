<?php
require_once('config.php');
$check=isset($_POST['check']) ? $_POST['check'] : '';
if(!empty($check)){
$array=explode(",",$check);

$filename = "lead_csv.csv";
$fp = fopen('php://output', 'w');
$table=array();
$objmerged=array();

foreach($array as $key=>$val){

 $data=getexportdatabyid($val);

 $objmerged[] =  array_merge((array) $data, (array) $table);


}


foreach ($objmerged as $k=>$v){
    $header[] = array_keys($objmerged[0]);

}

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header[0]);

foreach ($objmerged as $k=>$v){
    fputcsv($fp, $v);
}

exit;
}


function getexportdatabyid($id){
    $db = Database::instance();

    $db->select('property', array('parcel_number'=>$id), false, false,'','*');

    $result=$db->result_array();

	 error_log("=========export===========".print_r($result, true));
    return $result[0];
}
?>
