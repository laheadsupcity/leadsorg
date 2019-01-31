$(document).ready(function() {
	$('select[multiple]').each(function(){
		var select = $(this).on({
			"focusout": function(){
			var values = select.val() || [];
			setTimeout(function(){
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

/*=======================For save custom search filter================================*/
function savefilter(){
	var filtername=$("#filtername").val();
	if(filtername !='')
	{
		var formData=$('#cdsearchform').serializeArray();
		zip = []; city=[]; zoning=[]; exemption=[]; casetype=[];optype=[];
			$('#zip_to option').each(function(){
				zip.push($(this).val())
				formData.push({ name: "zipto", value: zip });
			});
            
			$('#city_to option').each(function() {
				city.push($(this).val())
				formData.push({ name: "cityto", value: city });
			});
        
			$('#zoning_to option').each(function() {
				zoning.push($(this).val())
				formData.push({ name: "zoningto", value: zoning });
			});

			$('#tax_to option').each(function() {
				exemption.push($(this).val())
				formData.push({ name: "exemptionto", value: exemption });
			});

			$('#oct_to option').each(function() {
				casetype.push($(this).val())
				formData.push({ name: "casetypeto", value: casetype });
			});

			var sname=$('#filtername').val(); 
			jQuery.ajax({
				type: "POST",
				url: "lead_savefilter.php",
				dataType: 'json',
				data:formData,
				success: function(data){
					if(data.status=='sucess'){
						alert("Search saved successfully");
						$("#datalistname").append('<option value='+sname+'>'+sname+'</option>');
					}
					else if(data.status=='update'){
						alert("Search updated successfully");
					}
					else {
						alert("Search save name already in used");
					}
				}
			})
	}

	 else{
		alert("Please enter filter name");
		}
	return false;
 }

/*=======================For search custom search page ================================*/

$(document).ready(function() {
	$("#filtername").on('input', function () {    
	var val = this.value;
	if($('#datalistname').find('option').filter(function(){
	return this.value.toUpperCase() === val.toUpperCase();        
	}).length) {
	var filtername=this.value;
	jQuery.ajax({
	type: "POST",
	url: "lead_get_customdata.php",
	dataType: 'json',
	data:{'name':filtername},
	success: function(data){
	console.log(data); 
	$('#nouf').val(data.nouf);
	$('#nout').val(data.nout);
	$('#nbedf').val(data.nbedf);
	$('#nbedt').val(data.nbedt);
	$('#nbathf').val(data.nbathf);
	$('#nbatht').val(data.nbatht);
	$('#nstrf').val(data.nstrf);
	$('#nstrt').val(data.nstrt);
	$('#cpsf').val(data.cpsf);
	$('#cpst').val(data.cpst);
	$('#lasqf').val(data.lasqf);
	$('#lasqt').val(data.lasqt);
	$('#sprf').val(data.sprf);
	$('#sprt').val(data.sprt);
	$('#ybrf').val(data.ybrf);
	$('#ybrt').val(data.ybrt);
	$('#sdrf').val(data.sdrf);
	$('#sdrt').val(data.sdrt);
	$('#searchid').val(data.searchid);
	$("input[name=ooc][value='"+data.ooc+"']").prop("checked",true);
	$("input[name=fmlytype][value='"+data.fmlytype+"']").prop("checked",true);
	$("input[name=sfmlytype][value='"+data.sfmlytype+"']").prop("checked",true);
	var citydata=data.city;
	var zipdata=data.zip;
	var zoningdata=data.zoning;
	var exdata=data.exemption;
    var casetypedata=data.casetype;
    var cdate=data.cdate;
    var ctime=data.ctime;
    console.log(casetypedata.length+'------'+cdate.length+'--------'+ctime.length);
	var valArr =citydata.split(',');
	var zipArr=zipdata.split(',');
	var zonArr=zoningdata.split(',');
    var exArrr=exdata.split(',');
  
	//var caseArr=casetypedata.split(',');
	i = 0, size =valArr.length;
	if(size>0){
	for(i; i < size; i++){
	$('#city_to').append("<option value='"+valArr[i]+"'>"+valArr[i]+"</option>");
	$("#city option[value='"+valArr[i]+"']").remove();
	}
	} 
	i = 0, zsize =zipArr.length;
	if(zsize>0){
	for(i; i < zsize; i++){
	$('#zip_to').append("<option value='"+zipArr[i]+"'>"+zipArr[i]+"</option>");
	$("#zip option[value='"+zipArr[i]+"']").remove();
	}
	}
	i = 0, zosize =zonArr.length;
	if(zosize>0){
	for(i; i < zosize; i++){
	$('#zoning_to').append("<option value='"+zonArr[i]+"'>"+zonArr[i]+"</option>");
	$("#zoning option[value='"+zonArr[i]+"']").remove();
	}
	}
	i = 0, esize =exArrr.length;
	if(esize>0){
	for(i; i < esize; i++){
	$('#tax_to').append("<option value='"+exArrr[i]+"'>"+exArrr[i]+"</option>");
	$("#tax option[value='"+exArrr[i]+"']").remove();
	}
	}
    i = 0, ctysize =casetypedata.length;
    if(ctysize>0){
        for(i; i < ctysize; i++){

            $('.optype').each(function() {
                var cname=$(this).val();
                var id=$(this).attr('id');
                if(cname==casetypedata[i]){
                    $('#'+id).prop( "checked", true );

                }
        
            });
       
        }

    } 

    i = 0, cdsize =cdate.length;
    if(cdsize>0){
        for(i; i < cdsize; i++){
            $('.optype').each(function() {
                var cname=$(this).val();
               var number = $(this).attr("data-id") ;
               if(cname==casetypedata[i]){
               $('.codate'+number).val(cdate[i]);
               $('.codate'+number).attr('disabled', false);
               }

            }); 

            //console.log(cdate[i]);

        }
    } 

    i = 0, ctsize =ctime.length;
    if(ctsize>0){
        for(i; i < ctsize; i++){

            $('.optype').each(function() {
                var cname=$(this).val();
                var number = $(this).attr("data-id") ;
                if(cname==casetypedata[i]){
                $('.cotime'+number).val(ctime[i]);
                $('.cotime'+number).attr('disabled', false);
            }

            }); 

          //  console.log(ctime[i]);

        }
    } 





	}
	});
	}
	else if($("#filtername").val().length ==0){
	$('#cdsearchform')[0].reset();
	$('#oct_to').empty();
	$('#tax_to').empty();
	$('#zoning_to').empty();
	$('#zip_to').empty();
	$('#city_to').empty();
	}
	});
	$('.schname').keypress(function( e ) {
	if(e.which === 32) 
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
   
$('#sdrf').datetimepicker({format:'m/d/Y',timepicker:false});
$('#sdrt').datetimepicker({format:'m/d/Y',timepicker:false});
$('.cdate').datetimepicker({format:'m/d/Y',timepicker:false,scrollInput:false});   
});


$(document).ready(function() {

    $('#btnUpload').click(function () {  

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
            success: function (result) {  
                console.log(result);
                 if(result.status=='sucess'){
                    $(".wait").hide(); 
                   $("#overlay1").show();
                    $(".msg").html('File uploaded successfully. <br/> Inserted record = '+result.insert+'<br/> Duplicate record = '+result.duplicate);
                    $('#upload_csv')[0].reset();
                 }
                 else{
                     alert("Invalid file format");
                     $(".wait").hide();
                 }
                   
            },  
            error: function (err) {  
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
		var datepick=$('#datetimepicker6').val();
		if(datepick==""){
		$("#datetimepicker6").css("border", "1px solid red");
		alert("Please select date and time");
		}
		else{
        $('#searchsubmit').val('submitsearch');
        $("#overlay").show();
        $("#batchform").show();
        return false;
       // $("#upload_csv").submit();
		}
     
       
    });
    
    $("#schbatchname").blur(function(e){
        e.preventDefault();
       var schname=$(this).val();
       $('#schedulername').val(schname);
       if(schname){

        jQuery.ajax({
            type: "POST",
			url: "lead_check_scheduler.php",
			dataType: 'json',
			data:{'name':schname},
			success: function(data){
                console.log(data);

           if(data.msg=='pass'){
          
            $('#schedulername').val(schname);
           }else {
            $('#schedulername').val('');
            $('#schbatchname').val('');
            alert("Scheduled Name alredy exist");

           }
            
            }
 
          

        });

        return false;



       }
       else {

        alert("Please enter scheduler name");
        return false;
       }
        
    });

    $("#scdsearch").click(function () {

        var schname=$("#schbatchname").val();
        if(schname){

            $("#upload_csv").submit();
        }
        else {

            alert("Please enter scheduler name");
            return false;
        }


    });
    

}); 
function something_happens() {
    input.replaceWith(input.val('').clone(true));
};


$(document).ready(function() {
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
        getValueUsingClass();
    });

    $(".chk").click(function() {
	   getValueUsingClass();
    }); 


	$("#batch").click(function() {
	 var check=$("#ckeckvall").val();
	 if(check){
        $('.leadbatch').prop('disabled', false);
		$("#overlay").show();
		$("#batchform").show();
	 }

	 else{
	  alert("Please select at least one check box");
         return false;
	 
	 }
	});


	$("#batchsubmit").click(function() {
	var check=$("#ckeckvall").val();
     var name=$("#batchname").val();
     if (name==""){
        $(".errormsg").show();
     }
     else{
        $(".errormsg").hide();
		jQuery.ajax({
			type: "POST",
			url: "lead_batch.php",
			dataType: 'json',
			data:{'check': check,'name':name},
			success: function(data){
			console.log(data);
			if(data.msg=='Add'){
                $('.leadbatch').prop('disabled', true);
				$("#batchname").val('');
				$(".succmsg").show();
				setTimeout(function(){
				   $("#overlay").hide();
					$("#batchform").hide();
					$(".succmsg").hide();
                }, 5000);
                
			}

			else{
			alert("Batch name alredy exist");
			}
	
			}
        });
    }

		return false;

	});  


	$("#scdbatch").click(function() {
	var check=$("#ckeckvall").val();
     var name=$("#batchname").val();
     if (name==""){
        $(".errormsg").show();
     }
     else{
        $(".errormsg").hide();
		jQuery.ajax({
			type: "POST",
			url: "lead_add_schedulebatch.php",
			dataType: 'json',
			data:{'check': check,'name':name},
			success: function(data){
			console.log(data);

			if(data.msg=='Add'){
                $('.leadbatch').prop('disabled', true);
				$("#batchname").val('');
				$(".succmsg").show();
				setTimeout(function(){
				   $("#overlay").hide();
					$("#batchform").hide();
					$(".succmsg").hide();
				}, 5000);
			}

			else{
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

        var check=$("#ckeckvall").val();
        filename="customsearch.csv"
        if(check){

            jQuery.ajax({
                type: "POST",
                url: "lead_export.php",
               
                 data:{'check': check},
                 success: function(response){
                // console.log(response);
                 var type = 'application/csv';
        var blob = new Blob([response], { type: type });

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

            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
        }
    
               /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
                window.open(uri, 'test.csv');*/

                 }
                });


        }
        else {

         alert("Please select at least one check box");
         return false;
        }
 
    });   


/* For schedule page export csv*/

	 $("#schexpcsvbtn").click(function() {

        var check=$("#ckeckvall").val();
        filename="customsearch.csv"
        if(check){

            jQuery.ajax({
                type: "POST",
                url: "lead_schedule_exportdata.php",
               
                 data:{'check': check},
                 success: function(response){
                // console.log(response);
                 var type = 'application/csv';
        var blob = new Blob([response], { type: type });

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

            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
        }
    
               /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
                window.open(uri, 'test.csv');*/

                 }
                });


        }
        else {

         alert("Please select at least one check box");
         return false;
        }
 
    }); 

});
function getValueUsingClass(){
var chkArray = [];
	
	/* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
	$(".chk:checked").each(function() {
		chkArray.push($(this).val());
	});
	
	/* we join the array separated by the comma */
	var selected;
    selected = chkArray.join(',') ;
    
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
	var uname=$("#username").val();
	 var password=$("#password").val();

	 //alert(uname);  alert(password); 

		jQuery.ajax({
			type: "POST",
			url: "signin.php",
			dataType: 'json',
			data:{'uname': uname,'password':password},
			success: function(data){
			console.log(data);

			if(data.msg=='notuser'){
				$(".error").show();
				$(".blnkerror").hide();
				$(".box").css("border", "1px solid red");
			}

			else if(data.msg=='user'){
				window.location.href = "index.php";
			}

			else if(data.msg=='blank'){
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
function deletebatch(id){
	if(confirm("Are you sure you want to delete this?")){
		jQuery.ajax({
				type: "POST",
				url: "lead_delete_schedulebatch.php",
				dataType: 'json',
				data:{'id': id},
				success: function(data){
				console.log(data);
					alert( "Batch deleted successfully" );
					 location.reload();
				}
			});
	}

	else{
	 return false;
	}
}
 function exportschedule(id){
    filename="scheduledsearch.csv"
    jQuery.ajax({
        type: "POST",
        url: "lead_export_scheduledata.php",
       
         data:{'id': id},
         success: function(response){
        // console.log(response);
         var type = 'application/csv';
var blob = new Blob([response], { type: type });

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

    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
}

       /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
        window.open(uri, 'test.csv');*/

         }
        });
  return flase;

 }


 jQuery(document).ready(function(){
 var schid=$('#reschid').val();
 if(schid){
     
    jQuery.ajax({
        type: "POST",
        url: "lead_reschedule_search.php",
        dataType: 'json',
         data:{'id':schid},
         success: function(data){
        if(data.status=="success"){
            
            $('#nouf').val(data.result.nouf);
            $('#nout').val(data.result.nout);
            $('#nbedf').val(data.result.nbedf);
            $('#nbedt').val(data.result.nbedt);
            $('#nbathf').val(data.result.nbathf);
            $('#nbatht').val(data.result.nbatht);
            $('#nstrf').val(data.result.nstrf);
            $('#nstrt').val(data.result.nstrt);
            $("input[name=fmlytype][value='"+data.result.fmlytype+"']").prop("checked",true);
            $("input[name=sfmlytype][value='"+data.result.sfmlytype+"']").prop("checked",true);
           
            var citydata=data.result.city;
            if (citydata.indexOf(',') > -1) {
            var valArr =citydata.split(',');
            }
            else {
                var valArr =citydata;

            }
            
           
            var zipdata=data.result.zip;
            if (zipdata.indexOf(',') > -1) {
            var zipArr=zipdata.split(',');
            }
            else {
                var zipArr=zipdata;
            }

            
      
          
           
            
             i = 0, size =valArr.length;
            if(size>0){
           for(i; i < size; i++){
              $('#city_to').append("<option value='"+valArr[i]+"'>"+valArr[i]+"</option>");
               $("#city option[value='"+valArr[i]+"']").remove();
             }
            } 

             i = 0, zsize =zipArr.length;
             if(zsize>0){
             for(i; i < zsize; i++){
                $('#zip_to').append("<option value='"+zipArr[i]+"'>"+zipArr[i]+"</option>");
                 $("#zip option[value='"+zipArr[i]+"']").remove();
               }
            }    
         // console.log(data.result.nbedf)


        }

         }
        });
 }

 });


 function leadbatchexport(id){

    filename="leadexport.csv"
    jQuery.ajax({
        type: "POST",
        url: "lead_exportcsv.php",
       
         data:{'id': id},
         success: function(response){
        // console.log(response);
         var type = 'application/csv';
var blob = new Blob([response], { type: type });

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

    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
}

       /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
        window.open(uri, 'test.csv');*/

         }
        });
  return flase;
 }

 function deleteschedule(id){
    if(confirm("Are you sure you want to delete this?")){
		jQuery.ajax({
				type: "POST",
				url: "lead_delete_schedule.php",
				dataType: 'json',
				data:{'id': id},
				success: function(data){
				console.log(data);
					alert( "Batch deleted successfully" );
					 location.reload();
				}
			});
	}

	else{
	 return false;
	}

 }

/*  for delete batches*/


function updatepfilter(){

    if(confirm("Are you sure you want to update this?")){
    var m_data=$('#cdsearchform').serialize();
    jQuery.ajax({
        type: "POST",
        url: "lead_updateproperty.php",
        dataType: 'json',
        data:m_data,
        success: function(data){
        if(data.msg="success"){

            alert( "Data updated successfully" );
        }

        }


    });
    return false;
}
else {

    return false;
}


}




function cdeletebatch(id){
	if(confirm("Are you sure you want to delete this?")){
		jQuery.ajax({
				type: "POST",
				url: "lead_delete_custombatch.php",
				dataType: 'json',
				data:{'id': id},
				success: function(data){
				console.log(data);
					alert( "Batch deleted successfully" );
					 location.reload();
				}
			});
	}

	else{
	 return false;
	}
}


function cleadbatchexport(id){

    filename="leadexport.csv"
    jQuery.ajax({
        type: "POST",
        url: "lead_export_custombatch.php",
       
         data:{'id': id},
         success: function(response){
        // console.log(response);
         var type = 'application/csv';
var blob = new Blob([response], { type: type });

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

    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
}

       /* var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(data);
        window.open(uri, 'test.csv');*/

         }
        });
  return flase;
 }
function customschedulebatch(id){
    if(confirm("Are you sure you want to reschedule batch search?")){

        jQuery.ajax({
            type: "POST",
            url: "lead_schedule_custombatch.php",
            dataType: 'json',
            data:{'id': id},
            success: function(data){
            if(data.msg=="success"){
            
              alert('Reschedule batch search successfully');
            }

            }
        });

        return false;
    }
    else {


        return false;
    }

}


 function reschedulebatch(id){

    if(confirm("Are you sure you want to reschedule batch search?")){
        
        jQuery.ajax({
            type: "POST",
            url: "lead_reschedulebatch.php",
            dataType: 'json',
            data:{'id': id},
            success: function(data){
            if(data.msg=="success"){
            
              alert('Reschedule batch search successfully');
            }

            }
        });

        return false;
    }
    else {

        return false;
    }

    
    
 }



 function opencasedetail(apn, cid,ctyid){
 
		jQuery.ajax({
				type: "POST",
				url: "lead_case_detail.php",
				data:{'apn': apn, 'case_id': cid,'case_det_id':ctyid},
				success: function(data){
				console.log(data);
				$('.casedata').html(data);

					//alert( "Batch deleted successfully" );
					 //location.reload();
				}
			});
	 return false;
}


function liveopencasedetail(apn, cid, ctype){
$('.wait').show();
	//alert(apn); alert(cid); alert(ctype);
		jQuery.ajax({
				type: "POST",
				url: "livecasedetail.php",
				data:{'apn': apn, 'case_id': cid, 'case_type': ctype},
				success: function(data){
				console.log(data);
				$('.wait').hide();
				$('.casedata').html(data);

					//alert( "Batch deleted successfully" );
					 //location.reload();
				}
			});
	 return false;
}





function timesearch(id){
    $("#overlay").show();
    $("#batchform").show();
    var batchid=id;
    $("#batchid").val(batchid);

	//$('#sttask').datetimepicker({format:'m/d/Y',timepicker:false});

	 $.ajax({ 
        type: "POST",
        url: "lead_get_scheduletask.php", 
		dataType: 'json',
        data:{'batchid': batchid},               
        success: function(response){                    
           console.log(response);
		   if(response.msg=='alredyadd'){
			   $(".taskdelete").show();
				$("#taskname").val(response.result.taskname);
				$("#sttask").val(response.result.date);
				var l=response.result.period;
				if (l=="daily"){$("#taskintervald").prop("checked", true);}
				else if(l=="weekly"){$("#taskintervalw").prop("checked", true);}
				else if(l=="monthly"){$("#taskintervalm").prop("checked", true);}
				else if(l=="yearly"){$("#taskintervaly").prop("checked", true);}
				$(".taskdelete").show();
				$(".etime").hide();
				$(".btntext").html('');
				$(".btntext").html('Update');
				$(".succmsg").html('');
				$(".succmsg").html('Task Update successfully.');
				
		   }
		   else{
				$(".btntext").html('');
				$(".btntext").html('Submit');
				$(".taskdelete").hide();
		   }
        }

    });


 }
  
  
  
   function tasksubmit(){
		var taskname=$("#taskname").val();
		var taskdate=$("#sttask").val();
		var checked = $("input[type=radio]:checked").length;

      if(taskname==null || taskname==""){
		  $(".etask").show();
		  $("#taskname").css("border", "1px solid red");
		  return false;
	  }else{
	   $("#taskname").css("border", "1px solid #ccc");
	  }

	 if(taskdate==null || taskdate==""){
		  $(".edate").show();
		  $("#sttask").css("border", "1px solid red");
		  return false;
	  }else{
	   $("#sttask").css("border", "1px solid #ccc");
	  }


	 if(!checked) {
		$(".etime").show();
        return false;
     }

	  else{

          jQuery.ajax({
              type: "POST",
              url: "lead_add_scheduletask.php",
              dataType: 'json',
              data:$("#schtask input").serialize(),
              success: function(data){
              console.log(data);
  
              if(data.msg=='Add'){
                  $("#taskname").val('');
				  $("#sttask").val('');
				  $(".etime").hide();
                  $(".succmsg").show();
				  $("#taskintervald").prop("checked", false);
					$("#taskintervalw").prop("checked", false);
					$("#taskintervalm").prop("checked", false);
					$("#taskintervaly").prop("checked", false);
                    setTimeout(function(){
                     $("#overlay").hide();
                     // $("#batchform").hide();
                      $(".succmsg").hide();
                  }, 5000);
              }
  
              else{
				  $(".succmsg").html('');
					$(".succmsg").html('Task Update successfully.');
					$(".succmsg").show();
                    setTimeout(function(){
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




   function taskdelete(){
          jQuery.ajax({
              type: "POST",
              url: "lead_stop_scheduler.php",
              dataType: 'json',
              data:$("#schtask input").serialize(),
              success: function(data){
              console.log(data);
              if(data.msg=='delete'){
                 $(".succmsg").html('');
				$(".succmsg").html('Task remove successfully.');
				$(".succmsg").show('');
				$("#taskname").val('');
				$("#sttask").val('');
				$("#taskintervald").prop("checked", false);
				$("#taskintervalw").prop("checked", false);
				$("#taskintervalm").prop("checked", false);
				$("#taskintervaly").prop("checked", false);
				setTimeout(function(){
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


function customtimesearch(id,name){
    $("#overlay").show();
    $("#batchform").show();
    var curr=moment().format('MM/DD/YYYY hh:mm A');  
    var batchid=id;
    var batchname=name;
    $("#batchid").val(batchid);
    $("#taskname").val(batchname);

	//$('#sttask').datetimepicker({format:'m/d/Y',timepicker:false});

	 $.ajax({ 
        type: "POST",
        url: "lead_schedule_customtask.php", 
		dataType: 'json',
        data:{'batchid': batchid},               
        success: function(response){                    
           console.log(response);
		   if(response.msg=='alredyadd'){
			   $(".taskdelete").show();
				$("#taskname").val(response.result.taskname);
				$("#sttask").val(response.result.date);
				var l=response.result.period;
				if (l=="daily"){$("#taskintervald").prop("checked", true);}
				else if(l=="weekly"){$("#taskintervalw").prop("checked", true);}
				else if(l=="monthly"){$("#taskintervalm").prop("checked", true);}
				else if(l=="yearly"){$("#taskintervaly").prop("checked", true);}
				$(".btntext").html('');
				$(".btntext").html('Update');
				$(".succmsg").html('');
				$(".succmsg").html('Task Update successfully.');
				
		   }
		   else{
				$(".btntext").html('');
				$(".btntext").html('Submit');
				$(".taskdelete").hide();
				$("#sttask").val(curr);
				
		   }
        }

    });


 }
  
  
  
   function customtasksubmit(){
	   var taskname=$("#taskname").val();
		var taskdate=$("#sttask").val();
		var checked = $("input[type=radio]:checked").length;

      if(taskname==null || taskname==""){
		  $(".etask").show();
		  $("#taskname").css("border", "1px solid red");
		  return false;
	  }else{
	   $("#taskname").css("border", "1px solid #ccc");
	  }

	 if(taskdate==null || taskdate==""){
		  $(".edate").show();
		  $("#sttask").css("border", "1px solid red");
		  return false;
	  }else{
	   $("#sttask").css("border", "1px solid #ccc");
	  }

	 if(!checked) {
		$(".etime").show();
        return false;
     }
	  else{
          jQuery.ajax({
              type: "POST",
              url: "lead_add_customtask.php",
              dataType: 'json',
              data:$("#schtask input").serialize(),
              success: function(data){
              console.log(data);
              if(data.msg=='Add'){
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
               }
  
              else{
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




  function customtaskdelete(){
          jQuery.ajax({
              type: "POST",
              url: "lead_delete_customtask.php",
              dataType: 'json',
              data:$("#schtask input").serialize(),
              success: function(data){
              console.log(data);
              if(data.msg=='delete'){
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



$(document).ready(function(){
    $("#taskname").keyup(function(){
         $(".etask").hide();
		  $("#taskname").css("border", "1px solid #ccc;");
    });

	$("#sttask").blur(function(){
         $(".edate").hide();
		  $("#sttask").css("border", "1px solid #ccc;");
    });
});


$(document).ready(function(){

    $.urlParam = function(name){

        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);

         if(results !=null)
         return results[1] || 0;

    }

    var apn=$.urlParam('apn');

    var cid=$.urlParam('case_id');

    if(apn && cid){

        jQuery.ajax({

            type: "POST",

            url: "lead_case_detail.php",

            data:{'apn': apn, 'case_id': cid},

            success: function(data){

            console.log(data);

            $('.casedata').html(data);



                //alert( "Batch deleted successfully" );

                 //location.reload();

            }

        });

     return false;

        

    }



});


function checkcasetype(a){

   var check= $('#opcty' + a).is(":checked");

   if(check){

$('.codate'+ a).attr('disabled', false);

$('.cotime'+ a).attr('disabled', false);



   }

   else {

    $('.codate'+ a).attr('disabled', true);

    $('.cotime'+ a).attr('disabled', true);

    $('.codate'+ a).val('');

    $('.cotime'+ a).val('');



   }


}


function  openctime(a){
    var cdate=$('.codate'+ a).val();
    if(cdate.length>0){
     $('.cotime'+ a).val('');
    }
   }
function opendtime(a){
    var ctime=$('.cotime'+ a).val();
if(ctime.length>0){
$('.codate'+ a).val('');
}
}

jQuery( document ).ready(function( $ ) {
$("#filtername").click(function(){
  $("#filtername").val('');
});


$('.number').keyup(function(e) {
if (/\D/g.test(this.value)){
// Filter non-digits from input value.
this.value = this.value.replace(/\D/g, '');
}


});

$('.cnumber').keyup(function(e) {
if (/\D/g.test(this.value)){
// Filter non-digits from input value.
this.value = this.value.replace(/\D/g, '');
}


});


});


function scrapperinst(btnval){
      
    var total_instances=$('#scp_total_instances').val();
    var total_records=$('#scp_total_records').val();
    var scrapperid=$('#scrapperid').val();
    var submitval=btnval;
    if((total_instances.length==0)){
        alert("Please enter total instance");
        $("#scp_total_instances").focus();
        return false;
    }
    else if ((total_records.length==0)) {
        alert("Please enter and number of records");
        $("#scp_total_records").focus();
        return false
    }


    jQuery.ajax({

        type: "POST",
        url: "leads_scrapper_data.php",
        dataType: 'json',
        data:{'total_instances': total_instances, 'total_records': total_records,'scrapperid':scrapperid,'submit':submitval},

        success: function(data){
          console.log(data.status);
           if(data.status=="save"){
               alert("Save data successfully");

               location.reload();
           }else if(data.status=="remove") {

            alert("Remove data successfully");
            location.reload();
           }

        }
    });


return false;

    };


function startaudit(a){
    batchid=a;
    if(confirm("Are you sure you want to start audit?")){
        jQuery.ajax({

            type: "POST",
            url: "lead_audit_start.php",
            dataType: 'json',
            data:{'batchid': batchid},
            success: function(data){

                if(data.status="success"){
                    alert("audit scheduled successfully");

                }else {
                    alert("audit not scheduled");

                }

            }
        });

        return false;
    }else {

        return false;
    }



    }




    function createallscheduler(){



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

    function stopscheduler(){

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

    function customallschsubmit(){
        var allstask=$("#allstask").val()
        var alltaskinterval=$("#alltaskinterval").val();
        var m_data=$('#createallbatch').serialize();
        if($('#createallbatch').find('input[type=checkbox]:checked').length == 0)
    {
        alert('Please select atleast one checkbox');
        return false;
    }

    if(alltaskinterval && allstask ){
        
        jQuery.ajax({

            
            type: "POST",
    
            url: "lead_create_schedule.php",
    
            dataType: 'json',
    
            data:m_data,
    
            success: function(data){

                if(data.msg="Add"){

                    alert("Selected scheduler created successfully");
    
                    location.reload();
    
    
    
                }else {
    
    
    
                    alert("error create selected scheduler ");
    
                }


            }


        });

    }else {

        alert("Please enter start task and task interval ")
    }
       
   return false;

    }

    function stopallschsubmit(){
        var m_data=$('#stopschtask').serialize();
       
        if($('#stopschtask').find('input[type=checkbox]:checked').length == 0)
    {
        alert('Please select at least one checkbox');
        return false;
    }


        if(confirm("Are you sure you want to stop selected scheduler ?")){
           
            jQuery.ajax({



                type: "POST",

                url: "lead_stop_all_scheduler.php",

                dataType: 'json',

                data:m_data,

                success: function(data){

    

                    if(data.status="delete"){

                        alert("Selected scheduler stop successfully");

                        $("#overlay3").hide();

		               $("#allschedulerform").hide();

                        location.reload();

                    }

    

                }

            });

    

            return false;


        }else{

            return false;

        }



    }
