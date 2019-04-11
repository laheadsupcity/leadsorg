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
  <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
  <link rel="stylesheet" type="text/css" href="css/custom_search_results.css"/>

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/custom_database_search/results.js"></script>
  <script type="text/javascript" src="js/custom_database_search/editable_fields.js"></script>
  <script type="text/javascript" src="js/custom_database_search/actions.js"></script>
  <script type="text/javascript" src="js/add_favorite_properties.js"></script>
  <style>
    .active1{background:#337ab7!important;}
  </style>
  <meta name="format-detection" content="telephone=no"/>
</head>
<body>
  <?php
    include('nav.php');

    $search_params=array(
      'num_units_min' => $_GET['num_units_min'],
      'num_units_max' => $_GET['num_units_max'],
      'zip' => $_GET['zip_codes'],
      'city' => $_GET['cities'],
      'zoning' => $_GET['zoning'],
      'exemption' => $_GET['tax_exemption_codes'],
      'casetype' => $_GET['case_types'],
      'num_bedrooms_min' => $_GET['num_bedrooms_min'],
      'num_bedrooms_max' => $_GET['num_bedrooms_max'],
      'num_baths_min' => $_GET['num_baths_min'],
      'num_baths_max' => $_GET['num_baths_max'],
      'num_stories_min' => $_GET['num_stories_min'],
      'num_stories_max' => $_GET['num_stories_max'],
      'cost_per_sq_ft_min' => $_GET['cost_per_sq_ft_min'],
      'cost_per_sq_ft_max' => $_GET['cost_per_sq_ft_max'],
      'lot_area_sq_ft_min' => $_GET['lot_area_sq_ft_min'],
      'lot_area_sq_ft_max' => $_GET['lot_area_sq_ft_max'],
      'sales_price_min' => $_GET['sales_price_min'],
      'sales_price_max' => $_GET['sales_price_max'],
      'is_owner_occupied' => $_GET['is_owner_occupied'],
      'year_built_min' => $_GET['year_built_min'],
      'year_built_max' => $_GET['year_built_max'],
      'sales_date_from' => $_GET['sales_date_from'],
      'sales_date_to' => $_GET['sales_date_to']
    );

    $current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
    $num_rec_per_page = isset($_REQUEST['num_rec_per_page']) ? $_REQUEST['num_rec_per_page'] : 1000;

    $searcher = new CustomDatabaseSearch($search_params);
    $properties = $searcher->getResults($num_rec_per_page, $current_page);
    $matching_cases = $searcher->getMatchingCasesForProperties();
    $related_properties_counts = $searcher->getRelatedPropertiesCounts();
    $total_records = $searcher->getResultCount();
  ?>

  <div class="main-content mx-auto pl-4 pr-4">
    <?php if (!empty($properties)) { ?>
      <div class="d-flex justify-content-between p-2">
        <div>
          <span class="font-weight-bold pr-1">Total Records:</span> <?php echo $total_records; ?>
        </div>
        <div>
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
          <div class="mt-2" hidden>
            <span class="font-weight-bold pr-1">Sort Order:</span>
            <select id='sort_order'>
              <option value='parcel_number'>Parcel number</option>
              <option value='num_units_asc'>Number of units &#8593;</option>
              <option value='num_units_desc'>Number of units &#8595;</option>
              <option value='building_area_asc'>Building area &#8593;</option>
              <option value='building_area_desc'>Building area &#8595;</option>
              <option value='lot_area_sqft_asc'>Lot area sqft &#8593;</option>
              <option value='lot_area_sqft_desc'>Lot area sqft &#8595;</option>
              <option value='year_built_asc'>Year built &#8593;</option>
              <option value='year_built_desc'>Year built &#8595;</option>
              <option value='sale_date_asc'>Sale date &#8593;</option>
              <option value='sale_date_desc'>Sale date &#8595;</option>
            </select>
          </div>
        </div>
      </div>

      <?php
        $show_favorites_flag = false;
        $show_matching_cases = true;
        include('includes/properties_list.php');
      ?>

      <?php require('includes/search_results/pagination.php'); ?>

      <div class="mt-3">
        <div>
          <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#createLeadBatchModal">Create Lead Batch</button>
          <button type="submit" id="export_properties_csv_button" class="btn btn-primary">Export Selected</button>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addToFavoritesFolderModal">Add to Favorites</button>
        </div>
      </div>

      <div class="mt-3">
        <div id="selectPropertiesWarning" class="alert alert-warning fade show d-none" role="alert">
          You must select at least one property.
        </div>
      </div>
    <?php } else { ?>
      <div class="jumbotron jumbotron-fluid">
        <div class="container">
          <h1 class="display-4">No results...</h1>
          <p class="lead">There are no properties matching your filters. Try adjusting your filters to get more results.</p>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php include('includes/create_lead_batch_modal.php') ?>
  <?php include('includes/add_to_favorites_modal.php') ?>
 </body>
</html>
