$(document).ready(function() {
  $('select[multiple]').each(function() {
    var select = $(this).on({
      "focusout": function() {
        var values = select.val() || [];
        setTimeout(function() {
          select.val(values.length ? values : ['']).change();
        }, 1000);
      }
    });
  });
  $('#zip').multiselect();
  $('#city').multiselect();
  $('#zoning').multiselect();
  $('#tax').multiselect();
  $('#oct').multiselect();
});

/*=======================For search custom search page ================================*/

$(document).ready(function() {
  $('.schname').keypress(function(e) {
    if (e.which === 32)
      return false;
  });
});


$(document).ready(function() {
  $('#datetimepicker6').datetimepicker({
    useCurrent: false
  });
  $("#sttask").val('');
  $('#sttask').datetimepicker({
    //  useCurrent: false
    minDate: moment()

  });

  $('#sales_date_from').datetimepicker({
    format: 'm/d/Y',
    timepicker: false
  });
  $('#sales_date_to').datetimepicker({
    format: 'm/d/Y',
    timepicker: false
  });
});


$(document).ready(function() {

  $('#btnUpload').click(function() {

    var fileUpload = $("#import_list").get(0);
    var files = fileUpload.files;

    // Create FormData object
    var fileData = new FormData();

    // Looping over all files and add it to FormData object
    for (var i = 0; i < files.length; i++) {
      fileData.append('import_list', files[i]);
    }
    $(".wait").show();
    $.ajax({
      url: 'import.php',
      type: "POST",
      contentType: false, // Not to set any content header
      processData: false, // Not to process data
      data: fileData,
      dataType: 'json',
      success: function(result) {
        console.log(result);
        if (result.status == 'sucess') {
          $(".wait").hide();
          $("#overlay1").show();
          $(".msg").html('File uploaded successfully. <br/> Inserted record = ' + result.insert + '<br/> Duplicate record = ' + result.duplicate);
          $('#upload_csv')[0].reset();
        } else {
          alert("Invalid file format");
          $(".wait").hide();
        }

      },
      error: function(err) {
        alert(err.statusText);
      }
    });

    return false;

  });

  $('#searchn').click(function(e) {
    e.preventDefault();
    $('#searchsubmit').val('searchnow');
    $("#upload_csv").submit();



  });

  $('#submits').click(function(e) {
    e.preventDefault();
    var datepick = $('#datetimepicker6').val();
    if (datepick == "") {
      $("#datetimepicker6").css("border", "1px solid red");
      alert("Please select date and time");
    } else {
      $('#searchsubmit').val('submitsearch');
      $("#overlay").show();
      $("#batchform").show();
      return false;
      // $("#upload_csv").submit();
    }


  });

  $("#schbatchname").blur(function(e) {
    e.preventDefault();
    var schname = $(this).val();
    $('#schedulername').val(schname);
    if (schname) {

      jQuery.ajax({
        type: "POST",
        url: "lead_check_scheduler.php",
        dataType: 'json',
        data: {
          'name': schname
        },
        success: function(data) {
          console.log(data);

          if (data.msg == 'pass') {

            $('#schedulername').val(schname);
          } else {
            $('#schedulername').val('');
            $('#schbatchname').val('');
            alert("Scheduled Name alredy exist");

          }

        }



      });

      return false;



    } else {

      alert("Please enter scheduler name");
      return false;
    }

  });

  $("#scdsearch").click(function() {

    var schname = $("#schbatchname").val();
    if (schname) {

      $("#upload_csv").submit();
    } else {

      alert("Please enter scheduler name");
      return false;
    }


  });


});

function something_happens() {
  input.replaceWith(input.val('').clone(true));
};


