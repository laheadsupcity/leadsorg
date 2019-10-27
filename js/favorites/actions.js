let is_initial_load = true;

function getFolderID() {
  var url = new URL(window.location.href);

  return url.searchParams.get('folder_id');
}

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

function getConfirmDeleteFolderModal() {
  return $('#confirmDeleteFavoriteFolder');
}

function getConfirmResetFolderModal() {
  return $('#resetFlagsFolderModal');
}

function handleConfirmRemove() {
  var selected_properties = getSelectedProperties();

  $.post(
    "remove_properties_favorites_folder.php",
    {
      folder_id: getFolderID(),
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
    $('#selectPropertiesWarning').prop('hidden', false);
    return;
  }

  getRemoveFromFolderModal().modal('show');

}

function resetFolderFlags() {
  var selected_properties = getSelectedProperties();

  var folder_id = getFolderID();

  $.post(
    'reset_favorites_folder_flags.php',
    {
      folder_id: folder_id,
      parcel_numbers: selected_properties
    },
    function (data) {
      window.location = window.location;
    }
  );
}

function fetchFavoriteProperties() {
  let folder_id = new URLSearchParams(window.location.search).get('folder_id');
  $.post(
    'fetch_properties_results.php',
    {
      user_id: getUserID(),
      show_favorites_flag: true,
      include_related_properties: true,
      read_only_fields: false,
      show_matching_cases: false,
      apns_for_favorites_folder: folder_id
    },
    function(data) {
      data = JSON.parse(data);

      var results_id = $('.property-list-group').data('results-id');

      $('[data-properties-list="' + results_id + '"]').html(data.properties_list_markup);

      $('[data-total-records]').html(data.total_records);
      $('[data-folder-name]').html(data.folder_name);
      $('[data-results-and-actions]').prop('hidden', false);

      resizePropertyList();
      $('.main-content').width($('.properties-scroll').width() + 13);

      setupSortableColumns(results_id);

      setupEditableFields();

      is_initial_load = false;
    }
  );
}

$(document).ready(function() {

  fetchFavoriteProperties();

  $('[data-action="remove_from_folder"]').click(function(event) {
    handleRemoveFromFolder();
  });

  $('[data-action="remove"]').click(function(event) {
    handleConfirmRemove();
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

  $('[data-action=reset_flags]').click(function(event) {
    var selected_properties = getSelectedProperties();

    if (selected_properties.length == 0) {
      $('#selectPropertiesWarning').prop('hidden', false);
    } else {
      getConfirmResetFolderModal().modal('show');
    }
  });

});


/// UTILITIES

function getUserID() {
  // TO DO: This is temporary hack until user sessions are handled properly
  return $('[data-user-id]').data('user-id');
}

/// UTILITIES
