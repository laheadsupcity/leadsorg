<div class="modal fade" id="createNewFolder" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <span data-create-copy hidden>Create new folder</span>
          <span data-rename-copy hidden>Rename folder</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group input-group-lg">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-lg">Folder name</span>
          </div>
          <input type="text" data-favorite-folder-name class="form-control">
        </div>
        <div id="existingNameAlert" class="alert alert-warning mt-3" role="alert" hidden>
          A folder with this name already exists.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-action="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button data-rename-copy type="button" data-action="rename" class="btn btn-primary">
          Rename folder
        </button>
        <button data-create-copy type="button" data-action="create" class="btn btn-primary">
          Create folder
        </button>
      </div>
    </div>
  </div>
</div>
