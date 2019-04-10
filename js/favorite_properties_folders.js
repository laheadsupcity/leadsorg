const ACTION_RENAME_FOLDER = "rename-folder";
const ACTION_DELETE_FOLDER = "delete-folder";

function getCreateNewFolderModal() {
  return $('#createNewFolder');
}

function getConfirmDeleteFolderModal() {
  return $('#confirmDeleteFavoriteFolder');
}

function getNewFolderNameInput() {
  return getCreateNewFolderModal().find('[data-favorite-folder-name]');
}

function resetCreateNewFolderModal() {
  getNewFolderNameInput().val("");
}

function createNewFolder(folder_name) {
  if (folder_name == "") {
    getCreateNewFolderModal().modal('hide')
  } else {
    $.post(
      'create_new_favorites_folder.php',
      {
        folder_name: folder_name
      },
      function (data) {
        getCreateNewFolderModal().modal('hide');
        window.location = window.location;
      }
    );
  }
}

function renameFolder(folder_id, folder_name) {
  if (folder_name == "") {
    getCreateNewFolderModal().modal('hide')
  } else {
    $.post(
      'rename_favorites_folder.php',
      {
        folder_id: folder_id,
        folder_name: folder_name
      },
      function (data) {
        getCreateNewFolderModal().modal('hide');
        window.location = window.location;
      }
    );
  }
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

$(document).ready(function() {
  var createNewFolderModal = getCreateNewFolderModal();

  createNewFolderModal.on('shown.bs.modal', function() {
    getNewFolderNameInput().focus();
  });

  createNewFolderModal.on('hidden.bs.modal', function() {
    resetCreateNewFolderModal();
  });

  createNewFolderModal.find('[data-action="create"]').click(function() {
    createNewFolder(getNewFolderNameInput().val());
  });

  getConfirmDeleteFolderModal().find('[data-action="delete"]').click(function() {
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
  });

  createNewFolderModal.find('[data-action="rename"]').click(function(event) {
    renameFolder(
      createNewFolderModal.data('folder-id'),
      getNewFolderNameInput().val()
    );
  });

  $('[data-action="create-new-folder"]').click(function() {
    var modal = getCreateNewFolderModal();

    modal.find('[data-create-copy]').prop('hidden', false);
    modal.find('[data-rename-copy]').prop('hidden', true)
    modal.modal('show');
  });

  $('[data-folder-id]').click(function(event) {
    var folder_id = $(event.currentTarget).data('folder-id'),
        folder_name = $(event.currentTarget).data('folder-name'),
        action_button = $(event.target).parents('[data-action]');

    if (action_button.length) {
      switch (action_button.data('action')) {
        case ACTION_RENAME_FOLDER:
          handleRenameFolder(folder_id, folder_name);
          break;
        case ACTION_DELETE_FOLDER:
          handleDeleteFolder(folder_id);
          break;
      }
    } else {
      window.location = "favorite_properties.php?folder_id=" + folder_id;
    }
  });

});
