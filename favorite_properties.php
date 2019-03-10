<?php
  require_once('config.php');
  require_once('FavoriteProperties.php');
?>

<!doctype html>
<html lang="en" style="font-size: 14px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/custom_database_search/results.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/fontawesome/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
    <link rel="stylesheet" type="text/css" href="css/custom_search_results.css"/>
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

    <div class="main-content mx-auto">
      <?php
        $folder_id = $_GET['folder_id'];

        $favorites = new FavoriteProperties();

        $folder = $favorites->getFolderFromID($folder_id);

        $properties = $favorites->getPropertiesForFolder($folder_id);
      ?>

      <h5><?php echo($folder['name']); ?></h5>

      <?php include('includes/properties_table.php') ?>

      <div class="mt-3">
        <button type="submit" id="expcsvbtn" class="btn btn-primary">Export selected</button>
        <button type="submit" data-action="remove_from_folder" class="btn btn-warning">Remove selected</button>
        <button type="submit" data-action="remove_from_folder" class="btn btn-danger float-right">Delete folder</button>
      </div>
    </div>
  </body>
</html>