$(document).ready(function() {
  $("#checkAll").click(function() {
    $('input:checkbox').not(this).prop('checked', this.checked);
    getValueUsingClass();
  });

  $(".chk").click(function() {
    getValueUsingClass();
  });


  $("#batch").click(function() {
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
          console.log(data);
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


  $("#scdbatch").click(function() {
    var check = $("#ckeckvall").val();
    var name = $("#batchname").val();
    if (name == "") {
      $(".errormsg").show();
    } else {
      $(".errormsg").hide();
      jQuery.ajax({
        type: "POST",
        url: "lead_add_schedulebatch.php",
        dataType: 'json',
        data: {
          'check': check,
          'name': name
        },
        success: function(data) {
          console.log(data);

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


  $(".closeimg").click(function() {
    $("#overlay").hide();
    $("#batchform").hide();
    $("#batchname").val('');
  });

  $("#closebtn").click(function() {
    $("#overlay").hide();
    $("#batchform").hide();
    $("#batchname").val('');
    return false;
  });


  $(".taskcloseimg").click(function() {
    $("#overlay").hide();
    $("#batchform").hide();
    $("#taskname").val('');
    $("#sttask").val('');
    $("#taskintervald").prop("checked", false);
    $("#taskintervalw").prop("checked", false);
    $("#taskintervalm").prop("checked", false);
    $("#taskintervaly").prop("checked", false);
  });

  $(".uploadcloseimg").click(function() {
    $("#overlay1").hide();
    $(".msg").html();
    location.reload();
  });


  $("#expcsvbtn").click(function() {

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

          /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
           window.open(uri, 'test.csv');*/

        }
      });


    } else {

      alert("Please select at least one check box");
      return false;
    }

  });


  /* For schedule page export csv*/

  $("#schexpcsvbtn").click(function() {

    var check = $("#ckeckvall").val();
    filename = "customsearch.csv"
    if (check) {

      jQuery.ajax({
        type: "POST",
        url: "lead_schedule_exportdata.php",

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

          /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
           window.open(uri, 'test.csv');*/

        }
      });


    } else {

      alert("Please select at least one check box");
      return false;
    }

  });

});

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


  /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
  /*if(selected.length > 0){
		alert("You have selected " + selected);
	}else{
		alert("Please at least check one of the checkbox");
    }*/

}


$(document).ready(function() {
  $("#loginbtn").click(function() {
    var uname = $("#username").val();
    var password = $("#password").val();

    //alert(uname);  alert(password);

    jQuery.ajax({
      type: "POST",
      url: "signin.php",
      dataType: 'json',
      data: {
        'uname': uname,
        'password': password
      },
      success: function(data) {
        console.log(data);

        if (data.msg == 'notuser') {
          $(".error").show();
          $(".blnkerror").hide();
          $(".box").css("border", "1px solid red");
        } else if (data.msg == 'user') {
          window.location.href = "index.php";
        } else if (data.msg == 'blank') {
          $(".blnkerror").show();
          $(".error").hide();
          $(".box").css("border", "1px solid red");
        }

      }
    });

    return false;

  });

  $("#username").keyup(function() {
    $(".blnkerror").hide();
    $(".error").hide();
    $(".box").css("border", "1px solid #000");
  });

  $("#password").keyup(function() {
    $(".blnkerror").hide();
    $(".error").hide();
    $(".box").css("border", "1px solid #000");
  });

});


/*  for delete batches*/
function deletebatch(id) {
  if (confirm("Are you sure you want to delete this?")) {
    jQuery.ajax({
      type: "POST",
      url: "lead_delete_schedulebatch.php",
      dataType: 'json',
      data: {
        'id': id
      },
      success: function(data) {
        console.log(data);
        alert("Batch deleted successfully");
        location.reload();
      }
    });
  } else {
    return false;
  }
}

function exportschedule(id) {
  filename = "scheduledsearch.csv"
  jQuery.ajax({
    type: "POST",
    url: "lead_export_scheduledata.php",

    data: {
      'id': id
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

      /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
       window.open(uri, 'test.csv');*/

    }
  });
  return flase;

}

jQuery(document).ready(function() {
  var schid = $('#reschid').val();
  if (schid) {

    jQuery.ajax({
      type: "POST",
      url: "lead_reschedule_search.php",
      dataType: 'json',
      data: {
        'id': schid
      },
      success: function(data) {
        if (data.status == "success") {

          $('#num_units_min').val(data.result.num_units_min);
          $('#num_units_max').val(data.result.num_units_max);
          $('#num_bedrooms_min').val(data.result.num_bedrooms_min);
          $('#num_bedrooms_max').val(data.result.num_bedrooms_max);
          $('#num_baths_min').val(data.result.num_baths_min);
          $('#num_baths_max').val(data.result.num_baths_max);
          $('#num_stories_min').val(data.result.num_stories_min);
          $('#num_stories_max').val(data.result.num_stories_max);
          $("input[name=fmlytype][value='" + data.result.fmlytype + "']").prop("checked", true);
          $("input[name=sfmlytype][value='" + data.result.sfmlytype + "']").prop("checked", true);

          var citydata = data.result.city;
          if (citydata.indexOf(',') > -1) {
            var valArr = citydata.split(',');
          } else {
            var valArr = citydata;

          }


          var zipdata = data.result.zip;
          if (zipdata.indexOf(',') > -1) {
            var zipArr = zipdata.split(',');
          } else {
            var zipArr = zipdata;
          }






          i = 0, size = valArr.length;
          if (size > 0) {
            for (i; i < size; i++) {
              $('#city_to').append("<option value='" + valArr[i] + "'>" + valArr[i] + "</option>");
              $("#city option[value='" + valArr[i] + "']").remove();
            }
          }

          i = 0, zsize = zipArr.length;
          if (zsize > 0) {
            for (i; i < zsize; i++) {
              $('#zip_to').append("<option value='" + zipArr[i] + "'>" + zipArr[i] + "</option>");
              $("#zip option[value='" + zipArr[i] + "']").remove();
            }
          }
          // console.log(data.result.num_bedrooms_min)


        }

      }
    });
  }

});


function leadbatchexport(id) {

  filename = "leadexport.csv"
  jQuery.ajax({
    type: "POST",
    url: "lead_exportcsv.php",

    data: {
      'id': id
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

      /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
       window.open(uri, 'test.csv');*/

    }
  });
  return flase;
}

function deleteschedule(id) {
  if (confirm("Are you sure you want to delete this?")) {
    jQuery.ajax({
      type: "POST",
      url: "lead_delete_schedule.php",
      dataType: 'json',
      data: {
        'id': id
      },
      success: function(data) {
        console.log(data);
        alert("Batch deleted successfully");
        location.reload();
      }
    });
  } else {
    return false;
  }

}

/*  for delete batches*/
function cdeletebatch(id) {
  if (confirm("Are you sure you want to delete this?")) {
    jQuery.ajax({
      type: "POST",
      url: "lead_delete_custombatch.php",
      dataType: 'json',
      data: {
        'id': id
      },
      success: function(data) {
        console.log(data);
        alert("Batch deleted successfully");
        location.reload();
      }
    });
  } else {
    return false;
  }
}


function cleadbatchexport(id) {

  filename = "leadexport.csv"
  jQuery.ajax({
    type: "POST",
    url: "lead_export_custombatch.php",

    data: {
      'id': id
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

      /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
       window.open(uri, 'test.csv');*/

    }
  });
  return flase;
}

function customschedulebatch(id) {
  if (confirm("Are you sure you want to reschedule batch search?")) {

    jQuery.ajax({
      type: "POST",
      url: "lead_schedule_custombatch.php",
      dataType: 'json',
      data: {
        'id': id
      },
      success: function(data) {
        if (data.msg == "success") {

          alert('Reschedule batch search successfully');
        }

      }
    });

    return false;
  } else {


    return false;
  }

}


function reschedulebatch(id) {

  if (confirm("Are you sure you want to reschedule batch search?")) {

    jQuery.ajax({
      type: "POST",
      url: "lead_reschedulebatch.php",
      dataType: 'json',
      data: {
        'id': id
      },
      success: function(data) {
        if (data.msg == "success") {

          alert('Reschedule batch search successfully');
        }

      }
    });

    return false;
  } else {

    return false;
  }

}

function opencasedetail(apn, cid, ctyid) {
  jQuery.ajax({
    type: "POST",
    url: "lead_case_detail.php",
    data: {
      'apn': apn,
      'case_id': cid,
      'case_det_id': ctyid
    },
    success: function(data) {
      $('.casedata').html(data);
    }
  });

  return false;
}


function liveopencasedetail(apn, cid, ctype) {
  $('.wait').show();
  //alert(apn); alert(cid); alert(ctype);
  jQuery.ajax({
    type: "POST",
    url: "livecasedetail.php",
    data: {
      'apn': apn,
      'case_id': cid,
      'case_type': ctype
    },
    success: function(data) {
      console.log(data);
      $('.wait').hide();
      $('.casedata').html(data);

      //alert( "Batch deleted successfully" );
      //location.reload();
    }
  });
  return false;
}





function timesearch(id) {
  $("#overlay").show();
  $("#batchform").show();
  var batchid = id;
  $("#batchid").val(batchid);

  //$('#sttask').datetimepicker({format:'m/d/Y',timepicker:false});

  $.ajax({
    type: "POST",
    url: "lead_get_scheduletask.php",
    dataType: 'json',
    data: {
      'batchid': batchid
    },
    success: function(response) {
      console.log(response);
      if (response.msg == 'alredyadd') {
        $(".taskdelete").show();
        $("#taskname").val(response.result.taskname);
        $("#sttask").val(response.result.date);
        var l = response.result.period;
        if (l == "daily") {
          $("#taskintervald").prop("checked", true);
        } else if (l == "weekly") {
          $("#taskintervalw").prop("checked", true);
        } else if (l == "monthly") {
          $("#taskintervalm").prop("checked", true);
        } else if (l == "yearly") {
          $("#taskintervaly").prop("checked", true);
        }
        $(".taskdelete").show();
        $(".etime").hide();
        $(".btntext").html('');
        $(".btntext").html('Update');
        $(".succmsg").html('');
        $(".succmsg").html('Task Update successfully.');

      } else {
        $(".btntext").html('');
        $(".btntext").html('Submit');
        $(".taskdelete").hide();
      }
    }

  });


}



function tasksubmit() {
  var taskname = $("#taskname").val();
  var taskdate = $("#sttask").val();
  var checked = $("input[type=radio]:checked").length;

  if (taskname == null || taskname == "") {
    $(".etask").show();
    $("#taskname").css("border", "1px solid red");
    return false;
  } else {
    $("#taskname").css("border", "1px solid #ccc");
  }

  if (taskdate == null || taskdate == "") {
    $(".edate").show();
    $("#sttask").css("border", "1px solid red");
    return false;
  } else {
    $("#sttask").css("border", "1px solid #ccc");
  }


  if (!checked) {
    $(".etime").show();
    return false;
  } else {

    jQuery.ajax({
      type: "POST",
      url: "lead_add_scheduletask.php",
      dataType: 'json',
      data: $("#schtask input").serialize(),
      success: function(data) {
        console.log(data);

        if (data.msg == 'Add') {
          $("#taskname").val('');
          $("#sttask").val('');
          $(".etime").hide();
          $(".succmsg").show();
          $("#taskintervald").prop("checked", false);
          $("#taskintervalw").prop("checked", false);
          $("#taskintervalm").prop("checked", false);
          $("#taskintervaly").prop("checked", false);
          setTimeout(function() {
            $("#overlay").hide();
            // $("#batchform").hide();
            $(".succmsg").hide();
          }, 5000);
        } else {
          $(".succmsg").html('');
          $(".succmsg").html('Task Update successfully.');
          $(".succmsg").show();
          setTimeout(function() {
            $("#overlay").hide();
            // $("#batchform").hide();
            $(".succmsg").hide();
          }, 5000);
        }

      }
    });

  }

  return false;


}




function taskdelete() {
  jQuery.ajax({
    type: "POST",
    url: "lead_stop_scheduler.php",
    dataType: 'json',
    data: $("#schtask input").serialize(),
    success: function(data) {
      console.log(data);
      if (data.msg == 'delete') {
        $(".succmsg").html('');
        $(".succmsg").html('Task remove successfully.');
        $(".succmsg").show('');
        $("#taskname").val('');
        $("#sttask").val('');
        $("#taskintervald").prop("checked", false);
        $("#taskintervalw").prop("checked", false);
        $("#taskintervalm").prop("checked", false);
        $("#taskintervaly").prop("checked", false);
        setTimeout(function() {
          $("#overlay").hide();
          // $("#batchform").hide();
          $(".succmsg").hide();
        }, 5000);


      }

    }
  });

  return false;
}


/* custum leadbatch resuldel */


function customtimesearch(id, name) {
  $("#overlay").show();
  $("#batchform").show();
  var curr = moment().format('MM/DD/YYYY hh:mm A');
  var batchid = id;
  var batchname = name;
  $("#batchid").val(batchid);
  $("#taskname").val(batchname);

  //$('#sttask').datetimepicker({format:'m/d/Y',timepicker:false});

  $.ajax({
    type: "POST",
    url: "lead_schedule_customtask.php",
    dataType: 'json',
    data: {
      'batchid': batchid
    },
    success: function(response) {
      console.log(response);
      if (response.msg == 'alredyadd') {
        $(".taskdelete").show();
        $("#taskname").val(response.result.taskname);
        $("#sttask").val(response.result.date);
        var l = response.result.period;
        if (l == "daily") {
          $("#taskintervald").prop("checked", true);
        } else if (l == "weekly") {
          $("#taskintervalw").prop("checked", true);
        } else if (l == "monthly") {
          $("#taskintervalm").prop("checked", true);
        } else if (l == "yearly") {
          $("#taskintervaly").prop("checked", true);
        }
        $(".btntext").html('');
        $(".btntext").html('Update');
        $(".succmsg").html('');
        $(".succmsg").html('Task Update successfully.');

      } else {
        $(".btntext").html('');
        $(".btntext").html('Submit');
        $(".taskdelete").hide();
        $("#sttask").val(curr);

      }
    }

  });


}



function customtasksubmit() {
  var taskname = $("#taskname").val();
  var taskdate = $("#sttask").val();
  var checked = $("input[type=radio]:checked").length;

  if (taskname == null || taskname == "") {
    $(".etask").show();
    $("#taskname").css("border", "1px solid red");
    return false;
  } else {
    $("#taskname").css("border", "1px solid #ccc");
  }

  if (taskdate == null || taskdate == "") {
    $(".edate").show();
    $("#sttask").css("border", "1px solid red");
    return false;
  } else {
    $("#sttask").css("border", "1px solid #ccc");
  }

  if (!checked) {
    $(".etime").show();
    return false;
  } else {
    jQuery.ajax({
      type: "POST",
      url: "lead_add_customtask.php",
      dataType: 'json',
      data: $("#schtask input").serialize(),
      success: function(data) {
        console.log(data);
        if (data.msg == 'Add') {
          $("#taskname").val('');
          $("#sttask").val('');
          $(".etime").hide();
          $(".succmsg").show();
          $("#taskintervald").prop("checked", false);
          $("#taskintervalw").prop("checked", false);
          $("#taskintervalm").prop("checked", false);
          $("#taskintervaly").prop("checked", false);
          $(".succmsg").html('');
          $(".succmsg").html('Task Create successfully.');
          location.reload();
          /*setTimeout(function(){
                     $("#overlay").hide();
                     // $("#batchform").hide();
                      $(".succmsg").hide();
                  }, 5000);*/
        } else {
          $(".succmsg").html('');
          $(".succmsg").html('Task Updated successfully.');
          $(".succmsg").show();
          location.reload();
          /*setTimeout(function(){
                     $("#overlay").hide();
                     // $("#batchform").hide();
                      $(".succmsg").hide();
                  }, 5000);*/
        }





      }
    });
  }
  return false;


}

function customtaskdelete() {
  jQuery.ajax({
    type: "POST",
    url: "lead_delete_customtask.php",
    dataType: 'json',
    data: $("#schtask input").serialize(),
    success: function(data) {
      console.log(data);
      if (data.msg == 'delete') {
        $(".succmsg").html('');
        $(".succmsg").html('Task remove successfully.');
        $(".succmsg").show('');
        $("#taskname").val('');
        $("#sttask").val('');
        $("#taskintervald").prop("checked", false);
        $("#taskintervalw").prop("checked", false);
        $("#taskintervalm").prop("checked", false);
        $("#taskintervaly").prop("checked", false);
        location.reload();
        /*setTimeout(function(){
                     $("#overlay").hide();
                     // $("#batchform").hide();
                      $(".succmsg").hide();
                  }, 5000);*/


      }

    }
  });

  return false;
}

$(document).ready(function() {
  $("#taskname").keyup(function() {
    $(".etask").hide();
    $("#taskname").css("border", "1px solid #ccc;");
  });

  $("#sttask").blur(function() {
    $(".edate").hide();
    $("#sttask").css("border", "1px solid #ccc;");
  });
});


$(document).ready(function() {

  $.urlParam = function(name) {

    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);

    if (results != null)
      return results[1] || 0;

  }

  var apn = $.urlParam('apn');

  var cid = $.urlParam('case_id');

  if (apn && cid) {

    jQuery.ajax({

      type: "POST",

      url: "lead_case_detail.php",

      data: {
        'apn': apn,
        'case_id': cid
      },

      success: function(data) {

        console.log(data);

        $('.casedata').html(data);



        //alert( "Batch deleted successfully" );

        //location.reload();

      }

    });

    return false;



  }



});

jQuery(document).ready(function($) {
  $('.number').keyup(function(e) {
    if (/\D/g.test(this.value)) {
      // Filter non-digits from input value.
      this.value = this.value.replace(/\D/g, '');
    }
  });


});

function scrapperinst(btnval) {

  var total_instances = $('#scp_total_instances').val();
  var total_records = $('#scp_total_records').val();
  var scrapperid = $('#scrapperid').val();
  var submitval = btnval;
  if ((total_instances.length == 0)) {
    alert("Please enter total instance");
    $("#scp_total_instances").focus();
    return false;
  } else if ((total_records.length == 0)) {
    alert("Please enter and number of records");
    $("#scp_total_records").focus();
    return false
  }


  jQuery.ajax({

    type: "POST",
    url: "leads_scrapper_data.php",
    dataType: 'json',
    data: {
      'total_instances': total_instances,
      'total_records': total_records,
      'scrapperid': scrapperid,
      'submit': submitval
    },

    success: function(data) {
      console.log(data.status);
      if (data.status == "save") {
        alert("Save data successfully");

        location.reload();
      } else if (data.status == "remove") {

        alert("Remove data successfully");
        location.reload();
      }

    }
  });


  return false;

};

function startaudit(a) {
  batchid = a;
  if (confirm("Are you sure you want to start audit?")) {
    jQuery.ajax({

      type: "POST",
      url: "lead_audit_start.php",
      dataType: 'json',
      data: {
        'batchid': batchid
      },
      success: function(data) {

        if (data.status = "success") {
          alert("audit scheduled successfully");

        } else {
          alert("audit not scheduled");

        }

      }
    });

    return false;
  } else {

    return false;
  }



}




function createallscheduler() {



  $("#overlay3").show();

  $("#allschedulerform").show();

  return false;

}



$(document).ready(function() {

  $(".staskcloseimg").click(function() {

    $("#overlay3").hide();

    $("#allschedulerform").hide();

  });

  $('.dateinte').datetimepicker({

    minDate: moment()



  });



});

function stopscheduler() {

  $("#overlay4").show();
  $("#stopschedulerform").show();
}

function toggle(source) {
  var checkboxes = document.querySelectorAll('.batchcheck');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i] != source)
      checkboxes[i].checked = source.checked;
  }
}

