<?php 
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$sendtitleid = $_POST['sendtitleid'];
    $relatedtitleid = $_POST['relatedtitleid'];
    $status = $_POST['status'];
	
	if($status==1){
		$notices->get_update_status($sendtitleid,$relatedtitleid,$status);
	}
	elseif($status==3){
		$notices->update_status($sendtitleid,$relatedtitleid,$status);
	}
	else{
		$notices->update_status($sendtitleid,$relatedtitleid,$status);
	}
?> 