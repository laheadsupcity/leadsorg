<?php
  require_once('FavoriteProperties.php');

  $favorites = new FavoriteProperties();
  $favorites_folders = $favorites->getAllFoldersForUser($_SESSION['userdetail']['id']);

?>

<div class="modal fade" id="addToFavoritesModal" tabindex="-1" role="dialog" aria-labelledby="addToFavoritesModalTitle" aria-hidden="true">
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
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="favoriteFolder" id="folder<?php echo($folder['folder_id']); ?>" value="<?php echo($folder['folder_id']); ?>" checked>
            <label class="form-check-label" for="favoriteFolder">
              <?php echo($folder['name']); ?>
            </label>
          </div>
        <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
