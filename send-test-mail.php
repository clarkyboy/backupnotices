<?php 
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$sendtitleid = $_POST['sendtitleid'];
    $relatedtitleid = $_POST['relatedtitleid'];
	
	
	$notices->send_test_mail($sendtitleid,$relatedtitleid);
?>