<?php
  require_once('config.php');
  require_once('FavoriteProperties.php');
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
    <script type="text/javascript" src="js/custom_database_search/results.js"></script>
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
      <?php
        $folder_id = $_GET['folder_id'];

        $favorites = new FavoriteProperties();

        $folder = $favorites->getFolderFromID($folder_id);

        $properties = $favorites->getPropertiesForFolder($folder_id);
      ?>

      <?php if (empty($folder)) { ?>
        <div class="jumbotron jumbotron-fluid">
          <div class="container">
            <h1 class="display-4">Favorites folder does not exist...</h1>
            <p class="lead">Create a new favorites folder to save properties of interest.</p>
          </div>
        </div>
      <?php } else { ?>
        <?php if (empty($properties)) { ?>
          <div class="jumbotron jumbotron-fluid">
            <div class="container">
              <h1 class="display-4">Favorites folder is currently empty!</h1>
              <p class="lead">Perform a search and save some properties of interest.</p>
            </div>
          </div>
        <?php } else { ?>
          <h5><?php echo($folder['name']); ?></h5>

          <?php
            $show_favorites_flag = true;
            $show_matching_cases = false;
            include('includes/properties_list.php');
          ?>

          <div class="mt-3">
            <button type="submit" id="export_properties_csv_button" class="btn btn-primary">Export selected</button>
            <button type="submit" data-action="remove_from_folder" class="btn btn-warning">Remove selected</button>
            <button type="submit" data-action="remove_from_folder" class="btn btn-danger float-right">Delete folder</button>
          </div>
        <?php } ?>
      <?php }?>


    </div>
  </body>
</html>
