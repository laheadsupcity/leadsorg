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
<!--
  Override font size here since this is the only page using bootstrap 4 at the time of writing
  and the newer version of bootstrap sets font size differently.
  We can find a better way to set base font size once all pages are moved from bootstrap 3 to 4.
 -->
<html lang="en" style="font-size: 14px;">
  <head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Scraping</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/fontawesome/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="css/custom_search.css"/>
    <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script src="js/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="js/multiselect.js"></script>
    <script type="text/javascript" src="js/myscr.js"></script>
    <script type="text/javascript" src="js/custom_database_search/index.js"></script>
    <style>
      .active1{background:#337ab7!important;}
    </style>
  </head>
  <body>
    <div style="width:100%; float:left; margin:0;">
      <?php include('nav.php'); ?>
    </div>

    <div class="main-content main-content-fixed-width content-border h-100 mx-auto">
      <form action="lead_get_property.php" id="cdsearchform" method="post" >
        <div class="container pt-3">
          <div class="row">
            <div class="col-4">
              <div class="container p-0 mb-2">
                <div class="row">
                  <div class="col">
                    <div class="heading mb-2">Number of Units</div>
                    <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number" name="num_units_min" id="num_units_min" value=""></label>
                    <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number" name="num_units_max" id="num_units_max"value=""> </label>
                  </div>
                  <div class="col">
                    <div class="heading mb-2">Owner Occupied</div>
                    <label><span class="radiospan mr-2">Yes</span><input class="form-control form-control-sm" type="radio" name="is_owner_occupied" id="is_owner_occupied" value="Y" /></label>
                    <label><span class="radiospan mr-2">No</span><input class="form-control form-control-sm" type="radio" name="is_owner_occupied" id="is_owner_occupied" value="N" /></label>
                    <label><span class="radiospan mr-2">Don't Know</span><input class="form-control form-control-sm" type="radio" name="is_owner_occupied" id="is_owner_occupied" value="NA" checked/></label>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="heading mb-2">Select Zip Codes</div>
                <div class="container d-flex no-gutters p-0">
                  <div class="col pr-2">
                    <select name="zip" id="zip" class="form-control h-100 mrt" size="4" multiple="multiple">
                      <?php foreach ($zip as $key=>$zval) { ?>
                        <option value="<?php echo $zval; ?>" data-position="<?php echo $key; ?>"><?php echo $zval; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-2 pr-2">
                    <button type="button" id="zip_rightAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-right"></i></button>
                    <button type="button" id="zip_rightSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-right"></i></button>
                    <button type="button" id="zip_leftSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-left"></i></button>
                    <button type="button" id="zip_leftAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-left"></i></button>
                  </div>

                  <div class="col">
                    <select name="zip_codes" id="zip_to" class="h-100 form-control" size="4" multiple="multiple"></select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="heading mb-2">Select City</div>
                <div class="container d-flex no-gutters p-0">
                  <div class="col pr-2">
                    <select name="city" id="city" class="form-control h-100 mrt" size="4" multiple="multiple">
                      <?php foreach ($city as $ckey=>$cval) { ?>
                        <option value="<?php echo $cval; ?>" data-position="<?php echo $ckey; ?>"><?php echo $cval; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-2 pr-2">
                    <button type="button" id="city_rightAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-right"></i></button>
                    <button type="button" id="city_rightSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-right"></i></button>
                    <button type="button" id="city_leftSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-left"></i></button>
                    <button type="button" id="city_leftAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-left"></i></button>
                  </div>

                  <div class="col">
                    <select name="cities" id="city_to" class="h-100 form-control" size="4" multiple="multiple"></select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="heading mb-2">Zoning</div>
                <div class="container d-flex no-gutters p-0">
                  <div class="col pr-2">
                    <select name="zoning_list" id="zoning" class="form-control h-100 mrt" size="4" multiple="multiple">
                      <?php foreach ($zoning as $zkey=>$zval) { ?>
                        <option value="<?php echo $zval; ?>" data-position="<?php echo $zkey; ?>"><?php echo $zval; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-2 pr-2">
                    <button type="button" id="zoning_rightAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-right"></i></button>
                    <button type="button" id="zoning_rightSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-right"></i></button>
                    <button type="button" id="zoning_leftSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-left"></i></button>
                    <button type="button" id="zoning_leftAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-left"></i></button>
                  </div>

                  <div class="col">
                    <select name="zoning" id="zoning_to" class="h-100 form-control" size="4" multiple="multiple"></select>
                  </div>
                </div>
              </div>

              <div class="mb-2">
                <div class="heading mb-2">Tax Exemption Code</div>
                <div class="d-flex container no-gutters p-0">
                  <div class="col mr-2">
                    <select name="exemption" id="tax" class="form-control h-100 mrt" size="4" multiple="multiple">
                      <?php foreach ($exemption as $ekey=>$exval) { ?>
                        <option value="<?php echo $exval; ?>" data-position="<?php echo $ekey; ?>"><?php echo $exval; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-2 mr-2">
                    <button type="button" id="tax_rightAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-right"></i></button>
                    <button type="button" id="tax_rightSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-right"></i></button>
                    <button type="button" id="tax_leftSelected" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-left"></i></button>
                    <button type="button" id="tax_leftAll" class="btn btn-block btn-small btn-secondary small-search-button"><i class="fas fa-angle-double-left"></i></button>
                  </div>

                  <div class="col">
                    <select name="tax_exemption_codes" id="tax_to" class="form-control h-100" size="4" multiple="multiple"></select>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-2">
              <div class="heading mb-2">Number of Bedrooms</div>
              <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number" name="num_bedrooms_min" id="num_bedrooms_min" value=""></label>
              <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number" name="num_bedrooms_max" id="num_bedrooms_max" value=""> </label>
              <div class="heading mb-2">Number of Bathrooms</div>
              <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number" name="num_baths_min" id="num_baths_min" value=""></label>
              <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number" name="num_baths_max" id="num_baths_max" value=""> </label>
              <div class="heading mb-2">Number of Stories</div>
              <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number" name="num_stories_min" id="num_stories_min" value=""></label>
              <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number" name="num_stories_max" id="num_stories_max" value=""> </label>
              <div class="heading mb-2">Cost Per SQFT</div>
              <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number" name="cost_per_sq_ft_min" id="cost_per_sq_ft_min" value=""></label>
              <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number" name="cost_per_sq_ft_max" id="cost_per_sq_ft_max" value=""> </label>
            </div>

            <div class="col">
              <div class="container p-0">
                <div class="row mb-2">
                  <div class="col">
                    <div class="heading mb-2">Lot Area SQFT</div>
                    <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number numberdate" name="lot_area_sq_ft_min" id="lot_area_sq_ft_min" value=""></label>
                    <label><span class="mr-2">To</span> <input type="text" class="form-control form-control-sm number numberdate" name="lot_area_sq_ft_max" id="lot_area_sq_ft_max"value=""></label>

                    <div class="heading mb-2">Year Built Range</div>
                    <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number numberdate" name="year_built_min" id="year_built_min" value=""></label>
                    <label><span class="mr-2">To</span> <input type="text" class="form-control form-control-sm number numberdate" name="year_built_max" id="year_built_max"value=""></label>
                  </div>
                  <div class="col">
                    <div class="heading mb-2">Sale Price Range</div>
                    <label><span class="mr-2">From</span><input type="text" class="form-control form-control-sm number numberdate" name="sales_price_min" id="sales_price_min" value=""></label>
                    <label><span class="mr-2">To</span><input type="text" class="form-control form-control-sm number numberdate" name="sales_price_max" id="sales_price_max"value=""></label>

                    <div class="heading mb-2">Sale Date Range</div>
                    <label><span class="mr-2">From</span><input type="text" placeholder="&#x1F4C6;" class="form-control form-control-sm numberdate1 number" name="sales_date_from" id="sales_date_from" value="" autocomplete="off"></label>
                    <label><span class="mr-2">To</span><input type="text" placeholder="&#x1F4C6;"  class="form-control form-control-sm numberdate1 number" name="sales_date_to" id="sales_date_to" value="" autocomplete="off"></label>
                  </div>
                </div>
              </div>

              <div data-case-type-filter>
                <div class="heading mb-2">Open Case Type</div>
                <?php require('includes/case_type_filter.php'); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="scr4 pb-3 mt-5" style="text-align:center;float:none;overflow:hidden;width:100%;">
          <div class="scr4lead">
            <div class="heading mb-2">Select saved Filter</div>
            <input class="form-control mb-2 w-100 p-2" type="text" list="datalistname" autocomplete="off" placeholder="Save & Name this Filter" name="filtername" id="filtername" />
            <datalist id="datalistname">
              <?php foreach ($searchlist as $sekey=>$seacrval) { ?>
                <option value="<?php echo $seacrval; ?>" ><?php echo $seacrval; ?></option>
              <?php } ?>
            </datalist>
            <input type="hidden" id="searchid" value="" name="searchid" />
            <input type="submit" id="savesubmit" class="btn btn-block" onclick="return savefilter();" value="Save & Name this Filter" />

            <button type="submit" id="search" class="btn btn-block">Search</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
