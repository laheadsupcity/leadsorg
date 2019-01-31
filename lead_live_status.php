<?php
 require_once('config.php');
 $apnarray=array(4302021021,4302022002,4302022005,4302023010,4302027007,4302028028,4303001026,4303001027,4303002037,4303002045);
 $k = array_rand($apnarray);
 $apn = $apnarray[$k];
 $a=0;
 $b=0;
 $c=$apn;
 $status= gethousingstatus($a, $b, $c);
 if($status==1){

    updatescrappstatus($status);
 }else {
    updatescrappstatus($status);

 }


function updatescrappstatus($status){
    $db = Database::instance();
    $db->update(
        'scrapper_setting',
        array(
            'site_status' =>$status 
        ),
    
        array( 
            'id' => 1,
         )
    
    );

}
 function gethousingstatus($a, $b, $c)
 {
        $command = escapeshellcmd("sudo python ".PYTHON_PATH."property.py $a $b $c");
        $command_output = shell_exec($command);
        $response=json_decode($command_output); 
        return $response->status;
 }

?>
