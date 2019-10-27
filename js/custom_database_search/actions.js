function getSelectedProperties(results_id) {
  return $("[data-results-id=" + results_id + "] [data-property-checkbox]:checked").map(function(index, checkbox) {
    return checkbox.value;
  }).toArray();
}

function getCheckedProperties() {
  var checkedProperties = [];

  $("[data-property-checkbox]:checked").each(function() {
    checkedProperties.push($(this).val());
  });

  return checkedProperties;
}

function openProperty(property_element) {
  window.open($(property_element).data('property-url'));
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

function handleOpenAll() {
  var selected_properties = getCheckedProperties();

  selected_properties.forEach(function(parcel_number) {
    let property = $('[data-parcel_number="' + parcel_number + '"]')
    openProperty(property);
  });
}

$(document).ready(function() {

  $(document).on('change', "[data-property-checkbox],[data-check-all]", function() {
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

    $(document).on('click', "[data-action=batch_submit]", function(event) {
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
            'check': checked_properties.join(','),
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

  $(document).on('click', "[data-check-all]", function() {
    $(this).closest('.property-list-group').find('[data-property-checkbox]').not(this).prop('checked', this.checked);
  });

  $(document).on('click', '[data-target="#createLeadBatchModal"],[data-target="#addToFavoritesFolderModal"]', function(event) {
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
    var selected_property_data = getSelectedProperties("custom_database_search_results"),
        filename = "customsearch.csv";

    if (selected_property_data) {
      jQuery.ajax({
        type: "POST",
        url: "lead_export.php",
        data: {
          'user_id': getUserID(),
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

  $(document).on('click', '[data-parcel_number]', function(event) {
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

    openProperty($(event.currentTarget));
  });

  $(document).on('click', '[data-action="open_all"]', function(event) {
    handleOpenAll();
  });

});

/// UTILITIES

function getUserID() {
  // TO DO: This is temporary hack until user sessions are handled properly
  return $('[data-user-id]').data('user-id');
}

/// UTILITIES
