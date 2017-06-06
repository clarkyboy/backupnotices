<?php
	
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
	$shortcut = $_POST['shortcut'];
	
    $notices->update_email($name,$email,$contact,$address,$phone,$shortcut);

    $user = $_POST['user'];
	$type = "ispInfo";
	$desc = "Updated ISP ".$name." information";
	$notices->update_rights_holder($type,$desc,$user);
    
?>