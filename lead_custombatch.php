<?php 
	require_once('config.php');
	include('datafunction.php');
	function getscheduledetail(){
	    $db = Database::instance();
	    $db->select('batch', array(),'' , 'id DESC','*');
	    $record=$db->result_array();
	    return $record;
	}
	$record=getscheduledetail();
	$getlead_task_count=getlead_task_count();
	$activebatch=getlead_task_active();
	$batchlist=getfinalbatchlist();


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
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>                       
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> -->
                <script src="js/moment.min.js"></script>                       
		<script src="js/bootstrap-datetimepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
		<script type="text/javascript" src="js/multiselect.js"></script>
		<script type="text/javascript" src="js/myscr.js"></script>
		<link rel="stylesheet" href="css/style.css">
		<style>
			.active1{background:#337ab7!important;}
			.show_scrap{border:1px solid #fff; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; width: 100%;}
			.show_scrap th{background:#0070c0; color:#fff;font-size:13px; border:1px solid #fff; padding:5px 5px;}
			.show_scrap tr td{border:1px solid #fff;font-size:12px; padding:5px 5px;}
			tr{background-color: #fff;}
			tr:nth-child(even) {background-color: #e9ebf5;}
			.task input{width:100%; border:1px solid #333; margin-bottom:5px; padding:5px;}
			.task label{padding:0px!important; font-weight:600!important; font-size:13px!important;}
			#taskintervald, #taskintervalw, #taskintervaly, #taskintervalm{position:relative; top:5px; margin-right:15px; margin-bottom:15px;}
			.btn-block {display: inline !important;width: auto;background: #0070c0;color: #fff;padding: 5px;}
			.dropdown-menu{top:180px!important;}
			.taskdelete{width: auto; background: #0070c0;}
			.table>tbody>tr>td{
				text-align: left;
			}

			.multiselect {
    width:21em;
    height:140px;
    border:solid 1px #c0c0c0;
    overflow:auto;
}
 
.multiselect label {
    display:block;
}
 
.multiselect-on {
    color:#ffffff;
    background-color:#000099;
}
.batchcheck{
	top: 3px;
    position: relative;
	margin: 5px -161px !important;
}
.batchallcheck{
	/*top: 22px;
    position: relative;*/
	top: 3px;
    position: relative;
	margin: 5px -161px !important;
}
}
		</style>	  
	</head>
	<body>
		<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
		<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Lead Batches</h1>
		<div style="max-width: 1200px;margin: 0 auto 20px;">
			<div style="width:100%;overflow:hidden;text-align:right;">
				
				<button class="btn btn-block" onclick="return createallscheduler();" style="margin: 5px 0px;" id="createscheduler" >Create all scheduler</button>
				
					<button class="btn btn-block"  style="margin: 5px 0px;" onclick="return stopscheduler();" id="stopscheduler" >Stop all scheduler</button>&nbsp;</div></div>
        		<div class="scr1" style="height:auto;">
			<table class="table table-fixed" style="margin:0;">
				<thead>
					<tr style="background:#337ab7!important;">
					<th class="col-sm-1">Batch Id</th>
						<th class="col-sm-2">Batch Name</th>
						<th class="col-sm-2">Last Search Date</th>
						<!--<th>Scheduled Search date</th>-->
						<th class="col-sm-1">Number of records</th>
						<th class="col-sm-6">Options</th>
					</tr>
				</thead>
					<?php foreach($record as $key=>$val) {
						$a= $val['group_apn'];
						$b= explode(',', $a); 
						$count= count($b);
					?>
					<tr>
					<td class="col-sm-1"><?php echo $val['id']; ?></td>
						<td class="col-sm-2"><?php echo $val['batchname']; ?></td>
						<td class="col-sm-2"> <?php echo date('m-d-Y',strtotime($val['createddate'])); ?></td>
						<!--<td> <?php //echo date('m-d-Y H:i',strtotime($val['schedule_date'])); ?></td>-->
						<td class="col-sm-1"><span style="padding-left:10px;"><?php echo $count; ?></span></td>
						<td class="col-sm-6">
							<a href="lead_view_custombatch.php?view=<?php echo $val['id']; ?>">View</a> 
							<!--| <a href="#">Edit</a> -->| 
							<a href="#" class="deletebatch" onclick="return cdeletebatch(<?php echo $val['id']; ?>);" >Delete</a> | 
							<a onclick="return cleadbatchexport(<?php echo $val['id']; ?>);" href="#">Export</a>  |
							<a onclick="return customtimesearch(<?php echo $val['id']; ?>,'<?php echo $val['batchname']; ?>');" href="#">Reschedule</a> | 
							<a  href="lead_custom_leadbatch_rescheduleview.php?view=<?php echo $val['id']; ?>">Reschedule View</a> |
							<a  onclick="return startaudit(<?php echo $val['id']; ?>);" href="#">Start audit</a> |
							<a href="lead_audit_view.php?batchid=<?php echo $val['id']; ?>">Audit View</a>
						</td>
					</tr>
					<?php } ?>
				</table>
				<div id="overlay">
					<div id="batchform" style="text-align:left;" class="task">
						<div class="closeicon"><a href="#" class="taskcloseimg"><img src='images/close.png'></a></div>
						<p style="border-bottom:1px solid #000;">Task Definition</p>
						<form method="post" action="#" id="schtask">
							<input type="hidden" name="batchid" id="batchid" val="">
							<label style="color:#000;">Batch Name <span class="etask" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Task Name.</span></label><br/><input type="text" readonly="readonly" name="taskname" id="taskname"><br/>
							<label style="color:#000;">Start Task <span class="edate" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Start Date.</span></label><br/><input type="text" name="sttask" id="sttask" autocomplete="off"><br/>
							<!--<label style="color:#000;">Stop Task</label><br/><input type="date" name="stoptask" id="stoptask"><br/> -->
							<p style="color:#000; margin:0;  font-weight:600; font-size:13px;">Interval <span class="etime" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Select Any One.</span></p>
							<!--<label style="color:#000;"> Once <input type="radio" name="taskinterval" id="taskinterval" value="Once"> </label>-->
							<label style="color:#000;"> Daily <input type="radio" name="taskinterval" id="taskintervald" value="daily"> </label> 
							<label style="color:#000;"> Weekly <input type="radio" name="taskinterval" id="taskintervalw" value="weekly"> </label>
							<label style="color:#000;"> Monthly <input type="radio" name="taskinterval" id="taskintervalm" value="monthly"> </label>
							<label style="color:#000;"> Yearly <input type="radio" name="taskinterval" id="taskintervaly" value="yearly"> </label>
							<p><button type="submit" class="btn btn-block" style="padding:5px 10px;" onclick="return customtasksubmit();"><span class="btntext">Submit</span></button> 
							<button type="submit" class="btn taskdelete" style="padding:5px 10px; display:none; top:-1px; left:15px; position:relative; color:#fff;" onclick="return customtaskdelete();">Stop</button></p>
							<p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Task created successfully</p>
						</form>
					</div>
				</div>

			</div>
            <div id="overlay3">
					<div id="allschedulerform" class="task">
					<div class="closeicon"><a href="#" class="staskcloseimg"><img src='images/close.png'></a></div>
						<p style="border-bottom:1px solid #000;">Create Scheduler</p>
						<form method="post" action="#" id="createallbatch">
						<div id="wrapper">
						<label style="color:#000;">Batch Name</label><br/>
					    <div class="multiselect">
						<label><input type="checkbox" class="batchallcheck" onclick="batchtoggle(this);" />Check all</label>
							<?php foreach($batchlist as $batchid) { ?>

							<label><input type="checkbox" class="batchallcheck" name="selectbatch[]" value="<?php echo  $batchid;?>" /><?php echo getbatchname($batchid) ;?></label>



							<?php } ?>			
								</div>
						</div>
							<br/>
							<label style="color:#000;">Start Task <span class="edate" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Start Date.</span></label><br/><input type="text" class="dateinte" name="allstask" id="allstask" autocomplete="off"><br/>
							<label style="color:#000;">Task Interval <span class="etask" style="color:red; font-size:12px; padding-left:25px; font-style:italic; display:none;">Please Enter Task Interval.</span></label><br/><input type="text" name="alltaskinterval" class="number" id="alltaskinterval"><br/>
							<!--<label style="color:#000;">Stop Task</label><br/><input type="date" name="stoptask" id="stoptask"><br/> -->

							<!--<label style="color:#000;"> Once <input type="radio" name="taskinterval" id="taskinterval" value="Once"> </label>-->
							
							<p><button type="submit" class="btn btn-block" style="padding:5px 10px;" onclick="return customallschsubmit();"><span class="btntext">Submit</span></button> 
	
							<p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Task created successfully</p>
						</form>
						
					</div>
			</div>
			<div id="overlay4">
					<div id="stopschedulerform" class="task">
					<div class="closeicon"><a href="#" class="stopcloseimg"><img src='images/close.png'></a></div>
						<p style="border-bottom:1px solid #000;">Stop Scheduler</p>
						<form method="post" action="#" id="stopschtask" >
						<div id="wrapper">
						<label style="color:#000;">Batch Name</label><br/>
					    <div class="multiselect">
						<label><input type="checkbox" class="batchcheck" onclick="toggle(this);" />Check all</label>
							<?php foreach($activebatch as $batchid) { ?>

							<label><input type="checkbox" class="batchcheck" name="stopselectbatch[]" value="<?php echo  $batchid['batchid'] ;?>" /><?php echo getbatchname($batchid['batchid']) ;?></label>



							<?php } ?>			
								</div>
						</div>
							<br/>
							<!--<label style="color:#000;">Stop Task</label><br/><input type="date" name="stoptask" id="stoptask"><br/> -->

							<!--<label style="color:#000;"> Once <input type="radio" name="taskinterval" id="taskinterval" value="Once"> </label>-->
							
							<p><button type="submit" class="btn btn-block" style="padding:5px 10px;" onclick="return stopallschsubmit();"><span class="btntext">Submit</span></button> 
	
							<p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Task created successfully</p>
						</form>
						
					</div>
            </div>

		</body>
</html>

