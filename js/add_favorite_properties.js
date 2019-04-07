function getAddToFolderModal() {
  return $('#addToFavoritesFolderModal');
}

function getSelectedFavoritesFolderID() {
  return getAddToFolderModal().find("input[name='favoriteFolder']:checked").val();
}

function getSelectedParcelNumbers() {
  var parcel_numbers = $('[data-property-checkbox]:checked').map(function(i, checkbox) {
    return $(checkbox).val();
  }).toArray().join();

  return parcel_numbers;
}

function handleAddToFavoritesFolder() {
  var folder_id = getSelectedFavoritesFolderID(),
      selected_apns = getSelectedParcelNumbers();

  $.post(
    'add_to_favorites_folder.php',
    {
      folder_id: folder_id,
      parcel_numbers: selected_apns
    },
    function (data) {
      getAddToFolderModal().modal('hide');
      window.location = "favorite_properties.php?folder_id=" + folder_id;
    }
  );

}

$(document).ready(function() {

  getAddToFolderModal().find('[data-action="add"]').click(function(event) {
    handleAddToFavoritesFolder();
  });

});
