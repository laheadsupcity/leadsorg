function getUserID() {
  // TO DO: This is temporary hack until user sessions are handled properly
  return $('[data-user-id]').data('user-id');
}

function updateProperty() {
  var property_data = $('#update-property-form').serialize(),
      parcel_number = $('#update-property-form').find('[name=apn]').val(),
      private_note = $('#update-property-form').find('[name=private_note]').val();
      public_note = $('#update-property-form').find('[name=public_note]').val();

  $.post(
    "edit_property_notes.php",
    {
      user_id: getUserID(),
      parcel_number: parcel_number,
      content: private_note,
      is_private: true
    }
  );

  $.post(
    "edit_property_notes.php",
    {
      user_id: getUserID(),
      parcel_number: parcel_number,
      content: public_note,
      is_private: false
    }
  );

  $.post(
    'lead_updateproperty.php',
    property_data,
    function(data) {
      window.location = window.location;
    }
  );
}

$(document).ready(function() {

  $('#editRelated').change(function (event) {
    $(event.currentTarget).val($(event.currentTarget).prop('checked'));
  });

  $('[data-action="update_property"]').click(function(event) {
    event.preventDefault();

    $('#confirmEditProperty').modal('show');
  });

  $('[data-action="confirm_edit"]').click(function() {
    updateProperty();
  });

});
