function getCreateNewFolderModal() {
  return $('#createNewFolder');
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
      'create_new_folder.php',
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

function handleRenameFolder(folder_id) {

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

  $('[data-folder-id]').click(function(event) {
    var folder_id = $(event.currentTarget).data('folder-id');
    window.location = "favorite_properties.php?folder_id=" + folder_id;
  });

  $('[data-action="rename-folder"]').click(function(event) {
    var folder = $(event.currentTarget).parents('[data-folder-id]');

    handleRenameFolder(folder.data('folder-id'));
  });

});
