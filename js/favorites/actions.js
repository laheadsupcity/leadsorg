function handleConfirmRemove(folder_id) {
  var selected_properties = $("[data-property-checkbox]:checked").map(function(index, checkbox) {
    return checkbox.value;
  }).toArray();

  $.post(
    "remove_properties_favorites_folder.php",
    {
      folder_id: folder_id,
      parcel_numbers: selected_properties
    },
    function(data) {
      $('#removeFromFolderModal').modal('hide');
      window.location = window.location;
    }
  );
}

function getRemoveFromFolderModal() {
  return $('#removeFromFolderModal');
}

function handleRemoveFromFolder() {
  var selected_properties = $("[data-property-checkbox]:checked");

  if (selected_properties.length == 0) {
    // no op
    return;
  }

  getRemoveFromFolderModal().modal('show');

}

$(document).ready(function() {

  $('[data-action="remove_from_folder"]').click(function(event) {
    handleRemoveFromFolder();
  });

  $('[data-action="remove"]').click(function(event) {
    var folder_id = $('#removeFromFolderModal').data('folder-id');
    handleConfirmRemove(folder_id);
  });

});
