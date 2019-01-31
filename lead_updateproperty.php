<?php
require_once('config.php');
//echo json_encode($_POST);
//error_log("------post----------------->".print_r($_POST,true));
$pid= isset($_POST['pid']) ? $_POST['pid'] : '';
$array=array("parcel_number"=>$_POST['apn'],"owner_name2"=>$_POST['ownername'],"owner1_first_name"=>$_POST['ofname'],"owner1_last_name"=>$_POST['olname'],"owner1_spouse_first_name"=>$_POST['osname'],"street_number"=>$_POST['streetnumber'],"street_name"=>$_POST['strname'],"site_address_zip"=>$_POST['sazip'],"mail_address_zip"=>$_POST['mazip'],"full_mail_address"=>$_POST['fmaddress'],"mail_address_city_state"=>$_POST['maddcs'],"use_code"=>$_POST['uscode'],"use_code_descrition"=>$_POST['uscodedesc'],"building_area"=>$_POST['buldarea'],"bedrooms"=>$_POST['bedroom'],"bathrooms"=>$_POST['bathrooms'],"tract"=>$_POST['tract'],"lot_area_sqft"=>$_POST['lasqft'],"year_built"=>$_POST['yearbuilt'],"pool"=>$_POST['pool'],"garage_type"=>$_POST['garage_type'],"sales_date"=>$_POST['sales_date'],"number_of_units"=>$_POST['nou'],"number_of_stories"=>$_POST['nos'],"zoning"=>$_POST['zoning'],"sales_price"=>$_POST['salprice'],"cost_per_sq_ft"=>$_POST['cpsqft'],"total_assessed_value"=>$_POST['totalav'],"sales_price_code"=>$_POST['spcode'],"tax_exemption_code"=>$_POST['texcode'],"fireplace"=>$_POST['fireplace'],"owner_occupied"=>$_POST['ownerocupied'],"total_market_value"=>$_POST['tmv'],"assessed_improvement_value"=>$_POST['aiv'],"assessed_land_value"=>$_POST['alv']);
$db->update(
	'property',
	$array,
	array( // 'WHERE' clause
		'id' => $pid
	)
);
$val=array("msg"=>"success");
echo json_encode($val)
?>