<?php
require_once('config.php');
function getscheduledetail(){
    $db = Database::instance();
    $db->select('scheduled_batch', array(),'' , 'id DESC','*');
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
<title>Scraping | Lead Batch</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="js/moment.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>                       
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/multiselect.js"></script>
<script type="text/javascript" src="js/myscr.js"></script>
<link rel="stylesheet" href="css/style.css">
<style>
.active3{background:#337ab7!important;}
tr{background-color: #fff;}
tr:nth-child(even) {background-color: #e9ebf5;}
.table-fixed tbody { height: 400px!important;}
.task input{width:100%; border:1px solid #333; margin-bottom:5px; padding:5px;}
.task label{padding:0px!important; font-weight:600!important; font-size:13px!important;}
#taskintervald, #taskintervalw, #taskintervaly, #taskintervalm{position:relative; top:5px; margin-right:15px; margin-bottom:15px;}
.btn-block {
display: inline !important;
width: auto;
background: #0070c0;
color: #fff;
padding: 5px;
}
.dropdown-menu{top:180px!important;}
.taskdelete{width: auto; background: #0070c0;}
</style>	  
</head>
 <body>
	<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Lead Batches</h1>
	<div class="scr1" style="height:auto;">
		<table class="table table-fixed" style="margin:0;">
		<thead>
		<tr style="background:#337ab7!important;">
		<th class="col-sm-3">Batch Name</th>
		<th class="col-sm-3">Last Scrape Date</th>
		<!--<th>Scheduled Search date</th>-->
		<th class="col-sm-3">Number of records</th>
		<th class="col-sm-3">Options</th>
		</tr>
		</thead>
		<?php foreach($record as $key=>$val) {
		$a= $val['group_apn'];
		$b= explode(',', $a); 
		$c= count($b);
		?>
		<tr style="color:#777">
		<td class="col-sm-3"><?php echo $val['batchname']; ?></td>
		<td class="col-sm-3"> <?php echo date('m-d-Y H:i'); ?></td>
		<!--<td> <?php //echo date('m-d-Y H:i',strtotime($val['schedule_date'])); ?></td>-->
		<td class="col-sm-3"> <?php echo $c; ?></td>
		<td class="col-sm-3"><a href="lead_view_schedulebatch.php?view=<?php echo $val['id']; ?>">View</a> <!--| <a href="#">Edit</a> -->| <a href="#" class="deletebatch" onclick="return deletebatch(<?php echo $val['id']; ?>);" >Delete</a> | <a onclick="return leadbatchexport(<?php echo $val['id']; ?>);" href="#">Export</a>&nbsp;|&nbsp; <a onclick="return timesearch(<?php echo $val['id']; ?>);" href="#" >Reschedule</a> | 
         <a  href="lead_custom_rescheduleview.php?view=<?php echo $val['id']; ?>">Reschedule View</a></td>
		   </tr>
		<?php } ?>
	</table>
<div id="overlay">
			<div id="batchform" class="task">
			 <div class="closeicon"><a href="#" class="taskcloseimg"><img src='images/close.png'></a></div>
				<p style="border-bottom:1px solid #000;">Task Definition</p>
				<form method="post" action="#" id="schtask">
					<input type="hidden" name="batchid" id="batchid" val="">
					<label style="color:#000;">Scheduled Task <span class="etask" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Task Name.</span></label><br/><input type="text" name="taskname" id="taskname"><br/>
					<label style="color:#000;">Start Task <span class="edate" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Start Date.</span></label><br/><input type="text" name="sttask" id="sttask"><br/>
					<!--<label style="color:#000;">Stop Task</label><br/><input type="date" name="stoptask" id="stoptask"><br/> -->
					<p style="color:#000; margin:0; font-weight:600; font-size:13px;">Interval <span class="etime" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Select Any One.</span></p>
					<!--<label style="color:#000;"> Once <input type="radio" name="taskinterval" id="taskinterval" value="Once"> </label>-->
					<label style="color:#000;"> Daily <input type="radio" name="taskinterval" id="taskintervald" value="daily"> </label> 
					<label style="color:#000;"> Weekly <input type="radio" name="taskinterval" id="taskintervalw" value="weekly"> </label>
					<label style="color:#000;"> Monthly <input type="radio" name="taskinterval" id="taskintervalm" value="monthly"> </label>
					<label style="color:#000;"> Yearly <input type="radio" name="taskinterval" id="taskintervaly" value="yearly"> </label>
					<p><button type="submit" class="btn btn-block" style="padding:5px 10px;" onclick="return tasksubmit();"><span class="btntext">Submit</span></button> 
					<button type="submit" class="btn taskdelete" style="padding:5px 10px; display:none; top:-1px; left:15px; position:relative; color:#fff;" onclick="return taskdelete();">Stop</button>
					</p>
					<p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Task created successfully</p>
				</form>
			</div>
		</div>
	</div>
 </body>
</html>
