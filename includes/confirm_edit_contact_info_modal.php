<div class="modal fade" id="editContactInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 2000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm contact information changes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="overflow: scroll;">
        Are you sure you would like to change <span class="font-italic font-weight-bold"><span data-owner-name class="text-nowrap"></span>'s</span> contact information to the following?

        <div class="h4 text-center text-primary mt-2 text-truncate" data-new-contact-info></div>

        <div class="mt-4" data-related-properties style="width: 2376px;"></div>
      </div>
      <div class="modal-footer">
        <button data-action="cancel_edit" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button data-action="confirm_edit" type="button" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>
