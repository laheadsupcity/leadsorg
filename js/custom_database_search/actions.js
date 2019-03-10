function getValueUsingClass() {
  var chkArray = [];

  /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
  $(".chk:checked").each(function() {
    chkArray.push($(this).val());
  });

  /* we join the array separated by the comma */
  var selected;
  selected = chkArray.join(',');

  $("#ckeckvall").val(selected);
}

$(document).ready(function() {

  $(".chk").click(function() {
    getValueUsingClass();
  });

  $("#checkAll").click(function() {
    $('input:checkbox').not(this).prop('checked', this.checked);
    getValueUsingClass();
  });

  $("#create_batch_button").click(function() {
    var check = $("#ckeckvall").val();
    if (check) {
      $('.leadbatch').prop('disabled', false);
      $("#overlay").show();
      $("#batchform").show();
    } else {
      alert("Please select at least one check box");
      return false;
    }
  });

  $("#batchsubmit").click(function() {
    var check = $("#ckeckvall").val();
    var name = $("#batchname").val();
    if (name == "") {
      $(".errormsg").show();
    } else {
      $(".errormsg").hide();
      jQuery.ajax({
        type: "POST",
        url: "lead_batch.php",
        dataType: 'json',
        data: {
          'check': check,
          'name': name
        },
        success: function(data) {
          if (data.msg == 'Add') {
            $('.leadbatch').prop('disabled', true);
            $("#batchname").val('');
            $(".succmsg").show();
            setTimeout(function() {
              $("#overlay").hide();
              $("#batchform").hide();
              $(".succmsg").hide();
            }, 5000);
          } else {
            alert("Batch name alredy exist");
          }
        }
      });
    }

    return false;
  });

  $("#export_properties_csv_button").click(function() {

    var check = $("#ckeckvall").val();
    filename = "customsearch.csv"
    if (check) {

      jQuery.ajax({
        type: "POST",
        url: "lead_export.php",

        data: {
          'check': check
        },
        success: function(response) {
          // console.log(response);
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
