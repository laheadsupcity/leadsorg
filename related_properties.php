<?php
  require_once('config.php');
  require_once('Property.php');
  require_once('LoggedInUser.php');

  $parcel_number = $_GET['parcel_number'];
  $properties = Property::getRelatedPropertiesForAPN($parcel_number);

  $current_page = 1;
  $total_records = count($properties);
  $page_size = 10;
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
  <script type="text/javascript" src="js/custom_database_search/sortable_table.js"></script>
  <script type="text/javascript" src="js/custom_database_search/editable_fields.js"></script>
  <script type="text/javascript" src="js/custom_database_search/actions.js"></script>
  <script type="text/javascript" src="js/favorites/add_favorite_properties.js"></script>
  <meta name="format-detection" content="telephone=no"/>
</head>
<body>
  <?php
    include('nav.php');

    $is_admin_user = LoggedInUser::isAdminUser($user_id);
  ?>

  <div class="main-content mx-auto pl-4 pr-4">
    <?php include('includes/select_properties_alert.php'); ?>
    <?php if (!empty($properties)) { ?>
      <div class="d-flex justify-content-between">
        <div>
          <h5>Properties related to APN #<?php echo($parcel_number); ?> <span class="font-weight-light">(<?php echo $total_records; ?> total)</span></h5>
        </div>
      </div>

      <?php
        $show_favorites_flag = false;
        $show_matching_cases = false;
        $include_related_properties = false;
        $show_pagination = false;
        $id = 'related_properties';
        include('includes/properties_list_container.php');
      ?>

      <div class="mt-3 mb-3 d-flex align-items-center">
        <?php if ($is_admin_user) { ?>
          <button type="submit" class="btn btn-primary mr-1" data-toggle="modal" data-target="#createLeadBatchModal">Create Lead Batch</button>
        <?php } ?>
        <button type="submit" id="export_properties_csv_button" class="btn btn-primary mr-1">Export Selected</button>
        <button type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#addToFavoritesFolderModal">Add to Favorites</button>
        <button data-action="open_all" class="mr-1 btn btn-primary">Open All</button>
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
  <?php
    $is_search_results = true;
    include('includes/favorites_folders/add_to_favorites_modal.php');
  ?>
 </body>
</html>
