<?php 
	require_once('config.php');
	$hid=isset($_REQUEST['hid']) ? $_REQUEST['hid'] : '';
	$hapn=isset($_REQUEST['hapn']) ? $_REQUEST['hapn'] : '';
	$scraphistory=getscrapDetail($hid);


	function getscrapDetail($hid){
		$db = Database::instance();
		$db->select('property_scrap_history', array('property_id' => $hid), false, false,'','*');
		$result=$db->result_array();
		return $result;
	}

		function getbatchname($batchid){
		$name ="";
		$db = Database::instance();
		$db->select('custom_scheduled_lead_task', array('id' => $batchid), false, false,'','taskname');		
		$result=$db->result_array();
		if(count($result >0)) {
		   foreach ($result as $key => $val) {
			 $name = $val['taskname'];
			}
		}
		   return $name;
	}
?>
<!doctype html>
<html lang="en">
<head>
	<title>Scrapping</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="js/multiselect.js"></script>
	<script type="text/javascript" src="js/myscr.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<style>
		.active1{background:#337ab7!important;}
		tr{background-color: #fff;}
		tr:nth-child(even) {background-color: #e9ebf5;}
		.btn-block {display: inline !important;width: auto;background:#0070c0; color:#fff;padding:5px;}
		.btn-block+.btn-block { margin-top: 0px!important;}
		.active1{background:#337ab7!important;}
		.menu{margin:0 auto 0px!important;}
		body{overflow-x:hidden}
		.table-fixed tbody { height: 400px!important;}
	</style>	  
</head>
 <body>
 <div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<h1 style="text-align:center; float:left; width:100%; margin:-10px 0 10px;">Property Scrap View</h1>
	<div class="scr1" style="height:auto;">
    <table class="table table-fixed" style="margin:0;">
		<thead>
			<tr style="background:#337ab7!important;">
			<th class="col-sm-1"><p style="text-align:-webkit-center">#</th>
			<th class="col-sm-2">Parcel Number</th>
			<th class="col-sm-1">Batch Task</th>
			<th class="col-sm-3">Scrap Status</th>
			<th class="col-sm-2">Scrap Date</th>
			</tr>
		</thead>
		<tbody>
		<?php $tcount=count($scraphistory); if($tcount >0) { ?> 
		<?php  $i=0; foreach($scraphistory as $key=>$val) { $i++; ?>
			<tr>
			<td class="col-sm-1" style="text-align:center;"><?php echo $i; ?></td>
			<td class="col-sm-2"><?php echo $hapn; ?> <br/></td>
			<td class="col-sm-1"><?php echo getbatchname($val['batch_task_id']); ?></td>
			<td class="col-sm-3">
			<?php
				$hstatus= $val['scrap_status'];
				if($hstatus==0){
					echo "No update because no changes in  LA site.";
				}
				elseif($hstatus==1){
					echo "Property update successfully";
				}
				elseif($hstatus==2){
					echo "Scraping failed due to  connection failed.";
				}
				elseif($hstatus==3){
					echo "Property is missing in  LA site.";
				}
			
			?>
			</td>
			<td class="col-sm-2">
				<?php 
					if($val['scrap_date']!='0000-00-00'){
						echo date('m/d/Y',strtotime($val['scrap_date']));
					}
					else {
						echo "";
					}
				?>
			</td>
		 </tr>
	<?php } } else{ ?>
	<tr>
	<td style="text-align:center;" colspan="14">No record Found.</td>
	</tr>				
	<?php } ?>
    </table>
 </body>
 </html>

