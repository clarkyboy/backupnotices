<?php
	include('classes/notices_controllers.php');
    $notices = new Notices;
    
    $ispname = $_POST['ispname'];
    $status = $_POST['status'];
    $notices->change_isp_status($ispname,$status);

    $user = $_POST['user'];
	$type = "ispStatus";
	$desc = "";
	if($status == 0)
	{
		$desc = "Deactivated ISP ".$ispname;
	}else{
		$desc = "Activated ISP ".$ispname;
	}	
	$notices->update_rights_holder($type,$desc,$user);
?>
	