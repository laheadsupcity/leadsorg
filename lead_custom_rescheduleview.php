<?php 
    require_once('config.php');
    function getscheduled_lead_task($id){

    $resarray=array();
    $db = Database::instance();
    $db->select('scheduled_lead_task', array('batchid' => $id), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result;
    }

    return $resarray;
    
    }
    $batchid=isset($_REQUEST['view']) ? $_REQUEST['view'] : '';
    $leadtask=getscheduled_lead_task($batchid);


    
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
	.active1{background:#337ab7!important;}
  </style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Rescheduled View</h1>
<div class="scr1">
    
    <div>
     <table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:#337ab7;color:#fff;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
         <tbody
         <tr>
             <th>scheduler#</th>
             <th>Task</th>
             <th>Task Schedule Date</th>
             <th>Schedule Start Time</th>
             <th>Schedule End Time</th>
             <th>Period</th>
             <th>Status</th>
             <th>Created Date</th>
             <th>Log View</th>
        </tr>
         </tbody>
         
         <?php
         $idx = 1;
         foreach($leadtask as $key =>$value  ) {
         ?>
            <tr style="color:#000;background:#fff;font-weight:bold;height:30px;">
              <td><?php echo $idx ;?></td>
             <td><?php echo $value['taskname']; ?></td>
             <td><?php echo date('m/d/Y h:i A',strtotime($value['starttask'])); ?></td>
             <td><?php echo isset($value['scheduled_start_time'])?date('m/d/Y h:i A',strtotime($value['scheduled_start_time'])):''; ?></td>
             <td><?php echo isset($value['scheduled_end_time'])?date('m/d/Y h:i A',strtotime($value['scheduled_end_time'])):''; ?></td>
             <td><?php echo $value['period']; ?></td>
             <td><?php echo $value['status']; ?></td>
             <td><?php  echo date('m/d/Y h:i A',strtotime($value['createdate'])); ?></td>
             <?php if($value['status']=='Complete'){ ?>
             <td><a href='lead_reschedule_log_view.php?log=<?php echo $value['id']; ?>'>View log</a></td>
              <?php } else { ?>  
              <td><a href='#'>View log</a></td>
              <?php } ?>
         </tr>
         <?php $idx++;}  ?>
     </table>
    </div>
</div>
</body>
</html>

