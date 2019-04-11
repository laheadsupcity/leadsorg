<?php
  require_once('FavoriteProperties.php');

  $favorites = new FavoriteProperties();
  $favorites_folders = $favorites->getAllFoldersForUser($_SESSION['userdetail']['id']);

?>

<div class="modal fade" id="addToFavoritesFolderModal" tabindex="-1" role="dialog" aria-labelledby="addToFavoritesFolderModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add to favorites</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php foreach ($favorites_folders as $folder) {?>
          <div class="mb-2 w-100 d-flex align-items-center">
            <input type="checkbox" name="favoriteFolder" id="folder<?php echo($folder->folder_id); ?>" value="<?php echo($folder->folder_id); ?>">
            <div
              data-folder-id="<?php echo($folder->folder_id); ?>"
              data-folder-name="<?php echo($folder->name)?>"
              class="favorite-folder-link card ml-2 w-100">
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
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" data-action="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" data-action="add" class="btn btn-primary">Add to folder</button>
      </div>
    </div>
  </div>
</div>
