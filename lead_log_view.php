<?php
 require_once('config.php');
 function getscheduled_lead_task($id){
    $resarray=array();
    $db = Database::instance();
    //$db->select('custom_scheduled_lead_task', array('id' => $id,'status'=>'Complete'), false, false,'AND','*');
    $db->select('custom_scheduled_lead_task', array('id' => $id), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result[0];
    }

    return $resarray;
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scraper</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/myscr.js"></script>
  <style>
	.active1{background:#337ab7!important;}
  </style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Rescheduled Log</h1>
<div class="scr1" style="overflow:scroll;height:500px; ">
<?php
$batchid=isset($_REQUEST['log']) ? $_REQUEST['log'] : '';
$leadtask=getscheduled_lead_task($batchid);
if(count($leadtask) >0 ){
$file=$leadtask['log_file_path'];
$command = escapeshellcmd("sudo cat $file");
$command_output = shell_exec($command);
echo "<pre>";
print_r($command_output);
echo "<pre>";

}else {

echo "<p style='text-align: center;margin: 6%;font-weight: bold;' >No record found</p>";	
}
?>
</div>
</div>
</body>
</html>

