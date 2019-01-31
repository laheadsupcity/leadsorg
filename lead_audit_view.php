<?php 
    require_once('config.php');

    function getscheduled_audit_task($id){

    $resarray=array();
    $db = Database::instance();
    $db->select('scrap_audit_history', array('batch_id' => $id), false, false,'','*');
    $result=$db->result_array();
    if(count($result)>0){
        $resarray=$result;
    }

    return $resarray;
    
    }
    $batchid=isset($_REQUEST['batchid']) ? $_REQUEST['batchid'] : '';
    $leadtask=getscheduled_audit_task($batchid);

function getbatchname($batchid){
		$name ="";
		$db = Database::instance();
		$db->select('batch', array('id' => $batchid), false, false,'','batchname');
		$result=$db->result_array();
		if(count($result >0)) {
		   foreach ($result as $key => $val) {
			 $name = $val['batchname'];
			}
		}
		   return $name;
	}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scrapper</title>
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
<h1 style="text-align:center;float:left;width:100%; margin:-10px 0 5px">Audit View</h1>
<div class="scr1">
    
    <div>
     <table class="table" cellspacing="3" cellpadding="3" rules="cols" id="MainContent_dgProperty" style="background-color:#337ab7;color:#fff;border-width:1px;border-style:None;font-family:Verdana;font-size:8pt;border-bottom:1px solid grey;">
         <tbody
         <tr>
             <th>scheduler#</th>
             <th>Batch Name</th>
             <th>Total Record</th>
             <th>Schedule Start Time</th>
             <th>Schedule End Time</th>
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
             <td><?php echo getbatchname($value['batch_id']); ?></td>
             <td><?php echo $value['total_record']; ?></td>
             <td><?php echo isset($value['start_date'])?date('m/d/Y h:i A',strtotime($value['start_date'])):''; ?></td>
             <td><?php echo isset($value['end_date'])?date('m/d/Y h:i A',strtotime($value['end_date'])):''; ?></td>
             <td><?php echo $value['status']; ?></td>
             <td><?php  echo date('m/d/Y h:i A',strtotime($value['createdate'])); ?></td>
         
             <td><a href='lead_audit_log_view.php?log=<?php echo $value['id']; ?>'>View log</a></td>
         </tr>
         <?php $idx++;}  ?>
     </table>
    </div>
</div>
</body>
</html>

