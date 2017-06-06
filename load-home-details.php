<?php
	session_start();
    include('classes/notices_controllers.php');
    $notices = new Notices;

   $totalSent = 0;
   $totalNotSent = 0;
   echo json_encode($notices->notice_details());
?>

