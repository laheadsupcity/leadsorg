<?php
require_once('config.php');
include('datafunction.php');
$zip=getziplistforschedule();
$city=getcityschlist();
$citycount= count($city);
$zipcount= count($zip);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus�">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Scrapping</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!--<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="js/moment.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>                       
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" /> 
 <!--<script src="js/jquery.datetimepicker.full.min.js"></script> -->
<script type="text/javascript" src="js/multiselect.js"></script>
<script type="text/javascript" src="js/myscr.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/style.css">
<style>.active3{background:#337ab7!important;}
#datetimepicker6{background:url(images/cal.png) no-repeat; background-size:20px;     background-position: right; border:1px solid #000; padding:3px 5px;}
.btn-block {
    display: inline !important;
    width: auto;
    background: #0070c0;
    color: #fff;
    padding: 5px;
    /* width: 100%; */
}

#schbatchname {
    width: 100%;
    height: 40px;
    border: 1px solid #000;
    padding: 5px;
    margin-bottom: 15px;
}


</style>
</head>
<body>
 <div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
	<div class="scr1">
		<h4>Define the listings to schedule a scrap</h4>
		<form  method="post" action="lead_get_scheduledata.php" name="upload_excel" id="upload_csv" enctype="multipart/form-data">
		  <div style="width:overflow:hidden; width:100%;">
			<div class="col-sm-10">
					<div class="col-sm-3">
						<div class="col-sm-12">
							<p class="heading1">Number of Units</p>
							<p><label><span>From</span> <input type="text" class="number" name="nouf" id="nouf" value=""></label> <label><span>To</span> <input type="text" class="number" name="nout" id="nout"value=""> </label>
							</p>
						</div>
					</div>

					<div class="col-sm-6" style="padding:25px 0 0;">
						<table>
							<!--<tr><td><label><span class="lblspan">Include Single family</span></label></td> <td><input type="checkbox" id="fmlytype" name="fmlytype" value="include Single family"></td> </tr>
							<tr><td><label><span class="lblspan">Single family only</span></label></td> <td> <input type="checkbox" id="sfmlytype" name="sfmlytype" value="single family only"></td> </tr>-->
						</table>
					</div>
					
					<div class="scr2">
						<div class="col-sm-6">
							<p class="heading1">Select Zip Codes</p>
							<div class="col-sm-5">
								<select  name="zip[]" id="zip" class="form-control" size="5" multiple="multiple">
								<?php foreach($zip as $key=>$zval){  ?>
                                <option value="<?php echo $zval; ?>" data-position="<?php echo $key; ?>"><?php echo $zval; ?></option>
                             
                            
                            <?php } ?>
								</select>
							</div>


							<div class="col-sm-2">
								<button type="button" id="zip_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
								<button type="button" id="zip_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
								<button type="button" id="zip_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
								<button type="button" id="zip_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
							</div>

							<div class="col-sm-5">
								<select name="zipto[]" id="zip_to" class="form-control" size="5" multiple="multiple"></select>
							</div>
						</div>

						<div class="col-sm-6">
							<p class="heading1">Select City</p>
							<div class="col-sm-5">
								<select name="city[]" id="city" class="form-control" size="5" multiple="multiple">
								<?php foreach($city as $ckey=>$cval){  ?>
                                <option value="<?php echo $cval; ?>" data-position="<?php echo $ckey; ?>"><?php echo $cval; ?></option>
                             
                            
                            <?php } ?>
								</select>
							</div>


							<div class="col-sm-2">
								<button type="button" id="city_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
								<button type="button" id="city_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
								<button type="button" id="city_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
								<button type="button" id="city_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
							</div>

							<div class="col-sm-5">
								<select name="cityto[]" id="city_to"  class="form-control" size="5" multiple="multiple"></select>
							</div>
						</div>
					</div>
			</div>

				<div class="col-sm-2">
						<p class="heading1">Number of Bedrooms</p>
						<p><label><span>From</span> <input type="text" class="number" name="nbedf"  id="nbedf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nbedt" id="nbedt" value=""> </label></p>
						<p class="heading1">Number of Bathrooms</p>
						<p><label><span>From</span> <input type="text" class="number" name="nbathf" id="nbathf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nbatht" id="nbatht" value=""> </label></p>
						<p class="heading1">Number of Stories</p>
						<p><label><span>From</span> <input type="text" class="number" name="nstrf" id="nstrf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nstrt" id="nstrt" value=""> </label></p>
					</div>

			</div>

			
					
			<div class="scr5" style="float:left; width:100%; text-align:center;">
						<div class="col-sm-3" style="padding-top: 97px;">					
						<div class="fileupload" style="display:none; margin:0 0 20px;"><input type="file" name="import_list" id="import_list" style="margin:0 0 10px 65px"/>  <button type="submit" id="btnUpload" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading..." style="padding:8px 33px">Upload Now</button></div>
						<p class="wait" style="display:none;"><img src="images/loading.gif" alt="" style="width:100px;"></p>
					
											
						<p class="para1" style="margin:0 0 20px"><a href="#" class="btn btn-block importbtn" style="padding:10px 23px;">Import New List</a></p>
						<button type="submit" id="searchn" class="btn btn-block" style="padding:8px 33px">Search Now</button>
						</div>
                        <div class="col-sm-6" style="padding-top: 60px;">
						<p class="heading1">Select Date and Time to Run Scrap</p>
						
						<!--<div class="controls" style="position: relative"><input type="text" id="datetimepicker6" name="scheduledate"/></div>-->
						<div class="controls" style="position: relative"><input type="hidden" name="schedulername" id="schedulername" value="" /><input type="text" autocomplete="off" id="datetimepicker6" name="scheduledate" style="z-index:100px; cursor:pointer;"/></div>
						<input type="hidden" name="searchsubmit" id="searchsubmit"  />
						<?php if($citycount >0 || $zipcount > 0){?>
						<p style="margin:15px 0;"><button type="submit" id="submits" class="btn btn-block" style="padding:6px 23px">Submit</button></p>
						<?php } else{?>
						<p style="margin:15px 0;"><button type="submit" id="" class="btn btn-block" style="padding:6px 23px" disabled>Submit</button></p>
						<?php } ?>
                        </div>						
				</div>
		</form>

		<div id="overlay1">
			<div style="color: green; background: #fff; padding: 0px 0px 2px 0px;width: 400px; margin: 8% auto; border-radius: 2px;font-size: 16px;">
			<h1 style="background:#337ab7; color:#fff; margin:0px; padding:7px; text-align:center; font-size:17px;">Import Status</h1>
				<p class="msg" style="color:green; text-align:center; padding:10px 0;"></p>
				<p href="#" class="uploadcloseimg" style="background:#337ab7; color:#fff; padding:6px; background-radius:2px; width:100%; text-align:center; width:150px; margin:0 auto; cursor:pointer;">OK</p>
			</div>
		</div>




		<input type="hidden" id="reschid" value="<?php echo isset($_REQUEST['schid']) ? $_REQUEST['schid'] : ''; ?>" />
	</div>
</body>
</html>

<div id="overlay">
<div id="batchform">
<div class="closeicon"><a href="#" class="closeimg"><img src='images/close.png'></a></div>

<label style="color:#000;">Scheduled Search Name</label><br/><input type="text" name="schbatchname" class="schname"  id="schbatchname" placeholder="Enter Scheduled Search Name">
<button type="submit" id="scdsearch" class="btn btn-block" style="padding:5px 10px;">Submit</button> &nbsp; <button type="submit" id="scdclosebtn" class="btn btn-block" style="padding:5px 10px;">Close</button>
<p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Scheduled Search created successfully</p>

</div>
</div>
<script>
jQuery(document).ready(function(){
	jQuery(".importbtn").click(function(e){
		jQuery(".fileupload").show();
		jQuery(".para1").hide();
	});
});


</script>
