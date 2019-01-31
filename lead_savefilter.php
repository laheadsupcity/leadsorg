<?php
require_once('config.php');
$num_units_min=isset($_POST['num_units_min']) ? $_POST['num_units_min'] : '';
$num_units_max=isset($_POST['num_units_max']) ? $_POST['num_units_max'] : '';
$fmlytype=isset($_POST['fmlytype']) ? $_POST['fmlytype'] : '';
$sfmlytype=isset($_POST['sfmlytype']) ? $_POST['sfmlytype'] : '';
$zip=isset($_POST['zip_codes']) ? $_POST['zip_codes'] : '';
$cities=isset($_POST['cities']) ? $_POST['cities'] : '';
$zoning_to=isset($_POST['zoning']) ? $_POST['zoning'] : '';
$tax_exemption_codes=isset($_POST['tax_exemption_codes']) ? $_POST['tax_exemption_codes'] : '';
$cdate=isset($_POST['cdate']) ? $_POST['cdate'] : '';
$ctime=isset($_POST['ctime']) ? $_POST['ctime'] : '';
$num_bedrooms_min=isset($_POST['num_bedrooms_min']) ? $_POST['num_bedrooms_min'] : '';
$num_bedrooms_max=isset($_POST['num_bedrooms_max']) ? $_POST['num_bedrooms_max'] : '';
$num_baths_min=isset($_POST['num_baths_min']) ? $_POST['num_baths_min'] : '';
$num_baths_max=isset($_POST['num_baths_max']) ? $_POST['num_baths_max'] : '';
$num_stories_min=isset($_POST['num_stories_min']) ? $_POST['num_stories_min'] : '';
$num_stories_max=isset($_POST['num_stories_max']) ? $_POST['num_stories_max'] : '';
$cost_per_sq_ft_min=isset($_POST['cost_per_sq_ft_min']) ? $_POST['cost_per_sq_ft_min'] : '';
$cost_per_sq_ft_max=isset($_POST['cost_per_sq_ft_max']) ? $_POST['cost_per_sq_ft_max'] : '';
$lot_area_sq_ft_min=isset($_POST['lot_area_sq_ft_min']) ? $_POST['lot_area_sq_ft_min'] : '';
$lot_area_sq_ft_max=isset($_POST['lot_area_sq_ft_max']) ? $_POST['lot_area_sq_ft_max'] : '';
$sales_price_min=isset($_POST['sales_price_min']) ? $_POST['sales_price_min'] : '';
$sales_price_max=isset($_POST['sales_price_max']) ? $_POST['sales_price_max'] : '';
$year_built_min=isset($_POST['year_built_min']) ? $_POST['year_built_min'] : '';
$year_built_max=isset($_POST['year_built_max']) ? $_POST['year_built_max'] : '';
$sales_date_from=isset($_POST['sales_date_from']) ? $_POST['sales_date_from'] : '';
$sales_date_to=isset($_POST['sales_date_to']) ? $_POST['sales_date_to'] : '';
$is_owner_occupied=isset($_POST['is_owner_occupied']) ? $_POST['is_owner_occupied'] : '';
$filter=isset($_POST['filtername']) ? $_POST['filtername'] : '';
$fcount=gettotalcount($filter);
$searchid=getsearchid($filter);



$arr_data=array('num_units_min'=>$num_units_min,'num_units_max'=>$num_units_max,'zip'=>$zip,'city'=>$cities,'zoning'=>$zoning_to,'exemption'=>$tax_exemption_codes,'casetype'=>$casetypeto,
'num_bedrooms_min'=>$num_bedrooms_min,'num_bedrooms_max'=>$num_bedrooms_max,'num_baths_min'=>$num_baths_min,'num_baths_max'=>$num_baths_max,'num_stories_min'=>$num_stories_min,'num_stories_max'=>$num_stories_max,'cost_per_sq_ft_min'=>$cost_per_sq_ft_min,'cost_per_sq_ft_max'=>$cost_per_sq_ft_max,'lot_area_sq_ft_min'=>$lot_area_sq_ft_min,
'lot_area_sq_ft_max'=>$lot_area_sq_ft_max,'sales_price_min'=>$sales_price_min,'is_owner_occupied'=>$is_owner_occupied,'searchid'=>$searchid,'sales_price_max'=>$sales_price_max,'year_built_min'=>$year_built_min,'year_built_max'=>$year_built_max,'sales_date_from'=>$sales_date_from,'sales_date_to'=>$sales_date_to,'fmlytype'=>$fmlytype,'sfmlytype'=>$sfmlytype,'cdate'=>$cdate,'ctime'=>$ctime);
$data=serialize($arr_data);

if($fcount==0 && $searchid=='' ){
    $db = Database::instance();
    $db->insert(
        'custom_search',
        array(
            'name' => $filter,
            'data' => $data

        )
    );
    $response=array('status'=>'sucess');

}
else if($fcount>0){
    $db = Database::instance();

    $db->update(
        'custom_search',
        array( // fields to be updated
            'data' => $data
        ),
        array( // 'WHERE' clause
            'id' => $searchid
        )
    );
    error_log("============data============>".print_r($db,true));
    $response=array('status'=>'update');

}
else {
    $response=array('status'=>'failed');


}



echo json_encode($response);




exit();

function gettotalcount($name){
    $db = Database::instance();
    $count=$db->select('custom_search', array('name' => $name))->count();
    return $count;
}

function getsearchid($name){
    $db = Database::instance();
    $id="";
    $db->select('custom_search', array('name' => $name),false, false,'','id');
    $result=$db->result_array();
    foreach($result as $key=>$val){

     $id=$val['id'];
    }
    return $id;

}
?>
