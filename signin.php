<?php
session_start();
require_once('config.php');
$uname=isset($_POST['uname']) ? $_POST['uname'] : '';
$password=isset($_POST['password']) ? $_POST['password'] : '';

if($uname!="" && $password!=""){

	$db->select('user_login', array('username' => $uname, 'password' => $password), false, false,'AND','*');
	$result=$db->result_array();
	$count= count($result);

	//error_log("======logincount=====>".print_r($count,true));

	if($count==0){
	$value = array('msg' => 'notuser');
	echo json_encode($value);
	}

	else{
	$value = array('msg' => 'user');
	$_SESSION["userdeatil"]=$result[0];
	echo json_encode($value);
	}

}

else{
$value = array('msg' => 'blank');
echo json_encode($value);
}


exit();
?>