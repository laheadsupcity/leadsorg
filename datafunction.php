<?php
function getziplist()
{
    $zip = array();
    $db = Database::instance();
    $db->select('property', array('impstatus'=>0), false, false, '', 'DISTINCT site_address_zip');
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $zip[] = $val['site_address_zip'];
    }
    return array_filter($zip);
}

function getziplistforschedule()
{
    $zip = array();
    $db = Database::instance();
    //$db->select('property', array(), false, false, '', 'DISTINCT site_address_zip');

	$query = "select DISTINCT site_address_zip from property WHERE impstatus=1";
   
    $db->query($query);

    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $zip[] = $val['site_address_zip'];
    }
    return array_filter($zip);
}


function getpropertydata($id)
{
    $db = Database::instance();
    $db->select('property', array('id' => $id), false, false, '', '*');
    $result = $db->result_array();

    return $result[0];
}



function getcityschlist()
{
    $city = array();
    $db = Database::instance();
    $query = "select DISTINCT site_address_city_state from property WHERE impstatus=1 AND site_address_city_state like ('%CA')";
    $db->query($query);
    $result = $db->result_array();
    foreach ($result as $key => $val) {
        $city[] = $val['site_address_city_state'];
    }
    return array_filter($city);
}

function getcitylist()
{
    $city = array();
    $db = Database::instance();
    $query = "select DISTINCT site_address_city_state from property WHERE impstatus=0 AND site_address_city_state like ('%CA')";
    
    $db->query($query);
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $city[] = $val['site_address_city_state'];
    }
    return array_filter($city);
}

function getscrapper_data(){
    $resarray=array();
    $db = Database::instance();
    $db->select('scrapper_instance', array(), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result[0];
    }

    return $resarray;
    
}

function getscheduled_lead_task($id){
    $resarray=array();
    $db = Database::instance();
    $db->select('custom_scheduled_lead_task', array('batchid' => $id), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result;
    }

    return $resarray;
    
}
function getbatch_count(){

    $db = Database::instance();

    $count=$db->select('batch', array(), false, false,'','*')->count();

    return $count;

}



function getlead_task_count(){

    $db = Database::instance();

    $count=$db->select('custom_scheduled_lead_task', array('status'=>'Active'), false, false,'','*')->count();

    return $count;



}
 function getlead_task_active(){

    $db = Database::instance();

    $db->select('custom_scheduled_lead_task', array('status'=>'Active'), false, false,'','batchid');
    $result=$db->result_array();

    return $result;
 }
 function getbatchname($batchid){

	$db = Database::instance();
    $db->select('batch', array('id'=>$batchid), false, false,'','batchname');
    $result=$db->row();
    return $result->batchname;
	
}
function getbatchlist(){
    $db = Database::instance();
    $query = "select DISTINCT id  from batch";
    $runquery=$db->query($query);
    $result = $runquery->result_array();
    $rarray=array();
    foreach ($result as $key=>$val){
        $rarray[]=$val['id'];
    } 
    return $rarray;
    

}
function getActivebatchlist(){
    $db = Database::instance();
    $query = "select DISTINCT batchid from custom_scheduled_lead_task where status ='Active'";
    $runquery=$db->query($query);
    $result = $runquery->result_array();
    $rarray=array();
    foreach ($result as $key=>$val){
        $rarray[]=$val['batchid'];
    } 
    return $rarray;
}
function getfinalbatchlist(){

    $array1=getbatchlist();
    $array2=getActivebatchlist();
    $result=array_diff($array1,$array2);
    return $result;
}
function get_batchdata(){

    $resarray=array();

    $db = Database::instance();

    $db->select('batch', array(), false, false,'','*');

    $result=$db->result_array();

    if(count($result)>0){

        $resarray=$result;

    }



    return $resarray;

}
function getinstances_data(){
    $resarray=array();
    $db = Database::instance();
    $db->select('instances_process', array(), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result;
    }

    return $resarray;
    
}
function getzoninglist()
{
    $zoning = array();
    $db = Database::instance();
    
    $query = "select DISTINCT zoning from property WHERE impstatus=0 AND site_address_city_state like ('%CA')";
    $db->query($query);
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $zoning[] = $val['zoning'];
    }
    return array_filter($zoning);
}

function getexemptionlist()
{
    $exemption = array();
    $db = Database::instance();
    $db->select('property', array(), false, false, '', 'DISTINCT tax_exemption_code');
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $exemption[] = $val['tax_exemption_code'];
    }
    return array_filter($exemption);

}
function getsearchlist()
{
    $name = array();
    $db = Database::instance();
    $db->select('custom_search', array(), false, false, '', 'name');
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $name[] = $val['name'];
    }
    return $name;

}

function getcasetype(){
$casetype = array();
$db = Database::instance();
$db->select('case_type_master', array(), false, false, '', '*');
$result = $db->result_array();
   foreach ($result as $key => $val) {

       $casetype[$val['id']] = $val['name'];
   }
   return $casetype;

}

function getcasetypelist()
{
    $casetype = array();
    $db = Database::instance();
    $db->select('property_cases', array(), false, false, '', 'DISTINCT case_type');
    $result = $db->result_array();
    foreach ($result as $key => $val) {

        $casetype[] = $val['case_type'];
    }
    return $casetype;

}

    function gethousingcasedetail($a, $b, $c)
    {


        $command = escapeshellcmd("sudo python ".PYTHON_PATH."case.py $a $b $c");
        $command_output = shell_exec($command);
        $array = json_decode($command_output);
         if (isset($array->casetable)) {
            $table = json_decode($array->casetable);
        } else {
            $table = array();
        }
        $objmerged = array_merge((array)$array, (array)$table);
        return $objmerged;

    }


    function gethousingdetail($a, $b, $c)
    {
        $command = escapeshellcmd("sudo python ".PYTHON_PATH."property.py $a $b $c");
        $command_output = shell_exec($command);
        return $command_output;
    }
      
?>
