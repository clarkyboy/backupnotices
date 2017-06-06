<?php
	include('classes/notices_controllers.php');
	$notices = new Notices;
	$geoipisp = isset($_POST['geoipisp'])?$_POST['geoipisp']:"";
	$id = isset($_POST['sid'])?$_POST['sid']:"";
	$spattern = isset($_POST['spattern'])?$_POST['spattern']:"";
	$sstatus = isset($_POST['sstatus'])?$_POST['sstatus']:"";
	//echo "Geo IP ISP: $geoipisp <br />";
	//echo "ID: $id <br />";
	//echo "Pattern: $spattern <br />";
	//echo "Status: $sstatus <br />";
	$result = $notices->delete_status_pattern($id);// function call to delete data from table
	//var_dump($result);
	/*
	if($result) 
		{
			echo'<script>alert("Update Successful");</script>';
			header('location:statusPaternManagement.php');
		}
	else
		{
			echo'<script>alert("Update Failed");</script>';
			header('location:statusPaternManagement.php');
		}
	*/
?>
<html>
<head>
	<title>Delete Status Pattern</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.6.min.js"></script>
    
</head>
<body>
	<div class="row" style="margin-top:5%;">

		<div class="container">
		
			<div class="col-md-4"></div>
			<!-- if delete operation is successful, display this div otherwise, hide -->
			<div class="col-md-4" style="<?php if(!$result) echo'display:none;';?>">
				<div class="panel panel-success">
				  <div class="panel-heading"><h2 class="text-center">Delete Operation Successful</h2></div>
				  <div class="panel-body">
				  	<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQvNzw9Y1pupY6Pi83evc5lI88w2IP97tqAEH-xd-j1OjbUPYR0Fg"
				  		 style="margin-left:auto !important; margin-right:auto !important; padding:5%; width:100%; "><br />
				    <a href="statusPaternManagement.php"><button class="btn btn-lg btn-success" style="width:100%;">Continue</button></a>
				  </div>
				</div>
			</div>
			<!-- if delete operation is not successful, display this div otherwise, hide -->
			<div class="col-md-4" style="<?php if($result) echo'display:none;';?>">
				<div class="panel panel-danger">
				  <div class="panel-heading"><h2 class="text-center">Delete Operation Failed</h2></div>
				  <div class="panel-body">
				    <img src="https://img.clipartfest.com/0ad7cf3ecb661eb7d55ee3b4dc3363ef_outlook-clipart-red-x-x-clipart-png_900-900.png"
				  		 style="margin-left:auto !important; margin-right:auto !important; padding:5%; width:100%; ">
				    <a href="statusPaternManagement.php"><button class="btn btn-lg btn-danger" style="width:100%;">Continue</button></a>
				  </div>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
</body>
</html>
