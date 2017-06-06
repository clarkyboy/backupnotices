<?php
include('classes/notices_controllers.php');
//include('header.php');
$notices = new Notices;
$sid = $_GET['statusID'];
$action = $_GET['action'];
//echo'<script>alert("Niagi sa statusPatternHandler");</script>';
$details=$notices->get_status_pattern_by_id($sid);//function call to get the data from notices_replypatterns table
$row = mysql_fetch_assoc($details);
//var_dump($details);
//echo'<script>alert("ID:'.$details[0]['ID'].'");</script>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Status Pattern</title>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.6.min.js"></script>
    
</head>
<body>
<!-- Hide if action == 'delete', show if action=='update' -->
	<div class="row" style="margin-top:5%; <?php echo($action == 'delete'?'display:none':''); ?>">
		<div class="container ">
			<div class="alert-success" style="padding:1%;margin-left:10%;margin-right:10%; margin-top:-5%;">
						<span>You Are About to <strong>Change</strong> a Geo IP ISP Reply Pattern and Status.</span><hr />
						<span><strong>Make Sure of the Changes</strong> you make before you click the <strong>Update</strong> button.
						Click the <strong>Back</strong> to return to the Status Pattern Management Page.</span>
					</div>
		</div>
		<div class="container">
		
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<form method="post" action="update_status.php">
					<div class="form-group ">
						<label>Geo IP ISP</label><input class="form-control" style="width:100%" type="text" readonly="readonly" name="geoipisp"  value="<?php echo $details[0]['GeoIpIsp'];?>" >
						<input class="form-control" style="width:100%; display:none;" type="text" name="sid" value="<?php echo $details[0]['ID'];?>" >
					</div>
					<div class="form-group ">
						<label>Pattern</label><textarea name="spattern" style="width:100%" rows="8"><?php echo $details[0]['Pattern'];?></textarea>
					</div>
					<div class="form-group ">
						<label>Status</label>
						<select name="sstatus" class="form-control" style="width:100%">
							<option <?php if($details[0]['Status']==='Successful') echo "selected";?> value="Successful">Successful</option>
							<option  <?php if($details[0]['Status']==='Not Successful') echo "selected";?> value="Not Successful">Not Successful</option>
						</select>
					</div>
					<div class="row">
							<div class="col-md-6">
								<input type="submit" name="submit" value="Update" class="btn btn-success btn-lg" style="width:100%;">
							</div>
							<div class="col-md-6">
								<a href="statusPaternManagement.php"><span class="btn btn-default btn-lg" style="width:100%">Back</span></a>
							</div>
						
						
						</div>

				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
	<!-- Hide if action == 'update', show if action=='delete' -->
	<div class="row" style="margin-top:5%; <?php echo($action == "update"?"display:none":""); ?>">
	
		<div class="container ">
			<div class="alert-danger" style="padding:1%;margin-left:10%;margin-right:10%; margin-top:-5%;">
						<span>You Are About to <strong>DELETE</strong> a Geo IP ISP Reply Pattern and Status.</span><hr />
						<span><strong>Are You SURE about this?</strong> click <strong>Delete</strong> to continue with the process
						or <strong>Back</strong> to return to the Status Pattern Management Page.</span>
					</div>
		</div>
	
		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					
					<form method="post" action="delete_status.php">
						<div class="form-group " style="padding-top:10%;">
							<label>Geo IP ISP</label><input class="form-control" style="width:100%" type="text" readonly="readonly" name="geoipisp"  value="<?php echo $details[0]['GeoIpIsp'];?>" />
							<input class="form-control" style="width:100%; display:none;" type="text" name="sid" value="<?php echo $details[0]['ID'];?>" >
						</div>
						<div class="form-group ">
							<label>Pattern</label><textarea readonly="readonly" name="spattern" style="width:100%" rows="8"><?php echo $details[0]['Pattern'];?></textarea>
						</div>
						<div class="form-group ">
							<label>Status</label><input type="text" readonly="readonly" name="spattern" style="width:100%" value="<?php echo $details[0]['Status'];?>" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<input type="submit" name="submit" value="Delete" class="btn btn-danger btn-lg" style="width:100%;">
							</div>
							<div class="col-md-6">
								<a href="statusPaternManagement.php"><span class="btn btn-default btn-lg" style="width:100%">Back</span></a>
							</div>
						
						
						</div>
					</form>
				</div>
				<div class="col-md-4"></div>
				</div>
				
		</div>
	</div>
</body>
</html>
