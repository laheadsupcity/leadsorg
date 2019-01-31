<?php 
require_once('config.php');
$db = Database::instance();
$total_instances=isset($_POST['total_instances']) ? $_POST['total_instances'] : '';
$total_records=isset($_POST['total_records']) ? $_POST['total_records'] : '';
$submitval=isset($_POST['submit']) ? $_POST['submit'] : '';
$scrapperid=isset($_POST['scrapperid']) ? $_POST['scrapperid'] : '';
if($submitval=="submit"){

    $db->insert(
    	'scrapper_instance',
    	array(
    		'total_instances' => $total_instances,
            'total_records' => $total_records,
            'status'=>1
    		)
    );
    $response=array('status'=>'save');
    $command = escapeshellcmd("sudo /var/www/html/leads/python/InstanceCreator.py" );
    $command_output = shell_exec($command);
}
else {

    $db->delete('scrapper_instance',$where=array('id'=>$scrapperid));
    $response=array('status'=>'remove');
}
echo json_encode($response);
exit();
?>