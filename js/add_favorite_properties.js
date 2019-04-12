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
  }).toArray();

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

  getAddToFolderModal().on('show.bs.modal', function(event) {
    var modal = $(event.currentTarget),
        selected_properties = getSelectedParcelNumbers();

    $.post(
      'get_properties_in_favorites_folders.php',
      {
        parcel_numbers: selected_properties
      },
      function(response) {
        response = JSON.parse(response);

        var modal = getAddToFolderModal();
        response.forEach(function(data) {
          modal
            .find('[data-folder-id=' + data.folder_id + '] [data-total-count]')
            .html(data.total_count);

          modal
            .find('[data-folder-id=' + data.folder_id + '] [data-existing-count]')
            .html(data.existing_count)
            .parents('[data-count-properties-pill]')
            .prop('hidden', false);
        });
      }
    )
  });

  getAddToFolderModal().find('[data-action="add"]').click(function(event) {
    handleAddToFavoritesFolder();
  });

});
