<?php
  require_once('config.php');
  require_once('FavoriteProperties.php');
?>

<!doctype html>
<html lang="en" style="font-size: 14px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/fontawesome/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
    <link rel="stylesheet" type="text/css" href="css/favorites_folders.css"/>
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

        $favorites = new FavoriteProperties();
        $favorites_folders = $favorites->getAllFoldersForUser($_SESSION['userdetail']['id']);
      ?>
    </div>

    <div class="main-content mx-auto d-flex flex-wrap justify-content-center">
      <?php foreach ($favorites_folders as $folder) { ?>
        <div class="card favorite-folder mr-3 mt-3">
          <div class="card-body">
            <h5 class="card-title"><?php echo($folder['name']); ?></h5>
            <p class="card-text font-weight-light"><?php echo($folder['property_count']); ?></p>
            <div class="d-flex flex-column">
              <a href="favorite_properties.php?folder_id=<?php echo($folder['folder_id']); ?>" class="">View</a>
              <a href="#" class="">Rename</a>
              <a href="#" class="text-danger">Delete</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </body>
</html>
