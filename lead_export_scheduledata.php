<?php
require_once('config.php');
$expid=isset($_POST['id']) ? $_POST['id'] : '';
function getapndetail($apn){
	$db = Database::instance();
        $db->select('property', array('parcel_number'=>$apn),'' , 'id DESC','','id,parcel_number,owner_name2,owner1_first_name,owner1_middle_name,owner1_last_name,owner1_spouse_first_name,owner2_first_name,owner2_middle_name,owner2_last_name,owner2_spouse_first_name,site_address_street_prefix,street_number,street_name,site_address_zip,site_address_city_state,full_mail_address,mail_address_city_state,mail_address_zip,site_address_unit_number,use_code,use_code_descrition,building_area,bedrooms,rooms,bathrooms,tract,lot_area_sqft,lot_area_acres,year_built,pool,garage_type,sales_date,sales_price,sales_price_code,sales_document_number,tax_exemption_code,fireplace,number_of_units,number_of_stories,owner_occupied,zoning,mail_flag,cost_per_sq_ft,total_assessed_value,total_market_value,assessed_improvement_value,assessed_land_value,assessed_improve_percent');

	$record=$db->result_array();
	return $record[0];
	
}
$list=array();
$records=array();
$filename = "scheduledsearch.csv";
$fp = fopen('php://output', 'w');
if(!empty($expid)){
    $record=getscheduledetailid($expid);
    
  

$count=count($record['group_apn']);

    if($count>0){
    $records=$record['group_apn'];
 
    }
   
    

}
foreach($records as $key=>$val){
    //
    $list[]=getapndetail($val);
}

foreach ($list as $k=>$v){
    $header[] = array_keys($list[0]);

}
//error_log("===========header=============>".print_r($header,true));

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header[0]);

foreach ($list as $k=>$vp){
    

    fputcsv($fp, $vp);
}

exit;

function getscheduledetailid($vewid){
        $db = Database::instance();
        $db->select('scheduled_search', array('id'=>$vewid),'' , 'id DESC','*');
        $record=$db->result_array();
    
        return $record[0];
        
    }

?>
