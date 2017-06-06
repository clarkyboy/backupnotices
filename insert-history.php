<?php 
	session_start();
	if(!isset($_SESSION['notices'])){die(1);}
	
	include('classes/notices_controllers.php');
    $notices = new Notices;
	$data = $_POST;
	
	if($_SESSION['notices']['access_value'] == 3){
		$type = 'user_activities';
		$desc = 'Disabled login on IP: <strong>' . $_POST['ip'] . '</strong>';
		$notices->update_rights_holder($type,$desc,$_SESSION['notices']['user']);	
	}
	else{
		echo $notices->update_rights_holder('rightholder', 'Disabled login on IP:' . $data['ip'], $_SESSION['notices']['user']);
	}
?> 