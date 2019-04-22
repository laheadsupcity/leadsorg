function getConfirmDeleteFolderModal() {
  return $('#confirmDeleteFavoriteFolder');
}

function getConfirmResetFolderModal() {
  return $('#resetFlagsFolderModal');
}

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

function resetFolderFlags() {
  var folder_id = getConfirmResetFolderModal().data('folder-id');

  $.post(
    'reset_favorites_folder_flags.php',
    {
      folder_id: folder_id
    },
    function (data) {
      window.location = window.location;
    }
  );
}

$(document).ready(function() {

  $('[data-action="remove_from_folder"]').click(function(event) {
    handleRemoveFromFolder();
  });

  $('[data-action="remove"]').click(function(event) {
    var folder_id = $('#removeFromFolderModal').data('folder-id');
    handleConfirmRemove(folder_id);
  });

  getConfirmDeleteFolderModal().find('[data-action="delete"]').click(function() {
    var folder_id = $('[data-folder-id]').data('folder-id');

    $.post(
      'delete_favorites_folder.php',
      {
        folder_id: folder_id
      },
      function (data) {
        getConfirmDeleteFolderModal().modal('hide');
        window.location = "favorites_folders.php";
      }
    );
  });

  getConfirmResetFolderModal().find('[data-action="reset"]').click(function(event) {
    resetFolderFlags();
  });


});
