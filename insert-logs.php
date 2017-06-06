<?php
	session_start();
    include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$type = $_POST['type'];
	$desc = $_POST['desc'];
	
	$user = $_SESSION['notices']['user'];
	$notices->update_rights_holder($type,$desc,$user);	
?>