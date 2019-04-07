const TYPE_CONTACT_INFO = "contact_info";
const TYPE_NOTES = "notes";

const editableNotesTextArea = "<div data-edit-input class=\"input-group\" hidden><textarea class=\"form-control\" rows=\"3\"></textarea></div>";

const editableFieldInput = "<div data-edit-input class=\"input-group input-group-sm\" style=\"width: 105px\" hidden><input type=\"text\" class=\"form-control\"></div>";

const unknownFieldMarkup = "<span class='font-italic'>unknown</span>";

const noNotesMarkup = "<span class='font-italic'>no notes yet</span>";

var editable_fields = [];

function getParcelNumber(editable_field) {
  return editable_field.parents('.property-item').data('parcel_number');
}

function getOwnerName(editable_field) {
  return editable_field.parents('.property-item').data('owner_name');
}

function shouldEditRelated(parcel_number) {
  var property_row = $('[data-parcel_number=' + parcel_number + ']'),
      edit_related_checkbox = property_row.find('[data-edit-related-checkbox]');

  return edit_related_checkbox.prop('checked');
}

function confirmContactInfoEdit(edited_field_data) {
  var related_properties_copy = $('#editContactInfoModal [data-related-properties]');

  var edit_related = shouldEditRelated(edited_field_data.parcel_number);
  var related_count = 0;
  if (edit_related) {
    related_count = $('#' + edited_field_data.id).parents('.property-item').data('related_properties');
    related_properties_copy.find('[data-count]').html(related_count);
  }
  related_properties_copy.prop('hidden', !(edit_related && related_count > 0));

  $('#editContactInfoModal [data-owner-name]').html(edited_field_data.owner_name.trim());
  $('#editContactInfoModal [data-new-contact-info]').html(
    edited_field_data.new_value == "" ? unknownFieldMarkup : edited_field_data.new_value
  );

  $('#editContactInfoModal').modal('show');
}

function confirmNotesEdit(edited_field_data) {
  $('#editNotesModal [data-new-notes]').html(edited_field_data.new_value);
  $('#editNotesModal').modal('show');
}

function confirmEdit(edited_field_data) {
  if (edited_field_data.type == TYPE_CONTACT_INFO) {
    confirmContactInfoEdit(edited_field_data);
  } else if (edited_field_data.type == TYPE_NOTES) {
    confirmNotesEdit(edited_field_data);
  }
}

function makeEdit(id) {
  let edited_field = $('#' + id),
      edited_field_data = editable_fields[id],
      new_value = edited_field.find('.form-control').val();

  if (edited_field_data.current_value != new_value) {
    editable_fields[id].new_value = new_value;
    confirmEdit(edited_field_data);
  } else {
    toggleEdit(id);
  }
}

function toggleEdit(id) {
  var editable_field = $('#' + id);
  var value_element = editable_field.find('[data-field-value]'),
      input_element = editable_field.find('[data-edit-input]')
      is_edit_mode = !editable_fields[id].is_editing;

    if (editable_fields[id].is_editing) {
      editable_field.removeClass('is-editing').addClass('is-not-editing');
    } else {
      editable_field.removeClass('is-not-editing').addClass('is-editing');
    }

    editable_fields[id].is_editing = is_edit_mode;

    value_element.prop('hidden', is_edit_mode);
    input_element.prop('hidden', !is_edit_mode);

  if (is_edit_mode) {
    let input = input_element.find('.form-control');
    input.val(editable_fields[id].current_value);
    input.focus();
  } else {
    var new_content = editable_fields[id].current_value == "" ?
      unknownFieldMarkup :
      editable_fields[id].current_value;

    value_element.html(new_content);

    if (editable_fields[id].type == TYPE_CONTACT_INFO) {
      $('#editContactInfoModal').modal('hide');
    } else if (editable_fields[id].type == TYPE_NOTES) {
      $('#editNotesModal').modal('hide');
    }
  }
}

