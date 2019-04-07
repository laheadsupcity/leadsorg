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
    $('#selectPropertiesWarning').removeClass('d-block').addClass('d-none');
  });

  $('#batchNameExists').alert();
  resetLeadBatchModal();

  $("#checkAll").click(function() {
    $('[data-property-checkbox]').not(this).prop('checked', this.checked);
  });

  // reset create lead batch modal
  $('#createLeadBatchModal').on('hide.bs.modal', function() {
    resetLeadBatchModal();
  });

  $('[data-target="#createLeadBatchModal"],[data-target="#addToFavoritesFolderModal"]').click(function(event) {
    event.stopPropagation();

    var checked_properties = getCheckedProperties();

    if (checked_properties.length == 0) {
      $('#selectPropertiesWarning').addClass('d-block');
    } else {
      var target_modal = $(event.target).data('target');
      $(target_modal).modal('show');
    }
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

  $("#export_properties_csv_button").click(function() {

    var checked_properties = getCheckedProperties();
    filename = "customsearch.csv"
    if (checked_properties) {
      jQuery.ajax({
        type: "POST",
        url: "lead_export.php",

        data: {
          'check': checked_properties
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

});
