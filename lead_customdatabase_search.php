<?php
	require_once('config.php');
	include('datafunction.php');
	$zip=getziplist();
	$city=getcitylist();
	$zoning=getzoninglist();
	$exemption=getexemptionlist();
	$casetype=getcasetypelist();
	$searchlist=getsearchlist();
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
	.table-fixed tbody {height: 200px!important;}
	</style>	  
</head>
 <body>
	<div style="width:100%; float:left; margin:0;">
		<?php  include('nav.php'); ?>
	</div>
	
	<div class="scr1 leadscr1" style="height:auto; padding-bottom:30px">
		<h4>Filter Database Search</h4>
		<form  action="lead_get_property.php" id="cdsearchform" method="post" >
        <div class="customsearchform">
				<div class="col-sm-5"><!---Start First coulmn----!-->
					<div class="col-sm-6">
						<p class="heading1">Number of Units</p>
						<p><label><span>From</span> <input type="text" class="number" name="nouf" id="nouf" value=""></label> <label><span>To</span> <input type="text" class="number" name="nout" id="nout"value=""> </label></p>				
					</div>

					<div class="col-sm-6">
						<p class="heading1">Owner Occupied</p>
						<p><label><span class="radiospan">Yes</span> <input type="radio" name="ooc"  id="ooc" value="Y"> </label> <label><span class="radiospan">No</span> <input type="radio" name="ooc" id="ooc" value="N"> </label></p>
					</div>

					<div class="scr2">
						<p class="heading1">Select Zip Codes</p>
						<div class="col-sm-5">
							<select name="zip[]" id="zip" class="form-control mrt" size="4" multiple="multiple">
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
							<select name="zipto[]" id="zip_to" class="form-control" size="4" multiple="multiple"></select>
						</div>
					</div>

					<div class="scr2">
						<p class="heading1">Select City</p>
						<div class="col-sm-5">
							<select name="city[]" id="city" class="form-control mrt" size="4" multiple="multiple">
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
							<select name="cityto[]" id="city_to" class="form-control" size="4" multiple="multiple"></select>
						</div>
					</div>

						<div class="scr2">
						<p class="heading1">Zoning</p>
						<div class="col-sm-5">
							<select name="zoning[]" id="zoning" class="form-control mrt" size="4" multiple="multiple">
							<?php foreach($zoning as $zkey=>$zval){  ?>
                                <option value="<?php echo $zval; ?>" data-position="<?php echo $zkey; ?>"><?php echo $zval; ?></option>
                             
                            
                            <?php } ?>
							</select>
						</div>

						<div class="col-sm-2">
							<button type="button" id="zoning_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
							<button type="button" id="zoning_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
							<button type="button" id="zoning_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
							<button type="button" id="zoning_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
						</div>

						<div class="col-sm-5">
							<select name="zoningto[]" id="zoning_to" class="form-control" size="4" multiple="multiple"></select>
						</div>
					</div>
				</div>  

				 <!---Start second  coulmn------>

				<div class="col-sm-2"> 
					<p class="heading1">Number of Bedrooms</p>
					<p><label><span>From</span> <input type="text" class="number" name="nbedf"  id="nbedf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nbedt" id="nbedt" value=""> </label></p>
					<p class="heading1">Number of Bathrooms</p>
					<p><label><span>From</span> <input type="text" class="number" name="nbathf" id="nbathf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nbatht" id="nbatht" value=""> </label></p>
					<p class="heading1">Number of Stories</p>
					<p><label><span>From</span> <input type="text" class="number" name="nstrf" id="nstrf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="nstrt" id="nstrt" value=""> </label></p>
					<p class="heading1">Cost Per SQFT</p>
					<p><label><span>From</span> <input type="text" class="number" name="cpsf" id="cpsf" value=""> </label> <label><span>To</span> <input type="text" class="number" name="cpst" id="cpst" value=""> </label></p>
				</div>

			<!---Start third  coulmn------>
				<div class="col-sm-5">  
						<div class="col-sm-6">
							<p class="heading1">Lot Area SQFT</p>
							<p><label><span>From</span> <input type="text" class="number numberdate" name="lasqf" id="lasqf" value=""></label> <label><span>To</span> <input type="text" class="number numberdate" name="lasqt" id="lasqt"value=""> </label></p>
							<p class="heading1">Sale Price Range</p>
							<p><label><span>From</span> <input type="text" class="number numberdate" name="sprf" id="sprf" value=""></label> <label><span>To</span> <input type="text" class="number numberdate" name="sprt" id="sprt"value=""> </label></p>
						</div>

						<div class="col-sm-6">
							<p class="heading1">Year Build Range</p>
							<p><label><span>From</span> <input type="text" class="number numberdate" name="ybrf" id="ybrf" value=""></label> <label><span>To</span> <input type="text" class="number numberdate" name="ybrt" id="ybrt"value=""> </label></p> 
							<p class="heading1">Sale Date Range</p>
							<p><label><span>From</span> <input type="text" placeholder="&#x1F4C6;"   class="numberdate1 number" name="sdrf" id="sdrf" value="" autocomplete="off"></label> <label><span>To</span> <input type="text" placeholder="&#x1F4C6;"   class="numberdate1 number" name="sdrt" id="sdrt" value="" autocomplete="off"> </label></p>
						</div>


						<div class="scr3">
						<p class="heading1">Tax Exemption Code</p>
						<div class="col-sm-5">
							<select name="exemption[]" id="tax" class="form-control mrt" size="4" multiple="multiple">
							<?php foreach($exemption as $ekey=>$exval){  ?>
                                <option value="<?php echo $exval; ?>" data-position="<?php echo $ekey; ?>"><?php echo $exval; ?></option>
                             
                            
                            <?php } ?>
							</select>
						</div>


						<div class="col-sm-2">
							<button type="button" id="tax_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
							<button type="button" id="tax_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
							<button type="button" id="tax_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
							<button type="button" id="tax_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
						</div>

						<div class="col-sm-5">
							<select name="exemptionto[]" id="tax_to" class="form-control" size="4" multiple="multiple"></select>
						</div>
					</div>
					
					<div class="scr3">
						<p class="heading1">Open Case Type</p>
						<div class="col-sm-12">
							<table class="table-fixed" style="width:100%; border:1px solid #ddd;">
							<thead>
							<tr style="background:#337ab7">
							<th class="col-sm-1"><!--<input type="checkbox" name="all" value="" style="width:15px; height:15px; top:1px; position:relative;">-->#</th>
							<th class="col-sm-4" style="font-size:11px; font-weight:600; padding:5px 0; text-align:center;">Open Case Type</th>
							<th class="col-sm-3" style="font-size:11px; font-weight:600; padding:5px 0; text-align:right;">Prior to</th>
							<th class="col-sm-4" style="font-size:11px; font-weight:600; padding:5px 10px; text-align:right;">Open for<br> #of days</th>
							</tr>
							</thead>
							<?php foreach($casetype as $ctkey=>$ctval){  ?>
							<tr>
							<td class="col-sm-1" style="font-size:12px; border-bottom:1px solid #ddd; height:45px; "><input type="checkbox" data-id="<?php echo $ctkey;  ?>" id="opcty<?php echo $ctkey;  ?>" class="optype" onclick="return checkcasetype(<?php echo $ctkey;  ?>);" name="opencasetype[]" value="<?php echo $ctval; ?>" style="width:15px; height:15px; top:1px; position:relative;"></td>
							<td class="col-sm-6" style="font-size:12px; border-bottom:1px solid #ddd; height:45px; position:relative; "><?php echo $ctval; ?></td>
							<td class="col-sm-3" style="font-size:12px; border-bottom:1px solid #ddd; height:45px;"><input type="text" autocomplete="off" onblur="return openctime(<?php echo $ctkey;  ?>);"  name="cdate[]" class="codate<?php echo $ctkey; ?> cdate" disabled="disabled" value="" style="width:100%; position:relative; top:3px;"></td>
							<td class="col-sm-2" style="font-size:12px; border-bottom:1px solid #ddd; height:45px;"><input type="text" onblur="return opendtime(<?php echo $ctkey;  ?>);"  name="ctime[]" class="cotime<?php echo $ctkey; ?> cnumber" disabled="disabled" value="" style="width:100%; position:relative; top:3px;"></td>
							</tr>
							<?php } ?>
							</table>
							   
						</div>

					</div>
				</div>

				<div class="scr4" style="text-align:center;float:none;overflow:hidden;width:100%;">
					<div class="scr4lead" >
						<p class="heading1">Select saved Filter</p>
						<p><input type="text" list="datalistname" autocomplete="off" placeholder="Save & Name this Filter" name="filtername" id="filtername"  />

						<datalist id="datalistname">
						<?php foreach($searchlist as $sekey=>$seacrval){  ?>
							<option value="<?php echo $seacrval;?>"  ><?php echo $seacrval;?></option>							
						<?php } ?>
						</datalist></p>
						<input type="hidden" id="searchid" value="" name="searchid" />
                        <input type="submit" id="savesubmit" class="btn btn-block" onclick="return savefilter();" value="Save & Name this Filter" />
						<!-- <button type="submit" id="savesubmit" class="btn btn-block">Save & Name this Filter</button> -->

						<button type="submit" id="search" class="btn btn-block">Search</button>
					</div>
				</div>
        </div>
        </form>
     
	</div>
	</body>
</html>
