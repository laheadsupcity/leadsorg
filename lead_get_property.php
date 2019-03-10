<?php
  require_once('config.php');
  require_once('CustomDatabaseSearch.php');
?>
<!doctype html>
<html lang="en" style="font-size: 14px;">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="css/fontawesome/all.min.css" />
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.js"></script>
  <script src="js/jquery.datetimepicker.full.min.js"></script>
  <script type="text/javascript" src="js/multiselect.js"></script>
  <script type="text/javascript" src="js/myscr.js"></script>
  <script type="text/javascript" src="js/custom_database_search/results.js"></script>
  <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
  <link rel="stylesheet" type="text/css" href="css/custom_search_results.css"/>
  <style>
    .active1{background:#337ab7!important;}
  </style>
  <meta name="format-detection" content="telephone=no"/>
</head>
<body>
  <div style="width:100%; float:left; margin:0;">
    <?php
      include('nav.php');
      if (isset($_GET['searchid'])) {
          unset($_SESSION['SearchFormData']);
          $num_units_min=isset($_GET['num_units_min']) ? $_GET['num_units_min'] : '';
          $num_units_max=isset($_GET['num_units_max']) ? $_GET['num_units_max'] : '';

          $zip=isset($_GET['zip_codes']) ? $_GET['zip_codes'] : '';
          $cities=isset($_GET['cities']) ? $_GET['cities'] : '';
          $zoning_to=isset($_GET['zoning']) ? $_GET['zoning'] : '';
          $tax_exemption_codes=isset($_GET['tax_exemption_codes']) ? $_GET['tax_exemption_codes'] : '';

          $num_bedrooms_min=isset($_GET['num_bedrooms_min']) ? $_GET['num_bedrooms_min'] : '';
          $num_bedrooms_max=isset($_GET['num_bedrooms_max']) ? $_GET['num_bedrooms_max'] : '';

          $num_baths_min=isset($_GET['num_baths_min']) ? $_GET['num_baths_min'] : '';
          $num_baths_max=isset($_GET['num_baths_max']) ? $_GET['num_baths_max'] : '';

          $num_stories_min=isset($_GET['num_stories_min']) ? $_GET['num_stories_min'] : '';
          $num_stories_max=isset($_GET['num_stories_max']) ? $_GET['num_stories_max'] : '';

          $cost_per_sq_ft_min=isset($_GET['cost_per_sq_ft_min']) ? $_GET['cost_per_sq_ft_min'] : '';
          $cost_per_sq_ft_max=isset($_GET['cost_per_sq_ft_max']) ? $_GET['cost_per_sq_ft_max'] : '';

          $lot_area_sq_ft_min=isset($_GET['lot_area_sq_ft_min']) ? $_GET['lot_area_sq_ft_min'] : '';
          $lot_area_sq_ft_max=isset($_GET['lot_area_sq_ft_max']) ? $_GET['lot_area_sq_ft_max'] : '';

          $sales_price_min=isset($_GET['sales_price_min']) ? $_GET['sales_price_min'] : '';
          $sales_price_max=isset($_GET['sales_price_max']) ? $_GET['sales_price_max'] : '';

          $year_built_min=isset($_GET['year_built_min']) ? $_GET['year_built_min'] : '';
          $year_built_max=isset($_GET['year_built_max']) ? $_GET['year_built_max'] : '';

          $sales_date_from=isset($_GET['sales_date_from']) ? $_GET['sales_date_from'] : '';
          $sales_date_to=isset($_GET['sales_date_to']) ? $_GET['sales_date_to'] : '';

          $is_owner_occupied=isset($_GET['is_owner_occupied']) ? $_GET['is_owner_occupied'] : '';

          $case_types = isset($_GET['case_types']) ? $_GET['case_types'] : '';

          $search_params=array(
            'num_units_min'=>$num_units_min,
            'num_units_max'=>$num_units_max,
            'zip'=>$zip,
            'city'=>$cities,
            'zoning'=>$zoning_to,
            'exemption'=>$tax_exemption_codes,
            'casetype'=>$case_types,
            'num_bedrooms_min'=>$num_bedrooms_min,
            'num_bedrooms_max'=>$num_bedrooms_max,
            'num_baths_min'=>$num_baths_min,
            'num_baths_max'=>$num_baths_max,
            'num_stories_min'=>$num_stories_min,
            'num_stories_max'=>$num_stories_max,
            'cost_per_sq_ft_min'=>$cost_per_sq_ft_min,
            'cost_per_sq_ft_max'=>$cost_per_sq_ft_max,
            'lot_area_sq_ft_min'=>$lot_area_sq_ft_min,
            'lot_area_sq_ft_max'=>$lot_area_sq_ft_max,
            'sales_price_min'=>$sales_price_min,
            'is_owner_occupied'=>$is_owner_occupied,
            'sales_price_max'=>$sales_price_max,
            'year_built_min'=>$year_built_min,
            'year_built_max'=>$year_built_max,
            'sales_date_from'=>$sales_date_from,
            'sales_date_to'=>$sales_date_to
          );

          $_SESSION['SearchFormData']  = $search_params;
      } elseif (isset($_SESSION['SearchFormData'])) {
          $search_params = $_SESSION['SearchFormData'];
      } else {
          header("LOCATION:lead_customdatabase_search.php");
      }

      $current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
      $num_rec_per_page = isset($_REQUEST['num_rec_per_page']) ? $_REQUEST['num_rec_per_page'] : 1000;

      $searcher = new CustomDatabaseSearch($search_params);
      $result = $searcher->getResults($num_rec_per_page, $current_page);
      $total_records = $searcher->getResultCount();
    ?>
  </div>
  <div class="main-content mx-auto">
    <div>
      <?php  if (true) { ?>
        <div class="d-flex justify-content-between p-2">
          <div>
            <span class="font-weight-bold pr-1">Total Records:</span> <?php echo $total_records; ?>
          </div>
          <div>
            <span class="font-weight-bold pr-1">No. of Records Per Page:</span>
            <select id='num_rec_per_page' name="num_rec_per_page">
              <option <?php echo ($num_rec_per_page==10)?"selected='selected'":''; ?> value='10'>10</option>
              <option <?php echo ($num_rec_per_page==25)?"selected='selected'":''; ?> value='25'>25</option>
              <option <?php echo ($num_rec_per_page==50)?"selected='selected'":''; ?> value='50'>50</option>
              <option <?php echo ($num_rec_per_page==100)?"selected='selected'":''; ?> value='100'>100</option>
              <option <?php echo ($num_rec_per_page==250)?"selected='selected'":''; ?> value='250'>250</option>
              <option <?php echo ($num_rec_per_page==500)?"selected='selected'":''; ?> value='500'>500</option>
              <option <?php echo ($num_rec_per_page==1000)?"selected='selected'":''; ?> value='1000'>1000</option>
              <option <?php echo ($num_rec_per_page==5000)?"selected='selected'":''; ?> value='5000'>5000</option>
            </select>
          </div>
        </div>

        <table class="table table-borderless table-striped fixed-head-table border">
          <thead class="border-bottom">
            <tr>
              <th class="checkbox-col">
                <input type="checkbox" id="checkAll" name="all">
              </th>
              <th class="apn-col">Parcel #</th>
              <th class="address-col">Address</th>
              <th class="owner-col">Owner Name</th>
              <th class="units-col">Units</th>
              <th class="beds-col">Beds</th>
              <th class="baths-col">Baths</th>
              <th class="lot-sqft-col">Lot SQFT</th>
              <th class="year-built-col">Year Built</th>
              <th class="sale-date-col">Sale Date</th>
              <th class="sale-price-col">Sale Price</th>
              <th class="options-col"></th>
            </tr>
          </thead>
          <tbody style="height: 500px;">
            <?php
              foreach ($result as $key=>$val) { ?>
                <tr class="border-bottom">
                  <td class="checkbox-col"><input type="checkbox" class="apncheck chk"  value="<?php echo $val['parcel_number']; ?>" /></td>
                  <td class="apn-col"><?php echo $val['parcel_number']; ?></td>
                  <td class="address-col"><?php echo $val['street_number'].','.$val['street_name'].',<br/> '.$val['site_address_city_state'].',
         '.$val['site_address_zip']; ?></td>
                  <td class="owner-col"><?php echo $val['owner_name2']; ?></td>
                  <td class="units-col"><?php echo $val['number_of_units']; ?></td>
                  <td class="beds-col"><?php echo $val['bedrooms']; ?></td>
                  <td class="baths-col"><?php echo $val['bathrooms']; ?></td>
                  <td class="lot-sqft-col"><?php echo $val['lot_area_sqft']; ?></td>
                  <td class="year-built-col"><?php echo $val['year_built']; ?></td>
                  <td class="sale-date-col">
                    <?php
                      if ($val['sales_date']!='0000-00-00') {
                          echo date('m/d/Y',
         strtotime($val['sales_date']));
                      } else {
                          echo "";
                      } ?>
                  </td>
                  <td class="sale-price-col"><?php echo $val['sales_price']; ?></td>
                  <td class="options-col">
                    <div>
                      <a target="_blank" class="br-1 pr-1 mr-1" href="lead_property_detail.php?apn=<?php echo $val['parcel_number']; ?>"><i class="text-secondary fas fa-chevron-circle-right"></i></a>
                    </div>
                    <div>
                      <a href="lead_update_customtask.php?editid=<?php echo $val['id']; ?>" target="_blank"><i class="text-secondary fas fa-edit"></i></a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
        </table>

        <?php require('includes/search_results/pagination.php'); ?>
      <?php } ?>
    </div>
    <div class="mt-3">
      <input type="hidden" value="" id="ckeckvall" />
      <div>
        <button type="submit" id="batch" class="btn btn-primary">Create Lead batch </button>
        <button type="submit" id="expcsvbtn" class="btn btn-primary">Export selected</button>
        <button type="submit" data-toggle="modal" data-target="#addToFavoritesModal" class="btn btn-primary">Add to favorites</button>
      </div>
    </div>
    <div id="overlay">
        <div id="batchform">
         <div class="closeicon"><a href="#" class="closeimg"><img src='images/close.png'></a></div>
          <form method="post" action="#">
            <label style="color:#000;">Batch Name</label><br/><input type="text" name="batchname" id="batchname" placeholder="Enter Batch Name">
            <button type="submit" id="batchsubmit" class="btn btn-block leadbatch" style="padding:5px 10px;">Submit</button> &nbsp; <button type="submit" id="closebtn" class="btn btn-block" style="padding:5px 10px;">Close</button>
            <p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Batch created successfully</p>
            <p style="text-align:left; padding:10px; color:red; display:none;" class="errormsg">Please enter batch name</p>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include('includes/add_to_favorites_modal.php')?>
 </body>
</html>
