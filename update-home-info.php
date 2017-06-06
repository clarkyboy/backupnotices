<?php 
	include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$atid = $_POST['at_id'];
    $id = $_POST['titleid'];
	$owner = $_POST['owner'];
	$contact = $_POST['contact'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$country = $_POST['country'];
	
	$notices->update_home($id,$owner,$contact,$address,$phone,$email,$country,$atid,$_POST);
	
	$user = $_POST['user'];
	$type = "rightholder";
	$desc = "Updated Right Holder of ".$_POST['isptitle']." information ";
	$notices->update_rights_holder($type,$desc,$user);
?>