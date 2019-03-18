<?php
require_once('config.php');
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Scraping | Login Form</title>
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/multiselect.js"></script>
   <script type="text/javascript" src="js/myscr.js"></script>
  <link rel="stylesheet" href="css/style.css">
 </head>
 <body style="background:url(images/login.jpg); background-size:cover;">
<div class="scr1" style="max-width:700px; width:100%; margin:5% auto; height:500px; border:1px solid #fff; background:#fff; padding:20px 0;">
	<h1 style="text-align:center; border-bottom:2px solid #337ab7; background:#fff; color:#337ab7; margin:0; padding-bottom:2px;">Login Form</h1>
	<div class="login">
		<form  action="#" id="loginform" method="post" >
			<label><i class="fa fa-user" aria-hidden="true"></i> UserName</label> <input type = "text" name ="username" id ="username"class="box" autofocus="autofocus"><br /><br />
			<label><i class="fa fa-lock"></i>  Password</label><input type = "password" name ="password" id ="password"  class="box"><br/><br />
			<button type="submit" id="loginbtn" class="btn btn-block">Login</button>
		</form>
		<p class="error" style="color:red; font-size:15px; margin:15px 0; display:none; font-style:italic;">Username and password is wrong, Please enter valid detail.</p>
		<p class="blnkerror" style="color:red; font-size:14px; margin:15px 0; display:none; font-style:italic;">Please enter vaild detail</p>
	</div>
</div>
 </body>
</html>
