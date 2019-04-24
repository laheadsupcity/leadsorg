function getSelectedProperties() {
  var selected_property_data = [];

  $("[data-property-checkbox]:checked").each(function() {
    var parcel_number = $(this).val(),
        property_element = $('[data-parcel_number=' + parcel_number + ']')
        matching_cases = property_element.data('matching-cases-string'),
        private_note = property_element.find('[data-property-note].private-notes-column [data-field-value]').html(),
        public_note = property_element.find('[data-property-note].public-notes-column [data-field-value]').html();

    selected_property_data.push({
      parcel_number: parcel_number,
      matching_cases: matching_cases,
      private_note: private_note,
      public_note: public_note
    });
  });

  return selected_property_data;
}

function getCheckedProperties() {
  var checkedProperties = [];

  $("[data-property-checkbox]:checked").each(function() {
    checkedProperties.push($(this).val());
  });

  return checkedProperties.join(',');
}

function resetLeadBatchModalErrors() {
  var modal = $('#createLeadBatchModal');
  $(modal).find('.is-invalid').removeClass('is-invalid');
}

function resetLeadBatchModal() {
  var modal = $('#createLeadBatchModal');
  resetLeadBatchModalErrors();

  $('#batchSuccess').hide();
  $('#batchNameExists').hide();

  $(modal).find('#batchName').val("");
}

$(document).ready(function() {

  $("[data-property-checkbox],#checkAll").change(function() {
    $('#selectPropertiesWarning').prop('hidden', true);
  });

  var create_batch_button = $('[data-target="#createLeadBatchModal"]');
  if (create_batch_button.length > 0) {
    $('#batchNameExists').alert();
    resetLeadBatchModal();

    // reset create lead batch modal
    $('#createLeadBatchModal').on('hide.bs.modal', function() {
      resetLeadBatchModal();
    });

    $("[data-action=batch_submit]").click(function(event) {
      var checked_properties = getCheckedProperties(),
          name_input = $("#batchName");

      var name = name_input.val();

      if (name == "") {
        name_input.addClass('is-invalid');
        return;
      } else {
        resetLeadBatchModalErrors();

        jQuery.ajax({
          type: "POST",
          url: "lead_batch.php",
          dataType: 'json',
          data: {
            'check': checked_properties,
            'name': name
          },
          success: function(data) {
            if (data.msg == 'Add') {
              $('#batchNameExists').hide();
              $("#batchSuccess").show();
              setTimeout(function() {
                $('#createLeadBatchModal').modal('hide')
              }, 2000);
            } else {
              $('#batchNameExists').show();
            }
          }
        });
      }

      return false;
    });
  }

  $("#checkAll").click(function() {
    $('[data-property-checkbox]').not(this).prop('checked', this.checked);
  });

  $('[data-target="#createLeadBatchModal"],[data-target="#addToFavoritesFolderModal"]').click(function(event) {
    event.stopPropagation();

    var checked_properties = getCheckedProperties();

    if (checked_properties.length == 0) {
      $('#selectPropertiesWarning').prop('hidden', false);
    } else {
      var target_modal = $(event.target).data('target');
      $(target_modal).modal('show');
    }
  });

  $("#export_properties_csv_button").click(function() {

    var selected_property_data = getSelectedProperties(),
        filename = "customsearch.csv";

    if (selected_property_data) {
      jQuery.ajax({
        type: "POST",
        url: "lead_export.php",
        data: {
          'selected_property_data': selected_property_data
        },
        success: function(response) {
          var type = 'application/csv';
          var blob = new Blob([response], {
            type: type
          });

          if (typeof window.navigator.msSaveBlob !== 'undefined') {
            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
            window.navigator.msSaveBlob(blob, filename);
          } else {
            var URL = window.URL || window.webkitURL;
            var downloadUrl = URL.createObjectURL(blob);

            if (filename) {
              // use HTML5 a[download] attribute to specify filename
              var a = document.createElement("a");
              // safari doesn't support this yet
              if (typeof a.download === 'undefined') {
                window.location = downloadUrl;
              } else {
                a.href = downloadUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
              }
            } else {
              window.location = downloadUrl;
            }

            setTimeout(function() {
              URL.revokeObjectURL(downloadUrl);
            }, 100); // cleanup
          }
        }
      });
    } else {
      alert("Please select at least one check box");
      return false;
    }
  });

  $('[data-parcel_number]').click(function(event) {
    var target = $(event.target)

    var is_editable_field =
      target.parents('.editable-field').length > 0 ||
      target.hasClass('editable-field');
    if (is_editable_field) {
      return;
    }

    var is_input = target.is('input');
    if (is_input) {
      return;
    }

    var is_anchor = target.is('a');
    if (is_anchor) {
      return;
    }

    var is_actions = target.parents('[data-actions]').length > 0;
    if (is_actions) {
      return;
    }

    window.open($(event.currentTarget).data('property-url'));
  });

});