function batchtoggle(source) {
  var checkboxes = document.querySelectorAll('.batchallcheck');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i] != source)
      checkboxes[i].checked = source.checked;
  }
}

$(document).ready(function() {

  $(".stopcloseimg").click(function() {

    $("#overlay4").hide();

    $("#stopschedulerform").hide();

  });

});

function customallschsubmit() {
  var allstask = $("#allstask").val()
  var alltaskinterval = $("#alltaskinterval").val();
  var m_data = $('#createallbatch').serialize();
  if ($('#createallbatch').find('input[type=checkbox]:checked').length == 0) {
    alert('Please select atleast one checkbox');
    return false;
  }

  if (alltaskinterval && allstask) {

    jQuery.ajax({


      type: "POST",

      url: "lead_create_schedule.php",

      dataType: 'json',

      data: m_data,

      success: function(data) {

        if (data.msg = "Add") {

          alert("Selected scheduler created successfully");

          location.reload();



        } else {



          alert("error create selected scheduler ");

        }


      }


    });

  } else {

    alert("Please enter start task and task interval ")
  }

  return false;

}

function stopallschsubmit() {
  var m_data = $('#stopschtask').serialize();

  if ($('#stopschtask').find('input[type=checkbox]:checked').length == 0) {
    alert('Please select at least one checkbox');
    return false;
  }


  if (confirm("Are you sure you want to stop selected scheduler ?")) {

    jQuery.ajax({



      type: "POST",

      url: "lead_stop_all_scheduler.php",

      dataType: 'json',

      data: m_data,

      success: function(data) {



        if (data.status = "delete") {

          alert("Selected scheduler stop successfully");

          $("#overlay3").hide();

          $("#allschedulerform").hide();

          location.reload();

        }



      }

    });

    return false;
  } else {

    return false;

  }

}
