const editableFieldInput = "<div data-edit-input class=\"input-group input-group-sm\" style=\"width: 105px\" hidden><input type=\"text\" class=\"form-control\"></div>";
const unknownFieldMarkup = "<span class='font-italic'>unknown</span>";

var confirmEditModal = $('#editContactInfoModal');

var editable_fields = [];

function getParcelNumber(editable_field) {
  return editable_field.parents('.property-item').data('parcel_number');
}

function getOwnerName(editable_field) {
  return editable_field.parents('.property-item').data('owner_name');
}

function setupEditableContactInfoFields() {
  $('.owner-column .editable-field').each(function(index, editable_field) {
    editable_field = $(editable_field);

    var id = 'editable-field-' + index,
        parcel_number = getParcelNumber(editable_field),
        owner_name = getOwnerName(editable_field),
        current_value = editable_field.html(),
        content = current_value == "" ? unknownFieldMarkup : current_value;

    editable_fields[id] = {
      id: id,
      is_editing: false,
      parcel_number: parcel_number,
      owner_name: owner_name,
      field: editable_field.data('field'),
      current_value: current_value,
      new_value: null
    };

    editable_field
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

    editable_field.find('input').on('focusout keyup', function(event) {
      if((event.keyCode && event.keyCode == 13) || event.focusout) {
        editContactInfo(id);
      }
    });
  });

  $('#editContactInfoModal [data-action=confirm_edit]').click(function() {
    let edited_field_data = Object.values(editable_fields).filter(field => field.is_editing)[0];

    $.post(
      "edit_owner_contact_information.php",
      {
        parcel_number: edited_field_data.parcel_number,
        field: edited_field_data.field,
        value: edited_field_data.new_value
      },
      function(data) {
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

function editContactInfo(id) {
  let edited_field = $('#' + id),
      edited_field_data = editable_fields[id],
      input = edited_field.find('input'),
      contact_field = edited_field_data.field,
      new_value = input.val();

  if (edited_field_data.current_value != new_value) {
    editable_fields[id].new_value = new_value;
    $('#editContactInfoModal [data-owner-name]').html(edited_field_data.owner_name);
    $('#editContactInfoModal [data-new-contact-info]').html(edited_field_data.new_value);
    $('#editContactInfoModal').modal('show');
  } else {
    toggleEdit(id);
  }
}

function toggleEdit(id) {
  var editable_field = $('#' + id);
  var value_element = editable_field.find('[data-field-value]'),
      input_element = editable_field.find('[data-edit-input]')
      is_edit_mode = !editable_fields[id].is_editing;

    editable_fields[id].is_editing = is_edit_mode;

    value_element.prop('hidden', is_edit_mode);
    input_element.prop('hidden', !is_edit_mode);

  if (is_edit_mode) {
    let input = input_element.find('input');
    input.val(editable_fields[id].current_value);
    input.focus();
  } else {
    var new_content = editable_fields[id].current_value == "" ?
      unknownFieldMarkup :
      editable_fields[id].current_value;

    value_element.html(new_content);
    $('#editContactInfoModal').modal('hide');
  }
}

$(document).ready(function() {

  setupEditableContactInfoFields();

});
