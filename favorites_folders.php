<?php
  require_once('config.php');
  require_once('FavoriteProperties.php');
?>

<!doctype html>
<html lang="en" style="font-size: 14px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/favorite_properties.js"></script>

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
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />

    <div class="main-content mx-auto">
      <div class="d-flex flex-wrap justify-content-center">
        <div class="w-100 m-3">
          <?php foreach ($favorites_folders as $folder) { ?>
            <div data-folder-id="<?php echo($folder['folder_id']); ?>" class="favorite-folder-link card mb-2">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div data-folder-name-and-count>
                  <span class="mr-2"><?php echo($folder['name']); ?></span>
                  <span class="badge badge-primary badge-pill"><?php echo($folder['property_count']); ?></span>
                </div>
                <div data-folder-name-input class='d-none'>
                  <input class="form-control" type="text" placeholder="New folder name">
                </div>
                <div>
                  <button data-action="rename-folder" type="button" class="btn btn-sm btn-warning"><i class="far fa-edit"></i></button>
                  <button data-action="delete-folder" type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i></button>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="mr-3 ml-3">
        <button type="submit" data-action="create_new_folder" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Folder</button>
      </div>
    </div>
  </body>
</html>
