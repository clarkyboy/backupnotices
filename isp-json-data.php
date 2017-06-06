<?php
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $status = $_GET['status'];
    $titleid = $_GET['titleid'];
    if(isset($_GET['search']))
    { 
    	$s = $_GET['search'];
		
		$type = 'user_activities';
		$desc = 'Search <strong>' . $s . '</strong>';
		$user = $_SESSION['notices']['user'];
		$notices->update_rights_holder($type,$desc,$user);	
	
    	echo json_encode($notices->search_notice($titleid,$status,$s));
    }else{	
        if(!isset($_GET['country']))
        {
			$type = 'user_activities';
			$desc = 'Filter by country <strong>' . $_GET['country'] . '</strong>';
			$user = $_SESSION['notices']['user'];
			$notices->update_rights_holder($type,$desc,$user);	
		
            echo json_encode($notices->notice_not_sent($titleid,$status)); 
        }else{
            echo json_encode($notices->filter_by_country($titleid,$status,$_GET['country'])); 
        } 
    }	
?>
	