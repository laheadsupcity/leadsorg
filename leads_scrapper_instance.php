<?php 
require_once('config.php');
include('datafunction.php');
$scrapper=getscrapper_data();
$instances_process=getinstances_data();
$status=isset($scrapper['status']) ? $scrapper['status'] : '0'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scraper</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/myscr.js"></script>
  <style>
	.active5{background:#337ab7!important;}
  </style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>

<div class="scr1">
    <form action="#" method="post" >
<div id="pagebody" align="center" style="max-width: 1300px;">

            <div class="panel panel-primary" style="width: 100%; border:0;" align="left">
                <div class="panel-heading">
                    <div>SCRAPPER INSTANCE</div>
                </div>
                <div class="panel-body">
                    

                    <div class="BigFont">Please enter all fields for create multiple instance that used in property scrapping:</div>


                        <br>
                        
                        <br>


                        <div>
                            <div class="col-md-3">
                                <label for="StreetNo" class="control-label ">Total Instance:</label>
                            </div>

                            <div class="col-md-4">
                                <input name="total_instances" required="required" value="<?php echo isset($scrapper['total_instances']) ? $scrapper['total_instances'] : ''; ?>" type="text" id="scp_total_instances" class="form-control cnumber" placeholder="Total Instance" />
                            </div>
                        </div>
                        <br>
                        <br>
                        <div>
                            <div class="col-md-3">
                                <label for="StreetName" class="control-label">Number of Records on Each Instance</label>
                            </div>
                            <div class="col-md-4">
                                <input name="total_records" required  value="<?php echo isset($scrapper['total_records']) ? $scrapper['total_records'] : ''; ?>" type="text" id="scp_total_records" class="form-control cnumber" placeholder="Number of Records on Each Instance" >
                            </div>
                        </div>
                       </br>
                       </br>
                        <div>
                            <div class="col-md-3">
                                <input type="hidden" name="scrapperid" id="scrapperid" value="<?php  echo isset($scrapper['id']) ? $scrapper['id'] : ''; ?>"/>
                             <?php  if($status){ ?>
                                
                                 <input type="submit" disabled name="BtnSubmit" value="Submit"  id="scrapperSubmit" class="btn btn-primary">
                                 <input type="submit" name="BtnSubmit" onclick="return scrapperinst('remove');" value="remove" id="removebtn" class="btn btn-primary">
                                 <?php   } else { ?>
                                <input type="submit"  name="BtnSubmit" onclick="return scrapperinst('submit');" value="submit" id="scrapperSubmit" class="btn btn-primary">
                                <?php } ?>
                                
                            </div>
                             <div class="col-md-9"></div>
                        </div>


                </div>
            </div>



        </div>
    </form>
    <div>
     <table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:#337ab7;color:#fff;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
         <tbody
         <tr>
             <th>scrapper#</th>
             <th>Total Records</th>
             <th>Complete Records</th>
             <th>Errors Records</th>
             <th>Process ID</th>
             <th>Running Status</th>
             <th>Created Date</th>
        </tr>
         </tbody>
   


         <?php foreach($instances_process as $key =>$value  ) {
          
             ?>
            <tr style="color:#000;background:#fff;font-weight:bold;height:30px;">
              <td><?php echo $value['id'] ;?></td>
             <td><?php echo $value['total_records']; ?></td>
             <td><?php echo $value['complete_records']; ?></td>
             <td><?php echo $value['errors_records']; ?></td>
             <td><?php echo $value['process_id']; ?></td>
             <td><?php  if($value['running_status']==1){echo "True" ; }else { echo "False"; }?></td>
             <td><?php  echo date('m/d/Y',strtotime($value['created_date']));?></td>

         </tr>
         <?php }  ?>

          
       
     </table>

    </div>
</div>

</body>
</html>
