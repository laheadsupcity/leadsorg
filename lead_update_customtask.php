<?php
require_once('config.php');
include('datafunction.php');
$id=isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '';
$propertdata=getpropertydata($id);
$zip=getziplist();
$city=getcitylist();
$zoning=getzoninglist();
$exemption=getexemptionlist();

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus®">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Scrapping</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="js/multiselect.js"></script>
<script type="text/javascript" src="js/myscr.js"></script>
<link rel="stylesheet" href="css/style.css">
<style>
.mrt option {background: #fff !important;}
.active1{background:#337ab7!important;}
</style>	  
</head>
 <body>
	<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
		<div class="scr1" style="height:auto;">
			<h4>Edit Property Information</h4>
			<form  id="cdsearchform" method="post" >
				<div class="editform" style="width:100%; overflow:hidden; padding:10px 0;">
					<div class="col-sm-4">
					<p class="heading2">Owner Information</p>
					<p><label><span>Parcel Number</span></label><br/> <input type="text" readonly class="edittext" name="apn" id="apn" value="<?php echo  isset($propertdata['parcel_number']) ? $propertdata['parcel_number'] : '' ?>"></p>

					<p><label><span>Owner Name</span></label><br/> <input type="text" class="edittext" name="ownername" id="ownername" value="<?php echo  isset($propertdata['owner_name2']) ? $propertdata['owner_name2'] : '' ?>"></p>

					<p><label><span>Owner First Name</span></label><br/> <input type="text" class="edittext" name="ofname" id="ofname" value="<?php echo  isset($propertdata['owner1_first_name']) ? $propertdata['owner1_first_name'] : '' ?>"></p>

					<p><label><span>Owner Last Name</span></label><br/> <input type="text" class="edittext" name="olname" id="olname" value="<?php echo  isset($propertdata['owner1_last_name']) ? $propertdata['owner1_last_name'] : '' ?>"></p>

					<p><label><span>Owner Spouse Name</span></label><br/> <input type="text" class="edittext" name="osname" id="osname" value="<?php echo  isset($propertdata['owner1_spouse_first_name']) ? $propertdata['owner1_spouse_first_name'] : '' ?>"></p>
					
					</div>

					<div class="col-sm-4">
						<p class="heading2">Address Information</p>
						<table style="width:100%;">
						<tr>
						<td><p><label><span>Street Number</span></label><br/> <input type="text" class="edittext" name="streetnumber" id="streetnumber" value="<?php echo  isset($propertdata['street_number']) ? $propertdata['street_number'] : '' ?>"></p></td>
						<td><p><label><span>Street Name</span></label><br/> <input type="text" class="edittext" name="strname" id="strname" value="<?php echo  isset($propertdata['street_name']) ? $propertdata['street_name'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td><p><label><span>Site Address Zip</span></label><br/> <input type="text" class="edittext" name="sazip" id="sazip" value="<?php echo  isset($propertdata['site_address_zip']) ? $propertdata['site_address_zip'] : '' ?>"></p></td>

						<td><p><label><span>Mail Address Zip</span></label><br/> <input type="text" class="edittext" name="mazip" id="mazip" value="<?php echo  isset($propertdata['mail_address_zip']) ? $propertdata['mail_address_zip'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td colspan="2"><p><label><span>Full Mail Address</span></label><br/> <input type="text" class="edittext1" name="fmaddress" id="fmaddress" value="<?php echo  isset($propertdata['full_mail_address']) ? $propertdata['full_mail_address'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td colspan="2"><p><label><span>Mail Address City/State</span></label><br/><input type="text" class="edittext1" name="maddcs" id="maddcs" value="<?php echo  isset($propertdata['mail_address_city_state']) ? $propertdata['mail_address_city_state'] : '' ?>"> </p></td>
						</tr>

						<tr>
						<td><p><label><span>Use Code</span></label><br/> <input type="text" class="edittext" name="uscode" id="uscode" value="<?php echo  isset($propertdata['use_code']) ? $propertdata['use_code'] : '' ?>"></p></td>

						<td><p><label><span>Use Code Description</span></label><br/> <input type="text" class="uscodedesc" name="uscodedesc" id="uscodedesc" value="<?php echo  isset($propertdata['use_code_descrition']) ? $propertdata['use_code_descrition'] : '' ?>"></p></td>
						</tr>

						</table>		
					</div>

					<div class="col-sm-4">
						<p class="heading2">Property Information</p>
						<table style="width:100%;">
						<tr>
						<td><p><label><span>Building Area</span></label><br/> <input type="text" class="edittext" name="buldarea" id="buldarea" value="<?php echo  isset($propertdata['building_area']) ? $propertdata['building_area'] : '' ?>"></p></td>
						<td><p><label><span>Bedrooms</span></label><br/> <input type="text" class="edittext" name="bedroom" id="bedroom" value="<?php echo  isset($propertdata['bedrooms']) ? $propertdata['bedrooms'] : '' ?>"></p></td>

						<td><p><label><span>Bathrooms</span></label><br/> <input type="text" class="edittext" name="bathrooms" id="bathrooms" value="<?php echo  isset($propertdata['bathrooms']) ? $propertdata['bathrooms'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td><p><label><span>Tract</span></label><br/> <input type="text" class="edittext" name="tract" id="sazip" value="<?php echo  isset($propertdata['tract']) ? $propertdata['tract'] : '' ?>"></p></td>

						<td><p><label><span>Lot Area SQFT</span></label><br/> <input type="text" class="edittext" name="lasqft" id="lasqft" value="<?php echo  isset($propertdata['lot_area_sqft']) ? $propertdata['lot_area_sqft'] : '' ?>"></p></td>
						
						<td><p><label><span>Year Built</span></label><br/> <input type="text" class="edittext" name="yearbuilt" id="yearbuilt" value="<?php echo  isset($propertdata['year_built']) ? $propertdata['year_built'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td><p><label><span>Pool</span></label><br/> <input type="text" class="edittext" name="pool" id="pool" value="<?php echo  isset($propertdata['pool']) ? $propertdata['pool'] : '' ?>"></p></td>

						<td><p><label><span>Garage Type</span></label><br/> <input type="text" class="edittext" name="garage_type" id="garage_type" value="<?php echo  isset($propertdata['garage_type']) ? $propertdata['garage_type'] : '' ?>"></p></td>
						
						<td><p><label><span>Sale Date</span></label><br/> <input type="text" class="edittext" name="sales_date" id="sales_date" value="<?php echo  isset($propertdata['sales_date']) ? $propertdata['sales_date'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td><p><label><span>Number of Units</span></label><br/> <input type="text" class="edittext" name="nou" id="nou" value="<?php echo  isset($propertdata['number_of_units']) ? $propertdata['number_of_units'] : '' ?>"></p></td>

						<td><p><label><span>Number of Stories</span></label><br/> <input type="text" class="edittext" name="nos" id="nos" value="<?php echo  isset($propertdata['number_of_stories']) ? $propertdata['number_of_stories'] : '' ?>"></p></td>
						
						<td><p><label><span>Zoning</span></label><br/> <input type="text" class="edittext" name="zoning" id="zoning" value="<?php echo  isset($propertdata['zoning']) ? $propertdata['zoning'] : '' ?>"> </p></td>
						</tr>

						<tr>
						<td><p><label><span>Sales Price</span></label><br/> <input type="text" class="edittext" name="salprice" id="salprice" value="<?php echo  isset($propertdata['sales_price']) ? $propertdata['sales_price'] : '' ?>"></p></td>

						<td><p><label><span>Cost Per Sq Ft</span></label><br/> <input type="text" class="edittext" name="cpsqft" id="cpsqft" value="<?php echo  isset($propertdata['cost_per_sq_ft']) ? $propertdata['cost_per_sq_ft'] : '' ?>"></p></td>
						
						<td><p><label><span>Total Assessed Value</span></label><br/> <input type="text" class="edittext" name="totalav" id="totalav" value="<?php echo  isset($propertdata['total_assessed_value']) ? $propertdata['total_assessed_value'] : '' ?>"></p></td>
						</tr>
						</table>
					</div>
				</div>
				<div class="editform" style="width:100%; overflow:hidden; padding:10px 0;">
					<div class="col-sm-6">
						<p class="heading2">Other Information</p>
							<table style="width:100%;">
						<tr>
						<td><p><label><span>Sales Price Code</span></label><br/> <input type="text" class="edittext" name="spcode" id="spcode" value="<?php echo  isset($propertdata['sales_price_code']) ? $propertdata['sales_price_code'] : '' ?>"></p></td>

						<td><p><label><span>Tax Exemption Code</span></label><br/> <input type="text" class="edittext" name="texcode" id="texcode" value="<?php echo  isset($propertdata['tax_exemption_code']) ? $propertdata['tax_exemption_code'] : '' ?>"></p></td>

						<td><p><label><span>Fireplace</span></label><br/> <input type="text" class="edittext" name="fireplace" id="fireplace" value="<?php echo  isset($propertdata['fireplace']) ? $propertdata['fireplace'] : '' ?>"></p></td>
						</tr>

						<tr>
						<td><p><label><span>Owner Occupied</span></label><br/> <input type="text" class="edittext" name="ownerocupied" id="ownerocupied" value="<?php echo  isset($propertdata['owner_occupied']) ? $propertdata['owner_occupied'] : '' ?>"></p></td>


						
						<td><p><label><span>Total Market Value</span></label><br/> <input type="text" class="edittext" name="tmv" id="tmv" value="<?php echo  isset($propertdata['total_market_value']) ? $propertdata['total_market_value'] : '' ?>"></p></td>
						</tr>
						</table>


						<table style="width:100%;">
						<tr>
						<td style="width: 17%;"><p><label><span>Assessed Improvement Value</span></label><br/> <input type="text"  class="edittext" name="aiv" id="aiv" value="<?php echo  isset($propertdata['assessed_improvement_value']) ? $propertdata['assessed_improvement_value'] : '' ?>"></p></td>

						<td style="width: 34%;"><p><label><span>Assessed Land Value</span></label><br/> <input type="text" style="width: 46%;" class="edittext" name="alv" id="alv" value="<?php echo  isset($propertdata['assessed_land_value']) ? $propertdata['assessed_land_value'] : '' ?>"><input type="hidden" name="pid" value="<?php echo  isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '' ?>"/></p></td>
						</tr>
						</table>
						 <input type="submit" style="width:30%;" id="savesubmit" class="btn btn-block" onclick="return updatepfilter();" value="Update" />
					</div>
		
				</div>

			</form>
		</div>
	</div>
 </body>
</html>