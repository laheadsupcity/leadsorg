function updateProperty() {
  if (confirm("Are you sure you want to update this?")) {
    var property_data = $('#update-property-form').serialize();
    jQuery.ajax({
      type: "POST",
      url: "lead_updateproperty.php",
      dataType: 'json',
      data: property_data,
      success: function(data) {
        console.log(data);
        if (data.msg = "success") {
          alert("Data updated successfully");
        }
        window.location = window.location;
      }
    });
    return false;
  } else {
    return false;
  }
}

$(document).ready(function() {

  $('#editRelated').change(function (event) {
    $(event.currentTarget).val($(event.currentTarget).prop('checked'));
  });

  $('[data-action="update"]').click(function() {
    updateProperty();
  });

});
