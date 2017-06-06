<?php
	include('classes/notices_controllers.php');
    $notices = new Notices();
    
    $val = $_POST['val'];
    $isp = $_POST['ispname'];
    $notices->updateISPInformation($val,$isp);
?> 