function setupEditableContactInfoFields() {
  $('.owner-column .editable-field').each(function(index, editable_field) {
    editable_field = $(editable_field);

    var id = 'editable-contact-info-field-' + index,
        parcel_number = getParcelNumber(editable_field),
        edit_related = true,
        owner_name = getOwnerName(editable_field),
        current_value = editable_field.html(),
        content = current_value == "" ? unknownFieldMarkup : current_value;

    editable_fields[id] = {
      id: id,
      type: TYPE_CONTACT_INFO,
      edit_related: edit_related,
      is_editing: false,
      parcel_number: parcel_number,
      owner_name: owner_name,
      field: editable_field.data('field'),
      current_value: current_value,
      new_value: null
    };

    editable_field
      .addClass('is-not-editing')
      .attr('id', id)
      .html("<span data-field-value>" + content + "</span>")
      .append(editableFieldInput);

    editable_field.click(function() {
      if (editable_fields[id].is_editing) {
        // no op
        return;
      }

      toggleEdit(id);
    });

    editable_field.find('.form-control').on('focusout keyup', function(event) {
      event.preventDefault();
      if((event.keyCode && event.keyCode == 13) || event.type == "focusout") {
        makeEdit(id);
      }
    });
  });

  $('#editContactInfoModal [data-action=confirm_edit]').click(function() {
    var edited_field_data = Object.values(editable_fields).filter(field => field.is_editing)[0];

    $.post(
      "edit_owner_contact_information.php",
      {
        parcel_number: edited_field_data.parcel_number,
        field: edited_field_data.field,
        value: edited_field_data.new_value,
        edit_related: edited_field_data.edit_related
      },
      function(data) {
        var edited_apns = data.split(',');

        // var fields_to_update = Object.values(editable_fields).filter(function(data) {
        //   return data.type == TYPE_CONTACT_INFO &&
        //     data.field == edited_field_data.field &&
        //     edited_apns.includes(String(data.parcel_number));
        // });
        //
        // for (var data in fields_to_update) {
        //   editable_fields[data.id].current_value = edited_field_data.value;
        // }

        editable_fields[edited_field_data.id].current_value = editable_fields[edited_field_data.id].new_value;
        editable_fields[edited_field_data.id].new_value = null;
        toggleEdit(edited_field_data.id);
      }
    );
  });

  $('#editContactInfoModal [data-action=cancel_edit]').click(function() {
    let edited_field_data = Object.values(editable_fields).filter(field => field.is_editing)[0];
    toggleEdit(edited_field_data.id);
  });
}

function setupEditableNotes() {
  $('.notes-column').each(function(index, editable_field) {
    editable_field = $(editable_field);
    let id = 'editable-notes-field-' + index,
        parcel_number = getParcelNumber(editable_field),
        current_value = editable_field.html().trim(),
        content = current_value == "" ? noNotesMarkup : current_value;

    editable_fields[id] = {
      id: id,
      type: TYPE_NOTES,
      is_editing: false,
      parcel_number: parcel_number,
      current_value: current_value,
      new_value: null
    };

    editable_field
      .addClass('is-not-editing')
      .attr('id', id)
      .html("<span data-field-value>" + content + "</span>")
      .append(editableNotesTextArea);

    editable_field.click(function() {
      if (editable_fields[id].is_editing) {
        // no op
        return;
      }

      toggleEdit(id);
    });

    editable_field.find('.form-control').on('focusout keyup', function(event) {
      event.preventDefault();
      if((event.keyCode && event.keyCode == 13) || event.type == "focusout") {
        makeEdit(id);
      }
    });
  });

  $('#editNotesModal [data-action=confirm_edit]').click(function() {
    let edited_field_data = Object.values(editable_fields).filter(field => field.is_editing)[0];

    $.post(
      "edit_property_notes.php",
      {
        parcel_number: edited_field_data.parcel_number,
        notes: edited_field_data.new_value
      },
      function(data) {
        editable_fields[edited_field_data.id].current_value = editable_fields[edited_field_data.id].new_value;
        editable_fields[edited_field_data.id].new_value = null;
        toggleEdit(edited_field_data.id);
      }
    );
  });

  $('#editNotesModal [data-action=cancel_edit]').click(function() {
    let edited_field_data = Object.values(editable_fields).filter(field => field.is_editing)[0];
    toggleEdit(edited_field_data.id);
  });
}

function handleEditRelatedCheckboxChange(event) {
  var checkbox = $(event.target);

  var values_to_update = Object.values(editable_fields).filter(function(data) {
    return data.parcel_number == checkbox.val() && data.type == TYPE_CONTACT_INFO;
  });

  for (var i in values_to_update) {
    editable_fields[values_to_update[i].id].edit_related = checkbox.prop('checked');
  }
}

$(document).ready(function() {

  $('[data-edit-related-checkbox]').change(function(event) {
    handleEditRelatedCheckboxChange(event);
  });

  setupEditableContactInfoFields();

  setupEditableNotes();

});