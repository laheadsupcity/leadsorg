<?php
    require_once('config.php');
    $name=isset($_POST['name']) ? $_POST['name'] : '';
    $count= $db->select('scheduled_search', array('name' => $name))->count();
    if($count>0){
        $value = array('msg' => 'fail');
        
    }
    else{
        $value = array('msg' => 'pass');
        
    }
    echo json_encode($value);
    exit();

?>