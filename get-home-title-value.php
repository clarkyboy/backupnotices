<?php 
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $title = $_POST['title'];
    echo json_encode($notices->get_home_title_info($title));
?>