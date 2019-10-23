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

    <script type="text/javascript" src="js/custom_database_search/actions.js"></script>
    <script type="text/javascript" src="js/custom_database_search/sortable_table.js"></script>
    <script type="text/javascript" src="js/favorites/actions.js"></script>
    <script type="text/javascript" src="js/favorites/add_favorite_properties.js"></script>
    <script type="text/javascript" src="js/custom_database_search/editable_fields.js"></script>
    <style>
      .active1 {
        background: #337ab7!important;
      }
    </style>
  </head>
  <body>
    <div style="width:100%; float:left; margin:0;">
      <?php
        include('nav.php');
      ?>
    </div>

    <div class="main-content mx-auto pl-2 pr-2">
      <div data-folder-dne hidden>
        <div class="jumbotron jumbotron-fluid">
          <div class="container">
            <h1 class="display-4">Favorites folder does not exist...</h1>
            <p class="lead">Create a new favorites folder to save properties of interest.</p>
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" data-toggle="modal" data-target="#confirmDeleteFavoriteFolder" class="btn btn-danger float-right">Delete folder</button>
        </div>
      </div>

      <div data-folder-empty class="jumbotron jumbotron-fluid" hidden>
        <div class="container">
          <h1 class="display-4">Favorites folder does not exist...</h1>
          <p class="lead">Create a new favorites folder to save properties of interest.</p>
        </div>
      </div>

      <div data-results-and-actions hidden>
        <h5><span data-folder-name></span> <span class="font-weight-light">(<span data-total-records></span> properties)</span></h5>

        <?php
          $show_favorites_flag = true;
          $read_only_fields = false;
          $show_matching_cases = false;
          $include_related_properties = true;
          $results_id = 'favorites_list';
          include('includes/properties_list_container.php');
        ?>

        <div class="mt-3 d-flex justify-content-between">
          <div class="d-flex" style="height: 44px;">
            <button id="export_properties_csv_button" class="mr-1 btn btn-primary">Export selected</button>
            <button data-toggle="modal" data-target="#addToFavoritesFolderModal" class="mr-1 btn btn-primary">Add to folder(s)</button>
            <button data-action="open_all" class="mr-1 btn btn-primary">Open All</button>
            <button data-action="reset_flags" class="btn btn-info mr-1">Reset flags</button>
            <button data-action="remove_from_folder" class="btn btn-warning mr-3">Remove selected</button>

            <?php include 'includes/select_properties_alert.php'; ?>
          </div>
          <button type="submit" data-toggle="modal" data-target="#confirmDeleteFavoriteFolder" class="btn btn-danger float-right">Delete folder</button>
        </div>

        <?php include('includes/favorites_folders/confirm_reset_folder_flags_modal.php'); ?>
        <?php include('includes/favorites_folders/remove_from_folder_modal.php'); ?>
        <?php include('includes/favorites_folders/delete_favorites_folder_modal.php'); ?>
        <?php
          $folder_id_to_exclude = $folder_id;
          $is_search_results = false;
          include('includes/favorites_folders/add_to_favorites_modal.php');
        ?>
      </div>
    </div>
  </body>
</html>
