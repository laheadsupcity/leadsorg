const ACTION_RENAME_FOLDER = "rename-folder";
const ACTION_DELETE_FOLDER = "delete-folder";

function getUserID() {
  // TO DO: This is temporary hack until user sessions are handled properly
  return $('[data-user-id]').data('user-id');
}

function resetModal(modal, callback) {
  modal.find('input').val("");
  hideDuplicateFolderNameAlert();
  callback();
}

function hideDuplicateFolderNameAlert() {
  $('#existingNameAlert').prop('hidden', true);
}

function showDuplicateFoldeNameAlert() {
  $('#existingNameAlert').prop('hidden', false);
}

function getCreateNewFolderModal() {
  return $('#createNewFolder');
}

function getConfirmDeleteFolderModal() {
  return $('#confirmDeleteFavoriteFolder');
}

function getNewFolderNameInput() {
  return getCreateNewFolderModal().find('[data-favorite-folder-name]');
}

function createNewFolder(user_id, folder_name) {
  if (folder_name == "") {
    getCreateNewFolderModal().modal('hide')
  } else {
    $.post(
      'create_new_favorites_folder.php',
      {
        user_id: user_id,
        folder_name: folder_name
      },
      function (data) {
        data = JSON.parse(data);
        if (data.error == "duplicate_folder_name") {
          showDuplicateFoldeNameAlert();
        } else {
          getCreateNewFolderModal().modal('hide');
          window.location = window.location;
        }
      }
    );
  }
}

function renameFolder(user_id, folder_id, folder_name) {
  if (folder_name == "") {
    getCreateNewFolderModal().modal('hide')
  } else {
    $.post(
      'rename_favorites_folder.php',
      {
        user_id: user_id,
        folder_id: folder_id,
        folder_name: folder_name
      },
      function (data) {
        data = JSON.parse(data);
        if (data.error == "duplicate_folder_name") {
          showDuplicateFoldeNameAlert();
        } else {
          getCreateNewFolderModal().modal('hide');
          window.location = window.location;
        }
      }
    );
  }
}

function deleteFolder() {
  var folder_id = getConfirmDeleteFolderModal().data('folder-id');

  $.post(
    'delete_favorites_folder.php',
    {
      folder_id: folder_id
    },
    function (data) {
      getConfirmDeleteFolderModal().modal('hide');
      window.location = window.location;
    }
  );
}

function handleRenameFolder(folder_id, folder_name) {
  getNewFolderNameInput().val(folder_name)

  var modal = getCreateNewFolderModal();

  modal.find('[data-create-copy]').prop('hidden', true);
  modal.find('[data-rename-copy]').prop('hidden', false);
  modal.data('folder-id', folder_id);
  modal.modal('show');
}

function handleDeleteFolder(folder_id) {
  getConfirmDeleteFolderModal()
    .data('folder-id', folder_id)
    .modal('show');
}

function handleFolderClick(clicked_element, clicked_folder) {
  var folder_id = clicked_folder.data('folder-id'),
      folder_name = clicked_folder.data('folder-name'),
      action_button = clicked_element.closest('[data-action]');

  if (action_button.length) {
    switch (action_button.data('action')) {
      case ACTION_RENAME_FOLDER:
        resetModal($(this), function () {
          handleRenameFolder(folder_id, folder_name);
          getNewFolderNameInput().focus();
        });
        break;
      case ACTION_DELETE_FOLDER:
        handleDeleteFolder(folder_id);
        break;
    }
  } else {
    window.open("favorite_properties.php?folder_id=" + folder_id);
  }
}

$(document).ready(function() {
  var createNewFolderModal = getCreateNewFolderModal();

  createNewFolderModal.on('shown.bs.modal', function() {
    getNewFolderNameInput().focus();
  });
  createNewFolderModal.find('[data-action="create"]').click(function() {
    createNewFolder(
      getUserID(),
      getNewFolderNameInput().val()
    );
  });
  createNewFolderModal.find('[data-action="rename"]').click(function(event) {
    renameFolder(
      getUserID(),
      createNewFolderModal.data('folder-id'),
      getNewFolderNameInput().val()
    );
  });
  createNewFolderModal.find('input').on('focus', function() {
    hideDuplicateFolderNameAlert();
  });

  getConfirmDeleteFolderModal().find('[data-action="delete"]').click(function() {
    deleteFolder();
  });

  $('[data-action="create-new-folder"]').click(function() {
    resetModal(createNewFolderModal, function() {
      createNewFolderModal.find('[data-create-copy]').prop('hidden', false);
      createNewFolderModal.find('[data-rename-copy]').prop('hidden', true)
      createNewFolderModal.modal('show');
    });
  });

  $('[data-folder-id]').click(function(event) {
    var clicked_folder = $(event.currentTarget),
        clicked_element = $(event.target);
    handleFolderClick(clicked_element, clicked_folder);
  });

});
