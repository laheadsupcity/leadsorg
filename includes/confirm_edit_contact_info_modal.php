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

        <div data-related-properties-inline-list hidden style="width: 2376px;">
          <?php
            $results_id = "confirm_contact_info_edit_related_properties";
            $select_all = true;
            $show_pagination = false;
            $read_only_fields = true;
            $show_favorites_flag = false;
            $show_matching_cases = false;
            $include_related_properties = false;

            include('includes/properties_list_container.php');
          ?>
        </div>

      </div>
      <div class="modal-footer">
        <button data-action="cancel_edit" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button data-action="confirm_edit" type="button" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>
