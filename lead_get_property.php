<?php
  require_once('config.php');
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
  <script type="text/javascript" src="js/deparam.js"></script>
  <script type="text/javascript" src="js/custom_database_search/results.js"></script>
  <script type="text/javascript" src="js/custom_database_search/sortable_table.js"></script>
  <script type="text/javascript" src="js/custom_database_search/editable_fields.js"></script>
  <script type="text/javascript" src="js/custom_database_search/actions.js"></script>
  <script type="text/javascript" src="js/favorites/add_favorite_properties.js"></script>
  <style>
    .active1{background:#337ab7!important;}
  </style>
  <meta name="format-detection" content="telephone=no"/>
</head>
<body>
  <?php
    include('nav.php');
    $page_size = isset($_REQUEST['page_size']) ? $_REQUEST['page_size'] : 10;
  ?>

  <div class="main-content mx-auto pl-4 pr-4">
    <?php include('includes/select_properties_alert.php'); ?>

    <div data-loading class="d-flex justify-content-center mb-5">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div data-results-and-actions class="d-none">
      <div class="d-flex justify-content-between p-2">
        <div>
          <span class="font-weight-bold pr-1">Total Records:</span> <span data-total-records></span>
        </div>
        <div>
          <span class="font-weight-bold pr-1">No. of Records Per Page:</span>
          <select id='page_size' name="page_size">
            <option <?php echo ($page_size==10)?"selected='selected'":''; ?> value='10'>10</option>
            <option <?php echo ($page_size==25)?"selected='selected'":''; ?> value='25'>25</option>
            <option <?php echo ($page_size==50)?"selected='selected'":''; ?> value='50'>50</option>
            <option <?php echo ($page_size==100)?"selected='selected'":''; ?> value='100'>100</option>
            <option <?php echo ($page_size==250)?"selected='selected'":''; ?> value='250'>250</option>
            <option <?php echo ($page_size==500)?"selected='selected'":''; ?> value='500'>500</option>
            <option <?php echo ($page_size==1000)?"selected='selected'":''; ?> value='1000'>1000</option>
            <option <?php echo ($page_size==5000)?"selected='selected'":''; ?> value='5000'>5000</option>
          </select>
        </div>
      </div>

      <?php
        $results_id = "custom_database_search_results";
        $select_all = false;
        $show_pagination = true;
        $show_favorites_flag = false;
        $show_matching_cases = true;
        $include_related_properties = true;

        include('includes/properties_list_container.php');
      ?>

      <div class="mt-3 mb-3 d-flex align-items-center">
        <?php if ($is_admin_user) { ?>
          <button type="submit" class="btn btn-primary mr-1" data-toggle="modal" data-target="#createLeadBatchModal">Create Lead Batch</button>
        <?php } ?>
        <button type="submit" id="export_properties_csv_button" class="btn btn-primary mr-1">Export Selected</button>
        <button type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#addToFavoritesFolderModal">Add to Favorites</button>
        <button data-action="open_all" type="button" class="btn btn-primary">Open All</button>
      </div>
    </div>

    <div class="jumbotron jumbotron-fluid d-none" id="no_results_alert">
      <div class="container">
        <h1 class="display-4">No results...</h1>
        <p class="lead">There are no properties matching your filters. Try adjusting your filters to get more results.</p>
      </div>
    </div>
  </div>

  <?php include('includes/confirm_edit_contact_info_modal.php'); ?>

  <?php include('includes/confirm_edit_notes_modal.php'); ?>

  <?php include('includes/create_lead_batch_modal.php') ?>

  <?php
    $is_search_results = true;
    include('includes/favorites_folders/add_to_favorites_modal.php');
  ?>
 </body>
</html>
