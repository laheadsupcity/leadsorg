<?php
require_once('config.php');
function getscheduledetail(){
    $db = Database::instance();
    $db->select('scheduled_search', array(),'' , 'id DESC','*');
    $record=$db->result_array();
    return $record;
    
}
$record=getscheduledetail();

?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Scraping</title>
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script type="text/javascript" src="js/multiselect.js"></script>
   <script type="text/javascript" src="js/myscr.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
 .active3{background:#337ab7!important;}
tr{background-color: #fff;}
tr:nth-child(even) {background-color: #e9ebf5;}
.table-fixed tbody { height: 400px!important;}
</style>	  
 </head>
 <body>
	<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Scheduled Scrape & Results</h1>
	<div class="scr1" style="height:auto;">
		<table class="table table-fixed" style="margin:0;">
		<thead>
		<tr style="background:#337ab7!important;">
		<th class="col-sm-2">Scrape Name</th>
		<th class="col-sm-2">Last Scrape Date</th>
		<th class="col-sm-2">Scheduled Scrape Date</th>
		<th class="col-sm-2">Number of Records</th>
		<th class="col-sm-4">Options</th>
		</tr>
		</thead>
		<?php foreach($record as $key=>$val) {?>
		<tr>
		<td class="col-sm-2"><?php echo $val['name'] ?> </td>
		<td class="col-sm-2"> <?php echo date('m-d-Y h:i a',strtotime($val['create_date']) ); ?></td>
		<td class="col-sm-2"> <?php echo date('m-d-Y h:i a',strtotime($val['schedule_date'])); ?></td>
		<td class="col-sm-2"><?php echo $val['record']?> </td>
		<td class="col-sm-4"><a href="lead_viewschedule.php?view=<?php echo $val['id']; ?>">View</a> | <a onclick="return deleteschedule(<?php echo $val['id']; ?>);" href="#">Delete</a> | <a onclick="return exportschedule(<?php echo $val['id']; ?>);" href="#">Export</a> | <a href="lead_schedulesearch.php?schid=<?php echo $val['id']; ?>">Reschedule Scrape </a></td>
		   </tr>
		<?php } ?>
	</table>
	</div>
 </body>
</html>
