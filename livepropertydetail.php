<?php
require_once('config.php');
include('datafunction.php');
$apn=isset($_REQUEST['apn']) ? $_REQUEST['apn'] : '';
$a=isset($streetnum) ? $streetnum : '0';
$b=isset($streetnam ) ? $streetnam : '0';
//$casety=array('1'=>'Complaint','2'=>'Systematic Code Enforcement Program','3'=>'Case Management','4'=>'Rent Escrow Account Program','5'=>'Hearing','6'=>'SCEP','10'=>'Property Management Training Program','12'=>"Utility Maintenance Program",'13'=>"Emergency",'15'=>'Franchise Tax Board','16'=>'Specialized Enforcement Unit');
$casety=getcasetype();
$c=$apn;
$data= json_decode(gethousingdetail($a,$b,$c));

$casedata=array();
$arr = (array)$data->table;
if(isset($data->table) && !empty($arr)){
    $tbl=json_decode($data->table);
    $tblarray=$tbl->tbldata;
    foreach($tblarray as $key=>$val) {
        $casedata[]=explode("~",$val);

    }
}
$totalcount=count($data);



$property=getpropertyinfo($apn);
$imgurl=getimglist($apn);
$property = $property[0];

function getimgurl($imgurl){
$url = explode('http://',$imgurl);
$newurl=isset($url[2]) ? $url[2] : '';
return $newurl;
}


function getimglist($apn){
$db = Database::instance();
$db->select('property_cases_detail', array('apn' => $apn), false, false,"",'imageurl');
$result=$db->result_array();
return $result;
}

