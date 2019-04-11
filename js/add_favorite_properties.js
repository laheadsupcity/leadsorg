function getAddToFolderModal() {
  return $('#addToFavoritesFolderModal');
}

function getSelectedFavoritesFolderIDs() {
  var selected_folders =
    getAddToFolderModal()
      .find("input[name='favoriteFolder']:checked")
      .map(function(index, checkbox) {
        return checkbox.value;
      });

  return selected_folders.toArray();
}

function getSelectedParcelNumbers() {
  var parcel_numbers = $('[data-property-checkbox]:checked').map(function(i, checkbox) {
    return $(checkbox).val();
  }).toArray().join();

  return parcel_numbers;
}

function handleAddToFavoritesFolder() {
  var folder_ids = getSelectedFavoritesFolderIDs(),
      selected_apns = getSelectedParcelNumbers();

  $.post(
    'add_to_favorites_folder.php',
    {
      folder_ids: folder_ids,
      parcel_numbers: selected_apns
    },
    function (data) {
      getAddToFolderModal().find('input').prop('checked', false);
      getAddToFolderModal().modal('hide');
    }
  );

}

$(document).ready(function() {

  getAddToFolderModal().find('[data-action="add"]').click(function(event) {
    handleAddToFavoritesFolder();
  });

});
