
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
.active{background:#337ab7!important;}
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
</style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<div class="scr1" style="border:1px solid #0070c0; height:auto;">
        <h4>PROPERTY INFORMATION</h4>
   <div style="padding: 10px;">
	<?php
		require_once('config.php');
		$a=isset($_POST['streetno']) ? $_POST['streetno'] : '';
		$b=isset($_POST['streetname'] ) ? $_POST['streetname'] : '';
		$c=isset($_POST['apn']) ? $_POST['apn'] : '';
		$d=isset($_POST['casenum']) ? $_POST['casenum'] : '';
		if($a && !$b && !$c && !$d ){
		$db = Database::instance();
		$query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_number=$a";
		$db->query($query);
		$result=$db->result_array();
		$count=count($result);
	?>
	<span id="MainContent_title" style="color:#004000;background-color:White;font-size:Large;">Search Result</span>
	<?php
		if($count>0){
			echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
		}
		else {
			echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
		}
	?>
<table bgcolor="white">
<tbody>
<tr valign="top">
<td> <table id="MainContent_tblpropSummary">
<tbody><tr>
<td>
<table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:White;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
<tbody><tr style="color:White;background-color:#3399FF;font-weight:bold;height:30px;">
                <td>APN</td><td>Address</td>
                <?php $tcount=count($result);  if($tcount>0) { ?>
                <?php foreach($result as $row){ ?>
			</tr><tr style="color:Black;background-color:White;">
				<td><a target="_blank" href="lead_property_detail.php?apn=<?php echo $row['parcel_number'];?>"  ><?php echo $row['parcel_number']; ?></a></td><td><?php echo  $row['street_number'].' , '.$row['street_name'].', '.$row['site_address_city_state'].' '.$row['site_address_zip'] ?></td>
			</tr>
                <?php } } else {

                   echo  '<tr><td colspan="2">No record found</td></tr>';
                } ?>
		</tbody></table></td>
	</tr>
</tbody></table>

                            </td>
                        </tr>

                    </tbody></table>
    <?php

}else if(!$a && $b && !$c && !$d ){

    $db = Database::instance();
    $query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_name like ('%$b')";
    $db->query($query);
    $result=$db->result_array();
    $count=count($result);

    ?>
<span id="MainContent_title" style="color:#004000;background-color:White;font-size:Large;">Search Result</span>
<?php
if($count>0){

echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
}
else {

    echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
}
?>
<table bgcolor="white">
<tbody>
<tr valign="top">
<td> <table id="MainContent_tblpropSummary">
<tbody><tr>
<td>
<table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:White;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
<tbody><tr style="color:White;background-color:#3399FF;font-weight:bold;height:30px;">
                <td>APN</td><td>Address</td>
                <?php $tcount=count($result);  if($tcount>0) { ?>
                <?php foreach($result as $row){ ?>
			</tr><tr style="color:Black;background-color:White;">
				<td><a target="_blank" href="lead_property_detail.php?apn=<?php echo $row['parcel_number'];?>"  ><?php echo $row['parcel_number']; ?></a></td><td><?php echo  $row['street_number'].' , '.$row['street_name'].', '.$row['site_address_city_state'].' '.$row['site_address_zip'] ?></td>
			</tr>
                <?php } } else {

                   echo  '<tr><td colspan="2">No record found</td></tr>';
                } ?>
		</tbody></table></td>
	</tr>
</tbody></table>

                            </td>
                        </tr>

                    </tbody></table>
    <?php
 }
 else if(!$a && !$b && $c && !$d ){

    header("LOCATION:lead_property_detail.php?apn=$c");


 }
 else if(!$a && !$b && !$c && $d ){
    $db = Database::instance();
    $query = "select  APN,case_id from property_cases WHERE  case_id =$d GROUP BY `APN`";
    $db->query($query);
    $result=$db->result_array();
    $apn=isset($result[0]['APN']) ? $result[0]['APN'] : '';
    $caseid=isset($result[0]['case_id']) ? $result[0]['case_id'] : '';
    header("LOCATION:lead_property_detail.php?apn=$apn&case_id=$caseid");

 }
 else if($a && $b && !$c && !$d ){
   $db = Database::instance();
    $query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_number=$a AND street_name like ('%$b')";
    $db->query($query);
    $result=$db->result_array();
    $count=count($result);
    ?>
<span id="MainContent_title" style="color:#004000;background-color:White;font-size:Large;">Search Result</span>
<?php
if($count>0){

echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
}
else {

    echo '<span id="MainContent_lblMessage" style="color:Red;"><p>'.$count.' Properties matching your search criteria found:</p></span>';
}
?>
<table bgcolor="white">
<tbody>
<tr valign="top">
<td> <table id="MainContent_tblpropSummary">
<tbody><tr>
<td>
<table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:White;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
<tbody><tr style="color:White;background-color:#3399FF;font-weight:bold;height:30px;">
                <td>APN</td><td>Address</td>
                <?php $tcount=count($result);  if($tcount>0) { ?>
                <?php foreach($result as $row){ ?>
			</tr><tr style="color:Black;background-color:White;">
				<td><a target="_blank" href="lead_property_detail.php?apn=<?php echo $row['parcel_number'];?>"  ><?php echo $row['parcel_number']; ?></a></td><td><?php echo  $row['street_number'].' , '.$row['street_name'].', '.$row['site_address_city_state'].' '.$row['site_address_zip'] ?></td>
			</tr>
                <?php } } else {

                   echo  '<tr><td colspan="2">No record found</td></tr>';
                } ?>
		</tbody></table></td>
	</tr>
</tbody></table>

                            </td>
                        </tr>

                    </tbody></table>
    <?php

 }
 else if($a && !$b && $c && !$d ){
  $db = Database::instance();
    $query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_number=$a AND parcel_number= $c";
    $db->query($query);
    $result=$db->result_array();
    $count=count($result);
    $apn=isset($result[0]['parcel_number']) ? $result[0]['parcel_number'] : '';

    header("LOCATION:lead_property_detail.php?apn=$apn");
 }
 else if(!$a && !$b && $c && $d ){
    $db = Database::instance();
    $query = "SELECT DISTINCT APN,case_id FROM property_cases where case_id=$d  and  APN=$c";
    $db->query($query);
    $result=$db->result_array();
    $apn=isset($result[0]['APN']) ? $result[0]['APN'] : '';
    $caseid=isset($result[0]['case_id']) ? $result[0]['case_id'] : '';
    $count=count($result);
    if($count>0){

        header("LOCATION:lead_property_detail.php?apn=$apn&case_id=$caseid");

    }
    else { ?>

        <div style="padding: 15px;border:1px solid #337ab7;">
                 <center style="color:red;" > This property is not available in this system </center></div>
   <?php }
 }
 else if($a && !$b && !$c && $d ){
    header("LOCATION:index.php");

 }
 else if(!$a && $b && $c && !$d ){
     $db = Database::instance();
    $query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_name like ('%$b') AND parcel_number= $c";
    $db->query($query);
    $result=$db->result_array();
    $count=count($result);
    $apn=isset($result[0]['parcel_number']) ? $result[0]['parcel_number'] : '';

    header("LOCATION:lead_property_detail.php?apn=$apn");

 }
 else if(!$a && $b && !$c && $d ){
    header("LOCATION:index.php");

 }
 else if($a && $b && $c && !$d ){
    $db = Database::instance();
    $query = "select  parcel_number,street_number,street_name,site_address_zip,site_address_city_state from property WHERE impstatus=0 AND street_number=$a AND street_name like ('%$b') AND parcel_number= $c";
    $db->query($query);
    $result=$db->result_array();
    $count=count($result);
    $apn=isset($result[0]['parcel_number']) ? $result[0]['parcel_number'] : '';

    header("LOCATION:lead_property_detail.php?apn=$apn");

 }
 else if(!$a && $b && $c && $d ){
    header("LOCATION:index.php");

 }
 else if($a && !$b && $c && $d ){
    header("LOCATION:index.php");

 }
 else if($a && $b && !$c && $d ){
    header("LOCATION:index.php");

 }
 else if($a && $b && $c && $d ){
    header("LOCATION:index.php");

 }
 else {


 }

?>
</div>
</div>
</body>
</html>
