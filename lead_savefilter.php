<?php
require_once('config.php');
$nouf=isset($_POST['nouf']) ? $_POST['nouf'] : '';
$nout=isset($_POST['nout']) ? $_POST['nout'] : '';
$fmlytype=isset($_POST['fmlytype']) ? $_POST['fmlytype'] : '';
$sfmlytype=isset($_POST['sfmlytype']) ? $_POST['sfmlytype'] : '';
$zip=isset($_POST['zipto']) ? $_POST['zipto'] : '';
$cityto=isset($_POST['cityto']) ? $_POST['cityto'] : '';
$zoning_to=isset($_POST['zoningto']) ? $_POST['zoningto'] : '';
$exemptionto=isset($_POST['exemptionto']) ? $_POST['exemptionto'] : '';
$casetypeto=isset($_POST['opencasetype']) ? $_POST['opencasetype'] : '';
$cdate=isset($_POST['cdate']) ? $_POST['cdate'] : '';
$ctime=isset($_POST['ctime']) ? $_POST['ctime'] : '';
$nbedf=isset($_POST['nbedf']) ? $_POST['nbedf'] : '';
$nbedt=isset($_POST['nbedt']) ? $_POST['nbedt'] : '';
$nbathf=isset($_POST['nbathf']) ? $_POST['nbathf'] : '';
$nbatht=isset($_POST['nbatht']) ? $_POST['nbatht'] : '';
$nstrf=isset($_POST['nstrf']) ? $_POST['nstrf'] : '';
$nstrt=isset($_POST['nstrt']) ? $_POST['nstrt'] : '';
$cpsf=isset($_POST['cpsf']) ? $_POST['cpsf'] : '';
$cpst=isset($_POST['cpst']) ? $_POST['cpst'] : '';
$lasqf=isset($_POST['lasqf']) ? $_POST['lasqf'] : '';
$lasqt=isset($_POST['lasqt']) ? $_POST['lasqt'] : '';
$sprf=isset($_POST['sprf']) ? $_POST['sprf'] : '';
$sprt=isset($_POST['sprt']) ? $_POST['sprt'] : '';
$ybrf=isset($_POST['ybrf']) ? $_POST['ybrf'] : '';
$ybrt=isset($_POST['ybrt']) ? $_POST['ybrt'] : '';
$sdrf=isset($_POST['sdrf']) ? $_POST['sdrf'] : '';
$sdrt=isset($_POST['sdrt']) ? $_POST['sdrt'] : '';
$ooc=isset($_POST['ooc']) ? $_POST['ooc'] : '';
$filter=isset($_POST['filtername']) ? $_POST['filtername'] : '';
$fcount=gettotalcount($filter);
$searchid=getsearchid($filter);



$arr_data=array('nouf'=>$nouf,'nout'=>$nout,'zip'=>$zip,'city'=>$cityto,'zoning'=>$zoning_to,'exemption'=>$exemptionto,'casetype'=>$casetypeto,
'nbedf'=>$nbedf,'nbedt'=>$nbedt,'nbathf'=>$nbathf,'nbatht'=>$nbatht,'nstrf'=>$nstrf,'nstrt'=>$nstrt,'cpsf'=>$cpsf,'cpst'=>$cpst,'lasqf'=>$lasqf,
'lasqt'=>$lasqt,'sprf'=>$sprf,'ooc'=>$ooc,'searchid'=>$searchid,'sprt'=>$sprt,'ybrf'=>$ybrf,'ybrt'=>$ybrt,'sdrf'=>$sdrf,'sdrt'=>$sdrt,'fmlytype'=>$fmlytype,'sfmlytype'=>$sfmlytype,'cdate'=>$cdate,'ctime'=>$ctime);
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