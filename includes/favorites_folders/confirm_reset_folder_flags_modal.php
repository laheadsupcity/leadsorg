<div class="modal fade" id="resetFlagsFolderModal" tabindex="-1" role="dialog" aria-hidden="true" data-folder-id="<?php echo $folder_id; ?>">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Confirm reset
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you would like to reset all update flags for the selected properties?
      </div>
      <div class="modal-footer">
        <button type="button" data-action="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" data-action="reset" class="btn btn-info">
          Reset
        </button>
      </div>
    </div>
  </div>
</div>