function getpropertyinfo($apn){
$db = Database::instance();
$db->select('property', array('parcel_number' => $apn), false, false,'','*');
$result=$db->result_array();
return $result;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>PROPERTY INFORMATION</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/myscr.js"></script>
    <style>
	.active5{background:#337ab7!important;}
	.caselist tr:nth-child(odd){background-color:#fff}
    .caselist tr:nth-child(even){background-color:#f2f2f2}

	
	.slideshow-container {
	  max-width: 1000px;
	  position: relative;
	  margin: auto;
	}

	
	.prev{
	  cursor: pointer;
	  position: absolute;
	  top:99%;
	  left:25%;
	  width: auto;
	  padding: 16px;
	  margin-top: -22px;
	  color: #337ab7;
	  font-weight: bold;
	  font-size: 35px;
	  transition: 0.6s ease;
	  border-radius: 0 3px 3px 0;
	}


	 .next {
	  cursor: pointer;
	  position: absolute;
	  top:99%;
	  right:25%;
	  width: auto;
	  padding: 16px;
	  margin-top: -22px;
	  color: #337ab7;
	  font-weight: bold;
	  font-size: 35px;
	  transition: 0.6s ease;
	  border-radius: 0 3px 3px 0;
	}

	.mySlides{margin-bottom:35px;}
	.prev img, .next img {width:50px;}

	
	.prev:hover, .next:hover {
	  text-decoration:none;
	  color:#337ab7;
	}


	.text {
	  color: #f2f2f2;
	  font-size: 15px;
	  padding: 8px 12px;
	  position: absolute;
	  bottom: 8px;
	  width: 100%;
	  text-align: center;
	}

	.numbertext {
	  color: #f2f2f2;
	  font-size: 12px;
	  padding: 8px 12px;
	  position: absolute;
	  top: 0;
	}

	
	.dot {
		display:none;
	  cursor: pointer;
	  height: 15px;
	  width: 15px;
	  margin: 0 2px;
	  background-color: #bbb;
	  border-radius: 50%;
	  display: inline-block;
	  transition: background-color 0.6s ease;
	}

	.active1, .dot:hover {
	  background-color: #717171;
	}

  </style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<div class="scr1" style="border:1px solid #fff; height:auto;">
		<h4>PROPERTY INFORMATION</h4>   
                <?php if($totalcount >0 ){  ?>
		<div class="col-sm-4" style="padding:0px 30px 0 0;">
				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Property Address</h4>
						<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Assessor Parcel Number:</td>
							<td class="field1data"><?php echo $data->propertyinfo->APN; ?></td>
							</tr>
							<tr>
							<td class="field1">Official Address:</td>
							<td class="field1data"><?php echo $data->propertyinfo->Address; ?></td>
							</tr>
							<tr>
							<td class="field1">Council District:</td>
							<td class="field1data"><?php echo $data->propertyinfo->lblCDval; ?></td>
							</tr>
						</table>
				</div>

				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Property Details </h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Total Units:</td>
							<td class="field1data"><?php echo $data->propertyinfo->number_of_units; ?></td>
							</tr>
							<tr>
							<td class="field1">Total Exemption Units:</td>
							<td class="field1data"><?php echo $det['exemption']; ?></td>
							</tr>
							<tr>
							<td class="field1">Bedrooms:</td>
							<td class="field1data spdata"><?php echo $property['bedrooms']; ?></td>
							</tr>
							<tr>
							<td class="field1">Bathrooms</td>
							<td class="field1data spdata"><?php echo $property['bathrooms']; ?></td>
							</tr>
							<td class="field1">Number of Stories:</td>
							<td class="field1data spdata"><?php echo $property['number_of_stories']; ?></td>
							</tr>
							<tr>
							<td class="field1">Lot Area Sqft:</td>
							<td class="field1data spdata"><?php echo $property['lot_area_sqft']; ?></td>
							</tr>
							<tr>
							<td class="field1">Pool:</td>
							<td class="field1data spdata"><?php echo $property['pool']; ?></td>
							</tr>
							<tr>
							<td class="field1">Year Built:</td>
							<td class="field1data"><?php echo $data->propertyinfo->lblYear; ?></td>
							</tr>
							<tr>
							<td class="field1">Zoning:</td>
							<td class="field1data spdata"><?php echo $property['zoning']; ?></td>
							</tr>
						</table>
				</div>

				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Other Details</h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Use Code:</td>
							<td class="field1data spdata"><?php echo $property['use_code']; ?></td>
							</tr>
							<tr>
							<td class="field1">Use Code Description:</td>
							<td class="field1data spdata"><?php echo $property['use_code_descrition']; ?></td>
							</tr>
							<tr>
							<td class="field1">Building Area:</td>
							<td class="field1data spdata"><?php echo $property['building_area']; ?></td>
							</tr>
							<tr>
							<td class="field1">Code Regional Area:</td>
							<td class="field1data"><?php echo $data->propertyinfo->lblCodeRegionalAreaval; ?></td>
							</tr>
							<tr>
							<td class="field1">Rent Office ID:</td>
							<td class="field1data"><?php echo $data->propertyinfo->RentOffice; ?></td>
							</tr>
							<tr>
							<td class="field1">Rent Registration Number:</td>
							<td class="field1data"></td>
							</tr>
							<tr>
							<td class="field1">Census Tract:</td>
							<td class="field1data"><?php echo $data->propertyinfo->lblCTval; ?></td>
							</tr>
						</table>
				</div>
		</div>

		<div class="col-sm-4">
				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Owners Info</h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Owners Name:</td>
							<td class="field1data spdata"><?php echo $property['owner_name2']; ?></td>
							</tr>
							<tr>
							<td class="field1">Phone:</td>
							<td class="field1data"></td>
							</tr>
							<tr>
							<td class="field1">Email:</td>
							<td class="field1data"></td>
							</tr>
						</table>
				</div>

				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Photos</h4>
						<div class="slideshow-container">
							<?php
                                                        $defaultimage="images/No_Image.jpg";
							if(count($imgurl) > 0){	
							foreach($imgurl as $key){	
							$cimge= getimgurl($key['imageurl']); 
							?>
								<div class="mySlides">
                                                                <img src="<?php echo isset($cimge) ? '//'.$cimge : $defaultimage ?>" style="width:100%;">
								<!--  <img src="<?php echo "//".$cimge; ?>" style="width:100%;"> -->
								</div>
							<?php
							}}
							
							else{?>

							<div class="mySlides">
								 <img src="<?php  echo $defaultimage; ?>" style="width:100%;">
								</div>
							<?php
							
							}
							?>
							<a class="prev" onclick="plusSlides(-1)"><img src="images/leftarrow.png"></a>
							<a class="next" onclick="plusSlides(1)"> <img src="images/rightarrow.png"></a>
						</div>

						<div style="text-align:center; display:none;">
							<?php
							foreach($imgurl as $key){	
							$cimge= getimgurl($key['imageurl']); 
							?>
						      <span class="dot" onclick="currentSlide()"></span> 
						  <?php
							}
							?>
						</div>
				</div>
		</div>

		<div class="col-sm-4" style="padding:0px 0px 0 30px;">
				<div style="border:1px solid #337ab7; margin:10px 0 0; min-height:50px;">
					<h4 style="text-transform:initial;">Owners Mailing Address </h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Address:</td>
							<td class="field1data spdata">
							<?php echo $property['full_mail_address']; echo "<br/>"; 
							echo $property['mail_address_city_state']; echo "<br/>" ;
							echo $property['mail_address_zip']; 
							?>
							</td>
							</tr>
						</table>
				</div>

				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Sales History</h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Last sale date:</td>
							<td class="field1data spdata"><?php echo $property['sales_date']; ?></td>
							</tr>
							<tr>
							<td class="field1">Sales Price:</td>
							<td class="field1data spdata"><?php echo $property['sales_price']; ?></td>
							</tr>
							<tr>
							<td class="field1">Cost per Sqft:</td>
							<td class="field1data spdata"><?php echo $property['cost_per_sq_ft']; ?></td>
							</tr>
						</table>
				</div>

				<div style="border:1px solid #337ab7; margin:10px 0 0;">
					<h4 style="text-transform:initial;">Value</h4>
					<table cellspacing="5" style="width:100%; margin:0 auto;">
							<tr>
							<td class="field1">Total Assessed Value:</td>
							<td class="field1data spdata"><?php echo $property['total_assessed_value']; ?></td>
							</tr>
							<tr>
							<td class="field1">Assessed Land Value:</td>
							<td class="field1data spdata"><?php echo $property['assessed_land_value']; ?></td>
							</tr>
							<tr>
							<td class="field1">Assessed improvement Value:</td>
							<td class="field1data spdata"><?php echo $property['assessed_improvement_value']; ?></td>
							</tr>
						</table>
				</div>
		</div>
		<div style="width:100%; float:left; margin:15px 0 0;">
			<h4>PROPERTY CASE</h4>
			<p style="margin:15px 0; color:#333;">Please click on a Case Number to view&nbsp;"Property Activity Report"</p>
				<div class="col-sm-5 caselist" style="padding:0 20px 0 0;">
					<table cellpadding="10" style="width:100%; margin:0 auto; border:1px solid #337ab7; font-size:12px;">
						<tr style="background: #337ab7; color:#fff;">
						<td style='padding:3px 5px; border-right:1px solid #fff;'>Case Type</td>
						<td style='padding:3px 5px; border-right:1px solid #fff;'>Case Number</td>
						<td style='padding:3px 5px;'>Date Closed</td>
						</tr>

						<?php foreach($casedata as $row){ $case_type=array_search($row[0],$casety); ?>
							<tr style='color:#333;'>
							<td style='border-bottom:1px solid #337ab7; border-right:1px solid #337ab7; padding:3px 5px;'><?php echo $row[0] ?></td>
							<td style='border-bottom:1px solid #337ab7; border-right:1px solid #337ab7; padding:3px 5px;'>

							<a href='#' onclick='return liveopencasedetail(<?php echo $apn ?>, <?php echo $row[1]?>, <?php echo isset($case_type) ? $case_type : '1'; ?>);'  style='color:DarkBlue;'><?php echo $row[1];?></a>

							<!--<a href='livecasedetail.php?apn=<?php echo $apn ?>&case_id=<?php echo $row[1]?>&case_type=<?php echo isset($case_type) ? $case_type : '1'; ?>' style='color:DarkBlue;'><?php echo $row[1];?></a>-->


							</td>
							<td style='padding:3px 5px; border-bottom:1px solid #337ab7;'><?php echo  $row[2] ;?></td>
							</tr>
						<?php }  ?>  

					</table>
				</div>

				<div class="col-sm-7 casedata">
					<p style="text-align:center;"><img src="images/loading.gif" class="wait" style="display:none;"></p>
					<!-- Insert case data-->
				</div>
                           <?php }else{  ?>
<div style="padding: 15px;border:1px solid #337ab7;">
                 <center style="color:red;" > This property is not available in this system </center></div>
                <?php } ?>

		</div>
	</div>   
	
	<script type="text/javascript">
	var slideIndex1 = 0;
	showSlides1();

	function showSlides1() {
		var i;
		var slides = document.getElementsByClassName("mySlides");
		var dots = document.getElementsByClassName("dot");
		for (i = 0; i < slides.length; i++) {
		   slides[i].style.display = "none";  
		}
		slideIndex1++;
		if (slideIndex1 > slides.length) {slideIndex1 = 1}    
		for (i = 0; i < dots.length; i++) {
			dots[i].className = dots[i].className.replace(" active", "");
		}
		slides[slideIndex1-1].style.display = "block";  
		dots[slideIndex1-1].className += " active";
		setTimeout(showSlides1, 3000); // Change image every 2 seconds
	}


	var slideIndex = 1;
	showSlides(slideIndex);

	function plusSlides(n) {
	  showSlides(slideIndex += n);
	}

	function currentSlide(n) {
	  showSlides(slideIndex = n);
	}

	function showSlides(n) {
	  var i;
	  var slides = document.getElementsByClassName("mySlides");
	  var dots = document.getElementsByClassName("dot");
	  if (n > slides.length) {slideIndex = 1}    
	  if (n < 1) {slideIndex = slides.length}
	  for (i = 0; i < slides.length; i++) {
		  slides[i].style.display = "none";  
	  }
	  for (i = 0; i < dots.length; i++) {
		  dots[i].className = dots[i].className.replace(" active", "");
	  }
	  slides[slideIndex-1].style.display = "block";  
	  dots[slideIndex-1].className += " active";
	}
</script>
</body>
</html>
