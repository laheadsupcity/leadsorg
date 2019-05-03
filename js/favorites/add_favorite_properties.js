var favorites_folders = {};

function getUserID() {
  // TO DO: This is temporary hack until user sessions are handled properly
  return $('[data-user-id]').data('user-id');
}

function getAddToFolderModal() {
  return $('#addToFavoritesFolderModal');
}

function shouldShowUnseenUpdateFlag() {
  getAddToFolderModal().data('show-unseen-update-flag');
}

function createFoldersListFromData(data) {
  favorites_folders = {};

  var current_folder_id = $('[data-current-folder]').data('current-folder');
  data.forEach(function(entry) {
    if (entry.folder_id != current_folder_id) {
      favorites_folders[entry.folder_id] = entry;
    }
  });
}

function handleAddToFavoritesModalShown() {
  var show_unseen_update_flag = shouldShowUnseenUpdateFlag(),
      selected_properties = getSelectedParcelNumbers();

  $.post(
    'get_properties_in_favorites_folders.php',
    {
      user_id: getUserID(),
      selected_parcel_numbers: selected_properties,
      show_unseen_update_flag: show_unseen_update_flag
    },
    function(response) {
      var modal = getAddToFolderModal();
      response = JSON.parse(response);
      createFoldersListFromData(response);

      modal.find('[data-folders-list]').html("");

      var template = modal.find('[data-folder-row]');
      Object.values(favorites_folders).forEach(function(folder_data) {
        var folder = template.clone(),
            checkbox = folder.find('[data-folder-checkbox]');

        folder.data('folder-id', folder_data.folder_id);

        checkbox.attr('value', folder_data.folder_id);

        folder.find('[data-folder-name]').html(folder_data.name);

        folder.find('[data-total-count]').html(folder_data.total_count);

        folder.find('[data-existing-count]')
          .html(folder_data.existing_count)
          .parents('[data-count-properties-pill]')
          .prop('hidden', false);

        modal.find('[data-folders-list]').append(folder);
      });

      if (Object.keys(favorites_folders).length == 0) {
        modal.find('[data-folders-list]').append("No favorites folders yet!");
      }
    }
  );
}

function isMovePropertiesOptionSelected() {
  var is_move = $('#removeFromExistingFolders').prop('checked');
  return is_move;
}

function handleAddMoveOptionChange(checkbox) {
  var checked = checkbox.prop('checked');

  if (checked) {
    getAddToFolderModal().find('[data-action="add"]').html("Move to folder(s)");
  } else {
    getAddToFolderModal().find('[data-action="add"]').html("Add to folder(s)");
  }
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
      selected_apns = getSelectedParcelNumbers(),
      current_folder = getAddToFolderModal().data('current-folder');

  if (folder_ids.length == 0) {
    getAddToFolderModal().find('[data-select-folder-alert]').prop('hidden', false);
    return;
  }

  $.post(
    'add_to_favorites_folder.php',
    {
      user_id: getUserID(),
      folder_ids: folder_ids,
      parcel_numbers: selected_apns,
      should_move_instead_of_add: isMovePropertiesOptionSelected(),
      current_folder_id: current_folder
    },
    function (data) {
      getAddToFolderModal().find('input').prop('checked', false);
      getAddToFolderModal().modal('hide');

      if (current_folder) {
        window.location = window.location;
      } else {
        populateFavoriteFoldersColumn(JSON.parse(data));
      }
    }
  );
}

function populateFavoriteFoldersColumn(parcel_number_folders_map) {
  var property_rows = $('.property-item[data-parcel_number]');

  property_rows.each(function(i, row) {
    var row = $(row),
        parcel_number = row.data('parcel_number');

    if (parcel_number_folders_map[parcel_number]) {
      row.data('favorites_folders', parcel_number_folders_map[parcel_number].sort().join());
    } else {
      row.data('favorites_folders', "");

      parcel_number_folders_map[parcel_number] = [];
    }

    row.find(".favorites-folders.property-info-column").html(
      parcel_number_folders_map[parcel_number].sort().map(
        function(folder) {
          return "<div>" + folder + "</div>";
        }
      )
    )
  });

  sortProperties();
}

$(document).ready(function() {

  getAddToFolderModal().on('show.bs.modal', function(event) {
    var modal = getAddToFolderModal();
    modal.find('[data-select-folder-alert]').prop('hidden', true);
    modal.find('[data-action="add"]').html("Add to folder(s)");

    handleAddToFavoritesModalShown();
  });

  getAddToFolderModal().find('[data-action="add"]').click(function(event) {
    handleAddToFavoritesFolder();
  });

  $('#removeFromExistingFolders').change(function(event) {
    handleAddMoveOptionChange($(event.currentTarget));
  });

});
