<div class="modal fade" id="editNotesModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm changes to notes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you would like to save the following notes?
        <div class="text-primary mt-2" data-new-notes style="white-space: pre-wrap;"></div>
      </div>
      <div class="modal-footer">
        <button data-action="cancel_edit" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button data-action="confirm_edit" type="button" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>
