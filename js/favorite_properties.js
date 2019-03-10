function handleCreateNewFolder() {

}

function handleRenameFolder(folder_id) {

}

$(document).ready(function() {

  $('[data-folder-id]').click(function(event) {
    var folder_id = $(event.currentTarget).data('folder-id');
    window.location = "favorite_properties.php?folder_id=" + folder_id;
  });

  $('[data-action="create_new_folder"]').click(function() {
    handleCreateNewFolder();
  });

  $('[data-action="rename-folder"]').click(function(event) {
    var folder = $(event.currentTarget).parents('[data-folder-id]');

    handleRenameFolder(folder.data('folder-id'));
  });

});
