<?php 
	require_once('config.php');
	$vewid=isset($_REQUEST['view']) ? $_REQUEST['view'] : '';
	$record=getscheduledetailid($vewid);
	function getscheduledetailid($vewid){
		$db = Database::instance();
		$db->select('batch', array('id'=>$vewid),'' , 'id DESC','*');
		$record=$db->result_array();

		return $record[0];
		
	}
	$listarr=array();
	if(isset($record['group_apn'])){
		$listarr=explode(',', $record['group_apn']);
	}
	$db->leadbatchdata($listarr);
	$result=$db->result_array();
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
	<h1 style="text-align:center; float:left; width:100%; margin:-10px 0 10px;">Lead batch View</h1>
	<div class="scr1" style="height:auto;">
    <table class="table table-fixed" style="margin:0;">
		<thead>
			<tr style="background:#337ab7!important;">
			<th class="lf0"><p style="text-align:-webkit-center">#</th>
			<th class="lf2">Parcel Number</th>
			<th class="lf2">Address</th>
			<th class="lf2">Owner Name</th>
			<th class="lf0">#Units</th>
			<th class="lf0">#Stories</th>
			<th class="lf0">#Bed</th>
			<th class="lf0">#Bath</th>
			<th class="lf1">Lot SQFT</th>
			<th class="lf1">Cost Per SQFT</th>
			<th class="lf1">Year Built</th>
			<th class="lf2">Sale Date</th>
			<th class="lf1">Sale Price</th>
			<th class="lf1">Options</th>
			</tr>
		</thead>
		<tbody>
		<?php $tcount=count($result); if($tcount >0) { ?> 
		<?php  $i=0; foreach($result as $key=>$val) { $i++; ?>
			<tr>
			<td class="lf0" style="text-align:center;"><?php echo $i; ?></td>
			<!--<td class="cal1"><?php echo date("m-d-y"); ?></td>-->
			<td class="lf2"><?php echo $val['parcel_number']; ?> <br/> <a target="_blank" href="lead_property_scraphistory.php?hid=<?php echo $val['id'];?>&hapn=<?php echo $val['parcel_number'];?>" >Scrape Detail</a></td>
			<td class="lf2"><?php echo $val['street_number'].','.$val['street_name'].',<br/> '.$val['site_address_city_state'].', '.$val['site_address_zip']; ?></td>
			<!-- <td class="cal2"><?php echo $val['full_mail_address'].', '.$val['mail_address_city_state'].', '.$val['site_address_zip']; ?></td> -->
			<td class="lf2"><?php echo $val['owner_name2']; ?></td>
			<td class="lf0"><?php echo $val['number_of_units']; ?></td>
			<td class="lf0"><?php echo $val['number_of_stories']; ?></td>
			<td class="lf0"><?php echo $val['bedrooms']; ?></td>
			<td class="lf0"><?php echo $val['bathrooms']; ?></td>
			<td class="lf1" style="text-align:right;"><?php echo $val['lot_area_sqft']; ?></td>
			<td class="lf1" style="text-align:right;"><?php echo $val['cost_per_sq_ft']; ?></td>
			<td class="lf1" style="text-align:right;"><?php echo $val['year_built']; ?></td>
			<td class="lf2" style="text-align:center;">
				<?php 
					if($val['sales_date']!='0000-00-00'){
						echo date('m/d/Y',strtotime($val['sales_date']));
					}
					else {
						echo "";
					}
				?>
			</td>
			<td class="lf1" style="text-align:right;"><?php echo $val['sales_price']; ?></td>
			<td class="lf1" style="text-align:right;"><a target="_blank" href="lead_property_detail.php?apn=<?php echo $val['parcel_number'];?>" >View</a>&nbsp;|&nbsp;<a href="lead_update_customtask.php?editid=<?php echo $val['id'];?>" >Edit</a></td>
		 </tr>
	<?php } } else{ ?>
	<tr>
	<td style="text-align:center;" colspan="14">No record Found.</td>
	</tr>				
	<?php } ?>
    </table>
 </body>
 </html>
