<?php
  require_once('FavoriteProperties.php');

  $favorites = new FavoriteProperties();
  $favorites_folders = $favorites->getAllFoldersForUser($_SESSION['userdetail']['id']);

?>

<div
  <?php if ($folder_id_to_exclude) { ?>
    data-current-folder=<?php echo $folder_id_to_exclude; ?>
  <?php } ?>
  class="modal fade" id="addToFavoritesFolderModal" tabindex="-1" role="dialog" aria-labelledby="addToFavoritesFolderModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 580px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add to favorites</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php foreach ($favorites_folders as $folder) {
            if ($folder_id_to_exclude && $folder->folder_id == $folder_id_to_exclude) {
              continue;
            }
        ?>
          <div class="mb-2 w-100 d-flex align-items-center">
            <input type="checkbox" name="favoriteFolder" id="folder<?php echo($folder->folder_id); ?>" value="<?php echo($folder->folder_id); ?>">
            <div
              data-folder-id="<?php echo($folder->folder_id); ?>"
              data-folder-name="<?php echo($folder->name)?>"
              class="favorite-folder-link card ml-2 w-100">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div data-folder-name-and-count>
                  <span class="mr-2"><?php echo($folder->name); ?></span>
                  <span data-count-properties-pill class="badge badge-primary badge-pill"><span data-total-count><?php echo($folder->property_count); ?></span> total</span>
                  <span data-count-properties-pill class="badge badge-warning badge-pill ml-1" hidden><span data-existing-count><?php echo($folder->property_count); ?></span> existing</span>
                  <?php if($folder->has_unseen_updates) { ?>
                    <span class="ml-2" style="width: 15px;">
                        <i class="fas fa-flag text-danger"></i>
                    </span>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        <?php
        } ?>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="removeFromExistingFolders">
          <label class="form-check-label text-danger font-weight-bold" for="removeFromExistingFolders">
            <?php if ($folder_id_to_exclude) { ?>
              Move selected properties out of current folder
            <?php } else { ?>
              Move selected properties out of existing folders
            <?php } ?>
          </label>
        </div>
        <div>
          <button type="button" data-action="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" data-action="add" class="btn btn-primary">Add to folder(s)</button>
        </div>
      </div>
    </div>
  </div>
</div>
