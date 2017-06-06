<?php 
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $titleid = $_POST['titleid'];
    $country = isset($_POST['country'])? $_POST['country'] : '';
    echo json_encode($notices->get_home_other_info($titleid,$country));
?>