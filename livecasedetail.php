<?php
require_once('config.php');
include('datafunction.php');
$apn=isset($_POST['apn']) ? $_POST['apn'] : '';
$case_id=isset($_POST['case_id']) ? $_POST['case_id'] : '';
$case_type=isset($_POST['case_type']) ? $_POST['case_type'] : '';
$responsedata=gethousingcasedetail($apn,$case_id,$case_type);
$rescount=count($responsedata);
$caselis=array();
$img = explode('http://',$responsedata['propertyinfo']->imageurl);
if(isset($responsedata['casetable'])){
    $tbl=json_decode($responsedata['casetable']);
    $tblarray=$tbl->casetbldata;
    foreach($tblarray as $key=>$val) {
        $caselis[]=explode("~",$val);

    }
}

?>

<div class="col-sm-12" style="padding:0;">
	<h4>PROPERTY ACTIVITY REPORT</h4>
	<table cellspacing="5" style="width:100%; margin:0 auto;">
		<tr>
		<td class="field2">Assessor Parcel Number:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->APN; ?></td>
		<td class="field2">Official Address:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lnkbtnPropAddr; ?></td>
		</tr>
		<tr>
		<td class="field2">Council District:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCD; ?></td>
		<td class="field2">Case Number:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCaseNo; ?></td>
		</tr>
		<tr>
		<td class="field2">Census Tract:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCT; ?></td>
		<td class="field2">Case Type:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblSource; ?></td>
		</tr>
		<tr>
		<td class="field2">Rent Registration:</td>
		<td class="field2data"></td>
		<td class="field2">Inspector:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblInspectorName;?></td>
		</tr>
		<tr>
		<td class="field2">Historical Preservation Overlay Zone:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblHPOZ; ?></td>
		<td class="field2">Case Manager:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCaseManager; ?></td>
		</tr>

		<tr>
		<td class="field2">Total Units:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->ttlUnits; ?></td>
		<td class="field2">Total Exemption Units:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblTotalExemptionUnits; ?></td>
		</tr>

		<tr>
		<td class="field2">Regional&nbsp;Office:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCodeOffice; ?></td>
		<td class="field2">Regional Office Contact:</td>
		<td class="field2data"><?php echo $responsedata['propertyinfo']->lblCodeOfficeContactNo; ?></td>
		</tr>

		<tr>
		<td class="field2">Nature of Complaint:</td>
		<td class="field2data" colspan="4"><?php echo $responsedata['propertyinfo']->ComplaintNature; ?></td>
		</tr>
	</table>
	</div>
	<div style="width:100%; overflow:hidden;">
		<div class="col-sm-7" style="padding:0;">
			<table cellspacing="5" style="width:100%; margin:0 auto; border:1px solid #337ab7; font-size:12px;">
			<tr style="color:White;background-color:#3399FF;font-weight:bold;height:30px;">
			<td style="padding:0 5px; border-right:1px solid #fff;">Date</td>
			<td style="padding:0 5px;">Status</td>
			</tr>
				<?php
				foreach($caselis as $row){
				echo "<tr style='color:Black;background-color:White;'>
				<td align='left' style='width:40%; border-right:1px solid #337ab7; border-bottom:1px solid #337ab7; padding:3px 5px;'>".$row[0]."</td>
				<td align='left' style='width:60%; border-bottom:1px solid #337ab7; padding:3px 5px;'>".$row[1]."</td>
				</tr>";
				}
				?>
			</table>
		</div>

		<div class="col-sm-5" style="padding:0 0 0 15px;">
			 <p> <img id="MainContent_Image2" src="http://<?php echo $img[2]; ?>" style="color:#FFFF80; height:auto; width:100%;"> </p>
		</div>
	</div>
