<?php
  require_once('config.php');
  require_once('FavoriteProperties.php');
?>

<!doctype html>
<html lang="en" style="font-size: 14px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/favorite_properties_folders.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

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

        $user_id = $_SESSION['userdetail']['id'];
        $favorites = new FavoriteProperties();
        $favorites_folders = $favorites->getAllFoldersForUser($user_id);
      ?>
    </div>
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />

    <div class="main-content main-content-fixed-width mx-auto">
      <div class="d-flex flex-wrap justify-content-center">
        <div class="w-100 m-3">
          <?php if (empty($favorites_folders)) { ?>
            <div class="jumbotron jumbotron-fluid">
              <div class="container">
                <h1 class="display-4">No favorites yet...</h1>
                <p class="lead">Create a new favorites folder to save properties of interest.</p>
              </div>
            </div>
          <?php } else { ?>
            <?php foreach ($favorites_folders as $folder) { ?>
              <div
                data-folder-id="<?php echo($folder->folder_id); ?>"
                data-folder-name="<?php echo($folder->name)?>"
                class="favorite-folder-link card mb-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div data-folder-name-and-count>
                    <span class="mr-2"><?php echo($folder->name); ?></span>
                    <span class="badge badge-primary badge-pill"><?php echo($folder->property_count); ?></span>
                    <?php if($folder->has_unseen_updates) { ?>
                      <span class="ml-2" style="width: 15px;">
                          <i class="fas fa-flag text-danger"></i>
                      </span>
                    <?php } ?>
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
          <?php } ?>
        </div>
      </div>

      <div class="mr-3 ml-3">
        <button type="submit" data-action="create-new-folder" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Folder</button>
      </div>

      <?php include('includes/favorites_folders/create_new_folder_modal.php'); ?>
      <?php include('includes/favorites_folders/delete_favorites_folder_modal.php'); ?>
    </div>
  </body>
</html>
