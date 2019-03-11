<div class="modal fade" id="createLeadBatchModal" tabindex="-1" role="dialog" aria-labelledby="createLeadBatchModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create lead batch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="batchName">Batch name</label>
          <input type="text" class="form-control" id="batchName" placeholder="Enter batch name" required>
          <div class="invalid-feedback">
            Please choose a batch name.
          </div>
        </div>
        <div id="batchSuccess" class="alert alert-success fade show" role="alert">
          Successfully created batch!
        </div>
        <div id="batchNameExists" class="alert alert-danger alert-dismissible fade show" role="alert">
          Batch name already exists!
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" data-action="batch_submit">Submit</button>
      </div>
    </div>
  </div>
</div>
