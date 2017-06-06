<?php 
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$data = $_POST;
	echo $notices->insert_title($data);
?>